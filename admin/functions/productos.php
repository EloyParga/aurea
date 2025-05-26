<?php
    function obtenerProductosConCategorias() {
    global $conn;

    $sql = "SELECT 
                p.id_producto, p.nombre, p.descripcion, p.precio, p.stock, p.imagen_url, p.destacado,
                c.id_categoria, c.nombre_categoria
            FROM productos p
            LEFT JOIN producto_categoria pc ON p.id_producto = pc.fk_id_producto
            LEFT JOIN categorias c ON pc.fk_id_categoria = c.id_categoria";

    $resultado = mysqli_query($conn, $sql);
    $productos = [];

    while ($fila = mysqli_fetch_assoc($resultado)) {
        $id = $fila['id_producto'];
        if (!isset($productos[$id])) {
            $productos[$id] = [
                'id_producto' => $fila['id_producto'],
                'nombre' => $fila['nombre'],
                'descripcion' => $fila['descripcion'],
                'precio' => $fila['precio'],
                'stock' => $fila['stock'],
                'imagen_url' => $fila['imagen_url'],
                'destacado' => $fila['destacado'],
                'categorias' => [],
            ];
        }
        if ($fila['nombre_categoria']) {
            $productos[$id]['categorias'][] = $fila['nombre_categoria'];
        }
    }

    return array_values($productos);
}


    function agregarProducto($nombre, $descripcion, $precio, $stock, $imagen_url, $destacado = 0) {
    global $conn;

    $stmt = $conn->prepare("INSERT INTO productos (nombre, descripcion, precio, stock, imagen_url, destacado)
                            VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssdisi", $nombre, $descripcion, $precio, $stock, $imagen_url, $destacado);
    $stmt->execute();

    return $stmt->insert_id;
}


    function actualizarProducto($id_producto, $data) {
    global $conn;

    $nombre = $data['nombre'];
    $descripcion = $data['descripcion'];
    $precio = floatval($data['precio']);
    $stock = intval($data['stock']);
    $imagen_url = $data['imagen_url'] ?? '';
    $destacado = isset($data['destacado']) ? 1 : 0;

    $stmt = $conn->prepare("UPDATE productos SET nombre=?, descripcion=?, precio=?, stock=?, imagen_url=?, destacado=?
                            WHERE id_producto=?");
    $stmt->bind_param("ssdisii", $nombre, $descripcion, $precio, $stock, $imagen_url, $destacado, $id_producto);
    $stmt->execute();
}
    
    function eliminarProducto($id_producto) {
    global $conn;

    $conn->query("DELETE FROM producto_categoria WHERE fk_id_producto = $id_producto");
    $conn->query("DELETE FROM resenas WHERE fk_id_producto = $id_producto");
    $conn->query("DELETE FROM productos WHERE id_producto = $id_producto");
}

    

function asociarProductoConCategorias($id_producto, $categorias) {
    global $conn;

    // Eliminar relaciones anteriores
    $conn->query("DELETE FROM producto_categoria WHERE fk_id_producto = $id_producto");

    // Insertar nuevas relaciones
    foreach ($categorias as $id_categoria) {
        $stmt = $conn->prepare("INSERT INTO producto_categoria (fk_id_producto, fk_id_categoria) VALUES (?, ?)");
        $stmt->bind_param("ii", $id_producto, $id_categoria);
        $stmt->execute();
    }
}

function obtenerProductoPorId($id_producto) {
    global $conn;
    $stmt = $conn->prepare("SELECT * FROM productos WHERE id_producto = ?");
    $stmt->bind_param("i", $id_producto);
    $stmt->execute();
    $resultado = $stmt->get_result(); // obtienes el resultado
    return $resultado->fetch_assoc(); // y luego la fila asociativa
}


function obtenerCategoriasPorProducto($id_producto) {
    global $conn;
    $stmt = $conn->prepare("SELECT fk_id_categoria FROM producto_categoria WHERE fk_id_producto = ?");
    $stmt->bind_param("i", $id_producto);
    $stmt->execute();

    $resultado = $stmt->get_result();
    $categorias = [];

    while ($row = $resultado->fetch_assoc()) {
        $categorias[] = $row['fk_id_categoria'];
    }

    return $categorias;
}







?>