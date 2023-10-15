<?php
$hostname = "localhost";
$username = "root";
$password = "";
$dbname = "bdd_ctc";
try {
    $bdd = new PDO("mysql:host=$hostname; dbname=$dbname", "$username", "$password");
    $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Erreur: " . $e->getMessage();
}
