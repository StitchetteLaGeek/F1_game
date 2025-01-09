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
    x: 350,
    y: 300,
    width: 50,
    height: 30,
    speed: 5,
    angle: 0,
    image: new Image()
};

// Image du circuit
let trackImage = new Image();
trackImage.src = 'images/Monaco.png'; // Par défaut, le circuit Monaco

// Paramètres des lignes de départ et des positions de départ pour chaque circuit
const startLines = {
    monaco: { x: 351, y: 301, width: 5, height: 40 },
    hockenheim: { x: 351, y: 301, width: 5, height: 40 }, // Exemple pour hockenheim
    shanghai: { x: 301, y: 550, width: 5, height: 40 }, // Exemple pour Shanghai
    nuerburgring: { x: 600, y: 420, width: 5, height: 40 } // Exemple pour Nuerburgring
};

const startPositions = {
    monaco: { x: 350, y: 300 }, // Position de départ pour Monaco
    hockenheim: { x: 100, y: 300 }, // Position de départ pour hockenheim
    shanghai: { x: 301, y: 550 }, // Position de départ pour Shanghai
    nuerburgring: { x: 600, y: 420 } // Position de départ pour Nuerburgring
};

let currentStartLine = startLines.monaco; // Par défaut, Monaco
let currentStartPosition = startPositions.monaco; // Par défaut, Monaco

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

// Gérer la sélection du circuit et mettre à jour l'image de fond et la position de départ
// Gérer la sélection du circuit et mettre à jour l'image de fond et la position de départ
function updateTrackImage() {
    const circuit = document.getElementById('circuit-select').value;
    
    timer = 0;
    timerDisplay.textContent = "0:00"; // Réinitialiser l'affichage du timer

    // Mettre à jour l'image du circuit en fonction de la sélection
    if (circuit.toLowerCase() === 'hockenheim') {
        trackImage.src = 'images/Hockenheim.png';
        currentStartLine = startLines.hockenheim;
        currentStartPosition = startPositions.hockenheim;
    } else if (circuit.toLowerCase() === 'monaco') {
        trackImage.src = 'images/monaco.png';
        currentStartLine = startLines.monaco;
        currentStartPosition = startPositions.monaco;
    } else if (circuit.toLowerCase() === 'shanghai') {
        trackImage.src = 'images/shanghai.png';
        currentStartLine = startLines.shanghai;
        currentStartPosition = startPositions.shanghai;
    } else if (circuit.toLowerCase() === 'nuerburgring') {
        trackImage.src = 'images/nuerburgring.png';
        currentStartLine = startLines.nuerburgring;
        currentStartPosition = startPositions.nuerburgring;
    }
    // Réinitialiser la position de la voiture au départ du circuit
    car.x = currentStartPosition.x;
    car.y = currentStartPosition.y;
}


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

    // Détection de la ligne de départ (gauche à droite)
    if (
        car.x > currentStartLine.x &&
        car.x < currentStartLine.x + currentStartLine.width &&
        car.y > currentStartLine.y &&
        car.y < currentStartLine.y + currentStartLine.height
    ) {
        if (!hasCrossedStart) {
            hasCrossedStart = true;
            laps++; // Incrémentez le tour
            lapCountDisplay.textContent = `${laps} / 3`; // Affiche le nombre de tours
        }
    } else if (hasCrossedStart) {
        hasCrossedStart = false;
    }

    // Si tous les tours sont terminés
    if (laps >= 3) {
        clearInterval(gameInterval);
        alert(`🏁 Course terminée en ${minutes}:${seconds < 10 ? '0' : ''}${seconds} !`);
    }

    drawGame();  // Mise à jour du dessin
}

// Dessiner le jeu
function drawGame() {
    ctx.clearRect(0, 0, canvas.width, canvas.height);

    // Dessiner l'image du circuit comme fond
    if (trackImage.complete) {
        ctx.drawImage(trackImage, 0, 0, canvas.width, canvas.height);
    }

    // Dessiner la ligne de départ en rouge (verticale)
    ctx.fillStyle = 'red';  // Couleur rouge pour la ligne de départ
    ctx.fillRect(currentStartLine.x, currentStartLine.y, currentStartLine.width, currentStartLine.height);

    // Dessiner la voiture
    if (car.image.complete) {
        ctx.save();
        ctx.translate(car.x, car.y);
        ctx.rotate(car.angle);
        ctx.drawImage(car.image, -car.width / 2, -car.height / 2, car.width, car.height);
        ctx.restore();
    }
}
