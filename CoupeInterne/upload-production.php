<?php
require '../Classes/PHPExcel.php'; // Inclure PHPExcel (ou PhpSpreadsheet)
require '../php/db.php'; // Inclure votre connexion à la base de données

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["file"])) {
    $targetPath = '../CoupeInterne/uploads/' . basename($_FILES['file']['name']);

    // Déplacer le fichier téléchargé
    if (move_uploaded_file($_FILES['file']['tmp_name'], $targetPath)) {
        // Charger le fichier Excel
        try {
            $objPHPExcel = PHPExcel_IOFactory::load($targetPath);
        } catch (Exception $e) {
            die('Erreur lors du chargement du fichier Excel : ' . $e->getMessage());
        }

        $sheet = $objPHPExcel->getActiveSheet();
        $rowCount = 0; // Initialiser le compteur de lignes

        // Démarrer une transaction
        $conn->beginTransaction();
        try {
            // Itérer à travers chaque ligne
            foreach ($sheet->getRowIterator() as $row) {
                $rowCount++;
                if ($rowCount <= 1) {
                    continue; // Ignorer la première ligne (en-têtes)
                }

                $cellIterator = $row->getCellIterator();
                $cellIterator->setIterateOnlyExistingCells(false); // Boucler à travers toutes les cellules, même vides
                $rowData = [];

                foreach ($cellIterator as $cell) {
                    $value = $cell->getValue();
                    // Convertir les valeurs en chaîne pour la base de données
                    $rowData[] = (string)$value; // Force la conversion en chaîne
                }

                // Validation et nettoyage des données de la ligne avant l'accès
                $commande = isset($rowData[0]) ? $rowData[0] : null;
                $article = isset($rowData[1]) ? str_replace(' ', '', $rowData[1]) : null;
                $sub = isset($rowData[2]) ? ($rowData[2] == '1' ? '01' : $rowData[2]) : null;
                $quantite = isset($rowData[3]) ? $rowData[3] : null;
                $phase = isset($rowData[4]) ? $rowData[4] : null;
                $dateValue = isset($rowData[5]) ? $rowData[5] : null; // Assurez-vous que c'est une chaîne

                // Récupérer les opérateurs
                $operateur1 = isset($rowData[6]) ? $rowData[6] : null;
                $operateur2 = isset($rowData[7]) ? $rowData[7] : '0';
                $HR = isset($rowData[8]) ? $rowData[8] : null;

                // Insérer les données dans la base de données
                $query = "INSERT INTO production (commande, article, sub, quantite, phase, date, operateur1, operateur2, HR)
                          VALUES (:commande, :article, :sub, :quantite, :phase, :date, :operateur1, :operateur2, :HR)";
                $stmt = $conn->prepare($query);

                $stmt->bindValue(':commande', $commande, PDO::PARAM_STR);
                $stmt->bindValue(':article', $article, PDO::PARAM_STR);
                $stmt->bindValue(':sub', $sub, PDO::PARAM_STR);
                $stmt->bindValue(':quantite', $quantite, PDO::PARAM_STR);
                $stmt->bindValue(':phase', $phase, PDO::PARAM_STR);
                $stmt->bindValue(':date', $dateValue, PDO::PARAM_STR); // Utiliser la date
                $stmt->bindValue(':operateur1', $operateur1, PDO::PARAM_STR);
                $stmt->bindValue(':operateur2', $operateur2, PDO::PARAM_STR);
                $stmt->bindValue(':HR', $HR, PDO::PARAM_STR);

                $stmt->execute();
            }

            $conn->commit(); // Valider la transaction
            $alertClass = "alert-success";
            $message = "Données Excel importées dans la base de données.";
        } catch (PDOException $e) {
            // Annuler la transaction en cas d'erreur
            $conn->rollBack();
            $alertClass = "alert-danger";
            $message = "Erreur lors de l'insertion des données : " . $e->getMessage();
        }

        // Fermer la connexion à la base de données
        $conn = null;
    } else {
        $alertClass = "alert-danger";
        $message = "Erreur lors du déplacement du fichier téléchargé.";
    }

    // Redirection avec des variables encodées
    header("Location: list-tissu.php?type=" . urlencode($alertClass) . "&message=" . urlencode($message));
    exit;
}
?>
