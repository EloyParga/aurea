<?php
session_start();
require_once 'functions/db.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = trim($_POST['usuario'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($usuario && $password) {
        // Buscar usuario en DB
        $stmt = $conn->prepare("SELECT id_usuario, nombre_usuario, contraseña, tipo_usuario FROM usuarios WHERE nombre_usuario = ?");
        $stmt->bind_param("s", $usuario);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result && $result->num_rows === 1) {
            $row = $result->fetch_assoc();
            if (password_verify($password, $row['contraseña'])) {
                // Login correcto
                $_SESSION['usuario_id'] = $row['id_usuario'];
                $_SESSION['usuario_nombre'] = $row['nombre_usuario'];
                $_SESSION['tipo_usuario'] = $row['tipo_usuario'];

                if ($_SESSION['tipo_usuario'] === 'admin') {
                    // echo "<pre>";
                    // print_r($_SESSION);
                    // echo "</pre>";
                    // exit;
                header("Location: /aurea/admin/index.php");
                exit;
            } else {
                header("Location: /aurea/index.php");
                exit;
            }
                exit;
            } else {
                $error = "Contraseña incorrecta.";
            }
        } else {
            $error = "Usuario no encontrado.";
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
    <title>Iniciar sesión - Aurea</title>
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
        .login-container {
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
            width: 360px;
        }
        .login-container h2 {
            margin-bottom: 20px;
            font-weight: 700;
            color: #333;
            text-align: center;
        }
        .login-container label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: #555;
        }
        .login-container input[type="text"],
        .login-container input[type="password"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1.5px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
            transition: border-color 0.3s;
        }
        .login-container input[type="text"]:focus,
        .login-container input[type="password"]:focus {
            border-color: #007bff;
            outline: none;
        }
        .login-container button {
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
        .login-container button:hover {
            background-color: #805AA7;
        }
        .error-msg {
            color: #d9534f;
            font-weight: 600;
            margin-bottom: 15px;
            text-align: center;
        }
        .register-link {
            text-align: center;
            margin-top: 15px;
        }
        .register-link a {
            color: #007bff;
            text-decoration: none;
        }
        .register-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

    <div class="login-container">
        <h2>Iniciar sesión</h2>
        <?php if ($error): ?>
            <div class="error-msg"><?=htmlspecialchars($error)?></div>
        <?php endif; ?>
        <form method="post" action="">
            <label for="usuario">Usuario</label>
            <input type="text" id="usuario" name="usuario" required autocomplete="username" />
            
            <label for="password">Contraseña</label>
            <input type="password" id="password" name="password" required autocomplete="current-password" />
            
            <button type="submit">Entrar</button>
        </form>
        <div class="register-link">
            ¿No tienes cuenta? <a href="registro.php">Regístrate aquí</a>
        </div>
    </div>

</body>
</html>
