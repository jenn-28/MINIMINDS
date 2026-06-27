<?php
session_start();
include("../Logeo/conexion.php");
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

// Procesar el formulario si se ha enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener los datos del formulario
    $id_clase = $_POST['id_clase'];
    $user_id = $_POST['user_id'];
    
    // Consultar la tabla 'groups' para verificar si el ID de la clase coincide con el código del grupo
    $sql_group = "SELECT * FROM groups WHERE group_code = '$id_clase'";
    $result_group = $conexion->query($sql_group);

    if ($result_group->num_rows > 0) {
        // Si hay coincidencia en la tabla 'groups', actualizar la tabla 'students'
        $row_group = $result_group->fetch_assoc();
        $clase_nombre = $row_group['group_name']; // Supongamos que el nombre de la clase está en el campo 'nombre'
        
        $sql_update_students = "UPDATE students SET grupo = '$clase_nombre' WHERE id = '$user_id'";
        
        if ($conexion->query($sql_update_students) === TRUE) {
            // Actualizar el campo student_count en la tabla groups
            $sql_count_students = "SELECT COUNT(*) AS student_count FROM students WHERE grupo = '$clase_nombre'";
            $result_count_students = $conexion->query($sql_count_students);
            $row_count_students = $result_count_students->fetch_assoc();
            $student_count = $row_count_students['student_count'];

            $sql_update_group = "UPDATE groups SET student_count = '$student_count' WHERE group_code = '$id_clase'";
            $conexion->query($sql_update_group);

            mostrarNotificacion("success", "Felicidades!", "Te has inscrito en la clase $clase_nombre", "mi_grupo.php");

        } else {
            echo "Error actualizando la base de datos: " . $conexion->error;
        }
    } else {
        // Si no hay coincidencia en la tabla 'groups', mostrar una alerta
        mostrarNotificacion("error", "Error", "No hay ningún grupo con ese código", "mi_grupo.php");
    }
}

// Cerrar la conexión
$conexion->close();
?>
