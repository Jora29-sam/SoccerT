<?php
require_once('tcpdf/tcpdf.php');
include 'auth.php';
include 'conexion.php';

$pdf = new TCPDF();
$pdf->AddPage();
$pdf->SetFont('helvetica', '', 12);
$pdf->Write(0, "Reporte de Logística (Envíos)\n", '', 0, 'C', true, 0, false, false, 0);

$result = $conn->query("SELECT * FROM envios ORDER BY id_envio DESC");

$html = '<table border="1" cellpadding="5">
<tr><th>ID</th><th>ID Venta</th><th>Fecha</th><th>Transportista</th><th>Guía</th><th>Estado</th></tr>';

while ($row = $result->fetch_assoc()) {
    $html .= '<tr>
        <td>'.$row['id_envio'].'</td>
        <td>'.$row['id_venta'].'</td>
        <td>'.$row['fecha_envio'].'</td>
        <td>'.$row['transportista'].'</td>
        <td>'.$row['numero_guia'].'</td>
        <td>'.$row['estado'].'</td>
    </tr>';
}

$html .= '</table>';
$pdf->writeHTML($html, true, false, false, false, '');
$pdf->Output('reporte_logistica.pdf', 'I');
