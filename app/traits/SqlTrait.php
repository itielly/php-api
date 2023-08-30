<?php

namespace app\traits;
use app\utils\BaseModel;
use MongoDB\Driver\Manager;
use PDO;
use PDOException;
use PDOStatement;
use Exception;

trait SqlTrait
{
    /** @var Manager|PDO $conn */
    protected $conn;
    /** @var PDOStatement $stmt */
    protected $stmt;
    protected $table;

    protected $bind;

    /**
     * @param BaseModel $model
     * @return string
     */
    public function sqlInsert(BaseModel $model)
    {
        $inputs = $this->getModelWithoutPrimaryKey($model);

        $inputsForQuery = array_map(function ($key) {
            return "`{$key}`";
        }, array_keys($inputs));
        $inputsForQuery = implode(',', $inputsForQuery);

        $fields = implode(',', array_keys($inputs));
        $values = ':' . str_replace(',', ',:', $fields);

        $query = $this->conn
            ->prepare("INSERT INTO {$this->getTableName()} ({$inputsForQuery}) VALUES ({$values})");

        try {
            foreach (explode(',', $values) as $key => $value) {
                $query->bindValue($value, $inputs[explode(',', $fields)[$key]]);
            }

            $query->execute();

            return $this->conn->lastInsertId();
        } catch (PDOException $e) {
            throw $e;
        }
    }

    /**
     * @param array $models
     * @return bool
     */
    public function sqlInsertMany(array $models)
    {
        try {
            $inputs = $this->getModelWithoutPrimaryKey($models[0]);
            $fields = implode(',', array_keys($inputs));

            $sql = "INSERT INTO {$this->getTableName()} ({$fields}) VALUES ";
            $sql .= $this->mountValues($fields, count($models));

            $stmt = $this->conn->prepare($sql);

            $bind = 1;
            foreach ($models as $model) {
                $values = $this->removeNullValues($model->toArray());

                foreach (array_keys($inputs) as $key) {
                    $stmt->bindValue($bind, $values[$key] ?? null);
                    $bind++;
                }
            }

            return $stmt->execute();

        } catch (PDOException $e) {
            throw $e;
        }
    }

    /**
     * @param array $select
     * @param array $where
     * @param array $order
     * @param null $limit
     * @param null $groupBy
     * @return $this
     */
    public function sqlSelect($select = [], $where = [], $order = [], $limit = null, $groupBy = null)
    {
        if (empty($select))
            $select = "*";
        else
            $select = implode(',', $select);

        $sql = "SELECT {$select} FROM {$this->table} ";

        if (!empty($where))
            $sql .= $this->where($where);

        if (!empty($order))
            $sql .= $this->order($order);

        if (!empty($limit)) {
            $sql .= "LIMIT {$limit} ";
        }

        if (!empty($groupBy)) {
            $sql .= "GROUP BY {$groupBy}";
        }

        try {
            $this->stmt = $this->conn->prepare($sql);

            return $this;

        } catch (PDOException $e) {
            throw $e;
        }
    }

    public function dd()
    {
        dd([
            $this->stmt->queryString,
            $this->bind
        ]);
    }

    /**
     * @param bool $likeArray
     * @return array|mixed
     */
    public function get(bool $likeArray = true)
    {
        try {
            $this->stmt->execute();

            if ($this->stmt->rowCount() === 1 && !$likeArray) {
                return $this->stmt->fetchObject();
            }

            if ($this->stmt->rowCount() === 0 && !$likeArray) {
                return null;
            }

            return $this->stmt->fetchAll(PDO::FETCH_OBJ);
        } catch (PDOException $e) {
            throw $e;
        }
    }

    /**
     * @return mixed
     */
    public function first()
    {
        $this->stmt->execute();

        return $this->stmt->fetchObject();
    }

