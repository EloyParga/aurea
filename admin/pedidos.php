<?php
session_start();

if (!isset($_SESSION['usuario_id']) || $_SESSION['tipo_usuario'] !== 'admin') {
    header("Location: /aurea/login.php");
    exit;
}

require_once('functions/pedidos.php');
$pedidos = obtenerPedidosConProductos();
$totalPedidos = count($pedidos);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Pedidos</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" />
    <link rel="stylesheet" href="/aurea/assets/css/estilos.css">
    <link rel="stylesheet" href="assets/css/styles.css">
</head>

<body>
<?php include('partials/admin_navbar.php'); ?>

    <h1 class="mb-3">Pedidos realizados</h1>

    <div class="contador-usuarios">
        Total de pedidos registrados: <strong><?= $totalPedidos ?></strong>
    </div>

    <table>
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Cliente</th>
                <th>Fecha</th>
                <th>Total</th>
                <th>Envío Gratis</th>
                <th>Productos</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($pedidos as $pedido): ?>
                <tr>
                    <td><?= $pedido['id_compra'] ?></td>
                    <td><?= htmlspecialchars($pedido['nombre_usuario']) ?></td>
                    <td><?= $pedido['fecha'] ?></td>
                    <td><?= number_format($pedido['total'], 2) ?> €</td>
                    <td><?= $pedido['envio_gratis'] ? 'Sí' : 'No' ?></td>
                    <td>
                        <button class="btn btn-sm btn-outline-primary" type="button" data-bs-toggle="collapse" data-bs-target="#productos-<?= $pedido['id_compra'] ?>">
                            Ver productos
                        </button>
                    </td>
                </tr>
                <tr class="collapse" id="productos-<?= $pedido['id_compra'] ?>">
                    <td colspan="6">
                        <ul class="list-group">
                            <?php foreach ($pedido['productos'] as $prod): ?>
                                <li class="list-group-item d-flex justify-content-between">
                                    <span><?= htmlspecialchars($prod['nombre']) ?> (x<?= $prod['cantidad'] ?>)</span>
                                    <span><?= number_format($prod['precio_unitario'], 2) ?> €</span>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
