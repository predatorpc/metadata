<?php
namespace App\Entity;

class DataHitOldList extends EntityList
{
    public $list = [];
    public static function load($data)
    {
        $list = new self;
        foreach ($data as $item){
            $hit = DataHitOld::load($item);
            $list->add($hit);
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