<?php
session_start();
include("db.php");

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

include("include/header.php");
include("include/navbar.php");
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier un employé</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body {
            background: #f3f6fa;
        }
        .edit-card {
            background: #fff;
            border-radius: 24px;
            box-shadow: 0 8px 32px rgba(1,33,82,0.10), 0 1.5px 6px rgba(1,33,82,0.08);
            max-width: 520px;
            margin: 40px auto;
            padding: 40px 32px 32px 32px;
        }
        .edit-title {
            color: #0d6efd;
            font-weight: bold;
            text-align: center;
            margin-bottom: 24px;
            letter-spacing: 1px;
        }
        .form-label {
            color: #012152;
            font-weight: 500;
        }
        .btn-edit {
            background: #0d6efd;
            color: #fff;
            font-weight: bold;
            border-radius: 8px;
            padding: 10px 0;
            font-size: 1.1rem;
        }
        .btn-edit:hover {
            background: #012152;
            color: #fff;
        }
        .edit-icon {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-bottom: 18px;
        }
        .edit-icon .bi {
            font-size: 3.5rem;
            color: #0d6efd;
            background: #eaf1fb;
            border-radius: 50%;
            padding: 18px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="edit-card">
            <div class="edit-icon">
                <i class="bi bi-person-lines-fill"></i>
            </div>
            <h2 class="edit-title">Modifier l'employé</h2>
            <form method="post" class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Nom</label>
                    <input type="text" name="nom" class="form-control" value="<?= htmlspecialchars($employe['nom']) ?>" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Prénom</label>
                    <input type="text" name="prenom" class="form-control" value="<?= htmlspecialchars($employe['prenom']) ?>" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Salaire</label>
                    <input type="number" name="salaire" class="form-control" value="<?= htmlspecialchars($employe['salaire']) ?>" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Poste</label>
                    <input type="text" name="poste" class="form-control" value="<?= htmlspecialchars($employe['poste']) ?>" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Date d'embauche</label>
                    <input type="date" name="date_embauche" class="form-control" value="<?= htmlspecialchars($employe['date_embauche']) ?>" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Statut</label>
                    <select name="statut" class="form-select" required>
                        <option value="Actif" <?= $employe['statut'] == 'Actif' ? 'selected' : '' ?>>Actif</option>
                        <option value="En congé" <?= $employe['statut'] == 'En congé' ? 'selected' : '' ?>>En congé</option>
                        <option value="Inactif" <?= $employe['statut'] == 'Inactif' ? 'selected' : '' ?>>Inactif</option>
                    </select>
                </div>
                <div class="col-md-12">
                    <label class="form-label">Adresse</label>
                    <input type="text" name="adresse" class="form-control" value="<?= htmlspecialchars($employe['adresse']) ?>" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Téléphone</label>
                    <input type="text" name="telephone" class="form-control" value="<?= htmlspecialchars($employe['telephone']) ?>" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($employe['email']) ?>" required>
                </div>
                <div class="col-12 text-center mt-3">
                    <button type="submit" class="btn btn-edit px-4">Enregistrer</button>
                    <a href="home.php" class="btn btn-secondary ms-2 px-4">Annuler</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
<?php include("include/footer.php"); ?>