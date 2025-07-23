<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include 'conexion.php';

$error = "";
$exito = "";
$usuario = null;

// Cargar datos usuario si está logueado
if (isset($_SESSION["id_usuario"])) {
    $id_usuario = $_SESSION["id_usuario"];
    $sql = "SELECT nombre, correo, fecha_registro, rol FROM usuarios WHERE id_usuario = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id_usuario);
    $stmt->execute();
    $result = $stmt->get_result();
    $usuario = $result->fetch_assoc();
}


// Procesar formulario login o registro
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['accion'])) {

    if ($_POST['accion'] === 'login') {
        $correo = $_POST["correo"];
        $contrasena = $_POST["contrasena"];

        $sql = "SELECT * FROM usuarios WHERE correo = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $correo);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($usuario_login = $result->fetch_assoc()) {
            if (password_verify($contrasena, $usuario_login["contrasena"])) {
                $_SESSION["id_usuario"] = $usuario_login["id_usuario"];
                $_SESSION["usuario"] = $usuario_login["nombre"];
                $_SESSION["rol"] = $usuario_login["rol"];

                if ($usuario_login["rol"] === "admin") {
                    header("Location: dashboard.php");
                } else {
                    header("Location: index.php");
                }
                exit();
            } else {
                $error = "Contraseña incorrecta.";
            }
        } else {
            $error = "Usuario no encontrado.";
        }

    } elseif ($_POST['accion'] === 'registro') {
        $nombre = trim($_POST["nombre"]);
        $correo = trim($_POST["correo"]);
        $contrasena = $_POST["contrasena"];

        $sql = "SELECT id_usuario FROM usuarios WHERE correo = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $correo);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $error = "Ya existe una cuenta con ese correo.";
        } else {
            $hash = password_hash($contrasena, PASSWORD_DEFAULT);
            $sql = "INSERT INTO usuarios (nombre, correo, contrasena) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sss", $nombre, $correo, $hash);

            if ($stmt->execute()) {
                $exito = "Cuenta creada exitosamente. ¡Ahora puedes iniciar sesión!";
            } else {
                $error = "Error al registrar: " . $stmt->error;
            }
        }
    }
}
?>
