<?php
if (isset($_GET['atelier'], $_GET['commande'], $_GET['article'])) {
    // Récupérer les paramètres
    $atelier = htmlspecialchars($_GET['atelier']);
    $commande = htmlspecialchars($_GET['commande']);
    $article = htmlspecialchars($_GET['article']);

    // Chemin vers le dossier contenant les fichiers
    $filePath2 = "c://Apache24/htdocs/MES/CoupeInterne/uploads/trace/" . $article . "/" . $commande . "/";
    
    // Vérifier si le dossier existe
    if (!is_dir($filePath2)) {
        die("Le dossier spécifié n'existe pas.");
    }

    // Créer un nom pour le fichier ZIP
    $zipFileName = "traçé_{$atelier}_commande_{$commande}_article_{$article}.zip";

    // Initialiser l'archive ZIP
    $zip = new ZipArchive();
    if ($zip->open($zipFileName, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
        die("Erreur lors de la création de l'archive ZIP.");
    }

    // Ajouter chaque fichier du dossier à l'archive ZIP
    $files = scandir($filePath2); // Récupérer tous les fichiers dans le dossier
    foreach ($files as $file) {
        // Ignorer les fichiers spéciaux '.' et '..'
        if ($file !== '.' && $file !== '..') {
            $filePath = $filePath2 . $file; // Obtenir le chemin complet

            // Vérifier si le fichier existe avant de l'ajouter
            if (file_exists($filePath)) {
                $zip->addFile($filePath, basename($filePath)); // Ajouter avec le nom d'origine
            } else {
                echo "Le fichier $file est introuvable ou invalide.<br>";
            }
        }
    }

    $zip->close(); // Fermer l'archive ZIP

    // Vérifier si le ZIP a bien été créé
    if (!file_exists($zipFileName)) {
        die("Erreur lors de la génération du fichier ZIP.");
    }

    // Forcer le téléchargement du fichier ZIP
    header('Content-Type: application/zip');
    header('Content-Disposition: attachment; filename="' . basename($zipFileName) . '"');
    header('Content-Length: ' . filesize($zipFileName));

    // Envoyer le fichier au navigateur
    readfile($zipFileName);

    // Supprimer le fichier ZIP après téléchargement
    unlink($zipFileName);
    exit();
} else {
    die("Paramètres manquants pour le téléchargement.");
}
?>
