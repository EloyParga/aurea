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
            <form action="/aurea/functions/buscar.php" method="GET">
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
    <section class="container my-5 snap-section" style="height: 100dvh; margin-top: 80px;">
        <h2 class="text-center mb-4">Best Sellers</h2>
        <div class="row g-4">

            <?php
            $query = "SELECT * FROM productos WHERE destacado = 1 LIMIT 6";
            $result = mysqli_query($conn, $query);
            while ($producto = mysqli_fetch_assoc($result)) {
            ?>
            <div class="col-12 col-md-6 col-lg-4">
                <div class="card h-100 text-center">
                    <img src="<?= htmlspecialchars($producto['imagen_url']) ?>" class="card-img-top img-fluid" alt="<?= htmlspecialchars($producto['nombre']) ?>" style="height: 200px; object-fit: cover;" />
                    <div class="card-body">
                        <h5 class="card-title"><?= htmlspecialchars($producto['nombre']) ?></h5>
                        <p class="card-text"><?= htmlspecialchars(substr($producto['descripcion'], 0, 100)) ?>...</p>
                        <p class="fw-bold"><?= number_format($producto['precio'], 2) ?> €</p>
                        <form class="form-agregar-carrito">
                            <input type="hidden" name="producto_id" value="<?= $producto['id_producto'] ?>">
                            <input type="number" name="cantidad" value="1" min="1" style="width: 50px;">
                            <button type="button" class="btn btn-agregar" style="background-color: #9D7AC0; color: white;">Agregar al carrito</button>
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
    <section class="container my-5 snap-section" >
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
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js" integrity="sha384-j1CDi7MgGQ12Z7Qab0qlWQ/Qqz24Gc6BM0thvEMVjHnfYGF0rmFCozFSxQBxwHKO" crossorigin="anonymous"></script>
</html>




