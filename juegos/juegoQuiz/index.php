<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Modak&display=swap');
  </style>
  <link rel="icon" href="../../imagenes/LogoS.png">
  <title>Miniminds</title>
  <link rel="stylesheet" href="style.css">
</head>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Modak&display=swap');
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
    <button onclick="window.location.href='../inicioAlumno.php'">Salir</button>
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
      <button id="stop-button">
        <span><i class="fas fa-stop"></i></span>
      </button>
    </div>
  </div>

 <center><div class="end-game" style="display: none;">
    <h2>Terminaste el juego</h2>
    <h1>¡Felicidades!</h1>    
    <button onclick="window.location.href = '../juegos.php'">Salir del juego</button>
  </div>
  </center>

  <!-- Asegúrate de que script.js esté incluido -->
  <script src="script.js"></script>
</body>
</html>
