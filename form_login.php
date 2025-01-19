<?php
session_start();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div id="connexion">
        <h2>Connexion</h2>
        <form action="login.php" method="POST">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
            <?php
                if (!empty($error['email'])) echo "<p id = 'error'>" . $error['email'] . "</p>";
            ?>
            <br>
        
            <label for="password">Mot de passe:</label>
            <input type="password" id="password" name="password" required>
            <?php
                if (!empty($error['mdp'])) echo "<p id = 'error'>" . $error['mdp'] . "</p>";
            ?>
            <br>
            <label> 
                <input type="checkbox" name="remember"> Se souvenir de moi
            </label><br>
            <button type="submit">Se connecter</button>
        </form>

        <?php
            if (!empty($error['general'])) echo "<p id = 'error'>" . $error['general'] . "</p>";
        ?>

        <p>Pas encore inscrit ? <a href="register.html">S'inscrire ici</a></p>
        <p>Retour à <a href="index.html">la page d'accueil</a></p>
    </div>
</body>
</html>
