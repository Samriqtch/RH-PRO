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

// Pagination
$rowsPerPage = 10; // Nombre de lignes par page
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $rowsPerPage;

// Recherche
$q = isset($_GET['q']) ? trim($_GET['q']) : '';
$params = ['entreprise_id' => $entreprise_id];
$where = "WHERE e.entreprise_id = :entreprise_id";
if ($q !== '') {
    $where .= " AND (e.nom LIKE :q OR e.prenom LIKE :q OR e.email LIKE :q)";
    $params['q'] = "%$q%";
}

// Compter le total pour la pagination
$sql_count = "SELECT COUNT(*) FROM employes e $where";
$stmt_count = $pdo->prepare($sql_count);
$stmt_count->execute($params);
$totalRows = $stmt_count->fetchColumn();
$totalPages = ceil($totalRows / $rowsPerPage);

// Récupérer les employés paginés
$sql = "SELECT e.*, en.nom AS nom_entreprise
        FROM employes e
        JOIN entreprises en ON e.entreprise_id = en.id
        $where
        ORDER BY e.nom ASC
        LIMIT :limit OFFSET :offset";
$stmt = $pdo->prepare($sql);
foreach ($params as $key => $value) {
    $stmt->bindValue(":$key", $value);
}
$stmt->bindValue(':limit', $rowsPerPage, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
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

<div class="container py-2">
    <br>
    <h2 class="text-center mb-2 fw-bold text-primary">BIENVENUE SUR VOTRE APPLICATION RH </h2>

    <div class="row justify-content-center mb-2 g-3">
        <div class="col-12 col-md-6">
            <div class="card shadow border-0 p-3">
                <h6 class="text-center mb-2 text-primary">Répartition Employés</h6>
                <div style="width:100%;height:180px;">
                    <canvas id="employeChart"></canvas>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-6">
            <div class="card shadow border-0 p-3">
                <h6 class="text-center mb-2 text-primary">Employés en congé</h6>
                <div style="width:100%;height:180px;">
                    <canvas id="congeChart"></canvas>
                </div>
            </div>
        </div>
    </div>
<div>
    <br>
    <h2 class="text-center mb-2 fw-bold text-primary">LISTES DES EMPLOYES</h2>
    
</div>
    <!-- Liste des employés stylée et compacte -->
    <div class="d-flex justify-content-center">
        <div class="card shadow border-0" style="max-width: 1300px; width: 100%;">
            <div class="card-header bg-primary text-white d-flex align-items-center justify-content-between">
                <span class="fw-bold fs-5">Liste des employés</span>
                <span class="badge bg-light text-primary"><?= $total_employes ?> employés</span>
            </div>
            <div class="table-responsive" style="max-height: 300px; overflow-y: auto;">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Nom</th>
                            <th>Prénom</th>
                            <th>Salaire</th>
                            <th>Poste</th>
                            <th>Embauche</th>
                            <th>Adresse</th>
                            <th>Tél</th>
                            <th>Email</th>
                            <th>Statut</th>
                            <th>Entreprise</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($employes as $employe): ?>
                        <tr>
                            <td class="fw-semibold"><?= htmlspecialchars($employe["nom"]) ?></td>
                            <td><?= htmlspecialchars($employe["prenom"]) ?></td>
                            <td>
                                <span class="badge bg-info text-dark"><?= htmlspecialchars($employe["salaire"]) ?></span>
                            </td>
                            <td><?= htmlspecialchars($employe["poste"]) ?></td>
                            <td><?= htmlspecialchars($employe["date_embauche"]) ?></td>
                            <td style="max-width:120px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;" title="<?= htmlspecialchars($employe["adresse"]) ?>">
                                <?= htmlspecialchars($employe["adresse"]) ?>
                            </td>
                            <td><?= htmlspecialchars($employe["telephone"]) ?></td>
                            <td style="max-width:140px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;" title="<?= htmlspecialchars($employe["email"]) ?>">
                                <span class="badge bg-light text-dark border border-info"><?= htmlspecialchars($employe["email"]) ?></span>
                            </td>
                            <td>
                                <span class="badge
                                    <?= $employe["statut"] === "Actif" ? "bg-success" : ($employe["statut"] === "En congé" ? "bg-warning text-dark" : "bg-secondary") ?>">
                                    <?= htmlspecialchars($employe["statut"]) ?>
                            </span>
                            </td>
                            <td><?= htmlspecialchars($employe["nom_entreprise"]) ?></td>
                            <td>
                                <div class="d-flex justify-content-center gap-1">
                                    <a href="view.php?id=<?= $employe['id'] ?>" class="btn btn-outline-info btn-sm rounded-circle" data-bs-toggle="tooltip" title="Voir">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="edit.php?id=<?= $employe['id'] ?>" class="btn btn-outline-primary btn-sm rounded-circle" data-bs-toggle="tooltip" title="Modifier">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <a href="delete.php?id=<?= $employe['id'] ?>" class="btn btn-outline-danger btn-sm rounded-circle" data-bs-toggle="tooltip" title="Supprimer" onclick="return confirm('Voulez-vous vraiment supprimer cet employé ?');">
                                        <i class="bi bi-trash"></i>
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
            <div class="card-footer bg-white">
                <!-- Pagination supprimée -->
            </div>
        </div>
    </div>
    <!-- Fin centrage tableau -->

    <!-- Pagination supprimée -->

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

<!-- Ajoute ce style pour l'effet hover et l'amélioration visuelle -->
<style>
.table-row-hover:hover {
    background: #e7f1ff !important;
    cursor: pointer;
}
.table thead th {
    vertical-align: middle;
    font-size: 0.90rem;
    padding-top: 8px !important;
    padding-bottom: 8px !important;
}
.table td, .table th {
    vertical-align: middle;
    font-size: 0.91rem;
    padding-top: 6px !important;
    padding-bottom: 6px !important;
    padding-left: 8px !important;
    padding-right: 8px !important;
}
</style>

