<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
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
    mostrarNotificacion("error", "Error", "No hay unsa sesión activa", "../../Logeo/IniciarSesion.php");
}

$username = $_SESSION["username"];
$id_usuario = "";

// Obtener el ID del usuario
$consulta_estudiante = "SELECT id FROM students WHERE nombre_completo = ?";
$stmt = $conexion->prepare($consulta_estudiante);
if ($stmt === false) {
    mostrarNotificacion("error", "Error", "Error al preparar la consulta de estudiante", "../inicioAlumno.php");
}
$stmt->bind_param("s", $username);
$stmt->execute();
$resultado_estudiante = $stmt->get_result();

if ($resultado_estudiante->num_rows > 0) {
    $fila_estudiante = $resultado_estudiante->fetch_assoc();
    $id_usuario = $fila_estudiante["id"];
    echo "<script>console.log('ID del usuario: " . $id_usuario . "');</script>";
} else {
    mostrarNotificacion("error", "Error", "Usuario no encontrado", "../../index.php");
}

$stmt->close();

// Verificar si se recibe el id_task
if (!isset($_GET['iq']) || !isset($_GET['i']) || !isset($_GET['it'])) {
    mostrarNotificacion("error", "Error", "Parametros incompletos", "../inicioAlumno.php");
}

$quiz_id = intval($_GET['iq']);
$id_usuario = intval($_GET['i']);
$configuracion_esperado = $_GET['it'];

echo "<script>console.log('Quiz ID: " . $quiz_id . "');</script>";
echo "<script>console.log('ID del usuario: " . $id_usuario . "');</script>";
echo "<script>console.log('Configuración esperada: " . $configuracion_esperado . "');</script>";

// Obtener la información de la tarea desde la tabla tasks
$query_task = "SELECT task_name, created_by FROM tasks WHERE configuracion = ?";
$stmt = $conexion->prepare($query_task);
if ($stmt === false) {
    mostrarNotificacion("error", "Error", "Error al preparar la consulta de tarea", "../inicioAlumno.php");
}
$stmt->bind_param("s", $configuracion_esperado);
$stmt->execute();
$resultado_task = $stmt->get_result();

if ($resultado_task->num_rows > 0) {
    $fila_task = $resultado_task->fetch_assoc();
    $nombre_tarea = $fila_task["task_name"];
    $creador_tarea = $fila_task["created_by"];
    echo "<script>console.log('Nombre de la tarea: " . $nombre_tarea . "');</script>";
    echo "<script>console.log('Creador de la tarea: " . $creador_tarea . "');</script>";
} else {
    mostrarNotificacion("error", "Error", "Tarea no encontrada", "../inicioAlumno.php");
}

$stmt->close();

// Obtener el ID del profesor
$query_teacher = "SELECT id FROM teachers WHERE nombre_completo = ?";
$stmt = $conexion->prepare($query_teacher);
if ($stmt === false) {
    mostrarNotificacion("error", "Error", "Error al preparar la consulta del profesor", "../inicioAlumno.php");
}
$stmt->bind_param("s", $creador_tarea);
$stmt->execute();
$resultado_teacher = $stmt->get_result();

if ($resultado_teacher->num_rows > 0) {
    $fila_teacher = $resultado_teacher->fetch_assoc();
    $teacher_id = $fila_teacher["id"];
    echo "<script>console.log('ID del profesor: " . $teacher_id . "');</script>";
} else {
    mostrarNotificacion("error", "Error", "Profesor no encontrado", "../inicioAlumno.php");
}

$stmt->close();

// Obtener el nombre del quiz desde la tabla quizzes
$query_quiz = "SELECT quiz_id, quiz_name FROM quizzes WHERE quiz_name = ? AND teacher_id = ?";
$stmt = $conexion->prepare($query_quiz);
if ($stmt === false) {
    mostrarNotificacion("error", "Error", "Error al preparar la consulta del quiz", "../inicioAlumno.php");
}

