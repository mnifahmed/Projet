<?php
session_start();

$_SESSION['connecte'] = true;

if (!isset($_SESSION['idClient'])) {
    header('Location: connexion.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Mes réservations</title>
    <?php include('view/header.php') ?>
</head>

<body>
    <header>
        <!-- Topbar Start -->
        <div class="container-fluid bg-dark py-3 px-lg-5 d-none d-lg-block">
            <div class="row">
                <div class="col-md-6 text-center text-lg-left mb-2 mb-lg-0">
                    <div class="d-inline-flex align-items-center">
                        <a class="text-body pr-3" href=""><i class="fa fa-phone mr-2"></i>24.74.29.12</a>
                        <span class="text-body">|</span>
                        <a class="text-body px-3" href=""><i class="fa fa-envelope mr-2"></i>contact@CTC.com</a>
                    </div>
                </div>
                <div class="col-md-6 text-center text-lg-right">
                    <div class="d-inline-flex align-items-center">
                        <a class="text-body px-3" href="">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a class="text-body px-3" href="">
                            <i class="fab fa-twitter"></i>
                        </a>
                        <a class="text-body px-3" href="">
                            <i class="fab fa-linkedin-in"></i>
                        </a>
                        <a class="text-body px-3" href="">
                            <i class="fab fa-instagram"></i>
                        </a>
                        <a class="text-body pl-3" href="">
                            <i class="fab fa-youtube"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <!-- Topbar End -->

        <!-- Navbar Start -->
        <div class="container-fluid position-relative nav-bar p-0">
            <div class="position-relative px-lg-5" style="z-index: 9">
                <nav class="navbar navbar-expand-lg bg-secondary navbar-dark py-3 py-lg-0 pl-3 pl-lg-5">
                    <a class="navbar-brand" href="index.php"><img height="100" width="150" src="img/logo.png" alt="logo"></a>
                    <button type="button" class="navbar-toggler" data-toggle="collapse" data-target="#navbarCollapse">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse justify-content-between px-3" id="navbarCollapse">
                        <ul class="navbar-nav ml-auto">
                            <a href="index.php" class="nav-item nav-link">Accueil</a>
                            <a href="voitures.php" class="nav-item nav-link">Voitures</a>
                            <a href="contact.php" class="nav-item nav-link">Contact</a>
                            <div class="nav-item dropdown">
                                <a href="#" class="nav-link dropdown-toggle active" data-toggle="dropdown">Compte</a>
                                <div class="dropdown-menu rounded-0 m-0">
                                    <a href="reservations.php" class="dropdown-item active">Mes réservations</a>
                                    <a href="profil.php" class="dropdown-item">Profil</a>
                                    <a href="deconnexion.php" class="dropdown-item">Déconnecter</a>
                                </div>
                            </div>
                        </ul>
                    </div>
                </nav>
            </div>
        </div>
    </header>
    <main class="container mt-5">
        <h1 class="display-4 text-uppercase text-center mb-4">Mes réservations</h1>
        <?php
        require_once('config/bd.php');
        $req = $bdd->prepare("SELECT * FROM location WHERE idClient = ?");
        $req->execute(array($_SESSION['idClient']));
        while ($location = $req->fetch()) {
            $req2 = $bdd->prepare('SELECT * FROM voiture WHERE idVoiture = ?');
            $req2->execute(array($location['idVoiture']));
            $voiture = $req2->fetch();
            $req2->closeCursor();

            echo '<div class="card mb-3">';
            echo '<div class="row g-0">';
            echo '<div class="col-md-4">';
            echo '<img src="' . $voiture['image'] . '" alt="' . $voiture['marque'] . ' ' . $voiture['modele'] . '" class="img-fluid">';
            echo '</div>';
            echo '<div class="col-md-8">';
            echo '<div class="card-body">';
            echo '<h2 class="card-title">' . $voiture['marque'] . ' ' . $voiture['modele'] . '</h2>';
            echo '<p class="card-text">Date de début : ' . date('d-m-Y H:i', strtotime($location['dateDebut'])) . '</p>';
            echo '<p class="card-text">Date de fin : ' . date('d-m-Y H:i', strtotime($location['dateFin'])) . '</p>';
            echo '<p class="card-text">Prix total : ' . $location['coutTotal'] . ' TND</p>';
            if ($location['estTerminee']) {
                echo '<p class="card-text">Statut : Terminée</p>';
            } else {
                echo '<p class="card-text">Statut : En cours</p>';
            }
            echo '</div>';
            echo '</div>';
            echo '</div>';
            echo '</div>';
        }
        if ($req->rowCount() == 0) {
            echo '<p class="alert alert-warning">Vous n\'avez pas de réservations.</p>';
        }
        $req->closeCursor();
        ?>
    </main>
    <?php include('view/footer.php') ?>
</body>

</html>