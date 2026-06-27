<?php
session_start();
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
include("../../Logeo/conexion.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $task_id = $_POST["task_id"];
    $task_name = $_POST["task_name"];
    $description = $_POST["description"];
    $due_date = $_POST["due_date"];

    // Actualizar los datos de la tarea en la base de datos
    $query = "UPDATE tasks 
              SET task_name = '$task_name', descripcion = '$description', fecha_expiracion = '$due_date' 
              WHERE task_id = $task_id";
    $result = mysqli_query($conexion, $query);

    if ($result) {
        mostrarNotificacion("success", "Exito!", "Tarea Editada Exitosamente", "../inicioProfesor.php");
    } else {
        mostrarNotificacion("error", "Error", "Error al editar la tarea. Intentalo mas tarde.", "IniciarSesion.php");
    }

    // Cerrar la conexión a la base de datos
    mysqli_close($conexion);
}
?>
