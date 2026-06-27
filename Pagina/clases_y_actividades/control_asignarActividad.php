<?php
session_start();
include("../../Logeo/conexion.php");

// Función para mostrar notificaciones de SweetAlert
function mostrarNotificacion($icon, $titulo, $mensaje, $redireccion = null) {
    echo '<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            Swal.fire({
                icon: "' . $icon . '",
                title: "' . $titulo . '",
                text: "' . $mensaje . '",
                confirmButtonText: "Aceptar"
            }).then(function() {
                if ("' . $redireccion . '" !== "") {
                    window.location.href = "' . $redireccion . '";
                }
            });
        });
    </script>';
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $class_id = $_POST["class_id"];
    $task_name = $_POST["task_name"];
    $description = $_POST["description"];
    $due_date = $_POST["due_date"];
    $juego_id = $_POST["juego-select"];
    $created_by = $_SESSION["username"];
    $fecha_actual = $_POST["fecha_actual"];

    // Obtener la configuración específica del juego del campo oculto
    $configuracion_juego = [];
    if ($juego_id === "2") {
        $tema = $_POST["temas"];
        $nivel_dificultad = $_POST["nivel_dificultad"];
        $configuracion_juego = [
            "task_id" => "",
            "tema" => $tema,
            "dificultad" => $nivel_dificultad
        ];
        $nombre_archivo = "configuracion-memorama";
    } elseif ($juego_id === "1") {
        $tiempo_limite = $_POST["tiempo_limite"];
        $letra_correcta = $_POST["correct-letter"];
        $configuracion_juego = [
            "task_id" => "",
            "tiempo_limite" => $tiempo_limite,
            "letra_correcta" => $letra_correcta
        ];
        $nombre_archivo = "configuracion-burbujas";
    } elseif ($juego_id === "3") {
        $cantidad_preguntas = $_POST["cantidad_preguntas"];
        $categoria = $_POST["categoria"];
        $configuracion_juego = [
            "task_id" => "",
            "cantidad_preguntas" => $cantidad_preguntas,
            "categoria" => $categoria
        ];
        $nombre_archivo = "configuracion-quiz";
    } elseif($juego_id === "4"){
        $figura = $_POST["figura"];
        $configuracion_juego = [
            "task_id" => "",
            "figura" => $figura
        ];
        $nombre_archivo = "configuracion-figurasP";
    }

    // Guardar otros datos en la base de datos
    $consulta = "INSERT INTO tasks (class_id, task_name, descripcion, juego_id, created_by, fecha_creacion, fecha_expiracion) VALUES ('$class_id', '$task_name', '$description', '$juego_id', '$created_by', '$fecha_actual', '$due_date')";
    $resultado = mysqli_query($conexion, $consulta);

    if ($resultado) {
        // Obtener el ID de la tarea insertada
        $task_id = mysqli_insert_id($conexion);

        // Actualizar el campo task_id en la configuración del juego
        $configuracion_juego["task_id"] = $task_id;

        // Guardar la configuración en un archivo JSON y obtener su URL
        $carpeta_actividades = "actividades/";
        $archivo_config = $nombre_archivo . ".json"; // Nombre de archivo único
        $archivo_configuracion = $carpeta_actividades . $archivo_config;

        if (file_exists($archivo_configuracion)) {
            // Leer el contenido existente del archivo JSON
            $configuracion_existente_json = file_get_contents($archivo_configuracion);
            if ($configuracion_existente_json !== false) {
                // Decodificar el contenido existente del archivo JSON
                $configuracion_existente = json_decode($configuracion_existente_json, true);
            } else {
                echo "Error al leer el archivo de configuración existente.";
                exit; // Salir del script si no se puede leer el archivo
            }
        } else {
            $configuracion_existente = []; // Si el archivo no existe, crear un array vacío
        }

        // Guardar la nueva configuración en el array existente
        $configuracion_existente[] = $configuracion_juego;

        // Codificar el contenido actualizado del archivo JSON
        $configuracion_actualizado_json = json_encode($configuracion_existente);

        // Escribir la configuración actualizada en el archivo JSON
        if (file_put_contents($archivo_configuracion, $configuracion_actualizado_json) !== false) {
            // Notificación
            mostrarNotificacion("success", "Tarea Asignada!", "Tarea asignada correctamente.", "../inicioProfesor.php");
        } else {
            mostrarNotificacion("error", "Error", "Error al guardar la configuración del juego en el archivo JSON. Inental mas tarde", "../inicioProfesor.php");
        }
    } else {
        mostrarNotificacion("error", "Error", "Error al asignar la tarea. Inental mas tarde", "../inicioProfesor.php");
    }

    $consulta = "UPDATE tasks SET class_id = '$class_id', task_name = '$task_name', descripcion = '$description', juego_id = '$juego_id', created_by = '$created_by', configuracion = '$archivo_config', fecha_creacion = '$fecha_actual', fecha_expiracion = '$due_date' WHERE task_id = $task_id";
    $resultado = mysqli_query($conexion, $consulta);

    mysqli_close($conexion);
}
?>
