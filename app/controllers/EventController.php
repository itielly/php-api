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

  public function post()
  {
    try 
    {
      echo $this->returnSuccess(
      "Evento criado com sucesso!",
      (new EventService)->post());
    } catch (Exception $e) {
      echo $this->returnError($e, "Erro ao criar evento", 400);
    }
  }

  public function edit()
  {
    try 
    {
      echo $this->returnSuccess(
      "Evento editado com sucesso!",
      (new EventService)->edit());
    } catch (Exception $e) {
      echo $this->returnError($e, "Erro ao editar evento", 400);
    }
  }

  public function delete()
  {
    try 
    {
      echo $this->returnSuccess(
      "Evento deletado com sucesso!",
      (new EventService)->delete());
    } catch (Exception $e) {
      echo $this->returnError($e, "Erro ao deletar evento", 400);
    }
  }
}