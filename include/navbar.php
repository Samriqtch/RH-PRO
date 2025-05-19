<?php
//importation de connexion à la base de donnée
include("db.php");
?>
<nav class="navbar navbar-expand-lg " style="background-color:rgb(223, 216, 6);">
    <div class="container-fluid">
        <a class="navbar-brand text-white fw-bold" href="home.php"></a>
        <button class="navbar-toggler bg-white" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSearch" aria-controls="navbarSearch" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon" style="filter: invert(1);"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSearch">
            <form class="d-flex ms-auto" role="search" method="get" action="home.php">
                <input class="form-control me-2" type="search" name="q" placeholder="Rechercher un employé..." aria-label="Search" style="min-width:300px;">
                <button class="btn btn-light text-primary fw-bold" type="submit">
                    <i class="bi bi-search"></i> Rechercher
                </button>
            </form>
        </div>
    </div>
</nav>
<!-- Bootstrap Icons CDN -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
