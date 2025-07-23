<?php
include 'auth.php';
include 'conexion.php';
if (!isset($_SESSION["rol"]) || $_SESSION["rol"] !== "admin") {
    header("Location: index.php");
    exit();
}

$mensaje = '';

// PAGOS CLIENTES
if (isset($_POST['agregar_cliente'])) {
    try {
        $stmt = $conn->prepare("INSERT INTO pagos_clientes (id_venta, monto, metodo) VALUES (?, ?, ?)");
        $stmt->bind_param("ids", $_POST['id_venta'], $_POST['monto'], $_POST['metodo']);
        $stmt->execute();
        $mensaje = "✅ Pago de cliente registrado correctamente.";
    } catch (Exception $e) {
        $mensaje = "❌ Error al registrar pago de cliente: " . $e->getMessage();
    }
}

if (isset($_POST['editar_cliente'])) {
    try {
        $stmt = $conn->prepare("UPDATE pagos_clientes SET id_venta=?, monto=?, metodo=? WHERE id_pago=?");
        $stmt->bind_param("idsi", $_POST['id_venta'], $_POST['monto'], $_POST['metodo'], $_POST['id_pago']);
        $stmt->execute();
        $mensaje = "✅ Pago de cliente actualizado correctamente.";
    } catch (Exception $e) {
        $mensaje = "❌ Error al editar pago de cliente: " . $e->getMessage();
    }
}

if (isset($_GET['eliminar_cliente'])) {
    try {
        $id = intval($_GET['eliminar_cliente']);
        $conn->query("DELETE FROM pagos_clientes WHERE id_pago = $id");
        $mensaje = "✅ Pago de cliente eliminado.";
    } catch (Exception $e) {
        $mensaje = "❌ Error al eliminar pago de cliente: " . $e->getMessage();
    }
}

$cliente_editar = null;
if (isset($_GET['editar_cliente'])) {
    $id = intval($_GET['editar_cliente']);
    $res = $conn->query("SELECT * FROM pagos_clientes WHERE id_pago = $id");
    $cliente_editar = $res->fetch_assoc();
}

// PAGOS PROVEEDORES
if (isset($_POST['agregar_proveedor'])) {
    try {
        $stmt = $conn->prepare("INSERT INTO pagos_proveedores (id_orden, monto, metodo) VALUES (?, ?, ?)");
        $stmt->bind_param("ids", $_POST['id_orden'], $_POST['monto'], $_POST['metodo']);
        $stmt->execute();
        $mensaje = "✅ Pago a proveedor registrado correctamente.";
    } catch (Exception $e) {
        $mensaje = "❌ Error al registrar pago a proveedor: " . $e->getMessage();
    }
}

if (isset($_POST['editar_proveedor'])) {
    try {
        $stmt = $conn->prepare("UPDATE pagos_proveedores SET id_orden=?, monto=?, metodo=? WHERE id_pago=?");
        $stmt->bind_param("idsi", $_POST['id_orden'], $_POST['monto'], $_POST['metodo'], $_POST['id_pago']);
        $stmt->execute();
        $mensaje = "✅ Pago a proveedor actualizado correctamente.";
    } catch (Exception $e) {
        $mensaje = "❌ Error al editar pago a proveedor: " . $e->getMessage();
    }
}

if (isset($_GET['eliminar_proveedor'])) {
    try {
        $id = intval($_GET['eliminar_proveedor']);
        $conn->query("DELETE FROM pagos_proveedores WHERE id_pago = $id");
        $mensaje = "✅ Pago a proveedor eliminado.";
    } catch (Exception $e) {
        $mensaje = "❌ Error al eliminar pago a proveedor: " . $e->getMessage();
    }
}

$proveedor_editar = null;
if (isset($_GET['editar_proveedor'])) {
    $id = intval($_GET['editar_proveedor']);
    $res = $conn->query("SELECT * FROM pagos_proveedores WHERE id_pago = $id");
    $proveedor_editar = $res->fetch_assoc();
}

$clientes = $conn->query("SELECT * FROM pagos_clientes ORDER BY id_pago DESC");
$proveedores = $conn->query("SELECT * FROM pagos_proveedores ORDER BY id_pago DESC");
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
    <title>Finanzas</title>
    <link rel="stylesheet" href="static/styles/index.css">
    <link rel="stylesheet" href="static/styles/proveedores.css">
    <link rel="stylesheet" href="static/styles/modal.css">
    <link rel="stylesheet" href="static/styles/perfil.css">
    <link rel="stylesheet" href="static/styles/dropdown.css">
    <style>
        table {border-collapse: collapse; width: 100%; margin-bottom: 40px;}
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

<h1>💰 Finanzas</h1>

