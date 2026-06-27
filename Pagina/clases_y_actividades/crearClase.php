<?php
session_start();

include("../../Logeo/conexion.php");


?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Miniminds</title>
    <link rel="icon" href="../../imagenes/LogoS.png" >
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
        td, th, thead{
            border-bottom: 2px solid black;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Crear Grupo y Asignar Alumnos</h2>
        <form action="control_crearClase.php" method="POST">
            <div class="form-group">
                <label for="group_name">Nombre del Grupo:</label>
                <input type="text" class="form-control" id="group_name" name="group_name" required>
            </div>
            <!-- Generación automática del código del grupo -->
            <?php
            function generateGroupCode() {
                return strtoupper(substr(md5(uniqid(rand(), true)), 0, 8));
            }
            $group_code = generateGroupCode();
            ?>
            <div class="form-group">
                <label for="group_code">Código del Grupo:</label>
                <input type="text" class="form-control" id="group_code" name="group_code" value="<?php echo $group_code; ?>" readonly>
            </div>
            <div class="form-group">
                <label for="description">Descripción:</label>
                <textarea class="form-control" id="description" name="description" rows="3" required></textarea>
            </div>
            <!-- Lista de alumnos disponibles -->
            <div class="form-group" style="margin: 2%;">
    <h2 style="margin-top: 5%;">Alumnos Disponibles:</h2>
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
                die("Error en la consulta: " . $conexion->error);
            }
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td><input type='checkbox' name='selected_students[]' value='" . $row['id'] . "'></td>";
                    echo "<td><img style='width: 50px;' src='../../Logeo/foto_perfil/" . $row['profile_picture'] . "' alt='" . $row['nombre_completo'] . "'></td>";

                    echo "<td>" . $row['nombre_completo'] . "</td>";
                   
                }
            } else {
                echo "<tr><td colspan='5'>No hay alumnos disponibles</td></tr>";
            }

            // Cerrar conexión
            $conexion->close();
            ?>
        </tbody>
    </table>
</div>
<button  style="margin: 3%;" type="submit" class="btn btn-primary">Crear Grupo y Asignar Alumnos</button>
<button class="btn btn-secondary" onclick="window.location.href='../inicioProfesor.php'">Cancelar</button>

    <!-- Enlace a jQuery y Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <!-- Tu propio script de JavaScript para manejar la lógica de selección de alumnos -->
    <script src="agregar_alumno.js"></script>
</body>
</html>
