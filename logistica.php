<?php
include 'auth.php';
include 'conexion.php';
if (!isset($_SESSION["rol"]) || $_SESSION["rol"] !== "admin") {
    header("Location: index.php");
    exit();
}

$mensaje = '';

// INSERTAR
if (isset($_POST['agregar'])) {
    try {
        $stmt = $conn->prepare("INSERT INTO envios (id_venta, transportista, numero_guia, estado) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("isss", $_POST['id_venta'], $_POST['transportista'], $_POST['numero_guia'], $_POST['estado']);
        $stmt->execute();
        $mensaje = "✅ Envío registrado correctamente.";
    } catch (Exception $e) {
        $mensaje = "❌ Error al registrar envío: " . $e->getMessage();
    }
}

// EDITAR
if (isset($_POST['editar'])) {
    try {
        $stmt = $conn->prepare("UPDATE envios SET id_venta=?, transportista=?, numero_guia=?, estado=? WHERE id_envio=?");
        $stmt->bind_param("isssi", $_POST['id_venta'], $_POST['transportista'], $_POST['numero_guia'], $_POST['estado'], $_POST['id_envio']);
        $stmt->execute();
        $mensaje = "✅ Envío actualizado correctamente.";
    } catch (Exception $e) {
        $mensaje = "❌ Error al editar envío: " . $e->getMessage();
    }
}

// ELIMINAR
if (isset($_GET['eliminar'])) {
    try {
        $id = intval($_GET['eliminar']);
        $conn->query("DELETE FROM envios WHERE id_envio = $id");
        $mensaje = "✅ Envío eliminado.";
    } catch (Exception $e) {
        $mensaje = "❌ Error al eliminar envío: " . $e->getMessage();
    }
}

// Obtener envío a editar
$envio_editar = null;
if (isset($_GET['editar'])) {
    $id = intval($_GET['editar']);
    $res = $conn->query("SELECT * FROM envios WHERE id_envio = $id");
    $envio_editar = $res->fetch_assoc();
}

// Obtener todos los envíos
$result = $conn->query("SELECT * FROM envios ORDER BY id_envio DESC");
?>

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
    <title>Logística</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Estilos -->
    <link rel="stylesheet" href="static/styles/index.css">
    <link rel="stylesheet" href="static/styles/proveedores.css">
    <link rel="stylesheet" href="static/styles/modal.css">
    <link rel="stylesheet" href="static/styles/perfil.css">
    <link rel="stylesheet" href="static/styles/dropdown.css">
    <style>
        table { border-collapse: collapse; width: 100%; margin-bottom: 30px; }
        th, td { padding: 10px; border: 1px solid #ccc; background-color: white; color: black; }
        form { margin: 20px 0; }
        input, select { padding: 6px; margin: 5px; }
        button { padding: 8px 15px; background-color: #0077b6; color: white; border: none; cursor: pointer; }
        button:hover { background-color: #023e8a; }
    </style>
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
                <li><a href="logistica.php">Logística</a></li>
            </ul>
        </li>          
    </ul>

    <form class="buscador" action="/buscar" method="get">
        <a href="#" class="profile" id="openLogin">👤</a>
        <input type="text" name="q" placeholder="Buscar">
        <button type="submit">🔍</button>
    </form>
</header>

<?php if ($mensaje): ?>
<script>alert("<?= htmlspecialchars($mensaje) ?>");</script>
<?php endif; ?>

<h1>🚚 Gestión Logística - Envíos</h1>

<form method="post">
    <input type="hidden" name="id_envio" value="<?= $envio_editar['id_envio'] ?? '' ?>">
    <input type="number" name="id_venta" placeholder="ID Venta" required value="<?= $envio_editar['id_venta'] ?? '' ?>">
    <input type="text" name="transportista" placeholder="Transportista" required value="<?= $envio_editar['transportista'] ?? '' ?>">
    <input type="text" name="numero_guia" placeholder="Número de guía" required value="<?= $envio_editar['numero_guia'] ?? '' ?>">
    <select name="estado">
        <option value="pendiente" <?= (isset($envio_editar) && $envio_editar['estado'] == 'pendiente') ? 'selected' : '' ?>>Pendiente</option>
        <option value="en tránsito" <?= (isset($envio_editar) && $envio_editar['estado'] == 'en tránsito') ? 'selected' : '' ?>>En tránsito</option>
        <option value="entregado" <?= (isset($envio_editar) && $envio_editar['estado'] == 'entregado') ? 'selected' : '' ?>>Entregado</option>
    </select>
    <button type="submit" name="<?= $envio_editar ? 'editar' : 'agregar' ?>">
        <?= $envio_editar ? 'Actualizar Envío' : '➕ Registrar Envío' ?>
    </button>
</form>

<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>ID Venta</th>
            <th>Fecha</th>
            <th>Transportista</th>
            <th>Número de Guía</th>
            <th>Estado</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
    <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?= $row['id_envio'] ?></td>
            <td><?= $row['id_venta'] ?></td>
            <td><?= $row['fecha_envio'] ?></td>
            <td><?= htmlspecialchars($row['transportista']) ?></td>
            <td><?= htmlspecialchars($row['numero_guia']) ?></td>
            <td><?= htmlspecialchars($row['estado']) ?></td>
            <td>
                <a href="logistica.php?editar=<?= $row['id_envio'] ?>">Editar</a> |
                <a href="logistica.php?eliminar=<?= $row['id_envio'] ?>" onclick="return confirm('¿Eliminar este envío?')">Eliminar</a>
            </td>
        </tr>
    <?php endwhile; ?>
    </tbody>
</table>

<form method="get" action="logistica_pdf.php" style="margin: 20px 0;">
    <button type="submit">📄 Exportar a PDF</button>
</form>

<?php include 'modal-perfil.php'; ?>
<script src="static/js/modal.js"></script>
<script src="static/js/auth.js"></script>
</body>
</html>
