
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
    header("Location: home.php");
    exit();
}

$employe_id = $_GET['id'];
$entreprise_id = $_SESSION['entreprise_id'];

// Récupérer les infos actuelles de l'employé
$sql = "SELECT * FROM employes WHERE id = :id AND entreprise_id = :entreprise_id";
$stmt = $pdo->prepare($sql);
$stmt->execute(['id' => $employe_id, 'entreprise_id' => $entreprise_id]);
$employe = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$employe) {
    echo "Employé introuvable ou accès non autorisé.";
    exit();
}

// Traitement du formulaire de modification
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $salaire = $_POST['salaire'];
    $poste = $_POST['poste'];
    $date_embauche = $_POST['date_embauche'];
    $adresse = $_POST['adresse'];
    $telephone = $_POST['telephone'];
    $email = $_POST['email'];
    $statut = $_POST['statut'];

    $sql = "UPDATE employes SET nom = :nom, prenom = :prenom, salaire = :salaire, poste = :poste, date_embauche = :date_embauche, adresse = :adresse, telephone = :telephone, email = :email, statut = :statut WHERE id = :id AND entreprise_id = :entreprise_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'nom' => $nom,
        'prenom' => $prenom,
        'salaire' => $salaire,
        'poste' => $poste,
        'date_embauche' => $date_embauche,
        'adresse' => $adresse,
        'telephone' => $telephone,
        'email' => $email,
        'statut' => $statut,
        'id' => $employe_id,
        'entreprise_id' => $entreprise_id
    ]);
    header("Location: home.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier un employé</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <h2 class="mb-4">Modifier les informations de l'employé</h2>
        <form method="post">
            <div class="mb-3">
                <label class="form-label">Nom</label>
                <input type="text" name="nom" class="form-control" value="<?= htmlspecialchars($employe['nom']) ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Prénom</label>
                <input type="text" name="prenom" class="form-control" value="<?= htmlspecialchars($employe['prenom']) ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Salaire</label>
                <input type="number" name="salaire" class="form-control" value="<?= htmlspecialchars($employe['salaire']) ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Poste</label>
                <input type="text" name="poste" class="form-control" value="<?= htmlspecialchars($employe['poste']) ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Date d'embauche</label>
                <input type="date" name="date_embauche" class="form-control" value="<?= htmlspecialchars($employe['date_embauche']) ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Adresse</label>
                <input type="text" name="adresse" class="form-control" value="<?= htmlspecialchars($employe['adresse']) ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Téléphone</label>
                <input type="text" name="telephone" class="form-control" value="<?= htmlspecialchars($employe['telephone']) ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($employe['email']) ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Statut</label>
                <input type="text" name="statut" class="form-control" value="<?= htmlspecialchars($employe['statut']) ?>" required>
            </div>
            <button type="submit" class="btn btn-primary">Enregistrer</button>
            <a href="home.php" class="btn btn-secondary">Annuler</a>
        </form>
    </div>
</body>
</html>