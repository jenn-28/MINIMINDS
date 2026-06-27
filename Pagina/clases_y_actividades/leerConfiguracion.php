<?php
session_start();

// Incluir archivo de conexión
include("../../Logeo/conexion.php");

// Variables para almacenar el ID y el nombre del usuario en la sesión actual
$id_usuario = "";
$nombre_usuario = "";

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

// Verificar si hay una sesión activa
if (isset($_SESSION["username"])) {
    $username = $_SESSION["username"];
    
    // Consulta para obtener el ID y el nombre del estudiante
    $consulta_estudiante = "SELECT id, nombre_completo FROM students WHERE nombre_completo = '$username'";
    $resultado_estudiante = mysqli_query($conexion, $consulta_estudiante);

    if ($resultado_estudiante && mysqli_num_rows($resultado_estudiante) > 0) {
        $fila_estudiante = mysqli_fetch_assoc($resultado_estudiante);
        $id_usuario = $fila_estudiante["id"];
        $nombre_usuario = $fila_estudiante["nombre_completo"];

        // Lógica para estudiantes
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Verificar si se reciben los datos esperados
            if (isset($_POST['id_task']) && isset($_POST['json_config'])) {
                $id_tarea = $_POST['id_task'];
                $configuracion = $_POST['json_config'];

                // Imprimir valores en la consola
                echo "<script>console.log('ID de Tarea: " . $id_tarea . "');</script>";
                echo "<script>console.log('Configuración: " . $configuracion . "');</script>";

                // Obtener la ruta del archivo de configuración
                $ruta_archivo_configuracion = "actividades/$configuracion";

                // Verificar si el archivo de configuración existe
                if (file_exists($ruta_archivo_configuracion)) {
                    // Leer el contenido del archivo JSON de configuración
                    $configuracion_json = file_get_contents($ruta_archivo_configuracion);
                    if ($configuracion_json !== false) {
                        // Decodificar el JSON de configuración
                        $configuracion_data = json_decode($configuracion_json, true);

                        if (isset($configuracion_data)) {
                            $encontrado = false;
                            foreach ($configuracion_data as $config) {
                                if (isset($config['task_id']) && $config['task_id'] == $id_tarea) {
                                    $configuracion_tarea = $config;
                                    $encontrado = true;
                                    break;
                                }
                            }

                            if ($encontrado) {
                                // Imprimir valores de configuración de la tarea
                                echo "<script>console.log('Configuración de Tarea Encontrada: " . json_encode($configuracion_tarea) . "');</script>";

                                // Determinar a qué juego dirigir al alumno según la configuración de la tarea que coincide con el mismo task_id
                                if (isset($configuracion_tarea['tema']) && isset($configuracion_tarea['dificultad'])) {
                                    // Redirigir al alumno al juego de memorama con la configuración correspondiente
                                    header("Location: ../memorama/index.php?tem={$configuracion_tarea['tema']}&level={$configuracion_tarea['dificultad']}&i={$id_usuario}&it={$configuracion_tarea['task_id']}");
                                    exit();
                                } elseif (isset($configuracion_tarea['tiempo_limite']) && isset($configuracion_tarea['letra_correcta'])) {
                                    // Redirigir al alumno al juego de burbujas con la configuración correspondiente
                                    header("Location: ../bubblesGame/index.php?time={$configuracion_tarea['tiempo_limite']}&letra={$configuracion_tarea['letra_correcta']}&i={$id_usuario}&it={$configuracion_tarea['task_id']}");
                                    exit();
                                } elseif (isset($configuracion_tarea['cantidad_preguntas']) && isset($configuracion_tarea['categoria'])) {
                                    // Redirigir al alumno al juego de quiz con la configuración correspondiente
                                    header("Location: ../preguntas/index.php?&cant_p={$configuracion_tarea['cantidad_preguntas']}&tema={$configuracion_tarea['categoria']}&i={$id_usuario}&it={$configuracion_tarea['task_id']}");
                                    exit();
                                } elseif (isset($configuracion_tarea['figura'])) {
                                    // Redirigir al alumno al juego de union de Puntos 
                                    header("Location: ../puntos/index.php?figura={$configuracion_tarea['figura']}&i={$id_usuario}&it={$configuracion_tarea['task_id']}");
                                    exit();
                                } else {
                                    mostrarNotificacion("error", "Error", "La configuración del juego no es válida.", "../inicioAlumno.php");
                                }
                            } else {
                                mostrarNotificacion("error", "Error", "El ID de la tarea no está en la configuración.", "../inicioAlumno.php");
                            }
                        } else {
                            mostrarNotificacion("error", "Error", "No se pudo leer el archivo de configuración.", "../inicioAlumno.php");
                        }
                    } else {
                        mostrarNotificacion("error", "Error", "No se pudo leer el archivo de configuración.", "../inicioAlumno.php");
                    }
                } else {
                    // Si el archivo de configuración no existe, verificar en la base de datos
                    if (isset($_POST['id_task'])) {
                        $quiz_id = intval($_POST['id_task']);

                        // Construir el valor de configuración esperado
                        $configuracion_esperado = $configuracion;

                        // Imprimir valores antes de la consulta
                        echo "<script>console.log('Quiz ID: " . $quiz_id . "');</script>";
                        echo "<script>console.log('Configuración Esperada: " . $configuracion_esperado . "');</script>";

                        // Consultar la tabla tasks para verificar si hay una coincidencia en el campo configuracion
                        $query = "SELECT task_id FROM tasks WHERE configuracion = ?";
                        $stmt = $conexion->prepare($query);
                        if ($stmt === false) {
                            mostrarNotificacion("error", "Error", "Error al preparar la consulta ", "../inicioAlumno.php");
                            exit();
                        }

                        $stmt->bind_param("s", $configuracion_esperado);
                        $stmt->execute();
                        $stmt->store_result();

                        // Imprimir resultado de la consulta
                        echo "<script>console.log('Número de Filas Encontradas: " . $stmt->num_rows . "');</script>";

                        if ($stmt->num_rows > 0) {
                            // Si se encuentra una coincidencia, redirigir al usuario a la página quiz.php
                            header("Location: ../quizzes/quiz.php?iq=$quiz_id&i=$id_usuario&it=$configuracion_esperado");
                            exit(); // Terminar el script después de redirigir
                        } else {
                            mostrarNotificacion("error", "Error", "No se encontró una coincidencia en la base de datos.", "../inicioAlumno.php");
                        }
                        $stmt->close();
                    } else {
                        mostrarNotificacion("error", "Error", "ID de la tarea no proporcionado.", "../inicioAlumno.php");
                    }
                }
            } else {
                mostrarNotificacion("error", "Error", "Datos incompletos recibidos.", "../inicioAlumno.php");
            }
        }
    }
 else {
        // Si no es un estudiante, verificar si es un profesor
        $consulta_profesor = "SELECT id, nombre_completo FROM teachers WHERE nombre_completo = '$username'";
        $resultado_profesor = mysqli_query($conexion, $consulta_profesor);

        if ($resultado_profesor && mysqli_num_rows($resultado_profesor) > 0) {
            $fila_profesor = mysqli_fetch_assoc($resultado_profesor);
            $nombre_usuario = $fila_profesor["nombre_completo"];
            $id_usuario = $fila_profesor["id"];

            // Lógica para profesores
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                // Verificar si se reciben los datos esperados
                if (isset($_POST['id_task']) && isset($_POST['json_config'])) {
                    $id_tarea = $_POST['id_task'];
                    $configuracion = $_POST['json_config'];

                    // Obtener la ruta del archivo de configuración
                    $ruta_archivo_configuracion = "actividades/$configuracion";

                    // Verificar si el archivo de configuración existe
                    if (file_exists($ruta_archivo_configuracion)) {
                        // Leer el contenido del archivo JSON de configuración
                        $configuracion_json = file_get_contents($ruta_archivo_configuracion);
                        if ($configuracion_json !== false) {
                            // Decodificar el JSON de configuración
                            $configuracion_data = json_decode($configuracion_json, true);

                            if (isset($configuracion_data)) {
                                $encontrado = false;
                                foreach ($configuracion_data as $config) {
                                    if (isset($config['task_id']) && $config['task_id'] == $id_tarea) {
                                        $configuracion_tarea = $config;
                                        $encontrado = true;
                                        break;
                                    }
                                }

                                if ($encontrado) {
                                    // Determinar a qué juego dirigir al profesor según la configuración de la tarea que coincide con el mismo task_id
                                    if (isset($configuracion_tarea['tema']) && isset($configuracion_tarea['dificultad'])) {
                                        // Redirigir al profesor al juego de memorama con la configuración correspondiente
                                        header("Location: ../memorama/index.php?tem={$configuracion_tarea['tema']}&level={$configuracion_tarea['dificultad']}&it={$configuracion_tarea['task_id']}");
                                        exit();
                                    } elseif (isset($configuracion_tarea['tiempo_limite']) && isset($configuracion_tarea['letra_correcta'])) {
                                        // Redirigir al profesor al juego de burbujas con la configuración correspondiente
                                        header("Location:../bubblesGame/index.php?time={$configuracion_tarea['tiempo_limite']}&letra={$configuracion_tarea['letra_correcta']}&it={$configuracion_tarea['task_id']}");
                                        exit();
                                    } elseif (isset($configuracion_tarea['cantidad_preguntas']) && isset($configuracion_tarea['categoria'])) {
                                        // Redirigir al profesor al juego de quiz con la configuración correspondiente
                                        header("Location: ../preguntas/index.php?&cant_p={$configuracion_tarea['cantidad_preguntas']}&tema={$configuracion_tarea['categoria']}&it={$configuracion_tarea['task_id']}");
                                        exit();
                                    } elseif (isset($configuracion_tarea['figura'])) {
                                        // Redirigir al alumno al juego de union de Puntos 
                                        header("Location:../puntos/index.php?figura={$configuracion_tarea['figura']}&it={$configuracion_tarea['task_id']}");
                                        exit();
                                    }
                                    else {
                                        mostrarNotificacion("error", "Error", "La configuración del juego no es válida.", "../inicioProfesor.php");
                                    }
                                } else {
                                    mostrarNotificacion("error", "Error", "El ID de la tarea no está en la configuración.", "../inicioProfesor.php");
                                }
                            } else {
                                mostrarNotificacion("error", "Error", "No se pudo leer el archivo de configuración.", "../inicioProfesor.php");
                            }
                        } else {
                            mostrarNotificacion("error", "Error", "No se pudo leer el archivo de configuración.", "../inicioProfesor.php");
                        }
                    } else {
                        // Si el archivo de configuración no existe
                        $mensaje = "Tú has creado esta actividad. ¿Deseas ir a revisarla?";
                    
                        // Mostrar el mensaje de confirmación con SweetAlert
                        echo '<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
                        <script>
                            document.addEventListener("DOMContentLoaded", function() {
                                Swal.fire({
                                    title: "Cuestionario creado por ti",
                                    text: "' . $mensaje . '",
                                    icon: "info",
                                    showCancelButton: true,
                                    confirmButtonColor: "#3085d6",
                                    cancelButtonColor: "#d33",
                                    confirmButtonText: "Ir a revisarla",
                                    cancelButtonText: "Cancelar"
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        window.location.href = "../acciones/index.php#test";
                                    } else {
                                        window.location.href = "../inicioProfesor.php";
                                    }
                                });
                            });
                        </script>';
                    }
                    
                } else {
                    mostrarNotificacion("error", "Error", "Datos incompletos recibidos", "../inicioProfesor.php");
                }
            }
        } else {
            mostrarNotificacion("error", "Error", "El usuario no es válido.", "../../index.php");
        }
    }
}

// Función para mostrar errores
function mostrarError($mensaje) {
    echo "<script>alert('$mensaje');</script>";
    echo "<script>window.location.href='../../index.php';</script>";
    exit();
}
?>
