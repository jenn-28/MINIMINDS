// Repositorio de preguntas
const questionsRepo = [
    //tema1 = vocales
/*1*/   { pregunta: "Completa la palabra", respuestas: ["a", "e", "i", "o", "u"], respuestaCorrecta: 0, imagen: "img/gato.png", categoria: "vocales" },
/*2*/   { pregunta: "Completa la palabra", respuestas: ["u", "o", "i", "a", "e"], respuestaCorrecta: 3, imagen: "img/cama.png", categoria: "vocales" },
/*3*/   { pregunta: "Completa la palabra", respuestas: ["e", "a", "i", "o", "u"], respuestaCorrecta: 1, imagen: "img/vaca.png", categoria: "vocales" },
/*4*/   { pregunta: "Completa la palabra", respuestas: ["a", "u", "i", "o", "e"], respuestaCorrecta: 0, imagen: "img/dados.png", categoria: "vocales" },
/*5*/   { pregunta: "Completa la palabra", respuestas: ["a", "e", "i", "u", "o"], respuestaCorrecta: 1, imagen: "img/mesa.png", categoria: "vocales" },
/*6*/   { pregunta: "Completa la palabra", respuestas: ["a", "e", "u", "i", "o"], respuestaCorrecta: 1, imagen: "img/nube.png", categoria: "vocales" },
/*7*/   { pregunta: "Completa la palabra", respuestas: ["a", "o", "i", "e", "u"], respuestaCorrecta: 3, imagen: "img/pez.png", categoria: "vocales" },
/*8*/   { pregunta: "Completa la palabra", respuestas: [ "o", "u", "a", "e", "i"], respuestaCorrecta: 3, imagen: "img/abeja.png", categoria: "vocales" },
/*9*/   { pregunta: "Completa la palabra", respuestas: ["a", "e", "i", "o", "u"], respuestaCorrecta: 2, imagen: "img/niños.png", categoria: "vocales" },
/*10*/   { pregunta: "Completa la palabra", respuestas: ["a", "e", "i", "o", "u"], respuestaCorrecta: 2, imagen: "img/barril.png", categoria: "vocales" },

/*11*/   { pregunta: "Completa la palabra", respuestas: ["a", "e", "i", "o", "u"], respuestaCorrecta: 2, imagen: "img/hilo.png", categoria: "vocales" },
/*12*/   { pregunta: "Completa la palabra", respuestas: ["u", "o", "i", "a", "e"], respuestaCorrecta: 2, imagen: "img/anillo.png", categoria: "vocales" },
/*13*/   { pregunta: "Completa la palabra", respuestas: ["e", "a", "i", "o", "u"], respuestaCorrecta: 3, imagen: "img/coche.png", categoria: "vocales" },
/*14*/   { pregunta: "Completa la palabra", respuestas: ["a", "u", "i", "o", "e"], respuestaCorrecta: 3, imagen: "img/perro.png", categoria: "vocales" },
/*15*/   { pregunta: "Completa la palabra", respuestas: ["a", "e", "i", "u", "o"], respuestaCorrecta: 4, imagen: "img/foco.png", categoria: "vocales" },
/*16*/   { pregunta: "Completa la palabra", respuestas: ["a", "e", "u", "i", "o"], respuestaCorrecta: 4, imagen: "img/leon.png", categoria: "vocales" },
/*17*/   { pregunta: "Completa la palabra", respuestas: ["a", "e", "i", "o", "u"], respuestaCorrecta: 4, imagen: "img/parque.png", categoria: "vocales" },
/*18*/   { pregunta: "Completa la palabra", respuestas: ["a", "e", "i", "o", "u"], respuestaCorrecta: 4, imagen: "img/ducha.png", categoria: "vocales" },
/*19*/   { pregunta: "Completa la palabra", respuestas: ["a", "e", "i", "o", "u"], respuestaCorrecta: 4, imagen: "img/jaula.png", categoria: "vocales" },
/*20*/   { pregunta: "Completa la palabra", respuestas: ["a", "e", "i", "o", "u"], respuestaCorrecta: 4, imagen: "img/burro.png", categoria: "vocales" },

//tema2 = silabas
/*1*/{ pregunta: "Completa la palabra", respuestas: ["bo", "be", "bi", "bo", "bu"], respuestaCorrecta: 1, imagen: "img/bebe.png", categoria: "silabas" },
/*2*/{ pregunta: "Completa la palabra", respuestas: ["cu", "co", "ca", "ci", "ce"], respuestaCorrecta: 2, imagen: "img/camion.png", categoria: "silabas" },
/*3*/{ pregunta: "Completa la palabra", respuestas: ["co", "ca", "ce", "cu", "ci"], respuestaCorrecta: 4, imagen: "img/circo.png", categoria: "silabas" },
/*4*/{ pregunta: "Completa la palabra", respuestas: ["ga", "ge", "go", "gu", "gi"], respuestaCorrecta: 0, imagen: "img/galleta.png", categoria: "silabas" },
/*5*/{ pregunta: "Completa la palabra", respuestas: ["ho", "hi", "ha", "he", "hu"], respuestaCorrecta: 3, imagen: "img/helado.png", categoria: "silabas" },

/*6*/{ pregunta: "Completa la palabra", respuestas: ["ja", "gi", "ji", "ga", "hi"], respuestaCorrecta: 2, imagen: "img/jirafa.png", categoria: "silabas" },
/*7*/{ pregunta: "Completa la palabra", respuestas: ["ka", "ke", "ki", "ko", "ku"], respuestaCorrecta: 3, imagen: "img/koala.png", categoria: "silabas" },
/*8*/{ pregunta: "Completa la palabra", respuestas: ["li", "le", "la", "lo", "lu"], respuestaCorrecta: 0, imagen: "img/libro.png", categoria: "silabas" },
/*9*/{ pregunta: "Completa la palabra", respuestas: ["mu", "me", "mo", "ma", "mi"], respuestaCorrecta: 3, imagen: "img/mariposa.png", categoria: "silabas" },
/*10*/{ pregunta: "Completa la palabra", respuestas: ["ne", "na", "ni", "no", "nu"], respuestaCorrecta: 1, imagen: "img/niña.png", categoria: "silabas" },

/*11*/{ pregunta: "Completa la palabra", respuestas: ["ña", "ñe", "ñi", "ño", "ñu"], respuestaCorrecta: 1, imagen: "img/muñeca.png", categoria: "silabas" },
/*12*/{ pregunta: "Completa la palabra", respuestas: ["po", "pu", "pi", "pe", "pa"], respuestaCorrecta: 4, imagen: "img/panda.png", categoria: "silabas" },
/*13*/{ pregunta: "Completa la palabra", respuestas: ["ke", "ce", "que", "qui", "qe"], respuestaCorrecta: 2, imagen: "img/Chaqueta.png", categoria: "silabas" },
/*14*/{ pregunta: "Completa la palabra", respuestas: ["re", "ro", "ri", "ru", "ra"], respuestaCorrecta: 4, imagen: "img/raton.png", categoria: "silabas" },
/*15*/{ pregunta: "Completa la palabra", respuestas: ["so", "sa", "se", "si", "su"], respuestaCorrecta: 0, imagen: "img/sopa.png", categoria: "silabas" },

/*16*/{ pregunta: "Completa la palabra", respuestas: ["te", "ta", "tu", "ti", "to"], respuestaCorrecta: 2, imagen: "img/tucan.png", categoria: "silabas" },
/*17*/{ pregunta: "Completa la palabra", respuestas: ["ve", "vu", "vo", "va", "vi"], respuestaCorrecta: 4, imagen: "img/violin.png", categoria: "silabas" },
/*18*/{ pregunta: "Completa la palabra", respuestas: ["sa", "xa", "ca", "za", "ha"], respuestaCorrecta: 1, imagen: "img/examen.png", categoria: "silabas" },
/*19*/{ pregunta: "Completa la palabra", respuestas: ["ya", "ye", "yi", "yo", "yu"], respuestaCorrecta: 3, imagen: "img/yogur.png", categoria: "silabas" },
/*20*/{ pregunta: "Completa la palabra", respuestas: ["za", "zo", "zu", "ze", "zi"], respuestaCorrecta: 1, imagen: "img/zorro.png", categoria: "silabas" },

//Tema 3 = consonantes
/*1*/{ pregunta: "Completa la palabra", respuestas: ["b", "c", "d", "f", "g"], respuestaCorrecta: 0, imagen: "consonantes.jpg", categoria: "consonantes" },
/*2*/{ pregunta: "Completa la palabra", respuestas: ["b", "c", "d", "f", "g"], respuestaCorrecta: 1, imagen: "consonantes.jpg", categoria: "consonantes" },
/*3*/{ pregunta: "Completa la palabra", respuestas: ["b", "c", "d", "f", "g"], respuestaCorrecta: 2, imagen: "consonantes.jpg", categoria: "consonantes" },
/*4*/{ pregunta: "Completa la palabra", respuestas: ["b", "c", "d", "f", "g"], respuestaCorrecta: 3, imagen: "consonantes.jpg", categoria: "consonantes" },

/*5*/{ pregunta: "Completa la palabra", respuestas: ["j", "k", "l", "m", "n"], respuestaCorrecta: 1, imagen: "consonantes.jpg", categoria: "consonantes" },
/*6*/{ pregunta: "Completa la palabra", respuestas: ["j", "k", "l", "m", "n"], respuestaCorrecta: 2, imagen: "consonantes.jpg", categoria: "consonantes" },
/*7*/{ pregunta: "Completa la palabra", respuestas: ["j", "k", "l", "m", "n"], respuestaCorrecta: 3, imagen: "consonantes.jpg", categoria: "consonantes" },
/*8*/{ pregunta: "Completa la palabra", respuestas: ["j", "k", "l", "m", "n"], respuestaCorrecta: 4, imagen: "consonantes.jpg", categoria: "consonantes" },

/*9*/{ pregunta: "Completa la palabra", respuestas: ["ñ", "p", "q", "r", "s"], respuestaCorrecta: 0, imagen: "consonantes.jpg", categoria: "consonantes" },
/*10*/{ pregunta: "Completa la palabra", respuestas: ["ñ", "p", "q", "r", "s"], respuestaCorrecta: 1, imagen: "consonantes.jpg", categoria: "consonantes" },
/*11*/{ pregunta: "Completa la palabra", respuestas: ["ñ", "p", "q", "r", "s"], respuestaCorrecta: 2, imagen: "consonantes.jpg", categoria: "consonantes" },
/*12*/{ pregunta: "Completa la palabra", respuestas: ["ñ", "p", "q", "r", "s"], respuestaCorrecta: 3, imagen: "consonantes.jpg", categoria: "consonantes" },

/*13*/{ pregunta: "Completa la palabra", respuestas: ["t", "v", "w", "x", "y"], respuestaCorrecta: 1, imagen: "consonantes.jpg", categoria: "consonantes" },
/*14*/{ pregunta: "Completa la palabra", respuestas: ["t", "v", "w", "x", "y"], respuestaCorrecta: 1, imagen: "consonantes.jpg", categoria: "consonantes" },
/*15*/{ pregunta: "Completa la palabra", respuestas: ["t", "v", "w", "x", "y"], respuestaCorrecta: 1, imagen: "consonantes.jpg", categoria: "consonantes" },
/*16*/{ pregunta: "Completa la palabra", respuestas: ["t", "v", "w", "x", "y"], respuestaCorrecta: 1, imagen: "consonantes.jpg", categoria: "consonantes" },

/*17*/{ pregunta: "Completa la palabra", respuestas: ["z", "s", "g", "j", "h"], respuestaCorrecta: 1, imagen: "consonantes.jpg", categoria: "consonantes" },
/*18*/{ pregunta: "Completa la palabra", respuestas: ["z", "s", "g", "j", "h"], respuestaCorrecta: 1, imagen: "consonantes.jpg", categoria: "consonantes" },
/*19*/{ pregunta: "Completa la palabra", respuestas: ["z", "s", "g", "j", "h"], respuestaCorrecta: 1, imagen: "consonantes.jpg", categoria: "consonantes" },
/*20*/{ pregunta: "Completa la palabra", respuestas: ["z", "s", "g", "j", "h"], respuestaCorrecta: 1, imagen: "consonantes.jpg", categoria: "consonantes" },

//Tema 4 = numeros
/*1*/{ pregunta: "¿Cual es el numero que falta?", respuestas: ["1", "2", "3", "4", "5"], respuestaCorrecta: 0, imagen: "img/uno.png", categoria: "numeros" },
/*2*/{ pregunta: "¿Cual es el numero que falta?", respuestas: ["1", "2", "3", "4", "5"], respuestaCorrecta: 1, imagen: "img/dos.png", categoria: "numeros" },
/*3*/{ pregunta: "¿Cual es el numero que falta?", respuestas: ["1", "2", "3", "4", "5"], respuestaCorrecta: 2, imagen: "img/tres.png", categoria: "numeros" },
/*4*/{ pregunta: "¿Cual es el numero que falta?", respuestas: ["1", "2", "3", "4", "5"], respuestaCorrecta: 4, imagen: "img/cinco.png", categoria: "numeros" },

/*5*/{ pregunta: "¿Cual es el numero que falta?", respuestas: ["6", "7", "8", "9", "10"], respuestaCorrecta: 1, imagen: "img/siete.png", categoria: "numeros" },
/*6*/{ pregunta: "¿Cual es el numero que falta?", respuestas: ["6", "7", "8", "9", "10"], respuestaCorrecta: 2, imagen: "img/ocho.png", categoria: "numeros" },
/*7*/{ pregunta: "¿Cual es el numero que falta?", respuestas: ["6", "7", "8", "9", "10"], respuestaCorrecta: 3, imagen: "img/nueve.png", categoria: "numeros" },
/*8*/{ pregunta: "¿Cual es el numero que falta?", respuestas: ["6", "7", "8", "9", "10"], respuestaCorrecta: 4, imagen: "img/diez.png", categoria: "numeros" },

/*9*/{ pregunta: "¿Cual es el numero que falta?", respuestas: ["11", "12", "13", "14", "15"], respuestaCorrecta: 0, imagen: "img/once.png", categoria: "numeros" },
/*10*/{ pregunta: "¿Cual es el numero que falta?", respuestas: ["11", "12", "13", "14", "15"], respuestaCorrecta: 1, imagen: "img/doce.png", categoria: "numeros" },

/*11*/{ pregunta: "¿Cual es el numero que falta?", respuestas: ["16", "17", "18", "19", "20"], respuestaCorrecta: 0, imagen: "img/dieciseis.png", categoria: "numeros" },
/*12*/{ pregunta: "¿Cual es el numero que falta?", respuestas: ["16", "17", "18", "19", "20"], respuestaCorrecta: 4, imagen: "img/veinte.png", categoria: "numeros" },

//Tema 4.2 = conntar objetos
/*1*/{ pregunta: "¿Cuantos hay?", respuestas: ["1", "2", "3", "4", "5"], respuestaCorrecta: 0, imagen: "img/1oso.png", categoria: "numeros" },
/*2*/{ pregunta: "¿Cuantos hay?", respuestas: ["1", "2", "3", "4", "5"], respuestaCorrecta: 1, imagen: "img/2pelota.png", categoria: "numeros" },
/*3*/{ pregunta: "¿Cuantos hay?", respuestas: ["1", "2", "3", "4", "5"], respuestaCorrecta: 2, imagen: "img/3estrella.png", categoria: "numeros" },
/*4*/{ pregunta: "¿Cuantos hay?", respuestas: ["5", "8", "4", "5", "9"], respuestaCorrecta: 2, imagen: "img/4ovni.png", categoria: "numeros" },
/*5*/{ pregunta: "¿Cuantos hay?", respuestas: ["5", "6", "7", "8", "9"], respuestaCorrecta: 0, imagen: "img/5gato.png", categoria: "numeros" },
/*6*/{ pregunta: "¿Cuantos hay?", respuestas: ["5", "6", "7", "8", "9"], respuestaCorrecta: 1, imagen: "img/6manzana.png", categoria: "numeros" },
/*7*/{ pregunta: "¿Cuantos hay?", respuestas: ["4", "5", "6", "7", "8"], respuestaCorrecta: 3, imagen: "img/7pato.png", categoria: "numeros" },
/*8*/{ pregunta: "¿Cuantos hay?", respuestas: ["4", "6", "8", "10", "5"], respuestaCorrecta: 2, imagen: "img/8conejo.png", categoria: "numeros" },
/*9*/{ pregunta: "¿Cuantos hay?", respuestas: ["6", "10", "9", "8", "3"], respuestaCorrecta: 2, imagen: "img/9flor.png", categoria: "numeros" },
/*10*/{ pregunta: "¿Cuantos hay?", respuestas: ["8", "9", "10", "7", "6"], respuestaCorrecta: 2, imagen: "img/10lapiz.png", categoria: "numeros" },
/*11*/{ pregunta: "¿Cuantos hay?", respuestas: ["5", "9", "10", "11", "6"], respuestaCorrecta: 3, imagen: "img/11carro.png", categoria: "numeros" },
/*12*/{ pregunta: "¿Cuantos hay?", respuestas: ["8", "6", "12", "10", "11"], respuestaCorrecta: 2, imagen: "img/12dino.png", categoria: "numeros" },

  ];

