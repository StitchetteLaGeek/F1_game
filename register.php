<?php
session_start();
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $pseudo = htmlspecialchars($_POST['pseudo']);
    $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
    $mdp = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $ecurie = htmlspecialchars($_POST['ecurie']);

    $host = 'localhost';
    $dbname = 'e2202522';
    $user = 'e2202522';
    $password = 'metcquetuveux';

    try{
        $login = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $password);
        $login->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
    catch (PDOException $e){
        die("<p>Problème de co chef : " . $e->getMessage() . "</p>");
    }
    try{
        $emailexist = $login->prepare("SELECT email FROM users WHERE email = ?");
        $emailexist->execute([$email]);

        if ($emailexist->rowCount() > 0) echo "<p>Erreur : t'as déjà un compte chef</p>";

        else {
            $stmt = $login->prepare("INSERT INTO users (pseudo, email, password, ecurie) VALUES (?, ?, ?, ?)");
            if ($stmt->execute([$pseudo, $email, $mdp, $ecurie])){
                header("Location: connection.html");
                exit;
            }
            else echo "<p>Erreur de merde lors de l'inscription</p>";
        }
    }
    catch (PDOException $e) {
        die("<p>Problème de co : " . $e->getMessage() . "</p>";
    }
}
?>
