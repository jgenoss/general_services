<?php

namespace App\Models;

use App\Controllers\DBController;

class ProductsModel
{

   private $db;

   public function __construct()
   {
      $this->db = new DBController();
   }

   public function insertProduct($codigo, $nombre, $descripcion, $stock, $precio, $status)
   {
      // Consulta de inserción
      $query = "INSERT INTO productos (codigo, nombre, descripcion, precio, stock, status) VALUES (:codigo, :nombre, :descripcion, :precio, :stock,:status,)";

      // Parámetros con nombres
      $params = [
         ':codigo' => $codigo,
         ':nombre' => $nombre,
         ':descripcion' => $descripcion,
         ':stock' => $stock,
         ':precio' => $precio,
         ':status' => $status,
      ];

      // Ejecutar la consulta utilizando el método executeQuery de la clase $this->db
      // Este método debe admitir consultas preparadas con parámetros con nombres
      $stmt = $this->db->executeQuery($query, $params);

      // Verificar si la consulta se ejecutó correctamente y devolver true en caso afirmativo, o null en caso contrario
      if ($stmt) {
         return true;
      } else {
         return null;
      }
   }
   public function updateProduct($id, $codigo, $nombre, $descripcion, $stock, $precio, $status)
   {
      // Consulta de actualización
      $query = "UPDATE productos SET codigo = :codigo, nombre = :nombre, descripcion = :descripcion, stock = :stock, precio = :precio, status = :status WHERE id = :id";
      // Parámetros con nombres
      $params = [
         ':id' => $id,
         ':codigo' => $codigo,
         ':nombre' => $nombre,
         ':descripcion' => $descripcion,
         ':stock' => $stock,
         ':precio' => $precio,
         ':status' => $status,
      ];
      // Ejecutar la consulta utilizando el método executeQuery de la clase $this->db
      // Este método debe admitir consultas preparadas con parámetros con nombres
      $stmt = $this->db->executeQuery($query, $params);

      // Verificar si la consulta se ejecutó correctamente y devolver true en caso afirmativo, o false en caso contrario
      return ($stmt !== null);
   }
   public function converUtf8($value)
   {
      return mb_convert_encoding($value, "UTF-8", "UTF-8");
   }
   public function getProductById($id)
   {
      $query = "SELECT * FROM productos WHERE id = :id";
      $params = [':id' => $id];
      $stmt = $this->db->executeQuery($query, $params);
      if ($stmt) {
         $producto = $this->db->Consult($stmt); // Esto asume que se espera obtener solo un resultado
         if ($producto) {
            return [
               'id' => $producto->id,
               'codigo' => $producto->codigo,
               'nombre' => $this->converUtf8($producto->nombre),
               'descripcion' => $this->converUtf8($producto->descripcion),
               'stock' => $producto->stock,
               'precio' => $producto->precio,
               'precioOriginal' => $producto->precio,
               'cantidad' => 1,
               'status' => $producto->status,
            ];
         } else {
            return false;
         }

      } else {
         // Devolver null en caso de error o si el producto no se encontró
         return null;
      }
   }
   public function checkProductExists($codigo)
   {
      // Utiliza el código para verificar si el producto ya existe en la base de datos
      // Puedes realizar una consulta a la base de datos para buscar un producto con el mismo código
      $query = "SELECT * FROM productos WHERE codigo = :codigo";
      $params = [':codigo' => $codigo];
      $stmt = $this->db->executeQuery($query, $params);

      // Verificar si la consulta se ejecutó correctamente
      if ($stmt) {
         // Obtener el resultado de la consulta
         $producto = $this->db->Consult($stmt); // Esto asume que se espera obtener solo un resultado

         // Devolver true si el producto ya existe o false si no se encontró ningún producto con el mismo código
         return ($producto !== false);
      } else {
         // Devolver null en caso de error
         return null;
      }
   }

   public function getAllProducts()
   {
      $query = "SELECT * FROM productos"; // Cambiar "clientes" por "productos"
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