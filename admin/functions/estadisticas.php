<?php
require_once(__DIR__ . '/../../functions/db.php'); 

function obtenerTotalesSistema() {
    global $conn;

    $usuarios = $conn->query("SELECT COUNT(*) AS total FROM usuarios")->fetch_assoc()['total'];
    $productos = $conn->query("SELECT COUNT(*) AS total FROM productos")->fetch_assoc()['total'];
    $pedidos = $conn->query("SELECT COUNT(*) AS total FROM compras")->fetch_assoc()['total'];
    $ingresos = $conn->query("SELECT SUM(total) AS total FROM compras")->fetch_assoc()['total'];

    return [
        'usuarios' => $usuarios,
        'productos' => $productos,
        'pedidos' => $pedidos,
        'ingresos' => $ingresos ?: 0
    ];
}

function obtenerVentasMensuales() {
    global $conn;

    $query = "SELECT DATE_FORMAT(fecha, '%Y-%m') AS mes, SUM(total) AS total
              FROM compras
              GROUP BY mes
              ORDER BY mes DESC
              LIMIT 6";

    $result = $conn->query($query);
    $meses = $valores = [];

    while ($row = $result->fetch_assoc()) {
        $meses[] = $row['mes'];
        $valores[] = round($row['total'], 2);
    }

    return ['meses' => array_reverse($meses), 'valores' => array_reverse($valores)];
}

function obtenerUsuariosPorMes() {
    global $conn;

    $query = "SELECT DATE_FORMAT(fecha_nac, '%Y-%m') AS mes, COUNT(*) AS total
              FROM usuarios
              GROUP BY mes
              ORDER BY mes DESC
              LIMIT 6";

    $result = $conn->query($query);
    $meses = $valores = [];

    while ($row = $result->fetch_assoc()) {
        $meses[] = $row['mes'];
        $valores[] = $row['total'];
    }

    return ['meses' => array_reverse($meses), 'valores' => array_reverse($valores)];
}

function obtenerTopProductos() {
    global $conn;

    $query = "SELECT p.nombre, SUM(cp.cantidad) AS total
              FROM compra_producto cp
              JOIN productos p ON cp.fk_id_producto = p.id_producto
              GROUP BY cp.fk_id_producto
              ORDER BY total DESC
              LIMIT 5";

    $result = $conn->query($query);
    $nombres = $cantidades = [];

    while ($row = $result->fetch_assoc()) {
        $nombres[] = $row['nombre'];
        $cantidades[] = $row['total'];
    }

    return ['nombres' => $nombres, 'cantidades' => $cantidades];
}

function obtenerPromedioPuntuacion() {
    global $conn;

    $query = "SELECT p.nombre, AVG(r.puntuacion) AS promedio
              FROM resenas r
              JOIN productos p ON r.fk_id_producto = p.id_producto
              GROUP BY r.fk_id_producto
              ORDER BY promedio DESC
              LIMIT 5";

    $result = $conn->query($query);
    $nombres = $valores = [];

    while ($row = $result->fetch_assoc()) {
        $nombres[] = $row['nombre'];
        $valores[] = round($row['promedio'], 2);
    }

    return ['nombres' => $nombres, 'valores' => $valores];
}
