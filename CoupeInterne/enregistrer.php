<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../../index.php");
    exit();
}

require '../PHPMailer/src/PHPMailer.php';
require '../PHPMailer/src/Exception.php';
include("../php/db.php");
include("../php/fonction.php");

$formData = [
    'commande' => $_POST['commande'] ?? '',
    'article' => $_POST['article'] ?? '',
    'phase' => $_POST['phase'] ?? '',
    'atelier' => $_POST['atelier'] ?? '',
    'note' => $_POST['note'] ?? '',
    'dateTrace' => $_POST['dateTrace'] ?? '',
    'fileNames' => $_POST['fileNames'] ?? '' // Liste des noms de fichiers séparés par des virgules
];

if (isset($_POST['Suivant'])) {
    $table = 'trace';
    $params = [
        ':commande' => $formData['commande'],
        ':article' => $formData['article'],
        ':phase' => $formData['phase'],
        ':atelier' => $formData['atelier'],
        ':date' => $formData['dateTrace']
    ];

    $existingCount = checkData($table, "commande = :commande AND article = :article AND phase = :phase AND atelier = :atelier AND date = :date", $params, $conn);

    if ($existingCount > 0) {
        echo "Cette commande existe déjà.";
    } else {
        $data = 'commande, article, phase, atelier, note, date, Etat, fichier';
        $values = "'{$formData['commande']}', '{$formData['article']}', '{$formData['phase']}', '{$formData['atelier']}', '{$formData['note']}', '{$formData['dateTrace']}', 'En attente', '{$formData['fileNames']}'";

        $result = insertData($table, $data, $values, $conn);

        echo $result['message'];
    }
}
?>
