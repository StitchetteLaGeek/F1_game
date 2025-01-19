<?php
// Inclure la connexion à la base de données
require_once("connexion_bdd.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Récupérer les données envoyées par le formulaire
    $pseudo = $_POST['pseudo'] ?? null;
    $email = $_POST['email'] ?? null;
    $password = $_POST['password'] ?? null;
    $ecurie = $_POST['ecurie'] ?? null;

    // Validation des données
    if (isset($pseudo) && isset($email) && isset($password)) {
        // Vérifier si l'email existe déjà
        $sql = "SELECT id FROM users WHERE email = ?";
        $stmt = $conn->prepare($sql);

        if ($stmt === false) {
            die('Erreur de préparation de la requête : ' . $conn->error);
        }

        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $errorMsg = "Cet email est déjà utilisé.";
        } else {
            // Hacher le mot de passe
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Requête d'insertion dans la base de données
            $sql_insert = "INSERT INTO users (pseudo, email, password, ecurie) 
                           VALUES (?, ?, ?, ?)";
            $stmt_insert = $conn->prepare($sql_insert);

            if ($stmt_insert === false) {
                die('Erreur de préparation de la requête d\'insertion : ' . $conn->error);
            }

            // Bind des paramètres pour l'insertion
            $stmt_insert->bind_param("ssss", $pseudo, $email, $hashed_password, $ecurie);
            $stmt_insert->execute();

            if ($stmt_insert->affected_rows > 0) {
                // Inscription réussie, afficher un message de succès
                echo "
                <!DOCTYPE html>
                <html lang='fr'>
                <head>
                    <meta charset='UTF-8'>
                    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
                    <title>Inscription réussie</title>
                    <link rel='stylesheet' href='style.css'>
                </head>
                <body>
                    <div class='container'>
                        <h1>Bien inscrit</h1>
                        <p>Vous êtes maintenant inscrit.</p>
                        <a href='index.html'>
                            <button class='btn'>Retour à la page d'acceuiln</button>
                        </a>
                    </div>
                </body>
                </html>";
            } else {
                $errorMsg = "Une erreur est survenue lors de l'inscription. Veuillez réessayer.";
            }
        }

        // Fermer la connexion
        $stmt->close();
        $conn->close();
    } else {
        $errorMsg = "Veuillez remplir tous les champs obligatoires.";
    }
}

if (isset($errorMsg)) {
    echo "
    <!DOCTYPE html>
    <html lang='fr'>
    <head>
        <meta charset='UTF-8'>
        <meta name='viewport' content='width=device-width, initial-scale=1.0'>
        <title>Erreur d'inscription</title>
        <link rel='stylesheet' href='style.css'>
    </head>
    <body>
        <div class='container'>
            <h1>Erreur</h1>
            <p>" . htmlspecialchars($errorMsg) . "</p>
            <a href='inscription.html'>
                <button class='btn'>Retour à la page d'inscription</button>
            </a>
        </div>
    </body>
    </html>";
}
?>
