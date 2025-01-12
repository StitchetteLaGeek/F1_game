
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
<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $pseudo = htmlspecialchars($_POST['pseudo']);
    $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
    $mdp = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $ecurie = htmlspecialchars($_POST['ecurie']);

    $host = 'localhost';
    $dbname = 'e2202522';
    $user = 'e2202522';
    $password = 'metcquetuveux';
    
    $login = new mysqli($host,$user,$password,$dbname);
    if ($login->connect_error) die ("<p>Problème de co chef : " . $login->connect_error . "</p>");

    if ($email && $pseudo && !empty($_POST(['password'])){
        $emailexist = $login->prepare("SELECT email FROM users WHERE email = ?");
        $emailexist->bind_param("s",$email);
        $emailexist->execute();
        $emailexist->store_result();

        if ($emailexist->num_rows > 0) echo "<p>Erreur : rentre un bon email chef !</p>";

        else {
            $stmt = $login->prepare("INSERT INTO users (pseudo, email, password, ecurie) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $pseudo, $email, $mdp, $ecurie);
            if ($stmt->execute()){
                header("Location: connection.html");
                exit;
            }
            else echo "<p>Erreur de merde : " . $stmt->error . "</p>";
            $stmt->close();
        }
        $emailexist->close();
    }
    $login->close();
}
?>
