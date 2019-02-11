<?php

namespace App\Entity;

use App\Database\Tables\Table;

class DataHitOld extends Entity
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
    //public $mobile;
    public $tm;
    public $offer_id;
    public $webmaster_id;
    public $webmaster_currency_id;

    public $is_visited_lp = 1;
    public $lp_id;
    public $lp_url;
    public $lp_time;
    public $external_id;

    public $is_visited_pl = 0;
    public $pl_id; //не используеться в старых версиях
    public $pl_url; //не используеться в старых версиях
    public $pl_time; //не используеться в старых версиях
    
    public static function load(array $data)
    {
        $hit = new self;
        if (array_key_exists('id', $data)) $hit->external_id = $data['id'];

        if (array_key_exists('time', $data)) {
            $hit->lp_time = $data['time'];

            $datetime = explode(' ', $data['time']);
            if (array_key_exists(0, $datetime)) {
                $hit->event_date = $datetime[0];
            }
        }

        if (array_key_exists('ip', $data)) $hit->ip = $data['ip'];
        if (array_key_exists('ua', $data)) $hit->ua = $data['ua'];
        if (array_key_exists('cookie', $data)) $hit->cookie = $data['cookie'];
        if (array_key_exists('referer', $data)) $hit->referer = $data['referer'];
       // if (array_key_exists('url', $data)) $hit->url = $data['url'];
        if (array_key_exists('location', $data)) $hit->lp_url = $data['location'];
        if (array_key_exists('click_id', $data)) $hit->click_id = $data['click_id'];
        if (array_key_exists('flow_hash', $data)) $hit->flow_hash = $data['flow_hash'];
        if (array_key_exists('sub1', $data)) $hit->sub1 = $data['sub1'];
        if (array_key_exists('sub2', $data)) $hit->sub2 = $data['sub2'];
        if (array_key_exists('sub3', $data)) $hit->sub3 = $data['sub3'];
        if (array_key_exists('sub4', $data)) $hit->sub4 = $data['sub4'];
        if (array_key_exists('sub5', $data)) $hit->sub5 = $data['sub5'];
        if (array_key_exists('raw', $data)) $hit->raw = $data['raw'];
        if (array_key_exists('landing_id', $data)) $hit->lp_id = $data['landing_id'];
        //if (array_key_exists('mobile', $data)) $hit->mobile = $data['mobile'];
        if (array_key_exists('tm', $data)) $hit->tm = $data['tm'];
        if (array_key_exists('offer_id', $data)) $hit->offer_id = $data['offer_id'];
        if (array_key_exists('flow_id', $data)) $hit->flow_id = $data['flow_id'];
        if (array_key_exists('webmaster_id', $data)) $hit->webmaster_id = $data['webmaster_id'];
        if (array_key_exists('webmaster_currency_id', $data)) $hit->webmaster_currency_id = $data['webmaster_currency_id'];
        return $hit;
    }
}