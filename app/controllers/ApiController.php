<?php

namespace app\controllers;

class ApiController
{
  public function returnSuccess($message, $data = [])
  {
      header('Content-Type: application/json');
      return json_encode([
          'status' => true,
          'message' => $message,
          'data' => $data,
          'errors' => null
      ]);
  }

  public function returnError($exception = null, $paramMessage = "", $status = 200, $data = null, $errors = [])
  {
      $message = $paramMessage != "" ?
          $paramMessage :
          (empty($exception) ?
              $paramMessage :
              $exception->getMessage());

      
          header("HTTP/1.0 {$status}");

      header('Content-Type: application/json');

      $data = method_exists($exception, 'getData') ? $exception->getData() : $data;

      return json_encode([
          'status' => false,
          'message' => $message,
          'data' => $data,
          'errors' => $errors
      ]);
  }
}