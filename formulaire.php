<?php
$host = 'localhost';
$dbname = 'e2202522';
$user = 'e2202522';
$password = 'TON_MOT_DE_PASSE';
 // formulaire 
try {
    // Connexion à la base de données
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Vérification de la méthode POST
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Récupération et validation des données du formulaire
        $pseudo = filter_input(INPUT_POST, 'pseudo', FILTER_SANITIZE_STRING);
        $age = filter_input(INPUT_POST, 'age', FILTER_VALIDATE_INT);
        $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
        $password = $_POST['password'];
        $ecurie = filter_input(INPUT_POST, 'ecurie', FILTER_SANITIZE_STRING);

        // Validation des données
        if (!$pseudo || strlen($pseudo) < 3) {
            echo "<h2>Erreur: Le pseudo doit contenir au moins 3 caractères.</h2>";
        } elseif (!$age || $age <= 0) {
            echo "<h2>Erreur: L'âge doit être un nombre positif.</h2>";
        } elseif (!$email) {
            echo "<h2>Erreur: L'email est invalide.</h2>";
        } elseif (strlen($password) < 6) {
            echo "<h2>Erreur: Le mot de passe doit contenir au moins 6 caractères.</h2>";
        } elseif (!$ecurie) {
            echo "<h2>Erreur: L'écurie doit être spécifiée.</h2>";
        } else {
            // Hash du mot de passe
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            // Insertion dans la base de données
            $stmt = $pdo->prepare("INSERT INTO users (pseudo, age, email, password, ecurie) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$pseudo, $age, $email, $hashedPassword, $ecurie]);

            echo "<h2>Inscription réussie!</h2>";
            echo "<p>Pseudo: $pseudo</p>";
            echo "<p>Âge: $age</p>";
            echo "<p>Email: $email</p>";
            echo "<p>Écurie F1 favorite: $ecurie</p>";
        }
    }
} catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
}
?>

<!-- Formulaire HTML -->
<form action="" method="POST">
    <label for="pseudo">Pseudo:</label>
    <input type="text" name="pseudo" id="pseudo" required><br><br>

    <label for="age">Âge:</label>
    <input type="number" name="age" id="age" required min="1"><br><br>

    <label for="email">Email:</label>
    <input type="email" name="email" id="email" required><br><br>

    <label for="password">Mot de passe:</label>
    <input type="password" name="password" id="password" required><br><br>

    <label for="ecurie">Écurie F1 favorite:</label>
    <input type="text" name="ecurie" id="ecurie" required><br><br>

    <input type="submit" value="S'inscrire">
</form>
