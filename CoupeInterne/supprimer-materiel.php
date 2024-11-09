<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../../index.php");
    exit();
}
include("../php/db.php");
include("../php/fonction.php");

// Récupérer les données à insérer (pour cet exemple, nous utilisons des données simulées)
$id = $_GET['id'];


// Sélectionner les champs nécessaires

$fromTables = 'materiel';
$whereConditions = "id = '$id'";


// Préparer et exécuter la requête pour récupérer les données
$result = delete($conn, $fromTables, $whereConditions);

// Vérifier si la requête a retourné un résultat
if ($result) {
 header('Location: list-materiel.php');
            exit;

} 






?>