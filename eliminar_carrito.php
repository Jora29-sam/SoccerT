<?php
session_start();
include 'conexion.php';

if (!isset($_SESSION['id_usuario'])) {
    header("Location: login.php");
    exit;
}

$id_carrito = $_POST['id_carrito'] ?? null;

if ($id_carrito) {
    $sql = "DELETE FROM carrito WHERE id_carrito = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id_carrito);
    $stmt->execute();
}

header("Location: resumen.php");
exit;
