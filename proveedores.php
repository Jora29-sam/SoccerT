<?php
include 'auth.php';

if (!isset($_SESSION["rol"]) || $_SESSION["rol"] !== "admin") {
    header("Location: index.php");
    exit();
}

include 'auth.php';

// INSERTAR proveedor
if (isset($_POST['agregar'])) {
    $stmt = $conn->prepare("INSERT INTO proveedores (nombre, contacto, telefono, correo, direccion) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $_POST['nombre'], $_POST['contacto'], $_POST['telefono'], $_POST['correo'], $_POST['direccion']);
    $stmt->execute();
    header("Location: proveedores.php");
    exit();
}

// ELIMINAR proveedor
if (isset($_GET['eliminar'])) {
    $id = intval($_GET['eliminar']);
    $conn->query("DELETE FROM proveedores WHERE id_proveedor = $id");
    header("Location: proveedores.php");
    exit();
}

// ACTUALIZAR proveedor
if (isset($_POST['editar'])) {
    $stmt = $conn->prepare("UPDATE proveedores SET nombre=?, contacto=?, telefono=?, correo=?, direccion=? WHERE id_proveedor=?");
    $stmt->bind_param("sssssi", $_POST['nombre'], $_POST['contacto'], $_POST['telefono'], $_POST['correo'], $_POST['direccion'], $_POST['id_proveedor']);
    $stmt->execute();
    header("Location: proveedores.php");
    exit();
}

// SI SE ESTÁ EDITANDO, obtenemos los datos del proveedor
$proveedor_editar = null;
if (isset($_GET['editar'])) {
    $id = intval($_GET['editar']);
    $res = $conn->query("SELECT * FROM proveedores WHERE id_proveedor = $id");
    $proveedor_editar = $res->fetch_assoc();
}

// OBTENER TODOS LOS PROVEEDORES
$result = $conn->query("SELECT * FROM proveedores ORDER BY id_proveedor DESC");
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Proveedores</title>
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
                    <li><a href="inventario.php">Inventario</a></li>
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

<!-- FORMULARIO -->
<form method="post">
    <input type="hidden" name="id_proveedor" value="<?= $proveedor_editar['id_proveedor'] ?? '' ?>">
    <input type="text" name="nombre" placeholder="Nombre" required value="<?= $proveedor_editar['nombre'] ?? '' ?>">
    <input type="text" name="contacto" placeholder="Contacto" required value="<?= $proveedor_editar['contacto'] ?? '' ?>">
    <input type="text" name="telefono" placeholder="Teléfono" required value="<?= $proveedor_editar['telefono'] ?? '' ?>">
    <input type="email" name="correo" placeholder="Correo" required value="<?= $proveedor_editar['correo'] ?? '' ?>">
    <input type="text" name="direccion" placeholder="Dirección" required value="<?= $proveedor_editar['direccion'] ?? '' ?>">

    <?php if ($proveedor_editar): ?>
        <button type="submit" name="editar">Actualizar</button>
    <?php else: ?>
        <button type="submit" name="agregar">Agregar</button>
    <?php endif; ?>
</form>

<!-- LISTA DE PROVEEDORES -->
<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Contacto</th>
            <th>Teléfono</th>
            <th>Correo</th>
            <th>Dirección</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
    <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?= $row['id_proveedor'] ?></td>
            <td><?= $row['nombre'] ?></td>
            <td><?= $row['contacto'] ?></td>
            <td><?= $row['telefono'] ?></td>
            <td><?= $row['correo'] ?></td>
            <td><?= $row['direccion'] ?></td>
            <td>
            <div class="acciones">
                <form method="get" style="display:inline;">
                    <input type="hidden" name="editar" value="<?= $row['id_proveedor'] ?>">
                    <button type="submit">✏️ Editar</button>
                </form>
                <form method="get" style="display:inline;" onsubmit="return confirm('¿Estás seguro?');">
                    <input type="hidden" name="eliminar" value="<?= $row['id_proveedor'] ?>">
                    <button type="submit">🗑️ Borrar</button>
                </form>
            </div>
            </td>
         
        </tr>
    <?php endwhile; ?>
    </tbody>
</table>
    <?php include 'modal-perfil.php'; ?>

    <script src="static/js/modal.js"></script>
    <script src="static/js/auth.js"></script>
</body>
</html>
