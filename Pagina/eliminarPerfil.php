<?php
session_start(); // Iniciar la sesión al principio del archivo

include("../Logeo/conexion.php"); // Incluir el archivo de conexión a la base de datos

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

// Verificar si hay una sesión activa
if (!isset($_SESSION["username"])) {
    header("Location: ../Logeo/IniciarSesion.php"); // Redirigir a la página de inicio de sesión si no hay sesión activa
    exit();
}

// Obtener el nombre de usuario de la sesión
$username_session = $_SESSION["username"];

// Verificar si se envió el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Verificar si todos los campos necesarios están presentes
    if (isset($_POST['password']) && isset($_POST['id_usuario']) && isset($_POST['name_usuario'])) {
        // Obtener los datos del formulario
        $password = $_POST['password'];
        $id_usuario = $_POST['id_usuario'];
        $name_usuario = $_POST['name_usuario'];

        // Consulta preparada para verificar la identidad del usuario
        $query = "SELECT * FROM `users` WHERE id = ? AND nombre_completo = ? AND password = ?";
        $stmt = mysqli_prepare($conexion, $query);
        mysqli_stmt_bind_param($stmt, "iss", $id_usuario, $name_usuario, $password);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if ($row = mysqli_fetch_assoc($result)) {
            $idU = $row['id'];
            $nameU = $row['nombre_completo'];
            $rol = $row['role'];

            if ($rol == 'student') {
                try {
                    mysqli_begin_transaction($conexion); // Iniciar la transacción

                    // 1. Preparación de la consulta para obtener el grupo del estudiante
                    $consulta_nombre_grupo = "SELECT grupo FROM students WHERE id = ? AND nombre_completo = ?";
                    $stmt_grupo = mysqli_prepare($conexion, $consulta_nombre_grupo);
                    mysqli_stmt_bind_param($stmt_grupo, "is", $idU, $nameU);
                    mysqli_stmt_execute($stmt_grupo);
                    $result_grupo = mysqli_stmt_get_result($stmt_grupo);
            
                    if ($row_grupo = mysqli_fetch_assoc($result_grupo)) {
                        $group_estudent = $row_grupo['grupo'];
                    }
                    mysqli_stmt_close($stmt_grupo);
            
                    // 2. Eliminar las interacciones del estudiante
                    $delete_interaction_e = "DELETE FROM student_interactions WHERE student_id = ?";
                    $stmt_delete_interactions = mysqli_prepare($conexion, $delete_interaction_e);
                    mysqli_stmt_bind_param($stmt_delete_interactions, "i", $idU);
                    mysqli_stmt_execute($stmt_delete_interactions);
                    mysqli_stmt_close($stmt_delete_interactions);
            
                    // 3. Limpiar el grupo del estudiante
                    $clear_group_sql = "UPDATE students SET grupo = '' WHERE grupo = ? AND id = ?";
                    $stmt_clear_group = mysqli_prepare($conexion, $clear_group_sql);
                    mysqli_stmt_bind_param($stmt_clear_group, "si", $group_estudent, $idU);
                    mysqli_stmt_execute($stmt_clear_group);
                    mysqli_stmt_close($stmt_clear_group);
            
                    // 4. Ejecutar la consulta para contar los estudiantes en el grupo
                    $sql_count_students = "SELECT COUNT(*) AS student_count FROM students WHERE grupo = ?";
                    $stmt_count_students = mysqli_prepare($conexion, $sql_count_students);
                    mysqli_stmt_bind_param($stmt_count_students, "s", $group_estudent);
                    mysqli_stmt_execute($stmt_count_students);
                    $result_count_students = mysqli_stmt_get_result($stmt_count_students);
            
                    if ($result_count_students) {
                        $row_count_students = mysqli_fetch_assoc($result_count_students);
                        $student_count = $row_count_students['student_count'];
                        mysqli_stmt_close($stmt_count_students);

                        // 5. Actualizar el contador de estudiantes en el grupo
                        $sql_update_group = "UPDATE groups SET student_count = ? WHERE group_name = ?";
                        $stmt_update_group = mysqli_prepare($conexion, $sql_update_group);
                        mysqli_stmt_bind_param($stmt_update_group, "is", $student_count, $group_estudent);
                        $update_group_success = mysqli_stmt_execute($stmt_update_group);
                        mysqli_stmt_close($stmt_update_group);

                        if ($update_group_success) {
                            // 6. Eliminar al estudiante de la tabla students
                            $delete_student = "DELETE FROM students WHERE id = ? AND nombre_completo = ?";
                            $stmt_delete_student = mysqli_prepare($conexion, $delete_student);
                            mysqli_stmt_bind_param($stmt_delete_student, "is", $idU, $nameU);
                            mysqli_stmt_execute($stmt_delete_student);
                            mysqli_stmt_close($stmt_delete_student);
            
                            // 7. Eliminar al estudiante de la tabla users
                            $delete_user_e = "DELETE FROM users WHERE id = ? AND nombre_completo = ?";
                            $stmt_delete_user = mysqli_prepare($conexion, $delete_user_e);
                            mysqli_stmt_bind_param($stmt_delete_user, "is", $idU, $nameU);
                            mysqli_stmt_execute($stmt_delete_user);
                            mysqli_stmt_close($stmt_delete_user);
                        } else {
                            throw new Exception("Error al actualizar el contador de estudiantes en el grupo.");
                        }
                    } else {
                        throw new Exception("Error al ejecutar la consulta para contar estudiantes.");
                    }
            
                    // Confirmar la transacción
                    mysqli_commit($conexion);
            
                    // Cerrar la sesión y redirigir al usuario a la página de inicio con un mensaje de eliminación exitosa
                    session_destroy();
                    header("Location: ../Logeo/IniciarSesion.php?delete_success=1");
                    exit();
                } catch (Exception $e) {
                    // En caso de error, revertir la transacción
                    mysqli_rollback($conexion);
                    echo "Error: " . $e->getMessage();
                }
            } elseif ($rol == 'teacher') {
                try {
                    mysqli_begin_transaction($conexion); // Iniciar la transacción

                    // 1. Limpiar el grupo de los estudiantes que pertenecían a los grupos creados por el profesor
                    $actualizar_grupo_students = "UPDATE students SET grupo = '' WHERE grupo IN (SELECT group_name FROM groups WHERE created_by = ?)";
                    $stmt_actualizar_grupo_students = mysqli_prepare($conexion, $actualizar_grupo_students);
                    mysqli_stmt_bind_param($stmt_actualizar_grupo_students, "s", $nameU);
                    mysqli_stmt_execute($stmt_actualizar_grupo_students);
                    mysqli_stmt_close($stmt_actualizar_grupo_students);

                    // 2. Eliminar interacciones de las tareas
                    $eliminar_interacciones_taskD = "DELETE FROM `student_interactions` WHERE `task_id` IN (SELECT task_id FROM tasks WHERE created_by = ?)";
                    $stmt_interactions_taskD = mysqli_prepare($conexion, $eliminar_interacciones_taskD);
                    mysqli_stmt_bind_param($stmt_interactions_taskD, "s", $nameU);
                    mysqli_stmt_execute($stmt_interactions_taskD);
                    mysqli_stmt_close($stmt_interactions_taskD);

                    // 3. Eliminar tareas asignadas por el profesor
                    $eliminar_tareas_asignadas = "DELETE FROM tasks WHERE created_by = ?";
                    $stmt_eliminar_tareas_asignadas = mysqli_prepare($conexion, $eliminar_tareas_asignadas);
                    mysqli_stmt_bind_param($stmt_eliminar_tareas_asignadas, "s", $nameU);
                    mysqli_stmt_execute($stmt_eliminar_tareas_asignadas);
                    mysqli_stmt_close($stmt_eliminar_tareas_asignadas);

                    // 4. Eliminar grupos creados por el profesor
                    $eliminar_grupos_creados = "DELETE FROM groups WHERE created_by = ?";
                    $stmt_eliminar_grupos_creados = mysqli_prepare($conexion, $eliminar_grupos_creados);
                    mysqli_stmt_bind_param($stmt_eliminar_grupos_creados, "s", $nameU);
                    mysqli_stmt_execute($stmt_eliminar_grupos_creados);
                    mysqli_stmt_close($stmt_eliminar_grupos_creados);

                    // 5. Eliminar preguntas de quizzes creados por el profesor
                    $eliminar_preguntas_quizzes = "DELETE FROM quiz_questions WHERE quiz_id IN (SELECT quiz_id FROM quizzes WHERE teacher_id = ?)";
                    $stmt_eliminar_preguntas_quizzes = mysqli_prepare($conexion, $eliminar_preguntas_quizzes);
                    mysqli_stmt_bind_param($stmt_eliminar_preguntas_quizzes, "i", $idU);
                    mysqli_stmt_execute($stmt_eliminar_preguntas_quizzes);
                    mysqli_stmt_close($stmt_eliminar_preguntas_quizzes);

                    // 6. Eliminar quizzes creados por el profesor
                    $eliminar_quizzes_creados = "DELETE FROM quizzes WHERE teacher_id = ?";
                    $stmt_eliminar_quizzes_creados = mysqli_prepare($conexion, $eliminar_quizzes_creados);
                    mysqli_stmt_bind_param($stmt_eliminar_quizzes_creados, "i", $idU);
                    mysqli_stmt_execute($stmt_eliminar_quizzes_creados);
                    mysqli_stmt_close($stmt_eliminar_quizzes_creados);

                    // 7. Eliminar recursos subidos por el profesor
                    $eliminar_recursos_subidos = "DELETE FROM recursos WHERE shared_by = ?";
                    $stmt_eliminar_recursos_subidos = mysqli_prepare($conexion, $eliminar_recursos_subidos);
                    mysqli_stmt_bind_param($stmt_eliminar_recursos_subidos, "i", $idU);
                    mysqli_stmt_execute($stmt_eliminar_recursos_subidos);
                    mysqli_stmt_close($stmt_eliminar_recursos_subidos);

                    // 8. Eliminar archivos del servidor
                    foreach ($recursos_subidos as $recurso) {
                        if (file_exists($recurso['nombre'])) {
                            unlink($recurso['nombre']);
                        }
                    }

                    // 9. Eliminar el profesor de la tabla de profesores
                    $eliminar_profesor = "DELETE FROM teachers WHERE id = ?";
                    $stmt_eliminar_profesor = mysqli_prepare($conexion, $eliminar_profesor);
                    mysqli_stmt_bind_param($stmt_eliminar_profesor, "i", $idU);
                    mysqli_stmt_execute($stmt_eliminar_profesor);
                    mysqli_stmt_close($stmt_eliminar_profesor);

                    // 10. Eliminar el profesor de la tabla de usuarios
                    $eliminar_usuario = "DELETE FROM users WHERE id = ?";
                    $stmt_eliminar_usuario = mysqli_prepare($conexion, $eliminar_usuario);
                    mysqli_stmt_bind_param($stmt_eliminar_usuario, "i", $idU);
                    mysqli_stmt_execute($stmt_eliminar_usuario);
                    mysqli_stmt_close($stmt_eliminar_usuario);

                    // Confirmar la transacción
                    mysqli_commit($conexion);

                    // Cerrar la sesión y redirigir al usuario a la página de inicio con un mensaje de eliminación exitosa
                    session_destroy();
                    header("Location: ../Logeo/IniciarSesion.php?delete_success=1");
                    exit();
                } catch (Exception $e) {
                    // En caso de error, revertir la transacción
                    mysqli_rollback($conexion);
                    echo "Error: " . $e->getMessage();
                }
            } else {
                mostrarNotificacion("warning", "Oops..!", "No identificamos tu rol", "../index.php");
            }
        } else {
            mostrarNotificacion("error", "Error", "Contraseña incorrecta", "editarPerfil.php");
        }
    } else {
        mostrarNotificacion("warning", "Oops..!", "Faltan campos requeridos en el formulario", "editarPerfil.php");

    }
}
// Cerrar la conexión
mysqli_close($conexion);
?>