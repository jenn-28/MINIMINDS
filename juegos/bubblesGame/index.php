<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Miniminds</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

  <style>
    @import url('https://fonts.googleapis.com/css2?family=Modak&display=swap');
  </style>

  <link rel="icon" href="../../imagenes/LogoS.png">
  <link rel="stylesheet" href="styles.css">
</head>
<body>
<div id="game-header" style="display: none;">
    <div id="score">Puntos: <span id="score-value">0</span></div>
    <div id="timer">Tiempo: <span id="timer-value">00:00</span></div>
   <center> <div id="correct-letter-info">Letra: <span style="font-size:40px; color: #2AA220;
  font-weight: bold; text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.5); " id="correct-letter-value"></span></div> </center>
    <div class="stop" style="display: none; float:right; margin-top: -50px;">
    <button id="stop-game" onclick="window.location.href='index.php'">Stop</button>
    </div>
  </div>
    <div id="parameters">
      <form id="game-settings-form">
    <h1 style="font-family: 'Modak', system-ui; 
    font-size: 3em;
    font-weight: 400; "><center>Bubbles Game</center></h1>
      <h2>Confijuracion del Juego</h2>
      <label for="time-limit" title="Por favor ingresa un valor entre 10 y 60 segundos">Tiempo límite (s):</label>
    <input type="number" id="time-limit" name="time-limit" min="10" value="10" max="60" required><br><br>
    <label for="correct-letter" title="Por favor selecciona una letra">Letra:</label>
    <select id="correct-letter" name="correct-letter" required>
        <option value="A">A</option>
        <option value="B">B</option>
        <option value="C">C</option>
        <option value="D">D</option>
        <option value="E">E</option>
        <option value="F">F</option>
        <option value="G">G</option>
        <option value="H">H</option>
        <option value="I">I</option>
        <option value="J">J</option>
        <option value="K">K</option>
        <option value="L">L</option>
        <option value="M">M</option>
        <option value="N">N</option>
        <option value="Ñ">Ñ</option>
        <option value="O">O</option>
        <option value="P">P</option>
        <option value="Q">Q</option>
        <option value="R">R</option>
        <option value="S">S</option>
        <option value="T">T</option>
        <option value="U">U</option>
        <option value="V">V</option>
        <option value="W">W</option>
        <option value="X">X</option>
        <option value="Y">Y</option>
        <option value="Z">Z</option>
      </select>
      <br><br>
      <button type="submit">Aplicar configuración</button>
      <button onclick="window.location.href = '../../Pagina/juegos.php'">Salir del juego</button>
      </form>
    </div>
    <div id="game-container" style="display: none;">  
    
  </div>

  <script src="script.js"></script>
  <script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script> 

  
</body>
</html>
