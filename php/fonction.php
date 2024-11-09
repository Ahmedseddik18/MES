<?php
include("db.php");
function updateData($table, $data, $conditions, $params, $conn) {
    // Build the SET clause
    $set = [];
    foreach ($data as $column => $value) {
        $set[] = "$column = :$column";
    }
    $set = implode(", ", $set);

    // Prepare the SQL statement
    $sql = "UPDATE $table SET $set WHERE $conditions";

    try {
        $stmt = $conn->prepare($sql);

        // Bind values for the SET clause
        foreach ($data as $column => $value) {
            $stmt->bindValue(":$column", $value);
        }

        // Bind values for the WHERE conditions
        foreach ($params as $key => $value) {
            $stmt->bindValue(":$key", $value);
        }

        // Execute the statement
        $stmt->execute();

        // Return success message
        return [
            'alertClass' => 'alert-success',
            'message' => 'Mise à jour réussie.'
        ];
    } catch (PDOException $e) {
        // Return error message
        return [
            'alertClass' => 'alert-danger',
            'message' => 'Erreur lors de la mise à jour : ' . $e->getMessage()
        ];
    }
}



// Fonction pour insérer les données dans la table planification de manière unique
function insertIntoPlanification($conn, $affectation) {
    // Vérifier si l'entrée existe déjà
    $sql = "SELECT COUNT(*) FROM planification 
            WHERE commande = :commande 
              AND article = :article 
              AND phase = :phase 
              AND operateur1 = :operateur1 
              AND operateur2 = :operateur2 
              AND datetime_debut = :datetime_debut 
              AND datetime_fin = :datetime_fin";
    $stmt = $conn->prepare($sql);
    $stmt->execute([
        ':commande' => $affectation['commande'],
        ':article' => $affectation['article'],
        ':phase' => $affectation['phase'],
        ':operateur1' => $affectation['operateur1'],
        ':operateur2' => $affectation['operateur2'],
        ':datetime_debut' => $affectation['debut'],
        ':datetime_fin' => $affectation['fin']
    ]);

    $count = $stmt->fetchColumn();
    if ($count == 0) {
        // L'entrée n'existe pas, donc insérer
        $sql = "INSERT INTO planification (commande, article, partie, sub, quantiteMatelas, codeMatelas, operateur1, operateur2, phase, datetime_debut, datetime_fin, HS)
                VALUES (:commande, :article, :partie, :sub, :quantiteMatelas, :codeMatelas, :operateur1, :operateur2, :phase, :datetime_debut, :datetime_fin, :HS)";
        $stmt = $conn->prepare($sql);
        $stmt->execute([
            ':commande' => $affectation['commande'],
            ':article' => $affectation['article'],
            ':partie' => $affectation['partie'] ?? null,
            ':sub' => $affectation['sub'] ?? null,
            ':quantiteMatelas' => $affectation['quantiteMatelas'] ?? null,
            ':codeMatelas' => $affectation['codeMatelas'],
            ':operateur1' => $affectation['operateur1'],
            ':operateur2' => $affectation['operateur2'],
            ':phase' => $affectation['phase'],
            ':datetime_debut' => $affectation['debut'],
            ':datetime_fin' => $affectation['fin'],
            ':HS' => $affectation['duree'] // Ajouter la durée à la colonne HS
        ]);
    }
}
function insertData($table, $data, $values, $conn) {
    // Prepare the insert query using placeholders for values
    $insertQuery = "INSERT INTO \"$table\" ($data) VALUES ($values)";

    try {
        // Prepare the statement
        $stmt = $conn->prepare($insertQuery);
        
        // Execute the statement with the values provided
        if ($stmt->execute()) {
            $alertClass = 'alert-success';
            $message = "Données insérées avec succès !";
        } else {
            $alertClass = 'alert-danger';
            $message = "Erreur lors de l'insertion !";
        }
    } catch (PDOException $e) {
        $alertClass = 'alert-danger';
        $message = "Erreur lors de l'insertion : " . $e->getMessage();
    }

    return array('alertClass' => $alertClass, 'message' => $message);
}

