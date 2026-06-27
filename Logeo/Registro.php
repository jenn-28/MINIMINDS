<?php
session_start();

 // Incluye el archivo de conexión a la base de datos
 include("conexion.php");

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


// Verifica si el formulario ha sido enviado y si los campos requeridos están presentes
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["username"]) && isset($_POST["correo"]) && isset($_POST["password"]) && isset($_POST["rol"])) {
    $username = $_POST["username"];
    $correo = $_POST["correo"];
    $password = $_POST["password"];
    $rol = $_POST["rol"];

    if($rol=="teacher"){
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["captcha_key"])){
            $key = $_POST["captcha_key"];
            $consulta_key = $conexion->prepare("SELECT * FROM `llave` WHERE `nkey` = ?");
            $consulta_key->bind_param("s", $key);
            $consulta_key->execute();
            $resultado_key = $consulta_key->get_result();
        
            if ($resultado_key->num_rows > 0) {
                 // Verifica si se ha subido alguna imagen de perfil
    if (isset($_FILES['p_picture']) && $_FILES['p_picture']['error'] == UPLOAD_ERR_OK) {
        $p_picture = $_FILES['p_picture'];
        // Guardar imagen en una carpeta en el servidor
        $nombre_imagen = $p_picture['name'];
        $ruta_imagen = '../Logeo/foto_perfil/' . $nombre_imagen;

        // Mueve la imagen al directorio destino
        if (!move_uploaded_file($p_picture['tmp_name'], $ruta_imagen)) {
            mostrarNotificacion("error", "Error", "Error al subir la imagen Intentalo mas tarde.", "IniciarSesion.php");
        }
    } else {
        // Si no se subió ninguna imagen, asigna un valor predeterminado
        $nombre_imagen = "default.png"; // imagen predeterminada
    }

    // Verifica si ya existe un usuario con el mismo nombre de usuario o correo electrónico
    $consulta_existencia = $conexion->prepare("SELECT * FROM `users` WHERE `nombre_completo` = ? OR `email` = ?");
    $consulta_existencia->bind_param("ss", $username, $correo);
    $consulta_existencia->execute();
    $resultado_existencia = $consulta_existencia->get_result();

    if ($resultado_existencia->num_rows > 0) {
        mostrarNotificacion("error", "Error", "Ya existe una cuenta con ese nombre y/o correo. Intenta con uno nuevo.", "IniciarSesion.php");
    } else {
        // Prepara la consulta para evitar la inyección SQL
        $insertar_usuario = $conexion->prepare("INSERT INTO `users` (`profile_picture`, `nombre_completo`, `email`, `password`, `role`) VALUES (?, ?, ?, ?, ?)");

        // Vincula los parámetros y ejecuta la consulta para insertar usuario
        $insertar_usuario->bind_param("sssss", $nombre_imagen, $username, $correo, $password, $rol);
        $insertar_usuario->execute();

        // Verifica si se insertaron filas correctamente
        if ($insertar_usuario->affected_rows > 0) {

            // Obtén el ID del usuario recién insertado
            $user_id = $insertar_usuario->insert_id;

            $insertar_student = $conexion->prepare("INSERT INTO `teachers` (`id`, `profile_picture`, `nombre_completo`, `email`) VALUES (?, ?, ?, ?)");
            $insertar_student->bind_param("isss", $user_id, $nombre_imagen, $username, $correo);
            $insertar_student->execute();

            // Almacena la información de inicio de sesión en la sesión del usuario
            $_SESSION["username"] = $username; // Almacena el nombre de usuario
            $_SESSION["role"] = $rol; // Almacena el rol del usuario

            // Redirige al usuario a la página de inicio de estudiante
            header("Location: ../Pagina/inicioProfesor.php");
            exit();
            
         // Cierra las consultas
         $insertar_usuario->close();
         if (isset($insertar_student)) $insertar_student->close();
         if (isset($insertar_teacher)) $insertar_teacher->close();
     }

    }

            } else {
                mostrarNotificacion("error", "Error", "La llave no es correcta. Si aún no cuentas con la llave, contactanos en adminiminds@proyd2.com para tener acceso.", "IniciarSesion.php");
            }
    }
    else{
        mostrarNotificacion("error", "Error", "Ingresa la llave para crear tu perfil. Si aún no cuentas con la llave, contactanos en adminiminds@proyd2.com para tener acceso.", "IniciarSesion.php");
    }
} elseif ($rol == "student"){

    // Verifica si se ha subido alguna imagen de perfil
    if (isset($_FILES['p_picture']) && $_FILES['p_picture']['error'] == UPLOAD_ERR_OK) {
        $p_picture = $_FILES['p_picture'];
        // Guardar imagen en una carpeta en el servidor
        $nombre_imagen = $p_picture['name'];
        $ruta_imagen = '../Logeo/foto_perfil/' . $nombre_imagen;

        // Mueve la imagen al directorio destino
        if (!move_uploaded_file($p_picture['tmp_name'], $ruta_imagen)) {
            
            mostrarNotificacion("error", "Error", "Error al subir la imagen Intentalo mas tarde.", "IniciarSesion.php");
        }
    } else {
        // Si no se subió ninguna imagen, asigna un valor predeterminado
        $nombre_imagen = "default.png"; // imagen predeterminada
    }

    // Verifica si ya existe un usuario con el mismo nombre de usuario o correo electrónico
    $consulta_existencia = $conexion->prepare("SELECT * FROM `users` WHERE `nombre_completo` = ? OR `email` = ?");
    $consulta_existencia->bind_param("ss", $username, $correo);
    $consulta_existencia->execute();
    $resultado_existencia = $consulta_existencia->get_result();

    if ($resultado_existencia->num_rows > 0) {
        mostrarNotificacion("error", "Error", "Ya existe una cuenta con ese nombre y/o correo. Intenta con uno nuevo.", "IniciarSesion.php");
    } else {
        // Prepara la consulta para evitar la inyección SQL
        $insertar_usuario = $conexion->prepare("INSERT INTO `users` (`profile_picture`, `nombre_completo`, `email`, `password`, `role`) VALUES (?, ?, ?, ?, ?)");

        // Vincula los parámetros y ejecuta la consulta para insertar usuario
        $insertar_usuario->bind_param("sssss", $nombre_imagen, $username, $correo, $password, $rol);
        $insertar_usuario->execute();

        // Verifica si se insertaron filas correctamente
        if ($insertar_usuario->affected_rows > 0) {

            // Obtén el ID del usuario recién insertado
            $user_id = $insertar_usuario->insert_id;

            // Inserta en la tabla correspondiente (students o teachers) según el rol
           
                $insertar_student = $conexion->prepare("INSERT INTO `students` (`id`, `profile_picture`, `nombre_completo`, `email`) VALUES (?, ?, ?, ?)");
                $insertar_student->bind_param("isss", $user_id, $nombre_imagen, $username, $correo);
                $insertar_student->execute();

                // Almacena la información de inicio de sesión en la sesión del usuario
                $_SESSION["username"] = $username; // Almacena el nombre de usuario
                $_SESSION["role"] = $rol; // Almacena el rol del usuario

                // Redirige al usuario a la página de inicio de estudiante
                header("Location: ../Pagina/inicioAlumno.php");
                exit();
           

        // Cierra las consultas
        $insertar_usuario->close();
        if (isset($insertar_student)) $insertar_student->close();
        if (isset($insertar_teacher)) $insertar_teacher->close();
    }
}
}
else{
    mostrarNotificacion("error", "Error", "No se detecto ningun rol", "IniciarSesion.php");
}

    // Cierra la conexión a la base de datos
    $conexion->close();
} else {
    mostrarNotificacion("error", "Error", "Por favor, complete el formulario.", "IniciarSesion.php");
}
?>
