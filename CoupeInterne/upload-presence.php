<?php
session_start();
require '../Classes/PHPExcel.php'; // Inclure PHPExcel
require '../php/db.php'; // Inclure votre connexion à la base de données
error_reporting(E_ALL & ~E_NOTICE); // Masque les notifications de type "Notice"

$type = "";
$message = "";
                function convertToDecimalHours($time) {
    // Sépare les heures et les minutes
    list($hours, $minutes) = explode(':', $time);
    
    // Convertir les minutes en fraction d'heure
    $minutes = ($minutes / 60);

    // Additionner les heures et la fraction d'heure
    return $hours + $minutes;
}
// Fonction pour convertir la date du format dd/mm/yyyy au format yyyy-mm-dd
function convertDateFormat($date) {
    $dateTime = DateTime::createFromFormat('d/m/Y', $date);
    if ($dateTime) {
        return $dateTime->format('Y-m-d');
    }
    return null;
}

// Fonction pour convertir une valeur Excel en format Date PHP (dd/mm/yyyy)
function convertToDate($excelValue) {
    $dateTime = PHPExcel_Shared_Date::ExcelToPHPObject($excelValue);
    return $dateTime->format('d/m/Y');
}

// Convertir les valeurs Excel en format de date et heure PHP
function convertToTime($excelValue) {
    $dateTime = PHPExcel_Shared_Date::ExcelToPHPObject($excelValue);
    return $dateTime->format('H:i:s');
}

