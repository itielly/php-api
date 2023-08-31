<?php

namespace app\traits;
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

    public function sqlCreate($values)
    {       
        try {
            $query = $this->conn
            ->prepare("INSERT INTO Event
                (name, dayEvent, initHour, finishHour, description) VALUES (:name, :day, :initHour, :finishHour, :description)");

            $query->bindValue(":name", $values->name); 
            $query->bindValue(":day", $values->dayEvent); 
            $query->bindValue(":initHour", $values->initHour);
            $query->bindValue(":finishHour", $values->finishHour);
            $query->bindValue(":description", $values->description);

            $query->execute();

            return $this->sqlSelect()->get();
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
    public function sqlSelect()
    {
        try {
            $sql = "SELECT * FROM Event";
            $this->stmt = $this->conn->prepare($sql);

            return $this;

        } catch (PDOException $e) {
            throw $e;
        }
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

    public function sqlDelete($id)
    {
        try 
        {
            $sql = "DELETE FROM Event WHERE id = {$id}";
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

    /**
     * @param $sql
     * @param array $bind
     * @return bool
     * @throws Exception
     */
    public function sqlRawUpdate($sql)
    {
        $this->stmt = $this->conn->prepare($sql);
        $this->stmt->execute();

        if ($this->stmt->rowCount() <= 0) {
            throw new Exception("Row not Found, query: \"" . $sql . "\"");
        }

        return true;
    }

    public function bind(array $bind)
    {
        $this->bindValues($bind);

        $this->bind = $bind;

        return $this;
    }

    private function bindValues($bind)
    {
        if (!empty($bind)) {
            foreach ($bind as $key => $value) {
                $this->stmt->bindValue($key, "{$value}");
            }
        }
    }
}