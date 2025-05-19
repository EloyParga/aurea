<?php
session_start();
require_once 'functions/db.php'; // Incluye tu conexión

// Función para obtener productos del carrito del usuario
function obtenerCarritoUsuario(int $usuarioId) {
    global $conn;

    // Obtener id_carrito
    $stmt = $conn->prepare("SELECT id_carrito FROM carrito WHERE fk_id_usuario = ?");
    $stmt->bind_param("i", $usuarioId);
    $stmt->execute();
    $result = $stmt->get_result();
    $carrito = $result->fetch_assoc();

    if (!$carrito) {
        return [];
    }

    $idCarrito = $carrito['id_carrito'];

    // Obtener productos y cantidades del carrito_producto
    $sql = "SELECT p.id_producto, p.nombre, p.descripcion, p.imagen_url, p.precio, cp.cantidad
            FROM carrito_producto cp
            JOIN productos p ON cp.fk_id_producto = p.id_producto
            WHERE cp.fk_id_carrito = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $idCarrito);
    $stmt->execute();
    $result = $stmt->get_result();

    return $result->fetch_all(MYSQLI_ASSOC);
}

// Redirigir si no está logueado
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit;
}

$usuarioId = $_SESSION['usuario_id'];
$productosCarrito = obtenerCarritoUsuario($usuarioId);

// Calcular total
$totalCarrito = 0;
foreach ($productosCarrito as $prod) {
    $totalCarrito += $prod['precio'] * $prod['cantidad'];
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <title>Mi Carrito - Aurea</title>
    <link rel="stylesheet" href="assets/css/estilos.css" />
    <style>
        .carrito-container {
            max-width: 900px;
            margin: 40px auto;
            padding: 0 15px;
            font-family: 'Roboto', sans-serif;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 12px 10px;
            text-align: center;
            border-bottom: 1px solid #ddd;
        }
        img.producto-imagen {
            max-width: 80px;
            height: auto;
        }
        .total {
            font-weight: bold;
            font-size: 1.2em;
            text-align: right;
            margin-top: 20px;
        }
        .cantidad-input {
            width: 60px;
            padding: 5px;
            text-align: center;
        }
        .btn-actualizar, .btn-eliminar {
            background-color: #9D7AC0;
            border: none;
            color: white;
            padding: 6px 12px;
            cursor: pointer;
            border-radius: 4px;
            font-weight: 500;
        }
        .btn-eliminar {
            background-color: #cc3333;
        }
        .btn-actualizar:hover {
            background-color: #7a599d;
        }
        .btn-eliminar:hover {
            background-color: #a32929;
        }
    </style>
</head>
<body>
<?php include 'partials/navbar.php'; ?>

<div class="carrito-container">
    <h1>Mi Carrito de Compras</h1>

    <?php if (empty($productosCarrito)): ?>
        <p>Tu carrito está vacío. <a href="catalogo.php">Ver productos</a></p>
    <?php else: ?>
        <form method="POST" action="functions/actualizar_carrito.php">
            <table>
                <thead>
                    <tr>
                        <th>Producto</th>
                        <th>Imagen</th>
                        <th>Precio</th>
                        <th>Cantidad</th>
                        <th>Subtotal</th>
                        <th>Acción</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($productosCarrito as $prod): ?>
                        <tr>
                            <td><?= htmlspecialchars($prod['nombre']) ?></td>
                            <td><img class="producto-imagen" src="<?= htmlspecialchars($prod['imagen_url']) ?>" alt="<?= htmlspecialchars($prod['nombre']) ?>" /></td>
                            <td>$<?= number_format($prod['precio'], 2) ?></td>
                            <td>
                                <input type="number" name="cantidades[<?= $prod['id_producto'] ?>]" value="<?= $prod['cantidad'] ?>" min="1" class="cantidad-input" />
                            </td>
                            <td>$<?= number_format($prod['precio'] * $prod['cantidad'], 2) ?></td>
                            <td>
                                <button type="submit" name="eliminar" value="<?= $prod['id_producto'] ?>" class="btn-eliminar" onclick="return confirm('¿Eliminar este producto del carrito?')">Eliminar</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <div class="total">Total: $<?= number_format($totalCarrito, 2) ?></div>

            <div style="margin-top: 20px;">
                <button type="submit" name="actualizar" class="btn-actualizar">Actualizar cantidades</button>
                <a href="catalogo.php" class="btn-actualizar" style="text-decoration:none; background:#777; margin-left: 10px;">Seguir comprando</a>
                <a href="checkout.php" class="btn-actualizar" style="text-decoration:none; background:#4CAF50; margin-left: 10px;">Finalizar compra</a>
            </div>
        </form>
    <?php endif; ?>
</div>

</body>
</html>
