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

if (!isset($_GET['quiz_id'])) {
    mostrarNotificacion("error", "Error", "ID de cuestionario no proporcionado.", "index.php");
    exit;
}

$quiz_id = $_GET['quiz_id'];

// Obtener datos del cuestionario
$query = "SELECT quiz_name, descrip FROM quizzes WHERE quiz_id = '$quiz_id'";
$result = mysqli_query($conexion, $query);

if ($row = mysqli_fetch_assoc($result)) {
    $quiz_name = $row['quiz_name'];
    $quiz_description = $row['descrip'];
} else {
    echo '<script>
        alert("Cuestionario no encontrado.");
        window.location.href = "index.php";
      </script>';
    exit;
}

// Obtener preguntas del cuestionario
$questions_query = "SELECT * FROM quiz_questions WHERE quiz_id = '$quiz_id'";
$questions_result = mysqli_query($conexion, $questions_query);
$questions = mysqli_fetch_all($questions_result, MYSQLI_ASSOC);

?>

<!DOCTYPE html>
<html lang="es">
<head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
        <link rel="stylesheet" href="style.css">    
        <title>Miniminds</title>
    <link rel="icon" href="../../imagenes/LogoS.png" >
    <style>
            .radio-buttons {
                display: flex;
                justify-content: center;
                gap: 20px;
            }

            .radio-buttons input[type="radio"] {
                display: none;
            }

            .radio-buttons label {
                position: relative;
                padding: 10px 20px;
                cursor: pointer;
                font-size: 1.2rem;
            }

            .radio-buttons input[type="radio"]:checked + label::after {
                content: '';
                position: absolute;
                bottom: 0;
                left: 0;
                right: 0;
                height: 3px;
                background-color: blue;
            }
           

            #editForm {
                width: 85%;
                margin: auto;
                background-color: #fff;
                padding: 20px;
                box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
                border-radius: 8px;
                border: 2px solid #4CAF50;
                background-color: #e8f5e9;
            }

             label {
                display: block;
                margin-top: 10px;
                font-weight: bold;
            }

            input[type="text"], input[type="radio"],  .btn {
                width: 80%;
                padding: 10px;
                margin: 5px 0;
                border: 1px solid #ccc;
                border-radius: 5px;
            }

            input[type="radio"] {
                width: auto;
                margin-left: 10px;
            }

            .answer-container, .answer-option {
                margin-bottom: 10px;
            }

            .answer-option label {
                display: inline-block;
                width: auto;
                margin-right: 10px;
                font-weight: normal;
            }

            .btn {
                display: inline-block;
                width: auto;
                padding: 10px 20px;
                margin: 5px;
                border: none;
                border-radius: 5px;
                cursor: pointer;
                font-size: 1rem;
            }

            .btn-primary {
                background-color: #007BFF;
                color: #fff;
            }

            .btn-success {
                background-color: #28A745;
                color: #fff;
            }

            .btn-primary:hover, .btn-success:hover {
                opacity: 1.2;
            }
    </style>