$stmt->bind_param("si", $nombre_tarea, $teacher_id);
$stmt->execute();
$resultado_quiz = $stmt->get_result();

if ($resultado_quiz->num_rows > 0) {
    $fila_quiz = $resultado_quiz->fetch_assoc();
    $quiz_name = $fila_quiz["quiz_name"];
    $quiz_id = $fila_quiz["quiz_id"];
    echo "<script>console.log('Nombre del quiz: " . $quiz_name . "');</script>";
    echo "<script>console.log('ID del quiz: " . $quiz_id . "');</script>";

   

$query_qtask = "SELECT task_id, task_name FROM tasks WHERE task_name = ? AND created_by = ?";
$stmt = $conexion->prepare($query_qtask);
if ($stmt === false) {
    mostrarNotificacion("error", "Error", "Error al preparar la consulta del quiz", "../inicioAlumno.php");
}

$stmt->bind_param("si", $nombre_tarea, $creador_tarea);
$stmt->execute();
$resultado_qtask = $stmt->get_result();

if ($resultado_qtask->num_rows > 0) {
    $fila_qtask = $resultado_qtask->fetch_assoc();
    $qtak_name = $fila_qtask["task_name"];
    $qtask_id = $fila_qtask["task_id"];

    echo "<script>console.log('ID de la tarea: " . $qtask_id . "');</script>";
}else{
    mostrarNotificacion("error", "Error", "Tarea no encontrada", "../inicioAlumno.php");

}

} else {
    mostrarNotificacion("error", "Error", "Quiz no encontrado", "../inicioAlumno.php");
}

$stmt->close();

// Obtener las preguntas del quiz desde la tabla quiz_question
$query_questions = "SELECT question_id, question_text, answer_optionA, answer_optionB, answer_optionC, answer_optionD, correct_answer FROM quiz_questions WHERE quiz_id = ?";
$stmt = $conexion->prepare($query_questions);
if ($stmt === false) {
    mostrarNotificacion("error", "Error", "Error al preparar la consulta de las preguntas", "../inicioAlumno.php");

}
$stmt->bind_param("i", $quiz_id);
$stmt->execute();
$resultado_questions = $stmt->get_result();

if ($resultado_questions->num_rows == 0) {
    mostrarNotificacion("error", "Error", "No se encontraron preguntas para este quiz.", "../inicioAlumno.php");
}

$preguntas = [];
while ($fila_question = $resultado_questions->fetch_assoc()) {
    $preguntas[] = $fila_question;
}

$stmt->close();
$conexion->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Miniminds</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <link rel="icon" href="../../imagenes/LogoS.png">
    <link rel="stylesheet" href="style.css">
    <script>
    // Función para formatear el tiempo en HH:MM:SS
    function formatearTiempo(milisegundos) {
        const horas = Math.floor(milisegundos / (1000 * 60 * 60));
        const minutos = Math.floor((milisegundos % (1000 * 60 * 60)) / (1000 * 60));
        const segundos = Math.floor((milisegundos % (1000 * 60)) / 1000);
        return `${horas.toString().padStart(2, '0')}:${minutos.toString().padStart(2, '0')}:${segundos.toString().padStart(2, '0')}`;
    }

    // Función para iniciar el temporizador
    let startTime;
    function iniciarTemporizador() {
        startTime = Date.now();
        document.getElementById('tiempo_inicio').value = formatearTiempo(startTime);
    }

    function detenerTemporizador() {
        const respuestasSeleccionadas = document.querySelectorAll('input[type="radio"]:checked');
        const totalPreguntas = <?php echo count($preguntas); ?>;
        let correctas = 0;

        // Verificar si hay al menos una pregunta en el formulario
        if (totalPreguntas === 0) {
            alert("Error: No hay preguntas en el formulario.");
            return false; // Evita que se detenga el temporizador y se envíe el formulario
        }

        // Verificar si se han respondido todas las preguntas
        if (respuestasSeleccionadas.length !== totalPreguntas) {
            alert("Por favor, responde todas las preguntas antes de enviar el formulario.");
            return false; // Evita que se detenga el temporizador y se envíe el formulario
        }

        // Obtener todas las preguntas y sus respuestas correctas
        const preguntas = <?php echo json_encode($preguntas); ?>;
        let correctasIndices = [];

        preguntas.forEach((pregunta, index) => {
            const respuestaSeleccionada = document.querySelector(`input[name="respuesta_${pregunta.question_id}"]:checked`);
            if (respuestaSeleccionada && respuestaSeleccionada.value === pregunta.correct_answer) {
                correctas++;
                correctasIndices.push(index + 1); // +1 para ajustar a la posición 1-based
            }
        });

       
        // Si todas las preguntas han sido respondidas correctamente, detener el temporizador y enviar el formulario
        const endTime = Date.now();
        const tiempoTranscurrido = endTime - startTime; // en milisegundos
        document.getElementById('tiempo_transcurrido').value = tiempoTranscurrido;

        // Establecer los valores correctos y enviar el formulario
        document.getElementById('correctas').value = correctas;
        document.getElementById('correctas_indices').value = correctasIndices.join(', ');

        return true;
    }

    window.onload = iniciarTemporizador;
