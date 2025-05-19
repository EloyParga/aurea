<?php
require_once('functions/db.php');
session_start();

// Verificar que el usuario esté logueado
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit;
}

$id_usuario = $_SESSION['usuario_id'];

// Obtener carrito del usuario
function obtenerCarrito($usuario_id) {
    global $conn;
    $sql = "SELECT p.id_producto, p.nombre, p.precio, cp.cantidad
            FROM carrito c
            JOIN carrito_producto cp ON c.id_carrito = cp.fk_id_carrito
            JOIN productos p ON cp.fk_id_producto = p.id_producto
            WHERE c.fk_id_usuario = ?";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $usuario_id);
    $stmt->execute();
    $resultado = $stmt->get_result();
    $carrito = $resultado->fetch_all(MYSQLI_ASSOC);
    $stmt->close();

    return $carrito;
}

$carrito = obtenerCarrito($id_usuario);

if (empty($carrito)) {
    echo "<p>No hay productos en el carrito.</p>";
    exit;
}

// Calcular total y si hay envío gratis
$total = 0;
foreach ($carrito as $item) {
    $total += $item['precio'] * $item['cantidad'];
}
$envio_gratis = $total >= 100 ? 1 : 0;
$fecha = date('Y-m-d H:i:s');

// Insertar en tabla compras
$stmt = $conn->prepare("INSERT INTO compras (fk_id_usuario, fecha, total, envio_gratis) VALUES (?, ?, ?, ?)");
if (!$stmt) {
    die("Error al preparar compra: " . $conn->error);
}
$stmt->bind_param("isdi", $id_usuario, $fecha, $total, $envio_gratis);
$stmt->execute();
$id_compra = $stmt->insert_id;
$stmt->close();

// Insertar detalles en compra_producto
$stmt_detalle = $conn->prepare("INSERT INTO compra_producto (fk_id_compra, fk_id_producto, cantidad, precio_unitario) VALUES (?, ?, ?, ?)");
if (!$stmt_detalle) {
    die("Error al preparar detalles de compra: " . $conn->error);
}

foreach ($carrito as $item) {
    $stmt_detalle->bind_param("iiid", $id_compra, $item['id_producto'], $item['cantidad'], $item['precio']);
    $stmt_detalle->execute();
}
$stmt_detalle->close();

// Vaciar carrito
// 1. Obtener id_carrito del usuario
$stmt_carrito = $conn->prepare("SELECT id_carrito FROM carrito WHERE fk_id_usuario = ?");
$stmt_carrito->bind_param("i", $id_usuario);
$stmt_carrito->execute();
$stmt_carrito->bind_result($id_carrito);
$stmt_carrito->fetch();
$stmt_carrito->close();

// 2. Eliminar productos del carrito
$stmt_del = $conn->prepare("DELETE FROM carrito_producto WHERE fk_id_carrito = ?");
$stmt_del->bind_param("i", $id_carrito);
$stmt_del->execute();
$stmt_del->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Compra Realizada</title>
    <link rel="stylesheet" href="assets/css/estilos.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="d-flex flex-column min-vh-100">
    <?php include('partials/navbar.php'); ?>

    <main class="flex-grow-1 d-flex justify-content-center align-items-center">
        <div class="container text-center" style="max-width: 600px;">
            <h2>¡Gracias por tu compra!</h2>
            <p>Compra Nº <strong><?= htmlspecialchars($id_compra) ?></strong></p>
            <p>Total: <strong><?= number_format($total, 2) ?> €</strong></p>
            <p>Envío: <strong><?= $envio_gratis ? 'Gratis' : 'Se aplicará coste de envío' ?></strong></p>
            <hr>
            <h4>Resumen:</h4>
            <ul class="list-unstyled">
                <?php foreach ($carrito as $item): ?>
                    <li><?= htmlspecialchars($item['nombre']) ?> - <?= $item['cantidad'] ?> x <?= number_format($item['precio'], 2) ?> €</li>
                <?php endforeach; ?>
            </ul>

            <a href="catalogo.php" class="btn btn-primary mt-3">Seguir comprando</a>
        </div>
    </main>

    <?php include('partials/footer.php'); ?>
</body>
</html>
