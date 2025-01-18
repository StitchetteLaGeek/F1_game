class JoyStick {
    constructor({ radius = 80, innerRadius = 40, x = 100, y = window.innerHeight - 150 }) {
        this.radius = radius;
        this.innerRadius = innerRadius;
        this.x = x;
        this.y = y;

        this.dx = 0;  // Déplacement horizontal
        this.dy = 0;  // Déplacement vertical
        this.isMoving = false; // Indicateur si le joystick est en mouvement

        this.createJoystick();
        this.bindEvents();
    }

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

    bindEvents() {
        const onMove = (event) => {
            const touch = event.touches ? event.touches[0] : event;
            const rect = this.container.getBoundingClientRect();
            const centerX = rect.left + this.radius;
            const centerY = rect.top + this.radius;

            const dx = touch.clientX - centerX;
            const dy = touch.clientY - centerY;
            const distance = Math.sqrt(dx * dx + dy * dy);

            if (distance > this.radius) return; // Ignore si on dépasse le rayon du joystick

            // Mettre à jour les déplacements horizontaux et verticaux
            this.dx = dx;
            this.dy = dy;
            this.isMoving = true;

            // Mise à jour de la position du joystick (contrôle interne)
            this.control.style.left = `${dx + this.radius - this.innerRadius}px`;
            this.control.style.top = `${dy + this.radius - this.innerRadius}px`;
        };

        const onEnd = () => {
            this.control.style.left = `${this.radius - this.innerRadius}px`;
            this.control.style.top = `${this.radius - this.innerRadius}px`;

            // Réinitialiser la position du joystick quand l'utilisateur arrête de toucher
            this.isMoving = false;
            this.dx = 0;
            this.dy = 0;
        };

        // Lier les événements pour commencer et arrêter le mouvement
        this.container.addEventListener('touchmove', onMove);
        this.container.addEventListener('touchstart', onMove);
        this.container.addEventListener('touchend', onEnd);
        this.container.addEventListener('mousemove', onMove);
        this.container.addEventListener('mousedown', onMove);
        this.container.addEventListener('mouseup', onEnd);
    }

    getMovement() {
        // Retourne les déplacements horizontaux et verticaux
        return { dx: this.dx, dy: this.dy, isMoving: this.isMoving };
    }
}
