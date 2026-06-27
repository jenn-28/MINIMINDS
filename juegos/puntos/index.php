<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Miniminds</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="shortcut icon" href="../../imagenes/LogoS.png" type="image/x-icon">
</head>
<body>
    <h1>Sigue los puntos para descubrir la figura</h1>
    <label for="shape">Elige una figura:</label>
    <select id="shape">
        <option value="">Elige una figura</option>
        <option value="heart">Corazón</option>
        <option value="house">Casa</option>
        <option value="star">Estrella</option>
        <option value="fish">Pez</option>
        <option value="butterfly">Mariposa</option>
    </select>
    <label for="color">Elige un color para pintar la figura:</label>
    <input type="color" id="color" value="#ff0000">
    <button class="btn" id="fillButton" disabled>Pintar</button>
    <button class="btn" onclick="window.location.href='../../Pagina/juegos.php'">Salir</button>

    <div id="feedback"></div>
    <div class="canvas-container">
        <canvas id="canvas"></canvas>
    </div>
    <script src="script.js"></script>
</body>
</html>
