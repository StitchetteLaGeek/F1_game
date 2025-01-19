<?php
session_start();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div id="inscription">
        <h2>Inscription</h2>
        <form action="register.php" method="POST">
            <label for="pseudo">Pseudo:</label>
            <input type="text" id="pseudo" name="pseudo" required>
            <?php
                if (isset($_SESSION['error']['pseudo'])) echo "<p id = 'error'>" . htmlspecialchars($_SESSION['error']['pseudo']) . "</p>";
            ?>
            <br>
        
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
            <?php
                if (isset($_SESSION['error']['email'])) echo "<p id = 'error'>" . htmlspecialchars($_SESSION['error']['email']) . "</p>";
            ?>
            <br>
        
            <label for="password">Mot de passe:</label>
            <input type="password" id="password" name="password" required>
            <?php
                if (isset($_SESSION['error']['mdp'])) echo "<p id = 'error'>" . htmlspecialchars($_SESSION['error']['mdp']) . "</p>";
            ?>
            <br>
        
            <label for="ecurie">Écurie F1 favorite:</label>
            <input type="text" id="ecurie" name="ecurie"><br>
        
            <button type="submit">S'inscrire</button>
        </form>
        
        <?php
            if (isset($_SESSION['error']['general'])) echo "<p id = 'error'>" . htmlspecialchars($_SESSION['error']['general']) . "</p>";
            unset($_SESSION['error']);
        ?>

        <p>Déjà inscrit ? <a href="connexion.html">Se connecter ici</a></p>
        <p>Retour à <a href="index.html">la page d'accueil</a></p>
    </div>
</body>
</html>
