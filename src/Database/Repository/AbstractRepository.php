<?php
namespace App\Database\Repository;

use App\App;
use App\Database\IDbManager;

abstract class AbstractRepository
{
    protected $adapter;
    public function __construct($adapter)
    {
        $this->adapter = $adapter;
    }

    public function getAdapter(){
        return $this->adapter;
    }
}