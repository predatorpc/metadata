<?php

namespace App\Database;

use App\App;
use App\Database\Tables\Hits;
use App\Database\Tables\Table;
use App\Entity\EntityList;
use App\Exceptions\InvalidArgumentException;
use ClickHouseDB\Client;
use Psr\Log\LoggerInterface;

class Manager implements IDbManager
{
    private $adapter;

    public function __construct()
    {
        $container = App::getInstance()->getSlim()->getContainer();
        $settings = $container->get('settings')['clickhouse'];
        $database = new Client($settings['connection']);
        $database->database($settings['database']);
        $this->adapter = $database;
    }

    public function getAdapter()
    {
        return $this->adapter;
    }

    public function getRepository($class)
    {
        return new $class($this->adapter);
    }

    public function createTable(Table $table)
    {
        $query = $this->getCreateQuery($table);
        $this->adapter->write($query);
    }

    public function query($query)
    {
        return $this->adapter->write($query);
    }

    public function getCreateQuery(Table $table)
    {
        if (empty($table->getTableName())) throw new InvalidArgumentException('Invalid table name: ' . get_class($table));
        if (empty($table->getColumns())) throw new InvalidArgumentException('Invalid columns in ' . get_class($table));

        $result = "CREATE TABLE IF NOT EXISTS " . $this->wrapQuotes($table->getTableName()) . "(";
        $columns = [];
        foreach ($table->getColumns() as $column => $type) {
            $columns[] = $this->wrapQuotes($column) . " " . $type;
        }
        $result .= implode(',', $columns) . ") ENGINE = " . $table->getEngine();
        return $result;
    }

    public function insertIntoTable(Table $table, EntityList $list)
    {
        $columns = $table->getColumns();
        $data = [];
        //$index = $this->getMaxId($table) + 1;
        foreach ($list->getList() as $row) {
            App::getInstance()->getSlim()->getContainer()->get(LoggerInterface::class)->info($row->offer_id);
            $item = [];
            //$row->id = $index++;
            foreach ($columns as $columnName => $columnType) {
                $item[] = $row->getValueByTableColumn($columnName, $columnType);
            }
            $data[] = $item;
        }
        return $this->adapter->insert($table->getTableName(), $data, array_keys($columns));
    }


    public function wrapQuotes($string)
    {
        return '`' . $string . '`';
    }

    public function getMaxId(Table $table)
    {
        $statement = $this->adapter->select('select max(id) as max_id from ' . $table->getTableName());
        $row = $statement->fetchOne();
        if($row) {
            return $row['max_id'];
        } else {
            return 0;
        }
    }
}