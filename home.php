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

<div class="container py-2">
    <br>
    <h2 class="text-center mb-2 fw-bold text-primary">BIENVENUE SUR VOTRE APPLICATION RH </h2>
    <br>
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
    <br>
</div>
    <!-- Liste des employés stylée et compacte -->
    <div class="d-flex justify-content-center">
        <div class="table-responsive" style="max-width: 1300px; width: 100%; max-height: 500px; overflow-y: auto;">
            <table class="table align-middle mb-0 bg-white shadow rounded-4 overflow-hidden" style="background: #f8f9fa; font-size: 0.92rem;">
                <thead style="background: linear-gradient(90deg, #0d6efd 60%, #00c6ff 100%); color: #fff;">
                    <tr>
                        <th class="fw-semibold text-uppercase px-2" style="letter-spacing:1px;">Nom</th>
                        <th class="fw-semibold text-uppercase px-2">Prénom</th>
                        <th class="fw-semibold text-uppercase px-2">Salaire</th>
                        <th class="fw-semibold text-uppercase px-2">Poste</th>
                        <th class="fw-semibold text-uppercase px-2">Embauche</th>
                        <th class="fw-semibold text-uppercase px-2">Adresse</th>
                        <th class="fw-semibold text-uppercase px-2">Tél</th>
                        <th class="fw-semibold text-uppercase px-2">Email</th>
                        <th class="fw-semibold text-uppercase px-2">Statut</th>
                        <th class="fw-semibold text-uppercase px-2">Entreprise</th>
                        <th class="text-center fw-semibold text-uppercase px-2" style="min-width:110px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($employes as $employe): ?>
                    <tr class="table-row-hover" style="transition: background 0.2s;">
                        <td class="fw-bold px-2"><?= htmlspecialchars($employe["nom"]) ?></td>
                        <td class="px-2"><?= htmlspecialchars($employe["prenom"]) ?></td>
                        <td class="px-2">
                            <span class="badge rounded-pill bg-light text-primary border border-primary px-2 py-1 shadow-sm" style="font-size:0.90em;">
                                <?= htmlspecialchars($employe["salaire"]) ?>
                            </span>
                        </td>
                        <td class="px-2"><?= htmlspecialchars($employe["poste"]) ?></td>
                        <td class="px-2"><?= htmlspecialchars($employe["date_embauche"]) ?></td>
                        <td class="px-2" style="max-width:120px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;" title="<?= htmlspecialchars($employe["adresse"]) ?>">
                            <?= htmlspecialchars($employe["adresse"]) ?>
                        </td>
                        <td class="px-2"><?= htmlspecialchars($employe["telephone"]) ?></td>
                        <td class="px-2" style="max-width:140px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;" title="<?= htmlspecialchars($employe["email"]) ?>">
                            <span class="badge rounded-pill bg-light text-dark border border-info px-2 py-1 shadow-sm" style="font-size:0.90em;">
                                <?= htmlspecialchars($employe["email"]) ?>
                            </span>
                        </td>
                        <td class="px-2">
                            <span class="badge rounded-pill px-2 py-1 shadow-sm
                                <?= $employe["statut"] === "Actif" ? "bg-primary" : ($employe["statut"] === "En congé" ? "bg-warning text-dark" : "bg-secondary") ?>"
                                style="font-size:0.90em;">
                                <?= htmlspecialchars($employe["statut"]) ?>
                            </span>
                        </td>
                        <td class="px-2"><?= htmlspecialchars($employe["nom_entreprise"]) ?></td>
                        <td class="px-2">
                            <div class="d-flex justify-content-center gap-1">
                                <a href="view.php?id=<?= $employe['id'] ?>" class="btn btn-outline-info btn-sm rounded-circle shadow-sm" data-bs-toggle="tooltip" title="Voir">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="edit.php?id=<?= $employe['id'] ?>" class="btn btn-outline-primary btn-sm rounded-circle shadow-sm" data-bs-toggle="tooltip" title="Modifier">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <a href="delete.php?id=<?= $employe['id'] ?>" class="btn btn-outline-danger btn-sm rounded-circle shadow-sm" data-bs-toggle="tooltip" title="Supprimer" onclick="return confirm('Voulez-vous vraiment supprimer cet employé ?');">
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
    </div>
    <!-- Fin centrage tableau -->
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
