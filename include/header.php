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
        .sidebar-custom {
            background: #0d6efd; /* Couleur bleue unie */
            min-height: 100vh;
            box-shadow: 2px 0 8px rgba(0,0,0,0.08);
            padding-top: 30px;
        }
        .sidebar-logo {
            font-weight: bold;
            color: #fff;
            letter-spacing: 2px;
            font-size: 1.7rem;
            margin-bottom: 40px;
            text-align: center;
        }
        .sidebar-nav .nav-link {
            color: #fff;
            font-weight: 500;
            margin-bottom: 10px;
            border-radius: 5px;
            transition: background 0.2s, color 0.2s;
        }
        .sidebar-nav .nav-link.active, .sidebar-nav .nav-link:hover {
            color: #0d6efd;
            background: #fff;
        }
        .sidebar-profile {
            text-align: center;
            margin-top: 40px;
        }
        .profile-pic {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 50%;
            border: 3px solid #fff;
            margin-bottom: 10px;
        }
        .sidebar-logout {
            margin-top: 30px;
        }
        @media (max-width: 991.98px) {
            .sidebar-custom {
                min-height: auto;
                padding-top: 10px;
            }
        }
    </style>
</head>
<body>
<div class="d-flex">
    <nav class="sidebar-custom d-flex flex-column p-3" style="width: 250px;">
        <div class="sidebar-logo">RH-PRO</div>
        <ul class="nav flex-column sidebar-nav">
            <li class="nav-item">
                <a class="nav-link<?= basename($_SERVER['PHP_SELF']) == 'home.php' ? ' active' : '' ?>" href="home.php">Dashboard</a>
            </li>
            <li class="nav-item">
                <a class="nav-link<?= basename($_SERVER['PHP_SELF']) == 'add.php' ? ' active' : '' ?>" href="add.php">Ajouter Employé</a>
            </li>
            <li class="nav-item">
                <a class="nav-link<?= basename($_SERVER['PHP_SELF']) == 'profile.php' ? ' active' : '' ?>" href="profile.php">Mon Profil</a>
            </li>
            <li class="nav-item sidebar-logout">
                <a class="nav-link" href="logout.php">Déconnexion</a>
            </li>
        </ul>
        <div class="sidebar-profile mt-auto">
            <img src="uploads/userpic.png" alt="Profil" class="profile-pic">
            <div class="text-white mt-2" style="font-size:1rem;">
                <?php if (isset($_SESSION['login'])) echo htmlspecialchars($_SESSION['login']); ?>
            </div>
        </div>
    </nav>
    <div class="flex-grow-1">