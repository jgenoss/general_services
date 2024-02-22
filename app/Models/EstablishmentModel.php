<?php

namespace App\Models;

use App\Controllers\DBController;

class EstablishmentModel
{

   private $db;

   public function __construct()
   {
      $this->db = new DBController();
   }
   public function getEstablishmentByEmail($email)
   {
      $query = "SELECT * FROM establecimientos WHERE correo_electronico = :correo_electronico";
      $params = [':correo_electronico' => $email];
      $stmt = $this->db->executeQuery($query, $params);
      if ($stmt) {
         $result = $this->db->Consult($stmt);
         if ($result) {
            return $result;
         } else {
            return false;
         }
      } else {
         return null;
      }
   }

   public function registerEstablishment($nombre_establecimiento, $direccion_establecimiento, $telefono_establecimiento, $correo_electronico_establecimiento, $id_cliente)
   {
      $query = "INSERT INTO establecimientos (nombre, direccion, telefono, correo_electronico,	id_cliente) VALUES (:nombre_establecimiento, :direccion_establecimiento, :telefono_establecimiento, :correo_electronico_establecimiento, :id_cliente)";

      $params = [
         ':nombre_establecimiento' => $nombre_establecimiento,
         ':direccion_establecimiento' => $direccion_establecimiento,
         ':telefono_establecimiento' => $telefono_establecimiento,
         ':correo_electronico_establecimiento' => $correo_electronico_establecimiento,
         ':id_cliente' => $id_cliente,
      ];
      $stmt = $this->db->executeQuery($query, $params);
      if ($stmt) {
         return true;
      } else {
         return null;
      }
   }
   public function changeEstablishment($nombre_establecimiento, $direccion_establecimiento, $telefono_establecimiento, $correo_electronico_establecimiento, $id_cliente, $id_establecimiento)
   {

      $query = "UPDATE establecimientos SET nombre = :nombre_establecimiento, direccion = :direccion_establecimiento, telefono = :telefono_establecimiento, correo_electronico = :correo_electronico_establecimiento, id_cliente = :id_cliente WHERE id_establecimiento = :id_establecimiento";

      $params = [
         ':nombre_establecimiento' => $nombre_establecimiento,
         ':direccion_establecimiento' => $direccion_establecimiento,
         ':telefono_establecimiento' => $telefono_establecimiento,
         ':correo_electronico_establecimiento' => $correo_electronico_establecimiento,
         ':id_establecimiento' => $id_establecimiento,
         ':id_cliente' => $id_cliente,
      ];
      $stmt = $this->db->executeQuery($query, $params);
      if ($stmt) {
         return true;
      } else {
         return null;
      }
   }
   public function getEstablishment($id_establecimiento)
   {
      $query = "SELECT * FROM establecimientos WHERE id_establecimiento = :id_establecimiento";

      $params = [':id_establecimiento' => $id_establecimiento];
      $stmt = $this->db->executeQuery($query, $params);

      if ($stmt) {
         $establishmentInfo = $this->db->Consult($stmt);
         if ($establishmentInfo) {
            return [
               'nombre' => $establishmentInfo->nombre,
               'direccion' => $establishmentInfo->direccion,
               'telefono' => $establishmentInfo->telefono,
               'correo_electronico' => $establishmentInfo->correo_electronico,
               'id_establecimiento' => $establishmentInfo->id_establecimiento,
               'id_cliente' => $establishmentInfo->id_cliente,
            ];
         }
      } else {
         return null;
      }
   }
   public function getAllEstablishments()
   {
      $query = "SELECT * FROM establecimientos";
      $params = [];
      $stmt = $this->db->executeQuery($query, $params);
      if ($stmt) {
         $results = $this->db->AllConsult($stmt);
         if ($results) {
            return $results;
         }
      } else {
         return null;
      }
   }
   public function getClients()
   {
      $query = "SELECT * FROM clientes";
      $params = [];
      $stmt = $this->db->executeQuery($query, $params);
      if ($stmt) {
         $results = $this->db->AllConsult($stmt);
         if ($results) {
            return $results;
         }
      } else {
         return null;
      }
   }
}