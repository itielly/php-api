<?php
  namespace app\models;

use app\utils\BaseModel;

  class Event extends BaseModel
  {
    protected $table = "Event";

    protected $name;
    protected $dayEvent;
    protected $initHour;
    protected $finishHour;
    protected $description;

    public function getAll()
    {  
      return $this->sqlSelect()
        ->get();
    }

    public function create($values)
    {  
      return $this->sqlCreate($values);
    }

    public function put($values)
    {  
      $sql = "UPDATE Event SET name = 'jui' WHERE id = 10";
      return $this->sqlRawUpdate($sql);
    }

    public function delete()
    {
      $id = 8;
      return $this->sqlDelete($id);
    }
  }