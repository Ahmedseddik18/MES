<?php
require '../Classes/PHPExcel.php'; // Include PHPExcel
require '../php/db.php'; // Include your database connection

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["file"])) {
    $allowedFileTypes = array(
        'application/vnd.ms-excel',
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        'text/csv'
    );

    if (in_array($_FILES["file"]["type"], $allowedFileTypes)) {
        $targetPath = '../CoupeInterne/uploads/' . $_FILES['file']['name'];

        if (move_uploaded_file($_FILES['file']['tmp_name'], $targetPath)) {
            // Load the spreadsheet
            $objPHPExcel = PHPExcel_IOFactory::load($targetPath);
            $sheet = $objPHPExcel->getActiveSheet();
            $rowCount = 0; // Initialize the row count

            // Iterate through each row
            foreach ($sheet->getRowIterator() as $row) {
                $rowCount++;
                if ($rowCount <= 1) {
                    continue; // Skip the first row (headers)
                }

                $cellIterator = $row->getCellIterator();
                $cellIterator->setIterateOnlyExistingCells(FALSE); // Loop through all cells, even if empty
                $rowData = [];
                foreach ($cellIterator as $cell) {
                    $rowData[] = $cell->getValue();
                }

                // Extraction des données de chaque ligne
                $code = substr($rowData[0] ?? '', -3); // Récupère les 3 caractères à droite de la colonne A
                // Récupère la première partie de la chaîne, avant le premier espace
$fournisseur = isset($rowData[1]) ? explode(' ', $rowData[1])[0] : null;

				if (strpos($rowData[2], 'Piquet') !== false) {
    $typologie = 'Piquet';
} elseif (strpos($rowData[2], 'Costa') !== false) {
    $typologie = 'Costa';
} elseif (strpos($rowData[2], 'Interlock') !== false) {
    $typologie = 'Interlock';
} elseif (strpos($rowData[2], 'Felpa') !== false) {
    $typologie = 'Felpa';
} elseif (strpos($rowData[2], 'Jersey') !== false) {
    $typologie = 'Jersey';
}else {
    continue;
}

                
                $replacements = [
    '% CO' => '%COTONE',
	'% CO ' => '%COTONE',
    '% PL' => '%POLYESTER',
    // Ajoutez d'autres remplacements ici
];

// Appliquer les remplacements
$composition = $rowData[3] ?? null;

if ($composition !== null) {
    foreach ($replacements as $abbr => $full) {
        $composition = str_replace($abbr, $full, $composition);
    }
}

                $base = substr($rowData[4] ?? '', -3);
                $largeur = $rowData[5] ?? null; // Colonne F
                $poids = $rowData[6] ?? null; // Colonne G

               

                // Validate numeric fields
                if (!is_numeric($largeur)) {
                    $largeur = null; // or set a default value if applicable
                }
                if (!is_numeric($poids)) {
                    $poids = null; // or set a default value if applicable
                }

                // Skip row if code is null
                if (empty($code)) {
                    continue;
                }

                // Vérifier l'existence des données dans la base de données
                $query = "SELECT COUNT(*) as count FROM tissu WHERE code = :code";
                $stmt = $conn->prepare($query);
                $stmt->bindValue(':code', $code, PDO::PARAM_STR);
                $stmt->execute();
                $count = $stmt->fetchColumn();

                // Si les données existent, passer à la ligne suivante
                if ($count > 0) {
                    continue;
                }

                // Insertion des données dans la base de données
                $query = "INSERT INTO tissu (code, fournisseur, typologie, composition, base, largeur, poids) VALUES (:code, :fournisseur, :typologie, :composition, :base, :largeur, :poids)";
                $stmt = $conn->prepare($query);

                $stmt->bindValue(':code', $code, PDO::PARAM_STR);
                $stmt->bindValue(':fournisseur', $fournisseur, PDO::PARAM_STR);
                $stmt->bindValue(':typologie', $typologie, PDO::PARAM_STR);
                $stmt->bindValue(':composition', $composition, PDO::PARAM_STR);
                $stmt->bindValue(':base', $base, PDO::PARAM_STR);
                $stmt->bindValue(':largeur', $largeur, PDO::PARAM_STR); // Ensure numeric or null
                $stmt->bindValue(':poids', $poids, PDO::PARAM_STR); // Ensure numeric or null
                $result = $stmt->execute();

                if ($result) {
    $alertClass = "alert-success";
    $message = "Données Excel importées dans la base de données.";
    
    
}else {
                    $message = "Erreur lors de l'exécution de l'insertion : " . implode(' ', $conn->errorInfo());
                }
            }

            // Close the database connection
            $conn = null;
        } else {
            $type = "error";
            $message = "Erreur lors du déplacement du fichier téléchargé.";
        }
    } else {
        $type = "error";
        $message = "Type de fichier invalide. Veuillez télécharger un fichier Excel.";
    }
    // Encoder les variables pour l'URL
    $typeEncoded = urlencode($type);
    $messageEncoded = urlencode($message);
    
    // Rediriger avec les variables encodées
    header("Location: list-tissu.php");
    exit;
}
?>
