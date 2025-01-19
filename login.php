ll<?php
session_start();
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
    $mdp = htmlspecialchars($_POST['password']);
    $remember = isset($_POST['remember']);

    if (!$email){
        $error['email'] = "Veuillez rentrer un adresse mail.";
    }
    if (empty($mdp)){
        $error['mdp'] = "Veuillez rentrer un mot de passe.";
    }
        
    $host = 'localhost';
    $dbname = 'e2202522';
    $user = 'e2202522';
    $password = 'metcquetuveux';
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
            $error['email'] = "Email introuvable.";
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
                $error['mdp'] = "Mot de passse incorrect.";
            }
        }
    }
    catch (PDOException $e){
        $error['general'] = "Problème de connnexion : " . $e->getMessage();
    }
}
?>
