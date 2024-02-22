<?php

namespace App\Controllers;

use App\Controllers\DBController;
use App\Models\UserModel;

class UserController
{

   private $userModel;
   private $sessionController;
   private $functionController;
   private $db;

   public function __construct()
   {

      $this->sessionController = SessionController::getInstance();
      $this->db = new DBController();
      $this->functionController = new FunctionController();
      $this->userModel = new UserModel();
   }
   public function requiresAuthentication()
   {
      return true; // Esta ruta requiere autenticación
   }
   public function getToken()
   {
      $data = ['token' => $this->sessionController->getSessionData()['token']];
      $this->functionController->sendResponse(200, $data);
   }
   /*
   function getAllUsers()
   {
      if ($this->functionController->verifyAuthToken()) {
         $result = $this->userModel->getAllUsers();

         foreach ($result as $key => $value) {

            //filtro de botones
            $buttons = ($value->status == 'true') ?
               //true
               '<button type="button" value="' . $value->id . '" class="edit btn btn-sm btn-icon btn-outline-primary"><i class="fas fa-edit"></i></button>
                  <button type="button" value="' . $value->id . '" class="trash btn btn-sm btn-icon btn-outline-warning"><i class="fas fa-trash"></i></button>' :
               //false
               '<button type="button" value="' . $value->id . '" class="edit btn btn-sm btn-icon btn-outline-primary"><i class="fas fa-edit"></i></button>
                  <button type="button" value="' . $value->id . '" class="trash btn btn-sm btn-icon btn-outline-warning"><i class="fas fa-trash"></i></button>';

            $A[] = [
               $buttons,
               ucfirst($value->nombre),
               $value->email,
               ucfirst($value->role),
               ($value->status == 'true') ? '<span class="badge badge-light-success">ACTIVE</span>' : '<span class="badge badge-light-danger">INACTIVE</span>',
            ];
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
   }*/
   function getAllUsers()
   {
      if ($this->functionController->verifyAuthToken()) {
         $result = $this->userModel->getAllUsers();
         $data = $this->formatUserData($result);

         return $this->functionController->sendResponse(200, $data);
      } else {
         $response = ['message' => 'Unauthorized access, invalid token'];
         $this->functionController->sendResponse(401, $response);
      }
   }

   private function formatUserData($result)
   {
      $formattedData = [];

      foreach ($result as $value) {
         $buttons = $this->generateButtons($value->id, $value->role_id, $value->status);

         $formattedData[] = [
            $buttons,
            ucfirst($value->nombre),
            $value->email,
            ucfirst($value->role),
            $this->getStatusBadge($value->status),
         ];
      }

      return [
         "sEcho" => 1,
         "iTotalRecords" => count($formattedData),
         "iTotalDisplayRecords" => count($formattedData),
         "data" => $formattedData
      ];
   }

   private function generateButtons($userId, $role, $status)
   {
      $editButton = '<button type="button" value="' . $userId . '" class="edit btn btn-sm btn-icon btn-outline-primary"><i class="fas fa-edit"></i></button>';
      $trashButton = '<button type="button" value="' . $userId . '" class="trash btn btn-sm btn-icon btn-outline-warning"><i class="fas fa-trash"></i></button>';
      return ($status == 'true') ? $editButton . $trashButton : $editButton . $trashButton;
   }

   private function getStatusBadge($status)
   {
      return ($status == 'true') ? '<span class="badge badge-light-success">ACTIVE</span>' : '<span class="badge badge-light-danger">INACTIVE</span>';
   }
   public function registerUserAndEdit()
   {
      if ($this->functionController->verifyAuthToken()) {
         $Id = isset($_POST['id']) ? $_POST['id'] : null;
         if ($Id !== null) {
            if ($this->userModel->changeUserData($_POST['nombre'], $_POST['email'], $_POST['contrasena'], $_POST['status'], $_POST['role_id'], $Id)) {

               $response = ['message' => 'Changes made successfully'];
               $this->functionController->sendResponse(200, $response);

            } else {
               $response = ['message' => 'Error editing user'];
               $this->functionController->sendResponse(400, $response);
            }
         } else {
            if (!$this->userModel->checkExistingUser($_POST['email'])) {
               if ($this->userModel->registerUser($_POST['nombre'], $_POST['email'], $_POST['contrasena'], $_POST['status'], $_POST['role_id'])) {
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
   public function deleteUserId($id)
   {
      if ($this->functionController->verifyAuthToken()) {
         $elementId = isset($id) ? $id : null;
         if ($elementId !== null) {
            if ($this->userModel->deleteUserId($elementId)) {

               $response = ['message' => 'Changes made successfully'];
               $this->functionController->sendResponse(200, $response);

            } else {
               $response = ['message' => 'Error delete user'];
               $this->functionController->sendResponse(400, $response);
            }
         }
      } else {
         $response = ['message' => 'Unauthorized access, invalid token'];
         $this->functionController->sendResponse(401, $response);
      }
   }
   public function getUserData($id)
   {
      if ($this->functionController->verifyAuthToken()) {
         $elementId = isset($id) ? $id : null;
         if ($elementId !== null) {
            $data = [$this->userModel->getUserData($elementId)];
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
   public function checkUserPermissions()
   {
      if ($this->functionController->verifyAuthToken()) {
         $required_permissions = $_POST['required_permissions'];
         if ($this->userModel->checkUserPermissions($_SESSION['data']['user']['id'], $required_permissions)) {
            $response = ['permissions' => 'true'];
            $this->functionController->sendResponse(200, $response);
         } else {
            $response = ['permissions' => 'false'];
            $this->functionController->sendResponse(401, $response);
         }
      } else {
         $response = ['message' => 'Unauthorized access, invalid token'];
         $this->functionController->sendResponse(401, $response);
      }
   }
}