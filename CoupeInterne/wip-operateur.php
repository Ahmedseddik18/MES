<?php
require '../vendor/autoload.php'; // Chemin correct vers PhpSpreadsheet
require '../php/db.php';
require '../php/fonction.php';

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

function loadSpreadsheet($filePath) {
    error_reporting(E_ALL & ~E_NOTICE);
    try {
        $spreadsheet = IOFactory::load($filePath);
        return $spreadsheet;
    } catch (\PhpOffice\PhpSpreadsheet\Reader\Exception $e) {
        die('Erreur lors du chargement du fichier : ' . $e->getMessage());
    } catch (Error $e) {
        die('Erreur : ' . $e->getMessage());
    }
}

// Chemin vers les modèles Excel pour chaque phase
$templatePaths = [
    'relaxation' => '../file/relaxation.xlsx',
    'matelassage' => '../file/matelassage.xlsx',
    'coupe' => '../file/coupe.xlsx',
    'etiquetage' => '../file/etiquetage.xlsx'
];



$mergedCellRanges = [
    ['commande' => 'B9', 'article' => 'B7', 'quantiteMatelas' => 'K13', 'sub' => 'B13', 'operateur1' => 'B15', 'operateur2' => 'B17', 'uni' => 'H7', 'raye' => 'K7', 'partie' => 'K15', 'matelas' => 'K17', 'table' => 'K18'],
    ['commande' => 'B24', 'article' => 'B22', 'quantiteMatelas' => 'K28', 'sub' => 'B28', 'operateur1' => 'B30', 'operateur2' => 'B32', 'uni' => 'H22', 'raye' => 'K22' , 'partie' => 'K30', 'matelas' => 'K32', 'table' => 'K33'],
    ['commande' => 'B39', 'article' => 'B37', 'quantiteMatelas' => 'K43', 'sub' => 'B43', 'operateur1' => 'B45', 'operateur2' => 'B47', 'uni' => 'H37', 'raye' => 'K37' , 'partie' => 'K45', 'matelas' => 'K47', 'table' => 'K48'],
    ['commande' => 'P9', 'article' => 'P7', 'quantiteMatelas' => 'Y13', 'sub' => 'P13', 'operateur1' => 'P15', 'operateur2' => 'P17', 'uni' => 'V7', 'raye' => 'K7' , 'partie' => 'y15', 'matelas' => 'y17', 'table' => 'y18'],
    ['commande' => 'P24', 'article' => 'P22', 'quantiteMatelas' => 'Y28', 'sub' => 'P28', 'operateur1' => 'P30', 'operateur2' => 'P32', 'uni' => 'V22', 'raye' => 'K22' , 'partie' => 'y30', 'matelas' => 'y32', 'table' => 'y33'],
    ['commande' => 'P39', 'article' => 'P37', 'quantiteMatelas' => 'Y43', 'sub' => 'P43', 'operateur1' => 'P45', 'operateur2' => 'P47', 'uni' => 'V37', 'raye' => 'K37' , 'partie' => 'y45', 'matelas' => 'y47', 'table' => 'y48'],
];

$phases = ['relaxation', 'matelassage', 'coupe', 'etiquetage'];

$files = []; // Tableau pour stocker les chemins des fichiers générés

