<?php

namespace App\Controllers;

class FunctionController
{
   private $userModel;
   private $sessionController;
   private $secret_key;

   public function __construct()
   {
      $this->secret_key = '4d1a9c7ed0c6d91702f9';
   }
   public function requiresAuthentication()
   {
      return true; // Esta ruta requiere autenticación
   }
   public function converUtf8($value)
   {
      return mb_convert_encoding($value, "UTF-8", "UTF-8");
   }
   public function getClientIP()
   {
      $ip_address = '';

      if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
         $ip_address = $_SERVER['HTTP_CLIENT_IP'];
      } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
         $ip_address = $_SERVER['HTTP_X_FORWARDED_FOR'];
      } else {
         $ip_address = $_SERVER['REMOTE_ADDR'];
      }

      return $ip_address;
   }
   public function getDateAndTime()
   {
      date_default_timezone_set('America/Bogota');
      return date("Y-m-d H:i:s");
   }
   public function verifyAuthToken()
   {   
      $getallheaders = getallheaders();
      $retVal = (isset($getallheaders['Authorization'])) ? $getallheaders['Authorization'] : false ;
      $token = str_replace('Bearer ', '', $retVal) ?? '';
      if ($token) {
         $result = $this->decrypt_sha256($token);
         if ($result) {
            return true;
         } else {
            return false;
         }
      } else {
         return false;
      }
   }

   public function encrypt_sha256($message)
   {
      try {
         $iv = random_bytes(16);
         $encrypted = openssl_encrypt($message, 'AES-256-CBC', $this->secret_key, 0, $iv);
         $encrypted_with_iv = $iv . $encrypted;
         return base64_encode(bin2hex($encrypted_with_iv));
      } catch (\Exception $th) {
         return null;
      }
   }

   public function decrypt_sha256($encrypted)
   {
      try {
         $encrypted_with_iv = hex2bin(base64_decode($encrypted));
         $iv = substr($encrypted_with_iv, 0, 16);
         $encrypted_message = substr($encrypted_with_iv, 16);
         return openssl_decrypt($encrypted_message, 'AES-256-CBC', $this->secret_key, 0, $iv);
      } catch (\Exception $th) {
         return null;
      }
   }
   // Rest of the code for creating, editing, and deleting elements...

   public function sendResponse($statusCode, $data)
   {
      header('Content-Type: application/json');
      http_response_code($statusCode);
      echo json_encode($data);
      exit;
   }
}
