<?php
session_start();

// Verificar si la sesión está iniciada y el usuario es un profesor
if (isset($_SESSION["username"]) && $_SESSION["role"] === "teacher") {
    $username = $_SESSION["username"];

    // Incluir archivo de conexión
    include("../../Logeo/conexion.php");

    $consulta = "SELECT id, nombre_completo FROM users WHERE nombre_completo = ?";
    $stmt = $conexion->prepare($consulta);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado && $resultado->num_rows > 0) {
        $fila = $resultado->fetch_assoc();
        $id_profesor = $fila["id"];
        $nombre_profesor = $fila["nombre_completo"];
    } else {
        echo '<script>
        alert("No se pudo encontrar el ID del profesor.");
        window.location.href = "index.php";
      </script>';
        exit;
    }

    $stmt->close();
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
           

            #crearCuestionario {
                width: 85%;
                margin: auto;
                background-color: #fff;
                padding: 20px;
                box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
                border-radius: 8px;
                border: 2px solid #4CAF50;
                background-color: #e8f5e9;
            }

            #cuestionarios label {
                display: block;
                margin-top: 10px;
                font-weight: bold;
            }

            #cuestionarios input[type="text"], input[type="radio"],  #cuestionarios .btn {
                width: 80%;
                padding: 10px;
                margin: 5px 0;
                border: 1px solid #ccc;
                border-radius: 5px;
            }

            #cuestionarios input[type="radio"] {
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

            #cuestionarios .btn {
                display: inline-block;
                width: auto;
                padding: 10px 20px;
                margin: 5px;
                border: none;
                border-radius: 5px;
                cursor: pointer;
                font-size: 1rem;
            }

            #cuestionarios .btn-primary {
                background-color: #007BFF;
                color: #fff;
            }

            #cuestionarios .btn-success {
                background-color: #28A745;
                color: #fff;
            }

            #cuestionarios .btn-primary:hover, .btn-success:hover {
                opacity: 1.2;
            }
        </style>

    </head>
    <body>
        <div class="nav">
            <a href="../inicioProfesor.php"><i class="fa-solid fa-house fa-flip"></i></a>
            <a href="#" onclick="showContent('grupos')">Grupos</a>
            <a href="#" onclick="showContent('tareas')">Tareas</a>
            <a href="#" onclick="showContent('test')">Cuestionarios</a>
            <a href="#" onclick="showContent('recursos')">Recursos</a>
        </div>


    <div id="grupos" class="content" >
            <br>
           <center><h1>Grupos</h1></center> 
           <br><br>
            <?php
            // Obtener los nombres de los grupos y sus IDs
            $grupo_nombres = array();
            $grupo_ids = array();

            $consulta_clases = "SELECT group_id, group_name FROM groups WHERE created_by = ?";
            $stmt_clases = $conexion->prepare($consulta_clases);
            $stmt_clases->bind_param("s", $nombre_profesor);
            $stmt_clases->execute();
            $result_clases = $stmt_clases->get_result();

            if ($result_clases && $result_clases->num_rows > 0) {
                while ($row = $result_clases->fetch_assoc()) {
                    $grupo_ids[] = $row['group_id'];
                    $grupo_nombres[] = htmlspecialchars($row['group_name']);
                }
            }

            // Ordenar los nombres de los grupos alfabéticamente manteniendo sus IDs correspondientes
            array_multisort($grupo_nombres, SORT_ASC, $grupo_ids);

            // Mostrar los botones en orden alfabético
            foreach ($grupo_nombres as $key => $grupo_nombre) {
                $grupo_id = $grupo_ids[$key];
                echo "<button class='btn boton-C' type='button' data-bs-toggle='collapse' data-bs-target='#grupo_$grupo_id' aria-expanded='false' aria-controls='grupo_$grupo_id'>$grupo_nombre</button><br><br>";
                echo "<div class='collapse' id='grupo_$grupo_id'>";
                echo "<div class='card card-body'>";
                echo "<br><br>";
                echo "<button class='print-btn' onclick='generarPDF(\"grupo\", $grupo_id, \"$nombre_profesor\", \"$grupo_nombre\")'><i class='fa-solid fa-file-pdf'></i></button>";
                echo "<br>";
                echo "<table id='tabla_grupo_$grupo_id' class='task-table'>";
                echo "<tr>
                        <th>No. Tarea</th>
                        <th>Nombre de Tarea</th>
                        <th>Descripción</th>
                        <th>Fecha de Expiración</th>
                    </tr>";

                // Obtener tareas para este grupo
                $sql_tareas = "SELECT * FROM tasks WHERE class_id = ?";
                $stmt_tareas = $conexion->prepare($sql_tareas);
                $stmt_tareas->bind_param("i", $grupo_id);
                $stmt_tareas->execute();
                $result_tareas = $stmt_tareas->get_result();

                if ($result_tareas && $result_tareas->num_rows > 0) {
                    $contador = 1;
                    while ($row_tarea = $result_tareas->fetch_assoc()) {
                        echo "<tr>
                                <td>" . $contador. "</td>
                                <td>" . htmlspecialchars($row_tarea['task_name']) . "</td>
                                <td>" . htmlspecialchars($row_tarea['descripcion']) . "</td>
                                <td>" . htmlspecialchars($row_tarea['fecha_expiracion']) . "</td>
                            </tr>";
                        $contador++;
                    }
                } else {
                    echo "<tr><td colspan='4'>No hay tareas asignadas a este grupo.</td></tr>";
                }
                $stmt_tareas->close();

                echo "</table>";
                echo "<br>";
                echo "</div>";
                echo "<br>";
                echo "</div>";
            }

            if (empty($grupo_nombres)) {
                echo "No se encontraron grupos para este profesor.";
            }

            $stmt_clases->close();
            ?>

    </div>

    <div id="tareas" class="content" style="display:none;">
            <br>
           <center><h1>Tareas</h1></center> 
           <br><br>
            <?php
            // Obtener las tareas creadas por el profesor
            $consulta_tareas = "SELECT t.task_id, t.task_name, t.descripcion, t.fecha_expiracion, g.group_name
                                FROM tasks t
                                INNER JOIN groups g ON t.class_id = g.group_id
                                WHERE t.created_by = ?";
            $stmt_tareas = $conexion->prepare($consulta_tareas);
            $stmt_tareas->bind_param("s", $nombre_profesor);
            $stmt_tareas->execute();
            $result_tareas = $stmt_tareas->get_result();

            $tareas_por_grupo = array(); // Array para almacenar las tareas agrupadas por grupo

            if ($result_tareas && $result_tareas->num_rows > 0) {
                while ($row = $result_tareas->fetch_assoc()) {
                    $grupo_nombre = htmlspecialchars($row['group_name']);
                    $tareas_por_grupo[$grupo_nombre][] = array(
                        'id' => $row['task_id'],
                        'nombre' => htmlspecialchars($row['task_name']),
                        'grupo' => $grupo_nombre,
                        'descripcion' => htmlspecialchars($row['descripcion']),
                        'fecha_expiracion' => htmlspecialchars($row['fecha_expiracion'])
                    );
                }
            }

            // Ordenar los nombres de grupo alfabéticamente
            ksort($tareas_por_grupo);

            // Función de comparación para ordenar las tareas por nombre
            function compararTareasPorNombre($a, $b) {
                return strcmp($a['nombre'], $b['nombre']);
            }

            // Mostrar las tareas ordenadas
            foreach ($tareas_por_grupo as $grupo_nombre => $tareas_grupo) {
                echo "<h2>$grupo_nombre</h2>";
                
                // Ordenar las tareas por nombre en este grupo
                usort($tareas_grupo, 'compararTareasPorNombre');
                
                foreach ($tareas_grupo as $tarea) {
                    $tarea_id = $tarea['id'];
                    $tarea_nombre = $tarea['nombre'];
                    $grupo_nombre = $tarea['grupo'];

                    echo "<button class='btn boton-C' type='button' data-bs-toggle='collapse' data-bs-target='#tarea_$tarea_id' aria-expanded='false' aria-controls='tarea_$tarea_id'>$tarea_nombre ($grupo_nombre)</button><br><br>";
                    echo "<div class='collapse' id='tarea_$tarea_id'>";
                    echo "<div class='card card-body'>";
                    echo "<button class='print-btn' id='print-btn_tarea_$tarea_id' onclick='generatePDF(\"tarea\", $tarea_id, \"$nombre_profesor\", \"$tarea_nombre\", \"$grupo_nombre\")'><i class='fa-solid fa-file-pdf'></i></button>";
                    echo "<br><br>";
                    echo "<table id='tabla_tarea_$tarea_id' class='task-table '>";
                    echo "<tr>
                            <th>Nombre del Alumno</th>
                            <th>Fin</th>
                            <th>Duración</th>
                            <th>Puntaje</th>
                            <th>Detalles</th>
                        </tr>";

                    // Obtener todos los alumnos de la clase
                    $sql_alumnos = "SELECT id, nombre_completo FROM students WHERE grupo = ?";
                    $stmt_alumnos = $conexion->prepare($sql_alumnos);
                    $stmt_alumnos->bind_param("s", $grupo_nombre);
                    $stmt_alumnos->execute();
                    $result_alumnos = $stmt_alumnos->get_result();

                    if ($result_alumnos && $result_alumnos->num_rows > 0) {
                        while ($row_alumno = $result_alumnos->fetch_assoc()) {
                            $alumno_id = $row_alumno['id'];
                            $alumno_nombre = htmlspecialchars($row_alumno['nombre_completo']);

                            // Obtener la interacción del alumno para esta tarea
                            $sql_interaccion_alumno = "SELECT * FROM student_interactions WHERE student_id = ? AND task_id = ?";
                            $stmt_interaccion_alumno = $conexion->prepare($sql_interaccion_alumno);
                            $stmt_interaccion_alumno->bind_param("ii", $alumno_id, $tarea_id);
                            $stmt_interaccion_alumno->execute();
                            $result_interaccion_alumno = $stmt_interaccion_alumno->get_result();

                            echo "<tr>";
                            if ($result_interaccion_alumno && $result_interaccion_alumno->num_rows > 0) {
                                $row_interaccion_alumno = $result_interaccion_alumno->fetch_assoc();
                                echo "<td>$alumno_nombre</td>";
                                echo "<td>" . htmlspecialchars($row_interaccion_alumno['end_time']) . "</td>";
                                echo "<td>" . htmlspecialchars($row_interaccion_alumno['duration']) . "</td>";
                                echo "<td>" . htmlspecialchars($row_interaccion_alumno['puntaje']) . "</td>";
                                echo "<td>" . htmlspecialchars($row_interaccion_alumno['detalles']) . "</td>";
                            } else {
                                echo "<td>$alumno_nombre</td>";
                                echo "<td></td>";
                                echo "<td></td>";
                                echo "<td></td>";
                                echo "<td></td>";
                                echo "<td>No ha realizado la tarea</td>";
                            }
                            echo "</tr>";

                            $stmt_interaccion_alumno->close();
                        }
                    }

                    echo "</table>";
                    echo "<br>";
                    echo "</div>";
                    echo "<br>";
                    echo "</div>";

                    $stmt_alumnos->close();
                }
            }

            if (empty($tareas_por_grupo)) {
                echo "No se encontraron tareas creadas por este profesor.";
            }

            $stmt_tareas->close();
            ?>


    </div>

        <div id="test" class="content" style="display:none;">
            <div id="cuestionarios">
                <br>
               <center><h1>Cuestionarios</h1></center>
               <br><br>
                <div class="radio-buttons">
                    <input type="radio" id="crear" name="cuestionarioOption" value="crear" checked>
                    <label for="crear">Crear Cuestionario</label>
                    <input type="radio" id="ver" name="cuestionarioOption" value="ver">
                    <label for="ver">Ver Cuestionarios Creados</label>
                </div>
                <div id="crearCuestionario" class="panel">
                    <div class="container">
                        <h2>Crear Test</h2>
                        <form id="testForm" method="post" action="guardar_cuestionario.php">
                            <br>
                            <label for="nameQuizz">Nombre del cuestionario:</label>
                            <input type="text" id="nameQuizz" name="nameQuizz" required>
                            <br><br>
                            <label for="descripQuizz">Descripcion Breve:</label>
                            <input type="text" id="descripQuizz" name="descripQuizz" required>
                            <br><br>
                            <div class="question-container" data-question-number="1">
                                <label for="questionText1">Pregunta:</label>
                                <input type="text" id="questionText1" name="questionText1" required>
                            </div>
                            <div class="answer-container">
                                <label>Opciones:</label>
                                <div class="answer-option">
                                    <label for="answerA1">A.</label>
                                    <input type="text" id="answerA1" name="answerA1" required>
                                    <input type="radio" id="correctAnswerA1" name="correctAnswer1" value="A">
                                </div>
                                <div class="answer-option">
                                    <label for="answerB1">B.</label>
                                    <input type="text" id="answerB1" name="answerB1" required>
                                    <input type="radio" id="correctAnswerB1" name="correctAnswer1" value="B">
                                </div>
                                <div class="answer-option">
                                    <label for="answerC1">C.</label>
                                    <input type="text" id="answerC1" name="answerC1" required>
                                    <input type="radio" id="correctAnswerC1" name="correctAnswer1" value="C">
                                </div>
                                <div class="answer-option">
                                    <label for="answerD1">D.</label>
                                    <input type="text" id="answerD1" name="answerD1" required>
                                    <input type="radio" id="correctAnswerD1" name="correctAnswer1" value="D">
                                </div>
                            </div>
                            <div id="additionalQuestions"></div>
                            <button type="button" class="btn btn-primary" onclick="agregarPregunta()">+ Nueva pregunta</button>
                            <input type="hidden" name="id_profesor" value="<?php echo $id_profesor; ?>">
                            <button type="submit" class="btn btn-success" id="guardarFormulario">Guardar Formulario</button>
                        </form>
                    </div>
                </div>
                <div id="verCuestionarios" class="panel" style="display:none;">
                    <div class="container">
                        <h2>Cuestionarios Creados</h2>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Nombre del Cuestionario</th>
                                    <th>Descripción</th>
                                    <th>Cantidad de Preguntas</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php
                            // Consulta para obtener los cuestionarios del profesor activo
                            $query = "SELECT quiz_id, quiz_name, descrip FROM quizzes WHERE teacher_id = '$id_profesor'";
                            $result = mysqli_query($conexion, $query);

                            if (mysqli_num_rows($result) > 0) {
                                while ($row = mysqli_fetch_assoc($result)) {
                                    // Obtener la cantidad de preguntas para cada cuestionario
                                    $quiz_id = $row['quiz_id'];
                                    $question_count_query = "SELECT COUNT(*) AS question_count FROM quiz_questions WHERE quiz_id = '$quiz_id'";
                                    $question_count_result = mysqli_query($conexion, $question_count_query);
                                    $question_count_row = mysqli_fetch_assoc($question_count_result);
                                    $question_count = $question_count_row['question_count'];
                            ?>
                                    <tr>
                                        <td><?php echo $row['quiz_name']; ?></td>
                                        <td><?php echo $row['descrip']; ?></td>
                                        <td><?php echo $question_count; ?></td>
                                        <td>
                                            <a href="editar_cuestionario.php?quiz_id=<?php echo $quiz_id; ?>" class="btn btn-primary">Editar</a>
                                            <form action="eliminar_cuestionario.php" method="post" style="display:inline;">
                                            <input type="hidden" name="quiz_id" value="<?php echo $quiz_id; ?>">
                                            <button type="submit" class="btn btn-danger">Eliminar</button>
                                            </form>
                                            <a href="asignar_grupo.php?quiz_id=<?php echo $quiz_id; ?>" class="btn btn-success">Asignar a Grupo</a>
                                        </td>
                                    </tr>
                            <?php
                                }
                            } else {
                                echo "<tr><td colspan='4'>No se encontraron cuestionarios.</td></tr>";
                            }
                            ?>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    <div id="recursos" class="content" style="display:none;">
       <br>
       <center><h1>Recursos</h1></center> 
       <br><br>
        <div class="radio-buttons">
                            <input type="radio" id="compartir" name="recursoOption" value="crear" checked>
                            <label for="compartir">Compartir Recursos</label>
                            <input type="radio" id="verRecursos" name="recursoOption" value="ver">
                            <label for="verRecursos">Ver Recursos Compartidos</label>
        </div>
        <div id="compartirRecursos" class="panel" style="display:none;">
                <div class="container">
                <h2>Compartir Recursos</h2>
                <form action="subir_recursos.php" method="post" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="grupo">Seleccione el Grupo:</label>
                        <select class="form-control" name="grupo" id="grupo">
                            <?php
                            // Realizar la consulta para obtener los grupos disponibles
                            $consulta_grupo = "SELECT group_id, group_name, group_code FROM groups WHERE created_by = '$nombre_profesor'";
                            $datos_grupo = mysqli_query($conexion, $consulta_grupo);

                            // Verificar si se obtuvieron resultados
                            if (mysqli_num_rows($datos_grupo) > 0) {
                                // Iterar sobre los resultados y construir las opciones del menú desplegable
                                while ($row = mysqli_fetch_assoc($datos_grupo)) {
                                    echo "<option value='" . $row['group_id'] . "'>" . $row['group_name'] ." - - ".  $row['group_code'] ."</option>";
                                }
                            } else {
                                echo "<option value='' disabled>No hay grupos disponibles</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="recurso" class="form-label">Seleccionar Recurso (formato pdf máximo 1 MB):</label>
                        <input type="file" class="form-control" id="recurso" name="recurso" accept=".pdf" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Subir Recurso</button>
                </form>
            </div>
        </div>

        <div id="verRecurso" class="panel" style="display:none;">
            <div class="container">
                <h2>Ver Recursos Compartidos</h2>
                <table class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre del Recurso</th>
                            <th>Grupo</th>
                            <th>Enlace de Descarga</th>
                            <th>Eliminar Recurso</th>

                        </tr>
                    </thead>
                    <tbody>
                    <?php
                    // Consulta para obtener los recursos compartidos por el profesor activo
                    $query_recursos = "SELECT id, nombre, grupo FROM recursos WHERE shared_by = ?";
                    $stmt_recursos = $conexion->prepare($query_recursos);
                    $stmt_recursos->bind_param("i", $id_profesor); // Usar el ID del profesor obtenido previamente
                    $stmt_recursos->execute();
                    $result_recursos = $stmt_recursos->get_result();

                    if ($result_recursos->num_rows > 0) {
                        $contador = 1; // Inicializa el contador
                        while ($row = $result_recursos->fetch_assoc()) {
                            $recurso_id = $row["id"];
                            $nombre_recurso = htmlspecialchars($row["nombre"]);
                            $grupo_recurso = htmlspecialchars($row["grupo"]);

                            // Consulta para obtener el nombre del grupo
                $query_grupo = "SELECT group_name FROM groups WHERE group_id = ?";
                $stmt_grupo = $conexion->prepare($query_grupo);
                $stmt_grupo->bind_param("i", $grupo_recurso);
                $stmt_grupo->execute();
                $result_grupo = $stmt_grupo->get_result();
                $nombre_grupo = $result_grupo->fetch_assoc()["group_name"];

                            echo "<tr>";
                            echo "<td>" . $contador . "</td>"; 
                            echo "<td>" . $nombre_recurso . "</td>";
                            echo "<td>" . htmlspecialchars($nombre_grupo) . "</td>";
                            echo "<td>
                            <form action='download.php' method='POST'>
                                <input type='hidden' name='file' value='" . $nombre_recurso . "'>
                                <button class='btn btn-info' type='submit'>Descargar</button>
                            </form>
                            </td>";
                                echo "<td>
                            <form action='eliminar_recurso.php' method='post' style='display:inline;'>
                                <input type='hidden' name='id' value='" . $recurso_id . "'>
                                <input type='hidden' name='id_profesor' value='" . $id_profesor . "'>
                                <button type='submit' class='btn btn-danger'>Eliminar</button>
                            </form>
                        </td>";
                            echo "</tr>";
                            $contador++; // Incrementa el contador en cada iteración

                        }
                    } else {
                        echo "<tr><td colspan='4'>No se encontraron recursos compartidos.</td></tr>";
                    }
                    $stmt_recursos->close();
                    ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const crearRadio = document.getElementById('crear');
        const verRadio = document.getElementById('ver');
        const crearPanel = document.getElementById('crearCuestionario');
        const verPanel = document.getElementById('verCuestionarios');

        crearRadio.addEventListener('change', function() {
            if (crearRadio.checked) {
                crearPanel.style.display = 'block';
                verPanel.style.display = 'none';
            }
        });

        verRadio.addEventListener('change', function() {
            if (verRadio.checked) {
                crearPanel.style.display = 'none';
                verPanel.style.display = 'block';
            }
        });

        
    });

     // Mostrar el div de grupos al cargar la página
     document.addEventListener('DOMContentLoaded', function() {
        showContent('grupos');
    });

    function agregarPregunta() {
        const additionalQuestions = document.getElementById('additionalQuestions');
        const questionCount = additionalQuestions.children.length + 2; // Starting from 2 since 1 is already there

        const questionDiv = document.createElement('div');
        questionDiv.className = 'question-container';
        questionDiv.dataset.questionNumber = questionCount;

        const questionLabel = document.createElement('label');
        questionLabel.setAttribute('for', `questionText${questionCount}`);
        questionLabel.textContent = `Pregunta:`;

        const questionInput = document.createElement('input');
        questionInput.type = 'text';
        questionInput.id = `questionText${questionCount}`;
        questionInput.name = `questionText${questionCount}`;
        questionInput.required = true;

        const deleteButton = document.createElement('button');
        deleteButton.type = 'button';
        deleteButton.className = 'btn btn-danger';
        deleteButton.textContent = 'Eliminar Pregunta';
        deleteButton.onclick = function() {
            eliminarPregunta(deleteButton);
        };

        const answerContainer = document.createElement('div');
        answerContainer.className = 'answer-container';

        const answers = ['A', 'B', 'C', 'D'];
        answers.forEach(answer => {
            const answerOption = document.createElement('div');
            answerOption.className = 'answer-option';

            const answerLabel = document.createElement('label');
            answerLabel.setAttribute('for', `answer${answer}${questionCount}`);
            answerLabel.textContent = `${answer}.`;

            const answerInput = document.createElement('input');
            answerInput.type = 'text';
            answerInput.id = `answer${answer}${questionCount}`;
            answerInput.name = `answer${answer}${questionCount}`;
            answerInput.required = true;

            const correctAnswerInput = document.createElement('input');
            correctAnswerInput.type = 'radio';
            correctAnswerInput.id = `correctAnswer${answer}${questionCount}`;
            correctAnswerInput.name = `correctAnswer${questionCount}`;
            correctAnswerInput.value = answer;

            answerOption.appendChild(answerLabel);
            answerOption.appendChild(answerInput);
            answerOption.appendChild(correctAnswerInput);
            answerContainer.appendChild(answerOption);
        });

        questionDiv.appendChild(questionLabel);
        questionDiv.appendChild(questionInput);
        if (questionCount > 1) {
            questionDiv.appendChild(deleteButton);
        }
        questionDiv.appendChild(answerContainer);
        additionalQuestions.appendChild(questionDiv);

        renumerarPreguntas();
    }

    function eliminarPregunta(button) {
        const questionDiv = button.closest('.question-container');
        questionDiv.remove();
        renumerarPreguntas();
        toggleEliminarPrimeraPregunta();
    }

    function toggleEliminarPrimeraPregunta() {
        const firstQuestionDeleteButton = document.querySelector('.question-container[data-question-number="1"] button');
        if (firstQuestionDeleteButton) {
            firstQuestionDeleteButton.style.display = document.querySelectorAll('.question-container').length > 1 ? 'inline-block' : 'none';
        }
    }

    function renumerarPreguntas() {
        const questions = document.querySelectorAll('.question-container');
        questions.forEach((question, index) => {
            const questionNumber = index + 1;
            const questionLabel = question.querySelector('label');
            const questionInput = question.querySelector('input[type="text"]');
            const deleteButton = question.querySelector('button');

            question.dataset.questionNumber = questionNumber;
            questionLabel.setAttribute('for', `questionText${questionNumber}`);
            questionLabel.textContent = `Pregunta ${questionNumber}:`;
            questionInput.id = `questionText${questionNumber}`;
            questionInput.name = `questionText${questionNumber}`;

            deleteButton.onclick = function() {
                eliminarPregunta(deleteButton);
            };

            const answerOptions = question.querySelectorAll('.answer-option');
            answerOptions.forEach((option, idx) => {
                const answerLabel = option.querySelector('label');
                const answerInput = option.querySelector('input[type="text"]');
                const correctAnswerInput = option.querySelector('input[type="radio"]');
                const answerLetter = ['A', 'B', 'C', 'D'][idx];

                answerLabel.setAttribute('for', `answer${answerLetter}${questionNumber}`);
                answerInput.id = `answer${answerLetter}${questionNumber}`;
                answerInput.name = `answer${answerLetter}${questionNumber}`;
                correctAnswerInput.id = `correctAnswer${answerLetter}${questionNumber}`;
                correctAnswerInput.name = `correctAnswer${questionNumber}`;
            });
        });
    }

    // Inicialmente ocultar el botón de eliminar para la primera pregunta
    document.addEventListener('DOMContentLoaded', toggleEliminarPrimeraPregunta);
