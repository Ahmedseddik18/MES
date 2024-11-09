<?php
require '../php/db.php'; // Inclure la connexion à la base de données

// Spécifiez le type de contenu de la réponse
header('Content-Type: application/json');

// Récupérer les données POST
$data = json_decode(file_get_contents("php://input"), true);

// Vérification des paramètres
$id = $data['id'] ?? null;
$etat = $data['etat'] ?? null;
$dateAujourdHui = date('Y-m-d');

// Vérifier si les paramètres obligatoires sont présents
if ($id === null || $etat === null) {
    echo json_encode(['success' => false, 'message' => 'Paramètres manquants']);
    exit();
}

// Initialiser la requête SQL
$sql = "UPDATE db SET etat = :etat";

// Définir les colonnes à mettre à jour en fonction de l'état
if ($etat == 'En attente') {
    $sql .= ", debut = NULL, fin = NULL";
} elseif ($etat == 'En cours') {
    $sql .= ", debut = :debut, fin = NULL";
    $debut = $dateAujourdHui;
} elseif ($etat == 'Termine') {
    $sql .= ", fin = :fin";
    $fin = $dateAujourdHui;
}

// Ajouter la clause WHERE pour identifier la tâche
$sql .= " WHERE id = :id";

// Préparer la requête
try {
    $stmt = $conn->prepare($sql);

    // Lier les paramètres
    $stmt->bindParam(':etat', $etat);
    $stmt->bindParam(':id', $id);

    // Lier les paramètres conditionnels en fonction de l'état
    if ($etat == 'En cours') {
        $stmt->bindParam(':debut', $debut);
    } elseif ($etat == 'Termine') {
        $stmt->bindParam(':fin', $fin);
    }

    // Exécuter la requête
    $stmt->execute();

    // Retourner un message de succès en JSON
    echo json_encode(['success' => true, 'message' => 'Mise à jour réussie']);
} catch (PDOException $e) {
    // Gérer les erreurs et retourner un message d'erreur en JSON
    echo json_encode(['success' => false, 'message' => 'Erreur lors de la mise à jour : ' . $e->getMessage()]);
}
?>
