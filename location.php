<?php

ob_start();

// Vérification de la connexion de l'utilisateur
session_start();

$_SESSION['connecte'] = true;

if (!isset($_SESSION['idClient']) && !isset($_SESSION['idAdmin'])) {
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
    <title>Location</title>
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
        <h1 class="display-4 text-uppercase text-center mb-4">Location de voiture</h1>
        <?php if (isset($_GET['cin'])) {
            echo '<p class="alert alert-danger">Il n\'existe aucun compte avec cette carte d\'identité nationale.</p>';
        } ?>
        <?php
        if (isset($_GET["datefin"])) {
            echo "<p class='alert alert-danger'>La date de fin doit être postérieure à la date de début.</p>";
        }

        // Récupération de l'id de la voiture
        $idVoiture = (int) $_GET['idVoiture'];

        if (empty($idVoiture)) {
            header('Location: voitures.php');
            exit();
        }

        require_once('config/bd.php');

        // Récupération de la voiture
        $requete = $bdd->prepare('SELECT * FROM voiture WHERE idVoiture = ?');
        $requete->execute(array($idVoiture));
        if ($requete->rowCount() == 0) {
            header('Location: voitures.php');
            exit();
        }
        $voiture = $requete->fetch();
        $requete->closeCursor();

        // Vérification de la disponibilité de la voiture
        if (!$voiture['disponible']) {
            echo '<p class="alert alert-warning">Cette voiture n\'est pas disponible pour le moment.</p>';
        } else {

            // Vérification de la soumission du formulaire
            if (isset($_POST['dateDebut']) && isset($_POST['dateFin'])) {
                // Récupération des dates
                if (isset($_POST['cin'])) {
                    $cin = $_POST['cin'];
                }
                $dateDebut = $_POST['dateDebut'];
                $dateFin = $_POST['dateFin'];

                if (isset($cin)) {
                    $stmt = $bdd->prepare("SELECT idClient FROM client WHERE cin = :cin");
                    $stmt->execute(array(':cin' => $cin));
                    $check = $stmt->fetch(PDO::FETCH_ASSOC);
                    $idClient = $check['idClient'];
                    if (!$check) {
                        header("Location: location.php?idVoiture=" . $voiture['idVoiture'] . "&cin");
                        exit();
                    }
                }

                // Vérification de la validité des dates
                if ($dateDebut >= $dateFin) {
                    header('Location: location.php?idVoiture=' . $voiture['idVoiture'] . '&datefin');
                } else {
                    // Calcul du coût total de la location
                    $nbJours = (strtotime($dateFin) - strtotime($dateDebut)) / (60 * 60 * 24);
                    $coutTotal = $nbJours * $voiture['prixLocation'];

                    // Insertion de la location dans la base de données
                    if (!isset($cin)) {
                        $requete = $bdd->prepare('INSERT INTO location (idClient, idVoiture, dateDebut, dateFin, coutTotal) VALUES (?, ?, ?, ?, ?)');
                        $requete->execute(array($_SESSION['idClient'], $voiture['idVoiture'], $dateDebut, $dateFin, $coutTotal));
                        $requete->closeCursor();

                        $requete = $bdd->prepare('UPDATE voiture SET disponible = 0 WHERE idVoiture = ?');
                        $requete->execute(array($idVoiture));
                        $requete->closeCursor();

                        echo '<p class="alert alert-success">Votre location a été bien confirmée.</p>';
                        echo '<a href="reservations.php" class="btn btn-primary">Voir mes réservations</a>';
                    } else {
                        $requete = $bdd->prepare('INSERT INTO location (idClient, idVoiture, dateDebut, dateFin, coutTotal) VALUES (?, ?, ?, ?, ?)');
                        $requete->execute(array($idClient, $voiture['idVoiture'], $dateDebut, $dateFin, $coutTotal));
                        $requete->closeCursor();

                        $requete = $bdd->prepare('UPDATE voiture SET disponible = 0 WHERE idVoiture = ?');
                        $requete->execute(array($idVoiture));
                        $requete->closeCursor();

                        echo '<p class="alert alert-success">La location a été bien confirmée.</p>';
                        echo '<a href="gestion_locations.php" class="btn btn-primary">Gérer les locations</a>';
                    }
                }
            } else {

                echo '<p>Veuillez remplir le formulaire ci-dessous pour louer cette voiture :</p>';

                echo '<form action="location.php?idVoiture=' . $voiture['idVoiture'] . '" method="post">';
                if (isset($_SESSION['idAdmin'])) {
                    echo '<div class="mb-3">';
                    echo '<label for="cin" class="form-label"><span style="color: red; font-size: 1.1em;">* </span>CIN :</label>';
                    echo '<input type="number" id="cin" name="cin" class="form-control" required>';
                    echo '</div>';
                }
                echo '<div class="mb-3">';
                echo '<label for="dateDebut" class="form-label"><span style="color: red; font-size: 1.1em;">* </span>Date de début :</label>';
                echo '<input type="datetime-local" id="dateDebut" name="dateDebut" class="form-control" required>';
                echo '</div>';
                echo '<div class="mb-3">';
                echo '<label for="dateFin" class="form-label"><span style="color: red; font-size: 1.1em;">* </span>Date de fin :</label>';
                echo '<input type="datetime-local" id="dateFin" name="dateFin" class="form-control" required>';
                echo '</div>';
                echo '<input type="submit" class="btn btn-primary" value="Confirmer la location"></input>';
                echo '<a href="voitures.php" class="btn btn-dark ml-1">Retourner</a>';
                echo '</form>';
            }
        }
        ?>
    </main>
    <?php include('view/footer.php') ?>
</body>

</html>

<?php
ob_end_flush();
?>