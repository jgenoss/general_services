<?php

namespace App\Controllers;

use App\Models\VisitModel;

class VisitController
{
    private $sessionController;
    private $functionController;
    private $visitModel;
    public function __construct()
    {
        $this->sessionController = SessionController::getInstance();
        $this->functionController = new FunctionController();
        $this->visitModel = new VisitModel();
    }
    public function registerVistit()
    {
        if ($this->functionController->verifyAuthToken()) {
            $P = [
                0 => $_SESSION['data']['user']['id'],
                1 => $_POST['nombre'],
                2 => $_POST['observacion'],
                3 => $_POST['direccion'],
                4 => $_POST['fecha'],
            ];
            if ($this->visitModel->insertVisit($P[0], $P[1], $P[2], $P[3], $P[4])) {
                $response = ['message' => 'Visita registrada con exito'];
                $this->functionController->sendResponse(200, $response);
            } else {
                $response = ['message' => 'Error al registrar visita'];
                $this->functionController->sendResponse(401, $response);
            }
        } else {
            $response = ['message' => 'Unauthorized access, invalid token'];
            $this->functionController->sendResponse(401, $response);
        }
    }
    public function getVisits()
    {
        if ($this->functionController->verifyAuthToken()) {
            $userInfo = $this->sessionController->getSessionData()['user'];
            if ($userInfo['role'] == 'admin' || $userInfo['role'] == 'editor') {
                $visitsInfo = $this->visitModel->getAllVisits();
            } else {
                $visitsInfo = $this->visitModel->getAllVisitsByUserId($_SESSION['data']['user']['id']);
            }
            $data = [];
            if (!empty($visitsInfo) && is_array($visitsInfo)) {
                foreach ($visitsInfo as $visit) {
                    $buttons = '<button type="button" value="' . $visit->visita_id . '" class="view btn btn-sm btn-icon btn-outline-primary"><i class="fas fa-edit"></i></button>';
                    $data[] = [
                        $buttons,
                        $this->functionController->converUtf8(strtoupper($visit->nombre)),
                        $this->functionController->converUtf8(strtoupper($visit->observacion)),
                        $this->functionController->converUtf8(strtoupper($visit->direccion)),
                        $visit->fechahora,
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
    public function getVisitById($id)
    {
        if ($this->functionController->verifyAuthToken()) {
            $visitInfo = $this->visitModel->getVisitById($id);
            if ($visitInfo) {
                $response = [
                    'visita_id' => $visitInfo->visita_id,
                    'nombre' => $visitInfo->nombre,
                    'observacion' => $visitInfo->observacion,
                    'direccion' => $visitInfo->direccion,
                    'fecha' => $visitInfo->fechahora,
                ];
                $this->functionController->sendResponse(200, $response);
            } else {
                $response = ['message' => 'Error al gestionar visita'];
                $this->functionController->sendResponse(401, $response);
            }
        } else {
            $response = ['message' => 'Unauthorized access, invalid token'];
            $this->functionController->sendResponse(401, $response);
        }
    }
}