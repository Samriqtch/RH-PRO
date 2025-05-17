<?php
// Connexion à la base de donnée RH PRO
    try {
        $pdo = new PDO('mysql:host=localhost;port=3307;dbname=rh_pro', 'samson', '');
    } catch (PDOException $e) {
        echo 'Connexion  chou e : ' . $e->getMessage();
    }
?>