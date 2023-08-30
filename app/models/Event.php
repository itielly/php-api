<?php
  namespace app\models;

use app\utils\BaseModel;

  class Event extends BaseModel
  {
    protected $table = "Event";

    public function getAll()
    {  
      return $this->sqlSelect()
        ->get();
    }
  }