</script>

<script>
            document.addEventListener('DOMContentLoaded', function() {
                const crearRadio = document.getElementById('crear');
                const verRadio = document.getElementById('ver');
                const crearPanel = document.getElementById('crearCuestionario');
                const verPanel = document.getElementById('verCuestionarios');

                crearRadio.addEventListener('change', function() {
                    if (crearRadio.checked) {
                        crearPanel.style.display = 'block';
                        verPanel.style.display = 'none';
                    }
                });

                verRadio.addEventListener('change', function() {
                    if (verRadio.checked) {
                        crearPanel.style.display = 'none';
                        verPanel.style.display = 'block';
                    }
                });
            });

    // Inicialmente ocultar el botón de eliminar para la primera pregunta
    document.addEventListener('DOMContentLoaded', function() {
        const firstQuestionDeleteButton = document.querySelector('.question-container[data-question-number="1"] button');
        if (firstQuestionDeleteButton) {
            firstQuestionDeleteButton.style.display = 'none';
        }
    });

    document.addEventListener('DOMContentLoaded', function() {
    const compartirRadio = document.getElementById('compartir');
    const verRecursosRadio = document.getElementById('verRecursos');
    const compartirPanel = document.getElementById('compartirRecursos');
    const verPanel = document.getElementById('verRecurso');

    compartirRadio.addEventListener('change', function() {
        if (compartirRadio.checked) {
            compartirPanel.style.display = 'block';
            verPanel.style.display = 'none';
        }
    });

    verRecursosRadio.addEventListener('change', function() {
        if (verRecursosRadio.checked) {
            compartirPanel.style.display = 'none';
            verPanel.style.display = 'block';
        }
    });

    // Inicialmente ocultar el contenido de ver recursos
    compartirPanel.style.display = 'block';
    });

    function showContent(option) {
                var contents = document.getElementsByClassName('content');
                for (var i = 0; i < contents.length; i++) {
                    contents[i].style.display = 'none';
                }
                document.getElementById(option).style.display = 'block';
            }
