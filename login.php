<?php
//importation de connexion à la base de donnée
include("db.php");
session_start();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion RH-PRO</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: #f3f6fa;
            min-height: 100vh;
        }
        .login-main {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-card {
            background: #fff;
            border-radius: 24px;
            box-shadow: 0 8px 32px rgba(1,33,82,0.10), 0 1.5px 6px rgba(1,33,82,0.08);
            overflow: hidden;
            max-width: 900px;
            width: 100%;
            display: flex;
            flex-direction: row;
        }
        .login-form-side {
            flex: 1 1 0;
            padding: 48px 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        .login-img-side {
            flex: 1 1 0;
            background: #eaf1fb;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-img-side img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            min-height: 350px;
        }
        .login-title {
            font-size: 2rem;
            font-weight: bold;
            color: #012152;
            margin-bottom: 32px;
            text-align: left;
        }
        .form-label {
            color: #012152;
            font-weight: 500;
        }
        .btn-login {
            background: #0d6efd;
            color: #fff;
            font-weight: bold;
            border-radius: 8px;
            padding: 10px 0;
            font-size: 1.1rem;
            margin-top: 10px;
        }
        .btn-login:hover {
            background: #012152;
            color: #fff;
        }
        .login-footer {
            text-align: center;
            margin-top: 18px;
            color: #888;
        }
        @media (max-width: 900px) {
            .login-card {
                flex-direction: column;
                max-width: 95vw;
            }
            .login-img-side {
                min-height: 180px;
                max-height: 220px;
            }
        }
    </style>
</head>
<body>
<div class="login-main">
    <div class="login-card">
        <div class="login-form-side">
            <div class="login-title">Connexion RH-PRO</div>
            <form action="login.php" method="post">
                <div class="mb-3">
                    <label for="username" class="form-label">Nom d'utilisateur</label>
                    <input type="text" id="username" name="username" class="form-control form-control-lg" required autofocus>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Mot de passe</label>
                    <input type="password" id="password" name="password" class="form-control form-control-lg" required>
                </div>
                <button type="submit" class="btn btn-login w-100">Se connecter</button>
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
            <div class="login-footer">
                <span>Vous n'avez pas de compte ? <a href="#" class="text-primary">Contactez l'administrateur</a></span>
            </div>
        </div>
        <div class="login-img-side">
            <img src="uploads/image1.jpg" alt="RH-PRO Illustration">
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>