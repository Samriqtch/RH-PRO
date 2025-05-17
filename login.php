<?php
//importation de connexion à la base de donnée
include("db.php");
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            /* Utilisation de l'image backgroundrh.jpg en fond */
            background: url('uploads/backgroundrh.jpg') no-repeat center center fixed;
            background-size: cover;
            min-height: 100vh;
        }
        .login-container {
            background: rgba(255,255,255,0.95);
            border-radius: 15px;
            box-shadow: 0 0 20px rgba(0,0,0,0.2);
            max-width: 400px;
            margin: 80px auto;
            padding: 40px 30px 30px 30px;
        }
        .userpic {
            width: 90px;
            height: 90px;
            object-fit: cover;
            border-radius: 50%;
            display: block;
            margin: 0 auto 20px auto;
            border: 3px solid #0d6efd;
            background: #fff;
        }
        .login-title {
            text-align: center;
            margin-bottom: 25px;
            font-weight: bold;
            color: #0d6efd;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <img src="uploads/userpic.png" alt="User Icon" class="userpic">
        <h2 class="login-title">Connexion RH-PRO</h2>
        <form action="login.php" method="post">
            <div class="mb-3">
                <label for="username" class="form-label">Nom d'utilisateur</label>
                <input type="text" id="username" name="username" class="form-control" required autofocus>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Mot de passe</label>
                <input type="password" id="password" name="password" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary w-100">Se connecter</button>
        </form>
        <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $username = $_POST['username'];
            $password = md5($_POST['password']);

            $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username AND password = :password");
            $stmt->execute(['username' => $username, 'password' => $password]);
            $user = $stmt->fetch();

            if ($user) {
                $_SESSION["login"] = $username;
                $_SESSION["entreprise_id"] = $user["entreprise_id"];
                header("Location: home.php");
                exit();
            } else {
                echo '<div class="alert alert-danger mt-3 text-center">Identifiants incorrects. Veuillez réessayer.</div>';
            }
        }
        ?>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>