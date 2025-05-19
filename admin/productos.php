<?php
session_start();
if (!isset($_SESSION['id_usuario']) || $_SESSION['tipo_usuario'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

require_once('../functions/db.php');

// Obtener todos los productos
$sql = "SELECT * FROM productos";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Productos - Administración</title>
</head>
<body>
    <?php include('../partials/navbar.php'); ?>

    <div class="container mt-5">
        <h2>Listado de Productos</h2>
        <a href="nuevo_producto.php" class="btn btn-primary mb-3">Agregar Producto</a>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th><th>Nombre</th><th>Precio</th><th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php while($producto = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= $producto['id_producto'] ?></td>
                        <td><?= htmlspecialchars($producto['nombre']) ?></td>
                        <td><?= number_format($producto['precio'], 2) ?> €</td>
                        <td>
                            <a href="editar_producto.php?id=<?= $producto['id_producto'] ?>" class="btn btn-warning btn-sm">Editar</a>
                            <a href="eliminar_producto.php?id=<?= $producto['id_producto'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Seguro que quieres eliminar este producto?');">Eliminar</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <?php include('../partials/footer.php'); ?>
</body>
</html>
