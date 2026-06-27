<?php
session_start();
include("../Logeo/conexion.php");

// Inicializa la variable $nombre_usuario con una cadena vacía
$nombre_usuario = ""; 

$foto_perfil = ""; // Inicializar la variable para la foto de perfil

$rol = "";

if (isset($_SESSION["username"])) {
    $username = $_SESSION["username"];
    $consulta = "SELECT nombre_completo, profile_picture, role FROM users WHERE nombre_completo = '$username'";
    $resultado = mysqli_query($conexion, $consulta);

    if ($resultado) {
        $fila = mysqli_fetch_assoc($resultado);
        $nombre_usuario = $fila["nombre_completo"];
        $foto_perfil = $fila["profile_picture"];
        $rol = $fila["role"];
    }

}

// Cerrar conexión
mysqli_close($conexion);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="../imagenes/LogoS.png">
    <link rel="stylesheet" href="styleJuegos.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <title>Miniminds</title>
    <link rel="icon" href="../imagenes/LogoS.png">

</head>
<?php
if($rol == 'student'){
    ?>
    <header>
        <div id="mySidenav" class="sidenav">
                <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
                <a href="inicioAlumno.php">Inicio</a>
                <a href="mis_actividades.php">Mis Tareas</a>
                <a href="compartidos.php">Recursos</a>
                <a href="mi_grupo.php">Mi Grupo</a>
                <br><br>
                <div class="dropdown">
                    <a href="#" class="d-flex align-items-center text-decoration-none dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false" style="font-size:15px; color:#000;">
                    
                    <img style="width: 50px;" src="../Logeo/foto_perfil/<?php echo $foto_perfil; ?>" alt="<?php echo $nombre_usuario; ?>" width="28" height="35" class="rounded-circle me-2">
                    <strong><?php echo $nombre_usuario; ?></strong></a>
                <ul class="dropdown-menu" >
                    <li><a class="dropdown-item" href="editarPerfil.php" style="font-size:15px;">Mi Perfil</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item" href="../Logeo/cerrarSesion.php" style="font-size:15px;">Cerrar Sesion</a></li>
                </ul>
                </div>
        </div> 
        <div class="menu-fixed">
                <span onclick="openNav()"><i class="fa-solid fa-bars"></i> <?php echo $nombre_usuario; ?> </span>
        </div>
    </header>

<body style="background-color:#31c2fcd2;">
     <h1 class="titulo">Juegos</h1>
    <div class="main-content">
        <div class="cartas">
            <div class="card">
                <img src="../imagenes/Logo.jpg" class="card-img-top" alt="">
                <div class="card-body">
                    <h5 class="card-title">Memorama</h5>
                    <a href="../juegos/memorama/" class="btn-game">Jugar</a>
                </div>
            </div>
            <div class="card">
                <img src="../imagenes/Logo.jpg" class="card-img-top" alt="">
                <div class="card-body">
                    <h5 class="card-title">JoinPoint</h5>
                    <a href="../juegos/puntos" class="btn-game">Jugar</a>
                </div>
            </div>
            <div class="card">
                <img src="../imagenes/Logo.jpg" class="card-img-top" alt="">
                <div class="card-body">
                    <h5 class="card-title">BubblesGame</h5>
                    <a href="../juegos/bubblesGame/index.php" class="btn-game">Jugar</a>
                </div>
            </div>
        </div>
        <div class="cartas">
            <div class="card">
                <img src="../imagenes/Logo.jpg" class="card-img-top" alt="">
                <div class="card-body">
                    <h5 class="card-title">Draw</h5>
                    <a href="../juegos/Draw/index.html" class="btn-game">Jugar</a>
                </div>
            </div>
            <div class="card">
                <img src="../imagenes/Logo.jpg" class="card-img-top" alt="">
                <div class="card-body">
                    <h5 class="card-title">3 en raya</h5>
                    <a href="../juegos/gato/index.html" class="btn-game">Jugar</a>
                </div>
            </div>
            <div class="card">
                <img src="../imagenes/Logo.jpg" class="card-img-top" alt="">
                <div class="card-body">
                    <h5 class="card-title">Conect4</h5>
                    <a href="../juegos/conect4/index.html" class="btn-game">Jugar</a>
                </div>
            </div>
        </div>
    </div>
<br>
<?php
}elseif($rol == 'teacher'){
    ?>
    <header>
        <div id="mySidenav" class="sidenav">
                <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
                <a href="inicioProfesor.php">Inicio</a>
                <a href="acciones/index.php">Acciones</a>
                <a href="send_email.php">Sugerencias</a>
                <br><br>
                <div class="dropdown">
                    <a href="#" class="d-flex align-items-center text-decoration-none dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false" style="font-size:15px; color:#000;">
                    
                    <img style="width: 50px;" src="../Logeo/foto_perfil/<?php echo $foto_perfil; ?>" alt="<?php echo $nombre_usuario; ?>" width="28" height="35" class="rounded-circle me-2">
                    <strong><?php echo $nombre_usuario; ?></strong></a>
                <ul class="dropdown-menu" >
                    <li><a class="dropdown-item" href="editarPerfil.php" style="font-size:15px;">Mi Perfil</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item" href="../Logeo/cerrarSesion.php" style="font-size:15px;">Cerrar Sesion</a></li>
                </ul>
                </div>
        </div> 
        <div class="menu-fixed">
                <span onclick="openNav()"><i class="fa-solid fa-bars"></i> <?php echo $nombre_usuario; ?> </span>
        </div>
    </header>
<body style="background-color: #31c2fcd2;">
        <h1 class="titulo">Juegos</h1>

    <div class="main-content">
        <div class="cartas">
            <div class="card">
                <img src="../imagenes/Logo.jpg" class="card-img-top" alt="">
                <div class="card-body">
                    <h5 class="card-title">Memorama</h5>
                    <a href="../juegos/memorama" class="btn-game">Jugar</a>
                </div>
            </div>
            <div class="card">
                <img src="../imagenes/Logo.jpg" class="card-img-top" alt="">
                <div class="card-body">
                    <h5 class="card-title">Quiz Game</h5>
                    <a href="../juegos/preguntas" class="btn-game">Jugar</a>
                </div>
            </div>
            <div class="card">
                <img src="../imagenes/Logo.jpg" class="card-img-top" alt="">
                <div class="card-body">
                    <h5 class="card-title">BubblesGame</h5>
                    <a href="../juegos/bubblesGame" class="btn-game">Jugar</a>
                </div>
            </div>
        </div>
        <div class="cartas">
            <div class="card">
                <img src="../imagenes/Logo.jpg" class="card-img-top" alt="">
                <div class="card-body">
                    <h5 class="card-title">Conect points</h5>
                    <a href="../juegos/puntos" class="btn-game">Jugar</a>
                </div>
            </div>
            <div class="card">
                <img src="../imagenes/Logo.jpg" class="card-img-top" alt="">
                <div class="card-body">
                    <h5 class="card-title">Quizzes</h5>
                    <a href="acciones/index.php" class="btn-game">Crear quiz</a>
                </div>
            </div>
            <div class="card">
                <img src="../imagenes/Logo.jpg" class="card-img-top" alt="">
                <div class="card-body">
                    <h5 class="card-title">BubblesGame</h5>
                    <a href="../juegos/bubblesGame" class="btn-game">Jugar</a>
                </div>
            </div>
        </div>
    </div>
<br>

<?php
}else{
    echo 'Usuario invalido.';
}

?>

<footer>
        <p>&copy; 2024 Miniminds</p>
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
        document.getElementById("main").style.marginLeft= "0";
        document.body.style.backgroundColor = "white";
    }
</script>

</body>
</html>