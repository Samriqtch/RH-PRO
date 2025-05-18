<?php
include("db.php");
include("include/header.php");
session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['login']) || !isset($_SESSION['entreprise_id'])) {
    header("Location: login.php");
    exit();
}

// Vérifier si l'ID de l'employé est passé en paramètre
if (!isset($_GET['id'])) {
    header("Location: home.php");
    exit();
}

$employe_id = $_GET['id'];
$entreprise_id = $_SESSION['entreprise_id'];

// Supprimer l'employé uniquement s'il appartient à l'entreprise de l'utilisateur connecté
$sql = "DELETE FROM employes WHERE id = :id AND entreprise_id = :entreprise_id";
$stmt = $pdo->prepare($sql);
$stmt->execute(['id' => $employe_id, 'entreprise_id' => $entreprise_id]);

header("Location: home.php");
exit();
?>