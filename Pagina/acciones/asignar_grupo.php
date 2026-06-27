<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

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

if (isset($_GET['quiz_id'])) {
    $quiz_id = intval($_GET['quiz_id']);

    // Obtener la información del cuestionario
    $query_quiz = "SELECT quiz_name, descrip FROM quizzes WHERE quiz_id = ?";
    $stmt_quiz = $conexion->prepare($query_quiz);
    if (!$stmt_quiz) {
        die('Error en la preparación de la consulta: ' . $conexion->error);
    }
    $stmt_quiz->bind_param("i", $quiz_id);
    $stmt_quiz->execute();
    $result_quiz = $stmt_quiz->get_result();
    if (!$result_quiz) {
        die('Error en la ejecución de la consulta: ' . $stmt_quiz->error);
    }
    $quiz_info = $result_quiz->fetch_assoc();

    if ($quiz_info) {
        $quiz_name = htmlspecialchars($quiz_info['quiz_name']);
        $quiz_description = htmlspecialchars($quiz_info['descrip']);

        // Obtener los grupos del profesor con la sesión activa
        $id_profesor = $_SESSION['username'];
        $query_grupos = "SELECT group_id, group_name FROM groups WHERE created_by = ?";
        $stmt_grupos = $conexion->prepare($query_grupos);
        if (!$stmt_grupos) {
            die('Error en la preparación de la consulta: ' . $conexion->error);
        }
        $stmt_grupos->bind_param("s", $id_profesor); // Aquí cambia "i" por "s" para el tipo de dato del profesor
        $stmt_grupos->execute();
        $result_grupos = $stmt_grupos->get_result();
        if (!$result_grupos) {
            die('Error en la ejecución de la consulta: ' . $stmt_grupos->error);
        }
        ?>
        <!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Miniminds</title>
    <link rel="icon" href="../../imagenes/LogoS.png" >

    <!-- Enlace a Bootstrap CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap');
        body{
            background-color: #DAF9FF;
            font-family: "Poppins", sans-serif;
        }

        h1{
            font-weight: 800;
        }
       
    </style>
</head>
<body>
    <div class="container">
        <h1 class="mt-5 mb-4">Asignar Cuestionario a Grupos</h1>
        <form action="procesar_asignacion.php" method="post">
            <div class="mb-3">
                <label for="task_name" class="form-label">Nombre de la Tarea:</label>
                <input type="text" class="form-control" id="task_name" name="task_name" value="<?php echo $quiz_name; ?>" required readonly>
            </div>

            <div class="mb-3">
                <label for="task_description" class="form-label">Descripción:</label>
                <input type="text" class="form-control" id="task_description" name="task_description" value="<?php echo $quiz_description; ?>" required readonly>
            </div>

            <div class="mb-3">
                <label for="expiry_datetime" class="form-label">Fecha de Expiración:</label>
                <input type="datetime-local" class="form-control" id="expiry_datetime" name="expiry_datetime" required>
            </div>


            <div class="mb-3">
                <label class="form-label">Seleccione los grupos:</label><br>
                <?php
                while ($row = $result_grupos->fetch_assoc()) {
                    echo "<div class='form-check'>";
                    echo "<input class='form-check-input' type='checkbox' name='groups[]' value='" . $row['group_id'] . "' id='group_" . $row['group_id'] . "'>";
                    echo "<label class='form-check-label' for='group_" . $row['group_id'] . "'>" . htmlspecialchars($row['group_name']) . "</label>";
                    echo "</div>";
                }
                ?>
            </div>
            <input type="hidden" name="quiz_id" value="<?php echo $quiz_id; ?>">
            <button type="submit" class="btn btn-primary">Asignar Cuestionario</button>
            <button type="button" class="btn btn-secondary" onclick="window.location.href='index.php'">Cancelar</button>
        </form>
    </div>

    <!-- Script de Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

        <?php
    } else {
        mostrarNotificacion("error", "Error", "Cuestionario no encontrado", "index.php");
        exit;
    }

} else {
    mostrarNotificacion("error", "Error", "ID del cuestionario no proporcionado", "index.php");
    exit;
}
?>
