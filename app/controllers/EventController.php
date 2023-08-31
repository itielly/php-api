<?php

namespace app\controllers;
use app\services\EventService;
use Exception;
use app\services\GoogleCalendar;

class EventController extends ApiController
{
  /**
     * @var GoogleCalendar
     */

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
      $request = $this->request(false);
      echo $this->returnSuccess(
      "Evento criado com sucesso!",
      (new EventService)->post($request));
    } catch (Exception $e) {
      echo $this->returnError($e, "Erro ao criar evento", 400);
    }
  }

  public function edit()
  {
    try 
    {
      $request = $this->request(false);
      echo $this->returnSuccess(
      "Evento editado com sucesso!",
      (new EventService)->edit($request));
    } catch (Exception $e) {
      echo $this->returnError($e, "Erro ao editar evento", 400);
    }
  }

  public function delete()
  {
    try 
    {
      $request = $this->request(false);

      echo $this->returnSuccess(
      "Evento deletado com sucesso!",
      (new EventService)->delete($request->id));
    } catch (Exception $e) {
      echo $this->returnError($e, "Erro ao deletar evento", 400);
    }
  }
}