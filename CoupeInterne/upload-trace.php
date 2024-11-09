<?php
require '../vendor/autoload.php'; // Inclusion de PhpSpreadsheet
require '../php/db.php'; // Connexion à la base de données
require '../php/fonction.php'; // Inclusion des fonctions

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Cell\Cell;
use PhpOffice\PhpSpreadsheet\Cell\DefaultValueBinder;

class CustomValueBinder extends DefaultValueBinder {
    public function bindValue(Cell $cell, $value) {
        // Convertir les entiers et flottants en chaînes
        if (is_int($value) || is_float($value)) {
            $value = (string) $value;
        }
        return parent::bindValue($cell, $value);
    }
}

// Appliquer le Binder personnalisé
Cell::setValueBinder(new CustomValueBinder());

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_FILES["file"])) {
    $allowedFileTypes = [
        'application/vnd.ms-excel',
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        'text/csv'
    ];

    if (in_array($_FILES["file"]["type"], $allowedFileTypes)) {
        $targetPath = '../CoupeInterne/uploads/' . basename($_FILES['file']['name']);

        if (move_uploaded_file($_FILES['file']['tmp_name'], $targetPath)) {
            try {
                $reader = IOFactory::createReaderForFile($targetPath);
                $reader->setReadDataOnly(true); // Ignorer les styles
                $spreadsheet = $reader->load($targetPath);

                $sheet = $spreadsheet->getActiveSheet();
                $rowCount = 0;

                foreach ($sheet->getRowIterator() as $row) {
                    $rowCount++;
                    if ($rowCount === 1) {
                        continue; // Ignorer la première ligne (en-têtes)
                    }

                    $cellIterator = $row->getCellIterator();
                    $cellIterator->setIterateOnlyExistingCells(false); // Inclure les cellules vides
                    $rowData = [];

                    foreach ($cellIterator as $cell) {
                        $value = $cell->getValue();
                        $colonne = $cell->getColumn();

                        // Traitement de la colonne A : Convertir les nombres en dates
                        if ($colonne === 'A' && is_numeric($value)) {
                            $value = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($value)->format('Y-m-d');
                        } 
                        // Traitement de la colonne D : Conserver les nombres tels quels
                        elseif ($colonne === 'D' && is_numeric($value)) {
                            $value = (string) $value;
                        } 
                        // Autres colonnes : Convertir en chaîne
                        else {
                            $value = (string) $value;
                        }

                        $rowData[] = $value;
                    }

                    // Récupération des données avec vérification de l'existence
                    $date = $rowData[0] ?? null;
                    $commande = isset($rowData[1]) ? str_replace(',', '_', $rowData[1]) : null;
                    $article = $rowData[2] ?? null;
                    $metrage = $rowData[3] ?? null;
                    $atelier = $rowData[6] ?? null;
                    $note = $rowData[7] ?? null;
                    $etat = "Termine";

                    // Gestion de la date pour obtenir le numéro de la semaine
                    $semaine = null;
                    if ($date) {
                        $timestamp = strtotime($date);
                        if ($timestamp !== false) {
                            $semaine = date('W', $timestamp);
                        }
                    }

                    // Vérification de l'existence de la ligne
                    $checkQuery = "
                        SELECT COUNT(*) FROM trace 
                        WHERE date = :date 
                        AND commande = :commande 
                        AND article = :article 
                        AND metrage = :metrage 
                        AND atelier = :atelier 
                        AND note = :note";

                    $checkStmt = $conn->prepare($checkQuery);
                    $checkStmt->bindValue(':date', $date, PDO::PARAM_STR);
                    $checkStmt->bindValue(':commande', $commande, PDO::PARAM_STR);
                    $checkStmt->bindValue(':article', $article, PDO::PARAM_STR);
                    $checkStmt->bindValue(':metrage', $metrage, PDO::PARAM_STR);
                    $checkStmt->bindValue(':atelier', $atelier, PDO::PARAM_STR);
                    $checkStmt->bindValue(':note', $note, PDO::PARAM_STR);
                    $checkStmt->execute();

                    $exists = $checkStmt->fetchColumn() > 0;

                    if (!$exists) {
                        // Préparation de la requête d'insertion
                        $query = "
                            INSERT INTO trace (date, commande, article, metrage, atelier, note, Etat, fin, semaine) 
                            VALUES (:date, :commande, :article, :metrage, :atelier, :note, :etat, :fin, :semaine)";
                        $stmt = $conn->prepare($query);

                        // Liaison des paramètres
                        $stmt->bindValue(':date', $date, PDO::PARAM_STR);
                        $stmt->bindValue(':commande', $commande, PDO::PARAM_STR);
                        $stmt->bindValue(':article', $article, PDO::PARAM_STR);
                        $stmt->bindValue(':metrage', $metrage, PDO::PARAM_STR);
                        $stmt->bindValue(':atelier', $atelier, PDO::PARAM_STR);
                        $stmt->bindValue(':etat', $etat, PDO::PARAM_STR);
                        $stmt->bindValue(':note', $note, PDO::PARAM_STR);
                        $stmt->bindValue(':fin', $date, PDO::PARAM_STR);
                        $stmt->bindValue(':semaine', $semaine, PDO::PARAM_STR);

                        // Exécution de la requête
                        if (!$stmt->execute()) {
                            throw new Exception("Erreur lors de l'insertion : " . implode(' ', $stmt->errorInfo()));
                        }
                    }
                }

                // Redirection avec message de succès
                $type = "success";
                $message = "Données Excel importées dans la base de données.";
                header("Location: facture-trace.php");
                exit;

            } catch (Exception $e) {
                $type = "error";
                $message = "Erreur : " . $e->getMessage();
                echo($message); // Enregistrer l'erreur dans le log
            }

        } else {
            $type = "error";
            $message = "Erreur lors du déplacement du fichier téléchargé.";
        }
    } else {
        $type = "error";
        $message = "Type de fichier invalide. Veuillez télécharger un fichier Excel.";
    }
}
?>
