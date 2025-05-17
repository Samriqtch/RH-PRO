<?php

include("db.php");
session_start();

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

    // Gestion de l'upload de la photo
    $photo = null;
    if (!empty($_FILES['photo']['name'])) {
        $photo = uniqid() . '_' . basename($_FILES['photo']['name']);
        $target_dir = "uploads/";
        $target_file = $target_dir . $photo;
        if (!move_uploaded_file($_FILES['photo']['tmp_name'], $target_file)) {
            $error = "Erreur lors de l'upload de la photo.";
        }
    }

    if (!isset($error)) {
        $sql = "INSERT INTO employes (nom, prenom, salaire, poste, date_embauche, adresse, telephone, email, statut, photo, entreprise_id) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$nom, $prenom, $salaire, $poste, $date_embauche, $adresse, $telephone, $email, $statut, $photo, $entreprise_id]);
        header("Location: home.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un Employé</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center mb-4">Ajouter un Employé</h2>
        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>
        <form method="post" enctype="multipart/form-data" class="shadow p-4 rounded bg-light">
            <div class="mb-3">
                <label class="form-label">Nom :</label>
                <input type="text" name="nom" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Prénom :</label>
                <input type="text" name="prenom" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Salaire :</label>
                <input type="number" name="salaire" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Poste :</label>
                <input type="text" name="poste" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Date d'embauche :</label>
                <input type="date" name="date_embauche" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Adresse :</label>
                <input type="text" name="adresse" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Téléphone :</label>
                <input type="text" name="telephone" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Email :</label>
                <input type="email" name="email" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Statut :</label>
                <input type="text" name="statut" class="form-control" required>
            </div>

            <div class="text-center">
                <button type="submit" class="btn btn-success">Ajouter</button>
                <a href="home.php" class="btn btn-secondary">Annuler</a>
            </div>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>