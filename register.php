<?php
session_start();
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $pseudo = htmlspecialchars($_POST['pseudo']);
    $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
    $mdp = $_POST['password'];
    $ecurie = htmlspecialchars($_POST['ecurie']);

    $_SESSION['error'] = [];

    $host = 'localhost';
    $dbname = 'course_game';
    $user = 'root';
    $password = '';

    if (empty($pseudo)) $_SESSION['error']['pseudo'] = "Veuillez renseigner un pseudo.";
    if (!$email) $_SESSION['error']['email'] = "Veuillez fournir une adresse email valide.";
    if (empty($mdp)) $_SESSION['error']['mdp'] = "Veuillez renseigner un mot de passe.";

    if (!empty($_SESSION['error'])){
        header("Location: form_register.php");
        exit;
    }
    $mdp = password_hash($mdp, PASSWORD_DEFAULT);
    try{
        $login = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $password);
        $login->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
    catch (PDOException $e){
        $_SESSION['error']['general'] = "Erreur lors de l'inscription.";
        header("Location: form_register.php");
        exit;
    }
    try{
        $emailexist = $login->prepare("SELECT email FROM users WHERE email = ?");
        $emailexist->execute([$email]);

        if ($emailexist->rowCount() > 0){
            $_SESSION['error']['email'] = "Erreur : cet Email est déjà associé à un compte.";
            header("Location: form_register.php");
            exit;
        }

        else {
            $stmt = $login->prepare("INSERT INTO users (pseudo, email, password, ecurie) VALUES (?, ?, ?, ?)");
            if ($stmt->execute([$pseudo, $email, $mdp, $ecurie])){
                header("Location: form_login.php");
                exit;
            }
            else {
                $_SESSION['error']['general'] = "Erreur lors de l'inscription";
                header("Location: form_register.php");
                exit;
            }
        }
    }
    catch (PDOException $e) {
        $_SESSION['error']['general'] = "Erreur lors de l'inscription.";
        header("Location: form_register.php");
        exit;
    }
}
?>
