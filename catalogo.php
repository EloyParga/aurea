<?php
    require_once('functions/db.php');
    session_start();

    // Obtener categorías 
    $categorias = obtenerCategorias();
    $catFiltro = $_GET['categoria'] ?? null;
    $busqueda = $_GET['q'] ?? null;
    $productos = obtenerProductosCatalogo($catFiltro, $busqueda);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Catalogo Productos</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- CSS -->
    <link rel="stylesheet" href="assets/css/estilos.css">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">

    



    <style>
        .btn-categoria {
            background-color: #9D7AC0;
            color: white;
        }
        .btn-categoria:hover {
            background-color: #8057a3;
            color: white;
        }
        .sin-stock {
            opacity: 0.6;
        }
    </style>

</head>




<body>
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

    <header class="position-relative w-100" style="padding-top: 100px;">
        <img src="assets/media/img/header-catalog.jpg" class="img-fluid w-100" style="height: 400px; width: 100%; object-fit: cover; object-position: center; " alt="imagen mujer rostro" srcset="">
        <div class="position-absolute top-50 start-0 translate-middle-y  w-50" style="margin-left: 2rem !important;">
            <form action="/aurea/functions/buscar.php" method="GET">
                <div class="input-group shadow">
                    <input type="text" name="q" class="form-control" placeholder="Busca tus productos..." />
                    <button class="btn btn-primary" style="background-color: #9D7AC0; color: white;" type="submit">Buscar</button>
                </div>
            </form>
        </div>
    </header>

    <!-- Filtro  por categorías -->
    <div class="container mt-5">
        <div class="d-flex flex-wrap gap-3 justify-content-center">
            <?php foreach ($categorias as $categoria): ?>
                <a href="catalogo.php?categoria=<?= $categoria['id_categoria'] ?>&categoria_nombre=<?= urlencode($categoria['nombre_categoria']) ?>" 
                class="btn btn-categoria d-flex align-items-center gap-2 text-decoration-none" role="button" style="width: 240px;"> 
                    <img src="<?= $categoria['icono_url'] ?>" width="42" height="42" alt="<?= $categoria['nombre_categoria'] ?>" class="rounded-circle">
                    <?= $categoria['nombre_categoria'] ?>
                </a>

            <?php endforeach; ?>
        </div>
    </div>

    <!-- Productos -->
    <?php
        $categoriaSeleccionada = isset($_GET['categoria']) ? intval($_GET['categoria']) : null;
        $productos = obtenerProductosCatalogo($categoriaSeleccionada); // ← se filtra si hay ID
        $mostrarTodos = !$categoriaSeleccionada;
    ?>

    <div class="container">
        <?php if ($mostrarTodos): ?>
            <?php $productosPorCategoria = agruparPorCategoria($productos); ?>
            <?php foreach ($productosPorCategoria as $categoriaNombre => $productosCat): ?>
                <div class="mb-5">
                    <h3 class="text-start categoria-titulo"><?= htmlspecialchars($categoriaNombre) ?></h3>
                    <hr>
                    <div class="row g-4">
                        <?php foreach ($productosCat as $producto): ?>
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

                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <?php if (!empty($productos)): ?>
                <div class="mb-5">
                    <h3 class="text-start categoria-titulo">
                        <?= isset($_GET['categoria_nombre']) 
                        ? htmlspecialchars($_GET['categoria_nombre']) 
                        : 'Categoría' ?>
                    </h3>
                    <hr>
                    <div class="row g-4">
                        <?php foreach ($productos as $producto): ?>
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

                        <?php endforeach; ?>
                    </div>
                </div>
            <?php else: ?>
                <p>No hay productos en esta categoría.</p>
            <?php endif; ?>
        <?php endif; ?>
    </div>


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


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/scripts.js"></script>

</body>
</html>


