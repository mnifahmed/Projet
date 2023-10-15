<?php
session_start();
if (isset($_SESSION['connecte']) && $_SESSION['connecte'] === true) {

    session_unset();
    session_destroy();

    header('Location: connexion.php?logout');
    exit;
} else {

    session_unset();
    session_destroy();

    header('Location: index.php');
}
