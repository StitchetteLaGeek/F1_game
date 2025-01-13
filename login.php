<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
    $mdp = htmlspecialchars($_POST['password']);

    if (!email){
        echo "<p>Rentre un mail chacal !</p>";
        exit;
    }
    if (empty($mdp)){
        echo "<p> Rentre un mot de passe chef</p>";
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
        die("<p>Problème de co chef : " . $e->getMessage() . "</p>");
    }
    
    try{
        $stmt = $login->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->rowCount() <= 0) echo "<p> Email introuvable chef t pas co </p>";
        else {
            $user_data = $stmt->fetch(PDO::FETCH_ASSOC);
            if (password_verify($mdp, $user_data['password'])){
                header("Location: index.php");
                exit;
            }
            else echo "<p> mot de passse incorrect arrête de hacker !!! </p>";
        }
    }
    catch (PDOException $e){
        echo "<p> Probleme chef : " . $e->getMessage() . "</p>";
    }
}
?>
