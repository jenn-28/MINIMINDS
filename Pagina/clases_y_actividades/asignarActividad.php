<?php
session_start();

include("../../Logeo/conexion.php");
$nombre_profesor = ""; // inicializar la variable

if (isset($_SESSION["username"])) {
    $username = $_SESSION["username"];
    $consulta = "SELECT nombre_completo FROM users WHERE nombre_completo = '$username'";
    $resultado = mysqli_query($conexion, $consulta);

    if ($resultado) {
        $fila = mysqli_fetch_assoc($resultado);
        $nombre_profesor = $fila["nombre_completo"];
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Miniminds</title>
    <link rel="icon" href="../../imagenes/LogoS.png">
    <!-- Enlace a Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap');
        body{
            background-color: #DAF9FF;
            font-family: "Poppins", sans-serif;
        }

        h2{
            font-weight: 800;
        }
        h3{
            font-weight: 700;
        }
        
    </style>
</head>
<body>
    <div class="container">
        <h2>Crear y Asignar Tarea</h2>
        <form action="control_asignarActividad.php" method="POST">
            <div class="form-group">
                <label for="class_id">Seleccione el Grupo:</label>
                <select class="form-control" name="class_id" id="class_id_grupo">
                    <?php
                    // Realizar la consulta para obtener los grupos disponibles
                    $consulta_grupo = "SELECT group_id, group_name, group_code FROM groups WHERE created_by = '$nombre_profesor'";
                    $datos_grupo = mysqli_query($conexion, $consulta_grupo);

                    // Verificar si se obtuvieron resultados
                    if (mysqli_num_rows($datos_grupo) > 0) {
                        // Iterar sobre los resultados y construir las opciones del menú desplegable
                        while ($row = mysqli_fetch_assoc($datos_grupo)) {
                            echo "<option value='" . $row['group_id'] . "'>" . $row['group_name'] ." - - ".  $row['group_code'] ."</option>";
                        }
                    } else {
                        echo "<option value='' disabled>No hay grupos disponibles</option>";
                    }
                    ?>
                </select>
            </div>

            <!-- Agregar campo oculto para la configuración del juego -->
            <input type="hidden" name="configuracion_juego" id="configuracion_juego">

            <div class="form-group">
                <label for="task_name">Nombre de la Tarea:</label>
                <input type="text" class="form-control" id="task_name" name="task_name" required>
            </div>
            <div class="form-group">
                <label for="description">Descripción de la Tarea:</label>
                <textarea class="form-control" id="description" name="description" rows="3" required></textarea>
            </div>
                <input type="hidden" name="fecha_actual" value="<?php date_default_timezone_set('America/Mexico_City'); // Establecer la zona horaria a México
                $fecha = new DateTime();
                $fecha->modify('-1 hour'); // Restar una hora
                echo $fecha->format('Y-m-d H:i:s'); ?>">

            <div class="form-group">
                <label for="due_date">Fecha de Vencimiento:</label>
                <input type="datetime-local" class="form-control" id="due_date" name="due_date" required>
            </div>

            <div class="form-group">
            <label for="juego-select">Selecciona la actividad:</label>
            <div class="input-group">
             <select class="form-control" name="juego-select" id="juego-select">
                 <?php
                    // Realizar la consulta para obtener los juegos disponibles
                    $consulta_juego = "SELECT * FROM juegos";
                    $datos_juego = mysqli_query($conexion, $consulta_juego);

                    // Verificar si se obtuvieron resultados
                    if (mysqli_num_rows($datos_juego) > 0) {
                        // Iterar sobre los resultados y construir las opciones del menú desplegable
                        while ($row = mysqli_fetch_assoc($datos_juego)) {
                            echo "<option style='color=#000;' value='" . $row['juego_id'] . "'>" . $row['nombre_juego'] ."</option>";
                        }
                    } else {
                        echo "<option value='' disabled>No hay juegos disponibles</option>";
                    }
                ?>
             </select>
            <div class="input-group-append">
            <button class="btn btn-outline-secondary" type="button" onclick="mostrarOpciones()">Aplicar</button>
           </div>
          </div>
         </div>


           <!--Configuracion individual para cada juego -->
<div id="opciones-memorama" class="configuracion-juego" style="display: none;">
    <br><br>
    <h3>Memorama</h3>
    <label for="temas">Tema:</label>
    <select id="memorama_temas" name="temas" required>
        <option value="numeros">Numeros</option>
        <option value="vocales">Vocales</option>
        <option value="abecedario">Abecedario</option>
        <option value="animales">Animales</option>
        <option value="figuras">Figuras</option>
    </select>
    <br><br>
    <label for="nivel_dificultad">Nivel de dificultad:</label>
    <select id="memorama_nivel_dificultad" name="nivel_dificultad" required>
        <option value="facil">Fácil</option>
        <option value="dificil">Difícil</option>
    </select>
    <br><br><br>
</div>

<div id="opciones-burbujas" class="configuracion-juego" style="display: none;">
    <br><br>
    <h3>BubblesGame</h3>
    <label for="tiempo_limite">Tiempo límite (segundos):</label>
    <input type="number" id="burbujas_tiempo_limite" name="tiempo_limite" min="10" value="10" max="90" required>
    <br><br>
    <label for="letra_correcta">Letra correcta:</label>
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
        <br><br><br>
</div>

<div id="opciones-preguntas" class="configuracion-juego" style="display: none;">
    <br><br>
    <h3>QuizGame</h3>
    <label for="cantidad_preguntas">Cantidad de preguntas:</label>
    <input type="number" id="preguntas_cantidad_preguntas" name="cantidad_preguntas" min="5" value="5" max="12" required>
    <br><br>
    <label for="categoria">Categoría:</label>
    <select id="preguntas_categoria" name="categoria" required>
        <option value="vocales">Vocales</option>
        <option value="silabas">Sílabas</option>
        <option value="consonantes">Consonantes</option>
        <option value="numeros">Números</option>
    </select>
    <br><br><br>
</div>

<div id="opciones-unionPuntos" class="configuracion-juego" style="display: none;">
    <br><br>
    <h3>Unione los puntos (secuencia de numeros)</h3>
    <label for="figura">Figura:</label>
    <select id="puntos_figura" name="figura" required>
        <option value="heart">Corazon(1-8)</option>
        <option value="house">Casa(1-9)</option>
        <option value="star">Estrella(1-10)</option>
        <option value="fish">Pez(1-13)</option>
        <option value="butterfly">Mariposa(1-20)</option>
    </select>
    <br><br><br>
</div>

<!-- Agregar campo oculto para la configuración del juego -->
<input type="hidden" name="configuracion_juego" id="configuracion_juego_url">

            <button type="submit" class="btn btn-primary">Crear y Asignar Tarea</button>
            <br><br>
        </form>
        <div class="footer-btn" style="float: right;">
        <a class="btn btn-danger" onclick="limpiarSeleccion()">Cancelar</a>
        <a type="button" onclick="window.location.href='../inicioProfesor.php'" class="btn btn-secondary">Salir</a>
            <br><br>
        </div>
    </div>

    <!-- Enlace a jQuery y Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <?php
    mysqli_free_result($datos_grupo);
    mysqli_close($conexion);
    ?>


<script>
// Limpiar la selección al hacer clic en el botón "Cancelar"
function limpiarSeleccion() {
    var selectGrupo = document.getElementById('class_id_grupo');
    var selectJuego = document.getElementById('juego-select');

    selectGrupo.selectedIndex = 0;
    selectJuego.selectedIndex = 0;

    var opcionesMemorama = document.getElementById('opciones-memorama');
    var opcionesBurbujas = document.getElementById('opciones-burbujas');
    var opcionesPreguntas = document.getElementById('opciones-preguntas');
    var opcionesPuntos = document.getElementById('opciones-unionPuntos');

    opcionesMemorama.style.display = 'none';
    opcionesBurbujas.style.display = 'none';
    opcionesPreguntas.style.display = 'none';
    opcionesPuntos.style.display = 'none';

    document.getElementById('task_name').value = '';
    document.getElementById('description').value = '';
    document.getElementById('due_date').value = '';
}
// Mostrar las opciones correspondientes al juego seleccionado
function mostrarOpciones() {
    var juegoSelect = document.getElementById('juego-select');
    var opcionesMemorama = document.getElementById('opciones-memorama');
    var opcionesBurbujas = document.getElementById('opciones-burbujas');
    var opcionesPreguntas = document.getElementById('opciones-preguntas');
    var opcionesPuntos = document.getElementById('opciones-unionPuntos');

    opcionesMemorama.style.display = 'none';
    opcionesBurbujas.style.display = 'none';
    opcionesPreguntas.style.display = 'none';
    opcionesPuntos.style.display = 'none';

    if (juegoSelect.value === '1') {
        opcionesBurbujas.style.display = 'block';
    } else if (juegoSelect.value === '2') {
        opcionesMemorama.style.display = 'block';
    } else if (juegoSelect.value === '3') {
        opcionesPreguntas.style.display = 'block';
    } else if (juegoSelect.value === '4'){
        opcionesPuntos.style.display = 'block';
    }
}
</script>

</body>
</html>
