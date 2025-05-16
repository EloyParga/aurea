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
?>
