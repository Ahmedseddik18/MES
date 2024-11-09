<?php
require '../php/db.php'; // Inclure votre connexion à la base de données

// Récupérer les données POST
$id = isset($_POST['id']) ? $_POST['id'] : null;
$article = $_POST['article'] ;
$commande =  $_POST['commande'] ;
$etat =  $_POST['etat'];
$dateAujourdHui = date('Y-m-d');
$problem = isset($_POST['problem']) ? $_POST['problem'] : null;
$departement = isset($_POST['departement']) ? $_POST['departement'] : null;
$dateBloquage = isset($_POST['dateBloquage']) ? $_POST['dateBloquage'] : null;
$note = isset($_POST['note']) ? $_POST['note'] : null;
$attachments = isset($_FILES['attachments']) ? $_FILES['attachments'] : null;

$attachmentPaths = []; // Initialiser un tableau pour stocker les chemins des fichiers joints

// Vérifier si des fichiers sont envoyés


$errorMessages = [];

if ($article === null) {
    $errorMessages[] = 'Article';
}
if ($commande === null) {
    $errorMessages[] = 'Commande';
}
if ($etat === null) {
    $errorMessages[] = 'État';
}

if (!empty($errorMessages)) {
    echo json_encode(['success' => false, 'error' => 'Paramètres manquants : ' . implode(', ', $errorMessages)]);
    exit();
}


// Initialisation de la requête SQL
$sql = "UPDATE db SET Etat = :etat";

// Ajoutez les conditions pour mettre à jour les colonnes 'debut' et 'fin'
if ($etat == 'En attente') {
    $sql .= ", debut = NULL, fin = NULL"; // NULL au lieu de chaînes vides pour éviter les erreurs SQL
} elseif ($etat == 'En cours') {
    $sql .= ", debut = :debut, fin = NULL"; // Mettre à jour la date de début
    $debut = $dateAujourdHui;
} elseif ($etat == 'Termine') {
    $sql .= ", fin = :fin"; // Mettre à jour la date de fin
    $fin = $dateAujourdHui;
} elseif ($etat == 'Bloque') {
    $sql .= ", debut = NULL"; // Si l'état est "Bloque", on garde la date de début mais sans date de fin
}

// Terminez la requête SQL
$sql .= " WHERE article = :article AND commande = :commande";

// Préparez la requête
$stmt = $conn->prepare($sql);

// Liez les paramètres communs
$stmt->bindParam(':etat', $etat);
$stmt->bindParam(':article', $article);
$stmt->bindParam(':commande', $commande);

// Liez les paramètres conditionnels selon l'état
if ($etat == 'En cours') {
    $stmt->bindParam(':debut', $debut);
} elseif ($etat == 'Termine') {
    $stmt->bindParam(':fin', $fin);
}

// Exécutez la requête et vérifiez le résultat
if ($stmt->execute()) {
    // Si l'état est "Bloque", insérer dans la table "arret"
    if ($etat == 'Bloque') {
		if ($attachments) {
    // Dossier où vous souhaitez stocker les fichiers
    $uploadDir = '../CoupeInterne/uploads/arret/' . $article . '_' . $commande;

    // Vérifiez si le dossier existe, sinon créez-le
    if (!is_dir($uploadDir)) {
        if (!mkdir($uploadDir, 0777, true)) {
            echo json_encode(['success' => false, 'error' => 'Impossible de créer le dossier de destination.']);
            exit();
        }
    }

    // Parcourir chaque fichier joint
    for ($i = 0; $i < count($attachments['name']); $i++) {
        if ($attachments['error'][$i] == UPLOAD_ERR_OK) {
            // Nom complet du fichier
            $uploadFile = $uploadDir . '/' . basename($attachments['name'][$i]);

            // Déplacez le fichier téléchargé dans le dossier de destination
            if (move_uploaded_file($attachments['tmp_name'][$i], $uploadFile)) {
                // Stocker le chemin du fichier si nécessaire
                $attachmentPaths[] = $uploadFile;
            } else {
                echo json_encode(['success' => false, 'error' => 'Échec du téléchargement du fichier ' . $attachments['name'][$i]]);
                exit();
            }
        }
    }
}
        $insertSQL = "INSERT INTO arret (article, commande, probleme, departement, note, debut, attachementBloquage) 
                      VALUES (:article, :commande, :problem, :departement, :note, :date, :attachment)";
        $insertStmt = $conn->prepare($insertSQL);

        // Lier les paramètres pour l'insertion
        $insertStmt->bindParam(':article', $article);
        $insertStmt->bindParam(':commande', $commande);
        $insertStmt->bindParam(':problem', $problem);
        $insertStmt->bindParam(':departement', $departement);
        $insertStmt->bindParam(':note', $note);
        $insertStmt->bindParam(':date', $dateBloquage);
        $insertStmt->bindParam(':attachment', implode(',', $attachmentPaths)); // Stocker tous les fichiers joints comme une chaîne

        // Exécutez l'insertion et vérifiez le résultat
        if ($insertStmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Tâche mise à jour et insérée avec succès dans arret.']);
        } else {
            echo json_encode(['success' => false, 'error' => $insertStmt->errorInfo()]);
        }
    } else {
        echo json_encode(['success' => true, 'message' => 'Tâche mise à jour avec succès.']);
    }
} else {
    echo json_encode(['success' => false, 'error' => $stmt->errorInfo()]);
}
?>
