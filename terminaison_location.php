<?php
session_start();

$_SESSION['connecte'] = true;

if (!isset($_SESSION['idAdmin'])) {
    header("Location: connexion.php");
    exit();
}
require_once('config/bd.php');
$id = $_GET['idLocation'];
$stmt = $bdd->prepare("SELECT estTerminee FROM location WHERE idLocation = :id");
$stmt->bindParam(':id', $id, PDO::PARAM_INT);
$stmt->execute();
$check = $stmt->fetch();
if ($check['estTerminee']) {
    header("Location: gestion_locations.php?noend");
}
if (isset($_GET['idLocation'])) {
    $req = $bdd->prepare('SELECT * FROM location WHERE idLocation = ?');
    $req->execute(array($_GET['idLocation']));
    $location = $req->fetch();
    $req->closeCursor();
}
if (isset($_POST['idLocation'])) {
    $req = $bdd->prepare('UPDATE location set estTerminee = 1 WHERE idLocation = ?');
    $req->execute(array($_POST['idLocation']));
    $req->closeCursor();
    $req2 = $bdd->prepare('UPDATE voiture set disponible = 1 WHERE idVoiture = ?');
    $req2->execute(array($_POST['idVoiture']));
    $req2->closeCursor();
    header("Location: gestion_locations.php?end");
    exit();
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Terminaison de location</title>
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
                            ?>
                        </ul>
                    </div>
                </nav>
            </div>
        </div>
    </header>
    <main class="container mt-5">
        <h1 class="display-4 text-uppercase text-center">Terminaison de location</h1>
        <p>Voulez-vous vraiment terminer cette location ?</p>
        <form method="post" action="terminaison_location.php?idLocation=' . <?php $location['idLocation'] ?> . '">
            <input type="hidden" name="idLocation" value="<?php echo $location['idLocation']; ?>">
            <input type="hidden" name="idVoiture" value="<?php echo $location['idVoiture']; ?>">
            <input type="submit" class="btn btn-danger" value="Oui"></input>
            <a href="gestion_locations.php" class="btn btn-dark">Non</a>
        </form>
    </main>
    <?php include('view/footer.php') ?>
</body>

</html>