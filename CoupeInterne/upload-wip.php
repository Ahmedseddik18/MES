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
                $commande = $rowData[4]; // Colonne A
                $article = $rowData[5]; // Colonne B
                $relaxation = $rowData[8]; // Colonne C
                

                

                // Insertion des données dans la base de données
                $query = "INSERT INTO db (commande, article, relaxation) 
                          VALUES (:commande, :article, :relaxation)";
                $stmt = $conn->prepare($query);

                $stmt->bindValue(':commande', $commande, PDO::PARAM_STR);
                $stmt->bindValue(':article', $article, PDO::PARAM_STR);
                $stmt->bindValue(':relaxation', $relaxation, PDO::PARAM_STR);
                

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
