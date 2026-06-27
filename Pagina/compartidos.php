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
    <a href="mi_grupo.php">Mi Grupo</a>
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
<h1 class="nombre-grupo">Recursos Compartidos</h1>
            <table class="table" style="width: 80%; margin-left: 10%;">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Recurso</th>
                        <th>Enlace de Descarga</th>
                    </tr>
                </thead>
                <tbody>
                <?php
               // Consulta para verificar si el estudiante está inscrito en algún grupo
$query_grupo_estudiante = "SELECT grupo FROM students WHERE nombre_completo = ?";
$stmt_grupo_estudiante = $conexion->prepare($query_grupo_estudiante);
$stmt_grupo_estudiante->bind_param("s", $nombre_alumno);
$stmt_grupo_estudiante->execute();
$result_grupo_estudiante = $stmt_grupo_estudiante->get_result();

if ($result_grupo_estudiante->num_rows > 0) {
    $row_grupo_estudiante = $result_grupo_estudiante->fetch_assoc();
    $grupo_estudiante = $row_grupo_estudiante["grupo"];

    // Consulta para obtener el ID del grupo
    $query_grupo = "SELECT group_id FROM groups WHERE group_name = ?";
    $stmt_grupo = $conexion->prepare($query_grupo);
    $stmt_grupo->bind_param("s", $grupo_estudiante);
    $stmt_grupo->execute();
    $result_grupo = $stmt_grupo->get_result();

    if ($result_grupo->num_rows > 0) {
        $row_grupo = $result_grupo->fetch_assoc();
        $grupo_id = $row_grupo["group_id"];

        // Consulta para obtener los recursos compartidos con el grupo del estudiante
        $query_recursos = "SELECT id, nombre, grupo FROM recursos WHERE grupo = ?";
        $stmt_recursos = $conexion->prepare($query_recursos);
        $stmt_recursos->bind_param("i", $grupo_id); // Usar el ID del grupo
        $stmt_recursos->execute();
        $result_recursos = $stmt_recursos->get_result();

        if ($result_recursos->num_rows > 0) {
            $contador = 1; // Inicializa el contador
            while ($row = $result_recursos->fetch_assoc()) {
                $recurso_id = $row["id"];
                $nombre_recurso = htmlspecialchars($row["nombre"]);
                $grupo_recurso = htmlspecialchars($row["grupo"]);

                // Consulta para obtener el nombre del grupo
                $query_nombre_grupo = "SELECT group_name FROM groups WHERE group_id = ?";
                $stmt_nombre_grupo = $conexion->prepare($query_nombre_grupo);
                $stmt_nombre_grupo->bind_param("i", $grupo_recurso);
                $stmt_nombre_grupo->execute();
                $result_nombre_grupo = $stmt_nombre_grupo->get_result();
                $nombre_grupo = $result_nombre_grupo->fetch_assoc()["group_name"];

                echo "<tr>";
                echo "<td>" . $contador . "</td>"; 
                echo "<td>" . $nombre_recurso . "</td>";
                echo "<td>
                    <form action='acciones/download.php' method='POST'>
                        <input type='hidden' name='file' value='" . $nombre_recurso . "'>
                        <button class='btn btn-info' type='submit'>Descargar</button>
                    </form>
                </td>";
                echo "</tr>";
                $contador++; // Incrementa el contador en cada iteración
            }
        } else {
            echo "<tr><td colspan='5'>No se encontraron recursos compartidos para tu grupo.</td></tr>";
        }
    } else {
        echo "<tr><td colspan='5'>No se encontró el ID del grupo.</td></tr>";
    }
} else {
    echo "<tr><td colspan='5'>No perteneces a ninguna clase.</td></tr>";
}
$stmt_grupo_estudiante->close();

                ?>
                </tbody>
            </table>

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