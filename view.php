<?php
include("db.php");
session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['login']) || !isset($_SESSION['entreprise_id'])) {
    header("Location: login.php");
    exit();
}

// Vérifier si l'ID de l'employé est passé en paramètre
if (!isset($_GET['id'])) {
    echo "Aucun employé sélectionné.";
    exit();
}

$employe_id = (int)$_GET['id'];
$entreprise_id = $_SESSION['entreprise_id'];

// Récupérer les infos de l'employé (et le nom de l'entreprise)
$sql = "SELECT e.*, en.nom AS nom_entreprise
        FROM employes e
        JOIN entreprises en ON e.entreprise_id = en.id
        WHERE e.id = :id AND e.entreprise_id = :entreprise_id";
$stmt = $pdo->prepare($sql);
$stmt->execute(['id' => $employe_id, 'entreprise_id' => $entreprise_id]);
$row = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$row) {
    echo "Employé introuvable ou accès non autorisé.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détails de l'employé</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center mb-4">Détails de l'employé</h2>
        <div class="card mx-auto" style="width: 28rem;">
            <?php if (!empty($row['photo'])): ?>
                <img src="uploads/<?php echo htmlspecialchars($row['photo']); ?>" class="card-img-top img-thumbnail" alt="photo" onerror="this.onerror=null; this.src='default.jpg';">
            <?php endif; ?>
            <div class="card-body">
                <h5 class="card-title"><?php echo htmlspecialchars($row["nom"]) . " " . htmlspecialchars($row["prenom"]); ?></h5>
                <p class="card-text"><strong>Salaire :</strong> <?php echo htmlspecialchars($row["salaire"]); ?></p>
                <p class="card-text"><strong>Poste :</strong> <?php echo htmlspecialchars($row["poste"]); ?></p>
                <p class="card-text"><strong>Date d'embauche :</strong> <?php echo htmlspecialchars($row["date_embauche"]); ?></p>
                <p class="card-text"><strong>Adresse :</strong> <?php echo htmlspecialchars($row["adresse"]); ?></p>
                <p class="card-text"><strong>Téléphone :</strong> <?php echo htmlspecialchars($row["telephone"]); ?></p>
                <p class="card-text"><strong>Email :</strong> <?php echo htmlspecialchars($row["email"]); ?></p>
                <p class="card-text"><strong>Statut :</strong> <?php echo htmlspecialchars($row["statut"]); ?></p>
                <p class="card-text"><strong>Entreprise :</strong> <?php echo htmlspecialchars($row["nom_entreprise"]); ?></p>
            </div>
        </div>
        <div class="text-center mt-4">
            <a href="home.php" class="btn btn-primary">Retour à la liste</a>
        </div>
    </div>
</body>
</html>