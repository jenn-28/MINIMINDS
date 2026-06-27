<?php
require('fpdf/fpdf.php');

// Obtener los parámetros de la URL
$tipo = $_GET['tipo'];
$id = $_GET['id'];
$nombre_profesor = urldecode($_GET['nombre_profesor']);
$nombre_tarea = urldecode($_GET['nombre_tarea']);
$nombre_grupo = urldecode($_GET['nombre_grupo']);

// Crear una instancia de FPDF
$pdf = new FPDF();
$pdf->AddPage();

// Agregar el logo de la empresa en la parte superior izquierda
$pdf->Image('Logo.png', 10, 10, 30); // X, Y, Width
$pdf->SetFont('Arial', 'B', 30);
$pdf->SetTextColor(10, 108, 147); // Color de texto 
$pdf->Cell(40); // Espacio para el logo
$pdf->Cell(0, 20, 'MINIMINDS', 0, 3, 'L');
$pdf->Ln(20);

// Configuración del título del PDF
$pdf->SetFont('Arial', 'B', 14);
$pdf->SetTextColor(94, 91, 99); // Color de texto 
$pdf->Cell(0, 10, utf8_decode("Profesor: $nombre_profesor"), 0, 1, 'C');
$pdf->Cell(0, 10, utf8_decode("Tarea: $nombre_tarea"), 0, 1, 'C');
$pdf->Cell(0, 10, utf8_decode("Grupo: $nombre_grupo"), 0, 1, 'C');
$pdf->Ln(10);

// Configuración de la tabla
$pdf->SetFillColor(200, 220, 255); // Color de fondo para las celdas
$pdf->SetFont('Arial', 'B', 11);
$pdf->SetTextColor(0, 0, 0); // Color de texto 
$pdf->Cell(45, 10, 'Nombre Alumno', 1, 0, 'C', true);
$pdf->Cell(35, 10, 'Completada', 1, 0, 'C', true);
$pdf->Cell(25, 10, utf8_decode('Duración'), 1, 0, 'C', true); // Línea corregida
$pdf->Cell(25, 10, 'Puntaje', 1, 0, 'C', true);
$pdf->Cell(60, 10, 'Detalles', 1, 1, 'C', true);

// Conectar a la base de datos y obtener los datos
include('../../Logeo/conexion.php');
$consulta = "SELECT s.nombre_completo, si.start_time, si.end_time, si.duration, si.puntaje, si.detalles 
             FROM student_interactions si 
             JOIN students s ON si.student_id = s.id 
             WHERE si.task_id = ?";
$stmt = $conexion->prepare($consulta);
$stmt->bind_param("i", $id);

$stmt->execute();
$result = $stmt->get_result();

// Establecer la fuente a Arial
$pdf->SetFont('Arial', '', 9);

// Establecer el color de fondo para las celdas de datos
$pdf->SetFillColor(255, 255, 255);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // Imprimir nombre completo
        $pdf->Cell(45, 10, utf8_decode($row['nombre_completo']), 1, 0, 'C', true);

        // Imprimir end_time
        $pdf->Cell(35, 10, utf8_decode($row['end_time']), 1, 0, 'C', true);

        // Imprimir duration
        $pdf->Cell(25, 10, utf8_decode($row['duration']), 1, 0, 'C', true);

        // Imprimir puntaje
        $pdf->Cell(25, 10, utf8_decode($row['puntaje']), 1, 0, 'C', true);

        // Imprimir detalles usando MultiCell
        $x = $pdf->GetX();
        $y = $pdf->GetY();
        $pdf->SetFont('Arial', '', 9); // Establecer la fuente a Arial
        $pdf->MultiCell(60, 10, utf8_decode($row['detalles']), 1, 'C', true);
        $pdf->SetXY($x + 60, $y);

        // Ajustar la posición Y para la siguiente línea después de usar MultiCell
        $y_after = $pdf->GetY();
        $pdf->SetY(max($y, $y_after));
        $pdf->Ln();
    }
} else {
    // Si no se encontraron interacciones de alumnos, imprimir un mensaje
    $pdf->Cell(0, 10, 'No se encontraron interacciones de alumnos para esta tarea.', 1, 1, 'C', true);
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
