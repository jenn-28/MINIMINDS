<?php
session_start();
include("../Logeo/conexion.php");

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['username'])) {
    header("Location: ../Logeo/IniciarSesion.php");
    exit();
}

// Obtener parámetros de la URL
$cantidad_p = isset($_GET['cant_p']) ? $_GET['cant_p'] : null;
$tema = isset($_GET['tema']) ? $_GET['tema'] : null;
$it = isset($_GET['it']) ? $_GET['it'] : null;
$i = isset($_GET['i']) ? $_GET['i'] : null;

// Verificar si los parámetros corresponden a un alumno o profesor
if ($cantidad_p && $tema && $it && $i) {
    echo '
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
  <style>
    @import url("https://fonts.googleapis.com/css2?family=Modak&display=swap");
  </style>
  <link rel="icon" href="../../imagenes/LogoS.png">
  <title>Miniminds</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <div class="controls-container" style="position: fixed; display:block;">
    <h1>QuizzGame</h1>
    <br>
    <!-- Asegúrate de que el botón "Play" tenga el id "start" -->
    <button id="start">Play</button>
    <button onclick="window.location.href=\'../inicioAlumno.php\'">Salir</button>
  </div>

  <div class="question-card" style="display: none;">
    <div id="game-info" style="display: none;">
      <p>Pregunta <span id="question-number">1</span> de <span id="total-questions"></span></p>
      Tiempo: <span id="time-counter">00:00</span> <span style="margin: 0 15px 0 15px;"> | |</span>
      Puntos: <span id="score-counter">0</span>
      <br><br>
    </div>

    <div id="question-container" style="display: none;">
      <div id="question"></div>
      <img id="question-image" src="" alt="Imagen de la pregunta">
      <div id="options"></div>
    </div>

    <div id="stop">
      <button id="stop-button" onclick="stop()">
        <span><i class="fas fa-stop"></i></span>
      </button>
    </div>
  </div>

  <center><div class="end-game" style="display: none;">
    <h2>Terminaste el juego</h2>
    <h1>¡Felicidades!</h1>
    <form id="completion-form" action="finisher.php" method="POST">
      <p id="end-game-message"></p>
      <input type="hidden" id="task_id" name="task_id">
      <input type="hidden" id="student_id" name="student_id">
      <input type="hidden" name="tiempo" id="tiempo">
      <input type="hidden" name="puntaje" id="puntaje">
      <input type="hidden" name="porcentaje" id="porcentaje">
      <button type="button" id="submit-completion-form">Finalizar</button>
    </form>
  </div>
  </center>

  <!-- Asegúrate de que script.js esté incluido -->
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
} elseif ($cantidad_p && $tema && $it && !$i) {
    echo '
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
  <style>
    @import url("https://fonts.googleapis.com/css2?family=Modak&display=swap");
  </style>
  <link rel="icon" href="../../imagenes/LogoS.png">
  <title>Miniminds</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <div class="controls-container" style="position: fixed; display:block;">
    <h1>QuizzGame</h1>
    <br>
    <!-- Asegúrate de que el botón "Play" tenga el id "start" -->
    <button id="start">Play</button>
    <button onclick="window.location.href=\'../inicioProfesor.php\'">Salir</button>
  </div>

  <div class="question-card" style="display: none;">
    <div id="game-info" style="display: none;">
      <p>Pregunta <span id="question-number">1</span> de <span id="total-questions"></span></p>
      Tiempo: <span id="time-counter">00:00</span> <span style="margin: 0 15px 0 15px;"> | |</span>
      Puntos: <span id="score-counter">0</span>
      <br><br>
    </div>

    <div id="question-container" style="display: none;">
      <div id="question"></div>
      <img id="question-image" src="" alt="Imagen de la pregunta">
      <div id="options"></div>
    </div>

    <div id="stop">
      <button onclick="stop()">
        <span><i class="fas fa-stop"></i></span>
      </button>
    </div>
  </div>

  <center><div class="end-game" style="display: none;">
    <h2>Terminaste el juego</h2>
    <h1>¡Felicidades!</h1>
    <p id="end-game-message"></p>
    <button type="button" onclick="window.location.href=\'../inicioProfesor.php\'" id="completion">Salir</button>
  </div>
  </center>

  <!-- Asegúrate de que script.js esté incluido -->
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