    /**
     * @param $set
     * @param $where
     * @param $bind
     * @param $throwEmpty
     * @return bool
     * @throws Exception
     */
    public function sqlUpdate($set, $where, $bind = [], $throwEmpty = true)
    {
        $sql = "UPDATE {$this->getTableName()} SET ";

        if (empty($set))
            throw new Exception("You passed a wrong statement to SET statement");

        foreach ($set as $key => $value) {
            $sql .= $key . " = " . $value . ", ";
        }

        $sql = substr($sql, 0, strlen($sql) - 2);

        if (!empty($where))
            $sql .= $this->where($where);

        $this->stmt = $this->conn->prepare($sql);

        $this->bindValues($bind);

        $this->stmt->execute();

        if ($this->stmt->rowCount() <= 0 && $throwEmpty)
            throw new Exception("Row not Found, query: \"" . $sql . "\"");

        return true;
    }

    public function sqlDelete($where)
    {
        $sql = "DELETE FROM {$this->table} " . $this->where($where);

        try {
            $stmt = $this->conn->prepare($sql);

            return $stmt->execute();
        } catch (PDOException $e) {
            throw $e;
        }
    }

    public function where(array $where)
    {
        $sql = " WHERE ";
        foreach ($where as $statement) {
            $continue = isset($statement[3]) ? " " . $statement[3] : "";
            $sql .= $statement[0] . " " . $statement[1] . " " . $statement[2] . $continue . " ";
        }
        return $sql;
    }

    public function sqlRawSelect($sql, $bind = [], $dd = false)
    {
        // var_dump($this->conn);
        $this->stmt = $this->conn->prepare($sql);

        $this->bindValues($bind);

        if ($dd)
            dd([
                $this->stmt->queryString,
                $bind
            ]);

        $this->stmt->execute();
        echo $this->stmt;
        return $this;
    }

    /**
     * @param $sql
     * @param array $bind
     * @return bool
     * @throws Exception
     */
    public function sqlRawUpdate($sql, $bind = [])
    {
        $this->stmt = $this->conn->prepare($sql);

        $this->bindValues($bind);

        $this->stmt->execute();

        if ($this->stmt->rowCount() <= 0) {
            throw new Exception("Row not Found, query: \"" . $sql . "\"");
        }

        return true;
    }

    public function order(array $order)
    {
        return " ORDER BY " . $order[0] . " " . $order[1] . " ";
    }

    public function bind(array $bind)
    {
        $this->bindValues($bind);

        $this->bind = $bind;

        return $this;
    }

    private function getPdoType($varType)
    {
        if (is_string($varType) || is_float($varType) || is_double($varType))
            return PDO::PARAM_STR;

        if (is_int($varType))
            return PDO::PARAM_INT;

        if (is_bool($varType))
            return PDO::PARAM_BOOL;
    }

    /**
     * @return string
     */
    private function getTableName()
    {
        if (empty($this->table)) {
            $className = explode("\\", get_class($this));
            $class = end($className);
            return strtolower($class);
        }
        return $this->table;
    }

    /**
     * @param BaseModel $model
     * @return array|BaseModel
     */
    private function getModelWithoutPrimaryKey(BaseModel $model)
    {
        $arrayModel = $this->removeNullValues($model->toArray());
        unset($arrayModel['table']);

        return $arrayModel;
    }

    /**
     * @param $array
     * @return mixed
     */
    private function removeNullValues($array)
    {
        foreach ($array as $key => $value) {
            if ($value === null) {
                unset($array[$key]);
            }
        }

        return $array;
    }

    private function bindValues($bind)
    {
        if (!empty($bind)) {
            foreach ($bind as $key => $value) {
                $this->stmt->bindValue($key, $value);
            }
        }
    }

    private function mountValues($fields, $quantityValues)
    {
        $values = [];
        $countFields = count(explode(',', $fields));
        for ($i = 0; $i < $quantityValues; $i++) {
            $sql = "(";
            for ($j = 0; $j < $countFields; $j++) {
                $sql .= "?";
                if ($j != $countFields - 1)
                    $sql .= ",";
            }
            $sql .= ")";
            $values[] = $sql;
        }

        return implode(',', $values);
    }

    protected function beginTransaction()
    {
        return $this->conn->beginTransaction();
    }

    protected function commit()
    {
        return $this->conn->commit();
    }

    protected function rollback()
    {
        if ($this->conn->inTransaction())
            return $this->conn->rollBack();

        return true;
    }
}