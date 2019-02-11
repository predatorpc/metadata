<?php
namespace App\Entity;

abstract class EntityList
{
    abstract static function load($data);
    /**
     * @return Entity[]
    */
    abstract function getList();
    abstract function add(Entity $item);
}