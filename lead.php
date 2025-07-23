<?php
include 'conexion.php'; 

if (!empty($_POST['nombre']) && !empty($_POST['correo']) && !empty($_POST['telefono'])) {
    $nombre = trim($_POST['nombre']);
    $correo = trim($_POST['correo']);
    $telefono = trim($_POST['telefono']);

    $stmt = $conn->prepare("INSERT INTO clientes_p (nombre, correo, telefono) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $nombre, $correo, $telefono);

    if ($stmt->execute()) {
        echo "¡Gracias por registrarte! Revisa tu correo para el catálogo 📧";
    } else {
        echo "Ocurrió un error. Intenta de nuevo.";
    }

    $stmt->close();
} else {
    echo "Por favor completa todos los campos.";
}
