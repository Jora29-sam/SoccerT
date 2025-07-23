<?php
include 'auth.php';

// Consulta para obtener equipos
$sql = "SELECT nombre, escudo_url FROM equipos";
$result = $conn->query($sql);
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
    <title>Equipos</title>
    <!-- Styles -->
    <link rel="stylesheet" href="static/styles/index.css">
    <link rel="stylesheet" href="static/styles/teams.css">
    <link rel="stylesheet" href="static/styles/modal.css">
    <link rel="stylesheet" href="static/styles/perfil.css">

</head>
<body>
    <header class="menu">
        <img src="static/images/logo.png" alt="Logo" class="logo">
        <ul>
            <li><a href="jugadores.php">Jugadores</a></li>
            <li><a href="equipos.php">Equipos</a></li>
        </ul>
        <form class="buscador" action="buscar.php" method="get">
            <a href="resumen.php" class="cart" id="cartIcon">🛒 <span class="cart-count">0</span></a>                   
            <a href="#" class="profile" id="openLogin">👤</a>
            <input type="text" name="q" placeholder="Buscar">
            <button type="submit">🔍</button>
        </form>
    </header>

    <section class="team-list">
        <div class="equipos-container">
            <?php while ($row = $result->fetch_assoc()): ?>
                <a href="jerseys.php?nombre=<?= urlencode($row['nombre']) ?>" class="equipo-btn">
                    <div class="equipo">
                        <img src="<?= $row['escudo_url'] ?>" alt="<?= $row['nombre'] ?>">
                        <p><?= $row['nombre'] ?></p>
                    </div>
                </a>
            <?php endwhile; ?>
        </div>
    </section>
    

    <?php include 'modal-perfil.php'; ?>

    <script>
    const isLoggedIn = <?= empty($usuario) ? 'false' : 'true' ?>;
    </script>

    <div id="loginErrorModal" style="display:none;" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h2>⚠️ Necesitas iniciar sesión</h2>
        <p>Por favor, inicia sesión para acceder al carrito.</p>
        <a href="#" id="openLoginFromError" class="button">Iniciar Sesión</a>
    </div>
    </div>

    <script src="static/js/modal.js"></script>
    <script src="static/js/auth.js"></script>
    <script src="static/js/cart.js"></script>
 

</body>
</html>
