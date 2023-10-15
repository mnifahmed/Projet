<?php
session_start();

if (isset($_SESSION['idClient']) || isset($_SESSION['idAdmin'])) {
    header('Location: index.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    require_once('config/bd.php');

    $email = $_POST['email'];
    $req = $bdd->prepare("SELECT * FROM client WHERE email = :email");
    $req->execute(array('email' => $email));
    $check = $req->fetch(PDO::FETCH_ASSOC);
    if ($check) {
        header("Location: inscription.php?exists");
        exit();
    }

    $cin = $_POST['cin'];
    $stmt = $bdd->prepare("SELECT * FROM client WHERE cin = :cin");
    $stmt->bindParam(':cin', $cin, PDO::PARAM_INT);
    $stmt->execute();
    $check = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($check) {
        header('Location: inscription.php?cin');
        exit();
    }
    $tel = $_POST['telephone'];
    $stmt = $bdd->prepare("SELECT * FROM client WHERE telephone = :tel");
    $stmt->execute(['tel' => $tel]);
    $check = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($check) {
        header('Location: inscription.php?tel');
        exit();
    }

    $req = $bdd->prepare('INSERT INTO client(nom, prenom, cin, adresse, telephone, email, motdepasse) VALUES (?, ?, ?, ?, ?, ?, ?)');
    $req->execute(array($_POST['nom'], $_POST['prenom'], $_POST['cin'], $_POST['adresse'], $_POST['telephone'], $_POST['email'], password_hash($_POST['motdepasse'], PASSWORD_DEFAULT)));
    $req->closeCursor();

    header("Location: connexion.php?succes");
    exit();
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Inscription</title>
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
                            <a class="nav-item nav-link" href="voitures.php">Voitures</a>
                            <a href="contact.php" class="nav-item nav-link">Contact</a>
                            <a class="nav-item nav-link active" href="inscription.php">Inscription</a>
                            <a class="nav-item nav-link" href="connexion.php">Connexion</a>
                        </ul>
                    </div>
                </nav>
            </div>
        </div>
    </header>
    <main class="container mt-5">
        <h1 class="display-4 text-uppercase text-center mb-4">Inscription</h1>
        <?php if (isset($_GET['exists'])) {
            echo '<p class="alert alert-danger">Un compte existe déjà avec cette adresse mail.</p>';
        } ?>
        <?php if (isset($_GET['cin'])) {
            echo '<p class="alert alert-danger">Un compte existe déjà avec cette carte d\'identité nationale.</p>';
        } ?>
        <?php if (isset($_GET['tel'])) {
            echo '<p class="alert alert-danger">Un compte existe déjà avec ce numéro de téléphone.</p>';
        } ?>
        <form method="post" action="inscription.php">
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="nom"><span style="color: red; font-size: 1.1em;">* </span>Nom :</label>
                    <input type="text" class="form-control" name="nom" required>
                </div>
                <div class="form-group col-md-6">
                    <label for="prenom"><span style="color: red; font-size: 1.1em;">* </span>Prénom :</label>
                    <input type="text" class="form-control" name="prenom" required>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="cin"><span style="color: red; font-size: 1.1em;">* </span>CIN :</label>
                    <input type="number" class="form-control" name="cin" required>
                </div>
                <div class="form-group col-md-6">
                    <label for="adresse"><span style="color: red; font-size: 1.1em;">* </span>Adresse :</label>
                    <input type="text" class="form-control" name="adresse" required>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="telephone"><span style="color: red; font-size: 1.1em;">* </span>Téléphone :</label>
                    <input type="number" class="form-control" name="telephone" required>
                </div>
                <div class="form-group col-md-6">
                    <label for="email"><span style="color: red; font-size: 1.1em;">* </span>Email :</label>
                    <input type="email" class="form-control" name="email" required>
                </div>
            </div>
            <div class="form-group">
                <label for="motdepasse"><span style="color: red; font-size: 1.1em;">* </span>Mot de passe :</label>
                <input type="password" class="form-control" name="motdepasse" required>
            </div>
            <div class="form-group">
                <input type="checkbox" name="termsconditions" required>
                <label for="termsconditions"><span style="color: red; font-size: 1.1em;">* </span>J'ai lu et j'accepte les termes et conditions.</label>
            </div>
            <input type="submit" class="btn btn-primary" value="S'inscrire">
        </form>
        <p class="mt-3">Vous avez déjà un compte ? <a href="connexion.php">Se connecter</a></p>
    </main>
    <?php include('view/footer.php') ?>
</body>

</html>