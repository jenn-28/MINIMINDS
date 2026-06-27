<?php
session_start();
include '../../Logeo/conexion.php';
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

// Verificar si se envió el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener los datos del formulario
    $quizName = $_POST['nameQuizz'];
    $quizDescription = $_POST['descripQuizz'];
    $id_profesor = $_POST['id_profesor']; // Obtener el ID del profesor desde el formulario

    // Insertar el cuestionario en la tabla quizzes
    $insertQuizQuery = "INSERT INTO quizzes (teacher_id, quiz_name, descrip) VALUES ('$id_profesor', '$quizName', '$quizDescription')";
    $result = mysqli_query($conexion, $insertQuizQuery);

    if ($result) {
        $quizId = mysqli_insert_id($conexion);

        // Obtener las preguntas del formulario
        $questions = array();
        $questionCount = isset($_POST['questionText1']) ? 1 : 0;

        while (isset($_POST['questionText' . $questionCount])) {
            $questionText = $_POST['questionText' . $questionCount];
            $answerA = $_POST['answerA' . $questionCount];
            $answerB = $_POST['answerB' . $questionCount];
            $answerC = $_POST['answerC' . $questionCount];
            $answerD = $_POST['answerD' . $questionCount];
            $correctAnswer = isset($_POST['correctAnswer' . $questionCount]) ? $_POST['correctAnswer' . $questionCount] : null;

            $questions[] = array(
                'text' => $questionText,
                'optionA' => $answerA,
                'optionB' => $answerB,
                'optionC' => $answerC,
                'optionD' => $answerD,
                'correct' => $correctAnswer
            );

            $questionCount++;
        }

        // Insertar las preguntas en la tabla quiz_questions
        foreach ($questions as $question) {
            $questionText = $question['text'];
            $answerA = $question['optionA'];
            $answerB = $question['optionB'];
            $answerC = $question['optionC'];
            $answerD = $question['optionD'];
            $correctAnswer = $question['correct'];

            $insertQuestionQuery = "INSERT INTO quiz_questions (quiz_id, question_text, answer_optionA, answer_optionB, answer_optionC, answer_optionD, correct_answer) VALUES ('$quizId', '$questionText', '$answerA', '$answerB', '$answerC', '$answerD', '$correctAnswer')";
            mysqli_query($conexion, $insertQuestionQuery);
        }
        mostrarNotificacion("success", "Guardado!", "El cuestionario se ha guardado correctamente.", "index.php");
    } else {
        mostrarNotificacion("error", "Error", "Error al guardar el cuestionario", "index.php");
    }
} else {
    mostrarNotificacion("error", "Error", "Acceso no autorizado", "../../index.php");
}
?>
