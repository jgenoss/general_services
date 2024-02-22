<?php

namespace App\Models;

use App\Controllers\DBController;

class OrdersModel
{
    private $db;
    private $pd;
    public function __construct()
    {
        $this->db = new DBController();
        $this->pd = new ProductsModel();
    }

    public function getClientEstablishment($id_cliente)
    {
        $query = "SELECT * FROM establecimientos WHERE id_cliente = :id_cliente";
        $params = [':id_cliente' => $id_cliente];
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
    public function deleteOrderById($pedido_id)
    {
        $query = "SELECT id, pedido_id, producto_id, cantidad FROM detalles_pedidos WHERE pedido_id = :pedido_id";
        $params = [
            ':pedido_id' => $pedido_id
        ];
        $stmt = $this->db->executeQuery($query, $params);
        if ($stmt) {
            $results = $this->db->AllConsult($stmt);
            foreach ($results as $value) {
                $paramss = [
                    ':producto_id' => $value->producto_id,
                    ':cantidad' => $value->cantidad
                ];
                // Corrección: Quita las comillas y realiza la suma directamente
                $this->db->executeQuery("UPDATE productos SET stock=stock+:cantidad WHERE id = :producto_id", $paramss);
            }
            // Corrección: Utiliza solo un marcador de posición para la consulta DELETE
            $this->db->executeQuery("DELETE pedidos, detalles_pedidos FROM pedidos INNER JOIN detalles_pedidos ON pedidos.id = detalles_pedidos.pedido_id WHERE pedidos.id = :pedido_id", $params);
            return true;
        } else {
            return null;
        }
    }
    public function updatePedido($id, $P)
    {
        $query = "UPDATE pedidos SET id_cliente=:id_cliente, id_establecimiento=:id_establecimiento, fecha_pedido=:fecha_pedido, estado=:estado, observacion=:observacion, id_usuario=:id_usuario, precio_sin_iva=:precio_sin_iva, precio_total=:precio_total WHERE id=:id";
        $params = [
            ':id' => $id,
            ':id_cliente' => $P[0],
            ':id_establecimiento' => $P[1],
            ':fecha_pedido' => $P[2],
            ':estado' => $P[3],
            ':observacion' => $P[4],
            ':id_usuario' => $P[6],
            ':precio_sin_iva' => $P[7],
            ':precio_total' => $P[8],
        ];
        $stmt = $this->db->executeQuery($query, $params);
        if ($stmt) {
            // Eliminar los detalles de pedido existentes
            $this->deletePedidoDetalles($id);

            // Insertar los nuevos detalles de pedido
            foreach ($P[5] as $detalle) {
                $this->insertDetalle_pedidos($id, $detalle['id'], $detalle['cantidad']);
                $this->discountStock($detalle['id'], $detalle['cantidad']);
            }

            return true;
        } else {
            return false;
        }
    }
    public function deletePedidoDetalles($pedido_id)
    {
        $query = "DELETE FROM detalles_pedidos WHERE pedido_id = :pedido_id";
        $params = [':pedido_id' => $pedido_id];
        $stmt = $this->db->executeQuery($query, $params);
        if ($stmt) {
            return true;
        } else {
            return false;
        }
    }

    public function insertPedidos($id_cliente, $id_establecimiento, $fecha_encargo, $estado, $observacion, $details, $user_id, $txid, $precio_sin_iva, $precio_total)
    {
        $query = "INSERT INTO pedidos(id_cliente, id_establecimiento, fecha_pedido, estado, observacion,id_usuario,txid,precio_sin_iva,precio_total)VALUES(:id_cliente,:id_establecimiento,:fecha_pedido,:estado,:observacion,:id_usuario,:txid,:precio_sin_iva,:precio_total)";
        $params = [
            ':id_cliente' => $id_cliente,
            ':id_establecimiento' => $id_establecimiento,
            ':fecha_pedido' => $fecha_encargo,
            ':estado' => $estado,
            ':observacion' => $observacion,
            ':id_usuario' => $user_id,
            ':txid' => $txid,
            ':precio_sin_iva' => $precio_sin_iva,
            ':precio_total' => $precio_total,
        ];
        $stmt = $this->db->executeQuery($query, $params);
        if ($stmt) {
            $id = $this->db->lastInsertId();
            foreach ($details as $key => $value) {
                $this->insertDetalle_pedidos($id, $value['id'], $value['cantidad']);
                $this->discountStock($value['id'], $value['cantidad']);
            }
            return true;
        } else {
            return null;
        }
    }
    public function insertDetalle_pedidos($pedido_id, $producto_id, $cantidad)
    {
        $query = "INSERT INTO detalles_pedidos(pedido_id,producto_id,cantidad)VALUES(:pedido_id,:producto_id,:cantidad)";
        $params = [
            'pedido_id' => $pedido_id,
            'producto_id' => $producto_id,
            'cantidad' => $cantidad,
        ];
        $stmt = $this->db->executeQuery($query, $params);
        if ($stmt) {
            return true;
        } else {
            return null;
        }
    }
    public function discountStock($id, $cantidad)
    {
        $query = "UPDATE productos SET stock=stock-:stock WHERE id = :id";
        // Parámetros con nombres
        $params = [
            ':id' => $id,
            ':stock' => $cantidad,
        ];
        $stmt = $this->db->executeQuery($query, $params);
        if ($stmt) {
            return true;
        } else {
            return null;
        }
    }
    public function updateStatus($id, $estado)
    {
        $query = "UPDATE pedidos SET estado = :estado WHERE id = :id";
        // Parámetros con nombres
        $params = [
            ':id' => $id,
            ':estado' => $estado,
        ];
        $stmt = $this->db->executeQuery($query, $params);
        if ($stmt) {
            return true;
        } else {
            return null;
        }
    }
    public function selectAllOrdersById($user_id)
    {
        $query = "SELECT p.precio_sin_iva,p.precio_total,p.txid, p.id,p.id_usuario,p.fecha_pedido, p.estado, p.observacion, c.tipo_identificacion, c.no_identificacion, c.nombres, c.apellidos, e.nombre FROM clientes AS c INNER JOIN establecimientos AS e ON c.id_cliente = e.id_cliente INNER JOIN pedidos AS p ON p.id_establecimiento = e.id_establecimiento AND c.id_cliente = p.id_cliente WHERE p.id_usuario = :id_usuario";
        $params = [':id_usuario' => $user_id];
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
    public function selectAllOrders()
    {
        $query = "SELECT p.precio_sin_iva,p.precio_total,p.txid, p.id,p.id_usuario,p.fecha_pedido, p.estado, p.observacion, c.tipo_identificacion, c.no_identificacion, c.nombres, c.apellidos, e.nombre FROM clientes AS c INNER JOIN establecimientos AS e ON c.id_cliente = e.id_cliente INNER JOIN pedidos AS p ON p.id_establecimiento = e.id_establecimiento AND c.id_cliente = p.id_cliente";
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
    public function selectOrderById($id)
    {
        $query = "SELECT p.txid, p.id,p.id_usuario,p.fecha_pedido, p.estado, p.observacion, c.tipo_identificacion, c.no_identificacion, c.nombres, c.apellidos, e.nombre,p.id_cliente,p.id_establecimiento,p.fecha_pedido FROM clientes AS c INNER JOIN establecimientos AS e ON c.id_cliente = e.id_cliente INNER JOIN pedidos AS p ON p.id_establecimiento = e.id_establecimiento AND c.id_cliente = p.id_cliente WHERE p.id = :id";
        $params = [':id' => $id];
        $stmt = $this->db->executeQuery($query, $params);
        if ($stmt) {
            $results = $this->db->Consult($stmt);
            if ($results) {
                return $results;
            }
        } else {
            return null;
        }
    }
    public function selectDetails_order($id)
    {
        $query = "SELECT * FROM detalles_pedidos WHERE pedido_id = :pedido_id";
        $params = [':pedido_id' => $id];
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