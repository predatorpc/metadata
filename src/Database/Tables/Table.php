<?php
namespace App\Database\Tables;

use App\Exceptions\InvalidArgumentException;
use App\Exceptions\MethodNotImplemented;

abstract class Table
{
    protected $columns = [];
    protected $tableEngine = 'TinyLog';
    protected $tableName;

    const COLUMN_UINT8      = 'UInt8';
    const COLUMN_INT8       = 'Int8';
    const COLUMN_INT32      = 'Int32';
    const COLUMN_INT64      = 'Int64';
    const COLUMN_STRING     = 'String';
    const COLUMN_DATE       = 'Date';
    const COLUMN_DATETIME   = 'DateTime';


    abstract function getTableName();
    abstract function getColumns();
    abstract function getEngine();
}