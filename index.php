<?php
session_start();
if (isset($_COOKIE['pseudo']) && isset($_COOKIE['email'])){
    $_SESSION['pseudo'] = $_COOKIE['pseudo'];
    $_SESSION['email'] = $_COOKIE['email'];
}
if (!isset($_SESSION['pseudo'])){
    $_SESSION['connected'] = false;
    $bouton1 = ['login.php', 'connexion'];
    $bouton2 = ['register.php', 'inscription'];
}
else {
    $_SESSION['connected'] = true;
    $bouton1 = ['index.php', $_SESSION['pseudo']];
    $bouton2 = ['logout.php', 'déconnexion'];
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mini-Jeu de Course</title>
    <!-- styles -->
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="style_accessoire.css">
    <link rel="stylesheet" href="page.css">

    <script src="script.js" defer></script>
   
</head>
<body>
    <!-- Menu de navigation -->
    <nav>
        <ul>
            <li><a href="index.php">Accueil</a></li>
            <li><a href=<?php echo $bouton1[0]; ?>><?php echo $bouton1[1]; ?></a></li>
            <li><a href=<?php echo $bouton2[0]; ?>><?php echo $bouton2[1]; ?></a></li>
            <li><a href="profil.php">Profil</a></li>
        </ul>
    </nav>

    <h1>🏁 Course F1 Simulator 🏁</h1>

    <!-- Choix de circuit (Menu déroulant) -->
    <div id="circuit-selection">
        <h2>Choisissez votre circuit</h2>
        <select id="circuit-select" onchange="updateTrackImage()">
            <option value="monaco">Circuit Monaco</option>
            <option value="Hockenheim">Circuit Hockenheim</option>
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

    <!-- Écran de jeu -->
    <div id="game-screen" style="display: none;">
        <canvas id="game-canvas" width="800" height="600"></canvas>
        <div id="dashboard">
            <div>
                ⏱️ <strong>Temps :</strong> <span id="timer">0:00</span>
            </div>
                <!-- Flèches directionnelles pour téléphone -->
    <div id="arrow-buttons" class="arrow-buttons">
        <div id="arrow-up" class="arrow-button arrow-up">↑</div>
        <div id="arrow-left" class="arrow-button arrow-left">←</div>
        <div id="arrow-right" class="arrow-button arrow-right">→</div>
        <div id="arrow-down" class="arrow-button arrow-down">↓</div>
    </div>
            <div>
                🏁 <strong>Tours :</strong> <span id="lap-count">0 / 3</span>
            </div>
        </div>
    </div>



</body>
</html>
