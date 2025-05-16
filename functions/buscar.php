<?php
require_once 'db.php';

$resultados = [];
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
</head>
<body>
<?php include('../partials/navbar.php'); ?>

<div class="container mt-5">
    <h2>Resultados de búsqueda</h2>
    <?php if ($resultados && $resultados->num_rows > 0): ?>
        <div class="row g-4 mt-4">
            <?php while ($producto = $resultados->fetch_assoc()): ?>
            <div class="col-md-4">
                <div class="card h-100">
                    <img src="<?= $producto['imagen_url'] ?>" class="card-img-top" alt="<?= $producto['nombre'] ?>" style="height:200px;object-fit:cover;">
                    <div class="card-body">
                        <h5 class="card-title"><?= $producto['nombre'] ?></h5>
                        <p class="card-text"><?= substr($producto['descripcion'], 0, 80) ?>...</p>
                        <p class="fw-bold"><?= number_format($producto['precio'], 2) ?> €</p>
                        <form action="agregar_carrito.php" method="POST">
                            <input type="hidden" name="id_producto" value="<?= $producto['id_producto'] ?>">
                            <button type="submit" class="btn btn-outline-primary">Añadir al carrito</button>
                        </form>
                    </div>
                </div>
            </div>
            <?php endwhile; ?>
        </div>
    <?php else: ?>
        <p class="text-muted mt-3">No se encontraron productos relacionados con “<strong><?= htmlspecialchars($query) ?></strong>”.</p>
    <?php endif; ?>
</div>

<?php include('../partials/footer.php'); ?>
</body>
</html>
