<?php
session_start();
include '../../Logeo/conexion.php';

$id_usuario = ""; // Inicializar la variable
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

if (isset($_SESSION["username"])) {
    $username = $_SESSION["username"];
    $consulta = "SELECT id FROM users WHERE nombre_completo = ?";
    $stmt = $conexion->prepare($consulta);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $fila = $result->fetch_assoc();
        $id_usuario = $fila["id"];
    }
    $stmt->close();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['id']);
    $id_profesor = intval($_POST['id_profesor']);

    // Verificar que el id_profesor del recurso coincida con el id_profesor de la sesión
    if ($id_profesor !== $id_usuario) {
        mostrarNotificacion("error", "Error", "Acceso no autorizado.", "../../index.php");
        exit;
    }

    // Obtener el nombre del archivo a eliminar
    $query = "SELECT nombre FROM recursos WHERE id = ? AND shared_by = ?";
    $stmt = $conexion->prepare($query);
    $stmt->bind_param("ii", $id, $id_profesor);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $file = '../../recursos/' . $row['nombre']; // Ajusta la ruta según la estructura de tu proyecto

        // Eliminar el archivo del servidor
        if (file_exists($file)) {
            unlink($file);
        }

        // Eliminar el registro de la base de datos
        $deleteQuery = "DELETE FROM recursos WHERE id = ?";
        $deleteStmt = $conexion->prepare($deleteQuery);
        $deleteStmt->bind_param("i", $id);
        if ($deleteStmt->execute()) {
         mostrarNotificacion("success", "Eliminado!", "El recurso ha sido eliminado correctamente.", "index.php");
        } else {
            mostrarNotificacion("error", "Error", "Error al eliminar el recurso de la base de datos", "index.php");
        }
        $deleteStmt->close();
    } else {
        mostrarNotificacion("error", "Error", "Recurso no encontrado o no autorizado.", "index.php");
    }
    $stmt->close();
} else {
    mostrarNotificacion("error", "Error", "Método de solicitud no permitido.", "index.php");
}
?>
