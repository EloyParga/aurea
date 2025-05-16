<?php
require_once('../aurea/functions/db.php');

// Obtener productos destacados (máx 6)
$productos = obtenerProductosDestacados(6);

// Obtener 3 reseñas destacadas
$resenas_result = obtenerResenasDestacadas(2);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Inicio - Aurea</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
    <!-- CSS -->
    <link rel="stylesheet" href="/aurea/assets/css/estilos.css">
</head>
<body class="snap-container">
    <?php include('partials/navbar.php'); ?>
    
    <!-- HEADER -->
    <header>
        <img src="/aurea/assets/media/img/heade" alt="" srcset="">
        <div>
            <form action="buscar.php" method="GET">
                <div>
                    <input type="text" name="" id="">
                    <button></button>
                </div>
            </form>
        </div>
    </header>



    
    <?php include('partials/footer.php'); ?>  
</body>
</html>
