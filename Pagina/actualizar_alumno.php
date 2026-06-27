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
// Verificar si el usuario está autenticado, de lo contrario, redirigirlo a la página de inicio de sesión
if (!isset($_SESSION["username"])) {
    header("Location: ../Logeo/IniciarSesion.php");
    exit();
}

include("../Logeo/conexion.php");

// Obtener el nombre de usuario de la sesión
$username = mysqli_real_escape_string($conexion, $_SESSION["username"]);

// Verificar si se enviaron datos del formulario de actualización
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recoger los datos del formulario
    $nombre_S = $_POST['nombre_S'];
    $correo_S = $_POST['correo_S'];
    $tutor = $_POST['tutor_S'];
    $telefono = $_POST['telefono_S'];

    // Verificar si el correo electrónico ya está en uso
    $consulta_correo = mysqli_query($conexion, "SELECT * FROM users WHERE email = '$correo_S' AND nombre_completo != '$username'");
    if (mysqli_num_rows($consulta_correo) > 0) {
        mostrarNotificacion("warning", "Oops..!", "El correo electrónico ya está en uso. Por favor, elija otro.", "editarPerfil.php");
    } else {
        if (isset($_FILES['p_picture_S']) && $_FILES['p_picture_S']['error'] == UPLOAD_ERR_OK) {
            $p_picture = $_FILES['p_picture_S'];
            // Guardar imagen en una carpeta en el servidor
            $nombre_imagen = $p_picture['name'];
            $ruta_imagen = '../Logeo/foto_perfil/' . $nombre_imagen;

            // Mueve la imagen al directorio destino
            if (!move_uploaded_file($p_picture['tmp_name'], $ruta_imagen)) {
                mostrarNotificacion("error", "Error", "Error al subir la imagen. Intentalo mas tarde.", "editarPerfil.php");
            }
        } else {
            // Obtener la imagen actual del usuario
            $consulta_img = mysqli_query($conexion, "SELECT profile_picture FROM users WHERE nombre_completo = '$username'");
            if (mysqli_num_rows($consulta_img) > 0) {
                $row = mysqli_fetch_assoc($consulta_img);
                $nombre_imagen = $row['profile_picture'];
            } else {
                $nombre_imagen = 'default.png';
            }
        }

        // Construir la consulta de actualización para la tabla students
        $consulta_actualizacion = "UPDATE `students` SET `profile_picture`='$nombre_imagen', `nombre_completo`='$nombre_S', `email`='$correo_S', `nombre_tutor`='$tutor', `telefono_tutor`='$telefono' WHERE `nombre_completo` = '" . $_SESSION['username'] . "'";

        // Ejecutar la consulta de actualización para la tabla students
        if (!mysqli_query($conexion, $consulta_actualizacion)) {
            // Manejar errores
            mostrarNotificacion("error", "Error", "Error al actualizar los datos del alumno.Intentalo mas tarde.", "inicioAlumno.php");
        }

        // Construir la consulta de actualización para la tabla users
        $consulta_actualizacion_users = "UPDATE `users` SET `nombre_completo` = '$nombre_S', `email` = '$correo_S', `profile_picture` = '$nombre_imagen' WHERE `nombre_completo` = '" . $_SESSION['username'] . "'";

        // Ejecutar la consulta de actualización para la tabla users
        if (!mysqli_query($conexion, $consulta_actualizacion_users)) {
            // Manejar errores
            mostrarNotificacion("error", "Error", "Error al actualizar los datos del usuario. Intentalo mas tarde.", "inicioAlumno.php");
        }

        // Redirigir a la página de perfil del alumno actualizado
        header("Location: editarPerfil.php");
        exit();
    }
} else {
    mostrarNotificacion("warning", "Faltan Datos!", "Por favor complete el formulario", "editarPerfil.php");
}
?>
