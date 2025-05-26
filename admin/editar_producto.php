<?php
session_start();
require_once('../functions/db.php');
require_once('functions/productos.php');

// Validar sesión
if (!isset($_SESSION['usuario_id']) || $_SESSION['tipo_usuario'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

// Obtener el id del producto
$id_producto = $_GET['id'] ?? null;
if (!$id_producto) {
    header("Location: productos.php");
    exit;
}

// Obtener los datos del producto
$producto = obtenerProductoPorId($id_producto);
if (!$producto) {
    // Producto no encontrado
    header("Location: productos.php?error=producto_no_encontrado");
    exit;
}

// Obtener todas las categorías
$categorias = obtenerCategorias();

// Obtener categorías asignadas al producto
$categoriasProducto = obtenerCategoriasPorProducto($id_producto);

// Si el formulario se envió, procesa la actualización
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];
    $precio = floatval($_POST['precio']);
    $stock = intval($_POST['stock']);
    $destacado = isset($_POST['destacado']) ? 1 : 0;
    $categoriasSeleccionadas = $_POST['categorias'] ?? [];

    // Manejar imagen si se carga
    $imagen_url = $producto['imagen_url'];
    if (!empty($_FILES['imagen']['name'])) {
        $nombreImagen = basename($_FILES['imagen']['name']);
        $rutaDestino = '../assets/media/productos/' . $nombreImagen;
        move_uploaded_file($_FILES['imagen']['tmp_name'], $rutaDestino);
        $imagen_url = 'assets/media/productos/' . $nombreImagen;
    }

    // Actualizar producto
    actualizarProducto($id_producto, $nombre, $descripcion, $precio, $stock, $imagen_url, $destacado, $categoriasSeleccionadas);

    header("Location: productos.php?editado=1");
    exit;
}
?>


<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8" />
<title>Editar Producto</title>
<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet" />
<style>
  body {
    font-family: 'Roboto', sans-serif;
    background: #f9f9fb;
    margin: 0;
    padding: 30px 40px;
    color: #333;
  }
  .container {
    max-width: 600px;
    background: #fff;
    padding: 30px 35px;
    margin: 0 auto;
    border-radius: 12px;
    box-shadow: 0 6px 20px rgba(109, 83, 158, 0.15);
  }
  h2 {
    color: #6e49a0;
    font-weight: 700;
    margin-bottom: 30px;
    font-size: 2rem;
  }
  form label {
    display: block;
    margin-bottom: 8px;
    font-weight: 600;
    color: #5a3e8a;
  }
  form input[type="text"],
  form input[type="number"],
  form input[type="file"],
  form textarea {
    width: 100%;
    padding: 12px 14px;
    margin-bottom: 22px;
    border: 1.8px solid #d1c4e9;
    border-radius: 10px;
    font-size: 1rem;
    transition: border-color 0.3s ease;
    resize: vertical;
  }
  form input[type="text"]:focus,
  form input[type="number"]:focus,
  form input[type="file"]:focus,
  form textarea:focus {
    outline: none;
    border-color: #6e49a0;
  }
  textarea {
    min-height: 90px;
  }
  img {
    border-radius: 10px;
    box-shadow: 0 4px 10px rgba(110, 73, 160, 0.15);
    margin-bottom: 20px;
  }
  form label.checkbox-label {
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 20px;
    cursor: pointer;
  }
  form input[type="checkbox"] {
    width: 20px;
    height: 20px;
    cursor: pointer;
  }
  .categorias-list label {
    display: block;
    margin-bottom: 10px;
    font-weight: 500;
    cursor: pointer;
  }
  button[type="submit"] {
    background-color: #6e49a0;
    color: white;
    padding: 14px 32px;
    font-size: 1.1rem;
    font-weight: 700;
    border: none;
    border-radius: 12px;
    cursor: pointer;
    box-shadow: 0 6px 14px rgba(110, 73, 160, 0.4);
    transition: background-color 0.3s ease;
  }
  button[type="submit"]:hover {
    background-color: #5b3a86;
  }
  a {
    display: inline-block;
    margin-top: 30px;
    color: #6e49a0;
    font-weight: 600;
    text-decoration: none;
    transition: color 0.3s ease;
  }
  a:hover {
    color: #9d7ac0;
  }

  /* Responsive */
  @media (max-width: 640px) {
    body {
      padding: 20px 15px;
    }
    .container {
      padding: 20px 25px;
    }
  }
</style>
</head>
<body>

<div class="container">
    <h2>✏️ Editar Producto</h2>
    <form action="" method="POST" enctype="multipart/form-data" novalidate>
        <label for="nombre">Nombre:</label>
        <input id="nombre" type="text" name="nombre" value="<?= htmlspecialchars($producto['nombre']) ?>" required>

        <label for="descripcion">Descripción:</label>
        <textarea id="descripcion" name="descripcion" required><?= htmlspecialchars($producto['descripcion']) ?></textarea>

        <label for="precio">Precio (€):</label>
        <input id="precio" type="number" name="precio" step="0.01" min="0" value="<?= $producto['precio'] ?>" required>

        <label for="stock">Stock:</label>
        <input id="stock" type="number" name="stock" min="0" value="<?= $producto['stock'] ?>" required>

        <label>Imagen actual:</label>
        <img src="../<?= htmlspecialchars($producto['imagen_url']) ?>" alt="Imagen actual del producto" width="150" />

        <label for="imagen">Cambiar imagen:</label>
        <input id="imagen" type="file" name="imagen" accept="image/*">

        <label class="checkbox-label">
            <input type="checkbox" name="destacado" <?= $producto['destacado'] ? 'checked' : '' ?>>
            Producto destacado
        </label>

        <label>Categorías:</label>
        <div class="categorias-list">
            <?php foreach ($categorias as $cat): ?>
                <label>
                    <input type="checkbox" name="categorias[]" value="<?= $cat['id_categoria'] ?>"
                        <?= in_array($cat['id_categoria'], $categoriasProducto) ? 'checked' : '' ?>>
                    <?= htmlspecialchars($cat['nombre_categoria']) ?>
                </label>
            <?php endforeach; ?>
        </div>

        <button type="submit">Guardar Cambios</button>
    </form>

    <a href="productos.php">← Volver al listado</a>
</div>

</body>
</html>
