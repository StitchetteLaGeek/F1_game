<?php
// Inclure la connexion à la base de données
require_once("connexion_bdd.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Récupérer les données envoyées par le formulaire
    $email = $_POST['email'] ?? null;
    $password = $_POST['password'] ?? null;

    if (isset($email) && isset($password)) {
        // Préparer la requête pour récupérer l'utilisateur par email
        $sql = "SELECT id, password FROM users WHERE email = ?";
        $stmt = $conn->prepare($sql);

        if ($stmt === false) {
            die('Erreur de préparation de la requête : ' . $conn->error);
        }

        // Lier l'email et exécuter la requête
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        // Vérifier si l'utilisateur existe
        if ($stmt->num_rows > 0) {
            $stmt->bind_result($id, $hashed_password);
            $stmt->fetch();

            // Vérifier le mot de passe
            if (password_verify($password, $hashed_password)) {
                // Créer une session et stocker les informations de l'utilisateur
                session_start();
                $_SESSION['id'] = $id;
                $_SESSION['email'] = $email;

                // Afficher un message de succès
                echo "
                <!DOCTYPE html>
                <html lang='fr'>
                <head>
                    <meta charset='UTF-8'>
                    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
                    <title>Connexion réussie</title>
                    <link rel='stylesheet' href='style.css'>
                </head>
                <body>
                    <div class='container'>
                        <h1>Bien connecté</h1>
                        <p>Vous êtes maintenant connecté.</p>
                        <a href='index.html'>
                            <button class='btn'>Retour à la page d'accueil</button>
                        </a>
                    </div>
                </body>
                </html>";
            } else {
                echo "
                <!DOCTYPE html>
                <html lang='fr'>
                <head>
                    <meta charset='UTF-8'>
                    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
                    <title>Erreur de connexion</title>
                    <link rel='stylesheet' href='style.css'>
                </head>
                <body>
                    <div class='container'>
                        <h1>Erreur</h1>
                        <p>Mot de passe incorrect.</p>
                        <a href='connexion.html'>
                            <button class='btn'>Retour à la page de connexion</button>
                        </a>
                    </div>
                </body>
                </html>";
            }
        } else {
            echo "
            <!DOCTYPE html>
            <html lang='fr'>
            <head>
                <meta charset='UTF-8'>
                <meta name='viewport' content='width=device-width, initial-scale=1.0'>
                <title>Erreur de connexion</title>
                <link rel='stylesheet' href='style.css'>
            </head>
            <body>
                <div class='container'>
                    <h1>Erreur</h1>
                    <p>Aucun utilisateur trouvé avec cet email.</p>
                    <a href='connexion.html'>
                        <button class='btn'>Retour à la page de connexion</button>
                    </a>
                </div>
            </body>
            </html>";
        }

        // Fermer la connexion
        $stmt->close();
    } else {
        echo "
        <!DOCTYPE html>
        <html lang='fr'>
        <head>
            <meta charset='UTF-8'>
            <meta name='viewport' content='width=device-width, initial-scale=1.0'>
            <title>Erreur de connexion</title>
            <link rel='stylesheet' href='style.css'>
        </head>
        <body>
            <div class='container'>
                <h1>Erreur</h1>
                <p>Veuillez remplir tous les champs.</p>
                <a href='connexion.html'>
                    <button class='btn'>Retour à la page de connexion</button>
                </a>
            </div>
        </body>
        </html>";
    }
}

$conn->close();
?>
