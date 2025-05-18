<?php
include("db.php");
include("include/header.php");
session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['login']) || !isset($_SESSION['entreprise_id'])) {
    header("Location: login.php");
    exit();
}

// Récupérer les infos de l'utilisateur connecté
$username = $_SESSION['login'];
$entreprise_id = $_SESSION['entreprise_id'];

// Récupérer le nom de l'utilisateur et le nom de l'entreprise
$sql = "SELECT u.username, en.nom AS nom_entreprise
        FROM users u
        JOIN entreprises en ON u.entreprise_id = en.id
        WHERE u.username = :username AND u.entreprise_id = :entreprise_id";
$stmt = $pdo->prepare($sql);
$stmt->execute(['username' => $username, 'entreprise_id' => $entreprise_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    echo "<div class='container mt-5'><div class='alert alert-danger'>Utilisateur introuvable.</div></div>";
    include("include/footer.php");
    exit();
}
?>

<div class="container mt-5">
    <h2 class="mb-4 text-center">Mon Profil</h2>
    <div class="card mx-auto" style="max-width: 400px;">
        <div class="card-body">
            <h5 class="card-title mb-3"><?= htmlspecialchars($user['username']) ?></h5>
            <p class="card-text"><strong>Entreprise :</strong> <?= htmlspecialchars($user['nom_entreprise']) ?></p>
        </div>
    </div>
</div>

<?php include("include/footer.php"); ?>