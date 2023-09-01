<?php


header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Credentials", "false");
header("Access-Control-Allow-Methods: GET,POST,PUT,DELETE");
header("Access-Control-Allow-Headers: Access-Control-Allow-Headers, Origin,Accept, X-Requested-With, Content-Type, Access-Control-Request-Method, Access-Control-Request-Headers");

require_once "../vendor/autoload.php";
require "../routes/router.php";


try {
  $uri = parse_url($_SERVER["REQUEST_URI"])["path"];
  $request = $_SERVER["REQUEST_METHOD"];

  if (!isset($router[$request])) {
    throw new \Exception("A rota não existe");
  }

  if (!array_key_exists($uri, $router[$request])) {
    throw new \Exception("A rota não existe");
  }

  $controller = $router[$request][$uri];
  $controller();
} catch (\Exception $e) {
  $e->getMessage();
}
