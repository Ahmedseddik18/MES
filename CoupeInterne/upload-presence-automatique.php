<?php
session_start();
require 'C:/Apache24/htdocs/MES/Classes/PHPExcel.php'; // Inclure PHPExcel
require 'C:/Apache24/htdocs/MES/php/db.php'; // Inclure votre connexion à la base de données
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

function getAttachments($inbox, $email_number, $part, $partNumber = null) {
    $attachments = [];

    // Si la partie contient des sous-parties, on les parcourt
    if (isset($part->parts) && count($part->parts)) {
        foreach ($part->parts as $index => $subPart) {
            // Construire le numéro de partie complet pour les sous-parties
            $newPartNumber = $partNumber ? $partNumber . "." . ($index + 1) : ($index + 1);
            // Récursivement chercher les pièces jointes
            $attachments = array_merge($attachments, getAttachments($inbox, $email_number, $subPart, $newPartNumber));
        }
    } else {
        // Vérifier si c'est une pièce jointe
        $isAttachment = isset($part->disposition) && (strtolower($part->disposition) == 'attachment' || strtolower($part->disposition) == 'inline');
        
        if ($isAttachment) {
            // Récupérer le nom de fichier depuis dparameters ou parameters
            $filename = null;
            if (isset($part->dparameters)) {
                foreach ($part->dparameters as $param) {
                    if (strtolower($param->attribute) == 'filename') {
                        $filename = $param->value;
                        break;
                    }
                }
            }
            if (!$filename && isset($part->parameters)) {
                foreach ($part->parameters as $param) {
                    if (strtolower($param->attribute) == 'name') {
                        $filename = $param->value;
                        break;
                    }
                }
            }

            // Vérifier si le fichier est bien un fichier Excel (xlsx ou xls)
            if ($filename && preg_match("/\.(xlsx|xls)$/i", $filename)) {
                echo "Nom de fichier détecté : " . $filename . "<br>";

                // Lire et décoder la pièce jointe
                $attachment = imap_fetchbody($inbox, $email_number, $partNumber);
                if ($part->encoding == 3) { // BASE64 encoding
                    $attachment = base64_decode($attachment);
                } elseif ($part->encoding == 4) { // QUOTED-PRINTABLE encoding
                    $attachment = quoted_printable_decode($attachment);
                }

                // Ajouter l'attachement à la liste
                $attachments[] = ['filename' => $filename, 'data' => $attachment];
            }
        }
    }

    return $attachments;
}


// Fonction principale pour télécharger les pièces jointes
function downloadExcelFromGmail($imapServer, $username, $password, $savePath) {
    // Connexion au serveur Gmail
    $inbox = imap_open($imapServer, $username, $password) or die('Impossible de se connecter: ' . imap_last_error());

    // Rechercher les emails contenant "Rapport journalier d'hier" dans l'objet
    $emails = imap_search($inbox, 'SUBJECT "Rapport journalier d\'hier" UNSEEN');

    if ($emails) {
        rsort($emails); // Trier les emails du plus récent au plus ancien

        foreach ($emails as $email_number) {
            $overview = imap_fetch_overview($inbox, $email_number, 0);

            // Vérification de la structure pour trouver les pièces jointes
            $structure = imap_fetchstructure($inbox, $email_number);
            echo "Structure de l'email analysée.<br>";
            $attachments = getAttachments($inbox, $email_number, $structure);

            if (!empty($attachments)) {
                foreach ($attachments as $attachment) {
                    $filename = $attachment['filename'];
                    echo "Téléchargement de la pièce jointe : " . $filename . "<br>";

                    // Vérifier si le répertoire existe, sinon le créer
                    if (!is_dir($savePath)) {
                        mkdir($savePath, 0777, true);
                        chmod($savePath, 0777);
                        echo "Répertoire créé : " . $savePath . "<br>";
                    }

                    // Enregistrer le fichier dans le répertoire spécifié
                    $filePath = $savePath . '/' . $filename;
                    file_put_contents($filePath, $attachment['data']);

                    // Fermer l'IMAP après téléchargement
                    imap_close($inbox);

                    return $filePath; // Retourner le chemin du fichier téléchargé
                }
            } else {
                echo "Pas de pièces jointes trouvées.<br>";
            }
        }
    } else {
        echo 'Aucun email correspondant trouvé.<br>';
    }

    imap_close($inbox);
    return false;
}

// Connexion IMAP et récupération du fichier
$imapServer = '{imap.gmail.com:993/imap/ssl}INBOX';
$username = 'mesbenetton@gmail.com';
$password = 'uhad lhav jdzq sqcx'; // Assurez-vous d'utiliser un mot de passe d'application Gmail
$savePath = '../CoupeInterne/uploads';

$uploadedFilePath = downloadExcelFromGmail($imapServer, $username, $password, $savePath);
if ($uploadedFilePath) {
    // Si le fichier a été téléchargé avec succès, traiter le fichier Excel
    $objPHPExcel = PHPExcel_IOFactory::load($uploadedFilePath);
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
				$datetime = new DateTime($date);
				


	

                if (empty($date) || empty($matricule)) {
                    continue;
                }
				if (empty($nomPrenom) )) {
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
    continue; // Si les données existent déjà, passer à l'itération suivante
}

// Récupérer la semaine à partir de la date
$semaine = date('W', strtotime($date));

// Préparer la requête d'insertion
$query = "INSERT INTO disponibilite (semaine, date, matricule, nomPrenom, e1, s1, e2, s2) VALUES (:semaine, :date, :matricule, :nomPrenom, :E1, :S1, :E2, :S2)";
$stmt = $conn->prepare($query);

// Lier les valeurs
$stmt->bindValue(':semaine', $semaine, PDO::PARAM_INT); // Si semaine est un entier
$stmt->bindValue(':date', $date, PDO::PARAM_STR);
$stmt->bindValue(':matricule', $matricule, PDO::PARAM_STR);
$stmt->bindValue(':nomPrenom', $nomPrenom, PDO::PARAM_STR);
$stmt->bindValue(':E1', $E1, PDO::PARAM_STR);
$stmt->bindValue(':S1', $S1, PDO::PARAM_STR);
$stmt->bindValue(':E2', $E2, PDO::PARAM_STR);
$stmt->bindValue(':S2', $S2, PDO::PARAM_STR);

// Exécuter la requête d'insertion
if ($stmt->execute()) {
    $insertedCount++; // Compter les insertions réussies
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

        
   

    
    exit;
} else {
    echo "Erreur : le fichier n'a pas été téléchargé.";
}
?>

