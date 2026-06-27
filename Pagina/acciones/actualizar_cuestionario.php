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
    $quiz_id = $_POST['quiz_id'];
    $quizName = $_POST['nameQuizz'];
    $quizDescription = $_POST['descripQuizz'];

    // Actualizar el cuestionario en la tabla quizzes
    $updateQuizQuery = "UPDATE quizzes SET quiz_name = '$quizName', descrip = '$quizDescription' WHERE quiz_id = '$quiz_id'";
    $result = mysqli_query($conexion, $updateQuizQuery);

    if ($result) {
        // Eliminar preguntas actuales del cuestionario
        $deleteQuestionsQuery = "DELETE FROM quiz_questions WHERE quiz_id = '$quiz_id'";
        mysqli_query($conexion, $deleteQuestionsQuery);

        // Obtener las nuevas preguntas del formulario
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

        // Insertar las nuevas preguntas en la tabla quiz_questions
        foreach ($questions as $question) {
            $questionText = $question['text'];
            $answerA = $question['optionA'];
            $answerB = $question['optionB'];
            $answerC = $question['optionC'];
            $answerD = $question['optionD'];
            $correctAnswer = $question['correct'];

            $insertQuestionQuery = "INSERT INTO quiz_questions (quiz_id, question_text, answer_optionA, answer_optionB, answer_optionC, answer_optionD, correct_answer) VALUES ('$quiz_id', '$questionText', '$answerA', '$answerB', '$answerC', '$answerD', '$correctAnswer')";
            mysqli_query($conexion, $insertQuestionQuery);
        }
        mostrarNotificacion("success", "Actualizacion Correcta", "El cuestionario se ha actualizado correctamente.", "index.php");
    } else {
        mostrarNotificacion("error", "Error", "Error al actualizar el cuestionario", "index.php");
    }
} else {
    mostrarNotificacion("error", "Error", "Acceso no autorizado", "../../index.php");
}
?>
