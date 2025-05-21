<?php
session_start();
if (!isset($_SESSION['usuario_id']) || $_SESSION['tipo_usuario'] !== 'admin') {
    header("Location: /aurea/login.php");
    exit;
}

require_once(__DIR__ . '/../../functions/db.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $idUsuario = intval($_POST['id_usuario'] ?? 0);
    $nueva = $_POST['nueva_contrasena'] ?? '';
    $confirmar = $_POST['confirmar_contrasena'] ?? '';

    if ($idUsuario <= 0 || empty($nueva) || empty($confirmar)) {
        die('Datos inválidos o incompletos');
    }

    if ($nueva !== $confirmar) {
        die('Las contraseñas no coinciden');
    }

    if (strlen($nueva) < 6) {
        die('La contraseña debe tener al menos 6 caracteres');
    }

    // Hashear y guardar nueva contraseña
    $hash = password_hash($nueva, PASSWORD_DEFAULT);

    $stmt = $conn->prepare("UPDATE usuarios SET contraseña = ? WHERE id_usuario = ?");
    $stmt->bind_param("si", $hash, $idUsuario);
    if ($stmt->execute()) {
        header("Location: ../usuarios.php?mensaje=contrasena_actualizada");
        exit;
    } else {
        die("Error al actualizar la contraseña.");
    }
} else {
    header("Location: ../usuarios.php");
    exit;
}
