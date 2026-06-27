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
    $id_task = $_POST['task_id'];
    $id_estudiante = $_POST['student_id'];
    $tiempo = $_POST['tiempo'];
    $puntaje = $_POST['puntaje'];
    $porcen = $_POST['porcentaje'];
    $porcentaje = $porcen." puntos de 100";

    // Verificar si la conexión a la base de datos está configurada
    if (!$conexion) {
        die("Error de conexión: " . mysqli_connect_error());
    }

    // Verificar si el id_estudiante existe en la tabla students
    $query = "SELECT * FROM `students` WHERE `id` = ?";
    $stmt = $conexion->prepare($query);
    if ($stmt === false) {
        die("Error en la preparación de la declaración: " . $conexion->error);
    }

    $stmt->bind_param("i", $id_estudiante);
    $stmt->execute();
    $result = $stmt->get_result();

    // Verificar si se obtuvo algún resultado de la consulta
    if ($result->num_rows > 0) {
         // Obtener la fecha y hora actual en la zona horaria de México (menos 1 hora)
         date_default_timezone_set('America/Mexico_City');
         $end_time = date('Y-m-d H:i:s', strtotime('-1 hour'));

         // Calcular start_time restando el tiempo de duration
         $start_time = date('Y-m-d H:i:s', strtotime('-' . $tiempo . ' seconds'));

        // El id_estudiante es válido, proceder con la inserción en student_interactions
        $sql = "INSERT INTO student_interactions (student_id, task_id, start_time, end_time, duration, puntaje, detalles) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conexion->prepare($sql);

        if ($stmt === false) {
            die("Error en la preparación de la declaración: " . $conexion->error);
        }

        // Corrigiendo el número de parámetros de bind_param
        $stmt->bind_param("iisssss", $id_estudiante, $id_task, $start_time, $end_time, $tiempo, $puntaje, $porcentaje);

        if ($stmt->execute() === true) {
            mostrarNotificacion("success", "Tarea Completada", "Se registro correctamente", "../inicioAlumno.php");

                } else {
                    mostrarNotificacion("error", "Error", "Hubo un error al finalizar tu tarea.", "../inicioAlumno.php");
                }

        $stmt->close();
    } else {
        mostrarNotificacion("info", "Fin de la revicion", "", "../inicioProfesor.php");
    }

    // Cerrar la conexión
    $conexion->close();
} else {
    mostrarNotificacion("error", "Error", "No se recivieron los datos", "../inicioAlumno.php");
}
?>
<script>
    // Aquí comienza el código JavaScript
    console.log(<?php echo json_encode($id_task); ?>);
    console.log(<?php echo json_encode($id_estudiante); ?>);
    console.log(<?php echo json_encode($tiempo); ?>);
    console.log(<?php echo json_encode($puntaje); ?>);
    console.log(<?php echo json_encode($porcentaje); ?>);
</script>
