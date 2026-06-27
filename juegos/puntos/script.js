const canvas = document.getElementById('canvas');
const ctx = canvas.getContext('2d');
const feedbackDiv = document.getElementById('feedback');
const shapeSelect = document.getElementById('shape');
const colorInput = document.getElementById('color');
const fillButton = document.getElementById('fillButton');

let points = [];
let currentPoint = 0;
let userOrder = [];
let lines = [];
let originalPoints = {};
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

shapeSelect.addEventListener('change', () => {
    const selectedShape = shapes[shapeSelect.value];
    originalPoints = JSON.parse(JSON.stringify(selectedShape)); // Copia profunda
    points = JSON.parse(JSON.stringify(selectedShape)); // Copia profunda para trabajar
    currentPoint = 0;
    userOrder = [];
    lines = [];
    scalePoints(); // Escalar puntos inmediatamente al seleccionar la figura
    clearCanvas();
    drawPoints();
    fillButton.disabled = true;
    feedbackDiv.textContent = '';
});



fillButton.addEventListener('click', () => {
    const color = colorInput.value;
    fillShape(color);
});

canvas.width = canvas.offsetWidth;
canvas.height = canvas.offsetHeight;

window.addEventListener('resize', () => {
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
    ctx.fillStyle = 'black';
    points.forEach((point, index) => {
        ctx.beginPath();
        ctx.arc(point.x, point.y, 5, 0, 2 * Math.PI);
        ctx.fill();
        ctx.font = '14px Arial';
        ctx.fillText(index + 1, point.x + 10, point.y + 5);
    });
    ctx.strokeStyle = 'black';
    ctx.lineWidth = 1;
    lines.forEach(line => {
        ctx.beginPath();
        ctx.moveTo(line.start.x, line.start.y);
        ctx.lineTo(line.end.x, line.end.y);
        ctx.stroke();
    });
}

function fillShape(color) {
    clearCanvas();
    drawPoints();
    ctx.fillStyle = color;
    ctx.beginPath();
    ctx.moveTo(points[points.length - 1].x, points[points.length - 1].y);
    points.forEach(point => {
        ctx.lineTo(point.x, point.y);
    });
    ctx.closePath();
    ctx.fill();
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
            fillButton.disabled = false;
        }
    } else if (currentPoint < points.length) {
        feedbackDiv.textContent = `Error. El siguiente punto debería ser el número ${currentPoint + 1}.`;
    }
});
