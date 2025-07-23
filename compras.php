<?php
include 'auth.php';


if (!isset($_SESSION["rol"]) || $_SESSION["rol"] !== "admin") {
    header("Location: index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (isset($_POST['accion']) && $_POST['accion'] === 'borrar') {
        $id_orden = (int)$_POST['id_orden'];

        $stmt = $conn->prepare("DELETE FROM orden_detalle WHERE id_orden = ?");
        $stmt->bind_param("i", $id_orden);
        $stmt->execute();
        $stmt->close();

        $stmt = $conn->prepare("DELETE FROM pagos_proveedores WHERE id_orden = ?");
        $stmt->bind_param("i", $id_orden);
        $stmt->execute();
        $stmt->close();

        $stmt = $conn->prepare("DELETE FROM ordenes_compra WHERE id_orden = ?");
        $stmt->bind_param("i", $id_orden);
        $stmt->execute();
        $stmt->close();

        $_SESSION['mensaje_exito'] = "🗑️ Orden eliminada correctamente.";
        header("Location: compras.php");
        exit();

    } elseif (isset($_POST['accion']) && $_POST['accion'] === 'editar') {
        $id_orden = (int)$_POST['id_orden'];
        $cantidades = $_POST['cantidades'] ?? [];

        foreach ($cantidades as $key => $cantidad) {
            list($id_jersey, $id_talla) = explode('_', $key);
            $id_jersey = (int)$id_jersey;
            $id_talla = (int)$id_talla;
            $cantidad = (int)$cantidad;

            $stmt = $conn->prepare("
                UPDATE orden_detalle
                SET cantidad = ?
                WHERE id_orden = ? AND id_jersey = ? AND id_talla = ?
            ");
            $stmt->bind_param("iiii", $cantidad, $id_orden, $id_jersey, $id_talla);
            $stmt->execute();
            $stmt->close();
        }

        $_SESSION['mensaje_exito'] = "✏️ Orden actualizada correctamente.";
        header("Location: compras.php");
        exit();

    } else {
        $cantidades = $_POST['cantidad'] ?? [];
        $proveedores = $_POST['proveedor'] ?? [];

        $ordenesPorProveedor = [];

        foreach ($cantidades as $key => $cantidad) {
            $cantidad = (int)$cantidad;
            if ($cantidad <= 0) continue;

            list($id_jersey, $id_talla) = explode('_', $key);
            $id_jersey = (int)$id_jersey;
            $id_talla = (int)$id_talla;

            $id_proveedor = isset($proveedores[$key]) ? (int)$proveedores[$key] : 0;
            if ($id_proveedor <= 0) continue;

            if (!isset($ordenesPorProveedor[$id_proveedor])) {
                $ordenesPorProveedor[$id_proveedor] = [];
            }

            $ordenesPorProveedor[$id_proveedor][] = [
                'id_jersey' => $id_jersey,
                'id_talla' => $id_talla,
                'cantidad' => $cantidad,
            ];
        }

        foreach ($ordenesPorProveedor as $id_proveedor => $items) {
            $stmt = $conn->prepare("INSERT INTO ordenes_compra (fecha, id_proveedor, estado) VALUES (NOW(), ?, 'pendiente')");
            $stmt->bind_param("i", $id_proveedor);
            $stmt->execute();
            $id_orden = $stmt->insert_id;
            $stmt->close();

            $total_orden = 0.0;

            foreach ($items as $item) {
                $id_jersey = $item['id_jersey'];
                $id_talla  = $item['id_talla'];
                $cantidad  = $item['cantidad'];

                $stmt = $conn->prepare("INSERT INTO orden_detalle (id_orden, id_jersey, id_talla, cantidad) VALUES (?, ?, ?, ?)");
                $stmt->bind_param("iiii", $id_orden, $id_jersey, $id_talla, $cantidad);
                $stmt->execute();
                $stmt->close();

                $stmt = $conn->prepare("SELECT precio FROM jerseys WHERE id_jersey = ?");
                $stmt->bind_param("i", $id_jersey);
                $stmt->execute();
                $stmt->bind_result($precio_unitario);
                $stmt->fetch();
                $stmt->close();

                $total_orden += $precio_unitario * $cantidad;
            }

            $metodo_pago = 'visa';
            $stmt = $conn->prepare("INSERT INTO pagos_proveedores (id_orden, monto, metodo) VALUES (?, ?, ?)");
            $stmt->bind_param("ids", $id_orden, $total_orden, $metodo_pago);
            $stmt->execute();
            $stmt->close();
        }

        $_SESSION['mensaje_exito'] = "✅ Órdenes de compra y pagos con VISA registrados correctamente.";
        header("Location: compras.php");
        exit();
    }
}

// Obtener proveedores
$proveedores = $conn->query("SELECT id_proveedor, nombre FROM proveedores");

// Obtener jerseys con stock bajo
$sql = "
SELECT jt.id_jersey, jt.id_talla, jt.stock, j.nombre AS jersey_nombre, t.talla AS talla_nombre
FROM jersey_tallas jt
JOIN jerseys j ON jt.id_jersey = j.id_jersey
JOIN tallas t ON jt.id_talla = t.id_talla
WHERE jt.stock <= 5
ORDER BY jt.stock ASC
";
$result = $conn->query($sql);

// Obtener órdenes existentes
$ordenes = $conn->query("
SELECT oc.id_orden, oc.fecha, oc.estado, p.nombre AS proveedor
FROM ordenes_compra oc
JOIN proveedores p ON oc.id_proveedor = p.id_proveedor
ORDER BY oc.fecha DESC
");
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Soccer Modules</title>
    <link rel="stylesheet" href="static/styles/index.css">
    <link rel="stylesheet" href="static/styles/modal.css">
    <link rel="stylesheet" href="static/styles/perfil.css">
    <link rel="stylesheet" href="static/styles/dropdown.css">
    <link rel="stylesheet" href="static/styles/compras.css">
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

    <h1>📦 Crear Orden de Compra</h1>

    <?php
    if (isset($_SESSION['mensaje_exito'])) {
        echo '<div class="notificacion-exito">' . htmlspecialchars($_SESSION['mensaje_exito']) . '</div>';
        unset($_SESSION['mensaje_exito']);
    }
    ?>

    <form method="post">
        <table border="1" cellpadding="5" cellspacing="0">
            <thead>
                <tr>
                    <th>ID Jersey</th>
                    <th>Jersey</th>
                    <th>Talla</th>
                    <th>Stock actual</th>
                    <th>Proveedor</th>
                    <th>Cantidad a pedir</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()) : ?>
                <tr>
                    <td><?= htmlspecialchars($row['id_jersey']) ?></td>
                    <td><?= htmlspecialchars($row['jersey_nombre']) ?></td>
                    <td><?= htmlspecialchars($row['talla_nombre']) ?></td>
                    <td><?= (int) $row['stock'] ?></td>
                    <td>
                        <select name="proveedor[<?= $row['id_jersey'] . '_' . $row['id_talla'] ?>]" required>
                            <option value="">Selecciona</option>
                            <?php
                            mysqli_data_seek($proveedores, 0);
                            while ($prov = $proveedores->fetch_assoc()) :
                            ?>
                            <option value="<?= htmlspecialchars($prov['id_proveedor']) ?>">
                                <?= htmlspecialchars($prov['nombre']) ?>
                            </option>
                            <?php endwhile; ?>
                        </select>
                    </td>
                    <td>
                        <input type="number" name="cantidad[<?= $row['id_jersey'] . '_' . $row['id_talla'] ?>]" min="0" value="0" style="width:60px;">
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <button type="submit">💳 Pagar con VISA</button>
    </form>

    <hr>
    <h2>📄 Órdenes Realizadas</h2>

    <?php if ($ordenes->num_rows == 0): ?>
        <p>No hay órdenes de compra registradas.</p>
    <?php else: ?>
        <?php while ($orden = $ordenes->fetch_assoc()): ?>
            <h3>📝 Orden #<?= $orden['id_orden'] ?> | <?= htmlspecialchars($orden['proveedor']) ?> | <?= htmlspecialchars($orden['estado']) ?> | <?= $orden['fecha'] ?></h3>

            <?php
            $stmt = $conn->prepare("
                SELECT od.id_orden, od.id_jersey, od.id_talla, j.nombre AS jersey, t.talla, od.cantidad
                FROM orden_detalle od
                JOIN jerseys j ON od.id_jersey = j.id_jersey
                JOIN tallas t ON od.id_talla = t.id_talla
                WHERE od.id_orden = ?
            ");
            $stmt->bind_param("i", $orden['id_orden']);
            $stmt->execute();
            $detalle = $stmt->get_result();
            ?>

            <form method="post" onsubmit="return confirm('¿Seguro que quieres realizar esta acción?');">
                <input type="hidden" name="id_orden" value="<?= $orden['id_orden'] ?>">
                <table class="detalle" border="1" cellpadding="5" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Jersey</th>
                            <th>Talla</th>
                            <th>Cantidad</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $detalle->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['jersey']) ?></td>
                            <td><?= htmlspecialchars($row['talla']) ?></td>
                            <td>
                                <input type="number" name="cantidades[<?= $row['id_jersey'].'_'.$row['id_talla'] ?>]" value="<?= (int)$row['cantidad'] ?>" min="1" required style="width:60px;">
                            </td>
                            <td>
                                <div class="acciones">
                                    <button type="submit" name="accion" value="editar">✏️ Editar</button>
                                    <button type="submit" name="accion" value="borrar" onclick="return confirm('¿Estás seguro de borrar esta orden?');">🗑️ Borrar</button>
                                </div>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </form>

            <?php $stmt->close(); ?>
        <?php endwhile; ?>
    <?php endif; ?>

    <?php include 'modal-perfil.php'; ?>
    <script src="static/js/modal.js"></script>
    <script src="static/js/auth.js"></script>
</body>
</html>
