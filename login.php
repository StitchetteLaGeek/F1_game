<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $mdp = $_POST['password'];

    $host = 'localhost';
    $dbname = 'e2202522';
    $user = 'e2202522';
    $password = 'metcquetuveux';
    
    $login = new mysqli($_serveur,$utilisateur,$modepasse,$base);
    if ($login->connect_error) die ("Problème de co chef : " . $login->connect_error);

    if ($email && $password){
        $stmt = $login->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $resultat = $stmt->get_result();
        if ($resultat->num_rows <= 0) echo "<p> Email introuvable chef t pas co </p>";
        else {
            if ($resultat['password'] == $mdp){
                header("Location : index.php");
                exit;
            }
            else echo "<p> mot de passse incorrect arrête de hacker !!! </p>"
        }
}
?>