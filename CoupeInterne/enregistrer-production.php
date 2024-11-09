<?php
// Inclure votre connexion PDO à la base de données ici
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require '../php/db.php';  // Vérifie le chemin

// Tester la connexion à la base de données
if (!$conn) {
    echo json_encode(['success' => false, 'message' => 'Connexion à la base de données échouée.']);
    exit;
}

header('Content-Type: application/json'); // Indiquer que la réponse est en JSON
try {
    // Décoder les données JSON envoyées par le client
    $data = json_decode(file_get_contents('php://input'), true);

    // Vérifier si toutes les données nécessaires sont présentes
    if (isset($data['commande'], $data['article'], $data['phase'], $data['sub'], $data['codeMatelas'], 
              $data['quantite'], $data['operateur1'], $data['heures'], $data['note'], $data['tabl'])) {

        // Récupérer les valeurs
        $commande = $data['commande'];
        $article = $data['article'];
        $phase = $data['phase'];
        $sub = $data['sub'];
        $codeMatelas = $data['codeMatelas'];
        $quantite = $data['quantite'];
        $operateur1 = $data['operateur1'];
        $operateur2 = isset($data['operateur2']) ? $data['operateur2'] : null; // Vérifier si operateur2 est fourni
        $heures = $data['heures'];
        $note = $data['note'];
        $tabl = $data['tabl'];
        $dateAujourdHui = date('Y-m-d');

        // Préparer et exécuter la requête d'insertion
        $stmt = $conn->prepare("INSERT INTO production (commande, article, phase, sub, codeMatelas, quantite, operateur1, operateur2, HR, note, date, tabl) 
                               VALUES (:commande, :article, :phase, :sub, :codeMatelas, :quantite, :operateur1, :operateur2, :heures, :note, :date, :tabl)");

        // Lier les paramètres
        $stmt->bindParam(':commande', $commande);
        $stmt->bindParam(':article', $article);
        $stmt->bindParam(':phase', $phase);
        $stmt->bindParam(':sub', $sub);
        $stmt->bindParam(':codeMatelas', $codeMatelas);
        $stmt->bindParam(':quantite', $quantite);
        $stmt->bindParam(':operateur1', $operateur1);
        $stmt->bindParam(':operateur2', $operateur2);
        $stmt->bindParam(':heures', $heures);
        $stmt->bindParam(':note', $note);
        $stmt->bindParam(':date', $dateAujourdHui);
        $stmt->bindParam(':tabl', $tabl);

        // Exécuter la requête d'insertion et vérifier le résultat
if ($stmt->execute()) {
    // Si l'insertion réussit, exécuter la requête de mise à jour
    $updateStmt = $conn->prepare("UPDATE planification SET etat = 'Termine' WHERE codeMatelas = :codeMatelas");
    $updateStmt->bindParam(':codeMatelas', $codeMatelas);

    if ($updateStmt->execute()) {
        // Si la mise à jour réussit, mettre à jour l'état dans la table commande en fonction de la phase
        if ($phase === 'relaxation') {
            $commandeUpdateStmt = $conn->prepare("UPDATE commande SET etatRelaxation = 'Termine' WHERE codeMatelas = :codeMatelas");
        } elseif ($phase === 'matelassage') {
            $commandeUpdateStmt = $conn->prepare("UPDATE commande SET etatMatelassage = 'Termine' WHERE codeMatelas = :codeMatelas");
        } elseif ($phase === 'coupe') {
            $commandeUpdateStmt = $conn->prepare("UPDATE commande SET etatCoupe = 'Termine' WHERE codeMatelas = :codeMatelas");
        } elseif ($phase === 'etiquetage') {
            $commandeUpdateStmt = $conn->prepare("UPDATE commande SET etatEtiquetage = 'Termine' WHERE codeMatelas = :codeMatelas");
        }

        if (isset($commandeUpdateStmt)) {
            $commandeUpdateStmt->bindParam(':codeMatelas', $codeMatelas);
            if ($commandeUpdateStmt->execute()) {
                // Si la mise à jour réussit
                echo json_encode(['success' => true, 'message' => 'Insertion et mises à jour réussies.']);
            } else {
                // Si la mise à jour échoue
                echo json_encode(['success' => false, 'message' => 'Insertion réussie, mais échec de la mise à jour dans la commande.']);
            }
        }
    } else {
        // Si la mise à jour de planification échoue
        echo json_encode(['success' => false, 'message' => 'Insertion réussie, mais échec de la mise à jour dans la planification.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Erreur lors de l\'exécution de la requête d\'insertion.']);
}


    } else {
        // En cas de données manquantes
        echo json_encode(['success' => false, 'message' => 'Données manquantes.']);
    }
} catch (PDOException $e) {
    // Gérer les erreurs de la base de données
    echo json_encode(['success' => false, 'message' => 'Erreur de base de données : ' . $e->getMessage()]);
}
