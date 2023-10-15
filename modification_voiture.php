<?php
session_start();

$_SESSION['connecte'] = true;

if (!isset($_SESSION['idAdmin'])) {
    header("Location: connexion.php");
    exit();
}

require_once('config/bd.php');

if (isset($_GET['idVoiture'])) {
    $req = $bdd->prepare('SELECT * FROM voiture WHERE idVoiture = ?');
    $req->execute(array($_GET['idVoiture']));
    $voiture = $req->fetch();
    $req->closeCursor();
}

$id = $_GET['idVoiture'];
$stmt = $bdd->prepare("SELECT disponible FROM voiture WHERE idVoiture = ?");
$stmt->execute([$id]);
$check = $stmt->fetch();
if (!$check['disponible']) {
    header("Location: gestion_voitures.php?nomodify");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $matricule = $_POST['matricule'];
    $req = $bdd->prepare("SELECT * FROM voiture WHERE matricule = ? AND idVoiture != ?");
    $req->execute([$matricule, $id]);
    $check = $req->fetch(PDO::FETCH_ASSOC);
    if ($check) {
        header("Location: modification_voiture.php?idVoiture=" . $voiture['idVoiture'] . "&exists");
        exit();
    }

    if ($_FILES["image"]["error"] == 0) {
        move_uploaded_file($_FILES["image"]["tmp_name"], "img/" . $_FILES["image"]["name"]);
        $file = "img/" . $_FILES["image"]["name"];
        $req = $bdd->prepare('UPDATE voiture SET matricule = ?, marque = ?, modele = ?, annee = ?, couleur = ?, prixLocation = ?, image = ? WHERE idVoiture = ?');
        $req->execute(array($_POST['matricule'], $_POST['marque'], $_POST['modele'], $_POST['annee'], $_POST['couleur'], $_POST['prixLocation'], $file, $_POST['idVoiture']));
        $req->closeCursor();
    } else {
        $req = $bdd->prepare('UPDATE voiture SET matricule = ?, marque = ?, modele = ?, annee = ?, couleur = ?, prixLocation = ? WHERE idVoiture = ?');
        $req->execute(array($_POST['matricule'], $_POST['marque'], $_POST['modele'], $_POST['annee'], $_POST['couleur'], $_POST['prixLocation'], $_POST['idVoiture']));
        $req->closeCursor();
    }

    header("Location: gestion_voitures.php?modify");
    exit();
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Modification de voiture</title>
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
        <h1 class="display-4 text-uppercase text-center mb-4">Modification de voiture</h1>
        <?php if (isset($_GET['exists'])) {
            echo '<p class="alert alert-danger">Une voiture existe déjà avec cette matricule.</p>';
        } ?>
        <form method="post" action="modification_voiture.php?idVoiture=<?php echo $voiture['idVoiture']; ?>" enctype="multipart/form-data">
            <input type="hidden" name="idVoiture" value="<?php echo $voiture['idVoiture']; ?>">
            <div class="mb-3">
                <label for="matricule" class="form-label"><span style="color: red; font-size: 1.1em;">* </span>Matricule :</label>
                <input type="text" name="matricule" value="<?php echo $voiture['matricule']; ?>" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="marque" class="form-label"><span style="color: red; font-size: 1.1em;">* </span>Marque :</label>
                <input type="text" name="marque" value="<?php echo $voiture['marque']; ?>" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="modele" class="form-label"><span style="color: red; font-size: 1.1em;">* </span>Modèle :</label>
                <input type="text" name="modele" value="<?php echo $voiture['modele']; ?>" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="annee" class="form-label"><span style="color: red; font-size: 1.1em;">* </span>Année :</label>
                <input type="number" name="annee" value="<?php echo $voiture['annee']; ?>" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="couleur" class="form-label"><span style="color: red; font-size: 1.1em;">* </span>Couleur :</label>
                <input type="text" name="couleur" value="<?php echo $voiture['couleur']; ?>" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="prixLocation" class="form-label"><span style="color: red; font-size: 1.1em;">* </span>Prix de location :</label>
                <input type="number" name="prixLocation" step="0.01" value="<?php echo $voiture['prixLocation']; ?>" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="image" class="form-label">Image :</label>
                <input type="file" name="image" class="form-control">
            </div>
            <input type="submit" class="btn btn-primary" value="Modifier"></input>
            <a href="gestion_voitures.php" class="btn btn-dark">Retourner</a>
        </form>
    </main>
    <?php include('view/footer.php') ?>
</body>

</html>