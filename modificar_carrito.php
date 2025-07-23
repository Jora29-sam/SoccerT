<?php
session_start();
include 'conexion.php';

if (!isset($_SESSION['id_usuario'])) {
    header("Location: login.php");
    exit;
}

$id_carrito = $_POST['id_carrito'] ?? null;
$cantidad = $_POST['cantidad'] ?? null;

if ($id_carrito && $cantidad && $cantidad > 0) {
    $sql = "UPDATE carrito SET cantidad = ? WHERE id_carrito = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $cantidad, $id_carrito);
    $stmt->execute();

    header("Location: resumen.php");
    exit;
} else {
    echo "Datos inválidos";
}
?>
