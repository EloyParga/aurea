<?php
require_once 'db.php';
session_start();

$resultados = [];
$query = '';
if (isset($_GET['q'])) {
    $query = trim($_GET['q']);
    if (!empty($query)) {
        $resultados = buscarProductos($query);
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Buscar - Aurea</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/css/estilos.css">
    <style>
        body {
            background-color: #f7f3fc;
            font-family: 'Roboto', sans-serif;
        }

        .card:hover {
            transform: translateY(-4px);
            transition: transform 0.2s ease;
        }

        .btn-agregar {
            background-color: #9D7AC0;
            color: white;
        }

        .btn-agregar:hover {
            background-color: #8057a3;
        }

        .resultados-container {
            margin-top: 50px;
            margin-bottom: 50px;
            padding-top: 100px;
            padding-bottom: 80px;
        }

        .no-results {
            padding: 60px 0;
            font-size: 18px;
            color: #777;
            text-align: center;
        }
    </style>
</head>
<body>

<?php include('../partials/navbar.php'); ?>

<div class="container resultados-container">
    <div class="text-center mb-5">
        <h2 class="fw-semibold">Resultados para "<span style="color: #8057a3;"><?= htmlspecialchars($query) ?></span>"</h2>
    </div>

    <?php if ($resultados && $resultados->num_rows > 0): ?>
        <div class="row g-4">
            <?php while ($producto = $resultados->fetch_assoc()): ?>
            <div class="col-12 col-md-6 col-lg-4">
                <div class="card h-100 text-center">
                    <img src="<?= $producto['imagen_url'] ?>" class="card-img-top img-fluid" alt="<?= $producto['nombre'] ?>" style="height: 200px; object-fit: cover;">
                    <div class="card-body">
                        <h5 class="card-title"><?= htmlspecialchars($producto['nombre']) ?></h5>
                        <p class="card-text"><?= substr($producto['descripcion'], 0, 100) ?>...</p>
                        <p class="fw-bold"><?= number_format($producto['precio'], 2) ?> €</p>
                        <form class="form-agregar-carrito">
                            <input type="hidden" name="producto_id" value="<?= $producto['id_producto'] ?>">
                            <input type="number" name="cantidad" value="1" min="1" style="width: 50px;">
                            <button type="button" class="btn btn-agregar mt-2">Agregar al carrito</button>
                        </form>
                    </div>
                </div>
            </div>
            <?php endwhile; ?>
        </div>
    <?php else: ?>
        <div class="no-results">
            No se encontraron productos relacionados con <strong>"<?= htmlspecialchars($query) ?>"</strong>.
        </div>
    <?php endif; ?>
</div>

<?php include('../partials/footer.php'); ?>

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
</body>
</html>
