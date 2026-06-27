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

// Verifica si se ha enviado una solicitud para eliminar la actividad
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["delete_activity_id"])) {
    $delete_activity_id = $_POST["delete_activity_id"];

    // Eliminar interacciones de los estudiantes con la tarea
    $delete_interactions_sql = "DELETE FROM student_interactions WHERE task_id = ?";
    $stmt = $conexion->prepare($delete_interactions_sql);
    $stmt->bind_param("i", $delete_activity_id);
    if ($stmt->execute()) {
        // Obtener el nombre del archivo JSON correspondiente al juego de la actividad eliminada
        $get_config_file_sql = "SELECT configuracion FROM tasks WHERE task_id = ?";
        $stmt = $conexion->prepare($get_config_file_sql);
        $stmt->bind_param("i", $delete_activity_id);
        if ($stmt->execute()) {
            $config_file_result = $stmt->get_result();
            if ($config_file_result && $config_file_result->num_rows > 0) {
                $config_row = $config_file_result->fetch_assoc();
                $config_file = $config_row["configuracion"];
                $config_file_ruta = "actividades/$config_file";
                // Leer el contenido del archivo JSON
                $config_json = file_get_contents($config_file_ruta);
                if ($config_json !== false) {
                    // Decodificar el contenido del archivo JSON
                    $config_data = json_decode($config_json, true);

                    // Buscar y eliminar la configuración correspondiente al task_id
                    foreach ($config_data as $key => $value) {
                        if ($value['task_id'] == $delete_activity_id) {
                            unset($config_data[$key]); // Eliminar la configuración encontrada
                            break; // Salir del bucle después de eliminar la configuración
                        }
                    }

                    // Codificar la configuración actualizada
                    $updated_config_json = json_encode(array_values($config_data)); // Reindexar el arreglo

                    // Escribir la configuración actualizada en el archivo JSON
                    if (file_put_contents($config_file_ruta, $updated_config_json) !== false) {
                        // Eliminar el registro de la actividad de la base de datos
                        $delete_activity_sql = "DELETE FROM tasks WHERE task_id = ?";
                        $stmt_delete = $conexion->prepare($delete_activity_sql);
                        $stmt_delete->bind_param("i", $delete_activity_id);
                        if ($stmt_delete->execute()) {
                            mostrarNotificacion("success", "Tarea Eliminada!", "La Tarea ha sido eliminada correctamente", "../inicioProfesor.php");
                        } else {
                            mostrarNotificacion("error", "Error", "Error al eliminar la tarea de la base de datos: " . $stmt_delete->error, "../inicioProfesor.php");
                        }
                        $stmt_delete->close();
                    } else {
                        mostrarNotificacion("error", "Error", "Error al actualizar el archivo de configuración.", "../inicioProfesor.php");
                    }
                } else {
                    // Eliminar interacciones de los estudiantes con la actividad
                    $delete_interactions_sql = "DELETE FROM student_interactions WHERE task_id = ?";
                    $stmt = $conexion->prepare($delete_interactions_sql);
                    $stmt->bind_param("i", $delete_activity_id);
                    if ($stmt->execute()) {
                        // Eliminar la actividad de la base de datos
                        $delete_activity_sql = "DELETE FROM tasks WHERE task_id = ?";
                        $stmt_delete = $conexion->prepare($delete_activity_sql);
                        $stmt_delete->bind_param("i", $delete_activity_id);
                        if ($stmt_delete->execute()) {
                            // La actividad y las interacciones se eliminaron correctamente
                            echo json_encode(array("success" => true));
                            mostrarNotificacion("success", "Tarea Eliminada!", "La Tarea ha sido eliminada correctamente", "../inicioProfesor.php");

                        } else {
                            // Error al eliminar la actividad de la base de datos
                            mostrarNotificacion("error", "Error", "Error al eliminar la tarea de la base de datos: " . $stmt_delete->error, "../inicioProfesor.php");
                        }
                        $stmt_delete->close();
                    } else {
                        // Error al eliminar las interacciones de los estudiantes
                        mostrarNotificacion("error", "Error", "Error al eliminar las interacciones de los estudiantes: " . $stmt->error, "../inicioProfesor.php");
                    }
                    $stmt->close();
                }
            } else {
                mostrarNotificacion("error", "Error", "No se encontró el archivo de configuración para la actividad.", "../inicioProfesor.php");
            }
        } else {
            mostrarNotificacion("error", "Error", "Error al obtener el archivo de configuración: " . $stmt->error, "../inicioProfesor.php");
        }
        $stmt->close();
    } else {
        mostrarNotificacion("error", "Error", "Error al eliminar las interacciones de los estudiantes: " . $stmt->error, "../inicioProfesor.php");
    }
} else {
    mostrarNotificacion("error", "Error", "No se recibió una solicitud válida para eliminar la actividad.", "../inicioProfesor.php");
}
?>
