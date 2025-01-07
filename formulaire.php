<?php
$host = 'localhost';
$dbname = 'e2202522';
$user = 'e2202522';
$password = 'TON_MOT_DE_PASSE';

try {
    // Connexion à la base de données
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Vérification de la méthode POST
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $pseudo = htmlspecialchars($_POST['pseudo']);
        $age = intval($_POST['age']);
        $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $ecurie = htmlspecialchars($_POST['ecurie']);

        // Validation des données
        if ($email && $pseudo && $age > 0 && $password) {
            // Insertion dans la base de données
            $stmt = $pdo->prepare("INSERT INTO users (pseudo, age, email, password, ecurie) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$pseudo, $age, $email, $password, $ecurie]);

            echo "<h2>Inscription réussie!</h2>";
            echo "<p>Pseudo: $pseudo</p>";
            echo "<p>Âge: $age</p>";
            echo "<p>Email: $email</p>";
            echo "<p>Écurie F1 favorite: $ecurie</p>";
        } else {
            echo "<h2>Erreur: Données invalides.</h2>";
        }
    }
} catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
}
?>
