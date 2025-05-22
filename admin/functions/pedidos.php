<?php
require_once(__DIR__ . '/../../functions/db.php');

function obtenerPedidosConProductos() {
    global $conn;

    // Obtener pedidos básicos
    $query = "SELECT c.*, u.nombre_usuario AS nombre_usuario 
              FROM compras c 
              JOIN usuarios u ON c.fk_id_usuario = u.id_usuario
              ORDER BY c.fecha DESC";
    $result = $conn->query($query);

    $pedidos = [];

    while ($pedido = $result->fetch_assoc()) {
        $pedido_id = $pedido['id_compra'];
        $pedido['productos'] = [];

        // Obtener productos por pedido
        $query_productos = "SELECT cp.*, p.nombre 
                            FROM compra_producto cp 
                            JOIN productos p ON cp.fk_id_producto = p.id_producto
                            WHERE cp.fk_id_compra = $pedido_id";
        $res_productos = $conn->query($query_productos);

        while ($producto = $res_productos->fetch_assoc()) {
            $pedido['productos'][] = $producto;
        }

        $pedidos[] = $pedido;
    }

    return $pedidos;
}
