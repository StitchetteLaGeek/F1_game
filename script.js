const isMobile = window.innerWidth <= 768 || /iPhone|iPad|iPod|Android/i.test(navigator.userAgent);

// Variables globales
const vehicles = ['images/f1-ferrari.png', 'images/f1.png', 'images/f1-mclaren.png', 'images/f1-redbull.png', 'images/barbie.png'];
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
const checkpointThreshold = 20;

// Variables du jeu
let timer = 0;
let laps = 0;
let hasCrossedStart = false; // Pour s'assurer que le joueur passe par la ligne de départ
let gameInterval;

// Liste des checkpoints
let checkpoints = {
    monaco: [{x: 200, y: 195, validated: false}, {x: 600, y: 150, validated: false}, {x: 600, y: 520, validated: false}],
    hockenheim: [{x: 100, y: 200, validated: false}, {x: 500, y: 350, validated: false}],
    shanghai: [{x: 300, y: 100, validated: false}, {x: 500, y: 540, validated: false}],
    nuerburgring: [{x: 250, y: 362, validated: false}, {x: 420, y: 190, validated: false}],
};

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

// Contrôles mobiles et clavier
let keys = {
    ArrowUp: false,
    ArrowDown: false,
    ArrowLeft: false,
    ArrowRight: false
};

// Fonction pour initialiser les contrôles sur mobile
function initArrowControl() {
    const arrowButtons = document.getElementById('arrow-buttons');
    const upButton = document.getElementById('arrow-up');
    const downButton = document.getElementById('arrow-down');
    const leftButton = document.getElementById('arrow-left');
    const rightButton = document.getElementById('arrow-right');

    // Variables pour maintenir les boutons enfoncés
    let isUpPressed = false;
    let isDownPressed = false;
    let isLeftPressed = false;
    let isRightPressed = false;

    // Afficher les flèches si c'est un mobile
    if (isMobile) {
        arrowButtons.style.display = 'grid';
    }

    // Ajouter les événements de déplacement sur les boutons
    upButton.addEventListener('touchstart', () => {
        isUpPressed = true;
    });
    upButton.addEventListener('touchend', () => {
        isUpPressed = false;
    });

    downButton.addEventListener('touchstart', () => {
        isDownPressed = true;
    });
    downButton.addEventListener('touchend', () => {
        isDownPressed = false;
    });

    leftButton.addEventListener('touchstart', () => {
        isLeftPressed = true;
    });
    leftButton.addEventListener('touchend', () => {
        isLeftPressed = false;
    });

    rightButton.addEventListener('touchstart', () => {
        isRightPressed = true;
    });
    rightButton.addEventListener('touchend', () => {
        isRightPressed = false;
    });

    // Garder la logique des mouvements avec maintien du bouton pour les touches mobiles
    setInterval(() => {
        if (isUpPressed) {
            car.x += Math.cos(car.angle) * car.speed;
            car.y += Math.sin(car.angle) * car.speed;
        }
        if (isDownPressed) {
            car.x -= Math.cos(car.angle) * car.speed;
            car.y -= Math.sin(car.angle) * car.speed;
        }
        if (isLeftPressed) {
            car.angle -= 0.05;
        }
        if (isRightPressed) {
            car.angle += 0.05;
        }
    }, 1000 / 60); // 60 FPS
}

// Paramètres des lignes de départ et des positions de départ pour chaque circuit
const startLines = {
    monaco: {x: 351, y: 301, width: 5, height: 40},
    hockenheim: {x: 351, y: 301, width: 5, height: 40},
    shanghai: {x: 301, y: 550, width: 5, height: 40},
    nuerburgring: {x: 600, y: 420, width: 5, height: 40}
};

const startPositions = {
    monaco: {x: 350, y: 300},
    hockenheim: {x: 100, y: 300},
    shanghai: {x: 301, y: 550},
    nuerburgring: {x: 600, y: 420}
};

let currentStartLine = startLines.monaco; // Par défaut, Monaco
let currentStartPosition = startPositions.monaco; // Par défaut, Monaco
let currentCheckpoints = checkpoints.monaco;

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

// Gérer les checkpoints
function checkpoint() {
    for (let i = 0; i < currentCheckpoints.length; i++) {
        if (!currentCheckpoints[i].validated) {
            if (Math.sqrt(Math.pow(car.x - currentCheckpoints[i].x, 2) + Math.pow(car.y - currentCheckpoints[i].y, 2)) < checkpointThreshold) {
                currentCheckpoints[i].validated = true;
                console.log(`Checkpoint ${i + 1} validé !`);
            }
        }
    }
}

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
        currentCheckpoints = checkpoints.hockenheim;
    } else if (circuit.toLowerCase() === 'monaco') {
        trackImage.src = 'images/monaco.png';
        currentStartLine = startLines.monaco;
        currentStartPosition = startPositions.monaco;
        currentCheckpoints = checkpoints.monaco;
    } else if (circuit.toLowerCase() === 'shanghai') {
        trackImage.src = 'images/shanghai.png';
        currentStartLine = startLines.shanghai;
        currentStartPosition = startPositions.shanghai;
        currentCheckpoints = checkpoints.shanghai;
    } else if (circuit.toLowerCase() === 'nuerburgring') {
        trackImage.src = 'images/nuerburgring.png';
        currentStartLine = startLines.nuerburgring;
        currentStartPosition = startPositions.nuerburgring;
        currentCheckpoints = checkpoints.nuerburgring;
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
    if (keys.ArrowUp || isMobile && document.getElementById('arrow-up').style.backgroundColor === 'rgb(255, 255, 255)') {
        car.x += Math.cos(car.angle) * car.speed;
        car.y += Math.sin(car.angle) * car.speed;
    }
    if (keys.ArrowDown || isMobile && document.getElementById('arrow-down').style.backgroundColor === 'rgb(255, 255, 255)') {
        car.x -= Math.cos(car.angle) * car.speed;
        car.y -= Math.sin(car.angle) * car.speed;
    }
    if (keys.ArrowLeft || isMobile && document.getElementById('arrow-left').style.backgroundColor === 'rgb(255, 255, 255)') {
        car.angle -= 0.05;
    }
    if (keys.ArrowRight || isMobile && document.getElementById('arrow-right').style.backgroundColor === 'rgb(255, 255, 255)') {
        car.angle += 0.05;
    }

    checkpoint();

    // Détection de la ligne de départ (gauche à droite)
    if (
        car.x > currentStartLine.x &&
        car.x < currentStartLine.x + currentStartLine.width &&
        car.y > currentStartLine.y &&
        car.y < currentStartLine.y + currentStartLine.height &&
        currentCheckpoints.filter(checkpoint => checkpoint.validated).length === currentCheckpoints.length
    ) {
        if (!hasCrossedStart) {
            hasCrossedStart = true;
            laps++; // Incrémentez le tour
            currentCheckpoints.forEach(checkpoint => {
                checkpoint.validated = false;
            });
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

    // Dessiner les checkpoints
    currentCheckpoints.forEach(checkpoint => {
        if (!checkpoint.validated) {
            ctx.beginPath();
            ctx.arc(checkpoint.x, checkpoint.y, 5, 0, Math.PI * 2);
            ctx.fillStyle = 'red';  // Point rouge pour le checkpoint
            ctx.fill();
        }
    });
}

initArrowControl();
