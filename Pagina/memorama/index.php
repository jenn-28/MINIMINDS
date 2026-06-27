<?php
session_start();
include("../Logeo/conexion.php");

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['username'])) {
    header("Location: ../Logeo/IniciarSesion.php");
    exit();
}

// Obtener parámetros de la URL
$tema = isset($_GET['tem']) ? $_GET['tem'] : null;
$level = isset($_GET['level']) ? $_GET['level'] : null;
$it = isset($_GET['it']) ? $_GET['it'] : null;
$i = isset($_GET['i']) ? $_GET['i'] : null;

// Verificar si los parámetros corresponden a un alumno o profesor
if ($tema && $level && $it && $i) {
echo'
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <title>Miniminds</title>
    <link rel="icon" href="../../imagenes/LogoS.png">
</head>
   
<header>
        
    </header>

      <body>
        <div class="wrapper">
          <div class="stats-container">
            <div id="moves-count"></div>
            <div id="time"></div>
          </div>
          <div class="game-container"></div>
          <button id="stop" onclick="salir()" class="hide"><i class="fa-solid fa-stop"></i></i></button>

        </div>
        <div class="controls-container" style="position: fixed;">
        <h2 class="titulo2 titulo">M i n i m i n d s</h2>
        <h1 class="titulo">M E M O R A M A</h1>
          <button id="start" ><i class="fa-solid fa-play"></i></button>
          <button id="back" onclick="window.location.href=\'../inicioAlumno.php\'">Regresar al Inicio</button>
        </div>
        
        <div id="game-complete" style="display:none; box-sizing: content-box; width: 50%; padding: 4% 3% 5% 3%; margin-top: 1%; margin-bottom: 10%; background-color: #f3e673; position: absolute; transform: translate(-50%, -50%); left: 50%; top: 50%; border-radius: 1em; box-shadow: 0 0.9em 2.8em rgba(86, 66, 0, 0.2);">
    <form id="completion-form" action="finisher.php" method="POST">
        <input type="hidden" name="id_task" id="id_task">
        <input type="hidden" name="tiempo" id="tiempo">
        <input type="hidden" name="movimientos" id="movimientos">
        <input type="hidden" name="id_estudiante" id="id_estudiante">
        <p id="result"></p>
        <center><button id="finish-game-btn" type="submit"
        style="font-size: 1em;
        font-weight: 400;
        display: block;
        margin: 20px auto;
        background-color: #1065c5;
        color: #ffffff;
        border-radius: 15px;
        padding: 10px 80px 10px 80px;">Finalizar</button></center>
    </form>
</div>


        <script src="script3.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
        <script>
            function mostrarNotificacion(icon, titulo, mensaje, redireccion = null) {
                $(document).ready(function() {
                    Swal.fire({
                        icon: icon,
                        title: titulo,
                        text: mensaje,
                        confirmButtonText: "Aceptar"
                    }).then(function(result) {
                        if (result.isConfirmed && redireccion !== "") {
                            window.location.href = redireccion;
                        }
                    });
                });
            }
        
            function salir() {
                // Mostrar la alerta de SweetAlert y obtener la respuesta del usuario
                mostrarNotificacion("question", "¿Deseas abandonar el juego?", "", "../inicioAlumno.php");
            }
        
        </script>
      </body>
    </html>';
}elseif($tema && $level && $it && !$i){
  echo'
  <!DOCTYPE html>
  <html lang="es">
  <head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <link rel="stylesheet" href="css.css">
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
      <title>Miniminds</title>
      <link rel="icon" href="../../imagenes/LogoS.png">
  </head>
     
  <header>
          
      </header>
  
        <body>
          <div class="wrapper">
            <div class="stats-container">
              <div id="moves-count"></div>
              <div id="time"></div>
            </div>
            <div class="game-container"></div>
            <button id="stop" class="hide" style="display: none;"><i class="fa-solid fa-stop"></i></i></button>
            <button style="font-size: 1em;
            font-weight: 400;
            display: block;
            margin: 20px auto;
            background-color: #1065c5;
            color: #ffffff;
            border-radius: 15px;
            padding: 10px 80px 10px 80px;" onclick="salir()">Salir</button>
  
          </div>
          <div class="controls-container" style="position: fixed;">
          <h2 class="titulo2 titulo">M i n i m i n d s</h2>
          <h1 class="titulo">M E M O R A M A</h1>
            <button id="start"><i class="fa-solid fa-play"></i></button>
            <button style="font-size: 1em;
            font-weight: 400;
            display: block;
            margin: 20px auto 23em auto;
            background-color: #1065c5;
            color: #ffffff;
            border-radius: 15px;
            padding: 10px 80px 10px 80px;" onclick="window.location.href=\'../inicioProfesor.php\'">Salir</button>
            <button id="back" style="display: none;">Regresar al Inicio</button>
          </div>
          
          <div id="game-complete" style="display:none; box-sizing: content-box; width: 50%; padding: 4% 3% 5% 3%; margin-top: 1%; margin-bottom: 10%; background-color: #f3e673; position: absolute; transform: translate(-50%, -50%); left: 50%; top: 50%; border-radius: 1em; box-shadow: 0 0.9em 2.8em rgba(86, 66, 0, 0.2);">
          <button style="font-size: 1em;
          font-weight: 400;
          display: block;
          margin: 20px auto;
          background-color: #1065c5;
          color: #ffffff;
          border-radius: 15px;
          padding: 10px 80px 10px 80px;" onclick="salir()">Salir</button>
         <form id="completion-form" action="finisher.php" method="POST">
            <input type="hidden" name="id_task" id="id_task">
            <input type="hidden" name="tiempo" id="tiempo">
            <input type="hidden" name="movimientos" id="movimientos">
            <input type="hidden" name="id_estudiante" id="id_estudiante">
            <p id="result"></p>
            <center><button id="finish-game-btn" type="submit" style="font-size: 1em;
            font-weight: 400;
            display: block;
            margin: 20px auto;
            background-color: #1065c5;
            color: #ffffff;
            border-radius: 15px;
            padding: 10px 80px 10px 80px;">Finalizar</button></center>
         </form>
         </div>
  
        <script src="script3.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
        <script>
            function mostrarNotificacion(icon, titulo, mensaje, redireccion = null) {
                $(document).ready(function() {
                    Swal.fire({
                        icon: icon,
                        title: titulo,
                        text: mensaje,
                        confirmButtonText: "Aceptar"
                    }).then(function(result) {
                        if (result.isConfirmed && redireccion !== "") {
                            window.location.href = redireccion;
                        }
                    });
                });
            }
        
            function salir() {
                // Mostrar la alerta de SweetAlert y obtener la respuesta del usuario
                mostrarNotificacion("question", "¿Deseas abandonar el juego?", "", "../inicioProfesor.php");
            }
        
        </script>
      </body>
    </html>';
}