function select($conn, $selectFields, $fromTables, $whereConditions, $orderBy) {
    try {
        // Construire la requête SQL
        $query = "SELECT $selectFields FROM \"$fromTables\" WHERE $whereConditions";
        if (!empty($orderBy)) {
            $query .= " ORDER BY $orderBy";
        }

        // Préparer et exécuter la requête
        $stmt = $conn->prepare($query);
        $stmt->execute();

        return $stmt;
    } catch (PDOException $e) {
        echo "Erreur : " . $e->getMessage();
        return false;
    }
}

function selectData($conn, $selectFields, $fromTables, $leftJoins, $whereConditions, $orderBy) {
    try {
        // Construire la requête SQL
        $query = "
            SELECT
                $selectFields
            FROM
                \"$fromTables\"
            $leftJoins
            WHERE
                $whereConditions
            ORDER BY
                $orderBy";

        // Préparer et exécuter la requête
        $stmt = $conn->prepare($query);
        $stmt->execute();

        return $stmt;
    } catch (PDOException $e) {
        echo "Erreur : " . $e->getMessage();
        return false;
    }
}
function checkData($table, $conditions, $params, $conn) {
    try {
        // Préparer la requête SQL avec les conditions dynamiques
        $query = "SELECT COUNT(*) AS count FROM \"$table\" WHERE $conditions";
        $stmt = $conn->prepare($query);
        
        // Lier les paramètres
        foreach ($params as $key => &$value) {
            $stmt->bindParam($key, $value);
        }
        
        // Exécuter la requête
        $stmt->execute();
        
        // Récupérer le résultat
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? $row['count'] : 0; // Retourne le nombre d'occurrences trouvées
    } catch (PDOException $e) {
        // Gérer les erreurs en cas de problème
        return -1; // Retourne une valeur indiquant une erreur
    }
}


function delete($conn, $fromTables, $whereConditions) {
    try {
        // Construire la requête SQL DELETE
        $query = "DELETE FROM $fromTables WHERE $whereConditions";

        // Préparer et exécuter la requête
        $stmt = $conn->prepare($query);
        $stmt->execute();

        // Vérifier si la suppression a réussi
        if ($stmt->rowCount() > 0) {
            return ['alertClass' => 'alert-success', 'message' => 'Suppression réussie.'];
        } else {
            return ['alertClass' => 'alert-warning', 'message' => 'Aucun enregistrement supprimé.'];
        }
    } catch (PDOException $e) {
        // Gestion des erreurs
        return ['alertClass' => 'alert-danger', 'message' => "Erreur : " . $e->getMessage()];
    }
}



use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;



function sendEmail($to, $subject, $body, $cc = []) {
    $mail = new PHPMailer(true); // Création d'une nouvelle instance PHPMailer

    try {
        // Configuration de l'email pour utiliser Sendmail
        $mail->isSendmail();
        $mail->Sendmail = 'C:/wamp64/www/MES/sendmail/sendmail.exe'; // Ajustez le chemin selon votre configuration

        // Expéditeur
        $mail->setFrom('mesbenetton@gmail.com', 'MES');

        // Destinataires
        foreach ($to as $recipient) {
            if (filter_var($recipient, FILTER_VALIDATE_EMAIL)) {
                $mail->addAddress($recipient);
            } else {
                echo "Adresse email invalide : $recipient<br>";
            }
        }

        // Ajouter les destinataires en CC
        foreach ($cc as $ccRecipient) {
            if (filter_var($ccRecipient, FILTER_VALIDATE_EMAIL)) {
                $mail->addCC($ccRecipient);
            } else {
                echo "Adresse email CC invalide : $ccRecipient<br>";
            }
        }

        // Configuration du contenu de l'email
        $mail->isHTML(true);
        $mail->CharSet = 'UTF-8';
        $mail->Subject = $subject;
        $mail->Body = $body;

        // Envoi de l'email
        $mail->send();
        
    } catch (Exception $e) {
        echo 'L\'email n\'a pas pu être envoyé. Erreur : ' . $mail->ErrorInfo . '<br>';
    }
}





?>