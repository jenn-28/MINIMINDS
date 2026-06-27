<?php
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
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['file'])) {
    $file = basename($_POST['file']);
    $file_path = __DIR__ . '/recursos/' . $file;

    // Mensaje de depuración para mostrar la ruta completa del archivo
    echo "<script>console.log('Intentando descargar el archivo: " . $file_path . "');</script>";

    if (file_exists($file_path)) {
        // Mensaje de depuración para confirmar que el archivo existe
        echo "<script>console.log('El archivo existe. Preparando para descargar...');</script>";

        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . basename($file_path) . '"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($file_path));
        flush();
        readfile($file_path);
        exit;
    } else {
        // Mensaje de depuración para indicar que el archivo no se encontró
        mostrarNotificacion("error", "Error","El archivo no existe: " . $file_path . "<br>", "index.php");
    }
} else {
    // Mensaje de depuración para indicar que no se especificó un archivo
    mostrarNotificacion("error", "Error", "No se especificó ningún archivo para descargar.<br>", "IniciarSesion.php");
}
?>
