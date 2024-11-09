<?php
require '../php/db.php'; // Include your database connection
header('Content-Type: application/json');

// Retrieve POST data
$id = isset($_POST['id']) ? $_POST['id'] : null;
$article = isset($_POST['article']) ? htmlspecialchars($_POST['article']) : null;
$commande = isset($_POST['commande']) ? htmlspecialchars($_POST['commande']) : null;
$etat = isset($_POST['etat']) ? htmlspecialchars($_POST['etat']) : null;
$dateDebloquage = isset($_POST['dateDebloquage']) ? $_POST['dateDebloquage'] : null;
$attachments = isset($_FILES['attachmentsDebloquage']) ? $_FILES['attachmentsDebloquage'] : null;

$attachmentPaths = []; // Initialize an array to store the attachment paths

try {
    // Update the state of the article
    $sql = "UPDATE db SET Etat = :etat WHERE article = :article AND commande = :commande";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':etat', $etat);
    $stmt->bindParam(':article', $article);
    $stmt->bindParam(':commande', $commande);

    // Execute the query and check the result
    if (!$stmt->execute()) {
        throw new Exception('Database error while updating table db: ' . implode(', ', $stmt->errorInfo()));
    }

    // Handle attachments
    if ($attachments && $attachments['error'][0] !== UPLOAD_ERR_NO_FILE) {
        // Directory to store the files
        $uploadDir = '../CoupeInterne/uploads/arret/' . $article . '_' . $commande;

        // Check if the directory exists, otherwise create it
        if (!is_dir($uploadDir) && !mkdir($uploadDir, 0777, true)) {
            throw new Exception('Error while creating the directory.');
        }

        // Loop through each attachment
        for ($i = 0; $i < count($attachments['name']); $i++) {
            if ($attachments['error'][$i] == UPLOAD_ERR_OK) {
                // Check the file type and size here if necessary
                $uploadFile = $uploadDir . '/' . basename($attachments['name'][$i]);
                if (!move_uploaded_file($attachments['tmp_name'][$i], $uploadFile)) {
                    throw new Exception('Error while uploading the file: ' . $attachments['name'][$i]);
                }
                $attachmentPaths[] = $uploadFile; // Add the file path
            }
        }
    }

    // Update the arret table
    $attachmentString = implode(',', $attachmentPaths); // Store the result in a variable
    $insertSQL = "UPDATE arret SET fin = :fin, attachementDebloquage = :attachment WHERE article = :article AND commande = :commande AND fin IS NULL";
    $insertStmt = $conn->prepare($insertSQL);
    $insertStmt->bindParam(':article', $article);
    $insertStmt->bindParam(':commande', $commande);
    $insertStmt->bindParam(':fin', $dateDebloquage);
    $insertStmt->bindParam(':attachment', $attachmentString); // Pass the variable

    // Execute the update and check the result
    if (!$insertStmt->execute()) {
        throw new Exception('Database error while updating table arret: ' . implode(', ', $insertStmt->errorInfo()));
    }

    // If everything went well
    $message = 'Task updated and successfully inserted into arret.<br/>' .
               'ID: ' . $id . '<br/>' .
               'Article: ' . $article . '<br/>' .
               'Commande: ' . $commande . '<br/>' .
               'État: ' . $etat . '<br/>' .
               'Date de débloquage: ' . $dateDebloquage . '<br/>' .
               'Fichiers joints: ' . implode(', ', $attachmentPaths);
    
    $response = ['success' => true, 'message' => $message];
    echo json_encode($response);
    
} catch (Exception $e) {
    // In case of error, return an error message
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    http_response_code(500); // Internal Server Error
}
?>