document.addEventListener('DOMContentLoaded', function() {   
let currentQuestions = [];
let questionIndex = 0;
let score = 0;
let startTime = 0;
let timerInterval;
let seconds = 0;
let minutes = 0;

// Función para extraer parámetros de la URL
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
const numQuestions = parseInt(params.cant_p);
const category = params.tema;




// Función para iniciar el juego con la configuración proporcionada
function startGame(numQuestions, category) {
    currentQuestions = selectRandomQuestions(numQuestions, category);
    questionIndex = 0;
    score = 0;
    startTime = Date.now();
    showNextQuestion();
    updateGameInfo();
    timerInterval = setInterval(updateTime, 1000);
    document.querySelector('.controls-container').style.display = 'none';
    document.querySelector('.end-game').style.display = 'none';
    document.querySelector('.question-card').style.display = 'block';
    document.getElementById('game-info').style.display = 'block';
    document.getElementById('question-container').style.display = 'block';
}

// Función para seleccionar preguntas al azar basadas en la categoría
function selectRandomQuestions(numQuestions, category) {
    let selectedQuestions = [];
    const filteredQuestions = questionsRepo.filter(question => question.categoria === category);
    const shuffledQuestions = filteredQuestions.sort(() => Math.random() - 0.5);
    selectedQuestions = shuffledQuestions.slice(0, numQuestions);
    return selectedQuestions;
}

// Función para mostrar la siguiente pregunta
function showNextQuestion() {
    if (questionIndex < currentQuestions.length) {
        const question = currentQuestions[questionIndex];
        showQuestion(question);
        updateGameInfo();
        questionIndex++;
    } else {
        endGame();
    }
}

// Función para mostrar una pregunta
function showQuestion(question) {
    const questionElement = document.getElementById('question');
    const imageElement = document.getElementById('question-image');
    const optionsElement = document.getElementById('options');

    questionElement.innerText = `${question.pregunta}`;
    imageElement.src = question.imagen;

    optionsElement.innerHTML = '';
    question.respuestas.forEach((respuesta, index) => {
        const button = document.createElement('button');
        button.innerText = respuesta;
        button.classList.add('option-btn');
        button.addEventListener('click', () => checkAnswer(index, question.respuestaCorrecta));
        optionsElement.appendChild(button);
    });
}

// Función para verificar la respuesta seleccionada por el usuario
function checkAnswer(selectedIndex, correctIndex) {
    if (selectedIndex === correctIndex) {
        score++;
    }
    showNextQuestion();
}

// Función para actualizar el tiempo cada segundo
function updateTime() {
    seconds += 1;
    if (seconds >= 60) {
        minutes += 1;
        seconds = 0;
    }
    document.getElementById('time-counter').innerHTML = `${pad(minutes)}:${pad(seconds)}`;
}

// Función para formatear el tiempo en formato MM:SS
function pad(number) {
    return number < 10 ? `0${number}` : number;
}

// Función para formatear el tiempo total en formato MM:SS
function formatTime(milliseconds) {
    let seconds = Math.floor(milliseconds / 1000);
    const hours = Math.floor(seconds / 3600);
    seconds %= 3600;
    const minutes = Math.floor(seconds / 60);
    seconds %= 60;
    return `${pad(minutes)}:${pad(seconds)}`;
}

// Función para manejar el final del juego
function endGame() {
    clearInterval(timerInterval);
    const totalQuestions = currentQuestions.length;
    const correctPercentage = Math.floor((score / totalQuestions) * 100);
    const elapsedTime = Date.now() - startTime;
    const totalTime = formatTime(elapsedTime);

    const message = `Puntuación final: ${score}/${totalQuestions}
    <br>Porcentaje de respuestas correctas: ${correctPercentage}%
    <br>Tiempo total: ${totalTime}`;
    document.getElementById('end-game-message').innerHTML = message;

    document.querySelector('.question-card').style.display = 'none';
    document.getElementById('game-info').style.display = 'none';
    document.querySelector('.end-game').style.display = 'block';
}

// Función para actualizar la información del juego
function updateGameInfo() {
    document.getElementById('question-number').textContent = questionIndex + 1;
    document.getElementById('total-questions').textContent = currentQuestions.length;
    document.getElementById('score-counter').textContent = score;
}

// Asignar el evento de inicio de juego al botón "Start"
document.getElementById('start').addEventListener('click', function(event) {
    event.preventDefault();
    startGame(numQuestions, category);
});


// Función para marcar como completado y enviar el formulario
function marcarComoCompletado() {
    const urlParams = new URLSearchParams(window.location.search);
    const idEstudiante = urlParams.get('i');
    const idTask = urlParams.get('it');
    const tiempo = `${pad(minutes)}:${pad(seconds)}`;
    const puntaje = score;
    const porcentaje = Math.floor((score / currentQuestions.length) * 100);

    // Actualizar los campos del formulario
    document.getElementById('task_id').value = idTask;
    document.getElementById('student_id').value = idEstudiante;
    document.getElementById('tiempo').value = tiempo;
    document.getElementById('puntaje').value = puntaje;
    document.getElementById('porcentaje').value = porcentaje;

    // Enviar el formulario
    document.getElementById('completion-form').submit();
}

// Asignar el evento de clic al botón "Finalizar"
document.getElementById('submit-completion-form').addEventListener('click', marcarComoCompletado);

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

// Función para manejar el evento de clic en el botón "Stop"
document.getElementById('stop-button').addEventListener('click', function() {
    // Mostrar la alerta de SweetAlert y obtener la respuesta del usuario
    mostrarNotificacion("question", "¿Deseas abandonar el juego?", "", "../inicioAlumno.php");
});
  });