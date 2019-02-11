<?php
namespace App\Services;

use App\Database\IDbManager;
use App\Database\Tables\DataHits;
use App\Entity\DataHitList;

class DataHitsPushService implements IService
{
    private $database;
    public function __construct(IDbManager $database)
    {
        $this->database = $database;
    }

    public function insert(array $data){
        $dataHits = DataHitList::load($data);
        $table = new DataHits();
        $this->database->insertIntoTable($table, $dataHits);
    }
}