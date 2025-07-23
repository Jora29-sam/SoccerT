<?php
session_start();
include 'conexion.php';

if (!isset($_SESSION['id_usuario'])) {
    http_response_code(401);
    echo "No autenticado";
    exit;
}

$id_usuario = $_SESSION['id_usuario'];
$jersey_id = $_POST['jersey_id'] ?? null;
$talla = $_POST['talla'] ?? null;
$jugador = $_POST['jugador'] ?? null;

if ($jersey_id && $talla && $jugador) {

    // Guarda en sesión como hacías antes (opcional)
    $_SESSION['carrito'][] = [
        'jersey_id' => $jersey_id,
        'talla' => $talla,
        'jugador' => $jugador
    ];

    // Buscar id_talla (porque en la tabla tienes el id, no S/M/L/XL)
    $stmt = $conn->prepare("SELECT id_talla FROM tallas WHERE talla = ?");
    $stmt->bind_param("s", $talla);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($res->num_rows === 0) {
        http_response_code(400);
        echo "Talla inválida";
        exit;
    }

    $id_talla = $res->fetch_assoc()['id_talla'];
    $cantidad = $_POST['cantidad'] ?? 1;

    $sql = "SELECT id_carrito, cantidad FROM carrito 
            WHERE id_usuario = ? AND id_jersey = ? AND id_talla = ? AND jugador_custom = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iiis", $id_usuario, $jersey_id, $id_talla, $jugador);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        $id_carrito = $row['id_carrito'];
        $sql = "UPDATE carrito SET cantidad = ? WHERE id_carrito = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $nueva_cantidad, $id_carrito);
        $stmt->execute();

    } else {
        $sql = "INSERT INTO carrito (id_usuario, id_jersey, id_talla, jugador_custom, cantidad) 
                VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iiisi", $id_usuario, $jersey_id, $id_talla, $jugador, $cantidad);
        $stmt->execute();
        echo "Registro insertado con éxito.";
    }

} else {
    http_response_code(400);
    echo "Datos incompletos";
}
?>
