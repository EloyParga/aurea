<?php
session_start();
require_once('../functions/db.php');
require_once('functions/productos.php');

if (!isset($_SESSION['usuario_id']) || $_SESSION['tipo_usuario'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

// Obtener datos
$productos = obtenerProductosConCategorias(); // incluye JOIN con categorías
$categorias = obtenerCategorias(); // para el formulario
$mensaje = '';

// Manejo de formularios
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['accion'])) {
        switch ($_POST['accion']) {
            case 'agregar':
                $nombre = $_POST['nombre'];
                $descripcion = $_POST['descripcion'];
                $precio = floatval($_POST['precio']);
                $stock = intval($_POST['stock']);
                $imagen_url = $_POST['imagen_url'] ?? '';
                $destacado = isset($_POST['destacado']) ? 1 : 0;
                $categoriasSeleccionadas = $_POST['categorias'] ?? [];

                $id_producto = agregarProducto($nombre, $descripcion, $precio, $stock, $imagen_url, $destacado);
                asociarProductoConCategorias($id_producto, $categoriasSeleccionadas);
                $mensaje = '✅ Producto agregado con éxito.';
                break;

            case 'editar':
                $id = intval($_POST['id_producto']);
                actualizarProducto($id, $_POST);
                asociarProductoConCategorias($id, $_POST['categorias'] ?? []);
                $mensaje = '✅ Producto actualizado.';
                break;

            case 'eliminar':
                $id = intval($_POST['id_producto']);
                eliminarProducto($id);
                $mensaje = '🗑 Producto eliminado.';
                break;
        }
    }
    // Recargar datos después del cambio
    $productos = obtenerProductosConCategorias();
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8" />
<title>Gestión de Productos</title>
<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet" />
<style>
  /* Reset */
  *, *::before, *::after {
    box-sizing: border-box;
  }
  body {
    font-family: 'Roboto', sans-serif;
    background: #f9f9fb;
    margin: 0;
    padding: 30px 40px;
    color: #333;
  }
  h1, h2 {
    color: #6e49a0;
    margin-bottom: 20px;
  }
  h1 {
    font-weight: 700;
    font-size: 2.6rem;
    display: flex;
    align-items: center;
    gap: 10px;
  }
  h2 {
    font-weight: 600;
    font-size: 1.8rem;
    margin-top: 40px;
    margin-bottom: 20px;
  }
  .success {
    background-color: #e6f9ea;
    color: #256029;
    padding: 12px 18px;
    border-radius: 8px;
    border: 1.5px solid #4caf50;
    margin-bottom: 30px;
    font-weight: 600;
    box-shadow: 0 2px 6px rgba(37, 96, 41, 0.2);
  }
  .form-agregrar {
    background: #fff;
    padding: 25px 30px;
    border-radius: 12px;
    box-shadow: 0 6px 16px rgba(109, 83, 158, 0.15);
    max-width: 600px;
  }
  form p {
    margin-bottom: 18px;
  }
  label {
    display: block;
    font-weight: 600;
    margin-bottom: 6px;
  }
  input[type="text"],
  input[type="number"],
  input[type="url"],
  textarea {
    width: 100%;
    padding: 10px 14px;
    border: 1.8px solid #d1c4e9;
    border-radius: 8px;
    font-size: 1rem;
    transition: border-color 0.25s ease;
  }
  input[type="text"]:focus,
  input[type="number"]:focus,
  input[type="url"]:focus,
  textarea:focus {
    border-color: #6e49a0;
    outline: none;
  }
  textarea {
    resize: vertical;
    min-height: 80px;
  }
  .checkbox-group {
    display: flex;
    flex-wrap: wrap;
    gap: 12px 20px;
    margin-top: 10px;
  }
  .checkbox-group label {
    font-weight: 500;
    display: flex;
    align-items: center;
    gap: 8px;
    cursor: pointer;
  }
  input[type="checkbox"] {
    width: 18px;
    height: 18px;
    cursor: pointer;
  }
  button[type="submit"] {
    background-color: #6e49a0;
    color: white;
    border: none;
    border-radius: 10px;
    padding: 14px 28px;
    font-weight: 700;
    font-size: 1.1rem;
    cursor: pointer;
    box-shadow: 0 6px 12px rgba(110, 73, 160, 0.4);
    transition: background-color 0.3s ease;
  }
  button[type="submit"]:hover {
    background-color: #5b3a86;
  }
  table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 25px;
    box-shadow: 0 6px 18px rgba(109, 83, 158, 0.1);
    border-radius: 12px;
    overflow: hidden;
  }
  thead {
    background-color: #6e49a0;
    color: white;
  }
  th, td {
    padding: 14px 18px;
    text-align: left;
    border-bottom: 1px solid #ddd;
  }
  tbody tr:hover {
    background-color: #f3e8ff;
  }
  td:nth-child(4) {
    max-width: 220px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
  }
  .acciones {
    display: flex;
    align-items: center;
    gap: 12px;
  }
  .acciones form.inline {
    margin: 0;
  }
  .acciones button, .acciones a {
    background: transparent;
    border: none;
    cursor: pointer;
    font-size: 1.25rem;
    color: #6e49a0;
    transition: color 0.3s ease;
  }
  .acciones button:hover,
  .acciones a:hover {
    color: #9d7ac0;
  }
  .acciones a {
    text-decoration: none;
  }

  /* Responsive */
  @media (max-width: 768px) {
    body {
      padding: 20px 15px;
    }
    form, table {
      width: 100%;
      overflow-x: auto;
    }
    table th, table td {
      white-space: nowrap;
    }
  }
