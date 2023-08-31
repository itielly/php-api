<?php

namespace app\controllers;

use Exception;

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

  protected function request($stdObject = true, $isFormData = false)
  {
      $json = !$isFormData ?
          json_decode(file_get_contents('php://input')) :
          (object)array_map(function ($item) {
              return json_decode($item);
          }, $_POST);

      $object = $stdObject ? $this->getJson($json) : $json;

      $object = $this->prepareRequest($object);

      if ($isFormData)
          if (count(get_object_vars($object)) < 1)
              throw new Exception(1100);
              
      return $object;
  }

  private function getJson($json)
  {
      $obj = $json->stdObject;

      if (count($obj) > 1)
          return $obj;

      return $obj[0];
  }

  public function prepareRequest($request)
  {

      if (!is_object($request)) {
          return $request;
      }

      $property = get_object_vars($request);

      foreach ($property as $key => $value) {
          if (is_object($request->$key)) {
              $request->$key = $this->prepareRequest($request->$key);
              continue;
          }

          if (!is_string($request->$key))
              continue;

          $request->$key = strip_tags($value);
      }

      return $request;
  }
}