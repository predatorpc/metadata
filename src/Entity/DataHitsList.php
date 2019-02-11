<?php
namespace App\Entity;

class DataHitList extends EntityList
{
    public $list = [];
    public static function load($data)
    {
        $list = new self;
        foreach ($data as $item){
            switch (empty($item['id'])){
                case true:
                    $hit = DataHit::load($item);
                    break;
                case false:
                default:
                    $hit = DataHitOld::load($item);
                    break;
            }
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