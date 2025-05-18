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

$entreprise_id = $_SESSION['entreprise_id'];

// Recherche
$q = isset($_GET['q']) ? trim($_GET['q']) : '';
$params = ['entreprise_id' => $entreprise_id];
$where = "WHERE e.entreprise_id = :entreprise_id";
if ($q !== '') {
    $where .= " AND (e.nom LIKE :q OR e.prenom LIKE :q OR e.email LIKE :q)";
    $params['q'] = "%$q%";
}

$sql = "SELECT e.*, en.nom AS nom_entreprise
        FROM employes e
        JOIN entreprises en ON e.entreprise_id = en.id
        $where";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$employes = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Nombre total d'employés
$sql_total = "SELECT COUNT(*) FROM employes WHERE entreprise_id = :entreprise_id";
$stmt_total = $pdo->prepare($sql_total);
$stmt_total->execute(['entreprise_id' => $entreprise_id]);
$total_employes = $stmt_total->fetchColumn();

// Nombre d'employés en congé
$sql_conges = "SELECT COUNT(*) FROM employes WHERE entreprise_id = :entreprise_id AND statut = 'En congé'";
$stmt_conges = $pdo->prepare($sql_conges);
$stmt_conges->execute(['entreprise_id' => $entreprise_id]);
$employes_conges = $stmt_conges->fetchColumn();
?>

<div class="container py-4">
    <h2 class="text-center mb-4 fw-bold text-primary">Liste des Employés</h2>
    <div class="row justify-content-center mb-4 g-3">
        <div class="col-12 col-md-6">
            <div class="card shadow border-0 p-3">
                <h6 class="text-center mb-2 text-primary">Répartition Employés</h6>
                <div style="width:100%;height:250px;">
                    <canvas id="employeChart"></canvas>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-6">
            <div class="card shadow border-0 p-3">
                <h6 class="text-center mb-2 text-primary">Employés en congé</h6>
                <div style="width:100%;height:250px;">
                    <canvas id="congeChart"></canvas>
                </div>
            </div>
        </div>
    </div>
    <div class="mb-3 text-end">
        <a href="add.php" class="btn btn-success px-4 fw-bold">
            <i class="bi bi-person-plus"></i> Ajouter un Employé
        </a>
    </div>
    <div class="table-responsive">
        <table class="table align-middle table-borderless shadow-sm rounded-4 overflow-hidden" style="background: #fff;">
            <thead style="background: #012152; color: #fff;">
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
                    <th class="text-center" style="min-width:140px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($employes as $employe): ?>
                <tr class="table-row-hover">
                    <td class="fw-bold"><?= htmlspecialchars($employe["nom"]) ?></td>
                    <td><?= htmlspecialchars($employe["prenom"]) ?></td>
                    <td><?= htmlspecialchars($employe["salaire"]) ?></td>
                    <td><?= htmlspecialchars($employe["poste"]) ?></td>
                    <td><?= htmlspecialchars($employe["date_embauche"]) ?></td>
                    <td><?= htmlspecialchars($employe["adresse"]) ?></td>
                    <td><?= htmlspecialchars($employe["telephone"]) ?></td>
                    <td><?= htmlspecialchars($employe["email"]) ?></td>
                    <td>
                        <span class="badge 
                            <?= $employe["statut"] === "Actif" ? "bg-primary" : ($employe["statut"] === "En congé" ? "bg-warning text-dark" : "bg-secondary") ?>">
                            <?= htmlspecialchars($employe["statut"]) ?>
                        </span>
                    </td>
                    <td><?= htmlspecialchars($employe["nom_entreprise"]) ?></td>
                    <td>
                        <div class="d-flex justify-content-center gap-2">
                            <a href="view.php?id=<?= $employe['id'] ?>" class="btn btn-light btn-sm rounded-circle shadow-sm" data-bs-toggle="tooltip" title="Voir">
                                <i class="bi bi-eye text-info"></i>
                            </a>
                            <a href="edit.php?id=<?= $employe['id'] ?>" class="btn btn-light btn-sm rounded-circle shadow-sm" data-bs-toggle="tooltip" title="Modifier">
                                <i class="bi bi-pencil text-primary"></i>
                            </a>
                            <a href="delete.php?id=<?= $employe['id'] ?>" class="btn btn-light btn-sm rounded-circle shadow-sm" data-bs-toggle="tooltip" title="Supprimer" onclick="return confirm('Voulez-vous vraiment supprimer cet employé ?');">
                                <i class="bi bi-trash text-danger"></i>
                            </a>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php if (empty($employes)): ?>
            <div class="alert alert-info text-center mt-3">Aucun employé trouvé.</div>
        <?php endif; ?>
    </div>
</div>

<!-- Bootstrap Icons & Chart.js -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctxEmploye = document.getElementById('employeChart').getContext('2d');
    new Chart(ctxEmploye, {
        type: 'doughnut',
        data: {
            labels: ['Total Employés', 'En congé'],
            datasets: [{
                data: [<?= $total_employes ?>, <?= $employes_conges ?>],
                backgroundColor: ['#0d6efd', '#ffc107'],
                borderWidth: 2
            }]
        },
        options: {
            cutout: '70%',
            plugins: {
                legend: {
                    display: true,
                    position: 'bottom',
                    labels: {
                        color: '#0d6efd',
                        font: {weight: 'bold'}
                    }
                }
            }
        }
    });

    const ctxConge = document.getElementById('congeChart').getContext('2d');
    new Chart(ctxConge, {
        type: 'bar',
        data: {
            labels: ['En congé', 'Autres'],
            datasets: [{
                label: 'Nombre d\'employés',
                data: [<?= $employes_conges ?>, <?= $total_employes - $employes_conges ?>],
                backgroundColor: ['#ffc107', '#0d6efd'],
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: true,
                    position: 'top',
                    labels: {
                        color: '#0d6efd',
                        font: {weight: 'bold'}
                    }
                },
                tooltip: {
                    callbacks: {
                        label: function(tooltipItem) {
                            return tooltipItem.label + ': ' + tooltipItem.raw;
                        }
                    }
                }
            }
        }
    });

    // Active les tooltips Bootstrap
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    tooltipTriggerList.forEach(function (tooltipTriggerEl) {
        new bootstrap.Tooltip(tooltipTriggerEl)
    })
</script>
<?php include("include/footer.php"); ?>