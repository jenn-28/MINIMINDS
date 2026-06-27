<?php
session_start();
include("../Logeo/conexion.php");

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

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['username'])) {
    header("Location: ../Logeo/IniciarSesion.php");
    exit();
}

$nombre_profesor = $_SESSION['username'];
$group_id = $_GET['group_id'];

// Verificar si el usuario es el profesor de la clase
$query = "SELECT created_by FROM groups WHERE group_id = '$group_id'";
$result = mysqli_query($conexion, $query);
$row = mysqli_fetch_assoc($result);

if ($row['created_by'] !== $nombre_profesor) {
    mostrarNotificacion("warning", "Oops...!", "No tienes permiso para ver esta página.", "../index.php");
    exit();
}

// Verificar si se ha enviado el formulario para agregar estudiantes
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['selected_students'])) {
    $selected_students = $_POST['selected_students'];

    if (empty($selected_students)) {
        mostrarNotificacion("warning", "Oops...!", "No se seleccionaron estudiantes.", "get_students.php");
        exit();
    }

    // Obtener el nombre del grupo
    $query = "SELECT group_name FROM groups WHERE group_id = '$group_id'";
    $result = mysqli_query($conexion, $query);
    $row = mysqli_fetch_assoc($result);
    $group_name = $row['group_name'];

    // Iniciar transacción
    $conexion->begin_transaction();

    try {
        // Actualizar el campo de grupo para cada alumno seleccionado
        foreach ($selected_students as $student_id) {
            $update_student_sql = "UPDATE students SET grupo = '$group_name' WHERE id = $student_id";
            if (!$conexion->query($update_student_sql)) {
                // Si hay un error en alguna de las actualizaciones, revertir la transacción
                throw new Exception("Error al actualizar el campo grupo para el alumno con ID $student_id");
            }
        }

        // Calcular la cantidad de estudiantes en el grupo
        $count_students_sql = "SELECT COUNT(*) as total FROM students WHERE grupo = '$group_name'";
        $result = $conexion->query($count_students_sql);
        $row = $result->fetch_assoc();
        $student_count = $row['total'];

        // Actualizar el contador de estudiantes en el grupo
        $update_group_sql = "UPDATE groups SET student_count = $student_count WHERE group_id = $group_id";
        if (!$conexion->query($update_group_sql)) {
            throw new Exception("Error al actualizar el número de estudiantes en el grupo");
        }

        // Confirmar la transacción
        $conexion->commit();

        // Redireccionar a alguna página de éxito
        header("Location: #");
        exit();
    } catch (Exception $e) {
        // Revertir la transacción en caso de error
        $conexion->rollback();
        echo "Error: " . $e->getMessage();
    }
}

