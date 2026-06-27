<?php
session_start();

// Incluir archivo de conexión
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

// Verificar si hay una sesión activa y obtener el ID del usuario
if (!isset($_SESSION["username"])) {
    mostrarNotificacion("error", "Error", "No hay una sesion activa", "../../index.php");
}

// Obtener los datos del formulario
if (
    isset($_POST['quiz_id']) &&
    isset($_POST['qtask_id']) &&
    isset($_POST['id_usuario']) &&
    isset($_POST['tiempo_transcurrido']) &&
    isset($_POST['correctas']) &&
    isset($_POST['correctas_indices'])
) {
    $quiz_id = intval($_POST['quiz_id']);
    $task_id = intval($_POST['qtask_id']);
    $id_usuario = intval($_POST['id_usuario']);
    $tiempo_transcurrido = intval($_POST['tiempo_transcurrido']);
    $correctas = intval($_POST['correctas']);
    $correctas_indices = $_POST['correctas_indices'];

    // Crear un array para almacenar las respuestas del estudiante
    $respuestas = [];
    foreach ($_POST as $key => $value) {
        // Verificar si el campo es una respuesta de pregunta
        if (strpos($key, 'respuesta_') === 0) {
            // Obtener el ID de la pregunta de la clave
            $question_id = intval(substr($key, strlen('respuesta_')));
            // Asignar la respuesta al ID de la pregunta en el array de respuestas
            $respuestas[$question_id] = $value;
        }
    }

    // Obtener las respuestas correctas del quiz desde la tabla quiz_question
    $query_respuestas_correctas = "SELECT question_id, correct_answer FROM quiz_questions WHERE quiz_id = ?";
    $stmt_respuestas_correctas = $conexion->prepare($query_respuestas_correctas);
    $stmt_respuestas_correctas->bind_param("i", $quiz_id);
    $stmt_respuestas_correctas->execute();
    $resultado_respuestas_correctas = $stmt_respuestas_correctas->get_result();

    if ($resultado_respuestas_correctas->num_rows == 0) {

    mostrarNotificacion("error", "Error", "No se encontraron las respuestas de este Quiz", "../inicioAlumno.php");
    }

    // Crear un array asociativo con las respuestas correctas
    $respuestas_correctas = [];
    while ($fila_respuesta = $resultado_respuestas_correctas->fetch_assoc()) {
        $respuestas_correctas[$fila_respuesta['question_id']] = $fila_respuesta['correct_answer'];
    }

    $aciertos = 0;
    $detalles = ""; // Detalles de las respuestas
    $contador_texto = 1; // Inicializar contador para la cadena de texto
    foreach ($respuestas as $question_id => $respuesta) {
        // Mostrar el contador en la cadena de texto en lugar del ID de la pregunta
        if (isset($respuestas_correctas[$question_id])) {
            if ($respuesta == $respuestas_correctas[$question_id]) {
                $aciertos++;
                $detalles .= "$contador_texto: correct| ";
            } else {
                $detalles .= "$contador_texto: incorrect| ";
            }
        } else {
            mostrarNotificacion("error", "Error", "No se encontró la respuesta correcta para la pregunta $question_id.", "../inicioAlumno.php");
        }
        $contador_texto++; // Incrementar contador para la cadena de texto
    }
    
    

    // Calcular la fecha y hora actuales y el tiempo de inicio
    date_default_timezone_set('America/Mexico_City');
    $end_time = date("Y-m-d H:i:s", strtotime("1 hour ago")); // Obtener la fecha y hora actual menos una hora
    $duration = $tiempo_transcurrido / 1000; // Convertir a segundos
    $durationFormatted = number_format($duration, 1); // Formatear a un decimal
    $durationT = $durationFormatted . "s";
    $start_time = date("Y-m-d H:i:s", strtotime($end_time) - $duration);

    // Preparar la consulta para insertar las respuestas en la tabla student_interactions
    $query_insert_respuestas = "INSERT INTO student_interactions (student_id, task_id, start_time, end_time, duration, puntaje, detalles) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conexion->prepare($query_insert_respuestas);

    if ($stmt === false) {
        mostrarNotificacion("error", "Error", "Error al preparar la consulta para insertar las respuestas", "../inicioAlumno.php");
    }

    // Ejecutar la inserción
    $stmt->bind_param("iisssis", $id_usuario, $task_id, $start_time, $end_time, $durationT, $correctas, $detalles);
    $stmt->execute();

    if ($stmt->affected_rows === -1) {
        mostrarNotificacion("error", "Error", "Error al registrar tus respuestas", "../inicioAlumno.php");
    }

    // Cerrar la consulta y la conexión
    $stmt->close();
    $conexion->close();

    // Mostrar alerta de éxito y redirigir
    mostrarNotificacion("success", "Tarea terminada!", "Respuestas guardadas correctamente", "../inicioAlumno.php");
    exit();

} else {
    mostrarNotificacion("error", "Error", "Datos del quiz incompletos", "../inicioAlumno.php");
}
?>

