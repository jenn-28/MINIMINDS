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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['quiz_id'])) {
        $quiz_id = intval($_POST['quiz_id']);

        // Iniciar una transacción
        $conexion->begin_transaction();

        try {
            // Obtener el task_id correspondiente al cuestionario
            $query_task_id = "SELECT task_id FROM tasks WHERE configuracion = ?";
            $stmt_task_id = $conexion->prepare($query_task_id);
            $stmt_task_id->bind_param("s", $configuracion);
            $configuracion = "c_" . $quiz_id;
            $stmt_task_id->execute();
            $stmt_task_id->bind_result($task_id);
            $stmt_task_id->fetch();
            $stmt_task_id->close();

            // Eliminar las interacciones de los estudiantes relacionadas con el task_id
            $delete_student_interactions_query = "DELETE FROM student_interactions WHERE task_id = ?";
            $stmt_delete_student_interactions = $conexion->prepare($delete_student_interactions_query);
            $stmt_delete_student_interactions->bind_param("i", $task_id);
            $stmt_delete_student_interactions->execute();

            // Eliminar las preguntas relacionadas con el cuestionario
            $delete_quiz_questions_query = "DELETE FROM quiz_questions WHERE quiz_id = ?";
            $stmt_delete_quiz_questions = $conexion->prepare($delete_quiz_questions_query);
            $stmt_delete_quiz_questions->bind_param("i", $quiz_id);
            $stmt_delete_quiz_questions->execute();

            // Eliminar el cuestionario
            $delete_quiz_query = "DELETE FROM quizzes WHERE quiz_id = ?";
            $stmt_delete_quiz = $conexion->prepare($delete_quiz_query);
            $stmt_delete_quiz->bind_param("i", $quiz_id);
            $stmt_delete_quiz->execute();

            // Eliminar los registros en la tabla task
            $delete_task_query = "DELETE FROM tasks WHERE configuracion = ?";
            $stmt_delete_task = $conexion->prepare($delete_task_query);
            $stmt_delete_task->bind_param("s", $configuracion);
            $stmt_delete_task->execute();

            // Confirmar la transacción
            $conexion->commit();

            mostrarNotificacion("success", "Éxito", "El cuestionario ha sido eliminado correctamente", "index.php");
        } catch (Exception $e) {
            // Revertir la transacción si algo falla
            $conexion->rollback();
            mostrarNotificacion("error", "Error", "Error al eliminar el cuestionario", "index.php");
        }

        // Cerrar las declaraciones
        $stmt_delete_student_interactions->close();
        $stmt_delete_quiz_questions->close();
        $stmt_delete_quiz->close();
        $stmt_delete_task->close();
    } else {
        mostrarNotificacion("error", "Error", "ID del cuestionario no proporcionado", "index.php");
    }
} else {
    mostrarNotificacion("error", "Error", "Método de solicitud no válido.", "IniciarSesion.php");
}
?>
