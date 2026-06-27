const moves = document.getElementById("moves-count");
const timeValue = document.getElementById("time");
const startButton = document.getElementById("start");
const stopButton = document.getElementById("stop");
const gameContainer = document.querySelector(".game-container");
const contenedor = document.querySelector(".wrapper");
const result = document.getElementById("result");
const controls = document.querySelector(".controls-container");
const title_memo = document.getElementById("title");
const back = document.getElementById("back");
const containerResults = document.querySelector('.container-results');
const btnFinish = document.getElementById("finish-game-btn");

let cards;
let interval;
let firstCard = false;
let secondCard = false;

let temaSeleccionado;
let nivelSeleccionado;


function iniciarJuego() {
    // Redirigir a la página de juego.html y pasar las selecciones del usuario como parámetros
    window.location.href = 'index.php?tem=' + temaSeleccionado + '&level=' + nivelSeleccionado;
}


const initializer = () => {
  result.innerText = "";
  winCount = 0;

   // Determinar el tema y el nivel de dificultad
   const urlParams = new URLSearchParams(window.location.search);
   const tema = urlParams.get('tem');
   const nivel = urlParams.get('level');
   const idEstudiante = urlParams.get('i');
   const idTask = urlParams.get('it');


   console.log("Tema:", tema);
   console.log("Nivel:", nivel);
   console.log("ID del Estudiante:", idEstudiante);
   console.log("ID de la tarea:", idTask);


  let cantidadCartas;

  // Determinar la cantidad de cartas según el nivel de dificultad
  if (nivel === 'facil') {
    cantidadCartas = 5;
  } else if (nivel === 'dificil') {
    cantidadCartas = 9;
  } else {
    console.error('Nivel de dificultad no válido:', nivel);
  }

  // Llamar a matrixGenerator con los valores del tema, la cantidad de cartas y el nivel
  matrixGenerator(generateRandom(cantidadCartas, tema, nivel), cantidadCartas, nivel);
};


     //Items array
const itemsPorCombinacion = {
  "vocales-facil":[
    { name: "Abeja", image: "img/abeja.png" },
    { name: "elefante", image: "img/elefante.png" },
    { name: "iguana", image: "img/iguana.png" },
    { name: "oso", image: "img/oso.png" },
    { name: "uvas", image: "img/uvas.png" },
  ],
  "vocales-dificil":[
    { name: "Astronauta", image: "img/astronauta.png" },
    { name: "Autobus", image: "img/autobus.png" },
    { name: "Elote", image: "img/elote.png" },
    { name: "Escuela", image: "img/escuela.png" },
    { name: "Invierno", image: "img/invierno.png" },
    { name: "Insecto", image: "img/insecto.png" },
    { name: "Oreja", image: "img/oreja.png" },
    { name: "Ovni", image: "img/ovni.png" },
   /*{ name: "Utencilio", image: "img/utencilio.png" },*/
    { name: "Uña", image: "img/uña.png" },
    ],
  "numeros-facil":[
    { name: "uno", image: "img/uno.png" },
    { name: "dos", image: "img/dos.png" },
    { name: "tres", image: "img/tres.png" },
    { name: "cuatro", image: "img/cuatro.png" },
    { name: "cinco", image: "img/cinco.png" }, 
   ],
  "numeros-dificil":[
   /*{ name: "cero", image: "img/cero.png" },*/
    { name: "uno", image: "img/uno.png" },
    { name: "dos", image: "img/dos.png" },
    { name: "tres", image: "img/tres.png" },
    { name: "cuatro", image: "img/cuatro.png" },
    { name: "cinco", image: "img/cinco.png" }, 
    { name: "seis", image: "img/seis.png" },
    { name: "siete", image: "img/siete.png" },
    { name: "ocho", image: "img/ocho.png" },
    { name: "nueve", image: "img/nueve.png" },
    ],
    "abecedario-facil":[
    { name: "mama", image: "img/mama.png" },
    { name: "papa", image: "img/papa.png" },
    { name: "casa", image: "img/casa.png" },
    { name: "foco", image: "img/foco.png" },
    { name: "hoja", image: "img/hoja.png" }, 
    ],
    "abecedario-dificil":[
    { name: "mama", image: "img/mama.png" },
    { name: "papa", image: "img/papa.png" },
    { name: "casa", image: "img/casa.png" },
    { name: "foco", image: "img/foco.png" },
    { name: "hoja", image: "img/hoja.png" }, 
    { name: "caja", image: "img/caja.png" },
    { name: "silla", image: "img/silla.png" },
    { name: "taza", image: "img/taza.png" },
    { name: "luna", image: "img/luna.png" },

    ],
    "animales-facil":[
    { name: "perro", image: "img/perro.png" },
    { name: "vaca", image: "img/vaca.png" },
    { name: "pinguino", image: "img/pinguino.png" },
    { name: "gato", image: "img/gato.png" },
    { name: "caballo", image: "img/caballo.png" }, 
    ],
    "animales-dificil":[
    { name: "perro", image: "img/perro.png" },
    { name: "vaca", image: "img/vaca.png" },
    { name: "pinguino", image: "img/pinguino.png" },
    { name: "gato", image: "img/gato.png" },
    { name: "caballo", image: "img/caballo.png" }, 
    { name: "cerdo", image: "img/cerdo.png" },
    { name: "ballena", image: "img/ballena.png" },
    { name: "rana", image: "img/rana.png" },
    { name: "jirafa", image: "img/jirafa.png" },

    ],
    "colores-facil":[
      { name: "rojo", image: "img/rojo.png" },
      { name: "amarillo", image: "img/amarillo.png" },
      { name: "verde", image: "img/verde.png" },
      { name: "azul", image: "img/azul.png" },
      { name: "morado", image: "img/morado.png" }, 
    ],
    "colores-dificil":[
      { name: "rojo", image: "img/rojo.png" },
      { name: "amarillo", image: "img/amarrillo.png" },
      { name: "verde", image: "img/verde.png" },
      { name: "azul", image: "img/azul.png" },
      { name: "morado", image: "img/morado.png" },
      { name: "rosa", image: "img/rosa.png" },
      { name: "naranja", image: "img/naranja.png" },
      { name: "blanco", image: "img/blanco.png" },
      { name: "negro", image: "img/negro.png" },
    ],
    "figuras-facil":[
    { name: "cuadrado", image: "img/cuadrado.png" },
    { name: "triangulo", image: "img/triangulo.png" },
    { name: "rectangulo", image: "img/rectangulo.png" },
    { name: "ovalo", image: "img/ovalo.png" },
    { name: "circulo", image: "img/circulo.png" }, 
    ],
    "figuras-dificil":[
    { name: "cuadrado", image: "img/cuadrado.png" },
    { name: "triangulo", image: "img/triangulo.png" },
    { name: "rectangulo", image: "img/rectangulo.png" },
    { name: "ovalo", image: "img/ovalo.png" },
    { name: "circulo", image: "img/circulo.png" },
    { name: "pentagono", image: "img/pentagono.png" },
    { name: "rombo", image: "img/rombo.png" },
    { name: "hexagono", image: "img/hexagono.png" },
    { name: "octagono", image: "img/octagono.png" },

    ],
  
};

