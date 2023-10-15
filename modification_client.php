<?php
session_start();

$_SESSION['connecte'] = true;

if (!isset($_SESSION['idAdmin'])) {
    header("Location: connexion.php");
    exit();
}

require_once('config/bd.php');

if (isset($_GET['idClient'])) {
    $req = $bdd->prepare('SELECT * FROM client WHERE idClient = ?');
    $req->execute(array($_GET['idClient']));
    $client = $req->fetch();
    $req->closeCursor();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    if ($_POST['cin'] != $client['cin']) {
        $cin = $_POST['cin'];
        $id = $client['idClient'];
        $stmt = $bdd->prepare("SELECT * FROM client WHERE cin = :cin AND idClient != :id");
        $stmt->execute(array(':cin' => $cin, ':id' => $id));
        $check = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($check) {
            header("Location: modification_client.php?idClient=" . $client['idClient'] . "&cin");
            exit();
        }
    }

    if ($_POST['telephone'] != $client['telephone']) {
        $tel = $_POST['telephone'];
        $id = $client['idClient'];
        $stmt = $bdd->prepare("SELECT * FROM client WHERE telephone = :tel AND idClient != :id");
        $stmt->bindParam(':tel', $tel, PDO::PARAM_INT);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $check = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($check) {
            header("Location: modification_client.php?idClient=" . $client['idClient'] . "&tel");
            exit();
        }
    }

    $req = $bdd->prepare('UPDATE client SET nom = ?, prenom = ?, cin = ?, adresse = ?, telephone = ? WHERE idClient = ?');
    $req->execute(array($_POST['nom'], $_POST['prenom'], $_POST['cin'], $_POST['adresse'], $_POST['telephone'], $_POST['idClient']));
    $req->closeCursor();

    header("Location: gestion_clients.php?succes");
    exit();
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Modification de client</title>
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
        <h1 class="display-4 text-uppercase text-center mb-4">Modification de client</h1>
        <?php if (isset($_GET['cin'])) {
            echo '<p class="alert alert-danger">Un compte existe déjà avec cette carte d\'identité nationale.</p>';
        } ?>
        <?php if (isset($_GET['tel'])) {
            echo '<p class="alert alert-danger">Un compte existe déjà avec ce numéro de téléphone.</p>';
        } ?>
        <form method="post" action="modification_client.php?idClient=<?php echo $client['idClient']; ?>" enctype="multipart/form-data">
            <input type="hidden" name="idClient" value="<?php echo $client['idClient']; ?>">
            <div class="mb-3">
                <label for="nom" class="form-label"><span style="color: red; font-size: 1.1em;">* </span>Nom :</label>
                <input type="text" name="nom" value="<?php echo $client['nom']; ?>" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="prenom" class="form-label"><span style="color: red; font-size: 1.1em;">* </span>Prénom :</label>
                <input type="text" name="prenom" value="<?php echo $client['prenom']; ?>" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="cin" class="form-label"><span style="color: red; font-size: 1.1em;">* </span>CIN :</label>
                <input type="number" name="cin" value="<?php echo $client['cin']; ?>" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="adresse" class="form-label"><span style="color: red; font-size: 1.1em;">* </span>Adresse :</label>
                <input type="text" name="adresse" value="<?php echo $client['adresse']; ?>" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="telephone" class="form-label"><span style="color: red; font-size: 1.1em;">* </span>Téléphone :</label>
                <input type="number" name="telephone" value="<?php echo $client['telephone']; ?>" class="form-control" required>
            </div>
            <input type="submit" class="btn btn-primary" value="Modifier"></input>
            <a href="gestion_clients.php" class="btn btn-dark">Retourner</a>
        </form>
    </main>
    <?php include('view/footer.php') ?>
</body>

</html>