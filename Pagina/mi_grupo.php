<?php
session_start();
include("../Logeo/conexion.php");

// Inicializa la variable $nombre_alumno con una cadena vacía
$nombre_alumno = "";
$foto_perfil = ""; // Inicializar la variable para la foto de perfil
$id_s = "";

// Variables para mensajes de error
$error_msg = "";

if (isset($_SESSION["username"])) {
    $username = $_SESSION["username"];
    $consulta = "SELECT id, profile_picture, nombre_completo FROM users WHERE nombre_completo = '$username'";
    $resultado = mysqli_query($conexion, $consulta);

    if ($resultado) {
        $fila = mysqli_fetch_assoc($resultado);
        if ($fila) {
            $nombre_alumno = $fila["nombre_completo"];
            $foto_perfil = $fila["profile_picture"];
            $id_s = $fila["id"];
        } else {
            $error_msg = "No se encontraron datos del usuario.";
        }
    } else {
        $error_msg = "Error al obtener los datos del usuario.";
    }
}

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Miniminds</title>
    <link rel="icon" href="../imagenes/LogoS.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

    <link rel="stylesheet" href="mi_grupo.css">
    <!-- Agrega aquí tus enlaces a otros estilos CSS, si los tienes -->
</head>
<header>
<div id="mySidenav" class="sidenav">
    <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
    <a href="inicioAlumno.php">Inicio</a>
    <a href="juegos.php">Juegos</a>
    <a href="mis_actividades.php">Mis Tareas</a>
    <a href="compartidos.php">Recursos</a>
    <br><br>
    <div class="dropdown">
        <a href="#" class="d-flex align-items-center text-decoration-none dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false" style="font-size:15px; color:#000;">
            <img style='width: 50px;' src='../Logeo/foto_perfil/<?php echo htmlspecialchars($foto_perfil); ?>' alt='<?php echo htmlspecialchars($nombre_alumno); ?>' width="28" height="35" class="rounded-circle me-2">
            <strong><?php echo htmlspecialchars($nombre_alumno); ?></strong>
        </a>
        <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="editarPerfil.php" style="font-size:15px;">Mi Perfil</a></li>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item" href="../Logeo/cerrarSesion.php" style="font-size:15px;">Cerrar Sesion</a></li>
        </ul>
    </div>
</div> 
<div class="menu-fixed">
    <span onclick="openNav()"><i class="fa-solid fa-bars"></i> <?php echo htmlspecialchars($nombre_alumno); ?> </span>
</div>
</header>
<body>
<?php

// Verificar si el usuario está en la tabla students
$consulta_student = "SELECT * FROM students WHERE nombre_completo = '$username'";
$resultado_student = mysqli_query($conexion, $consulta_student);

if ($resultado_student) {
    $fila_student = mysqli_fetch_assoc($resultado_student);
    if ($fila_student) {
        // Si el usuario está en la tabla students
        $grupo = $fila_student["grupo"];
        if (!empty($grupo)) {
            // Si tiene un grupo asignado, mostrar la tabla de estudiantes del mismo grupo
            $consulta_grupo = "SELECT nombre_completo, profile_picture FROM students WHERE grupo = '$grupo'";
            $resultado_grupo = mysqli_query($conexion, $consulta_grupo);
            if ($resultado_grupo && mysqli_num_rows($resultado_grupo) > 0) {

                // Obtener el ID del profesor que creó el grupo
                $consulta_profe = "SELECT created_by FROM groups WHERE group_name = '$grupo'";
                $resultado_profe = mysqli_query($conexion, $consulta_profe);

                if ($resultado_profe && mysqli_num_rows($resultado_profe) > 0) {
                    $fila_profe = mysqli_fetch_assoc($resultado_profe);
                    $id_profe = $fila_profe['created_by'];

                    // Obtener el nombre del profesor utilizando el ID
                    $consulta_nombre_profe = "SELECT nombre_completo FROM teachers WHERE nombre_completo = '$id_profe'";
                    $resultado_nombre_profe = mysqli_query($conexion, $consulta_nombre_profe);
                    
                    if ($resultado_nombre_profe && mysqli_num_rows($resultado_nombre_profe) > 0) {
                        $fila_nombre_profe = mysqli_fetch_assoc($resultado_nombre_profe);
                        $nombre_profe = $fila_nombre_profe['nombre_completo'];
                    } else {
                        $nombre_profe = "Desconocido";
                    }
                } else {
                    $nombre_profe = "Desconocido";
                }

                // Mostrar la tabla de estudiantes del mismo grupo
                ?>
                <div class="row">
                    <div class="col">
                        <h2 class="nombre-grupo"><?php echo htmlspecialchars($grupo); ?></h2>
                        <h5 class="nombre-profe">Profesor: <?php echo htmlspecialchars($nombre_profe); ?></h5>
                        <table class="table table-striped" style="width: 80%; margin-left: 10%;">
                            <thead>
                                <tr>
                                    <th>Foto de Perfil</th>
                                    <th>Nombre</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($row_grupo = mysqli_fetch_assoc($resultado_grupo)) : ?>
                                    <tr>
                                        <td><img src="../Logeo/foto_perfil/<?php echo htmlspecialchars($row_grupo['profile_picture']); ?>" alt="Foto de Perfil" style="width: 50px; margin-left: 3%;"></td>
                                        <td><?php echo htmlspecialchars($row_grupo['nombre_completo']); ?></td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <?php
            } else {
                $error_msg = "No se encontraron estudiantes en el grupo '$grupo'.";
            }
        } else {
            // Si el usuario no tiene grupo asignado, mostrar el formulario de inscripción
            ?>
            <div class="row">
                <div class="col">
                    <div class="tarjeta-inscrip">
                        <div class="mensaje-tarjeta">
                            <h3>Aún no perteneces a ningún grupo</h3>
                            <p>¿Tienes el código de un grupo? ¡Inscríbete!</p>
                        </div>
                        <form action="inscripcion.php" method="post">
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-user"></i></span>
                                </div>
                                <input type="text" name="id_clase" class="form-control" placeholder="ID de la clase" required>
                            </div>
                            <input type="hidden" name="user_id" id="user_id" value="<?php echo htmlspecialchars($id_s); ?>">
                            <input type="submit" class="btn btn-primary" value="Inscribirse"/>
                        </form>
                    </div>
                </div>
            </div>
            <?php
        }
    } else {
        $error_msg = "El usuario '$username' no se encontró en la tabla de estudiantes.";
    }
} else {
    $error_msg = "Error al buscar al usuario en la tabla de estudiantes.";
}

// Mostrar mensajes de error
if (!empty($error_msg)) {
    echo "<div class='alert alert-danger' role='alert'>$error_msg</div>";
}

// Cerrar conexión
mysqli_close($conexion);
?>

<script>
    // Funciones para abrir y cerrar el menú lateral
    function openNav() {
        document.getElementById("mySidenav").style.width = "250px";
        document.getElementById("main").style.marginLeft = "250px";
        document.body.style.backgroundColor = "rgba(0,0,0,0.4)";
    }
    
    function closeNav() {
        document.getElementById("mySidenav").style.width = "0";
        document.getElementById("main").style.marginLeft= "0";
        document.body.style.backgroundColor = "white";
    }
</script>

</body>
</html>