//Initial Time
let seconds = 0,
  minutes = 0;
//Initial moves and win count
let movesCount = 0,
  winCount = 0;

//For timer
const timeGenerator = () => {
  seconds += 1;
  //minutes logic
  if (seconds >= 60) {
    minutes += 1;
    seconds = 0;
  }
  //format time before displaying
  let secondsValue = seconds < 10 ? `0${seconds}` : seconds;
  let minutesValue = minutes < 10 ? `0${minutes}` : minutes;
  timeValue.innerHTML = `<span>Tiempo:</span>${minutesValue}:${secondsValue}`;
};

//For calculating moves
const movesCounter = () => {
  movesCount += 1;
  moves.innerHTML = `<span>Movimientos:</span>${movesCount}`;
};


const matrixGenerator = (cardValues, size, nivel) => {
  // Limpiar el contenido del contenedor de juego
  gameContainer.innerHTML = "";

  // Determinar la cantidad de columnas según el tamaño de la pantalla y el nivel de dificultad
  let numColumns;
  if (window.innerWidth > 768) {
    numColumns = 5; // Por ejemplo, 5 columnas para pantallas grandes
  } else {
    if (nivel === 'facil') {
      numColumns = 2; // Por ejemplo, 2 columnas para pantallas pequeñas en nivel "facil"
    } else if (nivel === 'dificil') {
      numColumns = 3; // Por ejemplo, 4 columnas para pantallas pequeñas en nivel "dificil"
    }
  }

  // Barajar aleatoriamente las cartas
  cardValues.sort(() => Math.random() - 0.5);

  // Iterar sobre el número total de cartas que se van a mostrar en el juego
  for (let i = 0; i < size * 2; i++) {
      // Crear el HTML de cada carta y añadirlo al contenedor de juego
      gameContainer.innerHTML += `
          <div class="card-container" data-card-value="${cardValues[i].name}">
              <div class="card-before">?</div>
              <div class="card-after">
              <img src="${cardValues[i].image}" class="image"/></div>
          </div>
      `;
  }
  
  // Configurar la cuadrícula para mostrar las cartas
  gameContainer.style.gridTemplateColumns = `repeat(${numColumns}, auto)`;

  // Seleccionar todas las cartas del juego
  cards = document.querySelectorAll(".card-container");
  // Iterar sobre cada carta
  cards.forEach(card => {
    card.addEventListener("click", () => {
        // Verificar si la carta ya está emparejada
        if (!card.classList.contains("matched")) {
            card.classList.add("flipped");
            if (!firstCard) {
                firstCard = card;
                firstCardValue = card.getAttribute("data-card-value");
            } else {
                movesCounter();
                secondCard = card;
                let secondCardValue = card.getAttribute("data-card-value");
                if (firstCardValue == secondCardValue) {
                    firstCard.classList.add("matched");
                    secondCard.classList.add("matched");
                    firstCard = false;
                    winCount += 1;
                    console.log("Parejas completadas:", winCount); //recuento de parejas
                    if (winCount == Math.floor(cardValues.length / 2)) {
                        console.log("Todas las parejas completadas"); 
                        endGame(); 
                        mostrarTarjetaFinalizacion(); 
                    }
                } else {
                    let [tempFirst, tempSecond] = [firstCard, secondCard];
                    firstCard = false;
                    secondCard = false;
                    let delay = setTimeout(() => {
                        tempFirst.classList.remove("flipped");
                        tempSecond.classList.remove("flipped");
                    }, 900);
                }
            }
        }
    });
});

};

