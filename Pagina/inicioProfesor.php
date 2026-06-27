<?php
session_start();

// Incluir archivo de conexión
include("../Logeo/conexion.php");

$nombre_profesor = ""; // Inicializar la variable
$foto_perfil = ""; // Inicializar la variable para la foto de perfil

if (isset($_SESSION["username"])) {
    $username = $_SESSION["username"];
    $consulta = "SELECT nombre_completo, profile_picture FROM users WHERE nombre_completo = '$username'";
    $resultado = mysqli_query($conexion, $consulta);

    if ($resultado) {
        $fila = mysqli_fetch_assoc($resultado);
        $nombre_profesor = $fila["nombre_completo"];
        $foto_perfil = $fila["profile_picture"];
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
    <!-- Agrega tus estilos CSS aquí -->
    <link rel="stylesheet" href="styleIndex.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</head>

<body>
<header>
   <div class="sec">
        <h1 class="titulo">Miniminds</h1>
        <div class="logo-1"><img src="../imagenes/LogoS.png"></div>
    <!-- Menú lateral desplegable para el profesor -->
    <div id="mySidenav" class="sidenav">
        <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
        <a href="juegos.php">Juegos y Actividades</a>
        <a href="acciones/index.php">Acciones</a>
        <a href="send_email.php">Sugerencias</a>
        <br><br>
        <div class="dropdown" >
            <a href="#" class="d-flex align-items-center text-decoration-none dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false" style="font-size:15px; color:#000;">
            <img style='width: 50px;' src='../Logeo/foto_perfil/<?php echo $foto_perfil; ?>' alt='<?php echo $nombre_profesor; ?>' width="28" height="35" class="rounded-circle me-2">
            <strong><?php echo $nombre_profesor; ?></strong></a>
        <ul class="dropdown-menu" >
            <li><a class="dropdown-item" href="editarPerfil.php" style="font-size:15px;">Mi Perfil</a></li>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item" href="../Logeo/cerrarSesion.php" style="font-size:15px;">Cerrar Sesion</a></li>
        </ul>
         </div>
    </div> 

    <div class="nav-menu menu-fixed">
            <span data-toggle='tooltip' title='Menu' onclick="openNav()"><i class="fa-solid fa-bars"></i>  Profesor: <?php echo $nombre_profesor; ?> </span>
    </div>
   </div>
</header>

<main class="main-content">
   <section class="class-management">
    <h2 class="title-juegos">Clases</h2> 
    <div class="fixed-button">
        <button class="btn-fix clase" onclick="window.location.href='clases_y_actividades/crearClase.php'" title="Crear nueva clase">Crear nueva clase</button>
    </div> 

    <div class="class-list">
    <?php
    // Consultar la lista de clases
    $query = "SELECT * FROM groups WHERE created_by = '$nombre_profesor'";
    $result = mysqli_query($conexion, $query);

    // Verificar si se obtuvieron resultados
    if (mysqli_num_rows($result) > 0) {
        // Mostrar la lista de clases
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<div class='class'>";
            echo "<h3>" . $row['group_name'] . "</h3>";
            echo "<p>Código: " . $row['group_code'] . "</p>";
            echo "<p>Descripción: " . $row['descrip'] . "</p>";
            echo "<p>Estudiantes: " . $row['student_count'] . "</p>";
            echo "<div class='' style=' display: flex; gap: 10px; '>";
            echo "<button type='button' onclick=\"window.location.href='get_students.php?group_id=" . $row['group_id'] . "'\" class='btn' data-toggle='tooltip' title='Ver Estudiantes'>";
            echo "<i class='fas fa-users'></i>";
            echo "</button>";
            echo "<button type='button' onclick=\"window.location.href='clases_y_actividades/asignarActividad.php'\" class='btn' data-toggle='tooltip' title='Asignar Tarea'>";
            echo "<i class='fas fa-tasks'></i>";
            echo "</button>";
            echo "<form action='clases_y_actividades/eliminarClase.php' method='POST' id='delete_class_form' class='d-inline'>";
            echo "<input type='hidden' name='delete_class_id' id='delete_class_id' value='" . $row['group_id'] . "'>";
            echo "<button type='button' class='btn' data-toggle='tooltip' title='Eliminar Clase' onclick='confirmDeleteClass()'>";
            echo "<i class='fas fa-trash-alt'></i>";
            echo "</button>";
            echo "</form>";
            echo "</div>";
            echo "</div>";
        }
    } else {
        // Si no hay clases, mostrar un mensaje
        echo "<div class='class'>";
        echo "<p>No has creado ninguna clase</p>";
        echo "</div>";
    }
    ?>
</div>
   </section>

   <section class="task-management">
    <h2 class="title-juegos">Tareas Asignadas</h2>
        <div class="task-list">
        <?php

       // Configurar la zona horaria
$zonaHoraria = new DateTimeZone('America/Mexico_City');

// Obtener la fecha y hora actual en la zona horaria especificada
$fecha_actual = new DateTime('now', $zonaHoraria);

// Aplicar un retraso de 1 hora
$fecha_actual->modify('-1 hour');

// Formatear la fecha y hora actual según el formato deseado
$fecha_formateada = $fecha_actual->format('Y-m-d H:i:s');

// Consultar las tareas asignadas al profesor actual que aún no han expirado
$query_tareas = "SELECT * FROM tasks WHERE created_by = '$nombre_profesor' AND fecha_expiracion > '$fecha_formateada'";
$result_tareas = mysqli_query($conexion, $query_tareas);
        $query_clases = "SELECT * FROM groups WHERE created_by = '$nombre_profesor'";
        $result_clases = mysqli_query($conexion, $query_clases);

        // Verificar si se obtuvieron resultados en ambas consultas
        if (mysqli_num_rows($result_tareas) > 0 && mysqli_num_rows($result_clases) > 0) {
            // Obtener todas las tareas en un arreglo
            $tareas = mysqli_fetch_all($result_tareas, MYSQLI_ASSOC);
            // Obtener todas las clases en un arreglo
            $clases = mysqli_fetch_all($result_clases, MYSQLI_ASSOC);
            // Iterar sobre las tareas y mostrarlas junto con la información de la clase correspondiente
             foreach ($tareas as $row_tareas) {
             // Encontrar la clase correspondiente a la tarea actual
             $clase_correspondiente = array_filter($clases, function($clase) use ($row_tareas) { return $clase['group_id'] == $row_tareas['class_id']; });

            // Verificar si se encontró la clase correspondiente
             if (!empty($clase_correspondiente)) {
            $row_clases = reset($clase_correspondiente); // Obtener el primer elemento del arreglo

            // Consultar el progreso de la tarea actual
            $task_id = $row_tareas['task_id'];
            $query_progress = "SELECT COUNT(DISTINCT interaction_id) AS completed_students 
                               FROM student_interactions 
                               WHERE task_id = $task_id";
            $result_progress = mysqli_query($conexion, $query_progress);
            $row_progress = mysqli_fetch_assoc($result_progress);
            $completed_students = $row_progress['completed_students'];
            
            // Obtener el total de estudiantes en la clase
            $total_students = $row_clases['student_count'];
            
            // Mostrar la tarea junto con su progreso
            echo "<div class='task'>";
            echo "<h3>" . $row_tareas['task_name'] . "</h3>";
            echo "<p>Clase: " . $row_clases['group_code'] . "</p>";
            echo "<p>Descripción de la Tarea: " . $row_tareas['descripcion'] . "</p>";
            echo "<p>Fecha de expiracion: ".$row_tareas['fecha_expiracion']."</p>";
            echo "<p>Progreso: $completed_students / $total_students estudiantes completados</p>";
            echo "<div class='' style='display: flex; gap: 10px; '>";
            echo "<button type='button' class='btn' data-toggle='tooltip' title='Editar Tarea' onclick=\"window.location.href='clases_y_actividades/editarTarea.php?task_id=$task_id'\"><i class='fas fa-edit'></i></button>";
            echo "<form action='clases_y_actividades/eliminarActividad.php' method='POST' id='delete_activity_form_" . $row_tareas['task_id'] . "'>";
            echo "<input type='hidden' name='delete_activity_id' id='delete_activity_id_" . $row_tareas['task_id'] . "' value='" . $row_tareas['task_id'] . "'>";
            echo "<button type='button' class='btn' data-toggle='tooltip' title='Eliminar Tarea' onclick='confirmDeleteActivity(" . $row_tareas['task_id'] . ")'>";
            echo "<i class='fas fa-trash-alt'></i>";
            echo "</button>";
            echo "</form>";
            
            echo "<form action='clases_y_actividades/leerConfiguracion.php' method='POST'>";
            echo "<input type='hidden' name='id_task' value='" .$row_tareas['task_id']. "'>";
            echo "<input type='hidden' name='json_config' value='" .$row_tareas['configuracion']. "'>";
            echo "<button class='btn' data-toggle='tooltip' title='Vista Previa'>";
            echo "<i class='fa-solid fa-eye'></i>";
            echo "</button>";
            echo "</form>";
                
            echo "</div>";
            echo "</div>";
                }
            }
        } else {
            // Si no hay tareas asignadas o todas han expirado, mostrar un mensaje
            echo "<div class='task'>";
            echo "<p>No hay tareas asignadas o todas han expirado</p>";
            echo "</div>";
        }
    ?>
    </div>
   </section>
</main>

<footer>
    <p>&copy; 2024 Miniminds</p>
    <a href="">Política de privacidad</a>
</footer>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

<script>
    function confirmDeleteClass() {
    // Mostrar el mensaje de confirmación con SweetAlert
    Swal.fire({
        title: '¿Estás seguro de eliminar la clase?',
        text: "No podrás revertir esta acción.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            // Obtener el ID de la clase a eliminar
            var classId = document.getElementById("delete_class_id").value;

            // Establecer el valor del campo oculto en el formulario
            document.getElementById("delete_class_id").value = classId;

            // Enviar el formulario
            document.getElementById("delete_class_form").submit();
        }
    });
}

function confirmDeleteActivity(activityId) {
    // Mostrar el mensaje de confirmación con SweetAlert
    Swal.fire({
        title: '¿Estás seguro de eliminar esta actividad?',
        text: "No podrás revertir esta acción.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            // Establecer el valor del campo oculto en el formulario correspondiente
            document.getElementById("delete_activity_id_" + activityId).value = activityId;

            // Enviar el formulario correspondiente
            document.getElementById("delete_activity_form_" + activityId).submit();
        }
    });
}


</script>

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

<?php // Cerrar la conexión a la base de datos
mysqli_close($conexion);
?>
</body>
</html>
