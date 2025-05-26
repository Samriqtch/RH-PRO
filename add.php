<?php
session_start();
include("db.php");

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['login']) || !isset($_SESSION['entreprise_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $salaire = $_POST['salaire'];
    $poste = $_POST['poste'];
    $date_embauche = $_POST['date_embauche'];
    $adresse = $_POST['adresse'];
    $telephone = $_POST['telephone'];
    $email = $_POST['email'];
    $statut = $_POST['statut'];
    $entreprise_id = $_SESSION['entreprise_id'];

    $sql = "INSERT INTO employes (nom, prenom, salaire, poste, date_embauche, adresse, telephone, email, statut, entreprise_id) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        $nom, $prenom, $salaire, $poste, $date_embauche, $adresse, $telephone, $email, $statut, $entreprise_id
    ]);

    // Compte le nombre total d'employés pour calculer la dernière page
    $sql_total = "SELECT COUNT(*) FROM employes WHERE entreprise_id = :entreprise_id";
    $stmt_total = $pdo->prepare($sql_total);
    $stmt_total->execute(['entreprise_id' => $_SESSION['entreprise_id']]);
    $total_employes = $stmt_total->fetchColumn();

    $rowsPerPage = 10; // Doit être le même que dans home.php
    $totalPages = ceil($total_employes / $rowsPerPage);

    // Redirige vers la dernière page
    header('Location: home.php?page=' . $totalPages);
    exit();
}

include("include/header.php");
include("include/navbar.php");
?>

<div class="container d-flex align-items-center justify-content-center" style="min-height: 90vh;">
    <div class="col-12 col-md-8 col-lg-6">
        <div class="card shadow-lg border-0 rounded-4">
            <div class="card-body p-5">
                <h2 class="text-center mb-4 text-primary fw-bold">
                    <i class="bi bi-person-plus-fill"></i> Ajouter un Employé
                </h2>
                <?php if (isset($error)): ?>
                    <div class="alert alert-danger"><?php echo $error; ?></div>
                <?php endif; ?>
                <form method="post" class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Nom :</label>
                        <input type="text" name="nom" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Prénom :</label>
                        <input type="text" name="prenom" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Salaire :</label>
                        <input type="number" name="salaire" class="form-control" required min="0">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Poste :</label>
                        <input type="text" name="poste" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Date d'embauche :</label>
                        <input type="date" name="date_embauche" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Statut :</label>
                        <select name="statut" class="form-select" required>
                            <option value="">Sélectionner...</option>
                            <option value="Actif">Actif</option>
                            <option value="En congé">En congé</option>
                            <option value="Inactif">Inactif</option>
                        </select>
                    </div>
                    <div class="col-md-12">
                        <label class="form-label">Adresse :</label>
                        <input type="text" name="adresse" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Téléphone :</label>
                        <input type="text" name="telephone" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Email :</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                    <div class="col-12 text-center mt-4">
                        <button type="submit" class="btn btn-success px-4">Ajouter</button>
                        <a href="home.php" class="btn btn-secondary ms-2 px-4">Annuler</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- Optionnel : Bootstrap Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<?php include("include/footer.php"); ?>