<?php

namespace App\Entity;

use App\App;
use App\Database\IDbManager;

class DataHit extends Entity
{
    //public $id = null;
    public $event_date;
    public $ip;
    public $cookie;
    public $ua;
    public $referer;
    //public $url;
    public $click_id;
    public $flow_hash;
    public $flow_id;
    public $sub1;
    public $sub2;
    public $sub3;
    public $sub4;
    public $sub5;
    public $raw;
   // public $mobile;
    public $tm;
    public $offer_id;
    public $webmaster_id;
    public $webmaster_currency_id;

    public $is_visited_pl = 0;
    public $pl_id;
    public $pl_url;
    public $pl_time;

    public $is_visited_lp = 0;
    public $lp_id;
    public $lp_url;
    public $lp_time;

    public $external_id = 0;

    public static function load(array $data)
    {
        $hit = new self;

        if (array_key_exists('IP', $data)) $hit->ip = $data['IP'];
        if (array_key_exists('UserAgent', $data)) $hit->ua = $data['UserAgent'];

        if (array_key_exists('Referer', $data)) $hit->referer = $data['Referer'];
        //if (array_key_exists('URL', $data)) $hit->url = $data['URL'];

        if (array_key_exists('Hash', $data)) $hit->click_id = $data['Hash'];
        if (array_key_exists('FlowHash', $data)) $hit->flow_hash = $data['FlowHash'];
        if (array_key_exists('Sub1', $data)) $hit->sub1 = $data['Sub1'];
        if (array_key_exists('Sub2', $data)) $hit->sub2 = $data['Sub2'];
        if (array_key_exists('Sub3', $data)) $hit->sub3 = $data['Sub3'];
        if (array_key_exists('Sub4', $data)) $hit->sub4 = $data['Sub4'];
        if (array_key_exists('Sub5', $data)) $hit->sub5 = $data['Sub5'];
        $hit->raw = \GuzzleHttp\json_encode($data);

        if (array_key_exists('LocationLP', $data)) $hit->lp_url = $data['LocationLP'];
        if (array_key_exists('LocationPL', $data)) $hit->pl_url = $data['LocationPL'];

        if (array_key_exists('LandingID', $data) && $data['LandingID']){
            $hit->lp_id = $data['LandingID'];

            if (array_key_exists('Time', $data)) {
                $hit->lp_time = $data['Time'];

                $datetime = explode(' ', $data['Time']);
                if (array_key_exists(0, $datetime)) {
                    $hit->event_date = $datetime[0];
                }
            }
        }

        if (array_key_exists('PrelandingID', $data) && $data['PrelandingID']){
            $hit->pl_id = $data['PrelandingID'];

            if (array_key_exists('Time', $data)) {
                $hit->pl_time = $data['Time'];

                $datetime = explode(' ', $data['Time']);
                if (array_key_exists(0, $datetime)) {
                    $hit->event_date = $datetime[0];
                }
            }
        }

        if (array_key_exists('IsVisitedLP', $data)) $hit->is_visited_lp = $data['IsVisitedLP'];
        if (array_key_exists('IsVisitedPL', $data)) $hit->is_visited_pl = $data['IsVisitedPL'];
        
        if (array_key_exists('OfferID', $data)) $hit->offer_id = $data['OfferID'];
        if (array_key_exists('FlowID', $data)) $hit->flow_id = $data['FlowID'];

        if (array_key_exists('WebMasterID', $data)) $hit->webmaster_id = $data['WebMasterID'];
        if (array_key_exists('WebMasterCurrencyID', $data)) $hit->webmaster_currency_id = $data['WebMasterCurrencyID'];

        return $hit;
    }
}