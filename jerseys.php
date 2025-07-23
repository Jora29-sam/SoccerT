<?php
include 'auth.php';

// Validar parámetro de equipo
$nombre_equipo = isset($_GET['nombre']) ? $_GET['nombre'] : null;
if (!$nombre_equipo) {
    die("No se proporcionó un equipo.");
}

// Buscar el equipo
$stmt = $conn->prepare("SELECT id_equipo FROM equipos WHERE nombre = ?");
$stmt->bind_param("s", $nombre_equipo);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    die("Equipo no encontrado.");
}
$id_equipo = $result->fetch_assoc()['id_equipo'];

// Cargar jersey
$jersey_sql = "SELECT * FROM jerseys WHERE id_equipo = $id_equipo LIMIT 1";
$jersey = $conn->query($jersey_sql)->fetch_assoc();

// Cargar jugadores del equipo
$jugadores_sql = "SELECT nombre, numero FROM jugadores WHERE id_equipo = $id_equipo";
$jugadores = $conn->query($jugadores_sql);
$jugador_seleccionado = isset($_GET['jugador']) ? $_GET['jugador'] : '';

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Jersey | <?= htmlspecialchars($nombre_equipo) ?></title>
    <!-- Styles -->
    <link rel="stylesheet" href="static/styles/index.css">
    <link rel="stylesheet" href="static/styles/jersey.css">
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
    <div class="producto">
        <img src="<?= $jersey['imagen_url'] ?>" alt="Jersey <?= $nombre_equipo ?>">

        <div class="info">
            <h1><?= $jersey['nombre'] ?></h1>
            <p class="precio">$MXN<?= number_format($jersey['precio'], 2) ?></p>

        <form action="cart.php" method="post" class="form-compra">
            <input type="hidden" name="jersey_id" value="<?= $jersey['id_jersey'] ?>">

            <div class="tallas">
                <p><strong>Selecciona tu talla:</strong></p>
                <label><input type="radio" name="talla" value="S" required> S</label>
                <label><input type="radio" name="talla" value="M"> M</label>
                <label><input type="radio" name="talla" value="L"> L</label>
                <label><input type="radio" name="talla" value="XL"> XL</label>
            </div>

            <div class="personalizacion">
                <h2>Customiza</h2>
                <label><input type="radio" name="personalizacion" value="jugador" checked> Escoge un jugador:</label>
                <select name="jugador" required>
                    <option disabled selected>Lista de jugadores</option>
                    <?php while ($jugador = $jugadores->fetch_assoc()): ?>
                        <option value="<?= $jugador['nombre'] ?> (<?= $jugador['numero'] ?>)"
                        <?= ($jugador['nombre'] === $jugador_seleccionado) ? 'selected' : '' ?>>
                            <?= $jugador['nombre'] ?> (<?= $jugador['numero'] ?>)
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>

            <button type="submit" class="add-to-cart">Añadir al carrito 🛒</button>
        </form>

    </div>

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
