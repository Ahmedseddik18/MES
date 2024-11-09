<?php
// Inclure PHPMailer si non installé via Composer
require '../PHPMailer/src/PHPMailer.php';
require '../PHPMailer/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

/**
 * Fonction d'envoi d'email avec PHPMailer.
 */
function sendEmail($to, $subject, $body) {
    $mail = new PHPMailer(true); // Création d'une nouvelle instance PHPMailer

    try {
        // Configuration de l'email pour utiliser Sendmail
        $mail->isSendmail();
        $mail->Sendmail = '../sendmail/sendmail.exe'; // Ajustez le chemin selon votre configuration

        // Expéditeur
        $mail->setFrom('cqmpbenetton@gmail.com', 'CQMP');

        // Destinataires
        foreach ($to as $recipient) {
            if (filter_var($recipient, FILTER_VALIDATE_EMAIL)) {
                $mail->addAddress($recipient);
            } else {
                echo "Adresse email invalide : $recipient<br>";
            }
        }

        // Configuration du contenu de l'email
        $mail->isHTML(true);
        $mail->CharSet = 'UTF-8';
        $mail->Subject = $subject;
        $mail->Body = $body;

        // Envoi de l'email
        $mail->send();
        echo 'L\'email a été envoyé avec succès.<br>';
    } catch (Exception $e) {
        echo 'L\'email n\'a pas pu être envoyé. Erreur : ' . $mail->ErrorInfo . '<br>';
    }
}

// Test de la fonction sendEmail
$testRecipients = ['ahmed.zoghlami@benetton.com']; // Remplacez par une adresse email de test valide
$testSubject = 'Test d\'envoi d\'email';
$testBody = '<h1>Ceci est un test d\'envoi d\'email</h1><p>Si vous voyez ce message, l\'envoi a réussi.</p>';

// Appel de la fonction pour tester l'envoi
sendEmail($testRecipients, $testSubject, $testBody);
?>
