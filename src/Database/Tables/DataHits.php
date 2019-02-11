<?php
namespace App\Database\Tables;

class DataHits extends Table
{
    public function getTableName(){
        return 'data_hits';
    }

    public function getColumns(){
        return [
            //'id'            => self::COLUMN_INT64,
            'event_date'    => self::COLUMN_DATE,
            'ip'            => self::COLUMN_STRING,
            'cookie'        => self::COLUMN_STRING,
            'ua'            => self::COLUMN_STRING,
            //'url'           => self::COLUMN_STRING,
            'referer'       => self::COLUMN_STRING,
            'click_id'      => self::COLUMN_STRING,
            'flow_hash'     => self::COLUMN_STRING,
            'flow_id'       => self::COLUMN_INT64,
            'sub1'          => self::COLUMN_STRING,
            'sub2'          => self::COLUMN_STRING,
            'sub3'          => self::COLUMN_STRING,
            'sub4'          => self::COLUMN_STRING,
            'sub5'          => self::COLUMN_STRING,
            'raw'           => self::COLUMN_STRING,
            //'mobile'        => self::COLUMN_INT32,
            'tm'            => self::COLUMN_INT32,
            'offer_id'      => self::COLUMN_INT64,
            'webmaster_currency_id' => self::COLUMN_INT32,
            'webmaster_id'  => self::COLUMN_INT32,
            'pl_id'         => self::COLUMN_INT64,
            'pl_url'        => self::COLUMN_STRING,
            'pl_time'       => self::COLUMN_DATETIME,
            'is_visited_pl' => self::COLUMN_UINT8,
            'lp_id'         => self::COLUMN_INT64,
            'lp_url'        => self::COLUMN_STRING,
            'lp_time'       => self::COLUMN_DATETIME,
            'is_visited_lp' => self::COLUMN_UINT8,
            'external_id'   => self::COLUMN_INT64,
        ];
    }

    public function getEngine()
    {
        return 'ReplacingMergeTree(event_date, (event_date, click_id, flow_hash, flow_id, offer_id, webmaster_currency_id), 8192)';
    }
}