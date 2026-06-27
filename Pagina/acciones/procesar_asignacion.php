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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $task_name = $_POST['task_name'];
    $task_description = $_POST['task_description'];
    $expiry_datetime = $_POST['expiry_datetime'];
    $quiz_id = $_POST['quiz_id'];
    $groups = $_POST['groups'];
    $configuracion = 'c_'.$quiz_id;
    $id_profesor = $_SESSION['username'];

    // Preparar la consulta para verificar si ya existe un registro
    $check_query = "SELECT COUNT(*) AS count FROM tasks WHERE class_id = ? AND configuracion = ?";
    $stmt_check = $conexion->prepare($check_query);
    $stmt_check->bind_param("is", $group_id, $configuracion);

    // Consulta para insertar la tarea
    $insert_query = "INSERT INTO tasks (class_id, task_name, descripcion, juego_id, created_by, configuracion, fecha_creacion, fecha_expiracion) VALUES (?, ?, ?, NULL, ?, ?, CURRENT_TIMESTAMP(), ?)";
    $stmt_insert = $conexion->prepare($insert_query);
    $stmt_insert->bind_param("ssssss", $group_id, $task_name, $task_description, $id_profesor, $configuracion, $expiry_datetime); // Corregido el orden de los parámetros

    foreach ($groups as $group_id) {
        // Ejecutar la consulta de verificación
        $stmt_check->bind_param("is", $group_id, $configuracion);
        $stmt_check->execute();
        $result_check = $stmt_check->get_result();
        $row_check = $result_check->fetch_assoc();
        $assignment_count = $row_check['count'];

        if ($assignment_count == 0) { // Si no se ha asignado antes, insertar
            $stmt_insert->execute();
            if (!$stmt_insert) {
                die('Error al insertar tarea: ' . $conexion->error);
            }
        } else {
          mostrarNotificacion("warning", "Oops..!", "El cuestionario ya ha sido asignado anteriormente a esta clase.", "index.php");
            exit;
        }
    }

    // Cerrar recursos
    $stmt_check->close();
    $stmt_insert->close();
    mostrarNotificacion("success", "Cuestionario Asignado!", "El cuestionario ha sido asignado correctamente", "../inicioProfesor.php");
} else {
  mostrarNotificacion("error", "Error", "Acceso denegado", "../../index.php");
    exit;
}
?>
