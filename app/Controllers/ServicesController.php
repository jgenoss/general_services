<?php

namespace App\Controllers;

use App\Models\ServicesModel;

class ServicesController
{
   private $servicesModel;
   private $functionController;

   public function __construct()
   {
      $this->servicesModel = new ServicesModel();
      $this->functionController = new FunctionController();
   }

   // Procesar el formulario para agregar un nuevo servicio
   public function addService()
   {
      if ($this->functionController->verifyAuthToken()) {
         // Puedes obtener los datos del formulario y llamar a la función de inserción del modelo
         $serviceName = $_POST['service_name'];
         $description = $_POST['description'];
         $price = $_POST['price'];
         $iva = $_POST['iva'];

         if ($$this->servicesModel->insertService($serviceName, $description, $price, $iva)) {
            $response = ['message' => "El servicio se ha agregado correctamente."];
            $this->functionController->sendResponse(200, $response);
         } else {
            $response = ['message' => "Hubo un problema al agregar el servicio."];
            $this->functionController->sendResponse(400, $response);
         }
      } else {
         $response = ['message' => 'Unauthorized access, invalid token'];
         $this->functionController->sendResponse(401, $response);
      }
   }

   // Procesar el formulario para editar un servicio
   public function editService($id)
   {
      if ($this->functionController->verifyAuthToken()) {
         $serviceId = isset($id) ? $id : null;
         if ($serviceId) {
            // Puedes obtener los datos del formulario y llamar a la función de actualización del modelo
            $serviceName = $_POST['service_name'];
            $description = $_POST['description'];
            $price = $_POST['price'];
            $iva = $_POST['iva'];

            if ($this->servicesModel->updateService($serviceId, $serviceName, $description, $price, $iva)) {
               $response = ['message' => "El servicio se ha actualizado correctamente."];
               $this->functionController->sendResponse(200, $response);
            } else {
               $response = ['message' => "Hubo un problema al actualizar el servicio."];
               $this->functionController->sendResponse(400, $response);
            }
         } else {
            // Show an error message if ID is not provided
            $response = ['message' => 'Element ID not provided'];
            $this->functionController->sendResponse(400, $response);
         }
      } else {
         $response = ['message' => 'Unauthorized access, invalid token'];
         $this->functionController->sendResponse(401, $response);
      }
   }

   // Eliminar un servicio
   public function deleteService($id)
   {
      if ($this->functionController->verifyAuthToken()) {
         if ($this->servicesModel->deleteService($id)) {
            $response = ['message' => "El servicio se ha eliminado correctamente."];
            $this->functionController->sendResponse(200, $response);
         } else {
            $response = ['message' => "Hubo un problema al eliminar el servicio."];
            $this->functionController->sendResponse(400, $response);
         }
      } else {
         $response = ['message' => 'Unauthorized access, invalid token'];
         $this->functionController->sendResponse(401, $response);
      }
   }

   // Mostrar todos los servicios
   public function showAllServices()
   {
      if ($this->functionController->verifyAuthToken()) {
         $services = $this->servicesModel->getAllServices();
         $data = $this->formatServicesData($services);
         return $this->functionController->sendResponse(200, $data);
      } else {
         $response = ['message' => 'Unauthorized access, invalid token'];
         $this->functionController->sendResponse(401, $response);
      }
   }
   public function formatServicesData($results)
   {
      $formattedData = [];
      if (is_array($results)) {
         foreach ($results as $key => $value) {
            $buttons = $this->generateButtons($value->service_id);
            $formattedData[] = [
               $buttons,
               $value->service_name,
               $value->description,
               $value->price,
               $value->iva
            ];
         }
      }
      return [
         "sEcho" => 1,
         "iTotalRecords" => count($formattedData),
         "iTotalDisplayRecords" => count($formattedData),
         "data" => $formattedData
      ];

   }
   public function generateButtons($serviceId)
   {
      $editButton = '<button type="button" value="' . $serviceId . '" class="edit btn btn-sm btn-icon btn-outline-primary"><i class="fas fa-edit"></i></button>';
      $trashButton = '<button type="button" value="' . $serviceId . '" class="trash btn btn-sm btn-icon btn-outline-warning"><i class="fas fa-trash"></i></button>';
      return ($serviceId) ? $editButton . $trashButton : $editButton . $trashButton;
   }

   // Mostrar detalles de un servicio 
   public function showServiceDetails($serviceId)
   {
      $service = $this->servicesModel->getServiceById($serviceId);

      // Puedes implementar la lógica para mostrar los detalles del servicio aquí
      echo "Mostrar detalles del servicio con ID: $serviceId";
      print_r($service);
   }
}
