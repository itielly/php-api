<?php

function load(string $controller, string $action)
{
    try {
        $controllerNamespace = "app\\controllers\\{$controller}";

        if (!class_exists($controllerNamespace)) {
            throw new Exception("O controller {$controller} não existe");
        }

        $controllerInstance = new $controllerNamespace();

        if (!method_exists($controllerInstance, $action)) {
            throw new Exception(
                "O método {$action} não existe no controller {$controller}"
            );
        }

        $controllerInstance->$action((object) $_REQUEST);
    } catch (Exception $e) {
        echo $e->getMessage();
    }
}

$router = [
  "GET" => [
    "/event" => fn () => load("EventController", "index"),
  ],
  "POST" => [
    "/event" => fn () => load("EventController", "post"),
  ],
  "PUT" => [
    "/event" => fn () => load("EventController", "edit"),
  ],
  "DELETE" => [
    "/event" => fn () => load("EventController", "delete"),
  ],
];
