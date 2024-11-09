<?php
include("../php/db.php");
include("../php/fonction.php");

// Définir l'en-tête JSON
header('Content-Type: application/json');

// Vérifier si les paramètres sont définis
if (isset($_GET['article']) && isset($_GET['commande'])) {
    // Récupérer les paramètres
    $article = $_GET['article'];
    $commande = $_GET['commande'];

    try {
        // Préparer la requête pour éviter les injections SQL
        $stmt = $conn->prepare("SELECT id, commande, article, probleme, departement, note FROM arret WHERE article = :article AND commande = :commande");
        $stmt->bindParam(':article', $article);
        $stmt->bindParam(':commande', $commande);

        // Exécuter la requête
        if ($stmt->execute()) {
            // Récupérer les résultats
            $tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Vérifier si des tâches ont été trouvées
            if (!empty($tasks)) {
                echo json_encode($tasks);
            } else {
                echo json_encode(['success' => false, 'error' => 'Aucune tâche trouvée pour ces paramètres']);
            }
        } else {
            echo json_encode(['success' => false, 'error' => 'Erreur lors de l\'exécution de la requête SQL']);
        }
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'error' => 'Erreur SQL: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Paramètres manquants']);
}
?>
