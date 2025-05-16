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
    <link rel="stylesheet" href="assets/css/estilos.css" />
</head>
<body>
    <?php include('partials/navbar.php'); ?>

    <!-- HEADER con buscador -->
    <header class="position-relative snap-section">
        <img src="assets/media/img/header-index.png" alt="Header" class="img-fluid w-100" style="height: 100dvh; object-fit: cover;" />
        <div class="position-absolute top-50 start-50 translate-middle w-50 w-md-50 px-3">
            <form action="/aurea/functions/buscar.php" method="GET">
                <div class="input-group shadow">
                    <input type="text" name="q" class="form-control" placeholder="Busca tus productos..." />
                    <button class="btn btn-primary" type="submit">Buscar</button>
                </div>
            </form>
        </div>
    </header>

    <!-- CARRUSEL de novedades -->
    <div id="carouselNoticias" class="carousel slide snap-section" data-bs-ride="carousel">
        <div class="carousel-inner" style="height: 100dvh;">
            <div class="carousel-item active">
                <img src="assets/media/img/slide1.jpg" class="d-block w-100 h-100" alt="Slide 1" style="object-fit: cover;" />
            </div>
            <div class="carousel-item">
                <img src="assets/media/img/slide2.jpg" class="d-block w-100 h-100" alt="Slide 2" style="object-fit: cover;" />
            </div>
            <div class="carousel-item">
                <img src="assets/media/img/slide3.jpg" class="d-block w-100 h-100" alt="Slide 3" style="object-fit: cover;" />
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
            <?php while ($producto = $productos->fetch_assoc()): ?>
            <div class="col-12 col-md-6 col-lg-4">
                <div class="card h-100 text-center">
                    <img src="<?= htmlspecialchars($producto['imagen_url']) ?>" class="card-img-top img-fluid" alt="<?= htmlspecialchars($producto['nombre']) ?>" style="height: 200px; object-fit: cover;" />
                    <div class="card-body">
                        <h5 class="card-title"><?= htmlspecialchars($producto['nombre']) ?></h5>
                        <p class="card-text"><?= htmlspecialchars(substr($producto['descripcion'], 0, 100)) ?>...</p>
                        <p class="fw-bold"><?= number_format($producto['precio'], 2) ?> €</p>
                        <form action="agregar_carrito.php" method="POST">
                            <input type="hidden" name="id_producto" value="<?= $producto['id_producto'] ?>" />
                            <button type="submit" class="btn btn-outline-primary">Añadir al carrito</button>
                        </form>
                    </div>
                </div>
            </div>
            <?php endwhile; ?>
        </div>
    </section>

    <!-- SECCIÓN: Imagen + Valores -->
    <section class="position-relative text-center snap-section" style="height: 100dvh;">
        <img src="assets/media/img/valores-bg.jpg" class="img-fluid w-100" style="height: 100dvh; object-fit: cover;" alt="Valores" />
        <div class="position-absolute top-50 start-50 translate-middle bg-white bg-opacity-75 p-4 rounded-3" style="width: 90%; max-width: 860px;">
            <div class="row gy-3">
                <div class="col-12 col-md-4">
                    <img src="assets/media/svg/hoja-icon.svg" width="60" alt="Natural" />
                    <p class="mt-2">Usamos solo ingredientes botánicos certificados que nutren tu piel y respetan el planeta.</p>
                </div>
                <div class="col-12 col-md-4">
                    <img src="assets/media/svg/animal-icon.svg" width="60" alt="Cruelty Free" />
                    <p class="mt-2">Cada producto es desarrollado sin pruebas en animales y con prácticas éticas en toda la cadena.</p>
                </div>
                <div class="col-12 col-md-4">
                    <img src="assets/media/svg/reciclado-icon.svg" width="60" alt="Reciclado" />
                    <p class="mt-2">Cada envase está hecho con materiales reciclables, cuidando el planeta desde el diseño.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- SECCIÓN: Reseñas -->
    <section class="container my-5 snap-section">
        <h2 class="text-center mb-4">Opiniones de nuestros clientes</h2>
        <div class="row g-4">
            <?php while ($resena = $resenas_result->fetch_assoc()): ?>
            <div class="col-12 col-md-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title"><?= htmlspecialchars($resena['nombre_usuario']) ?></h5>
                        <p class="card-text"><?= htmlspecialchars($resena['opinion']) ?></p>
                        <p class="text-warning">
                            <?= str_repeat('★', (int)$resena['puntuacion']) ?>
                            <?= str_repeat('☆', 5 - (int)$resena['puntuacion']) ?>
                        </p>
                    </div>
                </div>
            </div>
            <?php endwhile; ?>
        </div>
    </section>

    <!-- SECCIÓN: Footer -->
    <?php include('partials/footer.php'); ?>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        const sections = document.querySelectorAll('.snap-section');
        let isScrolling = false;

        window.addEventListener('wheel', (e) => {
            if (isScrolling) return;

            const direction = e.deltaY > 0 ? 1 : -1;
            const current = Math.round(window.scrollY / window.innerHeight);
            const target = Math.min(Math.max(current + direction, 0), sections.length - 1);

            isScrolling = true;
            window.scrollTo({
                top: sections[target].offsetTop,
                behavior: 'smooth'
            });

            setTimeout(() => { isScrolling = false; }, 1000);
        }, { passive: true });
    </script>
</body>
</html>
