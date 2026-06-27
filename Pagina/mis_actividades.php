<?php
    session_start();
    include("../Logeo/conexion.php");

    // Inicializa la variable $nombre_alumno con una cadena vacía
    $nombre_alumno = ""; 

    $foto_perfil = ""; // Inicializar la variable para la foto de perfil

    $id_s ="";

    // Variables para mensajes de error
    $error_msg = "";

    if (isset($_SESSION["username"])) {
        $username = $_SESSION["username"];
        $consulta = "SELECT  id, profile_picture, nombre_completo FROM users WHERE nombre_completo = '$username'";
        $resultado = mysqli_query($conexion, $consulta);

        if ($resultado) {
            $fila = mysqli_fetch_assoc($resultado);
            $nombre_alumno = $fila["nombre_completo"];
            $foto_perfil = $fila["profile_picture"];
            $id_s = $fila["id"];
        } else {
            $error_msg = "Error al obtener los datos del usuario.";
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
        <a href="compartidos.php">Recursos</a>
        <a href="mi_grupo.php">Mi Grupo</a>
        <br><br>
        <div class="dropdown">
            <a href="#" class="d-flex align-items-center text-decoration-none dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false" style="font-size:15px; color:#000;">
            <img style='width: 50px;' src='../Logeo/foto_perfil/<?php echo $foto_perfil; ?>' alt='<?php echo $nombre_alumno; ?>' width="28" height="35" class="rounded-circle me-2">
            <strong><?php echo $nombre_alumno; ?></strong></a>
        <ul class="dropdown-menu" >
            <li><a class="dropdown-item" href="editarPerfil.php" style="font-size:15px;">Mi Perfil</a></li>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item" href="../Logeo/cerrarSesion.php" style="font-size:15px;">Cerrar Sesion</a></li>
        </ul>
        </div>
</div> 
    <div class="menu-fixed">
            <span onclick="openNav()"><i class="fa-solid fa-bars"></i> <?php echo $nombre_alumno; ?> </span>
    </div>
</header>
<body>

<main class="container mt-5">
    <h1 class="nombre-grupo">Mis Tareas</h1>
    <br>
    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Tarea</th>
                <th>Fecha de Expiración</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
        <?php
        $contador = 1;
        // Obtener el ID de la clase del usuario
        $consulta_clase = "SELECT grupo FROM students WHERE id = $id_s";
        $resultado_clase = mysqli_query($conexion, $consulta_clase);
        $clase = mysqli_fetch_assoc($resultado_clase);

        // Obtener información del grupo
        $consulta_grupo = "SELECT group_id, group_name FROM groups WHERE group_name = '" . $clase['grupo'] . "'";
        $resultado_grupo = mysqli_query($conexion, $consulta_grupo);
        $grupo = mysqli_fetch_assoc($resultado_grupo);

        // Obtener las tareas de la clase del usuario
        $consulta_tareas = "SELECT * FROM tasks WHERE class_id = " . $grupo['group_id'];
        $resultado_tareas = mysqli_query($conexion, $consulta_tareas);

        if ($resultado_tareas) {
            // Configurar la zona horaria
            $zonaHoraria = new DateTimeZone('America/Mexico_City');
            // Obtener la fecha y hora actual en la zona horaria especificada
            $fecha_actual = new DateTime('now', $zonaHoraria);
            // Aplicar un retraso de 1 hora
            $fecha_actual->modify('-1 hour');

            // Formatear la fecha y hora actual según el formato deseado
            $fecha_formateada = $fecha_actual->format('Y-m-d H:i:s');

            
            while ($fila_tarea = mysqli_fetch_assoc($resultado_tareas)) {
                echo "<tr>";
                echo "<td>" . $contador++ . "</td>";
                echo "<td>" . htmlspecialchars($fila_tarea['task_name']) . "</td>";
                echo "<td>" . htmlspecialchars($fila_tarea['fecha_expiracion']) . "</td>";
                echo "<td>";

                // Revisa si ya realizo la tarea
                $consulta_tcompletadas = "SELECT * FROM `student_interactions` WHERE student_id = $id_s AND task_id = " . $fila_tarea['task_id'];
                $resultado_tcompletadas = mysqli_query($conexion, $consulta_tcompletadas);

                // Obtener la fecha de expiración de la tarea
                $fecha_expiracion = new DateTime($fila_tarea['fecha_expiracion'], $zonaHoraria);

                if (mysqli_num_rows($resultado_tcompletadas) > 0) {
                    echo "Tarea completada";
                } else {
                    // Verificar si la tarea ha caducado
                    if ($fecha_expiracion < $fecha_actual) {
                        echo "Esta tarea ha caducado";
                    } else {
                        echo "<form action='clases_y_actividades/leerConfiguracion.php' method='POST'>";
                        // Agrega los campos ocultos con los valores necesarios para el formulario
                        echo "<input type='hidden' name='id_task' value='" . htmlspecialchars($fila_tarea['task_id']) . "'>";
                        echo "<input type='hidden' name='json_config' value='" . htmlspecialchars($fila_tarea['configuracion']) . "'>";
                        echo "<button class='btn btn-primary'>Realizar Tarea</button>";
                        echo "</form>";
                    }
                }
                echo "</td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='4'>No se encontraron tareas.</td></tr>";
        }
        ?>


        </tbody>
    </table>
</main>

<?php
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
   