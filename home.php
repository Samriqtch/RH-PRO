<?php
session_start();
include("db.php");
include("include/header.php");


// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['login']) || !isset($_SESSION['entreprise_id'])) {
    header("Location: login.php");
    exit();
}

$entreprise_id = $_SESSION['entreprise_id'];

// Correction ici : nom de la table et du champ
$sql = "SELECT e.*, en.nom AS nom_entreprise
        FROM employes e
        JOIN entreprises en ON e.entreprise_id = en.id
        WHERE e.entreprise_id = :entreprise_id";
$stmt = $pdo->prepare($sql);
$stmt->execute(['entreprise_id' => $entreprise_id]);
$employes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Employés</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="d-flex">
        <!-- Sidebar -->
        <nav class="bg-dark text-white p-3 vh-100" style="width: 250px;">
            <h3 class="text-center">Menu</h3>
            <ul class="nav flex-column">
                <li class="nav-item mb-2">
                    <a href="home.php" class="nav-link text-white">Dashboard</a>
                </li>
                <li class="nav-item mb-2">
                    <a href="add.php" class="nav-link text-white">Ajouter un Employé</a>
                </li>
                <li class="nav-item mb-2">
                    <a href="profile.php" class="nav-link text-white">Mon Profil</a>
                </li>
                <li class="nav-item mb-2">
                    <a href="logout.php" class="nav-link text-white">Déconnexion</a>
                </li>
            </ul>
        </nav>

        <!-- Main Content -->
        <div class="container-fluid p-4">
            <h2 class="text-center mb-4">Liste des Employés</h2>
            <div class="mb-3 text-end">
                <a href="add.php" class="btn btn-success">Ajouter un Employé</a>
            </div>
            <table class="table table-bordered table-striped table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>Nom</th>
                        <th>Prénom</th>
                        <th>Salaire</th>
                        <th>Poste</th>
                        <th>Date d'embauche</th>
                        <th>Adresse</th>
                        <th>Téléphone</th>
                        <th>Email</th>
                        <th>Statut</th>
                        <th>Entreprise</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($employes as $employe): ?>
                    <tr>
                        <td><?= htmlspecialchars($employe["nom"]) ?></td>
                        <td><?= htmlspecialchars($employe["prenom"]) ?></td>
                        <td><?= htmlspecialchars($employe["salaire"]) ?></td>
                        <td><?= htmlspecialchars($employe["poste"]) ?></td>
                        <td><?= htmlspecialchars($employe["date_embauche"]) ?></td>
                        <td><?= htmlspecialchars($employe["adresse"]) ?></td>
                        <td><?= htmlspecialchars($employe["telephone"]) ?></td>
                        <td><?= htmlspecialchars($employe["email"]) ?></td>
                        <td><?= htmlspecialchars($employe["statut"]) ?></td>
                        <td><?= htmlspecialchars($employe["nom_entreprise"]) ?></td>
                        <td>
                            <a href="edit.php?id=<?= $employe['id'] ?>" class="btn btn-primary btn-sm">Modifier</a>
                            <a href="delete.php?id=<?= $employe['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Voulez-vous vraiment supprimer cet employé ?');">Supprimer</a>
                            <a href="view.php?id=<?= $employe['id'] ?>" class="btn btn-info btn-sm">Voir</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>