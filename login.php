ll<?php
session_start();
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $_SESSION['error'] = [];
    $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
    $mdp = htmlspecialchars($_POST['password']);
    $remember = isset($_POST['remember']);

    if (!$email) $_SESSION['error']['email'] = "Veuillez rentrer un adresse mail.";
    
    if (empty($mdp)) $_SESSION['error']['mdp'] = "Veuillez rentrer un mot de passe.";

    if (!empty($_SESSION['error'])){
        header("Location: form_login.php");
        exit;
    }
        
    $host = 'localhost';
    $dbname = 'course_game';
    $user = 'root';
    $password = '';
    try{
        $login = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $password);
        $login->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
    catch (PDOException $e){
        die("<p>Problème de connexion : " . $e->getMessage() . "</p>");
    }
    
    try{
        $stmt = $login->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->rowCount() <= 0){
            $_SESSION['error']['email'] = "Email introuvable.";
            header("Location: form_login.php");
            exit;
        }
        else {
            $user_data = $stmt->fetch(PDO::FETCH_ASSOC);
            if (password_verify($mdp, $user_data['password'])){
                $_SESSION['pseudo'] = $user_data['pseudo'];
                $_SESSION['connexion'] = true;
                if ($remember){
                    setcookie("pseudo", $user_data['pseudo'], time() + (86400 * 30), "/", "", true, true);
                    setcookie("email", $email, time() + (86400 * 30), "/", "", true, true);
                }
                header("Location: index.php");
                exit;
            }
            else {
                $_SESSION['error']['mdp'] = "Mot de passse incorrect.";
                header("Location: form_login.php");
                exit;
            }
        }
    }
    catch (PDOException $e){
        $_SESSION['error']['general'] = "Problème de connnexion : " . $e->getMessage();
        header("Location: form_login.php");
        exit;
    }
}
?>