</script>
</head>
<body>
    <center>
        <div class="presentacion">
            <h1><?php echo htmlspecialchars($quiz_name); ?></h1>
            <p>Creado por: <?php echo htmlspecialchars($creador_tarea); ?></p>
        </div>
    </center>
    <form action="submit_quiz.php" method="post" onsubmit="return detenerTemporizador()">
        <input type="hidden" name="quiz_id" value="<?php echo $quiz_id; ?>">
        <input type="hidden" name="qtask_id" value="<?php echo $qtask_id; ?>">
        <input type="hidden" name="id_usuario" value="<?php echo $id_usuario; ?>">
        <input type="hidden" id="tiempo_inicio" name="tiempo_inicio" value="">
        <input type="hidden" id="tiempo_transcurrido" name="tiempo_transcurrido" value="">
        <input type="hidden" id="correctas" name="correctas" value="">
        <input type="hidden" id="correctas_indices" name="correctas_indices" value="">

        <?php foreach ($preguntas as $pregunta): ?>
            <center>
                <fieldset>
                    <br>
                    <legend><?php echo htmlspecialchars($pregunta['question_text']); ?></legend>
                    <label><input type="radio" name="respuesta_<?php echo $pregunta['question_id']; ?>" value="A"> <?php echo htmlspecialchars($pregunta['answer_optionA']); ?></label><br>
                    <label><input type="radio" name="respuesta_<?php echo $pregunta['question_id']; ?>" value="B"> <?php echo htmlspecialchars($pregunta['answer_optionB']); ?></label><br>
                    <label><input type="radio" name="respuesta_<?php echo $pregunta['question_id']; ?>" value="C"> <?php echo htmlspecialchars($pregunta['answer_optionC']); ?></label><br>
                    <label><input type="radio" name="respuesta_<?php echo $pregunta['question_id']; ?>" value="D"> <?php echo htmlspecialchars($pregunta['answer_optionD']); ?></label><br>
                </fieldset>
            </center>
        <?php endforeach; ?>
        <br><br>
        <center><button class="btn" type="submit" id="submitBtn" disabled>Terminar</button></center>
    </form>

    <script>
        // Habilitar el botón de envío del formulario cuando todas las preguntas hayan sido respondidas
        document.addEventListener('DOMContentLoaded', function () {
            const radios = document.querySelectorAll('input[type="radio"]');
            const submitBtn = document.getElementById('submitBtn');

            radios.forEach(function (radio) {
                radio.addEventListener('change', function () {
                    if (document.querySelectorAll('input[type="radio"]:checked').length === <?php echo count($preguntas); ?>) {
                        submitBtn.disabled = false;
                    }
                });
            });
        });
    </script>
</body>
</html>
