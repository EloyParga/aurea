<?php
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Navbar</title>

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto+Condensed:wght@400;700&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700;800&display=swap" rel="stylesheet" />

    <!-- Estilos generales -->
    <link rel="stylesheet" href="/aurea/assets/css/estilos.css" />

    <style>
        nav.navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px 24px;
            background-color: white;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
            font-family: 'Roboto', sans-serif;
        }

        .menu-center a,
        .menu-right a {
            margin: 0 10px;
            color: #333;
            text-decoration: none;
            font-weight: 500;
        }

        .user-dropdown {
            position: relative;
            display: inline-block;
        }

        .user-dropdown-menu {
            display: none;
            position: absolute;
            right: 0;
            top: 50px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 0 8px rgba(0,0,0,0.15);
            min-width: 160px;
            z-index: 1000;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .user-dropdown-menu.show {
            display: block;
            opacity: 1;
        }

        .user-dropdown-menu a {
            display: block;
            padding: 12px 16px;
            color: #333;
            text-decoration: none;
            font-family: 'Roboto', sans-serif;
            font-weight: 500;
        }

        .user-dropdown-menu a:hover {
            background-color: #eee;
        }
    </style>
</head>
<body>

<nav id="mainNavbar" class="navbar fixed-top navbar-light">
    <div class="logo">
        <a href="../index.php">
            <img src="../assets/media/img/logo.png" alt="Logo" width="200" height="50" />
        </a>
    </div>
    <div class="menu-center">
        <a href="../index.php">Inicio</a>
        <a href="../catalogo.php">Productos</a>
        <a href="../acerca.php">Acerca de</a>
    </div>
    <div class="menu-right">
        <a href="../carrito.php" class="icon">
            <img src="../assets/media/svg/car-icon.svg" alt="Carrito" width="40" height="40" />
        </a>

        <div class="user-dropdown">
            <img src="../assets/media/svg/user-icon.svg" alt="Usuario" width="40" height="40" id="userIcon" style="cursor:pointer;">
            <div class="user-dropdown-menu" id="userDropdownMenu">
                <?php if (isset($_SESSION['usuario_id'])): ?>
                    <a href="../logout.php">Cerrar sesión</a>
                <?php else: ?>
                    <a href="../login.php">Iniciar sesión</a>
                    <a href="../registro.php">Registrarse</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</nav>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const icon = document.getElementById('userIcon');
        const menu = document.getElementById('userDropdownMenu');

        icon.addEventListener('click', function (e) {
            e.stopPropagation();
            menu.classList.toggle('show');
        });

        document.addEventListener('click', function (e) {
            if (!menu.contains(e.target) && e.target.id !== 'userIcon') {
                menu.classList.remove('show');
            }
        });
    });
</script>

</body>
</html>
