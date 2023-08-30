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

  public function create()
  {
      $data = new stdClass();
      $data->name = 'irruuu';
      $data->dayEvent = '2024-05-30';
      $data->initHour = '15:00:00';
      $data->finishHour = '17:30:00';
      $data->description ='Um evento inesquicível para os amantes de Chopin';

      return $this->model->create($data);
  }

  public function put()
  {
      $data = new stdClass();
      $data->id = 5;
      $data->name = 'ririri';

      return $this->model->put($data);
  }

  public function delete()
  {
    $id = 5;
    return $this->model->delete($id);
  }
}