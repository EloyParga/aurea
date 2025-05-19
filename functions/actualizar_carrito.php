<?php
session_start();
require_once 'db.php';

if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit;
}

$usuarioId = $_SESSION['usuario_id'];

// Obtener id_carrito
$stmt = $conn->prepare("SELECT id_carrito FROM carrito WHERE fk_id_usuario = ?");
$stmt->bind_param("i", $usuarioId);
$stmt->execute();
$result = $stmt->get_result();
$carrito = $result->fetch_assoc();

if (!$carrito) {
    header("Location: ../carrito.php");
    exit;
}

$idCarrito = $carrito['id_carrito'];

// Si se quiere eliminar producto
if (isset($_POST['eliminar'])) {
    $idProductoEliminar = intval($_POST['eliminar']);
    $stmt = $conn->prepare("DELETE FROM carrito_producto WHERE fk_id_carrito = ? AND fk_id_producto = ?");
    $stmt->bind_param("ii", $idCarrito, $idProductoEliminar);
    $stmt->execute();
}

// Si se quiere actualizar cantidades
if (isset($_POST['actualizar']) && isset($_POST['cantidades']) && is_array($_POST['cantidades'])) {
    foreach ($_POST['cantidades'] as $idProducto => $cantidad) {
        $cantidad = intval($cantidad);
        if ($cantidad < 1) $cantidad = 1;
        $idProducto = intval($idProducto);

        $stmt = $conn->prepare("UPDATE carrito_producto SET cantidad = ? WHERE fk_id_carrito = ? AND fk_id_producto = ?");
        $stmt->bind_param("iii", $cantidad, $idCarrito, $idProducto);
        $stmt->execute();
    }
}

header("Location: ../carrito.php");
exit;
