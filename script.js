// Variables globales
const vehicles = ['images/f1-ferrari.png', 'images/f1.png', 'images/f1-mclaren.png', 'images/f1-redbull.png'];
let currentVehicleIndex = 0;
const vehicleImage = document.getElementById('vehicle-image');
const prevButton = document.getElementById('prev');
const nextButton = document.getElementById('next');
const startButton = document.getElementById('start-game');
const vehicleSelection = document.getElementById('vehicle-selection');
const gameScreen = document.getElementById('game-screen');
const timerDisplay = document.getElementById('timer');
const lapCountDisplay = document.getElementById('lap-count');
const canvas = document.getElementById('game-canvas');
const ctx = canvas.getContext('2d');

// Variables du jeu
let timer = 0;
let laps = 0;
let hasCrossedStart = false; // Pour s'assurer que le joueur passe par la ligne de départ
let gameInterval;

// Position et vitesse de la voiture
let car = {
    x: 400,
    y: 300,
    width: 50,
    height: 30,
    speed: 5,
    angle: 0,
    image: new Image()
};

// Image du circuit
let trackImage = new Image();
trackImage.src = 'images/Monaco.png'; // Remplacez par votre propre image de circuit

// Points de référence pour le tour
const startLine = { x: 390, y: 500, width: 20, height: 5 }; // Ligne de départ

// Contrôles clavier
let keys = {
    ArrowUp: false,
    ArrowDown: false,
    ArrowLeft: false,
    ArrowRight: false
};

// Navigation des véhicules
prevButton.addEventListener('click', () => {
    currentVehicleIndex = (currentVehicleIndex - 1 + vehicles.length) % vehicles.length;
    vehicleImage.src = vehicles[currentVehicleIndex];
});

nextButton.addEventListener('click', () => {
    currentVehicleIndex = (currentVehicleIndex + 1) % vehicles.length;
    vehicleImage.src = vehicles[currentVehicleIndex];
});

// Démarrer le jeu
startButton.addEventListener('click', () => {
    car.image.src = vehicles[currentVehicleIndex];
    vehicleSelection.style.display = 'none';
    gameScreen.style.display = 'block';
    startGame();
});

// Démarrer le jeu
function startGame() {
    timer = 0;
    laps = 0;
    hasCrossedStart = false;
    lapCountDisplay.textContent = `${laps} / 3`;
    gameInterval = setInterval(updateGame, 1000 / 60); // 60 FPS
    window.addEventListener('keydown', handleKeyDown);
    window.addEventListener('keyup', handleKeyUp);
}

// Gérer les contrôles clavier
function handleKeyDown(e) {
    if (e.key in keys) keys[e.key] = true;
}

function handleKeyUp(e) {
    if (e.key in keys) keys[e.key] = false;
}

// Mettre à jour le jeu
function updateGame() {
    // Chrono
    timer += 1 / 60;
    const minutes = Math.floor(timer / 60);
    const seconds = Math.floor(timer % 60);
    timerDisplay.textContent = `${minutes}:${seconds < 10 ? '0' : ''}${seconds}`;

    // Mouvement de la voiture
    if (keys.ArrowUp) {
        car.x += Math.cos(car.angle) * car.speed;
        car.y += Math.sin(car.angle) * car.speed;
    }
    if (keys.ArrowDown) {
        car.x -= Math.cos(car.angle) * car.speed;
        car.y -= Math.sin(car.angle) * car.speed;
    }
    if (keys.ArrowLeft) {
        car.angle -= 0.05;
    }
    if (keys.ArrowRight) {
        car.angle += 0.05;
    }

    // Détection de la ligne de départ
    if (
        car.x > startLine.x &&
        car.x < startLine.x + startLine.width &&
        car.y > startLine.y &&
        car.y < startLine.y + startLine.height
    ) {
        if (!hasCrossedStart) {
            hasCrossedStart = true;
        }
    } else if (hasCrossedStart) {
        laps++;
        lapCountDisplay.textContent = `${laps} / 3`;
        hasCrossedStart = false;

        if (laps >= 3) {
            clearInterval(gameInterval);
            alert(`🏁 Course terminée en ${minutes}:${seconds < 10 ? '0' : ''}${seconds} !`);
        }
    }

    drawGame();
}

// Dessiner le jeu
function drawGame() {
    ctx.clearRect(0, 0, canvas.width, canvas.height);

    // Dessiner l'image du circuit comme fond
    if (trackImage.complete) {
        ctx.drawImage(trackImage, 0, 0, canvas.width, canvas.height);
    }

    // Dessiner la ligne de départ
    ctx.fillStyle = 'white';
    ctx.fillRect(startLine.x, startLine.y, startLine.width, startLine.height);

    // Dessiner la voiture
    if (car.image.complete) {
        ctx.save();
        ctx.translate(car.x, car.y);
        ctx.rotate(car.angle);
        ctx.drawImage(car.image, -car.width / 2, -car.height / 2, car.width, car.height);
        ctx.restore();
    }
}
