<?php
$host = "localhost";
$dbname = "aurea";
$user = "root";
$pass = "";

// Conexión
$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Funciones reutilizables

function obtenerProductosDestacados($limite = 6) {
    global $conn;
    $stmt = $conn->prepare("SELECT * FROM productos WHERE destacado = 1 LIMIT ?");
    $stmt->bind_param("i", $limite);
    $stmt->execute();
    return $stmt->get_result();
}

function buscarProductos($keyword) {
    global $conn;
    $keyword = "%" . $conn->real_escape_string($keyword) . "%";
    $stmt = $conn->prepare("SELECT * FROM productos WHERE nombre LIKE ? OR descripcion LIKE ?");
    $stmt->bind_param("ss", $keyword, $keyword);
    $stmt->execute();
    return $stmt->get_result();
}

function obtenerResenasDestacadas($limite = 3) {
    global $conn;
    $query = "SELECT r.opinion, r.puntuacion, u.nombre_usuario
              FROM resenas r
              JOIN usuarios u ON r.fk_id_usuario = u.id_usuario
              ORDER BY r.puntuacion DESC
              LIMIT ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $limite);
    $stmt->execute();
    return $stmt->get_result();
}

function obtenerCategorias() {
    global $conn;
    $sql = "SELECT id_categoria, nombre, icono_url FROM categorias";
    $result = mysqli_query($conn, $sql);
    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}

function obtenerProductosCatalogo($categoria = null, $busqueda = null) {
    global $conn;
    $sql = "SELECT p.*, c.nombre AS categoria_nombre 
            FROM productos p 
            JOIN categorias c ON p.id_categoria = c.id_categoria 
            WHERE 1";

    if ($categoria) {
        $sql .= " AND p.id_categoria = " . intval($categoria);
    }
    if ($busqueda) {
        $busqueda = mysqli_real_escape_string($conn, $busqueda);
        $sql .= " AND (p.nombre LIKE '%$busqueda%' OR p.descripcion LIKE '%$busqueda%')";
    }

    $sql .= " ORDER BY c.nombre, p.nombre";
    $result = mysqli_query($conn, $sql);
    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}

function agruparPorCategoria($productos) {
    $agrupados = [];
    foreach ($productos as $prod) {
        $cat = $prod['categoria_nombre'];
        $agrupados[$cat][] = $prod;
    }
    return $agrupados;
}

?>
