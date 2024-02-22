<?php

namespace App\Controllers;

use App\Models\UserModel;

class LoginController
{

   private $sessionController;
   private $userModel;
   private $functionController;

   public function __construct()
   {
      $this->sessionController = SessionController::getInstance();
      $this->userModel = new UserModel();
      $this->functionController = new FunctionController();
   }
   public function loginUser()
   {
      if ($_SERVER['REQUEST_METHOD'] === 'POST') {
         $email = $_POST['email'];
         $password = $_POST['password'];

         if ($this->userModel->checkFailedLoginAttempts($this->functionController->getClientIP())) {
            $data = [
               'message' => 'Your IP address has been blocked due to failed attempts. Please try again later.'
            ];
            $this->functionController->sendResponse(401, $data);
         } else {
            $user = $this->userModel->getUserByEmail($email);
            if ($user) {
               $hash = password_hash($password, PASSWORD_DEFAULT);
               if (password_verify($user->contrasena, $hash)) {
                  $token = $this->functionController->encrypt_sha256($user->id);
                  $this->sessionController->startSession(['user' => $this->userModel->getUserData($user->id), 'token' => $token]);
                  $data = ['message' => 'Inicio de sesión exitoso'];
                  $this->functionController->sendResponse(200, $data);
               } else {
                  $data = ['message' => 'Credenciales incorrectas'];
                  $this->functionController->sendResponse(401, $data);
               }
            } else if (!$user) {
               $data = ['message' => 'Credenciales incorrectas'];
               $this->functionController->sendResponse(401, $data);
            }
         }
      }
   }
   public function logoutUser()
   {
      $this->sessionController->endSession();
      $data = ['message' => 'Logged out'];
      $this->functionController->sendResponse(200, $data);
   }
}
