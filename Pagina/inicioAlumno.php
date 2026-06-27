<?php
// Inicia una nueva sesión o reanuda la sesión existente
session_start();

// Incluir el archivo de conexión a la base de datos
include("../Logeo/conexion.php");

// Inicializa la variable $nombre_alumno con una cadena vacía
$nombre_alumno = ""; 

$foto_perfil = ""; // Inicializar la variable para la foto de perfil

if (isset($_SESSION["username"])) {
    $username = $_SESSION["username"];
    $consulta = "SELECT nombre_completo, profile_picture FROM users WHERE nombre_completo = '$username'";
    $resultado = mysqli_query($conexion, $consulta);

    if ($resultado) {
        $fila = mysqli_fetch_assoc($resultado);
        $nombre_alumno = $fila["nombre_completo"];
        $foto_perfil = $fila["profile_picture"];
    }

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Miniminds</title>
    <link rel="icon" href="../imagenes/LogoS.png">
    <!-- Enlaces a los estilos CSS -->
    <link rel="stylesheet" href="styleIndex.css" type="text/css">
    <link rel="stylesheet" href="../CSS/estrellasDEcolor.css" type="text/css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

    <style>
        .sec {
            overflow: hidden;
        }
        .nav-menu {
            cursor: pointer;
            font-size: 1.9em;
            margin: 10px 0 40px 10px;
            font-weight: 600;
        }
        .nav-menu i {
            font-size: 2.5em;
            margin: 0 12px 0 5px;
        }
        @media (max-width: 760px) {
            .sec {
                height: 300px;
            }
            .nav-menu {
                font-size: 1.2em;
                margin: 10px 0 40px 3px;
            }
            .nav-menu i {
                font-size: 1.9em;
                margin: 0 12px 0 5px;
            }
        }
    </style>
</head>

<body>
<header>
    <div class="sec">
        <h1 class="titulo">Miniminds</h1>
        <div class="logo-1"><img src="../imagenes/LogoS.png"></div>
    </div>
    <script>
        // Crear 300 estrellas con tamaños y posiciones aleatorias
        for(let i = 1; i <= 300; i++) {
            let stars = document.createElement('div');
            stars.classList.add('star');
            let size = Math.random() * 20;
            stars.style.fontSize = 10 + size + 'px';
            stars.style.left = Math.random() * innerWidth + 'px';
            stars.style.top = Math.random() * innerHeight + 'px';
            stars.style.filter = `hue-rotate(${i * 5}deg)`;
            document.querySelector('.sec').appendChild(stars);
        }

        // Mover las estrellas al pasar el cursor
        document.addEventListener('mousemove', (e) => {
            let mouseX = e.clientX;
            let mouseY = e.clientY;

            let stars = document.querySelectorAll('.star');
            stars.forEach(star => {
                let starX = parseFloat(star.style.left);
                let starY = parseFloat(star.style.top);

                let deltaX = mouseX - starX;
                let deltaY = mouseY - starY;

                let distance = Math.sqrt(deltaX * deltaX + deltaY * deltaY);

                if (distance < 50) { // Ajustar el valor según necesidades
                    star.style.left = starX - deltaX * 0.05 + 'px';
                    star.style.top = starY - deltaY * 0.05 + 'px';
                }
            });
        });

        // Función para animar las estrellas
        function animateStars() {
            let AllStars = document.querySelectorAll('.star');
            let num = Math.floor(Math.random() * AllStars.length);
            AllStars[num].classList.toggle('animate');
        }
        setInterval(animateStars, 50);
    </script>
</header>
<!-- Fin de la cabecera -->
<main class="main-content">
    <!-- Menú lateral desplegable para el alumno -->
   <div id="mySidenav" class="sidenav">
        <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
        <a href="juegos.php">Juegos</a>
        <a href="mis_actividades.php">Mis Tareas</a>
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
    <div class="nav-menu">
        <span onclick="openNav()"><i class="fa-solid fa-bars"></i> <?php echo $nombre_alumno;?></span>
    </div>

    <section class="task-management">
    <h2 class="title-juegos">Mis Tareas</h2>
    <div class="task-list">
    <?php
    // Incluir el archivo de conexión a la base de datos
    include("../Logeo/conexion.php");

    // Obtener el ID de la clase del alumno
    $consulta_clase = "SELECT id, grupo FROM `students` WHERE nombre_completo = '$nombre_alumno'";
    $resultado_clase = mysqli_query($conexion, $consulta_clase);

    // Si la consulta devuelve resultados
    if ($resultado_clase && mysqli_num_rows($resultado_clase) > 0) {
        $fila_clase = mysqli_fetch_assoc($resultado_clase);
        $clase_name = $fila_clase["grupo"];
        $id_estudiante = $fila_clase["id"];

        // Obtener el ID de la clase desde la tabla de grupos
        $query_grupos = "SELECT group_id FROM groups WHERE group_name='$clase_name'";
        $resultado_grupos = mysqli_query($conexion, $query_grupos);

        // Si la consulta devuelve resultados
        if ($resultado_grupos && mysqli_num_rows($resultado_grupos) > 0) {
            $fila_grupo = mysqli_fetch_assoc($resultado_grupos);
            $clase_id = $fila_grupo["group_id"];

            // Consultar las tareas asignadas a la clase del alumno
            $query_tareas = "SELECT * FROM tasks WHERE class_id = '$clase_id'";
            $result_tareas = mysqli_query($conexion, $query_tareas);

            // Mostrar las tareas asignadas
            if ($result_tareas && mysqli_num_rows($result_tareas) > 0) {
                // Configurar la zona horaria
                $zonaHoraria = new DateTimeZone('America/Mexico_City');

                // Obtener la fecha y hora actual en la zona horaria especificada
                $fecha_actual = new DateTime('now', $zonaHoraria);

                // Aplicar un retraso de 1 hora
                $fecha_actual->modify('-1 hour');

                // Formatear la fecha y hora actual según el formato deseado
                $fecha_formateada = $fecha_actual->format('Y-m-d H:i:s');

                while ($row_tareas = mysqli_fetch_assoc($result_tareas)) {
                    // Obtener la fecha de expiración de la tarea
                    $fecha_expiracion = new DateTime($row_tareas['fecha_expiracion'], $zonaHoraria);

                    // Verificar si la fecha de expiración es mayor a la fecha actual
                    if ($fecha_expiracion > $fecha_formateada) {
                        // Obtener el task_id de la tarea
                        $task_id = $row_tareas['task_id'];

                        // Preparar la consulta SQL para verificar si hay alguna coincidencia en student_interaction
                        $sql_coincidencia = "SELECT end_time FROM student_interactions WHERE task_id = ? AND student_id = ?";
                        // Preparar la declaración
                        $stmt_coincidencia = $conexion->prepare($sql_coincidencia);
                        // Verificar si la preparación de la declaración fue exitosa
                        if ($stmt_coincidencia === false) {
                            die("Error en la preparación de la declaración: " . $conexion->error);
                        }

                        // Enlazar los parámetros con los valores del task_id y student_id
                        $stmt_coincidencia->bind_param("ii", $task_id, $id_estudiante);

                        // Ejecutar la declaración
                        if ($stmt_coincidencia->execute() === true) {
                            // Obtener el resultado de la consulta
                            $result_coincidencia = $stmt_coincidencia->get_result();

                            // Verificar si hay alguna coincidencia
                            if ($result_coincidencia->num_rows > 0) {
                                // Mostrar la marca de actividad completada en lugar del botón Comenzar
                                echo "<div class='task'>";
                                echo "<h3>" . htmlspecialchars($row_tareas['task_name']) . "</h3>";
                                echo "<p>Descripción de la Tarea: " . htmlspecialchars($row_tareas['descripcion']) . "</p>";
                                echo "<p>Fecha de Vencimiento: " . htmlspecialchars($row_tareas['fecha_expiracion']) . "</p>";

                                // Obtener la fecha de conclusión de la tarea
                                $row_coincidencia = $result_coincidencia->fetch_assoc();
                                $end_time = $row_coincidencia['end_time'];
                                echo "<p>Completada: " . htmlspecialchars($end_time) . "</p>";

                                echo "<p>Actividad Completada</p>";
                                echo "</div>";
                            } else {
                                // Mostrar el botón Comenzar
                                echo "<div class='task'>";
                                echo "<h3>" . htmlspecialchars($row_tareas['task_name']) . "</h3>";
                                echo "<p>Descripción de la Tarea: " . htmlspecialchars($row_tareas['descripcion']) . "</p>";
                                echo "<p>Fecha de Vencimiento: " . htmlspecialchars($row_tareas['fecha_expiracion']) . "</p>";
                                echo "<form action='clases_y_actividades/leerConfiguracion.php' method='POST'>";
                                echo "<input type='hidden' name='id_task' value='" . htmlspecialchars($task_id) . "'>";
                                echo "<input type='hidden' name='json_config' value='" . htmlspecialchars($row_tareas['configuracion']) . "'>";
                                echo "<button class='btnlg'>Comenzar</button>";
                                echo "</form>";
                                echo "</div>";
                            }
                        } else {
                            // Si ocurrió un error al ejecutar la consulta, mostrar mensaje de error
                            echo "Error al ejecutar la consulta: " . htmlspecialchars($stmt_coincidencia->error);
                        }
                    }
                }
            } else {
                // Si no hay tareas asignadas a la clase del alumno, mostrar un mensaje
                echo "<div class='task'>";
                echo "<p>No tienes tareas asignadas actualmente</p>";
                echo "</div>";
            }
        } else {
            // Si no se puede obtener el ID de la clase del alumno, mostrar un mensaje
            echo "<div class='task'>";
            echo "<p>No perteneces a una clase actualmente</p>";
            echo "<br><a href='mi_grupo.php' class='btnlg' style='text-decoration:none; color:black; padding:3px 5px;'>Inscribirse a una clase</a>";
            echo "</div>";
        }
    } else {
        echo "Usuario no encontrado";
    }
    ?>
</div>
</section>

</main>

<footer>
    <p>&copy; 2024 Miniminds</p>
    <a href="">Politica de privacidad</a>
</footer>

<!-- Scripts JavaScript -->
<script>
    // Funciones para abrir y cerrar el menú lateral
    function openNav() {
        document.getElementById("mySidenav").style.width = "250px";
        document.getElementById("main").style.marginLeft = "250px";
        document.body.style.backgroundColor = "rgba(0,0,0,0.4)";
    }

    function closeNav() {
        document.getElementById("mySidenav").style.width = "0";
        document.getElementById("main").style.marginLeft = "0";
        document.body.style.backgroundColor = "white";
    }
</script>
<!-- Fin de los scripts -->

<?php 
    }
// Cierra la conexión a la base de datos
mysqli_close($conexion);
?>
</body>
</html>
