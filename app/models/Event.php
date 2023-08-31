<?php
  namespace app\models;

use app\utils\BaseModel;
use PDO;

  class Event extends BaseModel
  {
    /** @var Manager|PDO $conn */
    protected $conn;

    protected $table = "Event";

    protected $name;
    protected $dayEvent;
    protected $initHour;
    protected $finishHour;
    protected $description;

    public function getAll()
    {  
      return $this->sqlSelect()->get();
    }

    public function create($values)
    {  
      return $this->sqlCreate($values);
    }

    public function put($values)
    { 
      $fields = array();

      if (isset($values->name)) {
          $fields[] = "name='$values->name'";
      }

      if (isset($values->dayEvent)) {
          $fields[] = "dayEvent='$values->dayEvent'";
      }

      if (isset($values->initHour)) {
          $fields[] = "initHour='$values->initHour'";
      }

      if (isset($values->finishHour)) {
          $fields[] = "finishHour='$values->finishHour'";
      }

      if (isset($values->description)) {
          $fields[] = "description='$values->description'";
      }

      $sql = "UPDATE Event SET " . implode(',', $fields) . " WHERE id={$values->id}";

      return $this->sqlRawUpdate($sql);
    }

    public function delete($id)
    {
      return $this->sqlDelete($id);
    }
  }