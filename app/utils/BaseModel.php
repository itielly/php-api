<?php

namespace app\utils;

use app\traits\SqlTrait;
use stdClass;

class BaseModel
{
    protected $primaryKey;
    protected $table;

    use SqlTrait;

    /**
     * BaseModel constructor.
     * @param null $obj
     */
    public function __construct()
    {
        $this->conn = Connection::open();
    }

    public function toStdClass()
    {
        $obj = new stdClass();
        foreach ($this as $key => $value) {
            if ($this->$key !== null && $key != "table")
                $obj->$key = $this->$key;
        }
        return $obj;
    }

    public function toArray()
    {
        $obj = [];
        foreach ($this as $key => $value) {
            if ($this->$key !== null && $key !== 'primaryKey')
                $obj[$key] = $this->$key;
        }
        return $obj;
    }

    public function getPrimaryKey()
    {
        // remove namespace
        $className = explode("\\", get_class($this));
        $className = end($className);
        return empty($this->primaryKey) ? "id" . $className : $this->primaryKey;
    }

    public function getTableName()
    {
        // remove namespace
        $className = explode("\\", get_class($this));
        $className = end($className);
        return empty($this->table) ? strtolower($className) : $this->table;
    }
}