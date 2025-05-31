<?php
session_start();
if (!isset($_SESSION['usuario_id']) || $_SESSION['tipo_usuario'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

require_once('../functions/db.php');

// Obtener usuarios
$query = "SELECT id_usuario, nombre_usuario, email, tipo_usuario, fecha_nac FROM usuarios";
$result = $conn->query($query);

//Contar total usuarios
$totalUsuariosQuery = "SELECT COUNT(*) AS total FROM usuarios";
$totalResult = $conn->query($totalUsuariosQuery);
$totalUsuarios = $totalResult->fetch_assoc()['total'];

?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Usuarios - Aurea</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" />
    <link rel="stylesheet" href="../assets/css/estilos.css" />
    <link rel="stylesheet" href="assets/css/styles.css">
</head>
<body>
<?php include('partials/admin_navbar.php'); ?>

<h1>Gestión de Usuarios</h1>

<div class="contador-usuarios">
    Total de usuarios registrados: <strong><?= $totalUsuarios ?></strong>
</div>

<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Email</th>
            <th>Tipo</th>
            <th>Fecha Nac.</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($usuario = $result->fetch_assoc()): ?>
            <tr>
                <td><?= $usuario['id_usuario'] ?></td>
                <td><?= htmlspecialchars($usuario['nombre_usuario']) ?></td>
                <td><?= htmlspecialchars($usuario['email']) ?></td>
                <td><?= htmlspecialchars($usuario['tipo_usuario']) ?></td>
                <td><?= htmlspecialchars($usuario['fecha_nac']) ?></td>
                <td>
                    <!-- Cambiar rol -->
                    <form class="inline" method="post" action="functions/acciones_usuario.php">
                        <input type="hidden" name="accion" value="cambiar_rol">
                        <input type="hidden" name="id_usuario" value="<?= $usuario['id_usuario'] ?>">
                        <button>Cambiar Rol</button>
                    </form>

                    <!-- Cambiar contraseña -->
                    <form class="inline" method="post" action="functions/acciones_usuario.php">
                        <input type="hidden" name="accion" value="cambiar_contrasena">
                        <input type="hidden" name="id_usuario" value="<?= $usuario['id_usuario'] ?>">
                        <button>Cambiar Contraseña</button>
                    </form>

                    <!-- Eliminar -->
                    <form class="inline" method="post" action="functions/acciones_usuario.php" onsubmit="return confirm('¿Estás seguro de eliminar este usuario?');">
                        <input type="hidden" name="accion" value="eliminar">
                        <input type="hidden" name="id_usuario" value="<?= $usuario['id_usuario'] ?>">
                        <button style="background: #c0392b;">Eliminar</button>
                    </form>
                </td>
            </tr>
        <?php endwhile; ?>
    </tbody>
</table>

</body>
</html>
