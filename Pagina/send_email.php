<?php 
// Inicia una nueva sesión o reanuda la sesión existente
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

// Verifica si el usuario ha iniciado sesión
if (!isset($_SESSION["username"])) {
    // Redirige al usuario a la página de inicio de sesión si no ha iniciado sesión
    header("Location: ../Logeo/IniciarSesion.php");
    exit();
}

// Incluye el archivo de conexión a la base de datos
include("../Logeo/conexion.php");

// Escapa cualquier carácter especial en el nombre de usuario para evitar inyección de SQL
$username = mysqli_real_escape_string($conexion, $_SESSION["username"]);

// Consulta la base de datos para obtener el nombre y el correo del profesor
$query = "SELECT nombre_completo, email FROM teachers WHERE nombre_completo = '$username'";
$result = mysqli_query($conexion, $query);
$profesor = mysqli_fetch_assoc($result);

// Verifica si se encontró el profesor y si tiene un correo registrado
if (!$profesor || empty($profesor['email'])) {
    mostrarNotificacion("warning", "Actualiza tus datos", "Por favor, actualice sus datos con un correo electrónico válido.", "editarPerfil.php");
    exit();
}

// Asigna el nombre y el correo del profesor a variables
$nombre = htmlspecialchars($profesor['nombre_completo']);
$email = htmlspecialchars($profesor['email']);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Miniminds</title>
    <link rel="icon" href="../imagenes/LogoS.png">
<style>
      * {
    font-family: Arial, sans-serif;
    }
    body {
        background-color: rgba(252, 241, 97, 0.692);
    }
    .btn-c{
        float:right;
        width: 30%;
        margin-top: 8%;
        background-color: #6b7280;
        color: #fff;
        font-weight: 700;
        font-size: 1.2em;
        border: none;
        border-radius: 0.375rem;
        padding: 8px 10px;
    }
    .btn-c:hover{
        background-color: #fff;
        color: #6b7280;
    }
    .contact-section {
        width: 100%;
        max-width: 45rem;
        margin-left: auto;
        margin-right: auto;
        padding: 3rem 1rem;
    }

    .contact-intro > * + * {
        margin-top: 1rem;
    }

    .contact-title {
        font-size: 2rem;
        line-height: 2.25rem;
        font-weight: 700;
    }

    .contact-description {
        color: rgb(107 114 128);
    }

    .contact-form {
        background-color: #8060CE;
        width: 100%;
        max-width: 45rem;
        padding: 2% 5% 5% 3%;
    }

    .form-group-container {
        display: grid;
        gap: 1rem;
        margin-top: 2rem;
    }

    .form-group {
        display: flex;
        flex-direction: column;
    }

    .form-label {
        margin-bottom: 0.5rem;
    }

    .form-input,
    .form-textarea {
        padding: 0.5rem;
        border: 1px solid #e5e7eb;
        display: flex;
        height: 2.5rem;
        width: 100%;
        border-radius: 0.375rem;
        font-size: 0.875rem;
        line-height: 1.25rem;
    }

    .form-input::placeholder,
    .form-textarea:focus-visible {
        color: #6b7280;
    }

    .form-input:focus-visible,
    .form-textarea:focus-visible {
        outline: 2px solid #2563eb;
        outline-offset: 2px;
    }

    .form-textarea {
        min-height: 120px;
    }

    .form-submit {
        width: 100%;
        margin-top: 1.2rem;
        background-color: #2fabd4;
        color: #fff;
        padding: 13px 5px;
        border-radius: 0.375rem;
        font-weight: 600;
        font-size: 1em;
    }

    /* Media Queries for Responsiveness */
    @media (max-width: 768px) {
        .contact-section {
        border-radius: 0.375rem;
        width: 100%;
        max-width: 90%;
        padding: 1.5rem 2rem 1.5rem 1.5rem;
    }
    .contact-title {
        font-size: 1rem;
        line-height: 1rem;
    }
    .contact-form {
        max-width: 90%;
    }

    .form-group-container {
        gap: 20px;
        margin-top: 1rem;
    }

    .form-input,
    .form-textarea {
        height: .8rem;
        line-height: .8rem;
    }
    
    }

</style>
</head>
<body>

<section class="contact-section">
  <div class="contact-intro">
    <h2 class="contact-title">Sugerencias</h2>
    <p class="contact-description">Enviar un correo con tus sugerencias, nuestros desarrolladores las leerán y tomarán en cuenta para mejorar tu experiencia</p>
  </div>

  <form class="contact-form" action="https://api.web3forms.com/submit" method="POST">

    <input type="hidden" name="access_key" value="4bed65d4-729a-483a-93e0-6d313b97bdcb" />
    <input type="hidden" name="subject" value="Miniminds Sugerencias" />
    <input type="hidden" name="from_name" value="Miniminds" />

    <div class="form-group-container">
      <div class="form-group">
        <label for="name" class="form-label">Nombre</label>
        <input id="name" name="name" class="form-input" value="<?php echo $nombre; ?>" readonly />
      </div>
      <div class="form-group">
        <label for="email" class="form-label">Email</label>
        <input id="email" name="email" class="form-input" value="<?php echo $email; ?>" readonly />
      </div>
      <div class="form-group">
        <label for="message" class="form-label">Mensaje</label>
        <textarea class="form-textarea" id="message" name="message" placeholder="Escribe tu mensaje" require></textarea>
      </div>
    </div>
    <button class="form-submit" type="submit">Enviar</button>
  </form>

  <button class="btn-c" onclick="window.location.href='inicioProfesor.php'">Salir</button>

</section>   

</body>
</html>