foreach ($phases as $phase) {
	$operateursQuery = "SELECT DISTINCT operateur1 FROM planification WHERE phase = :phase AND date(datetime_debut) = CURDATE() ";
$operateursStmt = $conn->prepare($operateursQuery);
$operateursStmt->execute([ ':phase' => $phase]);
    while ($operateurRow = $operateursStmt->fetch(PDO::FETCH_ASSOC)) {
		
        $currentOperateur = $operateurRow['operateur1'];

        // Utiliser une requête spécifique en fonction de la phase
        $query = ($phase === 'relaxation') 
            ? "SELECT p.commande, p.article, p.sub, SUM(p.quantiteMatelas) AS totalQuantiteMatelas, p.operateur1, p.operateur2, p.datetime_debut, d.phase, d.categorie 
               FROM planification p 
               RIGHT JOIN db d ON p.commande = d.commande 
               WHERE p.phase = 'relaxation' 
               AND date(p.datetime_debut) = CURDATE() 
               AND p.operateur1 = :operateur1 
               AND sub <> '31' 
               GROUP BY p.commande, p.article, p.sub, p.operateur1, p.operateur2, d.phase, d.categorie"
            : "SELECT p.commande,
			p.article,
			p.sub,
			p.quantiteMatelas,
			p.operateur1,
			p.operateur2,
			p.datetime_debut,
			p.partie,
			RIGHT(p.codeMatelas, 3) AS DerniersTroisCaractères,
			p.tabl,
			d.phase,
			d.categorie 
               FROM planification p 
               RIGHT JOIN db d ON p.commande = d.commande 
               WHERE p.phase = :phase 
               AND date(p.datetime_debut) = CURDATE() 
               AND p.operateur1 = :operateur1 
               ";

        $stmt = $conn->prepare($query);

        // Liaison des paramètres en fonction de la phase
        if ($phase === 'relaxation') {
            $stmt->execute([':operateur1' => $currentOperateur]);
        } else {
            $stmt->execute([':operateur1' => $currentOperateur, ':phase' => $phase]);
        }

        $rowCount = $stmt->rowCount();

        // Cloner le modèle approprié en fonction de la phase
        $templateToUse = loadSpreadsheet($templatePaths[$phase]);
        $objPHPExcel = clone $templateToUse;
        $sheet = $objPHPExcel->getActiveSheet();

        $index = 0;
        $dateDebut = null;

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            if (isset($mergedCellRanges[$index])) {
                $cellRange = $mergedCellRanges[$index];

                // Insérer les données dans les cellules appropriées
$sheet->setCellValue($cellRange['commande'], $row['commande']);
$sheet->setCellValue($cellRange['article'], $row['article']);
$sheet->setCellValue($cellRange['sub'], $row['sub']);
$sheet->setCellValue($cellRange['quantiteMatelas'], $row['quantiteMatelas']);
$sheet->setCellValue($cellRange['operateur1'], $row['operateur1']);
$sheet->setCellValue($cellRange['operateur2'], $row['operateur2']);

if ($phase === 'matelassage' || $phase === 'coupe') {
    $sheet->setCellValue($cellRange['partie'], $row['partie']);
    $sheet->setCellValue($cellRange['matelas'], $row['DerniersTroisCaractères']);
    $sheet->setCellValue($cellRange['table'], $row['tabl']);
}

// Aligner le texte à droite pour les cellules spécifiées
$alignCells = [
    
    $cellRange['matelas'],
];

foreach ($alignCells as $cell) {
    $sheet->getStyle($cell)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
}

                if ($dateDebut === null) {
                    $dateDebut = $row['datetime_debut'];
                }

                $index++;
            }
        }

        // Définir la date uniquement si elle est disponible
        if ($dateDebut) {
            $formattedDate = date('Y-m-d', strtotime($dateDebut));
            $sheet->setCellValue('B4', $formattedDate);
            $sheet->setCellValue('P4', $formattedDate);
        }

        // Définir le nom du fichier pour chaque opérateur
        $filename = $phase . '_' . $currentOperateur . '_' . date('d-m-Y') . '.xlsx';
        
        // Enregistrer le fichier sur le serveur
        $filePath = 'C:/wamp64/www/MES/Coupeinterne/uploads/' . $filename; // Assurez-vous que ce dossier existe et est accessible
        $writer = IOFactory::createWriter($objPHPExcel, 'Xlsx');
        $writer->save($filePath);

        // Stocker le chemin du fichier généré pour le téléchargement ultérieur
        $files[] = $filePath;
    }
}

// Proposer tous les fichiers générés pour téléchargement
header('Content-Type: application/zip');
header('Content-Disposition: attachment; filename="fichiers_generes.zip"');
header('Cache-Control: max-age=0');

// Créer un zip des fichiers générés
$zip = new ZipArchive();
$zipFileName = 'C:/wamp64/www/MES/Coupeinterne/uploads/fichiers_generes.zip';
$zip->open($zipFileName, ZipArchive::CREATE | ZipArchive::OVERWRITE);

foreach ($files as $file) {
    $zip->addFile($file, basename($file));
}

$zip->close();

// Force le téléchargement du fichier zip
readfile($zipFileName);
exit;
?>
