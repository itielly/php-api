<?php

namespace app\repositories;

use app\models\Event;

class EventRepository
{
  /**
   * @var Event
   */
  private $model;

  public function __construct()
  {
      $this->model = new Event;
  }

  public function get()
  {
      return $this->model->getAll();
  }
}