<?php
session_start();

if (!isset($_SESSION['usuario_id']) || $_SESSION['tipo_usuario'] !== 'admin') {
    header("Location: /aurea/login.php");
    exit;
}

$usuario = $_SESSION['usuario_nombre'] ?? 'Administrador';

require_once('../functions/db.php');

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <title>Panel de Administración - Aurea</title>
    <link rel="stylesheet" href="/aurea/assets/css/estilos.css" />
    <style>
        body {
            margin: 0;
            font-family: 'Roboto', sans-serif;
            background: #f3f0f9;
            color: #333;
        }

        header {
            background-color: #9D7AC0;
            padding: 20px 40px;
            color: white;
            font-size: 24px;
            font-weight: 600;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
        }

        .logout-btn {
            background-color: white;
            color: #9D7AC0;
            padding: 8px 16px;
            border: none;
            border-radius: 5px;
            font-weight: bold;
            cursor: pointer;
            transition: background 0.3s;
        }

        .logout-btn:hover {
            background-color: #f0e6fb;
        }

        .container {
            padding: 40px;
            height: calc(100vh - 80px);
        }

        .welcome {
            font-size: 28px;
            font-weight: 600;
            margin-bottom: 30px;
        }

        .card-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 30px;
        }

        .card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.07);
            padding: 25px;
            text-align: center;
            transition: transform 0.2s ease;
        }

        .card:hover {
            transform: translateY(-5px);
        }

        .card h3 {
            margin-bottom: 10px;
            color: #805AA7;
        }

        .card p {
            font-size: 14px;
            color: #666;
        }
    </style>
</head>
<body>

<header>
    Panel de Administración - Aurea
    <form method="post" action="/aurea/logout.php">
        <button class="logout-btn">Cerrar sesión</button>
    </form>
</header>

<div class="container">
    <div class="welcome">Hola, <?= htmlspecialchars($usuario) ?> 👋</div>

    <div class="card-grid">
        <div class="card">
            <h3>Usuarios</h3>
            <p>Gestiona cuentas de clientes y administradores.</p>
        </div>
        <div class="card">
            <h3>Pedidos</h3>
            <p>Consulta y gestiona los pedidos realizados.</p>
        </div>
        <div class="card">
            <h3>Productos</h3>
            <p>Agrega, edita o elimina productos del catálogo.</p>
        </div>
        <div class="card">
            <h3>Estadísticas</h3>
            <p>Consulta informes y métricas del sistema.</p>
        </div>
    </div>
</div>

</body>
</html>
