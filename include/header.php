
<?php
//importation de connexion à la base de donnée
include("db.php");

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($page_title) ? $page_title : "RH-PRO" ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: url('uploads/rh-bg.jpg') no-repeat center center fixed;
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
        /* Ajoute ici d'autres styles globaux */
    </style>
</head>
<body>