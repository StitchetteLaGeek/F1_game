let speed = 0; // Vitesse actuelle de la voiture
const maxSpeed = 200; // Vitesse maximale
const acceleration = 5; // Accélération de la voiture
const deceleration = 2; // Décélération de la voiture
let accelerating = false; // Indicateur si l'accélération est active

// Créer le joystick et gérer ses événements
class JoyStick {
    constructor({ radius = 80, innerRadius = 40, x = 100, y = window.innerHeight - 150 }) {
        this.radius = radius;
        this.innerRadius = innerRadius;
        this.x = x;
        this.y = y;

        this.up = false;
        this.down = false;
        this.left = false;
        this.right = false;

        this.createJoystick();
        this.bindEvents();
    }

    // Créer le conteneur et le contrôle du joystick
    createJoystick() {
        this.container = document.createElement('div');
        this.container.style.position = 'fixed';
        this.container.style.bottom = '10%';
        this.container.style.left = '10%';
        this.container.style.width = `${this.radius * 2}px`;
        this.container.style.height = `${this.radius * 2}px`;
        this.container.style.borderRadius = '50%';
        this.container.style.backgroundColor = 'rgba(200, 200, 200, 0.2)';
        this.container.style.border = '1px solid rgba(200, 200, 200, 0.5)';
        this.container.style.touchAction = 'none';
        document.body.appendChild(this.container);

        this.control = document.createElement('div');
        this.control.style.position = 'absolute';
        this.control.style.top = `${this.radius - this.innerRadius}px`;
        this.control.style.left = `${this.radius - this.innerRadius}px`;
        this.control.style.width = `${this.innerRadius * 2}px`;
        this.control.style.height = `${this.innerRadius * 2}px`;
        this.control.style.borderRadius = '50%';
        this.control.style.backgroundColor = 'rgba(200, 200, 200, 0.5)';
        this.control.style.border = '1px solid rgba(200, 200, 200, 0.8)';
        this.container.appendChild(this.control);
    }

    // Gérer les événements du joystick
    bindEvents() {
        const onMove = (event) => {
            const touch = event.touches ? event.touches[0] : event;
            const rect = this.container.getBoundingClientRect();
            const centerX = rect.left + this.radius;
            const centerY = rect.top + this.radius;

            const dx = touch.clientX - centerX;
            const dy = touch.clientY - centerY;
            const distance = Math.sqrt(dx * dx + dy * dy);

            if (distance > this.radius) return; // Si la distance dépasse le rayon, on arrête de bouger

            // Déplacer le contrôle interne
            this.control.style.left = `${dx + this.radius - this.innerRadius}px`;
            this.control.style.top = `${dy + this.radius - this.innerRadius}px`;

            // Contrôler l'accélération et la décélération selon le mouvement
            if (dy < -10) { // Si le joystick est vers le haut, accélérer
                accelerating = true;
            } else if (dy > 10) { // Si le joystick est vers le bas, décélérer
                accelerating = false;
            }
        };

        const onEnd = () => {
            this.control.style.left = `${this.radius - this.innerRadius}px`;
            this.control.style.top = `${this.radius - this.innerRadius}px`;
            accelerating = false; // Arrêter l'accélération ou la décélération quand le joystick est relâché
        };

        // Lier les événements de touch et mouse pour le mouvement
        this.container.addEventListener('touchmove', onMove);
        this.container.addEventListener('touchstart', onMove);
        this.container.addEventListener('touchend', onEnd);
        this.container.addEventListener('mousemove', onMove);
        this.container.addEventListener('mousedown', onMove);
        this.container.addEventListener('mouseup', onEnd);
    }
}

// Afficher le joystick quand le jeu commence
document.getElementById("start-game").addEventListener("click", function () {
    document.getElementById("joystick-container").style.display = "block"; // Afficher le joystick

    // Créer le joystick
    const joystick = new JoyStick({
        radius: 80,
        innerRadius: 40,
        x: 100,
        y: window.innerHeight - 150
    });

    // Logique de mise à jour de la vitesse et du contrôle de la voiture
    setInterval(() => {
        if (accelerating) {
            speed = Math.min(speed + acceleration, maxSpeed); // Limiter la vitesse maximale
        } else {
            speed = Math.max(speed - deceleration, 0); // Décélérer jusqu'à 0
        }

        // Affichage de la vitesse dans la console (ou mise à jour de l'UI)
        console.log(`Vitesse actuelle: ${speed}`);
    }, 100);
});
