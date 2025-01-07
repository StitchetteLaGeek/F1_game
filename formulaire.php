<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pseudo = htmlspecialchars($_POST['pseudo']);
    $age = intval($_POST['age']);
    $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $ecurie = htmlspecialchars($_POST['ecurie']);

    if ($email && $pseudo && $age > 0 && $password) {
        echo "<h2>Inscription réussie!</h2>";
        echo "<p>Pseudo: $pseudo</p>";
        echo "<p>Âge: $age</p>";
        echo "<p>Email: $email</p>";
        echo "<p>Écurie F1 favorite: $ecurie</p>";
    } else {
        echo "<h2>Erreur dans les informations fournies.</h2>";
    }
}
?>
