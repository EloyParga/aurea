<?php
session_start();
if (!isset($_SESSION['usuario_id']) || $_SESSION['tipo_usuario'] !== 'admin') {
    header("Location: /aurea/login.php");
    exit;
}

require_once(__DIR__ . '/../../functions/db.php'); 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $accion = $_POST['accion'] ?? '';
    $idUsuario = intval($_POST['id_usuario'] ?? 0);

    if ($idUsuario <= 0) {
        die('ID de usuario inválido');
    }

    if ($accion === 'cambiar_rol') {
        $stmt = $conn->prepare("SELECT tipo_usuario FROM usuarios WHERE id_usuario = ?");
        $stmt->bind_param("i", $idUsuario);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        if ($user) {
            $nuevoRol = ($user['tipo_usuario'] === 'admin') ? 'cliente' : 'admin';
            $update = $conn->prepare("UPDATE usuarios SET tipo_usuario = ? WHERE id_usuario = ?");
            $update->bind_param("si", $nuevoRol, $idUsuario);
            $update->execute();
        }

        header("Location: ../usuarios.php");
        exit;

    } elseif ($accion === 'cambiar_contrasena') {
        // Redirige a un formulario para establecer una nueva contraseña
        header("Location: ../formulario_contrasena.php?id_usuario=" . $idUsuario);
        exit;

    } elseif ($accion === 'eliminar') {
        $delete = $conn->prepare("DELETE FROM usuarios WHERE id_usuario = ?");
        $delete->bind_param("i", $idUsuario);
        $delete->execute();

        header("Location: ../usuarios.php");
        exit;
    }
}

// Si no se realizó ninguna acción válida
header("Location: ../usuarios.php");
exit;
