<?php
session_start();
if (!isset($_SESSION['usuario_id']) || $_SESSION['tipo_usuario'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

require_once(__DIR__ . '/../functions/db.php');

$idUsuario = intval($_GET['id_usuario'] ?? 0);
if ($idUsuario <= 0) {
    die('ID de usuario inválido');
}

// Obtener nombre de usuario
$stmt = $conn->prepare("SELECT nombre_usuario FROM usuarios WHERE id_usuario = ?");
$stmt->bind_param("i", $idUsuario);
$stmt->execute();
$result = $stmt->get_result();
$usuario = $result->fetch_assoc();
if (!$usuario) {
    die("Usuario no encontrado");
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Cambiar contraseña - Admin</title>
    <link rel="stylesheet" href="../assets/css/estilos.css">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f4f0fa;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 500px;
            margin: 60px auto;
            padding: 30px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 16px rgba(0,0,0,0.1);
        }

        h2 {
            text-align: center;
            color: #6e4b9e;
        }

        label {
            display: block;
            margin-top: 20px;
            font-weight: bold;
        }

        input[type="password"] {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border-radius: 6px;
            border: 1px solid #ccc;
        }

        .btn {
            margin-top: 25px;
            width: 100%;
            padding: 12px;
            background-color: #9D7AC0;
            color: white;
            border: none;
            border-radius: 6px;
            font-weight: bold;
            cursor: pointer;
        }

        .btn:hover {
            background-color: #7e5da5;
        }

        .back-link {
            text-align: center;
            margin-top: 15px;
        }

        .back-link a {
            color: #6e4b9e;
            text-decoration: none;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Cambiar contraseña de <br><strong><?= htmlspecialchars($usuario['nombre_usuario']) ?></strong></h2>

    <form method="post" action="functions/guardar_contrasena.php">
        <input type="hidden" name="id_usuario" value="<?= $idUsuario ?>">

        <label for="nueva_contrasena">Nueva contraseña</label>
        <input type="password" id="nueva_contrasena" name="nueva_contrasena" required>

        <label for="confirmar_contrasena">Confirmar contraseña</label>
        <input type="password" id="confirmar_contrasena" name="confirmar_contrasena" required>

        <button type="submit" class="btn">Guardar</button>
    </form>

    <div class="back-link">
        <a href="usuarios.php">← Volver a gestión de usuarios</a>
    </div>
</div>

</body>
</html>
