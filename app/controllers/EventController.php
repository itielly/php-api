<?php

namespace app\controllers;
use app\services\EventService;
use Exception;

class EventController extends ApiController
{
  public function index()
  {
    try 
    {
      echo $this->returnSuccess(
          "Eventos encontrados",
          (new EventService)->get());

    } catch (Exception $e) {
      echo $this->returnError($e, "Erro ao listar eventos", 400);
    }
  }

  public function store($params)
  {
    var_dump($params);
    var_dump("store do contact");
  }
}