<?php
session_start();
include("db.php");
include("include/header.php");
include("include/navbar.php");

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

<div class="container d-flex align-items-center justify-content-center" style="min-height: 80vh;">
    <div class="card shadow-lg border-0 rounded-4 p-4" style="max-width: 420px; width:100%;">
        <div class="d-flex flex-column align-items-center">
            <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center mb-3" style="width:90px;height:90px;">
                <i class="bi bi-person-circle text-white" style="font-size:3rem;"></i>
            </div>
            <h4 class="fw-bold mb-1 text-primary"><?= htmlspecialchars($user['username']) ?></h4>
            <p class="mb-2 text-secondary" style="font-size:1.1rem;">
                <i class="bi bi-building"></i>
                <?= htmlspecialchars($user['nom_entreprise']) ?>
            </p>
        </div>
        <hr>
        <div class="text-center">
            <a href="logout.php" class="btn btn-outline-danger px-4 mt-2">
                <i class="bi bi-box-arrow-right"></i> Déconnexion
            </a>
        </div>
    </div>
</div>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<?php include("include/footer.php"); ?>