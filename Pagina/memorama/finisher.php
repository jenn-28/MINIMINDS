<?php
session_start();
include("../../Logeo/conexion.php");
// Función para mostrar notificaciones de SweetAlert
function mostrarNotificacion($icon, $titulo, $mensaje, $redireccion = null) {
    echo '<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            Swal.fire({
                icon: "' . $icon . '",
                title: "' . $titulo . '",
                text: "' . $mensaje . '",
                confirmButtonText: "Aceptar"
            }).then(function() {
                if ("' . $redireccion . '" !== "") {
                    window.location.href = "' . $redireccion . '";
                }
            });
        });
    </script>';
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener los datos enviados por POST
    $id_task = $_POST['id_task'];
    $tiempo = $_POST['tiempo'];
    $movimi = $_POST['movimientos'];
    $id_estudiante = $_POST['id_estudiante'];
    $movimientos = $movimi." movimientos";
    // Verificar si el id_estudiante existe en la tabla students
    $query = "SELECT * FROM `students` WHERE `id` = ?";
    $stmt = $conexion->prepare($query);
    $stmt->bind_param("i", $id_estudiante);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Verificar si ya existe una interacción con los mismos student_id y task_id
        $checkQuery = "SELECT * FROM `student_interactions` WHERE `student_id` = ? AND `task_id` = ?";
        $checkStmt = $conexion->prepare($checkQuery);
        if ($checkStmt === false) {
            die("Error en la preparación de la declaración: " . $conexion->error);
        }

        $checkStmt->bind_param("ii", $id_estudiante, $id_task);
        $checkStmt->execute();
        $checkResult = $checkStmt->get_result();

        if ($checkResult->num_rows == 0) {
            // No existe una interacción previa, proceder con la inserción

            // Obtener la fecha y hora actual en la zona horaria de México (menos 1 hora)
            date_default_timezone_set('America/Mexico_City');
            $end_time = date('Y-m-d H:i:s', strtotime('-1 hour'));

            // Calcular start_time restando el tiempo de duration
            $start_time = date('Y-m-d H:i:s', strtotime($end_time) - $tiempo);

            $sql = "INSERT INTO student_interactions (student_id, task_id, start_time, end_time, duration, puntaje, detalles) 
            VALUES (?, ?, ?, ?, ?, NULL, ?)";
    $stmt = $conexion->prepare($sql);

    if ($stmt === false) {
        die("Error en la preparación de la declaración: " . $conexion->error);
    }

    // Corrigiendo el número de parámetros de bind_param
    $stmt->bind_param("iissss", $id_estudiante, $id_task, $start_time, $end_time, $tiempo, $movimientos);

    if ($stmt->execute() === true) {
        mostrarNotificacion("success", "Tarea Completada", "Se registro correctamente", "../inicioAlumno.php");
            } else {
                mostrarNotificacion("error", "Error", "Hubo un error al finalizar tu tarea.", "../inicioAlumno.php");
            }

            $stmt->close();
        } else {
            mostrarNotificacion("warning", "Advertencia", "Ya existe una interacción con los mismos valores de student_id y task_id.", "../inicioAlumno.php");
        }

        $checkStmt->close();
    } else {
        mostrarNotificacion("info", "Fin de la revision", "", "../inicioProfesor.php");
    }

   
    $conexion->close();
} else {
    mostrarNotificacion("error", "Error", "No se recivieron los datos", "../inicioAlumno.php");
}
?>
<script>
    // Aquí comienza el código JavaScript
    console.log(<?php echo json_encode($id_task); ?>);
    console.log(<?php echo json_encode($tiempo); ?>);
    console.log(<?php echo json_encode($movimientos); ?>);
    console.log(<?php echo json_encode($id_estudiante); ?>);
</script>