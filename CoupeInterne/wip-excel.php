<?php
require '../Classes/PHPExcel.php'; // Inclure PHPExcel
require '../php/db.php'; // Inclure la connexion à la base de données
require '../php/fonction.php'; // Inclure la fonction de sélection

// Définir les colonnes spécifiques à sélectionner et leurs en-têtes personnalisés
$columns = [
    'Semaine' => 'Semaine',
    'dateChargement' => 'Date Chargement',
    'commande' => 'Commande',
    'article' => 'Article',
    'Etat' => 'Etat',
 
    
    'phase' => 'Phase',
    
    'traitement' => 'Traitement',
    'tissu' => 'Tissu',
    
    'nbPartie' => 'Nombre Partie',
    'nbSub' => 'Nombre Sub',
    'quantiteDemandee' => 'Quantité Demandée',
    'quantiteReelle' => 'Quantité Réelle',
    'diff' => 'Difference',
    'prixUnitaire' => 'Prix Unitaire',
    'prixTotal' => 'Prix Total',
    'fin' => 'Date Fin Prévu' // Nouvelle colonne pour Date Fin
   
];

// Préparation de la requête SQL
$selectFields = implode(', ', array_keys($columns)). ', HS,debut';
$fromTables = "db";
$whereConditions = "Etat IN ('En cours', 'En attente', 'Bloque', 'T1') 
   OR (Etat = 'Termine' AND WEEK(fin) = WEEK(CURDATE()))";
$orderBy = 'article ASC';

$result = select($conn, $selectFields, $fromTables, $whereConditions, $orderBy);

// Fonction pour calculer la date de fin avec gestion des jours ouvrés
function calculateEndDate($startDate, $hours) {
    $hoursRemaining = $hours; // Heures à répartir
    $currentDate = new DateTime($startDate); // Date de début
    $currentDate->setTime(8, 0); // Début de la journée à 08:00

    while ($hoursRemaining > 0) {
        $dayOfWeek = $currentDate->format('N'); // 1 (lundi) à 7 (dimanche)

        // Déterminer la capacité horaire du jour
        $dailyLimit = ($dayOfWeek >= 1 && $dayOfWeek <= 5) ? 8 : 5;
        if ($dayOfWeek == 7) $dailyLimit = 0; // Dimanche : 0 heures

        // Calcul des heures consommées
        $hoursToUse = min($hoursRemaining, $dailyLimit);
        $hoursRemaining -= $hoursToUse;

        // Passer au jour suivant si les heures ne suffisent pas
        if ($hoursRemaining > 0) {
            $currentDate->modify('+1 day')->setTime(8, 0);
        }
    }
    return $currentDate->format('Y-m-d');
}

// Créer un objet PHPExcel et configurer la feuille active
$objPHPExcel = new PHPExcel();
$objPHPExcel->setActiveSheetIndex(0);
$sheet = $objPHPExcel->getActiveSheet();

// Définir les en-têtes de colonnes personnalisés
$col = 0;
foreach ($columns as $dbColumn => $header) {
    $sheet->setCellValueByColumnAndRow($col++, 1, $header);
    $sheet->getStyleByColumnAndRow($col - 1, 1)
          ->getAlignment()
          ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
}

// Remplir les données
$rowNum = 2;
while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
    $col = 0;

    // Calculer HS * 1.5
    $hs = $row['HS'] * 1.5;
    $row['heures'] = $hs; // Ajouter la nouvelle valeur calculée

    // Initialiser la date de fin
    $dateFin = '';

    // Vérifier si l'état est "En cours"
    if ($row['Etat'] === 'En cours') {
        // Calculer la date de fin seulement si l'état est "En cours"
        $dateFin = calculateEndDate($row['debut'], $hs);
    }

    // Ajouter la date de fin au tableau (peut être vide si l'état n'est pas "En cours")
    $row['fin'] = $dateFin;

    // Remplir chaque colonne
    foreach ($columns as $dbColumn => $header) {
        $value = isset($row[$dbColumn]) ? $row[$dbColumn] : '';
        $sheet->setCellValueByColumnAndRow($col++, $rowNum, $value);
        $sheet->getStyleByColumnAndRow($col - 1, $rowNum)
              ->getAlignment()
              ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    }
    $rowNum++;
}


// Ajuster automatiquement la largeur des colonnes
foreach (range(0, count($columns) - 1) as $colIndex) {
    $sheet->getColumnDimension(PHPExcel_Cell::stringFromColumnIndex($colIndex))->setAutoSize(true);
}

// Définir le nom du fichier
$filename = 'wip_' . date('d_m_Y') . '.xlsx';

// Enregistrer le fichier et envoyer la réponse au navigateur
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="' . $filename . '"');
header('Cache-Control: max-age=0');

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save('php://output');
exit;
?>
