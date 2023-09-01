<?php

namespace app\utils;

use Exception;
use PDO;
use PDOException;
use app\utils\env;

final class Connection
{

  public static function &open()
  {

    $host         = 'localhost';
    $name         = 'eventDB';
    $user         = 'root';
    $pass         = 'myPassword';
    $dsn = "mysql:dbname={$name};host={$host}";

    return self::getConn($dsn, $user, $pass);
  }

  private static function &getConn($dsn, string $user = '', string $pass = '')
    {
      try 
      {
        static $conn;

        if (!isset($conn)) {
          $conn = new PDO($dsn, $user, $pass);
        }
      } catch (PDOException $e)
      {
        throw new Exception("Erro com banco de dados", $e->getMessage());
      }
      catch (Exception $e)
      {
        throw new Exception("Erro genérico", $e->getMessage());
      }

      return $conn;
    }
}