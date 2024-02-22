<?php

namespace App\Models;

use App\Controllers\DBController;
use App\Controllers\FunctionController;

class UserModel
{

   private $db;
   private $fc;

   public function __construct()
   {
      $this->db = new DBController();
      $this->fc = new FunctionController;
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
   public function getUserByEmail($email)
   {
      $query = "SELECT * FROM usuarios WHERE email = :email";
      $params = [':email' => $email];
      $stmt = $this->db->executeQuery($query, $params);
      if ($stmt) {
         $result = $this->db->Consult($stmt);
         if ($result) {
            $this->resetFailedLoginAttempts($this->getClientIP());
            return $result;
         } else {
            return null;
         }
      } else {
         return null;
      }
   }

   public function registerUser($name, $email, $password, $status, $role)
   {
      $query = "INSERT INTO usuarios (nombre,email,contrasena,status)VALUES(:nombre,:email,:contrasena,:status)";
      $params = [
         ':nombre' => ucwords($name),
         ':email' => $email,
         ':contrasena' => $password,
         ':status' => $status
      ];
      $stmt = $this->db->executeQuery($query, $params);
      if ($stmt) {
         $this->insertUserRole($this->db->lastInsertId(), $role);
         return true;
      } else {
         return null;
      }
   }
   public function changeUserData($name, $email, $password, $status, $role_id, $id)
   {
      $query = "UPDATE usuarios SET nombre = :nombre, email = :email, contrasena = :contrasena, status = :status WHERE id = :id";
      $params = [
         ':nombre' => $name,
         ':email' => $email,
         ':contrasena' => $password,
         ':status' => $status,
         ':id' => $id,
      ];
      $stmt = $this->db->executeQuery($query, $params);
      if ($stmt) {
         $this->changeRolesById($id, $role_id);
         return true;
      } else {
         return null;
      }
   }
   public function changeRolesById($user_id, $role_id)
   {
      $query = "UPDATE usuarios_roles SET id_rol = :role_id WHERE id_usuario = :user_id";
      $params = [
         ':role_id' => $role_id,
         ':user_id' => $user_id,
      ];
      $stmt = $this->db->executeQuery($query, $params);
      if ($stmt) {
         return true;
      } else {
         return null;
      }
   }
   public function insertUserRole($user_id, $role_id)
   {
      $query = "INSERT INTO usuarios_roles (id_usuario,id_rol)VALUES(:user_id,:role_id)";
      $params = [
         ':user_id' => $user_id,
         ':role_id' => $role_id
      ];
      $stmt = $this->db->executeQuery($query, $params);
      if ($stmt) {
         return true;
      } else {
         return null;
      }
   }
   public function deleteUserId($id){
      $query = "SELECT * FROM roles AS r INNER JOIN usuarios_roles AS ur ON r.id = ur.id_rol INNER JOIN usuarios AS u ON u.id = ur.id_usuario WHERE u.id = :id AND r.id = 4 OR r.id = 1";
      $params = [':id' => $id];
      $stmt = $this->db->executeQuery($query, $params);
      if ($stmt) {
         $userInfo = $this->db->Consult($stmt);
         if ($userInfo) {
            return null;
         } else {
            $query = "DELETE usuarios, usuarios_roles FROM usuarios INNER JOIN usuarios_roles ON usuarios.id = usuarios_roles.id_usuario WHERE usuarios.id = :id";
            $params = [
               ':id' => $id
            ];
            $stmt = $this->db->executeQuery($query, $params);
            if ($stmt) {
               return true;
            } else {
               return null;
            }
         }
      } else {
         return null;
      }
         

   }
   public function checkExistingUser($email)
   {
      $query = "SELECT * FROM usuarios WHERE email= :email";
      $params = [':email' => $email];
      $stmt = $this->db->executeQuery($query, $params);
      if ($stmt) {
         $userInfo = $this->db->Consult($stmt);
         if ($userInfo) {
            return true;
         } else {
            return false;
         }
      } else {
         return null;
      }
   }
   public function getUserData($user_id)
   {
      $query = "SELECT usuarios.id, usuarios.nombre, usuarios.email,usuarios.contrasena,roles.id AS role_id, roles.nombre AS role, usuarios.status FROM usuarios_roles INNER JOIN roles ON usuarios_roles.id_rol = roles.id INNER JOIN usuarios ON usuarios_roles.id_usuario = usuarios.id WHERE usuarios.id = :user_id";
      $params = [':user_id' => $user_id];
      $stmt = $this->db->executeQuery($query, $params);

      if ($stmt) {
         $userInfo = $this->db->Consult($stmt);
         if ($userInfo) {
            return [
               'id' => $userInfo->id,
               'nombre' => ucwords($userInfo->nombre),
               'email' => $userInfo->email,
               'contrasena' => $userInfo->contrasena,
               'status' => $userInfo->status,
               'role' => $userInfo->role,
               'role_id' => $userInfo->role_id
            ];
         }
      } else {
         return null;
      }
   }
   public function getAllUsers()
   {
      $query = "SELECT a.id, a.nombre, a.email, b.nombre AS role, b.id AS role_id, a.`status` FROM usuarios AS a INNER JOIN usuarios_roles AS c ON c.id_usuario = a.id INNER JOIN roles AS b ON c.id_rol = b.id";
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
   public function getUserRole($user_id)
   {
      $query = "SELECT roles.nombre AS role FROM usuarios_roles INNER JOIN roles ON usuarios_roles.id_rol = roles.id WHERE usuarios_roles.id_usuario = :user_id";
      $params = [':user_id' => $user_id];
      $stmt = $this->db->executeQuery($query, $params);

      if ($stmt) {
         $role = $this->db->Consult($stmt);
         if ($role) {
            return $role->role;
         }
      } else {
         return null;
      }
   }
   public function checkUserPermissions($user_id, $required_permissions)
   {
      // Fetch user roles based on their ID
      $query = "SELECT r.nombre AS role FROM usuarios_roles ur JOIN roles r ON ur.id_rol = r.id WHERE ur.id_usuario = :user_id";
      $params = [':user_id' => $user_id];
      $stmt = $this->db->executeQuery($query, $params);

      if ($stmt) {
         $roles = $this->db->Consult($stmt);
         if ($roles) {
            // Get permissions associated with the user roles
            $permissions = [];
            $role_permissions = $this->getPermissionsByRole($roles->role);
            $permissions = array_merge($permissions, $role_permissions);

            // Check if the user has all the required permissions
            return count(array_intersect($required_permissions, $permissions)) === count($required_permissions);
         } else {
            return false;
         }
      }
      return false;
   }
   private function getPermissionsByRole($role)
   {
      // Define the permissions associated with each role (you can modify this based on your roles and permissions structure)
      $permissions_by_role = [
         'developer' => ['view_content', 'create_content', 'edit_content', 'delete_content'],
         'admin' => ['view_content', 'create_content', 'edit_content', 'delete_content'],
         'editor' => ['view_content', 'edit_content', 'create_content'],
         'user' => ['view_content','create_content']
      ];

      // Get the permissions associated with the role (if the role is not found, it assumes it has no permissions)
      return $permissions_by_role[$role] ?? [];
   }
   private function insertFailedLoginAttempt($ip_address)
   {
      // Consulta para insertar un intento fallido en la tabla failed_login_attempts

      $query = "INSERT INTO failed_login_attempts (ip_address, attempts, last_attempt) VALUES (:ip_address, 1, :last_attempt)";
      $params = [
         ':ip_address' => $ip_address,
         ':last_attempt' => $this->fc->getDateAndTime()
      ];

      $stmt = $this->db->executeQuery($query, $params);

      // Aquí también puedes manejar errores si es necesario.
   }
   public function resetFailedLoginAttempts($ip_address)
   {
      $query = "DELETE FROM failed_login_attempts WHERE ip_address = :ip_address";
      $params = [
         ':ip_address' => $ip_address
      ];
      $stmt = $this->db->executeQuery($query, $params);
      if ($stmt) {
         return true;
      } else {
         return null;
      }
      // Aquí también puedes manejar errores si es necesario.
   }
   private function setFailedLoginAttempts($ip_address)
   {
      // Consulta para reiniciar los intentos fallidos después de un inicio de sesión exitoso.

      $query = "UPDATE failed_login_attempts SET attempts=attempts+1, last_attempt = :last_attempt WHERE ip_address = :ip_address";
      $params = [
         ':ip_address' => $ip_address,
         ':last_attempt' => $this->fc->getDateAndTime()
      ];
      $stmt = $this->db->executeQuery($query, $params);

      // Aquí también puedes manejar errores si es necesario.
   }
   public function checkFailedLoginAttempts($ip_address)
   {
      // Consulta para obtener los intentos fallidos por dirección IP
      $query = "SELECT * FROM failed_login_attempts WHERE ip_address = :ip_address";
      $params = [
         ':ip_address' => $ip_address
      ];
      $stmt = $this->db->executeQuery($query, $params);
      if ($stmt) {
         $result = $this->db->Consult($stmt);
         if ($result) {
            if ($result->attempts > 0) {
               if ($result->attempts < 3) {
                  $this->setFailedLoginAttempts($this->getClientIP());
               }
               $last_attempt = strtotime($result->last_attempt);
               $current_time = strtotime(date("H:i:s"));

               $diferencia = $current_time - $last_attempt;
               $horas = floor($diferencia / 3600);
               $minutos = round(($diferencia - ($horas * 3600)) / 60);

               if ($result->attempts >= 2 && $minutos < 5) {
                  return true;
               } else if ($minutos > 5) {
                  $this->resetFailedLoginAttempts($this->getClientIP());
                  return false;
               }
            }
         } else {
            $this->insertFailedLoginAttempt($this->getClientIP());
            return false;
         }
      } else {
         // Error en la consulta
         return false;
      }
   }
}