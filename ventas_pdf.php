<?php
require_once('tcpdf/tcpdf.php');
include 'conexion.php';

// Crear PDF
$pdf = new TCPDF();
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Sistema ERP');
$pdf->SetTitle('Reporte de Ventas');
$pdf->SetHeaderData('', 0, 'Reporte de Ventas', date('d-m-Y')); // <-- CORREGIDO
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', 12));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', 10));
$pdf->SetMargins(15, 20, 15);
$pdf->SetAutoPageBreak(TRUE, 15);
$pdf->AddPage();

// Título
$pdf->SetFont('helvetica', 'B', 14);
$pdf->Cell(0, 10, 'Listado de Ventas', 0, 1, 'C');
$pdf->Ln(5);

// Contenido de la tabla
$pdf->SetFont('helvetica', '', 10);

$html = '<table border="1" cellpadding="5">
<thead>
<tr style="background-color:#f2f2f2;">
    <th>ID</th>
    <th>ID Usuario</th>
    <th>Fecha</th>
    <th>Estado</th>
</tr>
</thead>
<tbody>';

// Obtener datos de la BD
$result = $conn->query("SELECT * FROM ventas ORDER BY id_venta DESC");
while ($row = $result->fetch_assoc()) {
    $html .= '<tr>
        <td>'.$row['id_venta'].'</td>
        <td>'.$row['id_usuario'].'</td>
        <td>'.$row['fecha'].'</td>
        <td>'.$row['estado'].'</td>
    </tr>';
}
$html .= '</tbody></table>';

$pdf->writeHTML($html, true, false, true, false, '');
$pdf->Output('reporte_ventas.pdf', 'I');
