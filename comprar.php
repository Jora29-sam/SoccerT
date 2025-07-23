<?php
include 'auth.php';

if (!isset($_SESSION['id_usuario'])) {
    header('Content-Type: application/json');
    echo json_encode(['ok' => false, 'error' => 'No autenticado']);
    exit;
}


ini_set('display_errors', 1);
error_reporting(E_ALL);
header('Content-Type: application/json');

$id_usuario = $_SESSION['id_usuario'];

$stmt = $conn->prepare("INSERT INTO ventas (id_usuario, fecha, estado) VALUES (?, NOW(), 'pagada')");
$stmt->bind_param("i", $id_usuario);
$stmt->execute();
$id_venta = $stmt->insert_id;
$stmt->close();

$total_venta = 0.0;

$sql = "
SELECT c.id_carrito, c.id_jersey, c.id_talla, c.jugador_custom, c.cantidad, j.precio
FROM carrito c
JOIN jerseys j ON c.id_jersey = j.id_jersey
WHERE c.id_usuario = ?
";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_usuario);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    $id_jersey = $row['id_jersey'];
    $id_talla  = $row['id_talla'];
    $cantidad  = $row['cantidad'];
    $precio    = $row['precio'];

    $stmt2 = $conn->prepare("INSERT INTO venta_detalle (id_venta, id_jersey, id_talla, cantidad, precio_unitario) VALUES (?, ?, ?, ?, ?)");
    $stmt2->bind_param("iiiid", $id_venta, $id_jersey, $id_talla, $cantidad, $precio);
    $stmt2->execute();
    $stmt2->close();

    $stmt2 = $conn->prepare("UPDATE jersey_tallas SET stock = stock - ? WHERE id_jersey = ? AND id_talla = ?");
    $stmt2->bind_param("iii", $cantidad, $id_jersey, $id_talla);
    $stmt2->execute();
    $stmt2->close();

    $total_venta += $precio * $cantidad;
}

$metodo_pago = 'tarjeta';
$stmt = $conn->prepare("INSERT INTO pagos_clientes (id_venta, monto, metodo) VALUES (?, ?, ?)");
$stmt->bind_param("ids", $id_venta, $total_venta, $metodo_pago);
$stmt->execute();
$stmt->close();

$stmt = $conn->prepare("INSERT INTO envios (id_venta, estado) VALUES (?, 'pendiente')");
$stmt->bind_param("i", $id_venta);
$stmt->execute();
$stmt->close();

$conn->query("DELETE FROM carrito WHERE id_usuario = $id_usuario");

echo json_encode(['ok' => true, 'id_venta' => $id_venta]);
exit;
?>
