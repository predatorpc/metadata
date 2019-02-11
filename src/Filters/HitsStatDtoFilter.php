<?php
namespace App\Filters;

class HitsStatDtoFilter
{
    public $webmaster = false;
    public $dateFrom = '';
    public $dateTo = '';
    public $flowId = 0;
    public $offerId = array();
    public $groupField = 'data';
    public $sub1;
    public $sub2;
    public $sub3;
    public $sub4;
    public $sub5;
    public $currencyId = false;
    //public $mobile;

    public static function load(array $data){
        $filter = new self;
        if(array_key_exists('webmaster', $data) && $data['webmaster']) $filter->webmaster = $data['webmaster'];
        if(array_key_exists('dateFrom', $data) && $data['dateFrom']) $filter->dateFrom = $data['dateFrom'];
        if(array_key_exists('dateTo', $data) && $data['dateTo']) $filter->dateTo = $data['dateTo'];
        if(array_key_exists('flowId', $data) && $data['flowId']) $filter->flowId = $data['flowId'];
        if(array_key_exists('offerId', $data) && $data['offerId']) $filter->offerId = $data['offerId'];
        if(array_key_exists('groupField', $data) && $data['groupField']) $filter->groupField = $data['groupField'];
        if(array_key_exists('sub1', $data) && $data['sub1']) $filter->sub1 = $data['sub1'];
        if(array_key_exists('sub2', $data) && $data['sub2']) $filter->sub2 = $data['sub2'];
        if(array_key_exists('sub3', $data) && $data['sub3']) $filter->sub3 = $data['sub3'];
        if(array_key_exists('sub4', $data) && $data['sub4']) $filter->sub4 = $data['sub4'];
        if(array_key_exists('sub5', $data) && $data['sub5']) $filter->sub5 = $data['sub5'];
        if(array_key_exists('currencyId', $data) && $data['currencyId']) $filter->currencyId = $data['currencyId'];
       // if(array_key_exists('mobile', $data) && !is_null($data['mobile']) && $data["mobile"] !== "") $filter->mobile = $data['mobile'];

        return $filter;
    }
}