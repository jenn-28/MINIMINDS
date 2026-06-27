<?php
session_start();
echo '<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['username'], $_POST['password'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];

        // Include the connection file
        include("conexion.php");

        // Prepare the SQL statement to avoid SQL injection
        $stmt = $conexion->prepare("SELECT * FROM `users` WHERE `nombre_completo` = ? AND `password` = ?");
        $stmt->bind_param("ss", $username, $password);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
            // Authentication successful, retrieve user data
            $row = $result->fetch_assoc();
            // Set session variables
            $_SESSION["username"] = $username;
            $_SESSION["role"] = $row["role"];
            $_SESSION["user_id"] = $row["id"];

            // Redirect to appropriate page based on user role
            if ($row["role"] == "student") {
                header("Location: ../Pagina/inicioAlumno.php");
                exit(); // Asegúrate de salir para evitar que el código siguiente se ejecute
            } elseif ($row["role"] == "teacher") {
                header("Location: ../Pagina/inicioProfesor.php");
                exit(); // Asegúrate de salir para evitar que el código siguiente se ejecute
            }
        } else {
            // Authentication failed, display an error message
            echo '<script>
                $(document).ready(function() {
                    Swal.fire({
                        icon: "error",
                        title: "Error",
                        text: "Nombre de usuario y/o contraseña incorrectos."
                    }).then(function() {
                        window.location.href = "IniciarSesion.php";
                    });
                });
            </script>';
        }

        // Close the statement
        $stmt->close();
    } else {
        // Display an error message if username or password are not set
        echo '<script>
            $(document).ready(function() {
                Swal.fire({
                    icon: "error",
                    title: "Error",
                    text: "Por favor, ingresa tanto el nombre de usuario como la contraseña."
                }).then(function() {
                    window.location.href = "IniciarSesion.php";
                });
            });
        </script>';
    }
}
?>
