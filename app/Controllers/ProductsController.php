<?php
namespace App\Controllers;

use App\Models\ProductsModel;

class ProductsController
{
   private $productsModel;
   private $sessionController;
   private $functionController;

   public function __construct()
   {
      $this->sessionController = SessionController::getInstance();
      $this->functionController = new FunctionController();
      $this->productsModel = new ProductsModel();
   }
   public function registerProductAndEdit()
   {
      if ($this->functionController->verifyAuthToken()) {
         $idProducto = isset($_POST['id']) ? $_POST['id'] : null;
         $codigo = $_POST['codigo'];
         $nombre = $_POST['nombre'];
         $descripcion = $_POST['descripcion'];
         $stock = $_POST['stock'];
         $precio = $_POST['precio'];
         $status = $_POST['status'];

         if ($idProducto !== null) {
            if ($this->productsModel->updateProduct($idProducto, $codigo, $nombre, $descripcion, $stock, $precio, $status)) {
               $response = ['message' => 'Cambios realizados con éxito'];
               $this->functionController->sendResponse(200, $response);
            } else {
               $response = ['message' => 'Error al editar el producto'];
               $this->functionController->sendResponse(400, $response);
            }
         } else {
            if ($this->productsModel->insertProduct($codigo, $nombre, $descripcion, $stock, $precio, $status)) {
               $response = ['message' => 'Producto registrado exitosamente'];
               $this->functionController->sendResponse(200, $response);
            } else {
               $response = ['message' => 'Error al registrar el producto'];
               $this->functionController->sendResponse(400, $response);
            }
         }
      } else {
         $response = ['message' => 'Acceso no autorizado, token inválido'];
         $this->functionController->sendResponse(401, $response);
      }
   }

   public function getAllProducts()
   {
      if ($this->functionController->verifyAuthToken()) {
         $productos = $this->productsModel->getAllProducts();
         $data = [];
         if (!empty($productos) && is_array($productos)) {
            foreach ($productos as $producto) {
               $data[] = [
                  'id' => $producto->id,
                  'codigo' => $producto->codigo,
                  'nombre' => $this->functionController->converUtf8(ucfirst($producto->nombre)),
                  'descripcion' => $this->functionController->converUtf8($producto->descripcion),
                  'precio' => $producto->precio,
                  'stock' => $producto->stock,
                  'status' => ($producto->status == 'true') ? true : false,
               ];
            }
         }
         $response = [
            "sEcho" => 1,
            "iTotalRecords" => count($data),
            "iTotalDisplayRecords" => count($data),
            "data" => $data
         ];

         $this->functionController->sendResponse(200, $response);
      } else {
         $response = ['message' => 'Unauthorized access, invalid token'];
         $this->functionController->sendResponse(401, $response);
      }
   }

   public function getProduct($id)
   {
      if ($this->functionController->verifyAuthToken()) {
         $elementId = isset($id) ? $id : null;
         if ($elementId !== null) {
            $producto = $this->productsModel->getProductById($elementId);

            if ($producto) {
               $data = [$producto];
               $this->functionController->sendResponse(200, $data);
            } else {
               // Show an error message if the product is not found
               $data = ['message' => 'Product not found'];
               $this->functionController->sendResponse(404, $data);
            }
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