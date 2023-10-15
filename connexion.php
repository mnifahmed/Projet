<?php
session_start();

if ((isset($_SESSION['idClient']) || isset($_SESSION['idAdmin'])) && !isset($_SESSION['login_succes'])) {
    header('Location: index.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    require_once 'config/bd.php';
    $email = $_POST['email'];
    $motdepasse = $_POST['motdepasse'];

    // Rechercher le client
    $stmt = $bdd->prepare("SELECT idClient, motdepasse FROM client WHERE email = ?");
    $stmt->execute([$email]);
    $client = $stmt->fetch(PDO::FETCH_ASSOC);

    // Vérifier le mot de passe et créer la session
    if ($client && password_verify($motdepasse, $client['motdepasse'])) {
        $_SESSION['idClient'] = $client['idClient'];
        $date = $bdd->prepare("UPDATE client SET dernierLogin = NOW() WHERE idClient = ?");
        $date->execute([$client['idClient']]);
        $_SESSION['login_succes'] = true;
        header('Location: connexion.php');
        exit();
    } else {
        $erreur = '<p class="alert alert-danger">Email ou mot de passe incorrect.</p>';
    }

    // Rechercher l'administrateur
    if (!$client) {
        $stmt = $bdd->prepare("SELECT idAdmin, motdepasse FROM administrateur WHERE email = ?");
        $stmt->execute([$email]);
        $admin = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($admin && password_verify($motdepasse, $admin['motdepasse'])) {
            $_SESSION['idAdmin'] = $admin['idAdmin'];
            $date = $bdd->prepare("UPDATE administrateur SET dernierLogin = NOW() WHERE idAdmin = ?");
            $date->execute([$admin['idAdmin']]);
            $_SESSION['login_succes'] = true;
            header('Location: connexion.php');
            exit();
        } else {
            $erreur = '<p class="alert alert-danger">Email ou mot de passe incorrect.</p>';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Connexion</title>
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
                            <a class="nav-item nav-link" href="inscription.php">Inscription</a>
                            <a class="nav-item nav-link active" href="connexion.php">Connexion</a>
                        </ul>
                    </div>
                </nav>
            </div>
        </div>
    </header>
    <main class="container mt-5">
        <h1 class="display-4 text-uppercase text-center mb-4">Connexion</h1>
        <div id="succes-message" class="alert alert-success" style="display:none;"></div>
        <?php if (isset($_GET["succes"])) {
            echo '<p class="alert alert-success">Inscription effectuée. Vous pouvez se connecter maintenant.</p>';
        } ?>
        <?php if (isset($erreur)) {
            echo $erreur;
        } ?>
        <?php if (isset($_GET["logout"])) {
            echo '<p class="alert alert-success">Vous avez été déconnecté.</p>';
        } ?>
        <form action="connexion.php" method="post">
            <div class="mb-3">
                <label for="email" class="form-label"><span style="color: red; font-size: 1.1em;">* </span>Email :</label>
                <input type="email" id="email" name="email" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="motdepasse" class="form-label"><span style="color: red; font-size: 1.1em;">* </span>Mot de passe :</label>
                <input type="password" id="motdepasse" name="motdepasse" class="form-control" required>
            </div>
            <input type="submit" value="Se connecter" class="btn btn-primary">
        </form>
        <p class="mt-3">Pas encore inscrit ? <a href="inscription.php">Inscrivez-vous</a></p>
    </main>
    <?php include('view/footer.php') ?>
    <script>
        <?php if (isset($_SESSION['login_succes']) && $_SESSION['login_succes']) { ?>
            document.getElementById('succes-message').innerHTML = 'Connexion réussie. Redirection en cours...';
            document.getElementById('succes-message').style.display = 'block';
            setTimeout(function() {
                window.location.href = 'index.php';
            }, 3000);
            <?php unset($_SESSION['login_succes']); ?>
        <?php } ?>
    </script>
</body>

</html>