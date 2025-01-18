<?php
session_start();
if (!isset($_SESSION['pseudo'])){
    $_SESSION['connected'] = false;
    echo "<p id = 'connexion'><a href= 'login.html' > Connexion </a> / <a href= 'register.html'> Inscription </a></p>";
}
else {
    $_SESSION['connected'] = true;
    echo "<p id = 'connexion'> " . $_SESSION['pseudo'] . " <a href = 'logout.php'> Déconnexion </a></p> ";
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mini-Jeu de Course</title>
    <link rel="stylesheet" href="styles.css">
    <script src="script.js" defer></script>
</head>
<body>
    <h1>🏁 Course F1 Simulator 🏁</h1>

    <!-- Choix de circuit (Menu déroulant) -->
    <div id="circuit-selection">
        <h2>Choisissez votre circuit</h2>
        <select id="circuit-select" onchange="updateTrackImage()">
            <option value="monaco">Circuit Monaco</option>
            <option value="paul-ricard">Circuit Hockenheim</option>
            <option value="shanghai">Circuit Shanghai</option>
            <option value="nuerburgring">Circuit Nuerburgring</option>
        </select>
    </div>

    <!-- Sélection de la voiture et début du jeu -->
    <div id="vehicle-selection">
        <h2>Choisissez votre voiture</h2>
        <img id="vehicle-image" src="images/f1-ferrari.png" alt="Voiture sélectionnée">
        <div>
            <button id="prev">⬅️ Précédent</button>
            <button id="next">Suivant ➡️</button>
        </div>
        <button id="start-game">🏎️ Commencer la course</button>
    </div>

    <div id="game-screen">
        <canvas id="game-canvas" width="800" height="600"></canvas>
        <div id="dashboard">
            <div>
                ⏱️ <strong>Temps :</strong> <span id="timer">0:00</span>
            </div>
            <div>
                🏁 <strong>Tours :</strong> <span id="lap-count">0 / 3</span>
            </div>
        </div>
    </div>

    <!-- <a href="login.html">Se connecter / S'inscrire</a> -->
</body>
</html>
