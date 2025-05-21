<?php
// admin_navbar.php
?>
<nav class="navbar">
    <div class="nav-left">
        <a href="/aurea/admin/index.php">Panel</a>
        <a href="/aurea/admin/usuarios.php">Usuarios</a>
        <a href="/aurea/admin/productos.php">Productos</a>
        <a href="/aurea/admin/pedidos.php">Pedidos</a>
    </div>
    <div class="nav-right">
        <a href="javascript:history.back();">⬅ Atrás</a>
        <form method="post" action="/aurea/logout.php" style="display:inline;">
            <button class="logout-btn">Cerrar sesión</button>
        </form>
    </div>
</nav>

<style>
.navbar {
    background-color: #9D7AC0;
    padding: 15px 30px;
    color: white;
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-family: 'Roboto', sans-serif;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
}

.nav-left a,
.nav-right a {
    color: white;
    text-decoration: none;
    margin-right: 20px;
    font-weight: 500;
}

.nav-right form {
    display: inline;
}

.nav-left a:hover,
.nav-right a:hover {
    text-decoration: underline;
}

.logout-btn {
    background-color: white;
    color: #9D7AC0;
    padding: 6px 14px;
    border: none;
    border-radius: 5px;
    font-weight: bold;
    cursor: pointer;
    transition: background 0.3s;
}

.logout-btn:hover {
    background-color: #f0e6fb;
}
</style>
