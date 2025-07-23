<?php
require_once('tcpdf/tcpdf.php');
include 'auth.php';
include 'conexion.php';

$pdf = new TCPDF();
$pdf->AddPage();
$pdf->SetFont('helvetica', '', 12);
$pdf->Write(0, "Reporte de Finanzas\n", '', 0, 'C', true, 0, false, false, 0);

// PAGOS CLIENTES
$pdf->Ln(5);
$pdf->Write(0, "Pagos de Clientes:\n", '', 0, 'L', true, 0, false, false, 0);

$clientes = $conn->query("SELECT * FROM pagos_clientes ORDER BY id_pago DESC");

$html = '<table border="1" cellpadding="5">
<tr><th>ID</th><th>ID Venta</th><th>Monto</th><th>Fecha</th><th>Método</th></tr>';

while ($row = $clientes->fetch_assoc()) {
    $html .= '<tr>
        <td>'.$row['id_pago'].'</td>
        <td>'.$row['id_venta'].'</td>
        <td>$'.$row['monto'].'</td>
        <td>'.$row['fecha'].'</td>
        <td>'.$row['metodo'].'</td>
    </tr>';
}
$html .= '</table>';
$pdf->writeHTML($html, true, false, false, false, '');

// PAGOS PROVEEDORES
$pdf->Ln(5);
$pdf->Write(0, "Pagos a Proveedores:\n", '', 0, 'L', true, 0, false, false, 0);

$proveedores = $conn->query("SELECT * FROM pagos_proveedores ORDER BY id_pago DESC");

$html = '<table border="1" cellpadding="5">
<tr><th>ID</th><th>ID Orden</th><th>Monto</th><th>Fecha</th><th>Método</th></tr>';

while ($row = $proveedores->fetch_assoc()) {
    $html .= '<tr>
        <td>'.$row['id_pago'].'</td>
        <td>'.$row['id_orden'].'</td>
        <td>$'.$row['monto'].'</td>
        <td>'.$row['fecha'].'</td>
        <td>'.$row['metodo'].'</td>
    </tr>';
}
$html .= '</table>';
$pdf->writeHTML($html, true, false, false, false, '');

$pdf->Output('reporte_finanzas.pdf', 'I');
