<?php

include 'auth.php';

if (!isset($_SESSION['id_usuario'])) {
    header("Location: index.php?error=login");
    exit;
}


$id_usuario = $_SESSION['id_usuario'];

// Consulta el carrito del usuario
$sql = "
SELECT c.id_carrito, j.nombre AS jersey, t.talla AS talla, c.jugador_custom, c.cantidad, c.fecha_agregado
FROM carrito c
JOIN jerseys j ON c.id_jersey = j.id_jersey
JOIN tallas t ON c.id_talla = t.id_talla
WHERE c.id_usuario = ?
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_usuario);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Resumen del Carrito</title>
<link rel="stylesheet" href="static/styles/index.css">
<link rel="stylesheet" href="static/styles/resumen.css">
<link rel="stylesheet" href="static/styles/modal.css">
<link rel="stylesheet" href="static/styles/perfil.css">

</head>
<body class="resumen">

<header class="menu">
    <img src="static/images/logo.png" alt="Logo" class="logo">
    <ul>
        <li><a href="jugadores.php">Jugadores</a></li>
        <li><a href="equipos.php">Equipos</a></li>
    </ul>
    <form class="buscador" action="buscar.php" method="get">
        <a href="#" class="profile" id="openLogin">👤</a>
        <input type="text" name="q" placeholder="Buscar">
        <button type="submit">🔍</button>
    </form>
</header>

<main>
<h1>🛒 Resumen de tu Carrito</h1>

<?php if ($result->num_rows > 0): ?>
<table>
<thead>
<tr>
    <th>Jersey</th>
    <th>Talla</th>
    <th>Jugador</th>
    <th>Cantidad</th>
    <th>Fecha agregado</th>
    <th>Acciones</th>
</tr>
</thead>
<tbody>
<?php while ($row = $result->fetch_assoc()): ?>
<tr>
    <td><?= htmlspecialchars($row['jersey']) ?></td>
    <td><?= htmlspecialchars($row['talla']) ?></td>
    <td><?= htmlspecialchars($row['jugador_custom']) ?></td>
    <td>
        <form action="modificar_carrito.php" method="post" style="display:inline;">
            <input type="hidden" name="id_carrito" value="<?= $row['id_carrito'] ?>">
            <input type="number" name="cantidad" value="<?= htmlspecialchars($row['cantidad']) ?>" min="1">
            <button type="submit">Actualizar</button>
        </form>
    </td>
    <td><?= htmlspecialchars($row['fecha_agregado']) ?></td>
    <td>
        <form action="eliminar_carrito.php" method="post" style="display:inline;">
            <input type="hidden" name="id_carrito" value="<?= $row['id_carrito'] ?>">
            <button type="submit" onclick="return confirm('¿Estás seguro de eliminar este producto?')">Eliminar</button>
        </form>
    </td>
</tr>
<?php endwhile; ?>
</tbody>
</table>

<a href="index.php">Seguir Comprando</a>

<!-- ✅ Botón de Comprar -->
<form id="formComprar" action="comprar.php" method="post">
    <button type="submit" class="comprar-btn">¡Comprar!</button>
</form>

<?php else: ?>
<p class="vacio">Tu carrito está vacío.</p>
<a href="index.php">Ir a la tienda</a>
<?php endif; ?>
</main> 
    <?php include 'modal-perfil.php'; ?>
    <?php include 'modal_exito.php'; ?>

    <script src="static/js/modal.js"></script>
    <script src="static/js/auth.js"></script>
    <script src="static/js/cart.js"></script>
    <script src="static/js/comprar.js"></script>

</body>
</html>