<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio - Aurea</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- CSS -->
    <link rel="stylesheet" href="/aurea/assets/css/estilos.css">
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

    <!-- CARRUSEL de novedades -->
    <div id="carouselNoticias" class="carousel slide snap-section" data-bs-ride="carousel">
        <div class="carousel-inner" style="height: 100dvh;">
            <div class="carousel-item active">
                <img src="/aurea-ecomerce/assets/media/img/slide1.jpg" class="d-block w-100 h-100" alt="Slide 1" style="object-fit: cover;">
            </div>
            <div class="carousel-item">
                <img src="/aurea-ecomerce/assets/media/img/slide2.jpg" class="d-block w-100 h-100" alt="Slide 2" style="object-fit: cover;">
            </div>
            <div class="carousel-item">
                <img src="/aurea-ecomerce/assets/media/img/slide3.jpg" class="d-block w-100 h-100" alt="Slide 3" style="object-fit: cover;">
            </div>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#carouselNoticias" data-bs-slide="prev">
            <span class="carousel-control-prev-icon"></span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#carouselNoticias" data-bs-slide="next">
            <span class="carousel-control-next-icon"></span>
        </button>
    </div>

    <!-- SECCIÓN: Productos destacados -->
    <section class="container my-5 snap-section" style="height: 100dvh;">
        <h2 class="text-center mb-4">Best Sellers</h2>
        <div class="row g-4">

            <?php
            $query = "SELECT * FROM productos WHERE destacado = 1 LIMIT 6";
            $result = mysqli_query($conn, $query);
            while ($producto = mysqli_fetch_assoc($result)) {
            ?>
            <div class="col-12 col-md-6 col-lg-4">
                <div class="card h-100 text-center">
                <img src="<?= $producto['imagen_url'] ?>" class="card-img-top img-fluid" alt="<?= $producto['nombre'] ?>" style="height: 200px; object-fit: cover;">
                <div class="card-body">
                    <h5 class="card-title"><?= $producto['nombre'] ?></h5>
                    <p class="card-text"><?= substr($producto['descripcion'], 0, 100) ?>...</p>
                    <p class="fw-bold"><?= number_format($producto['precio'], 2) ?> €</p>
                    <form action="agregar_carrito.php" method="POST">
                    <input type="hidden" name="id_producto" value="<?= $producto['id_producto'] ?>">
                    <button type="submit" class="btn btn-outline-primary">Añadir al carrito</button>
                    </form>
                </div>
                </div>
            </div>
            <?php } ?>

        </div>
    </section>

    <!-- SECCIÓN: Imagen + Valores -->
    <section class="position-relative text-center snap-section" style="height: 100dvh;">
        <img src="/aurea-ecomerce/assets/valores-bg.jpg" class="img-fluid w-100" style="height: 800px; object-fit: cover;" alt="Valores">
        <div class="position-absolute top-50 start-50 translate-middle bg-white bg-opacity-75 p-4 rounded-3" style="width: 90%; max-width: 860px;">
            <div class="row gy-3">
                <div class="col-12 col-md-4">
                    <img src="assets/hoja-icon.svg" width="60" alt="Natural">
                    <p class="mt-2">Ingredientes naturales</p>
                </div>
                <div class="col-12 col-md-4">
                    <img src="assets/animal-icon.svg" width="60" alt="Cruelty Free">
                    <p class="mt-2">No testamos en animales</p>
                </div>
                <div class="col-12 col-md-4">
                    <img src="assets/reciclado-icon.svg" width="60" alt="Reciclado">
                    <p class="mt-2">Envases reciclados</p>
                </div>
            </div>
        </div>
    </section>

    <!-- SECCIÓN: Reseñas -->
    <section class="container my-5 snap-section">
        <h2 class="text-center mb-4">Opiniones de nuestros clientes</h2>
        <div class="row g-4">

            <?php
            $resenas_query = "
            SELECT r.opinion, r.puntuacion, u.nombre_usuario
            FROM resenas r
            JOIN usuarios u ON r.id_usuario = u.id_usuario
            ORDER BY r.puntuacion DESC
            LIMIT 3
            ";
            $resenas_result = mysqli_query($conn, $resenas_query);
            while ($resena = mysqli_fetch_assoc($resenas_result)) {
            ?>
            <div class="col-12 col-md-4">
                <div class="card h-100 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title"><?= $resena['nombre_usuario'] ?></h5>
                    <p class="card-text"><?= $resena['opinion'] ?></p>
                    <p class="text-warning">
                    <?= str_repeat('★', $resena['puntuacion']) ?>
                    <?= str_repeat('☆', 5 - $resena['puntuacion']) ?>
                    </p>
                </div>
                </div>
            </div>
            <?php } ?>

        </div>
    </section>

    
    



    
    <?php include('partials/footer.php'); ?>  
</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js" integrity="sha384-j1CDi7MgGQ12Z7Qab0qlWQ/Qqz24Gc6BM0thvEMVjHnfYGF0rmFCozFSxQBxwHKO" crossorigin="anonymous"></script>
</html>




