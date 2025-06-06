<?php
require_once('functions/db.php');

// Obtener productos destacados (máx 6)
$productos = obtenerProductosDestacados(6);

// Obtener 3 reseñas destacadas
$resenas_result = obtenerResenasDestacadas(2);

$noticias_json = file_get_contents('assets/noticias.json');
$noticias = json_decode($noticias_json, true);



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
<body style="padding-top: 100px;">
    <?php include('partials/navbar.php'); ?>

    <?php include('partials/modal-img.php'); ?>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const modalImagen = document.getElementById('modalImagen');
            const imagenes = document.querySelectorAll('.card-img-top');

            imagenes.forEach(img => {
                img.style.cursor = 'pointer';
                img.addEventListener('click', () => {
                    modalImagen.src = img.src;
                    const modal = new bootstrap.Modal(document.getElementById('imagenModal'));
                    modal.show();
                });
            });
        });
    </script>

    <!-- HEADER con buscador -->
    <header class="position-relative snap-section">
        <img src="assets/media/img/header-index.png" alt="Header" class="img-fluid w-100" style="height: 100dvh; object-fit: cover;" />
        <div class="position-absolute top-50 start-50 translate-middle w-50 w-md-50 px-3">
            <form action="functions/buscar.php" method="GET">
                <div class="input-group shadow">
                    <input type="text" name="q" class="form-control" placeholder="Busca tus productos..." />
                    <button class="btn btn-primary" style="background-color: #9D7AC0; color: white;" type="submit">Buscar</button>
                </div>
            </form>
        </div>
    </header>

    <!-- CARRUSEL de novedades -->
    <div id="carouselNoticias" class="carousel slide snap-section" data-bs-ride="carousel">
        <div class="carousel-inner" style="height: 100dvh;">
            <?php foreach ($noticias as $index => $noticia): ?>
                <div class="carousel-item <?= $index === 0 ? 'active' : '' ?>">
                    <div class="position-relative h-100 w-100">
                        <img src="<?= htmlspecialchars($noticia['imagen_url']) ?>" class="d-block w-100 h-100" style="object-fit: cover;" alt="Noticia <?= $index + 1 ?>" />
                        <div class="position-absolute top-50 start-50 translate-middle text-white text-center px-4 py-3 bg-dark bg-opacity-50 rounded shadow" style="max-width: 700px;">
                            <h2 class="mb-3"><?= htmlspecialchars($noticia['titulo']) ?></h2>
                            <p class="lead"><?= htmlspecialchars($noticia['descripcion']) ?></p>
                            <?php if (!empty($noticia['enlace'])): ?>
                                <a href="<?= htmlspecialchars($noticia['enlace']) ?>" class="btn mt-3" style="background-color: #9D7AC0; color: white;">Ver más</a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <button class="carousel-control-prev" type="button" data-bs-target="#carouselNoticias" data-bs-slide="prev">
            <span class="carousel-control-prev-icon"></span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#carouselNoticias" data-bs-slide="next">
            <span class="carousel-control-next-icon"></span>
        </button>
    </div>


    <!-- SECCIÓN: Productos destacados -->
    <section class="container my-5 snap-section" style="height: 100dvh; margin-top: 80px;">
        <h2 class="text-center mb-4">Best Sellers</h2>
        <div class="row g-4">
            <?php while ($producto = $productos->fetch_assoc()): ?>
            <div class="col-12 col-md-6 col-lg-4">
                <div class="card h-100 text-center <?= $producto['stock'] <= 0 ? 'sin-stock' : '' ?>">
                    <img src="<?= $producto['imagen_url'] ?>" class="card-img-top img-fluid" alt="<?= $producto['nombre'] ?>" style="height: 200px; object-fit: cover;">
                    <div class="card-body">
                        <h5 class="card-title"><?= $producto['nombre'] ?></h5>
                        <p class="card-text"><?= substr($producto['descripcion'], 0, 100) ?>...</p>
                        <p class="fw-bold"><?= number_format($producto['precio'], 2) ?> €</p>

                        <?php if ($producto['stock'] <= 0): ?>
                            <p class="text-danger fw-bold">Sin stock</p>
                            <button class="btn btn-secondary" disabled>Agregar al carrito</button>
                        <?php else: ?>
                            <form class="form-agregar-carrito">
                                <input type="hidden" name="producto_id" value="<?= $producto['id_producto'] ?>">
                                <input type="number" name="cantidad" value="1" min="1" max="<?= $producto['stock'] ?>" style="width: 50px;">
                                <button type="button" class="btn btn-agregar" style="background-color: #9D7AC0; color: white;">Agregar al carrito</button>
                            </form>
                        <?php endif; ?>
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
    <section class="container my-5 snap-section" >
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


        <script>
        document.addEventListener('DOMContentLoaded', () => {
            document.querySelectorAll('.form-agregar-carrito').forEach(form => {
                form.querySelector('button').addEventListener('click', () => {
                    const producto_id = form.querySelector('[name="producto_id"]').value;
                    const cantidad = form.querySelector('[name="cantidad"]').value;

                    fetch('functions/agregar_carrito.php', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                        body: new URLSearchParams({
                            producto_id,
                            cantidad
                        })
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            alert('✅ Producto agregado al carrito');
                        } else {
                            alert('⚠️ ' + (data.error || 'Error al agregar al carrito'));
                        }
                    })
                    .catch(err => {
                        console.error(err);
                        alert('❌ Error de red');
                    });
                });
            });
        });
    </script>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
   
    
</body>
</html>
