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
    <br>
    <form id="game-settings" >
    <h1>QuizzGame</h1>
      <label for="cantidad_preguntas">Cantidad de Preguntas:</label>
      <input type="number" min="5" value="5" max="10" name="cantidad_preguntas" id="cantidad_preguntas"required>
      <label for="correct-letter" title="Selecciona el tema">Tema:</label>
    <select id="correct-letter" name="correct-letter" required>
        <option value="vocales">Vocales</option>
        <option value="silabas">Silabas</option>
        <option value="consonantes">Consonantes</option>
        <option value="numeros">Numeros</option>
    </select>
    <br><br>
    <button type="submit">Aplicar configuración</button>
    </form>
    
    <button onclick="window.location.href='../../Pagina/juegos.php'">Salir</button>
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
      <p id="end-game-message"></p>
      <button type="button" id="submit-completion-form" onclick="window.location.href='../../Pagina/juegos.php'">Finalizar</button>
  </div>
  </center>

  <!-- Asegúrate de que script.js esté incluido -->
  <script src="script.js"></script>
</body>
</html>
