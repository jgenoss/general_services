<?php

namespace App\Models;

use App\Controllers\DBController;

class ServicesModel
{
    private $db;

    public function __construct()
    {
        $this->db = new DBController();
    }

    // Insertar un nuevo servicio
    public function insertService($serviceName, $description, $price, $iva)
    {
        $query = "INSERT INTO services (service_name, description, price, iva) VALUES (?, ?, ?, ?)";
        $params = [$serviceName, $description, $price, $iva];

        return $this->db->executeQuery($query, $params);
    }

    // Actualizar un servicio existente
    public function updateService($serviceId, $serviceName, $description, $price, $iva)
    {
        $query = "UPDATE services SET service_name = ?, description = ?, price = ?, iva = ? WHERE service_id = ?";
        $params = [$serviceName, $description, $price, $iva, $serviceId];

        return $this->db->executeQuery($query, $params);
    }

    // Eliminar un servicio por su ID
    public function deleteService($serviceId)
    {
        $query = "DELETE FROM services WHERE service_id = ?";
        $params = [$serviceId];

        return $this->db->executeQuery($query, $params);
    }

    // Obtener todos los servicios
    public function getAllServices()
    {
        $query = "SELECT * FROM services";
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


    // Obtener un servicio por su ID
    public function getServiceById($serviceId)
    {
        $query = "SELECT * FROM services WHERE service_id = ?";
        $params = [$serviceId];
        $result = $this->db->executeQuery($query, $params);

        return $this->db->Consult($result);
    }
}
