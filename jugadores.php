<?php
include 'auth.php';

// Consulta con JOIN para obtener también el nombre del equipo
$sql = "SELECT j.nombre AS jugador, j.numero, j.imagen_url, j.genero, e.nombre AS equipo
        FROM jugadores j
        JOIN equipos e ON j.id_equipo = e.id_equipo";

$result = $conn->query($sql);
?>

<!-- HTML -->

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Jugadores</title>

    <!-- Styles -->
    <link rel="stylesheet" href="static/styles/index.css">
    <link rel="stylesheet" href="static/styles/jugadores.css">
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

    <section class="jugadores-lista">
        <div class="jugadores-container">
            <?php while ($row = $result->fetch_assoc()): ?>
                <a href="jerseys.php?nombre=<?= urlencode($row['equipo']) ?>&jugador=<?= urlencode($row['jugador']) ?>" class="jugador-btn">
                    <div class="jugador">
                        <img src="<?= $row['imagen_url'] ?>" alt="<?= $row['jugador'] ?>">
                        <div class="jugador-info">
                            <h3><?= $row['jugador'] ?></h3>
                            <p>Número: <?= $row['numero'] ?></p>
                            <p>Equipo: <?= $row['equipo'] ?></p>
                            <p>Género: <?= $row['genero'] == 'F' ? 'Femenino' : 'Masculino' ?></p>
                        </div>
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
