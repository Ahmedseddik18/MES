<?php
require '../php/db.php'; // Include your database connection
header('Content-Type: application/json');

// Display errors for development
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Retrieve POST data
$id = isset($_POST['id']) ? $_POST['id'] : null;
$article = $_POST['article'];
$commande = $_POST['commande'];
$etat = $_POST['etat'];
$quantiteReelle = isset($_POST['quantiteReelle']) ? $_POST['quantiteReelle'] : null;
$dateFin = isset($_POST['dateFin']) ? $_POST['dateFin'] : null;

$errorMessages = [];

// Initialize the SQL query
$sql = "UPDATE db 
        SET etat = :etat, 
            fin = :dateFin, 
            quantitereelle = :quantiteReelle 
        WHERE article = :article 
          AND commande = :commande";

// Prepare the statement
$stmt = $conn->prepare($sql);

// Bind parameters
$stmt->bindParam(':etat', $etat, PDO::PARAM_STR);
$stmt->bindParam(':article', $article, PDO::PARAM_STR);
$stmt->bindParam(':commande', $commande, PDO::PARAM_STR);
$stmt->bindParam(':quantiteReelle', $quantiteReelle, PDO::PARAM_INT);
$stmt->bindParam(':dateFin', $dateFin, PDO::PARAM_STR); // Assuming date is in 'YYYY-MM-DD' format

// Execute the query and check the result
if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Tâche mise à jour et insérée avec succès dans arret.']);
} else {
    echo json_encode(['success' => false, 'error' => 'Erreur lors de la mise à jour: ' . implode(', ', $stmt->errorInfo())]);
}
?>
