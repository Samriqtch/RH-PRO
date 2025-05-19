<?php
include("db.php");
session_start();
include("include/header.php");
include("include/navbar.php");
?>
<?php

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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body {
            background: linear-gradient(120deg, #e3f0ff 0%, #f8f9fa 100%);
            min-height: 100vh;
        }
        .profile-card {
            max-width: 520px; /* ou 600px pour encore plus large */
            border-radius: 1.5rem;
            box-shadow: 0 8px 32px rgba(13,110,253,0.08);
            background: #fff;
            margin-top: 40px;
        }
        .profile-img {
            width: 120px;
            height: 120px;
            object-fit: cover;
            border-radius: 50%;
            border: 4px solid #0d6efd22;
            margin-top: -60px;
            background: #f8f9fa;
        }
        .profile-header {
            background: linear-gradient(90deg, #0d6efd 60%, #00c6ff 100%);
            border-radius: 1.5rem 1.5rem 0 0;
            height: 80px;
            position: relative;
        }
        .profile-title {
            margin-top: 10px;
            font-weight: bold;
            color: #0d6efd;
        }
        .profile-info strong {
            color: #0d6efd;
            min-width: 110px;
            display: inline-block;
        }
        .badge-status {
            font-size: 0.95em;
            padding: 0.5em 1em;
        }
        .profile-actions {
            margin-top: 24px;
        }
    </style>
</head>
<body>
    <div class="container py-4">
        <div class="row justify-content-center align-items-center" style="max-height: 80vh;">
            <div class="col-12 col-md-10 col-lg-8">
                <div class="card shadow-lg border-0 rounded-4 p-0 overflow-hidden">
                    <div class="row g-0">
                        <!-- Colonne gauche : photo et nom -->
                        <div class="col-12 col-md-4 bg-primary d-flex flex-column align-items-center justify-content-center text-white py-4">
                            <img src="uploads/userpic1.png"
                                 class="rounded-circle shadow mb-3"
                                 alt="photo"
                                 style="width:180px; height:180px; object-fit:cover; border:4px solid #fff;"
                                 onerror="this.onerror=null; this.src='default.jpg';">
                            <h4 class="fw-bold mb-1"><?php echo htmlspecialchars($row["nom"]) . " " . htmlspecialchars($row["prenom"]); ?></h4>
                            <span class="badge badge-status 
                                <?php
                                    if ($row["statut"] === "Actif") echo "bg-light text-primary";
                                    elseif ($row["statut"] === "En congé") echo "bg-warning text-dark";
                                    else echo "bg-secondary";
                                ?> px-3 py-2 mt-2">
                                <i class="bi bi-person-badge"></i> <?php echo htmlspecialchars($row["statut"]); ?>
                            </span>
                        </div>
                        <!-- Colonne droite : infos -->
                        <div class="col-12 col-md-8 bg-white p-5">
                            <h5 class="text-primary fw-bold mb-3">Informations de l'employé</h5>
                            <ul class="list-unstyled mb-4">
                                <li class="mb-2"><i class="bi bi-briefcase text-primary me-2"></i><strong>Poste :</strong> <?php echo htmlspecialchars($row["poste"]); ?></li>
                                <li class="mb-2"><i class="bi bi-cash text-primary me-2"></i><strong>Salaire :</strong> <?php echo htmlspecialchars($row["salaire"]); ?></li>
                                <li class="mb-2"><i class="bi bi-calendar-event text-primary me-2"></i><strong>Embauche :</strong> <?php echo htmlspecialchars($row["date_embauche"]); ?></li>
                                <li class="mb-2"><i class="bi bi-geo-alt text-primary me-2"></i><strong>Adresse :</strong> <?php echo htmlspecialchars($row["adresse"]); ?></li>
                                <li class="mb-2"><i class="bi bi-telephone text-primary me-2"></i><strong>Téléphone :</strong> <?php echo htmlspecialchars($row["telephone"]); ?></li>
                                <li class="mb-2"><i class="bi bi-envelope text-primary me-2"></i><strong>Email :</strong>
                                    <span class="badge bg-light text-dark border border-info px-2 py-1 shadow-sm">
                                        <?php echo htmlspecialchars($row["email"]); ?>
                                    </span>
                                </li>
                                <li class="mb-2"><i class="bi bi-building text-primary me-2"></i><strong>Entreprise :</strong> <?php echo htmlspecialchars($row["nom_entreprise"]); ?></li>
                            </ul>
                            <div class="text-end">
                                <a href="home.php" class="btn btn-outline-primary px-4">
                                    <i class="bi bi-arrow-left"></i> Retour à la liste
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .badge-status {
            font-size: 1em;
            border-radius: 1rem;
        }
        @media (max-width: 767px) {
            .card .row.g-0 {
                flex-direction: column;
            }
            .card .col-md-4, .card .col-md-8 {
                max-width: 100%;
                flex: 0 0 100%;
            }
        }
    </style>
</body>
</html>