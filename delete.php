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

// Supprimer l'employé uniquement s'il appartient à l'entreprise de l'utilisateur connecté
$sql = "DELETE FROM employes WHERE id = :id AND entreprise_id = :entreprise_id";
$stmt = $pdo->prepare($sql);
$stmt->execute(['id' => $employe_id, 'entreprise_id' => $entreprise_id]);

include("include/header.php");
include("include/navbar.php");
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Suppression Employé</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #eaf1fb 0%, #f3f6fa 100%);
        }
        .notif-card {
            background: #fff;
            border-radius: 32px;
            box-shadow: 0 12px 36px rgba(13,110,253,0.10), 0 2px 8px rgba(1,33,82,0.08);
            max-width: 430px;
            margin: 90px auto 0 auto;
            padding: 48px 36px 36px 36px;
            position: relative;
            overflow: hidden;
            animation: pop-in 0.7s cubic-bezier(.68,-0.55,.27,1.55);
        }
        @keyframes pop-in {
            0% { transform: scale(0.7) translateY(60px); opacity: 0; }
            100% { transform: scale(1) translateY(0); opacity: 1; }
        }
        .notif-confetti {
            position: absolute;
            top: -30px; left: 0; width: 100%; height: 60px; pointer-events: none;
            z-index: 2;
        }
        .notif-icon {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-bottom: 18px;
            position: relative;
            z-index: 3;
        }
        .notif-icon .bi {
            font-size: 4rem;
            color: #fff;
            background: linear-gradient(135deg, #0d6efd 60%, #67b6ff 100%);
            border-radius: 50%;
            padding: 22px;
            box-shadow: 0 2px 12px rgba(13,110,253,0.18);
            border: 4px solid #eaf1fb;
        }
        .notif-title {
            color: #0d6efd;
            font-weight: bold;
            text-align: center;
            margin-bottom: 12px;
            letter-spacing: 1px;
            font-size: 1.5rem;
        }
        .notif-text {
            text-align: center;
            color: #333;
            font-size: 1.13rem;
            margin-bottom: 10px;
        }
        .notif-redirect {
            text-align: center;
            color: #67b6ff;
            font-size: 1rem;
            font-style: italic;
        }
        .notif-btn {
            display: flex;
            justify-content: center;
            margin-top: 18px;
        }
        .notif-btn a {
            border-radius: 8px;
            font-weight: 500;
            padding: 8px 28px;
            background: #0d6efd;
            color: #fff;
            border: none;
            transition: background 0.2s;
            text-decoration: none;
        }
        .notif-btn a:hover {
            background: #012152;
            color: #fff;
        }
    </style>
    <script>
        // Redirection automatique après 2 secondes
        setTimeout(function() {
            window.location.href = "home.php";
        }, 2000);

        // Confetti dynamique
        document.addEventListener("DOMContentLoaded", function() {
            const confetti = document.getElementById('notifConfetti');
            for(let i=0; i<18; i++) {
                let span = document.createElement('span');
                span.style.position = 'absolute';
                span.style.left = Math.random()*100 + '%';
                span.style.width = '8px';
                span.style.height = '16px';
                span.style.background = ['#0d6efd','#67b6ff','#ffc107','#20c997','#f87171'][Math.floor(Math.random()*5)];
                span.style.borderRadius = '3px';
                span.style.opacity = 0.7 + Math.random()*0.3;
                span.style.transform = `rotate(${Math.random()*360}deg)`;
                span.style.top = Math.random()*40 + 'px';
                span.style.animation = `fall 1.2s ${Math.random()*0.8}s cubic-bezier(.68,-0.55,.27,1.55) forwards`;
                confetti.appendChild(span);
            }
        });
    </script>
    <style>
        @keyframes fall {
            to { top: 60px; opacity: 0.1; }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="notif-card">
            <div class="notif-confetti" id="notifConfetti"></div>
            <div class="notif-icon">
                <i class="bi bi-check-circle-fill"></i>
            </div>
            <div class="notif-title">Employé supprimé</div>
            <div class="notif-text">
                L'employé a bien été supprimé du système.
            </div>
            <div class="notif-redirect">
                <i class="bi bi-arrow-clockwise"></i> Redirection vers la liste...
            </div>
            <div class="notif-btn">
                <a href="home.php"><i class="bi bi-arrow-left"></i> Retour</a>
            </div>
        </div>
    </div>
</body>
</html>
<?php include("include/footer.php"); ?>