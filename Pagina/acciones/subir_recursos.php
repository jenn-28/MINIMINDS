<?php
session_start();

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
    // Verificar si el usuario está autenticado y es un profesor
    if (isset($_SESSION["username"])) {
        // Verificar si se ha seleccionado un archivo y un grupo
        if (isset($_POST["grupo"]) && isset($_FILES["recurso"])) {
            $grupo_id = $_POST["grupo"];
            $nombre_grupo = ""; // Obtener el nombre del grupo desde la base de datos
            $shared_by = $_SESSION["user_id"]; // Suponiendo que tienes el ID del usuario en $_SESSION
            $nombre_recurso = $_FILES["recurso"]["name"];
            $tipo_recurso = $_FILES["recurso"]["type"];
            $tamano_recurso = $_FILES["recurso"]["size"];
            $temp_file = $_FILES["recurso"]["tmp_name"];

            // Verificar el tipo y tamaño del archivo
            $permitidos = array("application/pdf");
            $max_size = 1024 * 1024; // 1 MB

            if (in_array($tipo_recurso, $permitidos) && $tamano_recurso <= $max_size) {
                // Mover el archivo a la ubicación deseada en el servidor
                $ruta_archivo = "recursos/" . $nombre_recurso;
                move_uploaded_file($temp_file, $ruta_archivo);

                // Insertar la información del recurso en la base de datos
                include("../../Logeo/conexion.php");
                $query = "INSERT INTO recursos (shared_by, grupo, nombre) VALUES (?, ?, ?)";
                $stmt = $conexion->prepare($query);
                $stmt->bind_param("iis", $shared_by, $grupo_id, $nombre_recurso);
                $stmt->execute();
                $stmt->close();
                $conexion->close();
                mostrarNotificacion("success", "Recurso Compartido!", "El recurso se ha compartido correctamente.", "index.php");
                } else {
                    mostrarNotificacion("error", "Error", "El tipo de archivo o el tamaño exceden los límites permitidos.", "index.php");
                }
        } else {
          mostrarNotificacion("error", "Error", "Primero debes seleccionar un grupo y un archivo.", "index.php");
       }
    } else {
      mostrarNotificacion("error", "Error", "Acceso no autorizado", "../../index.php");
    }
} else {
  mostrarNotificacion("error", "Error", "Método no permitido.", "index.php");
}
?>
