<?php
session_start(); // Inicia la sesión de PHP

// Incluye el archivo de conexión a la base de datos
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

// Verifica si el usuario tiene una sesión activa y es el profesor de la clase
if (!isset($_SESSION["username"]) || !isset($_GET["task_id"])) {
    // Redirecciona a la página de inicio de sesión o muestra un mensaje de error si no hay una sesión activa o falta el parámetro task_id
    header("Location: ../inicioProfesor.php");
    exit(); // Finaliza el script
}

$username = $_SESSION["username"]; // Obtiene el nombre de usuario de la sesión
$task_id = $_GET["task_id"]; // Obtiene el ID de la tarea de la URL

// Verifica si el usuario es el profesor de la clase asociada a la tarea
$query = "SELECT tasks.task_id 
          FROM tasks 
          INNER JOIN groups ON tasks.class_id = groups.group_id 
          WHERE tasks.task_id = $task_id AND groups.created_by = '$username'";
$result = mysqli_query($conexion, $query);

if (mysqli_num_rows($result) === 0) {
    // Si el usuario no es el profesor de la clase asociada a la tarea, redirecciona
    header("Location: ../inicioProfesor.php");
    exit(); // Finaliza el script
}

// Obtiene la información de la tarea
$query = "SELECT * FROM tasks WHERE task_id = $task_id";
$result = mysqli_query($conexion, $query);

if (!$result) {
    mostrarNotificacion("error", "Error", "Error al obtener la información de la tarea. Intentalo mas tarde.", "../inicioProfesor.php");
    exit(); // Finaliza el script
}

$row = mysqli_fetch_assoc($result); // Obtiene la fila de datos de la tarea
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Miniminds</title>
    <link rel="icon" href="../../imagenes/LogoS.png">
    <!-- Enlace a Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap');
        body{
            background-color: #DAF9FF;
            font-family: "Poppins", sans-serif;
        }

        h2{
            font-weight: 800;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Editar Tarea</h2>
        <form action="control_editarTarea.php" method="POST">
            <!-- Campo oculto para almacenar el ID de la tarea -->
            <input type="hidden" name="task_id" value="<?php echo $task_id; ?>">

            <div class="form-group">
                <label for="task_name">Nombre de la Tarea:</label>
                <input type="text" class="form-control" id="task_name" name="task_name" value="<?php echo $row['task_name']; ?>" required>
            </div>
            <div class="form-group">
                <label for="description">Descripción de la Tarea:</label>
                <textarea class="form-control" id="description" name="description" rows="3" required><?php echo $row['descripcion']; ?></textarea>
            </div>
            <div class="form-group">
                <label for="due_date">Fecha de Vencimiento:</label>
                <input type="datetime-local" class="form-control" id="due_date" name="due_date" value="<?php echo date('Y-m-d\TH:i', strtotime($row['fecha_expiracion'])); ?>" required>
            </div>
            <button type="submit" class="btn btn-primary">Guardar Cambios</button>
            <br><br>
        </form>
        <div class="footer-btn" style="float: right;">
            <a class="btn btn-danger" href="../inicioProfesor.php">Cancelar</a>
        </div>
    </div>

    <!-- Enlace a jQuery y Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
