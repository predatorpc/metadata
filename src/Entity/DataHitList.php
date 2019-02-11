<?php
namespace App\Entity;

class DataHitList extends EntityList
{
    public $list = [];
    public static function load($data)
    {
        $list = new self;
        foreach ($data as $item){
            if(!is_null($item)){
                if(empty($item['id'])){
                    $hit = DataHit::load($item);
                    $list->add($hit);
                } else {
                    $hit = DataHitOld::load($item);
                    $list->add($hit);
                }
            }
        }
        return $list;
    }

    public function getList(){
        return $this->list;
    }

    public function add(Entity $hit){
        $this->list[] = $hit;
    }
}