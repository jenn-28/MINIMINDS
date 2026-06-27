<?php
session_start();
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

// Verifica si se ha enviado una solicitud para eliminar la clase
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["delete_class_id"])) {
    $delete_class_id = intval($_POST["delete_class_id"]);

    // Log del ID recibido
    error_log("ID de la clase a eliminar: " . $delete_class_id);

    // Validar que el ID de la clase sea un entero positivo antes de usarlo en consultas SQL
    if ($delete_class_id <= 0) {
        mostrarNotificacion("error", "Error", "ID de clase inválido.", "../inicioProfesor.php");
    } else {
        // Iniciar transacción
        $conexion->begin_transaction();
        error_log("Transacción iniciada para eliminar la clase con ID: " . $delete_class_id);

        try {
            // Primero limpiar el campo grupo en la tabla students
            $get_group_name_sql = "SELECT group_name FROM groups WHERE group_id = ?";
            $stmt = $conexion->prepare($get_group_name_sql);
            $stmt->bind_param("i", $delete_class_id);
            $stmt->execute();
            $group_name_result = $stmt->get_result();

            if ($group_name_result && $group_name_result->num_rows > 0) {
                $row = $group_name_result->fetch_assoc();
                $group_name = $row["group_name"];
                error_log("Nombre del grupo obtenido: " . $group_name);

                // Actualizar el campo grupo en la tabla students
                $clear_group_sql = "UPDATE students SET grupo = '' WHERE grupo = ?";
                $stmt = $conexion->prepare($clear_group_sql);
                $stmt->bind_param("s", $group_name);
                $stmt->execute();
                error_log("Campo grupo en la tabla students limpiado");

                // Eliminar interacciones de los estudiantes con las tareas de esta clase
                $delete_interactions_sql = "DELETE FROM student_interactions WHERE task_id IN (SELECT task_id FROM tasks WHERE class_id = ?)";
                $stmt = $conexion->prepare($delete_interactions_sql);
                $stmt->bind_param("i", $delete_class_id);
                $stmt->execute();
                error_log("Interacciones de estudiantes eliminadas");

                // Eliminar tareas asociadas a esta clase
                $delete_tasks_sql = "DELETE FROM tasks WHERE class_id = ?";
                $stmt = $conexion->prepare($delete_tasks_sql);
                $stmt->bind_param("i", $delete_class_id);
                $stmt->execute();
                error_log("Tareas asociadas eliminadas");

                // Eliminar recursos asociados a este grupo
                $delete_resources_sql = "DELETE FROM recursos WHERE grupo = ?";
                $stmt = $conexion->prepare($delete_resources_sql);
                $stmt->bind_param("i", $delete_class_id);
                $stmt->execute();
                error_log("Recursos asociados eliminados");

                // Finalmente, eliminar la clase de la tabla groups
                $delete_group_sql = "DELETE FROM groups WHERE group_id = ?";
                $stmt = $conexion->prepare($delete_group_sql);
                $stmt->bind_param("i", $delete_class_id);
                $stmt->execute();
                error_log("Clase eliminada de la tabla groups");

                // Confirmar la transacción
                $conexion->commit();
                mostrarNotificacion("success", "Exito!", "La clase se elimino correctamente", "../inicioProfesor.php");
            } else {
                mostrarNotificacion("error", "Error", "No se encontró la clase con el ID proporcionado.", "../inicioProfesor.php");
            }
        } catch (Exception $e) {
            // Revertir la transacción en caso de error
            $conexion->rollback();
            mostrarNotificacion("error", "Error", "Error al eliminar la clase y sus elementos asociados", "../inicioProfesor.php");
        }
    }
} else {
    mostrarNotificacion("error", "Error", "No se recibió el ID de la clase para eliminar", "../inicioProfesor.php");
}
?>

