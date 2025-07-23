<?php
include 'conexion.php';

// Recoger datos con chequeo para evitar undefined index
$nombre = $_POST['nombre'] ?? '';
$correo = $_POST['correo'] ?? '';
$telefono = $_POST['telefono'] ?? '';

// Preparar y ejecutar consulta usando prepared statements para seguridad
$stmt = $conn->prepare("INSERT INTO usuarios_p 
    (nombre, correo, telefono)
    VALUES (?, ?, ?)");

$stmt->bind_param(
    "sss", 
    $nombre, $correo, $telefono
);

if ($stmt->execute()) {
    echo "Gracias por la informacion. <a href='ver_catalogo.php'> Ver Catalogo</a>";
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
