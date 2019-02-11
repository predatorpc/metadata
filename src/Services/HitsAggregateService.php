<?php
namespace App\Services;

use App\Database\IDbManager;
use App\Database\Repository\HitsRepository;
use App\Exceptions\InvalidArgumentException;
use App\Filters\HitsStatDtoFilter;

class HitsAggregateService
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

        return $this->database->getRepository(HitsRepository::class)->getStat($filter);
    }
}