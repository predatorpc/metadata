<?php
namespace App\Entity;
use App\Database\Tables\Table;

abstract class Entity
{
    abstract static function load(array $data);
    public function getValueByTableColumn($columnName, $type)
    {
        $value = null;
        switch ($columnName) {
            case true:
                $value = $this->$columnName;
        }
        switch ($type) {
            case Table::COLUMN_UINT8:
                $value = intval($value);
                break;
            case Table::COLUMN_INT32:
                $value = intval($value);
                break;
            case Table::COLUMN_INT64:
                $value = intval($value);
                break;
            case Table::COLUMN_STRING:
                $value = "" . $value;
                break;
            case Table::COLUMN_DATE:
                $date = new \DateTime($value);
                $value = $date->format('Y-m-d');
                break;
            case Table::COLUMN_DATETIME:
                $date = new \DateTime($value);
                $value = $date->format('Y-m-d H:i:s');
                break;
        }
        return $value;
    }
}