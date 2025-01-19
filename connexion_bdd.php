<?php
// Connexion à la base de données avec WAMP
$host = 'localhost';
$dbname = 'e2202522'; // Remplace par ton nom de base de données
$username = 'root'; // Nom d'utilisateur MySQL, généralement "root" sous WAMP
$password = ''; // Le mot de passe est généralement vide sous WAMP

// Connexion à MySQL via PDO
try {
    $conn = new mysqli($host, $username, $password, $dbname);

    // Vérifier la connexion
    if ($conn->connect_error) {
        die("Connexion échouée : " . $conn->connect_error);
    }
} catch (Exception $e) {
    echo "Erreur : " . $e->getMessage();
    exit;
}
?>
