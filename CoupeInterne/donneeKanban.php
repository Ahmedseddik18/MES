<?php

include("../php/db.php");
include("../php/fonction.php");
    // Récupérer les tâches depuis la base de données
$stmt = $conn->query("
    SELECT id, commande, article, phase, quantitedemandee, datechargement, etat
    FROM db
    WHERE etat IN ('En cours', 'En attente', 'Bloque')
       OR (etat = 'Termine' AND DATE_PART('week', fin) = DATE_PART('week', CURRENT_DATE));
");
 // Modifie la requête en fonction de ta structure
    $tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Encoder les données en JSON
    echo json_encode($tasks);

?>
