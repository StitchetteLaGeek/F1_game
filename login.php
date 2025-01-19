ll<?php
session_start();
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
    $mdp = htmlspecialchars($_POST['password']);

    if (!$email){
        echo "<p>Veuillez rentrer un adresse mail.</p>";
        exit;
    }
    if (empty($mdp)){
        echo "<p>Veuillez rentrer un mot de passe.</p>";
        exit;
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
            echo "<p> Email introuvable.</p>";
            exit;
        }
        else {
            $user_data = $stmt->fetch(PDO::FETCH_ASSOC);
            if (password_verify($mdp, $user_data['password'])){
                $_SESSION['pseudo'] = $pseudo;
                $_SESSION['connexion'] = true;
                setcookie("pseudo", $pseudo, time() + (86400 * 30), "/");
                setcookie("email", $email, time() + (86400 * 30), "/");
                header("Location: index.php");
                exit;
            }
            else {
                echo "<p> Mot de passse incorrect.</p>";
                exit;
            }
        }
    }
    catch (PDOException $e){
        echo "<p> Problème de connnexion : " . $e->getMessage() . "</p>";
        exit;
    }
}
?>
