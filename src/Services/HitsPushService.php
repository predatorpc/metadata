<?php
namespace App\Services;

use App\Database\IDbManager;
use App\Entity\HitList;

class HitsPushService implements IService
{
    private $database;
    public function __construct(IDbManager $database)
    {
        $this->database = $database;
    }

    public function insert($tableName, array $data){
        $list = HitList::load($data);
        $table = new $tableName();
        $this->database->insertIntoTable($table, $list);
    }
}