<?php
include 'auth.php';
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Soccer Threats</title>

<link rel="stylesheet" href="static/styles/index.css">
<link rel="stylesheet" href="static/styles/login.css">
<link rel="stylesheet" href="static/styles/modal.css">
<link rel="stylesheet" href="static/styles/perfil.css">
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

<div class="imagen_principal">
    <img src="static/images/wall/mainwall.jpg" alt="Imagen Principal">
</div>

<div class="slogan">
    <p>"Your game, your jersey, your identity."</p>
</div>

<section class="products">
    <h2>TOP JERSEYS</h2>
    <div class="product-container">
    <?php
        $sql = "SELECT j.nombre, j.precio, j.imagen_url, e.nombre AS equipo
                FROM jerseys j
                JOIN equipos e ON j.id_equipo = e.id_equipo
                LIMIT 4";
        $result = $conn->query($sql);

        while ($row = $result->fetch_assoc()) {
            $rutaImagen = ltrim($row["imagen_url"], '/');
            echo '<div class="product">';
            echo '  <a href="jerseys.php?nombre=' . urlencode($row["equipo"]) . '">';
            echo '    <img src="' . $rutaImagen . '" alt="' . htmlspecialchars($row["equipo"]) . ' Jersey">';
            echo '  </a>';
            echo '  <p>' . htmlspecialchars($row["nombre"]) . '</p>';
            echo '  <span class="price">$MXN' . number_format($row["precio"], 2) . '</span>';
            echo '</div>';
        }
    ?>
    </div>
</section>

<section class="trending-teams">
    <h2>Equipos más vendidos</h2>
    <div class="team-container">
    <?php
        $sql = "SELECT nombre, escudo_url FROM equipos LIMIT 6";
        $result = $conn->query($sql);

        while ($row = $result->fetch_assoc()) {
            $equipo = htmlspecialchars($row["nombre"]);
            $logo = htmlspecialchars($row["escudo_url"]);
            echo '<div class="team">';
            echo '  <a href="jerseys.php?nombre=' . urlencode($equipo) . '">';
            echo '    <img src="' . $logo . '" alt="' . $equipo . '">';
            echo '  </a>';
            echo '  <p>' . $equipo . '</p>';
            echo '</div>';
        }
    ?>
        <div class="team">
            <a href="equipos.php">
                <img src="static/images/mas.png" alt="más">
            </a>
            <p>Más</p>
        </div>
    </div>
</section>

    <?php include 'modal-perfil.php'; ?>
    
    <?php if (empty($usuario)): ?>
    <!-- Modal Leads -->
    <div class="modal" id="leadModal">
        <div class="modal-content">
            <span class="close" id="closeLead">&times;</span>
            <h2>¡Obtén nuestro catálogo gratis!</h2>
            <p>Déjanos tus datos y te enviamos el PDF al instante.</p>
            <form id="leadForm">
            <input type="text" name="nombre" placeholder="Tu nombre" required><br>
            <input type="email" name="correo" placeholder="Tu correo electrónico" required><br>
            <input type="text" name="telefono" placeholder="Tu teléfono" required><br>
            <button type="submit">Recibir Catálogo</button>
            </form>
            <div id="leadMsg"></div>
        </div>
    </div>

    <?php endif; ?>

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
    <script src="static/js/leads.js"></script>

</body>
</html>
