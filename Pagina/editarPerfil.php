<?php
session_start();



// Verificar si el usuario está autenticado, de lo contrario, redirigirlo a la página de inicio de sesión
if (!isset($_SESSION["username"])) {
    header("Location: ../Logeo/IniciarSesion.php");
    exit();
}

include("../Logeo/conexion.php");

// Obtener el nombre de usuario de la sesión
$username = $_SESSION["username"];

// Escapar el valor de $username para evitar inyección SQL
$username = mysqli_real_escape_string($conexion, $username);

// Consultar la información del usuario en la tabla users
$consulta_usuario = mysqli_query($conexion, "SELECT * FROM `users` WHERE `nombre_completo` = '$username'");

// Verificar si se encontró al menos un usuario
if (mysqli_num_rows($consulta_usuario) > 0) {
    while ($data = mysqli_fetch_array($consulta_usuario)) {
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
    
    <title>Miniminds</title>
    <link rel="icon" href="../imagenes/LogoS.png">
    <link rel="stylesheet" href="styleEditarPerfil.css">
</head>
<body>
<?php
    // Verificar si el usuario es un profesor
    if ($data['role'] == 'teacher') {
        // Consultar los datos del profesor en la tabla teachers
        $consulta_profesor = mysqli_query($conexion, "SELECT * FROM `teachers` WHERE `nombre_completo` = '$username'");
        if (mysqli_num_rows($consulta_profesor) > 0) {
            while ($row = mysqli_fetch_assoc($consulta_profesor)) {
                $id_profesor = $row['id'];
                $p_picture = $row['profile_picture'];
                $nombre_profesor = $row['nombre_completo'];
                $email = $row['email'];
                $telefono = $row['telefono'];
?>
<div class="datos">
<h2 class="datos-usuario">Datos del Usuario</h2>

        <div class="tarjeta-datos">
        <div class="botonActualizar">
    <a class="nuevoProducto" onclick="mostrarFormulario()"><span><i class="fa-solid fa-user-pen"></i></span></a>
    </div>
        <div class="columna">
            <div class="input-picture">
                <img src="../Logeo/foto_perfil/<?php echo $row['profile_picture']; ?>" alt="<?php echo $row['nombre_completo']; ?>">
            </div>
            </div>
            <div class="columna">
            <div class="input-field">
                <i class="fas fa-user"></i>
                <label for="">Nombre:</label>
                <h3><?php echo $nombre_profesor; ?></h3>
            </div>
            <div class="input-field">
                <i class="fa-solid fa-envelope"></i>
                <label for="">Correo:</label>
                <h3><?php echo $email; ?></h3>
            </div>
            <div class="input-field">
                <i class="fa-solid fa-phone"></i>
                <label for="">Teléfono:</label>
                <h3><?php echo $telefono; ?></h3>
            </div>
            </div>
        </div>
</div>


<div class="editar_datos" style="display: none;">
    <div class="Cabeza_piesFormulario">
        <div class="modal-body">
            <div class="container">
                <form action="actualizar_profesor.php" method="POST" enctype="multipart/form-data">
                    <div class="form-group row">
                        <label for="nombre_T" class="col-sm-2 col-form-label">Nombre:</label>
                        <div class="col-sm-10">
                            <input type="text" readonly class="form-control" id="nombre_T" name="nombre_T" value="<?php echo $nombre_profesor; ?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="correo_T" class="col-sm-2 col-form-label">Correo Electrónico:</label>
                        <div class="col-sm-10">
                            <input type="email" class="form-control" id="correo_T" name="correo_T" maxlength="60" value="<?php echo $email; ?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="telefono_T" class="col-sm-2 col-form-label">Teléfono:</label>
                        <div class="col-sm-10">
                            <input type="tel" class="form-control" id="telefono_T" name="telefono_T" value="<?php echo $telefono; ?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="p_picture_T" class="col-sm-2 col-form-label">Foto de perfil:</label>
                        <div class="col-sm-10">
                            <input type="file" class="form-control-file" id="p_picture_T" name="p_picture_T" accept="image/*">
                        </div>
                    </div>
            </div>
            <a class="btn btn-secondary" type="button" onclick="cerrarFormulario()"><span class="glyphicon glyphicon-remove"></span> Cancelar</a>
            <input type="hidden" class="id-producto" id="id-producto">
            <button class="btn btn-primary" type="submit">Actualizar Datos</button>
        </div>
        </form>
    </div>
</div>


<?php
            }
        } else {
            echo 'No existe este perfil como profesor';
        }
    } elseif ($data['role'] == 'student') {
        // Consultar los datos del estudiante en la tabla students
        $consulta_estudiante = mysqli_query($conexion, "SELECT * FROM `students` WHERE `nombre_completo` = '$username'");
        if (mysqli_num_rows($consulta_estudiante) > 0) {
            while ($row = mysqli_fetch_assoc($consulta_estudiante)) {
                $id_estudiante = $row['id'];
                $grupo = $row['grupo'];
                $p_picture = $row['profile_picture'];
                $nombre_alumno = $row['nombre_completo'];
                $email = $row['email'];
                $nombre_tutor = $row['nombre_tutor'];
                $telefono_tutor = $row['telefono_tutor'];
?>
<div class="datos">
    <h2 class="datos-usuario">Datos del Usuario</h2>
    <div class="tarjeta-datos">
    <div class="botonActualizar">
        <a class="nuevoProducto" onclick="mostrarFormulario()"><span><i class="fa-solid fa-user-pen"></i></span></a>
    </div> 
    
        <div class="columna">
            <div class="input-picture">
                <img src="../Logeo/foto_perfil/<?php echo $row['profile_picture']; ?>" alt="<?php echo $row['nombre_completo']; ?>">
            </div>
            <div class="input-field">
                <i class="fas fa-user"></i>
                <label for="">Nombre:</label>
                <h3><?php echo $nombre_alumno; ?></h3>
            </div>
        </div>
        <div class="columna">
            <div class="espacio"></div>
            <div class="input-field">
                <i class="fas fa-user"></i>
                <label for="">Grupo:</label>
                <h3><?php echo $grupo; ?></h3>
            </div>
            <div class="input-field">
                <i class="fa-solid fa-envelope"></i>
                <label for="">Correo:</label>
                <h3><?php echo $email; ?></h3>
            </div>
            <div class="input-field">
                <i class="fa-solid fa-envelope"></i>
                <label for="">Tutor:</label>
                <h3><?php echo $nombre_tutor; ?></h3>
            </div>
            <div class="input-field">
                <i class="fa-solid fa-phone"></i>
                <label for="">Teléfono:</label>
                <h3><?php echo $telefono_tutor; ?></h3>
            </div>
        </div>
    </div>
    
</div>



<div class="editar_datos" style="display: none;">
    <div class="Cabeza_piesFormulario">
        <div class="modal-body">
            <div class="container">
                <form action="actualizar_alumno.php" method="POST" enctype="multipart/form-data">
                    <div class="form-group row">
                        <label for="nombre_S" class="col-sm-2 col-form-label">Nombre:</label>
                        <div class="col-sm-10">
                            <input type="text" readonly class="form-control" id="nombre_S" name="nombre_S" value="<?php echo $nombre_alumno; ?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="correo_S" class="col-sm-2 col-form-label">Correo:</label>
                        <div class="col-sm-10">
                            <input type="email" class="form-control" id="correo_S" name="correo_S" value="<?php echo $email; ?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="tutor_S" class="col-sm-2 col-form-label">Tutor:</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="tutor_S" name="tutor_S" value="<?php echo $nombre_tutor; ?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="telefono_S" class="col-sm-2 col-form-label">Teléfono:</label>
                        <div class="col-sm-10">
                            <input type="tel" class="form-control" id="telefono_S" name="telefono_S" value="<?php echo $telefono_tutor; ?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="p_picture_S" class="col-sm-2 col-form-label">Foto de perfil:</label>
                        <div class="col-sm-10">
                            <input type="file" class="form-control-file" id="p_picture_S" name="p_picture_S" accept="image/*">
                        </div>
                    </div>
            </div>
            <a class="btn btn-secondary" type="button" onclick="cerrarFormulario()"><span class="glyphicon glyphicon-remove"></span> Cancelar</a>
            <input type="hidden" class="id-ptoduccto" id="id-producto">
            <button class="btn btn-primary" type="submit">Actualizar Datos</button>
            
        </div>
        </form>
    </div>
</div>

<?php
            }
        } else {
            echo 'No existe este perfil como Alumno';
        }
    }
?>
<div class="botones-datos">
    <p><a href="javascript:history.back()" data-toggle="tooltip" title="Regresar">Regresar</a></p>
    <p><a type="button" id="deleteProfileBtn" data-toggle="tooltip" title="Eliminar perfil"><i class="fa-solid fa-user-minus"></i></a></p>
    <p><a href="../Logeo/cerrarSesion.php" data-toggle="tooltip" title="Cerrar sesión"><i class="fa-solid fa-right-from-bracket"></i></a></p>
</div>

<?php 
// Obtener el nombre de usuario de la sesión
if(isset($_SESSION["username"])) {
    $username = $_SESSION["username"];

    // Escapar el valor de $username para evitar inyección SQL
    $username = mysqli_real_escape_string($conexion, $username);

    // Consultar la información del usuario en la tabla users
    $consulta_usuario = mysqli_query($conexion, "SELECT id, nombre_completo FROM `users` WHERE `nombre_completo` = '$username'");

    // Verificar si se encontró al menos un usuario
    if (mysqli_num_rows($consulta_usuario) > 0) {
        $data = mysqli_fetch_array($consulta_usuario);
        $id_usuario = $data['id'];
        $nombre_usuario = $data['nombre_completo'];
    }
}

?>

<div id="deleteProfileModal" style="display:none; width: 70%; margin-left:15%;">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="deleteProfileModalLabel">Confirmar eliminación de perfil</h5>
            <button type="button" class="close" aria-label="Close" id="CE">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <p>¿Estás seguro de que deseas eliminar tu perfil?</p>
            <form id="deleteProfileForm" action="eliminarPerfil.php" method="POST">
                <div class="form-group">
                    <label for="password">Introduce tu contraseña para continuar:</label>
                    <div id="captcha_pass" style="display: flex; flex-direction:row;">
                    <input type="password" class="form-control" id="password" name="password" required>
                    <span class="password-toggle" style="position: relative;" onclick="togglePassword()"><i class="fa-solid fa-eye"></i></span>
                    </div>
                    <input type="hidden" name="id_usuario" value="<?php echo $id_usuario; ?>">
                    <input type="hidden" name="name_usuario" value="<?php echo $nombre_usuario; ?>">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" id="Ce">Cancelar</button>
                    <button type="submit" class="btn btn-danger">Eliminar perfil</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
         function togglePassword() {
    const passwordInput = document.getElementById('password');
    const passwordToggle = document.querySelector('.password-toggle');
    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        passwordToggle.innerHTML = '<i class="fa-solid fa-eye-slash"></i>'; // Cambia el ícono
    } else {
        passwordInput.type = 'password';
        passwordToggle.innerHTML = '<i class="fa-solid fa-eye"></i>'; // Cambia el ícono
    }
}
</script>
<script>
    // Capturar el evento de clic en el botón de eliminar perfil
    document.getElementById('deleteProfileBtn').addEventListener('click', function(event) {
    document.getElementById('deleteProfileModal').style.display = 'flex';
    document.querySelector('.tarjeta-datos').style.display = 'none';
    document.querySelector('.datos-usuario').style.display = 'none';
    document.querySelector('.botones-datos').style.display = 'none';
    });

    document.getElementById('CE').addEventListener('click', function(event) {
        document.getElementById('deleteProfileModal').style.display = 'none';
        document.querySelector('.tarjeta-datos').style.display = 'flex'; // Mostrar la tarjeta de datos
        document.querySelector('.datos-usuario').style.display = 'block'; 
        document.querySelector('.botones-datos').style.display = 'flex';
    });

    document.getElementById('Ce').addEventListener('click', function(event) {
        document.getElementById('deleteProfileModal').style.display = 'none';
        document.querySelector('.tarjeta-datos').style.display = 'flex'; // Mostrar la tarjeta de datos
        document.querySelector('.datos-usuario').style.display = 'block'; 
        document.querySelector('.botones-datos').style.display = 'flex';
    });

</script>

<script>
    // Capturar el evento de envío del formulario
    document.getElementById('deleteProfileForm').addEventListener('submit', function(event) {
        // Obtener los datos del formulario
        var formData = new FormData(this);

        // Mostrar los datos en la consola
        console.log("Datos enviados por POST:");
        formData.forEach(function(value, key) {
            console.log(key + ': ' + value);
        });
    });
</script>


<script src="../JS/cambioColores.js"></script>

<script>
        
    
                function mostrarFormulario() {
                    document.querySelector('.editar_datos').style.display = 'block';
                    document.querySelector('.tarjeta-datos').style.display = 'none';
                    document.querySelector('.datos-usuario').style.display = 'none';
                    document.querySelector('.botones-datos').style.display = 'none';
                }

                function cerrarFormulario() {
                    document.querySelector('.editar_datos').style.display = 'none';
                    document.querySelector('.tarjeta-datos').style.display = 'flex'; // Mostrar la tarjeta de datos
                    document.querySelector('.datos-usuario').style.display = 'block'; 
                    document.querySelector('.botones-datos').style.display = 'flex';

                }
            </script>
</body>
</html>
<?php
    }
} else {
    // No se encontró el usuario, manejar de acuerdo a tus necesidades
    echo "Ha ocurrido un error. No se encontró el usuario.";
}
?>
