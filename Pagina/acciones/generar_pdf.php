<?php
require('fpdf/fpdf.php');

// Obtener los parámetros de la URL
$tipo = $_GET['tipo'];
$id = $_GET['id'];
$nombre_profesor = urldecode($_GET['nombre_profesor']);
$nombre_grupo = urldecode($_GET['nombre_grupo']);

// Crear una instancia de FPDF
$pdf = new FPDF();
$pdf->AddPage();

// Agregar el logo de la empresa en la parte superior izquierda
$pdf->Image('Logo.png', 10, 10, 30); // X, Y, Width
$pdf->SetFont('Arial', 'B', 30);
$pdf->SetTextColor(10, 108, 147 ); // Color de texto 
$pdf->Cell(40); // Espacio para el logo
$pdf->Cell(0, 20, 'MINIMINDS', 0, 3, 'L');
$pdf->Ln(20);

// Configuración del título del PDF
$pdf->SetFont('Arial', 'B', 14);
$pdf->SetTextColor(94, 91, 99); // Color de texto 
$pdf->Cell(0, 10, utf8_decode("Profesor: $nombre_profesor"), 0, 1, 'C');
$pdf->Cell(0, 10, utf8_decode("Grupo: $nombre_grupo"), 0, 1, 'C');
$pdf->Ln(10);

// Configuración de la tabla
$pdf->SetFillColor(200, 220, 255); // Color de fondo para las celdas
$pdf->SetFont('Arial', 'B', 11);
$pdf->SetTextColor(0, 0, 0); // Color de texto 
$pdf->Cell(8, 10, 'No', 1, 0, 'C', true);
$pdf->Cell(50, 10, 'Nombre de Tarea', 1, 0, 'C', true);
$pdf->Cell(95, 10, 'Descripcion', 1, 0, 'C', true);
$pdf->Cell(35, 10, 'Fecha Expiracion', 1, 1, 'C', true);

// Conectar a la base de datos y obtener los datos
include('../../Logeo/conexion.php');
if ($tipo == 'grupo') {
    $consulta = "SELECT * FROM tasks WHERE class_id = ?";
    $stmt = $conexion->prepare($consulta);
    $stmt->bind_param("i", $id);
} else {
    // Aquí puedes agregar más lógica si necesitas manejar otros tipos
}

$stmt->execute();
$result = $stmt->get_result();

$pdf->SetFont('Arial', '', 9);
$pdf->SetFillColor(255, 255, 255); // Color de fondo para las celdas de datos
if ($result->num_rows > 0) {
    $contador = 1;
    while ($row = $result->fetch_assoc()) {
        $pdf->Cell(8, 10, $contador, 1, 0, 'C', true);
        $pdf->Cell(50, 10, utf8_decode($row['task_name']), 1, 0, 'C', true);
        
        // Usar MultiCell para manejar desbordamientos de texto
        $x = $pdf->GetX();
        $y = $pdf->GetY();
        $pdf->MultiCell(95, 10, utf8_decode($row['descripcion']), 1, 'C', true);
        $pdf->SetXY($x + 95, $y);
        
        $pdf->Cell(35, 10, utf8_decode($row['fecha_expiracion']), 1, 1, 'C', true);
        $contador++;
    }
} else {
    $pdf->Cell(0, 10, 'No hay tareas asignadas a este grupo.', 1, 1, 'C', true);
}

$stmt->close();
$conexion->close();

// Agregar la fecha y hora actual en la parte inferior derecha
$pdf->SetY(15);
$pdf->SetFont('Arial', 'I', 8);
// Definir la zona horaria
$zonaHoraria = new DateTimeZone('America/Mexico_City');

// Crear un objeto DateTime con la fecha y hora actual en la zona horaria especificada
$fechaHora = new DateTime('now', $zonaHoraria);

$fechaHora->modify('-1 hour');

// Formatear la fecha y hora según el formato deseado
$fechaHoraFormateada = $fechaHora->format('d-m-Y H:i:s');

// Mostrar la fecha y hora en el PDF
$pdf->Cell(0, 10, 'Fecha y hora: ' . $fechaHoraFormateada, 0, 0, 'R');

// Salida del PDF
$pdf->Output();
?>