// Verificar si se ha enviado el formulario para eliminar un estudiante
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_student_id'])) {
    $delete_student_id = $_POST['delete_student_id'];

    // Obtener el nombre del grupo
    $query = "SELECT group_name FROM groups WHERE group_id = '$group_id'";
    $result = mysqli_query($conexion, $query);
    $row = mysqli_fetch_assoc($result);
    $group_name = $row['group_name'];

    // Iniciar transacción
    $conexion->begin_transaction();

    try {
        // Eliminar el estudiante del grupo
        $delete_student_sql = "UPDATE students SET grupo = '' WHERE id = $delete_student_id";
        if (!$conexion->query($delete_student_sql)) {
            // Si hay un error en alguna de las actualizaciones, revertir la transacción
            
            throw new Exception("Error al eliminar el estudiante con ID $delete_student_id del grupo");
        }

        // Calcular la cantidad de estudiantes en el grupo
        $count_students_sql = "SELECT COUNT(*) as total FROM students WHERE grupo = '$group_name'";
        $result = $conexion->query($count_students_sql);
        $row = $result->fetch_assoc();
        $student_count = $row['total'];

        // Actualizar el contador de estudiantes en el grupo
        $update_group_sql = "UPDATE groups SET student_count = $student_count WHERE group_id = $group_id";
        if (!$conexion->query($update_group_sql)) {
            throw new Exception("Error al actualizar el número de estudiantes en el grupo");
        }

        // Confirmar la transacción
        $conexion->commit();

        // Redireccionar a alguna página de éxito
        header("Location: #");
        exit();
    } catch (Exception $e) {
        // Revertir la transacción en caso de error
        $conexion->rollback();
        echo "Error: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Miniminds</title>
    <link rel="icon" href="../imagenes/LogoS.png">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap');
        body{
            background-color: #DAF9FF;
            font-family: "Poppins", sans-serif;
        }

        h2{
            font-weight: 800;
        }
        td, th, thead{
            border-bottom: 2px solid black;
        }
    </style>
    <script>
        function toggleStudentSection() {
            var x = document.getElementById("studentSection");
            if (x.style.display === "none") {
                x.style.display = "block";
            } else {
                x.style.display = "none";
            }
        }
    </script>
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center mb-4">Lista de Estudiantes</h2>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Foto</th>
                    <th>Nombre Completo</th>
                    <th>Email</th>
                    <th>Nombre del Tutor</th>
                    <th>Teléfono del Tutor</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $query = "SELECT id, profile_picture, nombre_completo, email, nombre_tutor, telefono_tutor 
                          FROM students 
                          WHERE grupo = (SELECT group_name FROM groups WHERE group_id = '$group_id')";
                $result = mysqli_query($conexion, $query);

                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>";
                    echo "<td><img style='width: 50px;' src='../Logeo/foto_perfil/" . $row['profile_picture'] . "' alt='" . $row['nombre_completo'] ."'></td>";
                    echo "<td>" . $row['nombre_completo'] . "</td>";
                    echo "<td>" . $row['email'] . "</td>";
                    echo "<td>" . $row['nombre_tutor'] . "</td>";
                    echo "<td>" . $row['telefono_tutor'] . "</td>";
                    echo "<td>";
                    echo "<form action='' method='POST' style='display:inline;'>";
                    echo "<input type='hidden' name='delete_student_id' value='" . $row['id'] . "'>";
                    echo "<button type='submit' class='btn btn-danger btn-sm'>Eliminar</button>";
                    echo "</form>";
                    echo "</td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
        <div class="text-center mt-4">
            <button type="button" class="btn btn-secondary" onclick="window.location.href='inicioProfesor.php'">Volver a Clases</button>
        </div>
        
        <div class="text-right mt-4">
            <button type="button" class="btn btn-primary" onclick="toggleStudentSection()">Agregar más estudiantes</button>
        </div>

        <!-- Lista de alumnos disponibles -->
        <div id="studentSection" class="form-group" style="margin: 2%; display: none;">
            <h2 style="margin-top: 5%;">Alumnos Disponibles:</h2>
            <form action="" method="POST">
                <input type="hidden" name="group_id" value="<?php echo $group_id; ?>">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Seleccionar alumno</th>
                            <th>Foto de Perfil</th>
                            <th>Nombre</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Consulta para obtener los alumnos disponibles
                        $sql = "SELECT id, nombre_completo, profile_picture FROM students WHERE grupo =''";
                        $result = $conexion->query($sql);

                        if (!$result) {
                            die("Error en la consulta de alumnos disponibles: " . $conexion->error);
                        }

                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td><input type='checkbox' name='selected_students[]' value='" . $row['id'] . "'></td>";
                                echo "<td><img style='width: 50px;' src='../Logeo/foto_perfil/" . $row['profile_picture'] . "' alt='" . $row['nombre_completo'] . "'></td>";
                                echo "<td>" . $row['nombre_completo'] . "</td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='3'>No hay alumnos disponibles</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
                <button style="margin: 3%;" type="submit" class="btn btn-primary">Agregar Alumnos al Grupo</button>
            </form>
        </div>
    </div>
</body>
</html>
