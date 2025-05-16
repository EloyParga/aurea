<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Navbar</title>

    <!-- Enlazar la fuente desde Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto+Condensed:wght@400;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700;800&display=swap" rel="stylesheet">


    <!-- Enlazamos el archivo CSS desde la carpeta assets/css -->
    <link rel="stylesheet" href="/aurea-ecomerce/assets/css/estilos.css">
</head>
<body>
    <!-- Barra de navegación -->
    <nav class="navbar">
        <div class="logo">
            <!-- Asumiendo que el logo está en /assets/media/img/logo.png -->
             <a href="/aurea-ecomerce/index.php">
                <img src="/aurea-ecomerce/assets/media/img/logo.png" alt="Logo" width="200" height="50">
             </a>
        </div>
        <div class="menu-center">
            <a href="/aurea-ecomerce/index.php">Inicio</a>
            <a href="/aurea-ecomerce/catalogo.php">Productos</a>
            <a href="/aurea-ecomerce/acerca.php">Acerca de</a>
        </div>
        <div class="menu-right">
            <!-- Icono carrito (SVG) -->
            <a href="/carrito.php" class="icon">
                <img src="/aurea-ecomerce/assets/media/svg/car-icon.svg" alt="Carrito" width="40" height="40">
            </a>
            <!-- Icono usuario (SVG) -->
            <a href="/login.php" class="icon">
                <img src="/aurea-ecomerce/assets/media/svg/user-icon.svg" alt="Usuario" width="40" height="40">
            </a>
        </div>
    </nav>
</body>
</html>