</style>
</head>
<body>

<?php include('partials/admin_navbar.php'); ?>

<h1>📦 Gestión de Productos</h1>

<?php if ($mensaje): ?>
  <div class="success"><?= htmlspecialchars($mensaje) ?></div>
<?php endif; ?>

<!-- Formulario para agregar producto -->
<h2>➕ Agregar Producto</h2>
<form class="form-agregrar" method="POST" novalidate>
  <input type="hidden" name="accion" value="agregar" />
  <p>
    <label for="nombre">Nombre:</label>
    <input id="nombre" name="nombre" type="text" required />
  </p>
  <p>
    <label for="descripcion">Descripción:</label>
    <textarea id="descripcion" name="descripcion"></textarea>
  </p>
  <p>
    <label for="precio">Precio (€):</label>
    <input id="precio" name="precio" type="number" step="0.01" min="0" required />
  </p>
  <p>
    <label for="stock">Stock:</label>
    <input id="stock" name="stock" type="number" min="0" required />
  </p>
  <p>
    <label for="imagen_url">URL Imagen:</label>
    <input id="imagen_url" name="imagen_url" type="url" placeholder="https://..." />
  </p>
  <p>
    <label><input type="checkbox" name="destacado" /> Producto destacado</label>
  </p>
  <p><strong>Categorías:</strong></p>
  <div class="checkbox-group">
    <?php foreach ($categorias as $cat): ?>
      <label>
        <input type="checkbox" name="categorias[]" value="<?= $cat['id_categoria'] ?>" />
        <?= htmlspecialchars($cat['nombre_categoria']) ?>
      </label>
    <?php endforeach; ?>
  </div>
  <p>
    <button type="submit">Agregar Producto</button>
  </p>
</form>

<!-- Tabla de productos -->
<h2>📋 Lista de Productos</h2>
<table>
  <thead>
    <tr>
      <th>Nombre</th>
      <th>Precio</th>
      <th>Stock</th>
      <th>Categorías</th>
      <th style="width:110px;">Acciones</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($productos as $producto): ?>
    <tr>
      <td><?= htmlspecialchars($producto['nombre']) ?></td>
      <td><?= number_format($producto['precio'], 2) ?> €</td>
      <td><?= $producto['stock'] ?></td>
      <td><?= htmlspecialchars(implode(', ', $producto['categorias'])) ?></td>
      <td class="acciones">
        <form class="inline" method="POST" onsubmit="return confirm('¿Eliminar producto?')">
          <input type="hidden" name="accion" value="eliminar" />
          <input type="hidden" name="id_producto" value="<?= $producto['id_producto'] ?>" />
          <button type="submit" title="Eliminar producto">🗑️</button>
        </form>
        <a href="editar_producto.php?id=<?= $producto['id_producto'] ?>" title="Editar producto">✏️</a>
      </td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>

</body>
</html>
