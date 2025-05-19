<?php
session_start();
header('Content-Type: application/json');

// Validar sesión
if (!isset($_SESSION['usuario_id'])) {
    http_response_code(403);
    echo json_encode(['error' => 'Debe iniciar sesión para agregar al carrito']);
    exit;
}

require_once __DIR__ . '/db.php';

if (!isset($_POST['producto_id']) || !isset($_POST['cantidad'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Faltan datos necesarios']);
    exit;
}

$productoId = intval($_POST['producto_id']);
$cantidad = intval($_POST['cantidad']);
$usuarioId = intval($_SESSION['usuario_id']);

if ($productoId <= 0 || $cantidad <= 0) {
    http_response_code(400);
    echo json_encode(['error' => 'Datos inválidos']);
    exit;
}

// 1. Verificar si el usuario ya tiene un carrito
$query = "SELECT id_carrito FROM carrito WHERE fk_id_usuario = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $usuarioId);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    $carritoId = $row['id_carrito'];
} else {
    // Crear carrito nuevo si no existe
    $insertCarrito = $conn->prepare("INSERT INTO carrito (fk_id_usuario) VALUES (?)");
    $insertCarrito->bind_param("i", $usuarioId);
    $insertCarrito->execute();
    $carritoId = $conn->insert_id;
}

// 2. Verificar si el producto ya está en el carrito
$query = "SELECT cantidad FROM carrito_producto WHERE fk_id_carrito = ? AND fk_id_producto = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("ii", $carritoId, $productoId);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    // Ya existe: actualizar cantidad
    $nuevaCantidad = $row['cantidad'] + $cantidad;
    $update = $conn->prepare("UPDATE carrito_producto SET cantidad = ? WHERE fk_id_carrito = ? AND fk_id_producto = ?");
    $update->bind_param("iii", $nuevaCantidad, $carritoId, $productoId);
    $update->execute();
} else {
    // No existe: insertar
    $insert = $conn->prepare("INSERT INTO carrito_producto (fk_id_carrito, fk_id_producto, cantidad) VALUES (?, ?, ?)");
    $insert->bind_param("iii", $carritoId, $productoId, $cantidad);
    $insert->execute();
}

echo json_encode(['success' => true, 'mensaje' => 'Producto agregado al carrito']);
exit;
?>
