<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Miniminds</title>
    <link rel="icon" href="imagenes/LogoS.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css"/> 
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Bungee+Inline&family=Bungee+Shade&family=Modak&display=swap');
        @import url('https://fonts.googleapis.com/css2?family=Lexend+Deca&display=swap');

 /* Reset CSS */
* {
    margin: 0;
    padding: 0;
    list-style: none;
    text-decoration: none;
}

/* Estilos comunes */
body {
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    height: 100vh;
    margin: 0;
    position: relative;
}

.sec {
    z-index: 1;
    position: relative;
    background-color: rgb(2, 16, 36);
    overflow: hidden;
    height: 100vh;
    width: 100vw;
}

.star {
    position: absolute;
    color: rgba(255,255,255,0.5);
    z-index: 1;
}

.star::before {
    content: '\f005';
    font-family: FontAwesome;
}

.titulo {
    margin-left: -30%;
    margin-top: -35%;

}
.titulo h1{
    z-index: 5;

}
.bienvenido {
    font-family: 'Lexend Deca', sans-serif;
    font-size: 4rem;
    color: black;
    text-shadow: 0 0 2px rgb(241, 238, 43),
                 0 0 8px rgb(241, 238, 14),
                 0 0 20px rgb(241, 238, 14);
}

.miniminds {
    font-family: 'Modak', system-ui;
    font-size: 10rem;
    color: rgb(250, 234, 11);
    text-shadow: 0 0 10px rgb(25, 132, 194),
                 0 0 25px rgb(25, 132, 194),
                 0 0 60px rgb(25, 132, 194);
    z-index: 10;
}

.cuerpo {
    text-align: center;
    position: absolute;
    top: 75%;
    left: 50%;
    transform: translate(-50%, -50%);
    z-index: 10;
}

.boton-registro {
    font-size: 8rem;
    cursor: pointer;
    color: rgb(250, 234, 11);
    text-shadow: 0 0 10px rgb(4, 39, 59),
                 0 0 25px rgb(4, 39, 59),
                 0 0 60px rgb(4, 39, 59);
    z-index: 10;
}

/* Estilos para los ojos */
.box {
    z-index: 2;
    display: flex;
}

.box .eye {
    position: relative;
    width: 180px;
    height: 180px;
    display: block;
    background: #fff;
    margin: 0 20px;
    border-radius: 50%;
    box-shadow: 0 5px 45px rgba(0,0,0,0.2),
                inset 0 0 15px rgb(0, 8, 51),
                inset 0 0 25px rgb(0, 8, 51);
}

.box .eye::before {
    content: '';
    position: absolute;
    top: 50%;
    left: 35px;
    transform: translate(-50%, -50%);
    width: 65px;
    height: 65px;
    border-radius: 50%;
    background: #000;
    border: 15px solid #2196f4;
    box-sizing: border-box;
}

/* Media queries for responsiveness */
@media (max-width: 768px) {
    /* Estilos para pantallas pequeñas */
    .titulo{
        margin-top: -45%;
    }
    .titulo .bienvenido {
        margin-left: 42%;
        font-size: 35px;
        font-weight: 600;
    }
    .titulo .miniminds{
        margin-left: 25%;
        font-size: 78px;
    }
    
    
    .box .eye {
        width: 160px;
        height: 160px;
    }
   
    .boton-registro {
        font-size: 8rem;
    }
}



    </style>
</head>
<body class="sec">
    <header>
        <div class="titulo">
            <h1 class="bienvenido"> Bienvenido a </h1>
            <div class="tutuloB">
                <h1 class="miniminds">MINIMINDS</h1>
           
    </header>
    <div >
        <div class="cuerpo">
            <!--Ojos-->
            <div class="ojos">
                <div class="box">
                    <div class="eye"></div>
                    <div class="eye"></div>
                </div>
            </div>
            <button class="btn boton-registro" onclick="window.location.href='Logeo/IniciarSesion.php'" data-bs-toggle="tooltip" data-bs-placement="top" title="Iniciar sesión">
            <i class="fa-solid fa-play fa-bounce"></i></button>

        </div>
    </div>
    <!-- Estrellas en el fondo -->
    <script>
        for(let i=1; i<=180;i++){
            let stars = document.createElement('div');
            stars.classList.add('star');
            let size = Math.random() * 15;
            stars.style.fontSize = 11 + size+'px';
            stars.style.left = Math.random()* + innerWidth + 'px';
            stars.style.top = Math.random()* + innerHeight + 'px';
            stars.style.filter = `hue-rotate(${i * 20}deg)`;
            document.querySelector('.sec').appendChild(stars);
        }
    </script>
    <!--JS Ojos-->
    <script>
        document.querySelector('body').addEventListener('mousemove', eyeball);
    
        function eyeball(event) {
            const eye = document.querySelectorAll('.eye');
            eye.forEach(function(eye) {
                let x = (eye.getBoundingClientRect().left) + (eye.clientWidth / 2);
                let y = (eye.getBoundingClientRect().top) + (eye.clientHeight / 2);

                let radian = Math.atan2(event.pageX - x, event.pageY - y);
                let rotation = (radian * (180 / Math.PI) * -1) + 270;

                console.log('X:', x, 'Y:', y, 'Rotation:', rotation);

                eye.style.transform = "rotate(" + rotation + "deg)";
            });
        }
    </script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
</body>
</html>