</head>
<body>
    <br><br>
    <center><h1>Editar Cuestionario</h1></center>
    <br><br>
    <button type="" class="btn btn-secondary" onclick="window.location.href='index.php'">Salir</button>
    <form id="editForm" method="post" action="actualizar_cuestionario.php">
        <input type="hidden" name="quiz_id" value="<?php echo $quiz_id; ?>">
        <label for="nameQuizz">Nombre del cuestionario:</label>
        <input type="text" id="nameQuizz" name="nameQuizz" value="<?php echo $quiz_name; ?>" required readonly>
        <br><br>
        <label for="descripQuizz">Descripción Breve:</label>
        <input type="text" id="descripQuizz" name="descripQuizz" value="<?php echo $quiz_description; ?>" required>
        <br><br>
        <div id="question-container">
            <?php
            $question_number = 1;
            foreach ($questions as $question) {
                echo '<div class="question" data-question-number="' . $question_number . '" id="question' . $question_number . '">';
                echo '<label for="questionText' . $question_number . '">Pregunta:</label>';
                echo '<input type="text" id="questionText' . $question_number . '" name="questionText' . $question_number . '" value="' . $question['question_text'] . '" required>';
                echo '<button type="button" class="btn btn-danger" onclick="eliminarPregunta(' . $question_number . ')">Eliminar Pregunta</button>';
                echo '<br>';
                echo '<label>Opciones:</label>';
                echo '<div class="answer-option"><label for="answerA' . $question_number . '">A.</label>';
                echo '<input type="text" id="answerA' . $question_number . '" name="answerA' . $question_number . '" value="' . $question['answer_optionA'] . '" required>';
                echo '<input type="radio" id="correctAnswerA' . $question_number . '" name="correctAnswer' . $question_number . '" value="A"' . ($question['correct_answer'] == 'A' ? ' checked' : '') . '></div>';
                echo '<div class="answer-option"><label for="answerB' . $question_number . '">B.</label>';
                echo '<input type="text" id="answerB' . $question_number . '" name="answerB' . $question_number . '" value="' . $question['answer_optionB'] . '" required>';
                echo '<input type="radio" id="correctAnswerB' . $question_number . '" name="correctAnswer' . $question_number . '" value="B"' . ($question['correct_answer'] == 'B' ? ' checked' : '') . '></div>';
                echo '<div class="answer-option"><label for="answerC' . $question_number . '">C.</label>';
                echo '<input type="text" id="answerC' . $question_number . '" name="answerC' . $question_number . '" value="' . $question['answer_optionC'] . '" required>';
                echo '<input type="radio" id="correctAnswerC' . $question_number . '" name="correctAnswer' . $question_number . '" value="C"' . ($question['correct_answer'] == 'C' ? ' checked' : '') . '></div>';
                echo '<div class="answer-option"><label for="answerD' . $question_number . '">D.</label>';
                echo '<input type="text" id="answerD' . $question_number . '" name="answerD' . $question_number . '" value="' . $question['answer_optionD'] . '" required>';
                echo '<input type="radio" id="correctAnswerD' . $question_number . '" name="correctAnswer' . $question_number . '" value="D"' . ($question['correct_answer'] == 'D' ? ' checked' : '') . '></div>';
                echo '</div><br>';
                $question_number++;
            }
            ?>
        </div>
        <button type="button" class="btn btn-primary" onclick="agregarPregunta()">+ Nueva pregunta</button>
        <button type="submit" class="btn btn-success">Guardar Cambios</button>
    </form>

</body>
</html>

<script>
    let questionNumber = <?php echo $question_number; ?>;
    
    function agregarPregunta() {
        let container = document.getElementById('question-container');
        let newQuestionHTML = `
            <div class="question" data-question-number="${questionNumber}" id="question${questionNumber}">
                <label for="questionText${questionNumber}">Pregunta:</label>
                <input type="text" id="questionText${questionNumber}" name="questionText${questionNumber}" required>
                <button type="button" class="btn btn-danger" onclick="eliminarPregunta(${questionNumber})">Eliminar Pregunta</button><br>
                <label>Opciones:</label>
                <div class="answer-option">
                    <label for="answerA${questionNumber}">A.</label>
                    <input type="text" id="answerA${questionNumber}" name="answerA${questionNumber}" required>
                    <input type="radio" id="correctAnswerA${questionNumber}" name="correctAnswer${questionNumber}" value="A">
                </div>
                <div class="answer-option">
                    <label for="answerB${questionNumber}">B.</label>
                    <input type="text" id="answerB${questionNumber}" name="answerB${questionNumber}" required>
                    <input type="radio" id="correctAnswerB${questionNumber}" name="correctAnswer${questionNumber}" value="B">
                </div>
                <div class="answer-option">
                    <label for="answerC${questionNumber}">C.</label>
                    <input type="text" id="answerC${questionNumber}" name="answerC${questionNumber}" required>
                    <input type="radio" id="correctAnswerC${questionNumber}" name="correctAnswer${questionNumber}" value="C">
                </div>
                <div class="answer-option">
                    <label for="answerD${questionNumber}">D.</label>
                    <input type="text" id="answerD${questionNumber}" name="answerD${questionNumber}" required>
                    <input type="radio" id="correctAnswerD${questionNumber}" name="correctAnswer${questionNumber}" value="D">
                </div>
            </div><br>`;
        container.insertAdjacentHTML('beforeend', newQuestionHTML);
        questionNumber++;
    }
    
    function eliminarPregunta(questionNumber) {
        let questionElement = document.getElementById('question' + questionNumber);
        if (questionElement) {
            questionElement.remove();
        }
    }
</script>
