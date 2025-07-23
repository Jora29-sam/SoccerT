<?php
include 'auth.php';


if (!isset($_SESSION["rol"]) || $_SESSION["rol"] !== "admin") {
    header("Location: index.php");
    exit();
}
?>
<!-- HTML -->

<!DOCTYPE html>
<html lang="es">
    <script>
// Bloquea Ctrl+U (Ver código fuente)
document.addEventListener("keydown", function (e) {
    if (e.ctrlKey && e.key.toLowerCase() === "u") {
        alert("🚫 Ver código fuente no está permitido.");
        e.preventDefault();
    }
});

// Bloquea clic derecho
document.addEventListener("contextmenu", function (e) {
    alert("🚫 Clic derecho deshabilitado.");
    e.preventDefault();
});
</script>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Soccer Modules</title>

    <!-- Styles -->
    <link rel="stylesheet" href="static/styles/index.css">
    <link rel="stylesheet" href="static/styles/dashboard.css">
    <link rel="stylesheet" href="static/styles/modal.css">
    <link rel="stylesheet" href="static/styles/perfil.css">
    <link rel="stylesheet" href="static/styles/dropdown.css">

</head>
<body>
    <header class="menu">
        <img src="static/images/logo.png" alt="Logo" class="logo">
        <ul>
            <li>
                <a href="#">SCM</a>
                <ul class="submenu">
                    <li><a href="proveedores.php">Proveedores</a></li>
                    <li><a href="compras.php">Compras</a></li>
                </ul>
            </li>  
            <li>
                <a href="#">ERP</a>
                <ul class="submenu">
                    <li><a href="ventas.php">Ventas</a></li>
                    <li><a href="finanzas.php">Finanzas</a></li>
                    <li><a href="logistica.php">Logistica</a></li>
                </ul>
            </li>          
        </ul>

        <form class="buscador" action="/buscar" method="get">
            <a href="#" class="profile" id="openLogin">👤</a>
            <input type="text" name="q" placeholder="Buscar">
            <button type="submit">🔍</button>
        </form>
    </header>

    <?php include 'modal-perfil.php'; ?>

    <script src="static/js/modal.js"></script>
    <script src="static/js/auth.js"></script>
       
</body>
</html>