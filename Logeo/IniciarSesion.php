<?php
session_start();
?>
<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/lottie-web/5.12.2/lottie.min.js" integrity="sha512-jEnuDt6jfecCjthQAJ+ed0MTVA++5ZKmlUcmDGBv2vUI/REn6FuIdixLNnQT+vKusE2hhTk2is3cFvv5wA+Sgg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script
      src="https://kit.fontawesome.com/64d58efce2.js"
      crossorigin="anonymous"
    ></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="IniciarSesion.css" />
    <title >Miniminds</title>
    <link rel="icon" href="../imagenes/LogoS.png">
    <style>
   
  </style>
  </head>
  <body>
    <div class="container">
      <div class="forms-container">
        <div class="signin-signup">
  <form action="Ingresar.php" method="POST" class="sign-in-form">
  <audio id="audioBienvenida" src="../audio/bienvenida.mp3"></audio>
  <lottie-player id="lottiePlayer" src="../js_files/cat_hello.json" background="transparent" speed="1" style="width: 280px; height: 280px; margin-top: -50px;" loop autoplay></lottie-player>

  <h2 class="title">Sign in</h2>
  <div class="input-field">
    <i class="fas fa-user"></i>
    <input type="text" name="username" placeholder="Nombre de Usuario" />
  </div>
  <div class="input-field">
    <i class="fas fa-lock"></i>
    <input type="password" name="password" id="password" placeholder="Contraseña" />
    <span class="passW-toggle" onclick="togglePassword3()"><i class="fa-solid fa-eye"></i></span>

  </div>
  <input type="submit" value="Login" class="btn solid" />
</form>
<form action="Registro.php" method="POST" class="sign-up-form" enctype="multipart/form-data">
  <h2 class="title">Sign up</h2>
  <div class="input-field">
    <i class="fa-solid fa-person-circle-question"></i>              
    <select name="rol" id="rol" class="input-field" style="margin-top: 6px; border:none;">
      <option style=" background: none; outline: none; border: none; line-height: 1; font-weight: 600; font-size: 1rem; color:#333" value="student">Estudiante</option>
      <option style=" background: none; outline: none; border: none; line-height: 1; font-weight: 600; font-size: 1rem; color:#333" value="teacher">Profesor</option>
    </select>
  </div>
  <div class="input-field">
    <i class="fas fa-user"></i>
    <input type="text" name="username" placeholder="Nombre Completo" required>
  </div>
  <div class="input-field">
    <i class="fas fa-envelope"></i>
    <input type="email" name="correo" placeholder="Correo Electronico" required>
  </div>
  <div class="input-field">
    <i class="fas fa-lock"></i>
    <input type="password" name="password" id="pass" placeholder="Contraseña" required>
    <span class="pass-toggle" onclick="togglePassword2()"><i class="fa-solid fa-eye"></i></span>

  </div>
  <div class="input-field">
    <i class="fas fa-image"></i>
    <input type="file" name="p_picture" id="p_picture" accept="image/*"><br>
  </div>
  <div class="input-field capt-field">
  <i class="fas fa-key"></i>
  <input type="password" name="captcha_key" id="captcha_key" placeholder="Clave de acceso" />
  <span class="password-toggle" onclick="togglePassword()"><i class="fa-solid fa-eye"></i></span>
  </div>
  <input type="hidden" name="user_id" id="user_id" value="">
  <input type="submit" class="btn" id="btn-sp" value="Sign up"/>
</form>
</div>
</div>

<div class="panels-container">
<div class="panel left-panel">
  <div class="content">
    <h3>¿Nuevo aqui?</h3>
    <p>
      Crea una cuenta e inicia la diversion y aprendizaje con MINIMIDS
    </p>
    <button class="btn transparent" id="sign-up-btn">
      Sign up
    </button>
  </div>
  <img id="imgTrigger" src="../imagenes/LogoS.png" class="image" alt="" />
</div>
<div class="panel right-panel">
  <div class="content">
    <h3>¿Ya tienes una cuenta?</h3>
    <p>
     Ingresa para continuar divirtiendote y aprendiendo con MINIMIDS
    </p>
    <button class="btn transparent" id="sign-in-btn">
      Sign in
    </button>
  </div>
  <img src="../imagenes/LogoS.png" class="image" alt=""/>
</div>
</div>
</div>
<script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script> 
<script src="scrip.js"></script>

<script>
    document.addEventListener("DOMContentLoaded", function() {
      // Reproducir audio cuando la página se carga
      const audioBienvenida = document.getElementById("audioBienvenida");
      audioBienvenida.play();

      // Obtener elementos de imagen y lottie-player
      const imgTrigger = document.getElementById("imgTrigger");
      const lottiePlayer = document.getElementById("lottiePlayer");

      // Reproducir audio cuando el cursor pasa por encima de la imagen
      imgTrigger.addEventListener("mouseenter", function() {
        audioBienvenida.play();
      });

      // Reproducir audio cuando el cursor pasa por encima del lottie-player
      lottiePlayer.addEventListener("mouseenter", function() {
        audioBienvenida.play();
      });
    });
  </script>

<script>
   document.addEventListener("DOMContentLoaded", function() {
  const rolSelect = document.getElementById('rol');
  const captchaField = document.querySelector('.capt-field');

  function toggleCaptchaField() {
    if (rolSelect.value === 'teacher') {
      captchaField.style.display = 'flex';
    } else {
      captchaField.style.display = 'none';
    }
  }

  rolSelect.addEventListener('change', toggleCaptchaField);
  toggleCaptchaField();
});

  </script>
  <script>
         function togglePassword() {
    const passwordInput = document.getElementById('captcha_key');
    const passwordToggle = document.querySelector('.password-toggle');
    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        passwordToggle.innerHTML = '<i class="fa-solid fa-eye-slash"></i>'; // Cambia el ícono
    } else {
        passwordInput.type = 'password';
        passwordToggle.innerHTML = '<i class="fa-solid fa-eye"></i>'; // Cambia el ícono
    }
}

function togglePassword2() {
    const passwordInput = document.getElementById('pass');
    const passwordToggle = document.querySelector('.pass-toggle');
    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        passwordToggle.innerHTML = '<i class="fa-solid fa-eye-slash"></i>'; // Cambia el ícono
    } else {
        passwordInput.type = 'password';
        passwordToggle.innerHTML = '<i class="fa-solid fa-eye"></i>'; // Cambia el ícono
    }
}

function togglePassword3() {
    const passwordInput = document.getElementById('password');
    const passwordToggle = document.querySelector('.passW-toggle');
    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        passwordToggle.innerHTML = '<i class="fa-solid fa-eye-slash"></i>'; // Cambia el ícono
    } else {
        passwordInput.type = 'password';
        passwordToggle.innerHTML = '<i class="fa-solid fa-eye"></i>'; // Cambia el ícono
    }
}
    </script>

  </body>
</html>
