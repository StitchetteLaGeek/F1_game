<?php
session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['id'])) {
    header('Location: connexion.php');
    exit();
}

// Afficher les informations de l'utilisateur
echo "Bienvenue, " . $_SESSION['email'] . "!";
?>
