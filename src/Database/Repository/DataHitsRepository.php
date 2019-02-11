<?php
namespace App\Database\Repository;

use App\Database\Tables\DataHits;
use App\Entity\DataHit;
use App\Filters\HitsStatDtoFilter;

class DataHitsRepository extends AbstractRepository
{
    public function getMaxExtId()
    {
        $hitsTable = new DataHits();

        $statement = $this->getAdapter()->select('select max(external_id) as max_id from ' . $hitsTable->getTableName());
        $row = $statement->fetchOne();
        if($row) {
            return $row['max_id'];
        } else {
            return 0;
        }
    }

    public function getHitByClickId($clickId){
        //$hitsTable = new DataHits();
        $query = 'select raw from data_hits where click_id = '. "'".$clickId."'" . ' AND event_date = ( select event_date from data_hits where click_id = '. "'".$clickId."'" . ' limit 1)';
        $statement = $this->adapter->select($query);
        $row = $statement->fetchOne();
        return $row['raw'];
    }

    public function getStat(HitsStatDtoFilter $filter){

        switch ($filter->groupField) {
            case 'sub1':
            case 'sub2':
            case 'sub3':
            case 'sub4':
            case 'sub5':
                $selectFields = $filter->groupField. ' as title,';
                $groupFields = 'dh.' . $filter->groupField;
                $filterField = $filter->groupField. ' as filter';
                break;
            case 'flow':
                $selectFields = 'dh.flow_id  as title,';
                $groupFields = 'dh.flow_id';
                $filterField = 'dh.flow_id as filter';
                break;
            case 'offer':
                $selectFields = 'dh.offer_id  as title,';
                $groupFields = 'dh.offer_id';
                $filterField = 'dh.offer_id as filter';
                break;
            case 'path':
                $selectFields = 'array(dh.pl_id, dh.lp_id) as title,';
                $groupFields = 'dh.pl_id, dh.lp_id';
                $filterField = "CONCAT(toString(dh.pl_id), '-', toString(dh.lp_id)) as filter";
                break;
            case 'date':
            default:
                $selectFields = 'dh.event_date as title,';
                $groupFields = 'dh.event_date';
                $filterField = 'dh.event_date as filter';
        }
        $where = [];
        if($filter->webmaster){
            if(!is_array($filter->webmaster)){
                $webmasterArray = array($filter->webmaster);
            } else {
                $webmasterArray = $filter->webmaster;
            }
            $where[] = "dh.webmaster_id IN(" . implode(', ', $webmasterArray) . ")";
        }
        $where[] = "dh.webmaster_currency_id = " . $filter->currencyId;
        $where[] = "dh.event_date BETWEEN toDate('" . $filter->dateFrom . "') AND toDate('" . $filter->dateTo . "')";
        $subs = array(
            'sub1',
            'sub2',
            'sub3',
            'sub4',
            'sub5'
        );
        foreach($subs as $sub){
            if($filter->$sub != ''){
                $where[] = "dh." . $sub . " = '" . $filter->$sub . "'";
            }
        }
        /*if(!is_null($filter->mobile)){
            $where[] = "dh.mobile = " . $filter->mobile;
        }*/
        if(!empty($filter->offerId)){
            if(is_array($filter->offerId)){
                $where[] = 'dh.offer_id IN(' . implode(', ', $filter->offerId) . ')';
            } else {
                $where[] = 'dh.offer_id = ' . $filter->offerId;
            }
        }
        if($filter->flowId > 0){
            $where[] = 'dh.flow_id = ' . $filter->flowId;
        }
        
        $query = "SELECT " . $selectFields . ' array ( sum(is_visited_pl) as pl, sum(is_visited_lp) as lp ) as hits, '.$filterField;
        $query .= " FROM " . (new DataHits())->getTableName() . " dh FINAL";
        if($where){
            $where = implode(') AND (', $where);
            $query .= " WHERE (" . $where . ")";
        }
        $query .= " GROUP BY " . $groupFields . " ORDER BY " . $groupFields . " DESC";

        $dbResult = $this->adapter->select($query);

        return $dbResult->rows();
    }
}