<h2>📥 Pagos de Clientes</h2>
<form method="post">
    <input type="hidden" name="id_pago" value="<?= $cliente_editar['id_pago'] ?? '' ?>">
    <input type="number" name="id_venta" placeholder="ID Venta" required value="<?= $cliente_editar['id_venta'] ?? '' ?>">
    <input type="number" name="monto" placeholder="Monto" step="0.01" required value="<?= $cliente_editar['monto'] ?? '' ?>">
    <select name="metodo">
        <option value="tarjeta" <?= (isset($cliente_editar) && $cliente_editar['metodo'] == 'tarjeta') ? 'selected' : '' ?>>Tarjeta</option>
        <option value="paypal" <?= (isset($cliente_editar) && $cliente_editar['metodo'] == 'paypal') ? 'selected' : '' ?>>PayPal</option>
        <option value="efectivo" <?= (isset($cliente_editar) && $cliente_editar['metodo'] == 'efectivo') ? 'selected' : '' ?>>Efectivo</option>
    </select>
    <button type="submit" name="<?= $cliente_editar ? 'editar_cliente' : 'agregar_cliente' ?>">
        <?= $cliente_editar ? 'Actualizar Pago Cliente' : 'Agregar Pago Cliente' ?>
    </button>
</form>
<table>
    <thead>
        <tr><th>ID</th><th>ID Venta</th><th>Monto</th><th>Fecha</th><th>Método</th><th>Acciones</th></tr>
    </thead>
    <tbody>
    <?php while ($row = $clientes->fetch_assoc()): ?>
        <tr>
            <td><?= $row['id_pago'] ?></td>
            <td><?= $row['id_venta'] ?></td>
            <td>$<?= number_format($row['monto'], 2) ?></td>
            <td><?= $row['fecha'] ?></td>
            <td><?= ucfirst($row['metodo']) ?></td>
            <td>
                <a href="finanzas.php?editar_cliente=<?= $row['id_pago'] ?>">Editar</a> |
                <a href="finanzas.php?eliminar_cliente=<?= $row['id_pago'] ?>" onclick="return confirm('¿Eliminar este pago de cliente?')">Eliminar</a>
            </td>
        </tr>
    <?php endwhile; ?>
    </tbody>
</table>

<h2>📤 Pagos a Proveedores</h2>
<form method="post">
    <input type="hidden" name="id_pago" value="<?= $proveedor_editar['id_pago'] ?? '' ?>">
    <input type="number" name="id_orden" placeholder="ID Orden" required value="<?= $proveedor_editar['id_orden'] ?? '' ?>">
    <input type="number" name="monto" placeholder="Monto" step="0.01" required value="<?= $proveedor_editar['monto'] ?? '' ?>">
    <select name="metodo">
        <option value="transferencia" <?= (isset($proveedor_editar) && $proveedor_editar['metodo'] == 'transferencia') ? 'selected' : '' ?>>Transferencia</option>
        <option value="efectivo" <?= (isset($proveedor_editar) && $proveedor_editar['metodo'] == 'efectivo') ? 'selected' : '' ?>>Efectivo</option>
        <option value="cheque" <?= (isset($proveedor_editar) && $proveedor_editar['metodo'] == 'cheque') ? 'selected' : '' ?>>Cheque</option>
    </select>
    <button type="submit" name="<?= $proveedor_editar ? 'editar_proveedor' : 'agregar_proveedor' ?>">
        <?= $proveedor_editar ? 'Actualizar Pago Proveedor' : 'Agregar Pago Proveedor' ?>
    </button>
</form>
<table>
    <thead>
        <tr><th>ID</th><th>ID Orden</th><th>Monto</th><th>Fecha</th><th>Método</th><th>Acciones</th></tr>
    </thead>
    <tbody>
    <?php while ($row = $proveedores->fetch_assoc()): ?>
        <tr>
            <td><?= $row['id_pago'] ?></td>
            <td><?= $row['id_orden'] ?></td>
            <td>$<?= number_format($row['monto'], 2) ?></td>
            <td><?= $row['fecha'] ?></td>
            <td><?= ucfirst($row['metodo']) ?></td>
            <td>
                <a href="finanzas.php?editar_proveedor=<?= $row['id_pago'] ?>">Editar</a> |
                <a href="finanzas.php?eliminar_proveedor=<?= $row['id_pago'] ?>" onclick="return confirm('¿Eliminar este pago a proveedor?')">Eliminar</a>
            </td>
        </tr>
    <?php endwhile; ?>
    </tbody>
</table>

<form method="get" action="finanzas_pdf.php" style="margin: 20px 0;">
    <button type="submit">📄 Exportar a PDF</button>
</form>

<?php include 'modal-perfil.php'; ?>
<script src="static/js/modal.js"></script>
<script src="static/js/auth.js"></script>
</body>
</html>
