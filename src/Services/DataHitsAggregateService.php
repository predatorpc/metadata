<?php
namespace App\Services;

use App\Database\IDbManager;
use App\Database\Repository\DataHitsRepository;
use App\Exceptions\InvalidArgumentException;
use App\Filters\HitsStatDtoFilter;

class DataHitsAggregateService
{
    private $database;
    public function __construct(IDbManager $adapter)
    {
        $this->database = $adapter;
    }

    public function getStatByFilter(HitsStatDtoFilter $filter){
        if(!$filter->currencyId) throw new InvalidArgumentException('Empty currency');
        if(!$filter->dateFrom) throw new InvalidArgumentException('Empty start period date');
        if(!$filter->dateTo) throw new InvalidArgumentException('Empty end period date');

        return $this->database->getRepository(DataHitsRepository::class)->getStat($filter);
    }
}