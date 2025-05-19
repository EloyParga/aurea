<?php
session_start();
require_once 'functions/db.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = trim($_POST['usuario'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $password2 = $_POST['password2'] ?? '';
    $fecha_nac = $_POST['fecha_nac'] ?? '';

    if ($usuario && $email && $password && $password2 && $fecha_nac) {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error = "El email no es válido.";
        } elseif ($password !== $password2) {
            $error = "Las contraseñas no coinciden.";
        } else {
            $stmt = $conn->prepare("SELECT id_usuario FROM usuarios WHERE nombre_usuario = ? OR email = ?");
            $stmt->bind_param("ss", $usuario, $email);
            $stmt->execute();
            $stmt->store_result();

            if ($stmt->num_rows > 0) {
                $error = "El usuario o email ya están registrados.";
            } else {
                $password_hash = password_hash($password, PASSWORD_DEFAULT);
                $stmt_insert = $conn->prepare("INSERT INTO usuarios (nombre_usuario, email, contraseña, fecha_nac) VALUES (?, ?, ?, ?)");
                $stmt_insert->bind_param("ssss", $usuario, $email, $password_hash, $fecha_nac);

                if ($stmt_insert->execute()) {
                    $success = "Registro exitoso. Puedes <a href='login.php'>iniciar sesión</a> ahora.";
                } else {
                    $error = "Error al registrar. Intenta de nuevo.";
                }
            }
        }
    } else {
        $error = "Por favor, completa todos los campos.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Registro - Aurea</title>
<link rel="stylesheet" href="/aurea/assets/css/estilos.css" />
<style>
    body {
        font-family: 'Roboto', sans-serif;
        background-color: #f7f7f7;
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
    }
    .register-container {
        background: white;
        padding: 30px;
        border-radius: 8px;
        box-shadow: 0 0 15px rgba(0,0,0,0.1);
        width: 360px;
    }
    .register-container h2 {
        margin-bottom: 20px;
        font-weight: 700;
        color: #333;
        text-align: center;
    }
    .register-container label {
        display: block;
        margin-bottom: 8px;
        font-weight: 500;
        color: #555;
    }
    .register-container input[type="text"],
    .register-container input[type="email"],
    .register-container input[type="date"],
    .register-container input[type="password"] {
        width: 100%;
        padding: 10px;
        margin-bottom: 15px;
        border: 1.5px solid #ccc;
        border-radius: 5px;
        font-size: 16px;
        transition: border-color 0.3s;
    }
    .register-container input[type="text"]:focus,
    .register-container input[type="email"]:focus,
    .register-container input[type="date"]:focus,
    .register-container input[type="password"]:focus {
        border-color: #9D7AC0;
        outline: none;
    }
    .register-container button {
        width: 100%;
        padding: 12px;
        background-color: #9D7AC0;
        border: none;
        border-radius: 6px;
        color: white;
        font-weight: 700;
        font-size: 16px;
        cursor: pointer;
        transition: background-color 0.3s;
    }
    .register-container button:hover {
        background-color: #805AA7;
    }
    .error-msg {
        color: #d9534f;
        font-weight: 600;
        margin-bottom: 15px;
        text-align: center;
    }
    .success-msg {
        color: #28a745;
        font-weight: 600;
        margin-bottom: 15px;
        text-align: center;
    }

    .close-btn {
    position: absolute;
    top: 30px;
    right: 30px;
    width: 36px;
    height: 36px;
    background-color: #805AA7;
    color: white;
    font-size: 24px;
    font-weight: bold;
    border-radius: 50%;
    text-align: center;
    line-height: 36px;
    text-decoration: none;
    box-shadow: 0 2px 8px rgba(0,0,0,0.2);
    transition: transform 0.3s ease, background-color 0.3s;
    z-index: 10;
    }
    .close-btn:hover {
        background-color: #9D7AC0;
        transform: scale(1.1);
    }
    .fade-out {
        animation: fadeOutPage 0.5s forwards;
    }
    @keyframes fadeOutPage {
        to {
            opacity: 0;
            transform: translateX(100%);
        }
    }

</style>
</head>
<body>

<a href="/aurea/index.php" class="close-btn" id="closeBtn" title="Cerrar">
    &times;
</a>

<div class="register-container">

    <h2>Registro</h2>
    <?php if ($error): ?>
        <div class="error-msg"><?=htmlspecialchars($error)?></div>
    <?php endif; ?>
    <?php if ($success): ?>
        <div class="success-msg"><?=$success?></div>
    <?php endif; ?>
    <form method="post" action="">
        <label for="usuario">Usuario</label>
        <input type="text" id="usuario" name="usuario" required />

        <label for="email">Email</label>
        <input type="email" id="email" name="email" required />

        <label for="fecha_nac">Fecha de nacimiento</label>
        <input type="date" id="fecha_nac" name="fecha_nac" required />

        <label for="password">Contraseña</label>
        <input type="password" id="password" name="password" required />

        <label for="password2">Confirmar contraseña</label>
        <input type="password" id="password2" name="password2" required />

        <button type="submit">Registrarse</button>
    </form>
</div>

<script>
    document.getElementById('closeBtn').addEventListener('click', function(e) {
        e.preventDefault(); // prevenir navegación inmediata
        document.body.classList.add('fade-out');
        setTimeout(() => {
            window.location.href = this.href;
        }, 180); // esperar a que termine la animación
    });
</script>

</body>
</html>
