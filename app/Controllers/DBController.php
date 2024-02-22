<?php

namespace App\Controllers;

class DBController
{
   private $host = 'localhost'; // Host de la base de datos
   private $db_name = 'general_services'; // Nombre de la base de datos
   private $username = 'root'; // Usuario de la base de datos
   private $password = ''; // Contraseña de la base de datos

   private $conn; // Variable para almacenar la conexión PDO

   public function __construct()
   {
      // Intentar conectarse a la base de datos en el constructor
      try {
         $dsn = "mysql:host=$this->host;dbname=$this->db_name";
         $this->conn = new \PDO($dsn, $this->username, $this->password);
         $this->conn->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
      } catch (\PDOException $e) {
         echo 'Error de conexión: ' . $e->getMessage();
      }
   }
   // Método para obtener la conexión PDO
   public function getConnection()
   {
      return $this->conn;
   }

   // Métodos para realizar consultas a la base de datos
   public function executeQuery($query, $params = [])
   {
      try {
         $stmt = $this->conn->prepare($query);
         $stmt->execute($params);
         return $stmt;
      } catch (\PDOException $e) {
         echo (json_encode('Error en la consulta: ' . $e->getMessage()));
         return null;
      }
   }
   public function AllConsult($val)
   {
      return $val->fetchAll(\PDO::FETCH_OBJ);
   }
   public function Consult($val)
   {
      return $val->fetch(\PDO::FETCH_OBJ);
   }
   public function lastInsertId()
   {
      return $this->conn->lastInsertId();
   }
   public function sql($sql)
   {
      return $this->conn->query($sql);
   }

   // Otros métodos para realizar operaciones CRUD si es necesario
}