</script>

<script>
// Función para redirigir y generar PDF
function generarPDF(tipo, id, nombre_profesor, nombre_grupo) {
    // Codificar parámetros para incluirlos en la URL
    nombre_profesor = encodeURIComponent(nombre_profesor);
    nombre_grupo = encodeURIComponent(nombre_grupo);

    // Redirigir a la página del script PHP que genera el PDF
    window.location.href = `generar_pdf.php?tipo=${tipo}&id=${id}&nombre_profesor=${nombre_profesor}&nombre_grupo=${nombre_grupo}`;
}
</script>
<script>
// Función para redirigir y generar PDF
function generatePDF(tipo, id, nombre_profesor, nombre_tarea, nombre_grupo) {
    // Codificar parámetros para incluirlos en la URL
    nombre_profesor = encodeURIComponent(nombre_profesor);
    nombre_tarea = encodeURIComponent(nombre_tarea);
    nombre_grupo = encodeURIComponent(nombre_grupo);

    // Redirigir a la página del script PHP que genera el PDF
    window.location.href = `generar_pdf_tarea.php?tipo=${tipo}&id=${id}&nombre_profesor=${nombre_profesor}&nombre_tarea=${nombre_tarea}&nombre_grupo=${nombre_grupo}`;
}
</script>

        <?php
        $conexion->close();
        ?>
    </body>
    </html>
    <?php
} else {
    echo '<script>
    alert("Acceso no autorizado.");
    window.location.href = "../../index.php";
  </script>';
}
?>

