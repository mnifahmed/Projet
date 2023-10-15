<?php
session_start();

$_SESSION['connecte'] = false;

?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Voitures</title>
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
                            <a href="voitures.php" class="nav-item nav-link active">Voitures</a>
                            <?php
                            if (isset($_SESSION['idClient'])) {
                                echo '<a href="contact.php" class="nav-item nav-link">Contact</a>
                                <div class="nav-item dropdown">
                                    <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">Compte</a>
                                    <div class="dropdown-menu rounded-0 m-0">
                                        <a href="reservations.php" class="dropdown-item">Mes réservations</a>
                                        <a href="profil.php" class="dropdown-item">Profil</a>
                                        <a href="deconnexion.php" class="dropdown-item">Déconnecter</a>
                                    </div>
                                </div>';
                            } elseif (isset($_SESSION['idAdmin'])) {
                                echo '<div class="nav-item dropdown">
                                <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">Admin</a>
                                <div class="dropdown-menu rounded-0 m-0">
                                    <a href="gestion_voitures.php" class="dropdown-item">Gestion voitures</a>
                                    <a href="gestion_locations.php" class="dropdown-item">Gestion locations</a>
                                    <a href="gestion_clients.php" class="dropdown-item">Gestion clients</a>';
                                if ($_SESSION['idAdmin'] == 1) {
                                    echo '<a href="gestion_admins.php" class="dropdown-item">Gestion admins</a>';
                                }
                                echo '</div>
                            </div>
                                <div class="nav-item dropdown">
                                    <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">Compte</a>
                                    <div class="dropdown-menu rounded-0 m-0">
                                        <a href="profil.php" class="dropdown-item">Profil</a>
                                        <a href="deconnexion.php" class="dropdown-item">Déconnecter</a>
                                    </div>
                                </div>';
                            } else {
                                echo '<a href="contact.php" class="nav-item nav-link">Contact</a>
                                <a class="nav-item nav-link" href="inscription.php">Inscription</a>
                                <a class="nav-item nav-link" href="connexion.php">Connexion</a>';
                            } ?>
                        </ul>
                    </div>
                </nav>
            </div>
        </div>
    </header>
    <main class="container mt-5">
        <h1 class="display-4 text-uppercase text-center mb-5">Nos voitures disponibles</h1>
        <div class="row">
            <?php
            require_once('config/bd.php');
            $req = $bdd->prepare('SELECT * FROM voiture WHERE disponible = 1');
            $req->execute();
            while ($voiture = $req->fetch()) {
                echo '<div class="col-lg-4 col-md-6 mb-2">';
                echo '<div class="rent-item mb-4">';
                echo '<img class="img-fluid mb-4" src="' . $voiture['image'] . '" alt="' . $voiture['marque'] . ' ' . $voiture['modele'] . '">';
                echo '<h4 class="text-uppercase mb-4">' . $voiture['marque'] . ' ' . $voiture['modele'] . '</h4>';
                echo '<div class="d-flex justify-content-center mb-4">';
                echo '<div class="px-2">';
                echo '<i class="fa fa-car text-primary mr-1"></i>';
                echo '<span>' . $voiture['annee'] . '</span>';
                echo '</div>';
                echo '<div class="px-2 border-left">';
                echo '<i class="fa fa-paint-roller text-primary mr-1"></i>';
                echo '<span>' . $voiture['couleur'] . '</span>';
                echo '</div>';
                echo '<div class="px-2 border-left">';
                echo '<i class="fa-solid fa-money-bill text-primary mr-1"></i>';
                echo '<span>' . $voiture['prixLocation'] . ' DT/j</span>';
                echo '</div>';
                echo '</div>';
                if (isset($_SESSION['idClient'])) {
                    echo '<a class="btn btn-primary px-3" href="location.php?idVoiture=' . $voiture['idVoiture'] . '">Louer</a>';
                } elseif (isset($_SESSION['idAdmin'])) {
                    echo '<a class="btn btn-primary px-3" href="location.php?idVoiture=' . $voiture['idVoiture'] . '">Louer pour un client</a>';
                } else {
                    echo '<a class="btn btn-primary px-3" href="connexion.php">Se connecter pour louer</a>';
                }
                echo '</div>';
                echo '</div>';
            }
            echo '</div>';
            if ($req->rowCount() == 0) {
                echo '<p class="alert alert-warning">Il n\'y a aucune voiture disponible pour le moment.</p>';
            }
            $req->closeCursor();
            ?>
    </main>
    <?php include('view/footer.php') ?>
</body>

</html>