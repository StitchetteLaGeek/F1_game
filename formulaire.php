<?php
// Inclure le fichier de connexion à la base de données
require_once("BDD/connexion_bdd.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Récupérer les données envoyées par le formulaire
    $pseudo = $_POST['pseudo'] ?? null;
    $email = $_POST['email'] ?? null;
    $password = $_POST['password'] ?? null;
    $ecurie = $_POST['ecurie'] ?? null;

    // Validation des données
    if (isset($pseudo) && isset($email) && isset($password)) {
        // Préparer la requête d'insertion dans la base de données
        $sql = "INSERT INTO users (pseudo, email, password, ecurie) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);

        if ($stmt === false) {
            die('Erreur de préparation de la requête : ' . $conn->error);
        }

        // Hacher le mot de passe avant de l'enregistrer
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Lier les paramètres
        $stmt->bind_param("ssss", $pseudo, $email, $hashed_password, $ecurie);

        // Exécuter la requête
        if ($stmt->execute()) {
            // Redirection vers la page de connexion après l'inscription
            header('Location: connexion.php');
            exit();
        } else {
            $errorMsg = "Erreur lors de l'inscription, veuillez réessayer.";
        }

        // Fermer la connexion
        $stmt->close();
    } else {
        $errorMsg = "Veuillez remplir tous les champs.";
    }
}

$conn->close();
?>
