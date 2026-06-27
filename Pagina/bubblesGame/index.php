<?php
session_start();
include("../Logeo/conexion.php");

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['username'])) {
    header("Location: ../Logeo/IniciarSesion.php");
    exit();
}

// Obtener parámetros de la URL
$tiempoL = isset($_GET['time']) ? $_GET['time'] : null;
$letra = isset($_GET['letra']) ? $_GET['letra'] : null;
$it = isset($_GET['it']) ? $_GET['it'] : null;
$i = isset($_GET['i']) ? $_GET['i'] : null;

// Verificar si los parámetros corresponden a un alumno o profesor
if ($tiempoL && $letra && $it && $i) {
    echo '
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Miniminds</title>
  <link rel="icon" href="../../imagenes/LogoS.png">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
  <style>
    @import url("https://fonts.googleapis.com/css2?family=Modak&display=swap");
  </style>
  <link rel="stylesheet" href="styles.css">
</head>
<body>
  <div id="inicio-juego">
    <h1>BubblesGame</h1>
    <button id="start">Play</button>
    <button onclick="window.location.href=\'../inicioAlumno.php\'">Salir</button>
  </div>
  <div id="game-header" style="display: none;">
    <div id="score">Puntos: <span id="score-value">0</span></div>
    <div id="timer">Tiempo: <span id="time-limit">00:00</span></div>
    <center>
      <div id="correct-letter">Letra: <span style="font-size:40px; color: #2AA220; font-weight: bold; text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.5);" id="correct-letter-value"></span></div>
    </center>
    <div class="stop" style="display: none; float:right; margin-top: -50px;">
      <button id="stop-button">Stop</button>
    </div>
  </div>
  <div id="game-container" style="display: none;"></div>
  <div class="end-game" id="end-game" style="display: none;">
    <p id="result" style="z-index: 100;"></p>
  </div>
  
<div class="overlay" id="overlay" style="display: hidden; z-index:1;">
</div>
  <script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>
  <script src="script.js"></script>
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

    function stop() {
        // Mostrar la alerta de SweetAlert y obtener la respuesta del usuario
        mostrarNotificacion("question", "¿Deseas abandonar el juego?", "", "../inicioAlumno.php");
    }

</script>
</body>
</html>';
}
elseif ($tiempoL && $letra && $it && !$i) {
  echo '
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Miniminds</title>
  <link rel="icon" href="../../imagenes/LogoS.png">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
  <style>
    @import url("https://fonts.googleapis.com/css2?family=Modak&display=swap");
  </style>
  <link rel="stylesheet" href="styles.css">
</head>
<body>
  <div id="inicio-juego">
    <h1>BubblesGame</h1>
    <button id="start">Play</button>
    <button onclick="window.location.href=\'../inicioProfesor.php\'">Salir</button>
  </div>
  <div id="game-header" style="display: none;">
    <div id="score">Puntos: <span id="score-value">0</span></div>
    <div id="timer">Tiempo: <span id="time-limit">00:00</span></div>
    <center>
      <div id="correct-letter">Letra: <span style="font-size:40px; color: #2AA220; font-weight: bold; text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.5);" id="correct-letter-value"></span></div>
    </center>
    <div class="stop" style="display: none; float:right; margin-top: -50px;">
      <button id="stop-button" style="display: none;>Stop</button>
    </div>
    <div class="stop" style="display: flex; float:right; margin-top: -50px;">
      <button onclick="stop()">Stop</button>
    </div>
  </div>
  <div id="game-container" style="display: none; z-index: 10;">
</div>
<div class="end-game" id="end-game" style="display: none; z-index: -10;">
<p id="result" style="display: none; z-index: -100; pointer-events: none;"></p>
</div>
<div class="overlay" id="overlay" style="display: none; z-index:100;">
<button onclick="stop()">Salir</button>
</div>


  <script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>
  <script src="script.js"></script>
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

    function stop() {
        // Mostrar la alerta de SweetAlert y obtener la respuesta del usuario
        mostrarNotificacion("question", "¿Deseas abandonar el juego?", "", "../inicioProfesor.php");
    }

</script>
</body>
</html>';
}
?>
