<?php
require '../Classes/PHPExcel.php'; // Inclure PHPExcel
require '../php/db.php'; // Inclure votre connexion à la base de données

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["file"])) {
    $allowedFileTypes = array(
        'application/vnd.ms-excel',
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        'text/csv'
    );

    if (in_array($_FILES["file"]["type"], $allowedFileTypes)) {
        $targetPath = '../CoupeInterne/uploads/' . $_FILES['file']['name'];

        if (move_uploaded_file($_FILES['file']['tmp_name'], $targetPath)) {
            // Charger le fichier Excel
            $objPHPExcel = PHPExcel_IOFactory::load($targetPath);
            $sheet = $objPHPExcel->getActiveSheet();
            $rowCount = 0; // Initialiser le compteur de lignes

            // Itérer à travers chaque ligne
            foreach ($sheet->getRowIterator() as $row) {
                $rowCount++;
                if ($rowCount <= 1) {
                    continue; // Ignorer la première ligne (en-têtes)
                }

                $cellIterator = $row->getCellIterator();
                $cellIterator->setIterateOnlyExistingCells(FALSE); // Boucle à travers toutes les cellules, même vides
                $rowData = [];
                foreach ($cellIterator as $cell) {
                    $rowData[] = $cell->getValue();
                }

                // Extraction des données de chaque ligne
                $commande = $rowData[0]; // Colonne A
                $article = $rowData[1]; // Colonne B
                $partie = $rowData[2]; // Colonne C
                $sub = $rowData[3]; // Colonne D

                $tsr = $rowData[4]; // Colonne E
                $tsm = $rowData[5]; // Colonne F
                $tsc = $rowData[6]; // Colonne G
                $tse = $rowData[7]; // Colonne H

                $quantite = $rowData[9]; // Colonne J

                // Insertion des données dans la base de données
                $query = "INSERT INTO commande (commande, article, partie, sub, TSR, TSM, TSC, TSE, quantiteMatelas) 
                          VALUES (:commande, :article, :partie, :sub, :tsr, :tsm, :tsc, :tse, :quantiteMatelas)";
                $stmt = $conn->prepare($query);

                $stmt->bindValue(':commande', $commande, PDO::PARAM_STR);
                $stmt->bindValue(':article', $article, PDO::PARAM_STR);
                $stmt->bindValue(':partie', $partie, PDO::PARAM_STR);
                $stmt->bindValue(':sub', $sub, PDO::PARAM_STR);
                $stmt->bindValue(':tsr', $tsr, PDO::PARAM_STR);
                $stmt->bindValue(':tsm', $tsm, PDO::PARAM_STR); // Assurez-vous que c'est numérique ou nul
                $stmt->bindValue(':tsc', $tsc, PDO::PARAM_STR); // Assurez-vous que c'est numérique ou nul
                $stmt->bindValue(':tse', $tse, PDO::PARAM_STR); // Assurez-vous que c'est numérique ou nul
                $stmt->bindValue(':quantiteMatelas', $quantite, PDO::PARAM_STR); // Assurez-vous que c'est numérique ou nul

                $result = $stmt->execute();

                if ($result) {
                    $type = "success";
                    $message = "Données Excel importées dans la base de données.";
                } else {
                    $type = "error";
                    $message = "Erreur lors de l'exécution de l'insertion : " . implode(' ', $conn->errorInfo());
                }
            }

            // Fermer la connexion à la base de données
            $conn = null;
        } else {
            $type = "error";
            $message = "Erreur lors du déplacement du fichier téléchargé.";
        }
    } else {
        $type = "error";
        $message = "Type de fichier invalide. Veuillez télécharger un fichier Excel.";
    }
} else {
    $type = "error";
    $message = "Aucun fichier téléchargé.";
}

// Encoder les variables pour l'URL
$typeEncoded = urlencode($type);
$messageEncoded = urlencode($message);

// Rediriger avec les variables encodées
header("Location: list-tissu.php?type=$typeEncoded&message=$messageEncoded");
exit;
?>
