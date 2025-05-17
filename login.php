<?php
//importation de connexion à la base de donnée
include("db.php");

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>login</title>
</head>
<body>
    <h1>Login</h1>
    <form action="login.php" method="post">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required><br><br>
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required><br><br>
        <input type="submit" value="Login">
    </form>

    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Récupération des données du formulaire
        $username = $_POST['username'];
        $password = md5($_POST['password']);

        // Vérification des identifiants saisis pas l'utilisateur
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username AND password = :password");
        $stmt->execute(['username' => $username, 'password' => $password]);
        $user = $stmt->fetch();

        if ($stmt->rowCount() > 0) {
            session_start();
            $_SESSION["login"] = $username;
            header("Location: home.php");
            exit();
        } else {
            $error = "Identifiants incorrects.";
            // Optionnel : afficher l'erreur sur la page
            echo $error;
        }
    }
    ?>

    
</body>
</html>