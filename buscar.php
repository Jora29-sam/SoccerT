<?php
include 'auth.php';

$q = $_GET['q'] ?? '';
$q = trim($q);

if ($q === '') {
    echo "Por favor escribe un término para buscar.";
    exit;
}

$q_like = '%' . $q . '%';

// 🟢 1. Intentar jugador + equipo juntos
$sql_jugador_equipo = "
SELECT j.nombre AS jugador, e.nombre AS equipo
FROM jugadores j
JOIN equipos e ON j.id_equipo = e.id_equipo
WHERE CONCAT(j.nombre, ' ', e.nombre) LIKE ?
LIMIT 1";
$stmt = $conn->prepare($sql_jugador_equipo);
$stmt->bind_param("s", $q_like);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    header("Location: jerseys.php?nombre=" . urlencode($row['equipo']) . "&jugador=" . urlencode($row['jugador']));
    exit();
}
$stmt->close();

// 🟢 2. Intentar solo por jugador
$sql_jugador = "
SELECT j.nombre AS jugador, e.nombre AS equipo
FROM jugadores j
JOIN equipos e ON j.id_equipo = e.id_equipo
WHERE j.nombre LIKE ?
LIMIT 1";
$stmt = $conn->prepare($sql_jugador);
$stmt->bind_param("s", $q_like);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    header("Location: jerseys.php?nombre=" . urlencode($row['equipo']) . "&jugador=" . urlencode($row['jugador']));
    exit();
}
$stmt->close();

// 🟢 3. Intentar por equipo
$sql_equipo = "SELECT nombre FROM equipos WHERE nombre LIKE ? LIMIT 1";
$stmt = $conn->prepare($sql_equipo);
$stmt->bind_param("s", $q_like);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    header("Location: jerseys.php?nombre=" . urlencode($row['nombre']));
    exit();
}
$stmt->close();

// 🟢 4. Intentar por jersey
$sql_jersey = "
SELECT e.nombre AS equipo
FROM jerseys j
JOIN equipos e ON j.id_equipo = e.id_equipo
WHERE j.nombre LIKE ?
LIMIT 1";
$stmt = $conn->prepare($sql_jersey);
$stmt->bind_param("s", $q_like);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    header("Location: jerseys.php?nombre=" . urlencode($row['equipo']));
    exit();
}
$stmt->close();

// 🚩 Nada encontrado
echo "No se encontraron resultados para '<strong>" . htmlspecialchars($q) . "</strong>'.";
?>
