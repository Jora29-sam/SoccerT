<?php
require_once('tcpdf/tcpdf.php');
require 'vendor/autoload.php';


include 'conexion.php';

// Datos del lead
$correo_destino = trim($_POST['correo'] ?? '');
$nombre = trim($_POST['nombre'] ?? '');
$telefono = trim($_POST['telefono'] ?? '');

// Registra al lead en la BD
$stmt = $conn->prepare("INSERT INTO usuarios_p (nombre, correo, telefono) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $nombre, $correo_destino, $telefono);
$stmt->execute();
$stmt->close();

// Ahora generamos el PDF
$pdf = new TCPDF();
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Soccer Threats');
$pdf->SetTitle('Catálogo de Jerseys');
$pdf->SetSubject('Catálogo');

// Sin header/footer
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);

$pdf->AddPage();
$pdf->SetFont('helvetica', '', 12);

// Consulta de los jerseys
$sql = "SELECT j.nombre, j.precio, j.imagen_url, e.nombre AS equipo
        FROM jerseys j
        JOIN equipos e ON j.id_equipo = e.id_equipo";
$result = $conn->query($sql);

$html = '<h1>Catálogo de Jerseys</h1>';
while ($row = $result->fetch_assoc()) {
    $html .= '<h3>' . htmlspecialchars($row['nombre']) . ' - ' . htmlspecialchars($row['equipo']) . '</h3>';
    $html .= '<p>Precio: $' . number_format($row['precio'], 2) . '</p>';
    $html .= '<img src="' . $row['imagen_url'] . '" height="100"><hr>';
}

$pdf->writeHTML($html, true, false, true, false, '');

// Guarda el PDF en el servidor (temporalmente)
$file = tempnam(sys_get_temp_dir(), 'cat_') . '.pdf';
$pdf->Output($file, 'F');

// Envía por correo
$asunto = "Tu catálogo de jerseys";
$mensaje = "Hola $nombre,\n\nAdjunto encontrarás el catálogo de nuestros jerseys. ¡Gracias por tu interés!";
$headers = "From: rascuache26@gmail.com";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$mail = new PHPMailer();
$mail->isSMTP();
$mail->Host = 'smtp.gmail.com';
$mail->SMTPAuth = true;
$mail->Username = 'rascuache26@gmail.com';
$mail->Password = 'fjfx ijan mocj kzqa'; // sin espacios extra
$mail->SMTPSecure = 'ssl';
$mail->Port = 465;

$mail->setFrom('rascuache26@gmail.com', 'SoccerThreats');
$mail->addAddress($correo_destino, $nombre);

$mail->Subject = $asunto;
$mail->Body = $mensaje;
$mail->addAttachment($file, 'catalogo.pdf');


if ($mail->send()) {
    echo "¡Gracias! Te hemos enviado el catálogo por correo 📧";
} else {
    echo "No se pudo enviar el correo 😔. Intenta más tarde.";
}

// Borra el archivo temporal
unlink($file);
