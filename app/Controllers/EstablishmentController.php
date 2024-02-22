<?php

namespace App\Controllers;

use App\Models\ClientModel;
use App\Models\EstablishmentModel;

class EstablishmentController
{

   private $establishmentModel;
   private $sessionController;
   private $functionController;
   private $clientModel;
   public function __construct()
   {

      $this->sessionController = SessionController::getInstance();
      $this->functionController = new FunctionController();
      $this->establishmentModel = new EstablishmentModel();
      $this->clientModel = new ClientModel();
   }
   public function registerEstablishmentsAndEdit()
   {
      if ($this->functionController->verifyAuthToken()) {
         $Id = isset($_POST['id_establecimiento']) ? $_POST['id_establecimiento'] : null;
         $P = [
            $_POST['nombre'],
            $_POST['direccion'],
            $_POST['telefono'],
            $_POST['correo_electronico'],
            $_POST['id_cliente'],
         ];
         if ($Id !== null) {
            if ($this->establishmentModel->changeEstablishment($P[0], $P[1], $P[2], $P[3], $P[4], $Id)) {
               $response = ['message' => 'Changes made successfully'];
               $this->functionController->sendResponse(200, $response);
            } else {
               $response = ['message' => 'Error editing user'];
               $this->functionController->sendResponse(400, $response);
            }
         } else {
            if (!$this->establishmentModel->getEstablishmentByEmail($_POST['correo_electronico'])) {
               if ($this->establishmentModel->registerEstablishment($P[0], $P[1], $P[2], $P[3], $P[4])) {
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
   public function getAllEstablishments()
   {
      if ($this->functionController->verifyAuthToken()) {
         $result = $this->establishmentModel->getAllEstablishments();
         $A = array();
         if (!empty($result) && is_array($result)) {
            foreach ($result as $key => $value) {
               $clientInfo = $this->clientModel->getClientData($value->id_cliente);
               $buttons = ($value->status == 'true') ?
                  '<button type="button" value="' . $value->id_establecimiento . '" class="edit btn btn-sm btn-icon btn-outline-primary"><i class="fas fa-edit"></i></button>' :
                  '<button type="button" value="' . $value->id_establecimiento . '" class="edit btn btn-sm btn-icon btn-outline-primary"><i class="fas fa-edit"></i></button>';
               $A[] = array(
                  $buttons,
                  strtoupper($value->nombre),
                  strtoupper($value->direccion),
                  $value->telefono,
                  $value->correo_electronico,
                  $clientInfo['tipo_identificacion'] . ' - ' . $clientInfo['no_identificacion'],
                  ($value->status == 'true') ? '<span class="badge badge-light-success">ACTIVE</span>' : '<span class="badge badge-light-danger">INACTIVE</span>',
               );
            }
         }
         $data = [
            "sEcho" => 1,
            "iTotalRecords" => count($A),
            "iTotalDisplayRecords" => count($A),
            "data" => $A
         ];

         return $this->functionController->sendResponse(200, $data);
      } else {
         $response = ['message' => 'Unauthorized access, invalid token'];
         $this->functionController->sendResponse(401, $response);
      }
   }
   public function getClients()
   {
      if ($this->functionController->verifyAuthToken()) {
         $clientInfo = $this->establishmentModel->getClients();
         return $this->functionController->sendResponse(200, $clientInfo);
      } else {
         $response = ['message' => 'Unauthorized access, invalid token'];
         $this->functionController->sendResponse(401, $response);
      }
   }
   public function getEstablishment($id)
   {
      if ($this->functionController->verifyAuthToken()) {
         $elementId = isset($id) ? $id : null;
         if ($elementId !== null) {
            $data = [$this->establishmentModel->getEstablishment($elementId)];
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