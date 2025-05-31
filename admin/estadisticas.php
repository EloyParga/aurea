<?php
session_start();

if (!isset($_SESSION['usuario_id']) || $_SESSION['tipo_usuario'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

require_once('functions/estadisticas.php');

$totales = obtenerTotalesSistema();
$ventasMensuales = obtenerVentasMensuales();
$usuariosMensuales = obtenerUsuariosPorMes();
$topProductos = obtenerTopProductos();
$promediosPuntuacion = obtenerPromedioPuntuacion();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Estadísticas - Aurea</title>
    <link rel="stylesheet" href="../assets/css/estilos.css">
    <link rel="stylesheet" href="assets/css/styles.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>

<?php include('partials/admin_navbar.php'); ?>

<h1>Panel de Estadísticas</h1>

<div class="stats-grid">
    <div class="card">Usuarios: <strong><?= $totales['usuarios'] ?></strong></div>
    <div class="card">Productos: <strong><?= $totales['productos'] ?></strong></div>
    <div class="card">Pedidos: <strong><?= $totales['pedidos'] ?></strong></div>
    <div class="card">Ingresos: <strong><?= number_format($totales['ingresos'], 2) ?> €</strong></div>
</div>

<div class="chart-container">
    <canvas id="ventasChart"></canvas>
</div>

<div class="chart-container">
    <canvas id="usuariosChart"></canvas>
</div>

<div class="chart-container">
    <canvas id="topProductosChart"></canvas>
</div>

<div class="chart-container">
    <canvas id="puntuacionesChart"></canvas>
</div>

<script>
    const ventasData = <?= json_encode($ventasMensuales) ?>;
    const usuariosData = <?= json_encode($usuariosMensuales) ?>;
    const topProductos = <?= json_encode($topProductos) ?>;
    const promedios = <?= json_encode($promediosPuntuacion) ?>;

    new Chart(document.getElementById('ventasChart'), {
        type: 'bar',
        data: {
            labels: ventasData.meses,
            datasets: [{
                label: 'Ventas (€)',
                data: ventasData.valores,
                backgroundColor: '#805AA7'
            }]
        }
    });

    new Chart(document.getElementById('usuariosChart'), {
        type: 'line',
        data: {
            labels: usuariosData.meses,
            datasets: [{
                label: 'Nuevos Usuarios',
                data: usuariosData.valores,
                borderColor: '#6C3483',
                fill: false
            }]
        }
    });

    new Chart(document.getElementById('topProductosChart'), {
        type: 'bar',
        data: {
            labels: topProductos.nombres,
            datasets: [{
                label: 'Cantidad Vendida',
                data: topProductos.cantidades,
                backgroundColor: '#AF7AC5'
            }]
        }
    });

    new Chart(document.getElementById('puntuacionesChart'), {
        type: 'bar',
        data: {
            labels: promedios.nombres,
            datasets: [{
                label: 'Puntuación Promedio',
                data: promedios.valores,
                backgroundColor: '#BB8FCE'
            }]
        }
    });
</script>

</body>
</html>
