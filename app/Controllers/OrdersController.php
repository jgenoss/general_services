<?php

namespace App\Controllers;

use App\Models\ClientModel;
use App\Models\EstablishmentModel;
use App\Models\OrdersModel;
use App\Models\ProductsModel;
use App\Models\UserModel;

class OrdersController
{
    private $ordersModel;
    private $productsModel;
    //private $mailController;
    private $sessionController;
    private $functionController;
    private $clientModel;
    private $establishmentModel;
    private $userModel;
    public function __construct()
    {
        $this->ordersModel = new OrdersModel();
        $this->productsModel = new ProductsModel();
        //$this->mailController = new EmailController();
        $this->functionController = new FunctionController();
        $this->clientModel = new ClientModel();
        $this->establishmentModel = new EstablishmentModel();
        $this->userModel = new UserModel();
        $this->sessionController = SessionController::getInstance();
    }
    public function getEstablishment($id)
    {
        if ($this->functionController->verifyAuthToken()) {
            $elementId = isset($id) ? $id : null;
            if ($elementId !== null) {
                $data = $this->ordersModel->getClientEstablishment($elementId);
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
    public function deleteOrderById($id)
    {
        if ($this->functionController->verifyAuthToken()) {
            $elementId = isset($id) ? $id : null;
            if ($elementId !== null) {
                if ($this->ordersModel->deleteOrderById($elementId)) {
                    $response = ['message' => 'Changes made successfully'];
                    $this->functionController->sendResponse(200, $response);

                } else {
                    $response = ['message' => 'Error delete order'];
                    $this->functionController->sendResponse(400, $response);
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
    public function getAllProducts()
    {
        if ($this->functionController->verifyAuthToken()) {
            $productos = $this->productsModel->getAllProducts();
            $data = [];
            if (!empty($productos) && is_array($productos)) {
                foreach ($productos as $producto) {
                    $buttons = '<button type="button" value="' . $producto->id . '" class="plus btn btn-sm btn-icon btn-outline-primary"><i class="fas fa-plus"></i></button>';
                    $statusBadge = ($producto->status == 'true') ? '<span class="badge badge-light-success">ACTIVE</span>' : '<span class="badge badge-light-danger">INACTIVE</span>';
                    $data[] = [
                        $buttons,
                        $this->functionController->converUtf8(strtoupper($producto->nombre)),
                        $producto->stock,
                        $producto->precio,
                        $statusBadge
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
    function formatCurrency($number)
    {
        $formattedNumber = number_format($number, 2, ',', '.');
        $formattedCurrency = "$ " . $formattedNumber;
        return $formattedCurrency;
    }
    public function getAllOrdersById()
    {
        if ($this->functionController->verifyAuthToken()) {
            $userInfo = $this->sessionController->getSessionData()['user'];
            if ($userInfo['role'] == 'admin' || $userInfo['role'] == 'editor') {
                $orders = $this->ordersModel->selectAllOrders();
            } else {
                $orders = $this->ordersModel->selectAllOrdersById($_SESSION['data']['user']['id']);
            }
            $data = array();
            if (!empty($orders) && is_array($orders)) {
                foreach ($orders as $order) {
                    $data[] = array(
                        'id' => $order->id,
                        'txid' => $order->txid,
                        'nombres' => $this->functionController->converUtf8(strtoupper($order->nombres)),
                        'nombre' => $this->functionController->converUtf8(strtoupper($order->nombre)),
                        'precio_sin_iva' => $this->formatCurrency($order->precio_sin_iva),
                        'precio_total' => $this->formatCurrency($order->precio_total),
                        'fecha_pedido' => $order->fecha_pedido,
                        'status' => ($order->estado == 'facturado') ? $order->estado : 'pendiente',
                    );
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
    public function checkStock()
    {
        if ($this->functionController->verifyAuthToken()) {
            $Id = isset($_POST['id_product']) ? $_POST['id_product'] : null;
            $cantidad = isset($_POST['cantidad']) ? $_POST['cantidad'] : null;
            $infoProduct = $this->productsModel->getProductById($Id);
            if ($infoProduct) {
                if ($cantidad > $infoProduct['stock']) {
                    $response = ['message' => 'Cantidad mayor a la existencia', 'max' => $infoProduct['stock']];
                    $this->functionController->sendResponse(401, $response);
                } else if ($cantidad <= 0) {
                    $response = ['message' => 'Por favor introdusca una cantidad valida', 'max' => 1];
                    $this->functionController->sendResponse(401, $response);
                }
            }
        } else {
            $response = ['message' => 'Unauthorized access, invalid token'];
            $this->functionController->sendResponse(401, $response);
        }
    }
    public function registerOrdersAndEdit()
    {
        if ($this->functionController->verifyAuthToken()) {
            $id = isset($_POST['id']) ? $_POST['id'] : null;
            $P = [
                0 => $_POST['id_cliente'],
                1 => $_POST['id_establecimiento'],
                2 => $_POST['fechaPedido'],
                3 => $_POST['estado'],
                4 => (isset($_POST['observacion'])) ? $_POST['observacion'] : '',
                5 => $_POST['details'],
                6 => $_SESSION['data']['user']['id'],
                7 => $_POST['precioSinIva'],
                8 => $_POST['precioTotal'],
            ];
            $txid = 'TXID-' . uniqid();

            if ($id !== null) {
                $success = $this->ordersModel->updatePedido($id, $P);
                if ($success) {
                    $response = ['message' => 'Cambios realizados con éxito'];
                    $this->functionController->sendResponse(200, $response);
                } else {
                    $response = ['message' => 'Error al actualizar el pedido'];
                    $this->functionController->sendResponse(401, $response);
                }
            } else {
                $success = $this->sendMailPedidos($P[0], $P[1], $P[5], $txid);
                if ($success) {
                    $success = $this->ordersModel->insertPedidos($P[0], $P[1], $P[2], $P[3], $P[4], $P[5], $P[6], $txid, $P[7], $P[8]);
                    if ($success) {
                        $response = ['message' => 'Pedido realizado con éxito'];
                        $this->functionController->sendResponse(200, $response);
                    } else {
                        $response = ['message' => 'Error al registrar pedido'];
                        $this->functionController->sendResponse(401, $response);
                    }
                } else {
                    $response = ['message' => 'Error al registrar pedido'];
                    $this->functionController->sendResponse(401, $response);
                }
            }
        } else {
            $response = ['message' => 'Unauthorized access, invalid token'];
            $this->functionController->sendResponse(401, $response);
        }
    }

    /*
    public function registerOrdersAndEdit()
    {
        if ($this->functionController->verifyAuthToken()) {
            $id = isset($_POST['id']) ? $_POST['id'] : null;
            $P = [
                0 => $_POST['id_cliente'],
                1 => $_POST['id_establecimiento'],
                2 => $_POST['fechaPedido'],
                3 => $_POST['estado'],
                4 => (isset($_POST['observacion'])) ? $_POST['observacion'] : '',
                5 => $_POST['details'],
                6 => $_SESSION['data']['user']['id'],
                7 => $_POST['precioSinIva'],
                8 => $_POST['precioTotal'],
            ];
            $txid = 'TXID-' . uniqid();
            if ($id !== null) {
                if ($this->ordersModel->updateStatus($id, $P[3])) {
                    $response = ['message' => 'Cambios realizados con exito'];
                    $this->functionController->sendResponse(200, $response);
                } else {
                    $response = ['message' => 'Error al registrar cambios en pedidos'];
                    $this->functionController->sendResponse(401, $response);
                }
            } else {
                if ($this->sendMailPedidos($P[0], $P[1], $P[5], $txid)) {
                    if ($this->ordersModel->insertPedidos($P[0], $P[1], $P[2], $P[3], $P[4], $P[5], $P[6], $txid, $P[7], $P[8])) {
                        $response = ['message' => 'Pedido realizado con exito'];
                        $this->functionController->sendResponse(200, $response);
                    } else {
                        $response = ['message' => 'Error al registrar pedido'];
                        $this->functionController->sendResponse(401, $response);
                    }
                } else {
                    $response = ['message' => 'Error al registrar pedido'];
                    $this->functionController->sendResponse(401, $response);
                }
            }
        } else {
            $response = ['message' => 'Unauthorized access, invalid token'];
            $this->functionController->sendResponse(401, $response);
        }
    }
    */
    public function getOrderById($id)
    {
        if ($this->functionController->verifyAuthToken()) {
            $orderInfo = $this->ordersModel->selectOrderById($id);
            $detailsInfo = $this->ordersModel->selectDetails_order($id);
            if ($orderInfo) {
                $order = [
                    "txid" => $orderInfo->txid,
                    "id" => $orderInfo->id,
                    "id_usuario" => $orderInfo->id_usuario,
                    "fechaPedido" => $orderInfo->fecha_pedido,
                    "estado" => $orderInfo->estado,
                    "observacion" => $this->functionController->converUtf8($orderInfo->observacion),
                    "tipo_identificacion" => $orderInfo->tipo_identificacion,
                    "no_identificacion" => $orderInfo->no_identificacion,
                    "nombres" => $this->functionController->converUtf8($orderInfo->nombres),
                    "apellidos" => $this->functionController->converUtf8($orderInfo->apellidos),
                    "id_cliente" => $orderInfo->id_cliente,
                    "id_establecimiento" => $orderInfo->id_establecimiento,
                ];
                $details = array();
                foreach ($detailsInfo as $key => $value) {
                    $infoProduct = $this->productsModel->getProductById($value->producto_id);
                    $details[] = [
                        'id' => $infoProduct['id'],
                        'codigo' => $this->functionController->converUtf8($infoProduct['codigo']),
                        'nombre' => $this->functionController->converUtf8($infoProduct['nombre']),
                        'precio' => $infoProduct['precio'],
                        'cantidad' => $value->cantidad,
                    ];
                }
                $response = ['orders' => $order, 'details' => $details];
                $this->functionController->sendResponse(200, $response);
            } else {
                $response = ['message' => 'Error al editar registrar pedido'];
                $this->functionController->sendResponse(401, $response);
            }
        } else {
            $response = ['message' => 'Unauthorized access, invalid token'];
            $this->functionController->sendResponse(401, $response);
        }
    }
    function sendMailPedidos($id_cliente, $id_establecimiento, $productos, $txid)
    {
        $clientInfo = $this->clientModel->getClientData($id_cliente);
        $establishmentInfo = $this->establishmentModel->getEstablishment($id_establecimiento);

        $contenido = '<h4>NUEVO PEDIDO</h4>';
        $contenido .= '<h4>ID DE TRANSACCION: ' . $txid . '</h4>';
        $contenido .= '<h4>VENDEDOR: ' . strtoupper($_SESSION['data']['user']['nombre']) . '</h4>';
        $contenido .= '<P>Cliente Nombres y Apellidos: ' . $clientInfo['nombres'] . ' ' . $clientInfo['apellidos'] . '</P>';
        $contenido .= '<P>¬ Tipo de itentificaion y numero: ' . $clientInfo['tipo_identificacion'] . '-' . $clientInfo['no_identificacion'] . '</P>';
        $contenido .= '<P>¬ Telefono: ' . $clientInfo['telefono'] . '</P>';
        $contenido .= '<P>¬ Correo Electronico: ' . $clientInfo['email'] . '</P>';
        $contenido .= '<P>Establecimiento: ' . strtoupper($establishmentInfo['nombre']) . '</P>';
        $contenido .= '<P>¬ Telefono: ' . $establishmentInfo['telefono'] . '</P>';
        $contenido .= '<P>¬ Correo Electronico: ' . $establishmentInfo['correo_electronico'] . '</P>';
        $contenido .= '<table border="1">';
        $contenido .= '<thead>';
        $contenido .= '<tr>';
        $contenido .= '<th>CODIGO</th>';
        $contenido .= '<th>PRODUCTO</th>';
        $contenido .= '<th>DESCRIPCION</th>';
        $contenido .= '<th>CANTIDAD</th>';
        $contenido .= '</tr>';
        $contenido .= '</thead>';
        $contenido .= '<tbody>';
        foreach ($productos as $key => $value) {
            $infoProduct = $this->productsModel->getProductById($value['id']);
            $contenido .= '<tr>';
            $contenido .= '<td>' . $infoProduct['codigo'] . '</td>';
            $contenido .= '<td>' . $this->functionController->converUtf8($infoProduct['nombre']) . '</td>';
            $contenido .= '<td>' . $this->functionController->converUtf8($infoProduct['descripcion']) . '</td>';
            $contenido .= '<td style="text-align:center;">' . $value['cantidad'] . '</td>';
            $contenido .= '</tr>';
        }
        $contenido .= '</tbody>';
        $contenido .= '</table>';
        $mailController = new EmailController();
        $usersInfo = $this->userModel->getAllUsers();
        foreach ($usersInfo as $key => $value) {
            if ($value->role == 'admin' || $value->role == 'editor') {
                if ($mailController->sendMail('pedidos@decasoluciones.co', $value->email, 'NUEVO PEDIDO CLIENTE - ' . $clientInfo['nombres'] . '', $contenido))
                    ;
            }
        }
        return true;
    }
}