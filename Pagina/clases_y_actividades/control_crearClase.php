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


$nombre_profesor = ""; // inicializar la variable

if (isset($_SESSION["username"])) {
    $username = $_SESSION["username"];
    $consulta = "SELECT nombre_completo FROM users WHERE nombre_completo = '$username'";
    $resultado = mysqli_query($conexion, $consulta);

    if ($resultado) {
        $fila = mysqli_fetch_assoc($resultado);
        $nombre_profesor = $fila["nombre_completo"];
    }
}
// Verificar si se ha enviado el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener los datos del formulario
    $group_name = $_POST['group_name'];
    $group_code = $_POST['group_code'];
    $description = $_POST['description'];
    $selected_students = $_POST['selected_students'];

    // Calcular la cantidad de estudiantes seleccionados
    $num_students = count($selected_students);

    // Iniciar transacción
    $conexion->begin_transaction();

    // Insertar los datos del grupo en la tabla groups
    $insert_group_sql = "INSERT INTO groups (group_name, group_code, descrip, created_by, student_count) VALUES ('$group_name', '$group_code', '$description', '$nombre_profesor', $num_students)";
    if ($conexion->query($insert_group_sql) === TRUE) {
        // Obtener el ID del grupo recién creado
        $group_id = $conexion->insert_id;

        // Actualizar el campo de grupo para cada alumno seleccionado
        foreach ($selected_students as $student_id) {
            $update_student_sql = "UPDATE students SET grupo = '$group_name' WHERE id = $student_id";
            if (!$conexion->query($update_student_sql)) {
                // Si hay un error en alguna de las actualizaciones, revertir la transacción
                $conexion->rollback();
                mostrarNotificacion("error", "Error", "Error al actualizar el campo grupo para el alumno con ID $student_id Intentalo mas tarde.", "../inicioProfesor.php");
                exit();
            }
        }

        // Confirmar la transacción
        $conexion->commit();

        // Redireccionar a alguna página de éxito
        header("Location: ../inicioProfesor.php");
        exit();
    } else {
        mostrarNotificacion("error", "Error", "Error al crear el grupo.Intentalo mas tarde.", "../inicioProfesor.php");
    }

    // Cerrar conexión
    $conexion->close();
}
?>
