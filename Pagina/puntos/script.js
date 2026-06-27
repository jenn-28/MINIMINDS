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
    const figure = params.figura;

    const canvas = document.getElementById('canvas');
    const ctx = canvas.getContext('2d');
    const feedbackDiv = document.getElementById('feedback');
    const startButton = document.getElementById('start');
    const juegoDiv = document.querySelector('.juego');
    const inicioDiv = document.querySelector('.inicio');
    const relojDiv = document.getElementById('reloj');
    const endGame = document.getElementById('end');

    let points = [];
    let currentPoint = 0;
    let userOrder = [];
    let lines = [];
    let originalPoints = {};
    let timerInterval = null;
    let startTime = 0;
    const originalWidth = 800;
    const originalHeight = 700;

const shapes = {

    heart: [
        { x: 400, y: 660 }, { x: 35, y: 400 }, { x: 80, y: 200 },
        { x: 200, y: 100 }, { x: 400, y: 200 }, { x: 600, y: 100 },
        { x: 720, y: 200 }, { x: 765, y: 400 }, { x: 390, y: 670 }
    ],
    star: [
        { x: 400, y: 50 }, { x: 480, y: 250 }, { x: 680, y: 250 },
        { x: 510, y: 380 }, { x: 580, y: 600 }, { x: 400, y: 500 },
        { x: 220, y: 600 }, { x: 290, y: 380 }, { x: 120, y: 250 },
        { x: 320, y: 250 },{ x: 410, y: 40 }
    ],
    house: [
        { x: 50, y: 600 }, { x: 50, y: 250 }, { x: 395, y: 50 },
        { x: 750, y: 250 }, { x: 750, y: 600 }, { x: 500, y: 600 },
        { x: 500, y: 350 }, { x: 290, y: 350 }, { x: 290, y: 600 },  
        { x: 25, y: 600 }
    ],
    fish: [
        { x: 700, y: 380 }, { x: 500, y: 530 }, { x: 380, y: 630 },
        { x: 400, y: 490 }, { x: 200, y: 370 }, { x: 80, y: 480 },
        { x: 150, y: 320 }, { x: 50, y: 180 }, { x: 200, y: 270 },
        { x: 380, y: 190 }, { x: 380, y: 50 }, { x: 500, y: 170 },
        { x: 720, y: 370 }
    ],
    butterfly: [
        { x: 350, y: 500 }, { x: 190, y: 600 }, { x: 120, y: 550 },
        { x: 100, y: 490 }, { x: 200, y: 390 }, { x: 85, y: 300 },
        { x: 75, y: 100 }, { x: 175, y: 130 }, { x: 350, y: 300 },
        { x: 375, y: 265 }, { x: 395, y: 300 }, { x: 570, y: 130 },
        { x: 670, y: 100 }, { x: 660, y: 300 }, { x: 545, y: 390 },
        { x: 645, y: 490 }, { x: 625, y: 550 }, { x: 555, y: 600 },
        { x: 395, y: 500 }, { x: 375, y: 515 }
    ]
};

    function initializeShape(shapeName) {
        if (shapes[shapeName]) {
            const selectedShape = shapes[shapeName];
            originalPoints = JSON.parse(JSON.stringify(selectedShape)); // Copia profunda
            points = JSON.parse(JSON.stringify(selectedShape)); // Copia profunda para trabajar
            currentPoint = 0;
            userOrder = [];
            lines = [];
            scalePoints(); 
            clearCanvas();
            drawPoints();
            feedbackDiv.textContent = '';
            startTimer();
        } else {
            console.error("Figura no válida o no especificada en la URL");
            feedbackDiv.textContent = 'Figura no válida o no especificada en la URL';
        }
    }


    window.addEventListener('click', () => {
        canvas.width = canvas.offsetWidth;
        canvas.height = canvas.offsetHeight;
        scalePoints();
        clearCanvas();
        drawPoints();
    });

    function scalePoints() {
        const scaleX = canvas.width / originalWidth;
        const scaleY = canvas.height / originalHeight;
        points = originalPoints.map(point => ({
            x: point.x * scaleX,
            y: point.y * scaleY
        }));
    }

    function drawPoints() {
        ctx.fillStyle = 'black'; //color del trazo para los puntos    
        points.forEach((point, index) => {
            ctx.beginPath();
            ctx.arc(point.x, point.y, 5, 0, 2 * Math.PI);
            ctx.fill(); // stroke
            ctx.font = '14px Arial';
            ctx.fillText(index + 1, point.x + 10, point.y + 5);
        });
    
        // Dibujar las líneas 
        ctx.strokeStyle = 'black';
        ctx.lineWidth = 1;
        lines.forEach(line => {
            ctx.beginPath(); 
            ctx.moveTo(line.start.x, line.start.y); 
            ctx.lineTo(line.end.x, line.end.y); 
            ctx.stroke(); 
        });
    }


    function clearCanvas() {
        ctx.clearRect(0, 0, canvas.width, canvas.height);
    }

    function findClosestPoint(x, y) {
        let closestDistance = Infinity;
        let closestPoint = null;
        points.forEach((point, index) => {
            const distance = Math.sqrt(Math.pow(x - point.x, 2) + Math.pow(y - point.y, 2));
            if (distance < closestDistance && !userOrder.includes(index + 1)) {
                closestDistance = distance;
                closestPoint = index + 1;
            }
        });
        return closestPoint;
    }

    canvas.addEventListener('click', (e) => {
        if (points.length === 0)
            {
                feedbackDiv.textContent = 'Error: No se han definido puntos para esta figura.';
                return;
            }
            
            const rect = canvas.getBoundingClientRect();
            const x = e.clientX - rect.left;
            const y = e.clientY - rect.top;
            const closestPoint = findClosestPoint(x, y);
            
            if (closestPoint === currentPoint + 1) {
                userOrder.push(closestPoint);
                currentPoint++;
                if (currentPoint > 1) {
                    lines.push({ start: points[currentPoint - 2], end: points[currentPoint - 1] });
                }
                drawPoints();
                feedbackDiv.textContent = '';
                if (currentPoint === points.length) {
                    feedbackDiv.textContent = '¡Felicidades! Has completado el juego.';
                    stopTimer();
                    showResult();
                }
            } else if (currentPoint < points.length) {
                feedbackDiv.textContent = `Error. El siguiente punto debería ser el número ${currentPoint + 1}.`;
            }
            });    
            
            function formatElapsedTime(elapsedTime) {
                let hours = Math.floor(elapsedTime / 3600000);
                let minutes = Math.floor((elapsedTime % 3600000) / 60000);
                let seconds = Math.floor((elapsedTime % 60000) / 1000);
                return { hours, minutes, seconds };
            }
        
            function startTimer() {
                startTime = Date.now();
                if (timerInterval) {
                    clearInterval(timerInterval);
                }
                timerInterval = setInterval(() => {
                    let elapsedTime = Date.now() - startTime;
                    let { hours, minutes, seconds } = formatElapsedTime(elapsedTime);
                    relojDiv.textContent = `${pad(hours)}:${pad(minutes)}:${pad(seconds)}`;
                }, 1000);
            }
        
            function stopTimer() {
                clearInterval(timerInterval);
            }
        
            function pad(number) {
                return number.toString().padStart(2, '0');
            }
        
            startButton.addEventListener('click', () => {
                inicioDiv.style.display = 'none';
                endGame.style.display = 'none';
                juegoDiv.style.display = 'block';
                initializeShape(figure);
            });
        
            function showResult() {
                clearInterval(timerInterval);
                const elapsedTime = Date.now() - startTime;
                const { minutes, seconds } = formatElapsedTime(elapsedTime);
                const totalTime = `${pad(minutes)}:${pad(seconds)}`;
                const result = document.createElement('div');
                result.classList.add('result');
                result.innerHTML = `<p>Tiempo total: ${totalTime}</p>`;
                feedbackDiv.appendChild(result);
                endGame.style.display = 'block'; // Mostrar el div del fin del juego
                document.getElementById('stop-button').style.display='none';            
                // Obtener los parámetros de la URL
                const urlParams = new URLSearchParams(window.location.search);
                const idEstudiante = urlParams.get('i');
                const idTask = urlParams.get('it');
                const figura = urlParams.get('figura');
                const tiempo = totalTime;
            
                // Actualizar los campos del formulario
                document.getElementById('task_id').value = idTask;
                document.getElementById('student_id').value = idEstudiante;
                document.getElementById('tiempo').value = tiempo;
                document.getElementById('figura').value = figura;

                console.log("ID task:".idTask);
                console.log("ID estudiante:".idEstudiante);
                console.log("Tiempo:".tiempo);
                console.log(figura);
            
                document.getElementById('submit-completion-form').addEventListener('click', function(event) {
                    // Evitar el envío predeterminado del formulario
                    event.preventDefault();
                    // Enviar el formulario manualmente
                    document.getElementById('completion-form').submit();
                });
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