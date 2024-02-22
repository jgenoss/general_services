<?php

namespace App\Models;

use App\Controllers\DBController;

class VisitModel
{
    private $db;
    public function __construct()
    {
        $this->db = new DBController();
    }

    public function insertVisit($usuario_id, $nombre, $observacion, $direccion, $fechahora)
    {
        $query = "INSERT INTO visitas(usuario_id, nombre, observacion, direccion, fechahora)VALUES(:usuario_id, :nombre, :observacion, :direccion, :fechahora)";
        // Parámetros con nombres
        $params = [
            ':usuario_id' => $usuario_id,
            ':nombre' => $nombre,
            ':observacion' => $observacion,
            ':direccion' => $direccion,
            ':fechahora' => $fechahora,
        ];
        $stmt = $this->db->executeQuery($query, $params);
        if ($stmt) {
            return true;
        } else {
            return null;
        }
    }
    public function getAllVisitsByUserId($id)
    {
        $query = "SELECT * FROM visitas WHERE usuario_id = :usuario_id";
        $params = [':usuario_id' => $id];
        $stmt = $this->db->executeQuery($query, $params);
        if ($stmt) {
            $results = $this->db->AllConsult($stmt);
            if ($results) {
                return $results;
            } else {
                return null;
            }
        } else {
            return null;
        }
    }
    public function getAllVisits()
    {
        $query = "SELECT * FROM visitas";
        $params = [];
        $stmt = $this->db->executeQuery($query, $params);
        if ($stmt) {
            $results = $this->db->AllConsult($stmt);
            if ($results) {
                return $results;
            } else {
                return null;
            }
        } else {
            return null;
        }
    }
    public function getVisitById($id)
    {
        $query = "SELECT * FROM visitas WHERE visita_id = :visita_id";
        $params = [':visita_id' => $id];
        $stmt = $this->db->executeQuery($query, $params);
        if ($stmt) {
            $results = $this->db->Consult($stmt);
            if ($results) {
                return $results;
            } else {
                return null;
            }
        } else {
            return null;
        }
    }
}