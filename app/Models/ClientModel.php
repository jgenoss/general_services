<?php

namespace App\Models;

use App\Controllers\DBController;

class ClientModel
{
   private $db;
   public function __construct()
   {
      $this->db = new DBController();
   }
   public function getClientByEmail($email)
   {
      $query = "SELECT * FROM clientes WHERE email = :email";
      $params = [':email' => $email];
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

   public function registerClient($nombres, $apellidos, $tipo_identificacion, $no_identificacion, $juridico, $direccion, $telefono, $email, $regimen_comun, $regimen_simplificado, $gran_contribuyente, $autoretenedor)
   {
      $query = "INSERT INTO clientes (nombres, apellidos, tipo_identificacion, no_identificacion, juridico, direccion, telefono, email, regimen_comun, regimen_simplificado, gran_contribuyente, autoretenedor) VALUES (:nombres, :apellidos, :tipo_identificacion, :no_identificacion, :juridico, :direccion, :telefono, :email, :regimen_comun, :regimen_simplificado, :gran_contribuyente, :autoretenedor)";

      $params = [
         ':nombres' => $nombres,
         ':apellidos' => $apellidos,
         ':tipo_identificacion' => $tipo_identificacion,
         ':no_identificacion' => $no_identificacion,
         ':juridico' => $juridico,
         ':direccion' => $direccion,
         ':telefono' => $telefono,
         ':email' => $email,
         ':regimen_comun' => $regimen_comun,
         ':regimen_simplificado' => $regimen_simplificado,
         ':gran_contribuyente' => $gran_contribuyente,
         ':autoretenedor' => $autoretenedor,
      ];
      $stmt = $this->db->executeQuery($query, $params);
      if ($stmt) {
         return true;
      } else {
         return null;
      }
   }
   public function changeClientData($nombres, $apellidos, $tipo_identificacion, $no_identificacion, $juridico, $direccion, $telefono, $email, $regimen_comun, $regimen_simplificado, $gran_contribuyente, $autoretenedor, $id_cliente)
   {

      $query = "UPDATE clientes SET nombres=:nombres, apellidos=:apellidos, tipo_identificacion=:tipo_identificacion, no_identificacion=:no_identificacion, juridico=:juridico, direccion=:direccion, telefono=:telefono, email=:email, regimen_comun=:regimen_comun, regimen_simplificado=:regimen_simplificado, gran_contribuyente=:gran_contribuyente, autoretenedor=:autoretenedor WHERE id_cliente=:id_cliente";

      $params = [
         ':nombres' => $nombres,
         ':apellidos' => $apellidos,
         ':tipo_identificacion' => $tipo_identificacion,
         ':no_identificacion' => $no_identificacion,
         ':juridico' => $juridico,
         ':direccion' => $direccion,
         ':telefono' => $telefono,
         ':email' => $email,
         ':regimen_comun' => $regimen_comun,
         ':regimen_simplificado' => $regimen_simplificado,
         ':gran_contribuyente' => $gran_contribuyente,
         ':autoretenedor' => $autoretenedor,
         ':id_cliente' => $id_cliente,
      ];
      $stmt = $this->db->executeQuery($query, $params);
      if ($stmt) {
         return true;
      } else {
         return null;
      }
   }
   public function getClientData($id_cliente)
   {
      $query = "SELECT * FROM clientes WHERE id_cliente = :id_cliente";

      $params = [':id_cliente' => $id_cliente];
      $stmt = $this->db->executeQuery($query, $params);

      if ($stmt) {
         $clientInfo = $this->db->Consult($stmt);
         if ($clientInfo) {
            return [
               'nombres' => $clientInfo->nombres,
               'apellidos' => $clientInfo->apellidos,
               'tipo_identificacion' => $clientInfo->tipo_identificacion,
               'no_identificacion' => $clientInfo->no_identificacion,
               'juridico' => $clientInfo->juridico,
               'direccion' => $clientInfo->direccion,
               'telefono' => $clientInfo->telefono,
               'email' => $clientInfo->email,
               'regimen_comun' => $clientInfo->regimen_comun,
               'regimen_simplificado' => $clientInfo->regimen_simplificado,
               'gran_contribuyente' => $clientInfo->gran_contribuyente,
               'autoretenedor' => $clientInfo->autoretenedor,
               'id_cliente' => $clientInfo->id_cliente,
            ];
         }
      } else {
         return null;
      }
   }
   public function getAllClients()
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
?>