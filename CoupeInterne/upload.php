<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../index.php");
    exit();
}

include("../php/db.php");

$article = $_POST['article'] ?? '';
$commande = $_POST['commande'] ?? '';
$uploadFileDir = "../CoupeInterne/uploads/trace/$article/$commande";

$result = ['status' => 'success', 'files' => [], 'errors' => []];

// Créer le répertoire si nécessaire
if (!is_dir($uploadFileDir) && !mkdir($uploadFileDir, 0755, true)) {
    $result['status'] = 'error';
    $result['errors'][] = "Erreur de création du répertoire: $uploadFileDir";
    echo json_encode($result);
    exit();
}

// Traiter chaque fichier
foreach ($_FILES['file']['tmp_name'] as $key => $tmp_name) {
    $fileName = basename($_FILES['file']['name'][$key]);
    $dest_path = "$uploadFileDir/$fileName";

    if (move_uploaded_file($tmp_name, $dest_path)) {
        $result['files'][] = $fileName;
    } else {
        $result['status'] = 'error';
        $result['errors'][] = "Erreur d'upload du fichier $fileName.";
    }
}

echo json_encode($result);
?>
