<?php
//Database connection

$host = "localhost";
$user = "root";
$password = "";
$database = "soccer";

// Creates connection
$conn = new mysqli($host, $user, $password, $database);

// Verify connection
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}
?>