function generateRandom(size, tema, dificultad) {
  const key = tema + '-' + dificultad;
  const items = itemsPorCombinacion[key];
  if (!items) {
      console.error('No se encontraron elementos para la combinación de tema y dificultad:', key);
      return [];
  }
  let tempArray = [...items];
  let cardValues = [];
  size = (size * 2) / 2;
  for (let i = 0; i < size; i++) {
      const randomIndex = Math.floor(Math.random() * tempArray.length);
      cardValues.push(tempArray[randomIndex]);
      cardValues.push(tempArray[randomIndex]);
      tempArray.splice(randomIndex, 1);
  }
  return cardValues;
}

//Start game
startButton.addEventListener("click", () => {
  movesCount = 0;
  seconds = 0;
  minutes = 0;
  //controls amd buttons visibility
  controls.classList.add("hide");
  stopButton.classList.remove("hide");
  startButton.classList.add("hide");
  //Start timer
  interval = setInterval(timeGenerator, 1000);
  //initial moves
  moves.innerHTML = `<span>Movimientos:</span> ${movesCount}`;
  initializer();

  
});

// Función para detener el juego y mostrar la notificación
const stopGame = () => {
  mostrarNotificacion("question", "¿Deseas abandonar el juego?", "", "../inicioAlumno.php");
};

// Agregar el evento click al botón stopButton
stopButton.addEventListener("click", stopGame);

// Función para mostrar la notificación utilizando SweetAlert
function mostrarNotificacion(icon, titulo, mensaje, redireccion = null) {
  Swal.fire({
      icon: icon,
      title: titulo,
      text: mensaje,
      confirmButtonText: "Aceptar"
  }).then((result) => {
      if (result.isConfirmed && redireccion !== "") {
          window.location.href = redireccion;
      }
  });
}


const endGame = () => {
  // Detener tiempo y guardar el valor final del tiempo
  clearInterval(interval);
  let secondsValue = seconds < 10 ? `0${seconds}` : seconds;
  let minutesValue = minutes < 10 ? `0${minutes}` : minutes;
  result.innerHTML = `
      <h2>Felicidades!!</h2>
      <br> <h4>Movimientos ${movesCount}</h4>
      <h4>Tiempo ${minutesValue}:${secondsValue}
      `;

      contenedor.style.display = "none";
      btnFinish.style.display ="block";

}

// Función para mostrar la tarjeta de finalización del juego
function mostrarTarjetaFinalizacion() {
  // Ocultar el contenedor del juego
  contenedor.style.display = "none";
  // Mostrar la tarjeta de finalización
  document.getElementById("game-complete").style.display = "block";

}

function marcarComoCompletado() {
  const urlParams = new URLSearchParams(window.location.search);
  const idEstudiante = urlParams.get('i');
  const idTask = urlParams.get('it');
  let secondsValue = seconds < 10 ? `0${seconds}` : seconds;
  let minutesValue = minutes < 10 ? `0${minutes}` : minutes;

  const id_task = idTask;
  const tiempo = `${minutesValue}:${secondsValue}`;
  const movimientos = movesCount;
  const id_estudiante = idEstudiante;

  // Actualizar los campos del formulario
  document.getElementById('id_task').value = id_task;
  document.getElementById('tiempo').value = tiempo;
  document.getElementById('movimientos').value = movimientos;
  document.getElementById('id_estudiante').value = id_estudiante;

  // Mostrar el formulario
  document.getElementById('game-complete').style.display = 'block';
}


btnFinish.addEventListener('click', marcarComoCompletado);

function mostrarNotificacion(icon, titulo, mensaje, redireccion = null) {
  $(document).ready(function() {
      Swal.fire({
          icon: icon,
          title: titulo,
          text: mensaje,
          confirmButtonText: "Aceptar"
      }).then(function(result) {
          if (result.isConfirmed && redireccion !== "") {
              window.location.href = redireccion;
          }
      });
  });
}

//back
back.addEventListener("click",
(  () =>{
  mostrarNotificacion("question", "¿Deseas abandonar el juego?", "", "../inicioAlumno.php");

} ));


