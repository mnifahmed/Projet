<?php
session_start();

$_SESSION['connecte'] = true;

if (!isset($_SESSION['idAdmin'])) {
    header("Location: connexion.php");
    exit();
}

?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Gestion des clients</title>
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
                                <a href="#" class="nav-link dropdown-toggle active" data-toggle="dropdown">Admin</a>
                                <div class="dropdown-menu rounded-0 m-0">
                                    <a href="gestion_voitures.php" class="dropdown-item">Gestion voitures</a>
                                    <a href="gestion_locations.php" class="dropdown-item">Gestion locations</a>
                                    <a href="gestion_clients.php" class="dropdown-item active">Gestion clients</a>';
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
        <h1 class="display-4 text-uppercase text-center mb-4">Gestion des clients</h1>
        <?php if (isset($_GET["succes"])) {
            echo '<p class="alert alert-success">Client modifié avec succès.</p>';
        } ?>

        <?php
        require_once('config/bd.php');
        $req = $bdd->prepare("SELECT idClient, nom, prenom, cin, adresse, telephone, email, dateCreation, DATE_FORMAT(dernierLogin, '%d-%m-%Y %H:%i') AS dernierLogin FROM client");
        $req->execute();
        if ($req->rowCount() == 0) {
            echo '<p class="alert alert-warning">Il n\'y a aucun client.</p>';
        } else {
            echo '<table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Nom</th>
                            <th>Prénom</th>
                            <th>CIN</th>
                            <th>Adresse</th>
                            <th>Téléphone</th>
                            <th>Email</th>
                            <th>Date de création</th>
                            <th>Date dernier connexion</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>';
        }
        while ($client = $req->fetch()) {
            echo '<tr>';
            echo '<td>' . $client['nom'] . '</td>';
            echo '<td>' . $client['prenom'] . '</td>';
            echo '<td>' . $client['cin'] . '</td>';
            echo '<td>' . $client['adresse'] . '</td>';
            echo '<td>' . $client['telephone'] . '</td>';
            echo '<td>' . $client['email'] . '</td>';
            echo '<td>' . date('d-m-Y H:i', strtotime($client['dateCreation'])) . '</td>';
            echo '<td>' . $client['dernierLogin'] . '</td>';
            echo '<td>';
            echo '<a class="btn btn-danger" href="modification_client.php?idClient=' . $client['idClient'] . '">Modifier</a>';
            echo '</td>';
            echo '</tr>';
        }
        $req->closeCursor();
        ?>
        </tbody>
        </table>
    </main>
    <?php include('view/footer.php') ?>
</body>

</html>