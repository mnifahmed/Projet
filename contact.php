<?php
session_start();

$_SESSION['connecte'] = false;

if (isset($_SESSION['idAdmin'])) {
    header('Location: index.php');
}
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
                            <a href="voitures.php" class="nav-item nav-link">Voitures</a>
                            <a href="contact.php" class="nav-item nav-link active">Contact</a>
                            <?php
                            if (isset($_SESSION['idClient'])) {
                                echo '<div class="nav-item dropdown">
                                    <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">Compte</a>
                                    <div class="dropdown-menu rounded-0 m-0">
                                        <a href="reservations.php" class="dropdown-item">Mes réservations</a>
                                        <a href="profil.php" class="dropdown-item">Profil</a>
                                        <a href="deconnexion.php" class="dropdown-item">Déconnecter</a>
                                    </div>
                                </div>';
                            } else {
                                echo '<a class="nav-item nav-link" href="inscription.php">Inscription</a>
                                <a class="nav-item nav-link" href="connexion.php">Connexion</a>';
                            } ?>
                        </ul>
                    </div>
                </nav>
            </div>
        </div>
    </header>
    <main class="container mt-5">
        <h1 class="display-4 text-uppercase text-center mb-5">Contactez-Nous</h1>
        <div class="row">
            <div class="col-lg-7 mb-2">
                <div class="contact-form bg-light mb-4" style="padding: 30px;">
                    <form>
                        <div class="row">
                            <div class="col-6 form-group">
                                <input type="text" class="form-control p-4" placeholder="Votre Nom" required="required">
                            </div>
                            <div class="col-6 form-group">
                                <input type="email" class="form-control p-4" placeholder="Votre Email" required="required">
                            </div>
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control p-4" placeholder="Sujet" required="required">
                        </div>
                        <div class="form-group">
                            <textarea class="form-control py-3 px-4" rows="5" placeholder="Message" required="required"></textarea>
                        </div>
                        <div>
                            <button class="btn btn-primary py-3 px-5" type="submit">Envoyer Message</button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="col-lg-5 mb-2">
                <div class="bg-secondary d-flex flex-column justify-content-center px-5 mb-4" style="height: 300px;">
                    <div class="d-flex mt-3 mb-3">
                        <i class="fa fa-2x fa-map-marker-alt text-primary flex-shrink-0 mr-3"></i>
                        <div class="mt-n1">
                            <h5 class="text-light">Siège Social</h5>
                            <p>40 Rue Tazarka, Sfax, Tunisie</p>
                        </div>
                    </div>
                    <div class="d-flex mb-3">
                        <i class="fa fa-2x fa-envelope-open text-primary flex-shrink-0 mr-3"></i>
                        <div class="mt-n1">
                            <h5 class="text-light">Service Client</h5>
                            <p>client@ctc.com</p>
                        </div>
                    </div>
                    <div class="d-flex">
                        <i class="fa fa-2x fa-envelope-open text-primary flex-shrink-0 mr-3"></i>
                        <div class="mt-n1">
                            <h5 class="text-light">Retour & Remboursement</h5>
                            <p class="m-0">remboursement@ctc.com</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <?php include('view/footer.php') ?>
</body>

</html>