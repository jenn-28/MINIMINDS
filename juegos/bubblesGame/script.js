document.addEventListener("DOMContentLoaded", function() {
  const form = document.getElementById('game-settings-form');
  form.addEventListener('submit', function(event) {
    event.preventDefault(); // Evita que el formulario se envíe normalmente
    validarConfiguracion(); // Llama a la función para validar la configuración
  });

  // Inicia la animación usando lottie-player
  const lottiePlayer = document.createElement("lottie-player");
  lottiePlayer.setAttribute("src", "../../js_files/peces.json");
  lottiePlayer.setAttribute("background", "transparent");
  lottiePlayer.setAttribute("speed", "1");
  lottiePlayer.setAttribute("loop", "");
  lottiePlayer.setAttribute("autoplay", "");
  document.getElementById("game-container").appendChild(lottiePlayer);
});

const bubbleContainer = document.getElementById('game-container');
const scoreDisplay = document.getElementById('score-value');
const timerDisplay = document.getElementById('timer-value');
const correctLetterInfo = document.getElementById('correct-letter-value');
const correctLetterInput = document.getElementById('correct-letter');

let score = 0;
let correctLetter = '';
let timeLeft = 0;
let errors = 0;
let correctBubbles = 0;
let clickedCorrectBubbles = 0;
let timerInterval;
let bubblesInterval;

function updateTime() {
  const minutes = Math.floor(timeLeft / 60);
  const seconds = timeLeft % 60;
  timerDisplay.textContent = `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
}

let correctBubblesInterval = 0;

function createBubble() {
  const bubble = document.createElement('div');
  bubble.classList.add('bubble');
  const size = Math.random() * 40 + 10; // Random size between 10px and 20px
  bubble.style.width = `${size}px`;
  bubble.style.height = `${size}px`;
  bubble.style.left = `${Math.random() * (window.innerWidth - size)}px`;
  bubble.style.top = `${Math.random() * (window.innerHeight / 2 - size)}px`; // Restricting bubbles to upper half of the screen
  bubble.style.animationDuration = `${Math.random() * 5 + 3}s`; // Randomizing animation duration

  const letters = 'ABCDEFGHIJKLMNÑOPQRSTUVWXYZ';
  let randomLetter;
  if (Math.random() < 0.3) { // Probability of 30% to generate the correct letter bubble
    randomLetter = correctLetter;
  } else {
    randomLetter = letters.charAt(Math.floor(Math.random() * letters.length));
  }
  bubble.textContent = randomLetter;

  if (randomLetter === correctLetter) {
    correctBubbles++; // Increment correct bubble count
    correctBubblesInterval++; // Increment correct bubble interval
  }

  bubble.addEventListener('animationiteration', () => {
    bubble.remove(); // Remove bubble when animation ends
    createBubble(); // Create a new bubble after the animation ends
  });
  bubble.addEventListener('click', () => {
    if (bubble.textContent === correctLetter) {
      score += 10; // Increase score by 10 if correct letter is clicked
      scoreDisplay.textContent = score;
      clickedCorrectBubbles++;
      bubble.classList.add('burst'); // Add burst animation
      bubble.remove(); // Remove bubble when correct letter is clicked
    } else {
      errors++; // Increase error count if incorrect letter is clicked
    }
  });
  bubbleContainer.appendChild(bubble);
  bubble.addEventListener('animationend', () => {
    bubble.remove(); // Remove bubble when animation ends
  });

  // Verificar si ha pasado un intervalo de 5 segundos y aún no se ha generado una burbuja con la letra correcta
  if (correctBubblesInterval >= 5 && correctBubbles === 0) {
    correctBubblesInterval = 0; // Reiniciar el intervalo de burbujas correctas
    bubble.textContent = correctLetter; // Establecer la burbuja con la letra correcta
  }
}

function startGame() {
  score = 0;
  errors = 0;
  correctBubbles = 0;
  clickedCorrectBubbles = 0;
  scoreDisplay.textContent = score;
  correctLetter = correctLetterInput.value.toUpperCase(); // Set correct letter from input
  correctLetterInfo.textContent = correctLetter; // Display correct letter in game-header
  timeLeft = parseInt(document.getElementById('time-limit').value); // Set time limit from input
  updateTime();
  timerInterval = setInterval(() => {
    timeLeft--;
    updateTime();
    if (timeLeft === 0) {
      clearInterval(timerInterval);
      clearInterval(bubblesInterval);
      showResult();
    }
  }, 1000); // Update timer every second

  bubblesInterval = setInterval(createBubble, 1000); // Create bubbles continuously
  document.getElementById('game-container').style.display = 'block';
  document.getElementById('game-header').style.display = 'block';
  document.querySelector('.stop').style.display='block';
}

function showResult() {
  const correctPercentage = ((clickedCorrectBubbles * 100) / (correctBubbles)).toFixed(1); // Calcula el porcentaje con un decimal
  const result = document.createElement('div');
  result.classList.add('result');
  result.innerHTML = `
    <h2>Fin del juego</h2>
    <p>Tiempo: ${(60 - timeLeft)} segundos</p>
    <p>Puntaje: ${score}</p>
    <p>Porcentaje de Burbujas Correctas: ${correctPercentage}%</p>
    <p>Burbujas Correctas: ${correctBubbles}</p>
    <p>Burbujas Correctas Clickeadas: ${clickedCorrectBubbles}</p>
    <p>Errores: ${errors}</p>
    <button id="restart-game">Reiniciar Juego</button>
    <button id="exit-game">Salir del Juego</button>
  `;
  bubbleContainer.appendChild(result);
  document.getElementById('game-header').style.display = 'none'; // Hide game header

  document.getElementById('restart-game').addEventListener('click', () => {
    result.remove();
    startGame();
  });

  document.getElementById('exit-game').addEventListener('click', () => {
    result.remove();
    document.getElementById('parameters').style.display = 'block'; // Show parameter form
    document.getElementById('game-header').style.display = 'none'; // Hide game header
    document.getElementById('game-container').style.display='none';
  });
}

function validarConfiguracion() {
  var timeLimit = document.getElementById("time-limit").value;
  var correctLetter = document.getElementById("correct-letter").value;

  // Verifica si el tiempo está dentro de los parámetros y se ha seleccionado una letra
  if ((timeLimit >= 10 && timeLimit <= 60) && correctLetter !== "") {
    // Si los campos son válidos, aplica la configuración
    aplicarConfiguracion();
  } else {
    // Si hay campos inválidos, muestra un mensaje de error
    alert("Por favor completa los campos correctamente.");
  }
}

function aplicarConfiguracion() {
  // Aquí puedes escribir el código para aplicar la configuración y comenzar el juego
  document.getElementById('parameters').style.display = 'none'; // Oculta el formulario de parámetros
  startGame(); // Comienza el juego
}
