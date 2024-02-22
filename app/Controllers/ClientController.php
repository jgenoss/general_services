<?php

namespace App\Controllers;

use App\Models\ClientModel;

class ClientController
{

   private $clientModel;
   private $functionController;

   public function __construct()
   {
      $this->functionController = new FunctionController();
      $this->clientModel = new ClientModel();
   }
   public function registerUserAndEdit()
   {
      if ($this->functionController->verifyAuthToken()) {
         $Id = isset($_POST['id_cliente']) ? $_POST['id_cliente'] : null;

         $P = [
            $_POST['nombres'],
            $_POST['apellidos'],
            $_POST['tipo_identificacion'],
            $_POST['no_identificacion'],
            $_POST['juridico'],
            $_POST['direccion'],
            $_POST['telefono'],
            $_POST['email'],
            isset($_POST['regimen_comun']) ? $_POST['regimen_comun'] : 'false',
            isset($_POST['regimen_simplificado']) ? $_POST['regimen_simplificado'] : 'false',
            isset($_POST['gran_contribuyente']) ? $_POST['gran_contribuyente'] : 'false',
            isset($_POST['autoretenedor']) ? $_POST['autoretenedor'] : 'false'
         ];

         if ($Id !== null) {
            if ($this->clientModel->changeClientData($P[0], $P[1], $P[2], $P[3], $P[4], $P[5], $P[6], $P[7], $P[8], $P[9], $P[10], $P[11], $Id)) {
               $response = ['message' => 'Changes made successfully'];
               $this->functionController->sendResponse(200, $response);
            } else {
               $response = ['message' => 'Error editing user'];
               $this->functionController->sendResponse(400, $response);
            }
         } else {
            if (!$this->clientModel->getClientByEmail($_POST['email'])) {
               if ($this->clientModel->registerClient($P[0], $P[1], $P[2], $P[3], $P[4], $P[5], $P[6], $P[7], $P[8], $P[9], $P[10], $P[11])) {
                  $response = ['message' => 'Successfully registered'];
                  $this->functionController->sendResponse(200, $response);
               } else {
                  $response = ['message' => 'Error while registering'];
                  $this->functionController->sendResponse(400, $response);
               }
            } else {
               $response = ['message' => 'User already exists'];
               $this->functionController->sendResponse(400, $response);
            }
         }
      } else {
         $response = ['message' => 'Unauthorized access, invalid token'];
         $this->functionController->sendResponse(401, $response);
      }
   }
   public function getAllClients()
   {
      if ($this->functionController->verifyAuthToken()) {
         $result = $this->clientModel->getAllClients();
         $A = [];
         if (is_array($result)) {
            foreach ($result as $key => $value) {
               $A[] = [
                  //$buttons,
                  'id_cliente' => $value->id_cliente,
                  'nombres' => ucfirst($value->nombres),
                  'apellidos' => ucfirst($value->apellidos),
                  'no_identificacion' => $value->tipo_identificacion . '-' . $value->no_identificacion,
                  'telefono' => $value->telefono,
                  'email' => $value->email,
                  'direccion' => $value->direccion,
                  'status' => ($value->status == 'true') ? true : false,
               ];
            }
         }

         $data = [
            "sEcho" => 1,
            "iTotalRecords" => count($A),
            "iTotalDisplayRecords" => count($A),
            "data" => $A
         ];
         $this->functionController->sendResponse(200, $data);
      } else {
         $response = ['message' => 'Unauthorized access, invalid token'];
         $this->functionController->sendResponse(401, $response);
      }
   }
   public function getClient($id)
   {
      if ($this->functionController->verifyAuthToken()) {
         $elementId = isset($id) ? $id : null;
         if ($elementId !== null) {
            $data = [$this->clientModel->getClientData($elementId)];
            $this->functionController->sendResponse(200, $data);
         } else {
            // Show an error message if ID is not provided
            $data = ['message' => 'Element ID not provided'];
            $this->functionController->sendResponse(400, $data);
         }
      } else {
         $response = ['message' => 'Unauthorized access, invalid token'];
         $this->functionController->sendResponse(401, $response);
      }
   }
}