function findColumnIndices($sheet, $headerRow, $requiredColumns) {
    $columnIndexes = [];
    $row = $sheet->getRowIterator($headerRow, $headerRow)->current();
    $cellIterator = $row->getCellIterator();
    $cellIterator->setIterateOnlyExistingCells(false);

    foreach ($cellIterator as $cell) {
        $value = trim($cell->getValue());
        if (in_array($value, $requiredColumns)) {
            $columnIndex = PHPExcel_Cell::columnIndexFromString($cell->getColumn());
            $columnIndexes[$value] = $columnIndex;
        }
    }

    foreach ($requiredColumns as $column) {
        if (!array_key_exists($column, $columnIndexes)) {
            throw new Exception("La colonne contenant '$column' n'a pas été trouvée.");
        }
    }

    return $columnIndexes;
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["file"])) {
    $allowedFileTypes = ['application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'text/csv'];

    if (in_array($_FILES["file"]["type"], $allowedFileTypes)) {
        $targetPath = '../CoupeInterne/uploads/' . $_FILES['file']['name'];

        if (move_uploaded_file($_FILES['file']['tmp_name'], $targetPath)) {
            $objPHPExcel = PHPExcel_IOFactory::load($targetPath);
            $sheet = $objPHPExcel->getActiveSheet();

            $headerRow = 0;
            $found = false;

            // Recherche dans la première ligne pour trouver la colonne 'Date'
            $rowIterator = $sheet->getRowIterator(1, 100);
            foreach ($rowIterator as $row) {
                $cellIterator = $row->getCellIterator();
                $cellIterator->setIterateOnlyExistingCells(false);
                foreach ($cellIterator as $cell) {
                    $value = trim($cell->getValue());
                    if ($value === 'Date') {
                        $headerRow = $row->getRowIndex();
                        $found = true;
                        break 2;
                    }
                }
            }

            if (!$found) {
                throw new Exception("La colonne contenant 'Date' n'a pas été trouvée.");
            }

            $requiredColumns = ['Date', 'Mat', 'Nom & Prénom', 'E1', 'S1', 'E2', 'S2'];
            $columnIndexes = findColumnIndices($sheet, $headerRow, $requiredColumns);

            $dateIndex = $columnIndexes['Date'];
            $matIndex = $columnIndexes['Mat'];
            $nomIndex = $columnIndexes['Nom & Prénom'];
            $E1Index = $columnIndexes['E1'];
            $S1Index = $columnIndexes['S1'];
            $E2Index = $columnIndexes['E2'];
            $S2Index = $columnIndexes['S2'];
            

            $insertedCount = 0; // Compteur pour les lignes insérées
            foreach ($sheet->getRowIterator($headerRow + 1) as $row) {
                $cellIterator = $row->getCellIterator();
                $cellIterator->setIterateOnlyExistingCells(false);
                $rowData = [];
                foreach ($cellIterator as $cell) {
                    $rowData[] = $cell->getValue();
                }

                $date = $rowData[$dateIndex - 1] ?? null;
                $date = convertDateFormat(convertToDate($date));
                $matricule = $rowData[$matIndex - 1] ?? null;
                $nomPrenom = $rowData[$nomIndex - 1] ?? null;
                $E1 = convertToTime($rowData[$E1Index - 1] ?? null);
                $S1 = convertToTime($rowData[$S1Index - 1] ?? null);
                $E2 = convertToTime($rowData[$E2Index - 1] ?? null);
                $S2 = convertToTime($rowData[$S2Index - 1] ?? null);
				
				


// Conversion des valeurs S1, E1, S2, E2 au format décimal
$decimalS1 = convertToDecimalHours($S1);
$decimalE1 = convertToDecimalHours($E1);
$decimalS2 = convertToDecimalHours($S2);
$decimalE2 = convertToDecimalHours($E2);
if ($matricule == '3881' || $matricule == '3882') {
    // Si E1 est inférieur à 8:00, le définir à 8:00
    if ($decimalE1 < 7) {
        $decimalE1 = 7; // Définir E1 à 8 heures si inférieur
    }
}else{
	if ($decimalE1 < 8) {
        $decimalE1 = 8; // Définir E1 à 8 heures si inférieur
		
	}
}
if ($matricule != '2175' ) {
if ($decimalS1 > 11.5) {
        $decimalS1 = 11.5; // Définir E1 à 8 heures si inférieur
    }
}else{
	if ($decimalS1 > 12.75) {
        $decimalS1 = 12.75; // Définir E1 à 8 heures si inférieur
    }
}
if ($matricule != '2175' ) {
	if ($decimalE2 < 12.25) {
        $decimalE2 = 12.25; // Définir E1 à 8 heures si inférieur	
	}
	}else{
		if ($decimalE2 < 13.5) {
        $decimalE2 = 13.5; // Définir E1 à 8 heures si inférieur	
	}
	}
	
if (empty($rowData[$E1Index - 1]) && empty($rowData[$E2Index - 1])) {
    // Si les deux valeurs E1 et E2 sont vides
    $Presence = 0;
} elseif (empty($rowData[$E1Index - 1]) && !empty($rowData[$E2Index - 1])) {
    // Si E1 est vide et E2 est non vide
    $Presence = ($decimalS2 - $decimalE2);
} elseif (!empty($rowData[$E1Index - 1]) && empty($rowData[$E2Index - 1])) {
    // Si E1 est non vide et E2 est vide
    $Presence = ($decimalS1 - $decimalE1);
} else {
    // Les deux valeurs sont non vides
    $Presence = ($decimalS1 - $decimalE1) + ($decimalS2 - $decimalE2);
}


                $ABS = '8.5' - $Presence ;
if ($ABS < 0) {
                    $ABS = 0;
                }
                if (empty($date) || empty($matricule)) {
                    continue;
                }

                // Vérifier l'existence des données dans la base de données
                $query = "SELECT COUNT(*) as count FROM disponibilite WHERE date = :date AND matricule = :matricule";
                $stmt = $conn->prepare($query);
                $stmt->bindValue(':date', $date, PDO::PARAM_STR);
                $stmt->bindValue(':matricule', $matricule, PDO::PARAM_STR);
                $stmt->execute();
                $count = $stmt->fetchColumn();

                if ($count > 0) {
                    continue;
                }

                $semaine = date('W', strtotime($date));

                $query = "INSERT INTO disponibilite (semaine, date, matricule, nomPrenom, e1, s1, e2, s2, presence, absence) VALUES (:semaine, :date, :matricule, :nomPrenom, :E1, :S1, :E2, :S2, :Presence, :ABS)";
                $stmt = $conn->prepare($query);

                $stmt->bindValue(':semaine', $semaine, PDO::PARAM_STR);
                $stmt->bindValue(':date', $date, PDO::PARAM_STR);
                $stmt->bindValue(':matricule', $matricule, PDO::PARAM_STR);
                $stmt->bindValue(':nomPrenom', $nomPrenom, PDO::PARAM_STR);
                $stmt->bindValue(':E1', $E1, PDO::PARAM_STR);
                $stmt->bindValue(':S1', $S1, PDO::PARAM_STR);
                $stmt->bindValue(':E2', $E2, PDO::PARAM_STR);
                $stmt->bindValue(':S2', $S2, PDO::PARAM_STR);
                $stmt->bindValue(':Presence', $Presence, PDO::PARAM_STR);
                $stmt->bindValue(':ABS', $ABS, PDO::PARAM_STR);

                if ($stmt->execute()) {
                    $insertedCount++;
                }
            }

            $conn = null;

            // Définir le message de succès après toutes les insertions
            if ($insertedCount > 0) {
                $_SESSION['alertClass'] = "alert-success";
                $_SESSION['message'] = "$insertedCount ligne(s) insérée(s) avec succès.";
            } else {
                $_SESSION['alertClass'] = "alert-info";
                $_SESSION['message'] = "Aucune nouvelle donnée insérée.";
            }

        } else {
            $_SESSION['alertClass'] = "alert-danger";
            $_SESSION['message'] = "Erreur lors du déplacement du fichier téléchargé.";
        }
    } else {
        $_SESSION['alertClass'] = "alert-danger";
        $_SESSION['message'] = "Type de fichier invalide. Veuillez télécharger un fichier Excel.";
    }

    // Rediriger vers la page list-effectif.php avec l'alerte
    header("Location: pointage.php");
    exit;
}
?>

