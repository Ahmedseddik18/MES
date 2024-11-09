<?php
require '../php/db.php'; // Inclure votre connexion à la base de données
header('Content-Type: application/json');

// Afficher les erreurs pour le développement
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Récupérer les données POST
$id = isset($_POST['id']) ? $_POST['id'] : null;
$article = $_POST['article'];
$commande = $_POST['commande'];
$etat = $_POST['etat'];
$dateAujourdHui = date('Y-m-d');
$problem = isset($_POST['problem']) ? $_POST['problem'] : null;
$departement = isset($_POST['departement']) ? $_POST['departement'] : null;
$dateBloquage = isset($_POST['dateBloquage']) ? $_POST['dateBloquage'] : null;
$note = isset($_POST['note']) ? $_POST['note'] : null;
$attachments = isset($_FILES['attachments']) ? $_FILES['attachments'] : null;

$attachmentPaths = []; // Initialiser un tableau pour stocker les chemins des fichiers joints

$errorMessages = [];

// Vérifier les paramètres requis
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

// Initialisation de la requête SQL pour PostgreSQL
$sql = "UPDATE db SET etat = :etat";

// Conditions pour mettre à jour les colonnes 'debut' et 'fin'
if ($etat == 'En attente') {
    $sql .= ", debut = NULL, fin = NULL"; 
} elseif ($etat == 'En cours') {
    $sql .= ", debut = :debut, fin = NULL";
    $debut = $dateAujourdHui;
} elseif ($etat == 'Termine') {
    $sql .= ", fin = :fin";
    $fin = $dateAujourdHui;
} elseif ($etat == 'Bloque') {
    $sql .= ", debut = NULL";
}

$sql .= " WHERE article = :article AND commande = :commande";

// Préparer et exécuter la requête
$stmt = $conn->prepare($sql);
$stmt->bindParam(':etat', $etat);
$stmt->bindParam(':article', $article);
$stmt->bindParam(':commande', $commande);

if ($etat == 'En cours') {
    $stmt->bindParam(':debut', $debut);
} elseif ($etat == 'Termine') {
    $stmt->bindParam(':fin', $fin);
}

if ($stmt->execute()) {
    if ($etat == 'Bloque' && $attachments) {
        $uploadDir = '../CoupeInterne/uploads/arret/' . $article . '_' . $commande;

        if (!is_dir($uploadDir)) {
            if (!mkdir($uploadDir, 0777, true)) {
                echo json_encode(['success' => false, 'error' => 'Impossible de créer le dossier de destination.']);
                exit();
            }
        }

        // Gestion du téléchargement des fichiers
        for ($i = 0; $i < count($attachments['name']); $i++) {
            if ($attachments['error'][$i] == UPLOAD_ERR_OK) {
                $uploadFile = $uploadDir . '/' . basename($attachments['name'][$i]);
                if (move_uploaded_file($attachments['tmp_name'][$i], $uploadFile)) {
                    $attachmentPaths[] = $uploadFile;
                } else {
                    echo json_encode(['success' => false, 'error' => 'Échec du téléchargement du fichier ' . $attachments['name'][$i]]);
                    exit();
                }
            }
        }

        $attachmentString = implode(',', $attachmentPaths);
        
        // Insérer les informations de blocage
        $insertSQL = "INSERT INTO arret (article, commande, probleme, departement, note, debut, attachementBloquage) 
                      VALUES (:article, :commande, :problem, :departement, :note, :date, :attachment)";
        $insertStmt = $conn->prepare($insertSQL);
        
        $insertStmt->bindParam(':article', $article);
        $insertStmt->bindParam(':commande', $commande);
        $insertStmt->bindParam(':problem', $problem);
        $insertStmt->bindParam(':departement', $departement);
        $insertStmt->bindParam(':note', $note);
        $insertStmt->bindParam(':date', $dateBloquage);
        $insertStmt->bindParam(':attachment', $attachmentString);

        if ($insertStmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Tâche mise à jour et insérée avec succès dans arret.']);
        } else {
            echo json_encode(['success' => false, 'error' => 'Erreur lors de l\'insertion dans arret: ' . implode(', ', $insertStmt->errorInfo())]);
        }
    } else {
        echo json_encode(['success' => true, 'message' => 'Tâche mise à jour avec succès.']);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Erreur lors de la mise à jour: ' . implode(', ', $stmt->errorInfo())]);
}
?>


