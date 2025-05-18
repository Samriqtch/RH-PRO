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

// Récupérer les employés de l'entreprise de l'utilisateur connecté avec le nom de l'entreprise
$sql = "SELECT e.*, en.nom AS nom_entreprise
        FROM employes e
        JOIN entreprises en ON e.entreprise_id = en.id
        WHERE e.entreprise_id = :entreprise_id";
$stmt = $pdo->prepare($sql);
$stmt->execute(['entreprise_id' => $entreprise_id]);
$employes = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Nombre total d'employés
$sql_total = "SELECT COUNT(*) FROM employes WHERE entreprise_id = :entreprise_id";
$stmt_total = $pdo->prepare($sql_total);
$stmt_total->execute(['entreprise_id' => $entreprise_id]);
$total_employes = $stmt_total->fetchColumn();

// Nombre d'employés en congé
$sql_conges = "SELECT COUNT(*) FROM employes WHERE entreprise_id = :entreprise_id AND statut = 'congé'";
$stmt_conges = $pdo->prepare($sql_conges);
$stmt_conges->execute(['entreprise_id' => $entreprise_id]);
$employes_conges = $stmt_conges->fetchColumn();
?>

<div class="container mt-5">
    <h2 class="text-center mb-4">Liste des Employés</h2>
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card text-bg-primary mb-3">
                <div class="card-body">
                    <h5 class="card-title">Nombre total d'employés</h5>
                    <p class="card-text fs-3"><?= $total_employes ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card text-bg-warning mb-3">
                <div class="card-body">
                    <h5 class="card-title">Employés en congé</h5>
                    <p class="card-text fs-3"><?= $employes_conges ?></p>
                </div>
            </div>
        </div>
    </div>
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
<?php include("include/footer.php"); ?>