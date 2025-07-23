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
        $stmt = $conn->prepare("INSERT INTO ventas (id_usuario, estado) VALUES (?, ?)");
        $stmt->bind_param("is", $_POST['id_usuario'], $_POST['estado']);
        $stmt->execute();
        $mensaje = "✅ Venta registrada exitosamente.";
    } catch (Exception $e) {
        $mensaje = "❌ Error al agregar venta: " . $e->getMessage();
    }
}

// ELIMINAR
if (isset($_GET['eliminar'])) {
    try {
        $id = intval($_GET['eliminar']);
        $conn->query("DELETE FROM ventas WHERE id_venta = $id");
        $mensaje = "✅ Venta eliminada.";
    } catch (Exception $e) {
        $mensaje = "❌ Error al eliminar venta: " . $e->getMessage();
    }
}

// EDITAR
if (isset($_POST['editar'])) {
    try {
        $stmt = $conn->prepare("UPDATE ventas SET id_usuario=?, estado=? WHERE id_venta=?");
        $stmt->bind_param("isi", $_POST['id_usuario'], $_POST['estado'], $_POST['id_venta']);
        $stmt->execute();
        $mensaje = "✅ Venta actualizada.";
    } catch (Exception $e) {
        $mensaje = "❌ Error al editar venta: " . $e->getMessage();
    }
}

// Obtener venta a editar
$venta_editar = null;
if (isset($_GET['editar'])) {
    $id = intval($_GET['editar']);
    $res = $conn->query("SELECT * FROM ventas WHERE id_venta = $id");
    $venta_editar = $res->fetch_assoc();
}

// Obtener todas las ventas
$result = $conn->query("SELECT * FROM ventas ORDER BY id_venta DESC");
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Ventas</title>
<link rel="stylesheet" href="static/styles/index.css">
<link rel="stylesheet" href="static/styles/proveedores.css">
<link rel="stylesheet" href="static/styles/modal.css">
<link rel="stylesheet" href="static/styles/perfil.css">
<link rel="stylesheet" href="static/styles/dropdown.css">
<style>
table {border-collapse: collapse; width: 100%;}
th, td {padding: 8px; border: 1px solid #ccc; text-align: left;}
form {margin: 20px 0;}
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

<!-- FORMULARIO -->
<form method="post">
    <input type="hidden" name="id_venta" value="<?= $venta_editar['id_venta'] ?? '' ?>">
    <input type="number" name="id_usuario" placeholder="ID Usuario" required value="<?= $venta_editar['id_usuario'] ?? '' ?>">
    <select name="estado">
        <option value="pendiente" <?= (isset($venta_editar) && $venta_editar['estado'] == 'pendiente') ? 'selected' : '' ?>>Pendiente</option>
        <option value="pagada" <?= (isset($venta_editar) && $venta_editar['estado'] == 'pagada') ? 'selected' : '' ?>>Pagada</option>
        <option value="enviada" <?= (isset($venta_editar) && $venta_editar['estado'] == 'enviada') ? 'selected' : '' ?>>Enviada</option>
        <option value="completada" <?= (isset($venta_editar) && $venta_editar['estado'] == 'completada') ? 'selected' : '' ?>>Completada</option>
    </select>
    <button type="submit" name="<?= $venta_editar ? 'editar' : 'agregar' ?>">
        <?= $venta_editar ? 'Actualizar' : 'Agregar' ?>
    </button>
</form>

<!-- LISTADO DE VENTAS -->
<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>ID Usuario</th>
            <th>Fecha</th>
            <th>Estado</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
    <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?= $row['id_venta'] ?></td>
            <td><?= $row['id_usuario'] ?></td>
            <td><?= $row['fecha'] ?></td>
            <td><?= $row['estado'] ?></td>
            <td>
                <a href="ventas.php?editar=<?= $row['id_venta'] ?>">Editar</a> | 
                <a href="ventas.php?eliminar=<?= $row['id_venta'] ?>" onclick="return confirm('¿Eliminar venta?')">Eliminar</a>
            </td>
        </tr>
    <?php endwhile; ?>
    </tbody>
</table>

<!-- BOTÓN PDF -->
<form method="get" action="ventas_pdf.php" style="margin: 20px 0;">
    <button type="submit">📄 Exportar a PDF</button>
</form>

<?php include 'modal-perfil.php'; ?>
<script src="static/js/modal.js"></script>
<script src="static/js/auth.js"></script>
</body>
</html>
