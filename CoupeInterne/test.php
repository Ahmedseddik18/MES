<?php
require '../Classes/PHPExcel.php'; // Inclure PHPExcel
require '../php/db.php'; // Inclure votre connexion à la base de données
require '../php/fonction.php'; // Inclure votre fonction de sélection

// Définir les colonnes spécifiques à sélectionner et leurs en-têtes personnalisés
$columns = [
    'commande' => 'Commande',
    'article' => 'Article',
    'partie' => 'Partie',
    'sub' => 'Sub',
    'quantiteMatelas' => 'Quantité Matelas',
    'codeMatelas' => 'Code Matelas',
    'HS' => 'HS',
    'tabl' => 'Table',
    'operateur1' => 'Opérateur 1',
    'operateur2' => 'Opérateur 2',
    'phase' => 'Phase',
    'datetime_debut' => 'Date & Heure Début',
    'datetime_fin' => 'Date & Heure Fin'
];

// Récupérer les phases distinctes
$phasesQuery = "SELECT DISTINCT phase FROM planification";
$phasesResult = $conn->query($phasesQuery);

// Créer un nouvel objet PHPExcel
$objPHPExcel = new PHPExcel();

while ($phaseRow = $phasesResult->fetch(PDO::FETCH_ASSOC)) {
    $currentPhase = $phaseRow['phase'];

    // Vérifier si la phase est étiquetage
    if ($currentPhase == 'relaxation') {
        // Requête pour obtenir la somme des quantités et HS
        $query = "
            SELECT 
                commande,
                article,
                SUM(quantiteMatelas) AS quantiteMatelas,
                SUM(HS) AS HS,
                operateur1,
                operateur2,
                MIN(datetime_debut) AS datetime_debut,
                MAX(datetime_fin) AS datetime_fin
            FROM 
                planification
            WHERE 
                phase = :phase
            GROUP BY 
                commande, article
            ORDER BY 
                datetime_debut, commande, article
        ";

        $stmt = $conn->prepare($query);
        $stmt->execute([':phase' => $currentPhase]);

        // Créer une nouvelle feuille pour la phase "relaxation"
        $sheet = $objPHPExcel->createSheet();
        $sheet->setTitle($currentPhase); // Définir le titre de la feuille

        // Définir les en-têtes de colonnes personnalisés
        $headers = [
            'Commande',
            'Article',
            'Quantité Matelas',
            'HS',
            'Opérateur 1',
            'Opérateur 2',
            'Phase',
            'Date & Heure Début',
            'Date & Heure Fin'
        ];
        
        $col = 0;
        foreach ($headers as $header) {
            $sheet->setCellValueByColumnAndRow($col++, 1, $header);
            $sheet->getStyleByColumnAndRow($col - 1, 1)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        }

        // Remplir les données
        $rowNum = 2;
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $col = 0;
            $sheet->setCellValueByColumnAndRow($col++, $rowNum, $row['commande']);
            $sheet->setCellValueByColumnAndRow($col++, $rowNum, $row['article']);
            $sheet->setCellValueByColumnAndRow($col++, $rowNum, $row['quantiteMatelas']);
            $sheet->setCellValueByColumnAndRow($col++, $rowNum, $row['HS']);
            $sheet->setCellValueByColumnAndRow($col++, $rowNum, $row['operateur1']);
            $sheet->setCellValueByColumnAndRow($col++, $rowNum, $row['operateur2']);
            $sheet->setCellValueByColumnAndRow($col++, $rowNum, $currentPhase);
            $sheet->setCellValueByColumnAndRow($col++, $rowNum, $row['datetime_debut']);
            $sheet->setCellValueByColumnAndRow($col++, $rowNum, $row['datetime_fin']);
            $rowNum++;
        }

        // Ajuster automatiquement la largeur des colonnes
        foreach (range(0, count($headers) - 1) as $colIndex) {
            $sheet->getColumnDimension(PHPExcel_Cell::stringFromColumnIndex($colIndex))->setAutoSize(true);
        }

    } else {
        // Récupérer les distincts opérateurs pour la phase actuelle
        $operateursQuery = "SELECT DISTINCT operateur1 FROM planification WHERE phase = :phase ";
        $operateursStmt = $conn->prepare($operateursQuery);
        $operateursStmt->execute([':phase' => $currentPhase]);

        while ($operateurRow = $operateursStmt->fetch(PDO::FETCH_ASSOC)) {
            $currentOperateur = $operateurRow['operateur1'];

            // Requête pour les données de l'opérateur dans la phase
            $selectFields = implode(', ', array_keys($columns));
            $fromTables = "planification";
            $whereConditions = "phase = :phase AND operateur1 = :operateur1";
            $orderBy = 'operateur1 ASC';

            // Préparer la requête
            $stmt = $conn->prepare("SELECT $selectFields FROM $fromTables WHERE $whereConditions ORDER BY $orderBy");
            $stmt->execute([':phase' => $currentPhase, ':operateur1' => $currentOperateur]);

            // Créer une nouvelle feuille pour l'opérateur
            $sheet = $objPHPExcel->createSheet();
            $sheet->setTitle($currentPhase . ' - ' . $currentOperateur); // Définir le titre de la feuille

            // Définir les en-têtes de colonnes personnalisés
            $col = 0;
            foreach ($columns as $header) {
                $sheet->setCellValueByColumnAndRow($col++, 1, $header);
                $sheet->getStyleByColumnAndRow($col - 1, 1)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            }

            // Remplir les données
            $rowNum = 2;
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $col = 0;
                foreach ($columns as $dbColumn => $header) {
                    // Vérifier si l'index existe avant d'y accéder
                    if (array_key_exists($dbColumn, $row)) {
                        $sheet->setCellValueByColumnAndRow($col++, $rowNum, $row[$dbColumn]);
                        $sheet->getStyleByColumnAndRow($col - 1, $rowNum)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                    } else {
                        // Valeur par défaut si l'index n'existe pas
                        $sheet->setCellValueByColumnAndRow($col++, $rowNum, '');
                    }
                }


				
				
                $rowNum++;
            }

            // Ajuster automatiquement la largeur des colonnes
            foreach (range(0, count($columns) - 1) as $colIndex) {
                $sheet->getColumnDimension(PHPExcel_Cell::stringFromColumnIndex($colIndex))->setAutoSize(true);
            }
        }
    }
}

// Définir le nom du fichier
$filename = 'planification_' . date('d-m-Y') . '.xlsx';

// Enregistrer le fichier
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="' . $filename . '"');
header('Cache-Control: max-age=0');

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save('php://output');

exit;
?>