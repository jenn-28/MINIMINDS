<?php
session_start();
include("../Logeo/conexion.php");

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['username'])) {
    header("Location: ../Logeo/IniciarSesion.php");
    exit();
}

// Obtener parámetros de la URL
$figura = isset($_GET['figura']) ? $_GET['figura'] : null;
$it = isset($_GET['it']) ? $_GET['it'] : null;
$i = isset($_GET['i']) ? $_GET['i'] : null;

// Verificar si los parámetros corresponden a un alumno o profesor
if ($figura && $i && $it) {

echo'
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Miniminds</title>
    <link rel="icon" href="../../imagenes/LogoS.png">
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<div class="inicio">
    <h1>Une los Puntos</h1>
    <br><br>
    <button id="start">Play</button>
    <button onclick="salirA()">Salir</button>
</div>

<div class="juego" style="display: none;">
    <h1>Sigue los puntos para descubrir la figura</h1>
    <div id="reloj">00:00</div> <!-- Reloj añadido -->
    <button id="stop-button" onclick="stop()">Stop</button>
    <div id="feedback"></div>
    <div id="end">
    <h2>Fin del juego</h2>
    <p class="result"></p>
    <form id="completion-form" action="finisher.php" method="POST">
        <input type="hidden" id="task_id" name="task_id" >
        <input type="hidden" id="student_id" name="student_id" >
        <input type="hidden" name="tiempo" id="tiempo">
        <input type="hidden" name="figura" id="figura">
        <button id="submit-completion-form" type="submit">Finalizar</button>
    </form>
    </div>
    <div class="canvas-container">
        <canvas id="canvas"></canvas>
    </div>
</div>
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

    function salirA() {
        mostrarNotificacion("question", "¿Deseas abandonar el juego?", "", "../inicioAlumno.php");
    }
    </script>

</body>
</html>
';
} elseif($figura && $it && !$i) {
    echo'
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Miniminds</title>
        <link rel="icon" href="../../imagenes/LogoS.png">
        <link rel="stylesheet" href="styles.css">
    </head>
    <body>
    <div class="inicio">
    <h1>Une los Puntos</h1>
    <br>
        <button id="start">Play</button>
        <button onclick="window.location.href=\'../inicioProfesor.php\'">Salir</button>
    </div>
    
    <div class="juego" style="display: none;">
        <h1>Sigue los puntos para descubrir la figura</h1>
        <div id="reloj">00:00</div> <!-- Reloj añadido -->
        <button id="stop" onclick="stop()">Stop</button>
        <div id="feedback"></div>
        <div id="end">
        <h2>Fin del juego</h2>
        <p class="result"></p>
        <button style="font-size: 1em;
        font-weight: 400;
        display: block;
        margin: 20px auto;
        background-color: #1065c5;
        color: #ffffff;
        border-radius: 15px;
        padding: 10px 80px 10px 80px;" onclick="salir()">Salir</button>
        </div>
        <div class="canvas-container">
            <canvas id="canvas"></canvas>
        </div>
    </div>
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

    function salir() {
        mostrarNotificacion("question", "¿Deseas abandonar el juego?", "", "../inicioProfesor.php");
    }
    </script>
    </body>
    </html>
    ';
}