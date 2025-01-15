let speed = 0; // Vitesse actuelle
const maxSpeed = 200; // Vitesse maximale
const minAngle = -140; // Angle minimum
const maxAngle = 30; // Angle maximum
const acceleration = 5; // Vitesse d'accélération
const deceleration = 2; // Vitesse de décélération
let accelerating = false; // Indique si l'accélération est active

// Démarrage du jeu
document.getElementById("start-game").addEventListener("click", () => {
    document.getElementById("game-screen").style.display = "block";
    document.getElementById("speedometer").style.display = "block";
    document.getElementById("joystick-container").style.display = "block";

    // Mise à jour de la vitesse et du speedomètre
    setInterval(() => {
        if (accelerating) {
            speed = Math.min(speed + acceleration, maxSpeed); // Limite à la vitesse maximale
        } else {
            speed = Math.max(speed - deceleration, 0); // Décélération jusqu'à 0
        }
        updateSpeedometer();
    }, 100);
});

// Met à jour l'aiguille du speedomètre
function updateSpeedometer() {
    const angle = minAngle + (speed / maxSpeed) * (maxAngle - minAngle);
    const indicator = document.getElementById("needle");
    indicator.style.transform = `translateX(-50%) rotate(${angle}deg)`; // Mise à jour de l'angle de l'aiguille
}

// Gestion des touches pour accélérer/décélérer
document.addEventListener("keydown", (event) => {
    if (event.key === "ArrowUp") {
        accelerating = true;
    }
});

document.addEventListener("keyup", (event) => {
    if (event.key === "ArrowUp") {
        accelerating = false;
    }
});
