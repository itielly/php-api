<?php

namespace app\repositories;

use app\models\Event;
use stdClass;

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

  public function create($values)
  {
      return $this->model->create($values);
  }

  public function put($values)
  {
      return $this->model->put($values);
  }

  public function delete($id)
  {
    return $this->model->delete($id);
  }
}