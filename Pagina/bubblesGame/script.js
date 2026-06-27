document.addEventListener("DOMContentLoaded", function() {

  function getQueryParams() {
    const params = {};
    window.location.search.substring(1).split('&').forEach(param => {
      const [key, value] = param.split('=');
      params[key] = decodeURIComponent(value);
    });
    return params;
  }

  // Extraer parámetros de la URL al cargar la página
  const params = getQueryParams();
  const timeL = parseInt(params.time);
  const leter = params.letra;

  // Inicia la animación usando lottie-player
  const lottiePlayer = document.createElement("lottie-player");
  lottiePlayer.setAttribute("src", "../../js_files/peces.json");
  lottiePlayer.setAttribute("background", "transparent");
  lottiePlayer.setAttribute("speed", "1");
  lottiePlayer.setAttribute("loop", "");
  lottiePlayer.setAttribute("autoplay", "");
  document.getElementById("game-container").appendChild(lottiePlayer);

  const bubbleContainer = document.getElementById('game-container');
  const scoreDisplay = document.getElementById('score-value');
  const timerDisplay = document.getElementById('time-limit');
  const correctLetterInfo = document.getElementById('correct-letter-value');
  const play = document.getElementById('start');

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
    const size = Math.random() * 40 + 10; // Random size between 10px and 50px
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
        score += 1; // Increase score by 1 if correct letter is clicked
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
    correctLetter = leter; 
    correctLetterInfo.textContent = correctLetter; // Display correct letter in game-header
    timeLeft = timeL; // Set time limit from input
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
    document.querySelector('.stop').style.display = 'block';
    document.getElementById('inicio-juego').style.display = 'none';
  }

  play.addEventListener('click', () => {
    startGame();
  });

  function showResult() {
    document.getElementById('overlay').style.display = 'block';


    const correctPercentage = ((clickedCorrectBubbles * 100) / (correctBubbles)).toFixed(1); // Calcula el porcentaje con un decimal
    const result = document.createElement('div');
    result.classList.add('result');
    result.innerHTML = `
      <h2>Fin del juego</h2>
      <p>Tiempo: ${(timeL - timeLeft)} segundos</p>
      <p>Puntaje: ${score}</p>
      <p>Porcentaje de Burbujas Correctas: ${correctPercentage}%</p>
      <p>Burbujas Correctas: ${correctBubbles}</p>
      <p>Burbujas Correctas Clickeadas: ${clickedCorrectBubbles}</p>
      <p>Errores: ${errors}</p>
      <form id="completion-form" action="finisher.php" method="POST">
        <input type="hidden" id="task_id" name="task_id">
        <input type="hidden" id="student_id" name="student_id">
        <input type="hidden" name="tiempo" id="tiempo">
        <input type="hidden" name="puntaje" id="puntaje">
        <input type="hidden" name="porcentaje" id="porcentaje">
        <button id="submit-completion-form" type="submit">Finalizar</button>
      </form>
    `;
    bubbleContainer.appendChild(result);
    document.getElementById('game-header').style.display = 'none'; // Hide game header 

    // Mostrar en consola los datos enviados
    const urlParams = new URLSearchParams(window.location.search);
    const idEstudiante = urlParams.get('i');
    const idTask = urlParams.get('it');
    const tiempo = urlParams.get('time');
    console.log("Task ID:", idTask);
    console.log("Student ID:", idEstudiante);
    console.log("Tiempo:", tiempo);
    console.log("Puntaje:", score);
    console.log("Porcentaje:", correctPercentage);

    // Actualizar los campos del formulario
    document.getElementById('task_id').value = idTask;
    document.getElementById('student_id').value = idEstudiante;
    document.getElementById('tiempo').value = tiempo;
    document.getElementById('puntaje').value = score;
    document.getElementById('porcentaje').value = correctPercentage;
  }


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

  // Asignar el evento de clic al botón "Stop"
  document.getElementById('stop-button').addEventListener('click', function() {
    // Mostrar la alerta de SweetAlert y obtener la respuesta del usuario
    mostrarNotificacion("question", "¿Deseas abandonar el juego?", "", "../inicioAlumno.php");
  });

});
