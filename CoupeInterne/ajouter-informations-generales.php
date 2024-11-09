<?php
session_start();


// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../index.php");
    exit();
}

// Inclusion des fichiers requis
require '../PHPMailer/src/PHPMailer.php';
require '../PHPMailer/src/Exception.php';
include("../php/db.php");
include("../php/fonction.php");

// Variables d'alertes
$alertClass = '';
$message = '';
$etat = "En attente";
$focusId = '';

$formData = [
    'commande' => '',
    'article' => '',
    'phase' => '',
    'categorie' => '',
    'traitement' => '',
    'relaxation' => '',
    'tissu' => '',
    'quantite' => '',
    'nbPartie' => '',
    'dateChargement' => '',
    'nbSub' => 0,
	'gestion' => '',
    'variante' => '',
    'semaine' => '',
    'subValues' => ''
];

// Vérification de la soumission du formulaire
if (isset($_POST['Suivant'])) {
    // Collecter et filtrer les données du formulaire
    $formData = [
        'commande' => $_POST['commande'] ?? '',
        'article' => strtoupper($_POST['article'] ?? ''),
        'phase' => $_POST['phase'] ?? '',
        'categorie' => $_POST['categorie'] ?? '',
        'traitement' => isset($_POST['traitement']) ? implode(',', $_POST['traitement']) : '',
        'relaxation' => $_POST['relaxation'] ?? '',
        'tissu' => strtoupper($_POST['tissu'] ?? ''),
        'quantite' => $_POST['quantite'] ?? '',
        'nbPartie' => $_POST['nbPartie'] ?? '',
		'variante' => $_POST['variante'] ?? '',
        'gestion' => $_POST['gestion'] ?? '',
        'dateChargement' => $_POST['dateChargement'] ?? '',
        'nbSub' => isset($_POST['sub']) ? count($_POST['sub']) : 0,
        'subValues' => isset($_POST['sub']) ? implode(',', $_POST['sub']) : '',
        'semaine' => $_POST['dateChargement'] ? date('W', strtotime($_POST['dateChargement'])) : ''
    ];

    // Validation des champs obligatoires
    foreach ($formData as $key => $value) {
        // Exclure 'nbSub' et 'semaine' de la vérification car ils peuvent être vides
        if (empty($value) && $key !== 'nbSub' && $key !== 'semaine' && $key !== 'traitement'&& $key !== 'gestion' ) {
            $alertClass = 'alert-danger';
            $message = "Le champ '$key' ne peut pas être vide.";
            $focusId = $key;
            break;
        }
    }

    // Vérification des valeurs sub sélectionnées
    if (!$focusId && empty($formData['subValues'])) {
        $alertClass = 'alert-danger';
        $message = "Vous devez sélectionner au moins un sub.";
        $focusId = 'Check01';
    }

    // Procéder à l'insertion des données si tout est correct
    if (empty($message)) {
        $table = 'db';
        
		$whereConditions = "commande = :commande AND article = :article";
            $params = ['commande' => $commande, 'article' => $article];
        $existingCount = checkData($table, $whereConditions,$params, $conn);

        // Vérification de l'existence de la commande
        if ($existingCount > 0) {
            $alertClass = 'alert-danger';
            $message = "Cette commande existe déjà dans la base de données.";
        } else {
            // Gestion de l'upload des fichiers
            $uploadResult = handleFileUpload($_FILES['file'], $formData['article']);
            if ($uploadResult['status'] === 'success') {
                $uploadedFilesStr = implode(',', $uploadResult['files']);
                $data = 'gestion,variante,commande, article, phase, categorie, traitement, relaxation, tissu, quantitedemandee, nbpartie, datechargement, nbsub, semaine, etat, fichier, subvalues';
                $values = "'{$formData['gestion']}','{$formData['variante']}','{$formData['commande']}', '{$formData['article']}', '{$formData['phase']}', '{$formData['categorie']}', '{$formData['traitement']}', '{$formData['relaxation']}', '{$formData['tissu']}', '{$formData['quantite']}', '{$formData['nbPartie']}', '{$formData['dateChargement']}', '{$formData['nbSub']}', '{$formData['semaine']}', '$etat', '$uploadedFilesStr', '{$formData['subValues']}'";

                // Insertion des données dans la base
                $result = insertData($table, $data, $values, $conn);

                // Affichage des résultats
                $alertClass = $result['alertClass'];
                $message = $result['message'];

                // Redirection après un succès d'insertion
                if ($alertClass === 'alert-success') {
					$subject = "Demande des informations pour la commande : {$formData['commande']}";
            
            // Préparer le message
            $message = "Bonjour,<br><br>
La commande <strong>{$formData['commande']}</strong> pour l'article <strong>{$formData['article']}</strong> a été chargée au sein du coupe interne.<br><br>
Merci de vous connecter au MES afin d'insérer le temps standard et le prix unitaire de cette commande.<br><br>";

if ($formData['nbPartie'] > 1) {
    $message .= "Cette commande comprend plusieurs parties. Afin d'assurer une efficacité optimale, nous vous prions de bien vouloir commencer le chronométrage spécifique à cette commande.<br><br> 
	Vous trouverez ci-dessous toutes les informations nécessaires.<br><br>";
}



            // Créer un tableau HTML avec toutes les données
$message .= "<table border='1' cellpadding='5' cellspacing='0' style='border-collapse: collapse;'>";

// Ligne d'en-têtes
$message .= "<tr>
<th>Date de chargement</th>
    <th>Commande</th>
    <th>Article</th>
    <th>Phase</th>
    <th>Catégorie</th>
    <th>Traitement</th>
    <th>Relaxation</th>
    <th>Tissu</th>
    <th>Quantité demandée</th>
    <th>Nombre de parties</th>
    
    <th>Nombre de sub</th>
</tr>";

// Ligne des valeurs
$message .= "<tr>
<td>{$formData['dateChargement']}</td>
    <td>{$formData['commande']}</td>
    <td>{$formData['article']}</td>
    <td>{$formData['phase']}</td>
    <td>{$formData['categorie']}</td>
    <td>{$formData['traitement']}</td>
    <td>{$formData['relaxation']}</td>
    <td>{$formData['tissu']}</td>
    <td>{$formData['quantite']}</td>
    <td>{$formData['nbPartie']}</td>
    
    <td>{$formData['nbSub']}</td>
</tr>";

$message .= "</table>";

$message .= "
<br><br> Merci de votre collaboration.<br>
MES.";

$testRecipients = ['nouha.benkhalifa@benetton.com','basma.aoichia@benetton.com']; // Remplacez par une adresse email de test valide
$cc = ['ahmed.zoghlami@benetton.com','zinatextile@gmail.com'];
// Appeler la fonction pour tester l'envoi
// sendEmail($testRecipients, $subject, $message , $cc);
                    header("Location: ajouter-details-matelas.php?nbPartie={$formData['nbPartie']}&subValues={$formData['subValues']}&commande={$formData['commande']}&article={$formData['article']}&phase={$formData['phase']}&dateChargement={$formData['dateChargement']}");
                    exit();
                }
            } else {
                // Affichage des erreurs d'upload
                $alertClass = 'alert-danger';
                $message = implode('<br>', $uploadResult['errors']);
            }
        }
    }
}

$allSubValues = ['01', '21', '31', '51', '02', '32'];
$firstColumnValues = ['01', '21', '31','41', '51'];
$secondColumnValues = ['02','22', '32','42','52'];
$thirdColumnValues = ['03', '23', '33'];



$formData['sub'] = isset($_POST['sub']) ? $_POST['sub'] : [];
// Affichage des alertes
echo '<div class="alert ' . $alertClass . ' fixed-top-right" role="alert" style="display: none;">';
echo $message;
echo '</div>';

// Script pour afficher/masquer les alertes
echo '<script>';
echo 'setTimeout(function() { document.querySelector(".alert.fixed-top-right").style.display = "block"; }, 200);';
echo 'setTimeout(function() { document.querySelector(".alert.fixed-top-right").style.display = "none"; }, 20000);';
echo '</script>';

// Fonction pour gérer l'upload des fichiers
function handleFileUpload($fileArray, $article) {
    $uploadFileDir = '../CoupeInterne/uploads/' . $article;
    $result = ['status' => 'success', 'files' => [], 'errors' => []];

    // Création du répertoire si nécessaire
    if (!is_dir($uploadFileDir)) {
        if (!mkdir($uploadFileDir, 0755, true)) {
            $result['status'] = 'error';
            $result['errors'][] = "Erreur lors de la création du répertoire: $uploadFileDir";
            return $result;
        }
    }

    // Parcourir les fichiers et traiter chaque upload
    foreach ($fileArray['tmp_name'] as $key => $tmp_name) {
        $fileName = $fileArray['name'][$key];
        $fileTmpPath = $fileArray['tmp_name'][$key];
        $fileError = $fileArray['error'][$key];
        $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        $dest_path = $uploadFileDir . '/' . $fileName;

        // Gestion des erreurs d'upload
        switch ($fileError) {
            case UPLOAD_ERR_OK:
                if ($fileExtension === 'pdf') {
                    if (move_uploaded_file($fileTmpPath, $dest_path)) {
                        $result['files'][] = $fileName;
                    } else {
                        $result['status'] = 'error';
                        $result['errors'][] = "Erreur lors de l'upload du fichier $fileName.";
                    }
                } else {
                    $result['status'] = 'error';
                    $result['errors'][] = "Le fichier $fileName n'est pas un PDF.";
                }
                break;
            case UPLOAD_ERR_INI_SIZE:
                $result['status'] = 'error';
                $result['errors'][] = "Le fichier $fileName dépasse la directive upload_max_filesize dans php.ini.";
                break;
            case UPLOAD_ERR_FORM_SIZE:
                $result['status'] = 'error';
                $result['errors'][] = "Le fichier $fileName dépasse la directive MAX_FILE_SIZE spécifiée dans le formulaire HTML.";
                break;
            case UPLOAD_ERR_PARTIAL:
                $result['status'] = 'error';
                $result['errors'][] = "Le fichier $fileName n'a été que partiellement téléchargé.";
                break;
            case UPLOAD_ERR_NO_FILE:
                $result['status'] = 'error';
                $result['errors'][] = "Aucun fichier téléchargé pour $fileName.";
                break;
            case UPLOAD_ERR_NO_TMP_DIR:
                $result['status'] = 'error';
                $result['errors'][] = "Répertoire temporaire manquant pour $fileName.";
                break;
            case UPLOAD_ERR_CANT_WRITE:
                $result['status'] = 'error';
                $result['errors'][] = "Échec de l'écriture du fichier $fileName sur le disque.";
                break;
            case UPLOAD_ERR_EXTENSION:
                $result['status'] = 'error';
                $result['errors'][] = "Une extension PHP a arrêté le téléchargement du fichier $fileName.";
                break;
            default:
                $result['status'] = 'error';
                $result['errors'][] = "Erreur inconnue lors du téléchargement du fichier $fileName.";
                break;
        }
    }

    return $result;
}
?>





<html
  lang="en"
  class="light-style layout-menu-fixed"
  dir="ltr"
  data-theme="theme-default"
  data-assets-path="../assets/"
  data-template="vertical-menu-template-free"
>
  <head>
    <meta charset="utf-8" />
    <meta
      name="viewport"
      content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0"
    />

    <title>Coupe Interne | Ajouter Une Commande</title>

    <meta name="description" content="" />

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="../assets/img/favicon/favicon.ico" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap"
      rel="stylesheet"
    />

    <!-- Icons. Uncomment required icon fonts -->
    <link rel="stylesheet" href="../assets/vendor/fonts/boxicons.css" />

    <!-- Core CSS -->
    <link rel="stylesheet" href="../assets/vendor/css/core.css" class="template-customizer-core-css" />
    <link rel="stylesheet" href="../assets/vendor/css/theme-default.css" class="template-customizer-theme-css" />
    <link rel="stylesheet" href="../assets/css/demo.css" />

    <!-- Vendors CSS -->
    <link rel="stylesheet" href="../assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />

    <!-- Page CSS -->
    <style>
    /* Style pour les alertes empil\u00e9es en haut à droite */
    .fixed-top-right {
        position: fixed;
        top: 10px;
        right: 10px;
        z-index: 9999;
    }
    </style>
    <!-- Helpers -->
    <script src="../assets/vendor/js/helpers.js"></script>

    <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->
    <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
    <script src="../assets/js/config.js"></script>
  </head>

  <body>
    <!-- Layout wrapper -->
    <div class="layout-wrapper layout-content-navbar">
      <div class="layout-container">
        <?php include 'menu.php'; ?>

        <!-- Layout container -->
        <div class="layout-page">
          <!-- Navbar -->

         <?php include 'navbar.php'; ?>

          <!-- Content wrapper -->
          <div class="content-wrapper">
            <!-- Content -->

            <div class="flex-grow-1 container-p-y container-fluid">
              <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Commandes /</span> Ajouter</h4>

              <div class="row">
                <div class="col-md-12">
                  
                  <div class="card mb-4">
                    <h5 class="card-header">Informations Générales</h5>
                   
                    
                    <div class="card-body">
					
                     <form method="POST" action="" enctype="multipart/form-data">
    <div class="d-flex align-items-start align-items-sm-center gap-4 mb-2">
        <img
            src="../assets/img/avatars/pdf.png"
            alt="pdf avatar"
            class="d-block rounded"
            height="100"
            width="180"
            id="uploadedAvatar"
        />

        <div class="button-wrapper">
            <input
                type="file"
                id="upload"
                accept=".pdf"
                name="file[]"
                multiple
                style="display: none;"
            />
            <label for="upload" class="btn btn-primary me-2 mb-4" tabindex="0">
                <span class="d-none d-sm-block">Sélectionner</span>
                <i class="bx bx-upload d-block d-sm-none"></i>
            </label>

            <!-- Bouton Annuler -->
            <button type="button" class="btn btn-outline-secondary me-2 mb-4">
                Annuler
            </button>
            
           

            <!-- Message des formats autorisés -->
            <p id="fileMessage" class="text-muted mb-0">Formats autorisés : PDF. Taille maximale de 5 Mo.</p>
        </div>
    </div>


<script>
document.getElementById('upload').addEventListener('change', function(event) {
    var fileInput = event.target;
    var fileMessage = document.getElementById('fileMessage');

    if (fileInput.files.length > 0) {
        var fileNames = Array.from(fileInput.files).map(file => file.name).join(', ');
        fileMessage.textContent = fileNames; // Affiche les noms de tous les fichiers sélectionnés
    } else {
        fileMessage.textContent = 'Formats autorisés : PDF. Taille maximale de 2 Mo.'; // Affiche le message par défaut
    }
});
</script>



                        <div class="row">
    <div class="mb-3 col-md-6">
        <label for="commande" class="form-label">Commande</label>
        <input
            class="form-control"
            type="text"
            id="commande"
            name="commande"
            maxlength="10"
            pattern="[A-Z0-9]{3}_[0-9]{4}_[0-9]"
            title="Veuillez entrer une commande au format xxx_xxxx_x"
            
            oninput="formatInput(this)"
			value="<?php echo htmlspecialchars($formData['commande']); ?>"
        />
    </div>

    <script>
        function formatInput(input) {
            let value = input.value.toUpperCase();
            value = value.replace(/[^A-Z0-9]/g, ''); // Supprime les caractères non autorisés
            let formattedValue = '';

            // Insère les tirets de soulignement aux positions appropriées
            if (value.length > 0) {
                formattedValue += value.slice(0, 3);
                if (value.length > 3) {
                    formattedValue += '_' + value.slice(3, 7);
                }
                if (value.length > 7) {
                    formattedValue += '_' + value.slice(7);
                }
            }

            input.value = formattedValue;
        }
    </script>

<div class="mb-3 col-md-6">
    <label for="article" class="form-label">Article</label>
    <input 
        class="form-control"
        type="text"
        name="article"
        id="article"
        maxlength="9"
		minlength="9"
        
        title="Veuillez entrer une commande au format XXXXXXXXX"
        style="text-transform: uppercase;"
		value="<?php echo htmlspecialchars($formData['article']); ?>"
    />
</div>


                         <div class="mb-3 col-md-6">
    <label for="phase" class="form-label">Phase</label>
    <select id="phase" name="phase" class="select2 form-select">
        <option value=""></option>
        <option value="Production" <?php echo $formData['phase'] == 'Production' ? 'selected' : ''; ?>>Production</option>
        <option value="Campionario" <?php echo $formData['phase'] == 'Campionario' ? 'selected' : ''; ?>>Campionario</option>
        <option value="Integration" <?php echo $formData['phase'] == 'Integration' ? 'selected' : ''; ?>>Integration</option>
    </select>
</div>

                          <div class="mb-3 col-md-6">
    <label for="categorie" class="form-label">Catégorie</label>
    <select id="categorie" name="categorie" class="select2 form-select">
        <option value=""></option>
        <option value="Uni" <?php echo $formData['categorie'] == 'Uni' ? 'selected' : ''; ?>>Uni</option>
        <option value="Raye" <?php echo $formData['categorie'] == 'Raye' ? 'selected' : ''; ?>>Rayé</option>
    </select>
</div>

                          <div class="mb-3 col-md-6">
    <label class="form-label" for="traitement">Traitement</label>
    <div class="row gy-3">
        <div class="col-md">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="traitement[]" value="SM" id="SM"   <?php echo in_array('SM', explode(',', $formData['traitement'])) ? 'checked' : ''; ?>/>
                <label class="form-check-label" for="SM"> SM </label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="traitement[]" value="RM" id="RM"  <?php echo in_array('RM', explode(',', $formData['traitement'])) ? 'checked' : ''; ?>/>
                <label class="form-check-label" for="RM"> RM </label>
            </div>
        </div>
    </div>
</div>

                        
                            
<div class="mb-3 col-md-6">
    <label for="address" class="form-label">Relaxation</label>
    <div class="row gy-3">
        <div class="col-md">
            <div class="form-check">
                <input
                    name="relaxation"
                    class="form-check-input"
                    type="radio"
                    value="Oui"
                    id="RadioOUI"
					<?php echo $formData['relaxation'] === 'Oui' ? 'checked' : ''; ?>
                />
                <label class="form-check-label" for="RadioOUI"> Oui </label>
            </div>
            <div class="form-check">
                <input
                    name="relaxation"
                    class="form-check-input"
                    type="radio"
                    value="Non"
                    id="RadioNon"
                    <?php echo $formData['relaxation'] === 'Non' ? 'checked' : ''; ?>
                />
                <label class="form-check-label" for="RadioNon"> Non </label>
            </div>
        </div>
    </div>
</div>

                          <div class="mb-3 col-md-6">
    <label for="state" class="form-label">Tissu</label>
    <input class="form-control" type="text" id="tissu" name="tissu" maxlength="3" style="text-transform: uppercase;"  title="Veuillez entrer seulement les 3 lettres majuscules de code tissu" value="<?php echo htmlspecialchars($formData['tissu']); ?>" />
</div>

                          <div class="mb-3 col-md-6">
                            <label for="quantite" class="form-label">Quantité</label>
                            <input
                              type="number"
                              class="form-control"
                              id="quantite"
                              name="quantite"
                              value="<?php echo htmlspecialchars($formData['quantite']); ?>"
                            />
                          </div>
						  <div class="col">
						  <div class=" col-md">
                            <label class="form-label" for="nbPartie">Variante</label>
                            <input
                              type="text"
                              class="form-control"
                              id="variante"
                              name="variante"
							  oninput="format(this)"
                              value="<?php echo htmlspecialchars($formData['variante']); ?>"
                            />
                          </div>
                          <div class=" col-md">
                            <label class="form-label" for="nbPartie">Nombre Partie</label>
                            <input
                              type="number"
                              class="form-control"
                              id="nbPartie"
                              name="nbPartie"
                              value="<?php echo htmlspecialchars($formData['nbPartie']); ?>"
                            />
                          </div>
						  <div class=" col-md">
                            <label class="form-label" for="dateChargement">Gestion</label>
                            <input
                              type="text"
                              class="form-control"
                              id="gestion"
                              name="gestion"
                              value="<?php echo htmlspecialchars($formData['gestion']); ?>"
                            />
                          </div>
						  <div class=" col-md">
                            <label class="form-label" for="dateChargement">Date Chargement</label>
                            <input
                              type="date"
                              class="form-control"
                              id="dateChargement"
                              name="dateChargement"
                              value="<?php echo htmlspecialchars($formData['dateChargement']); ?>"
                            />
                          </div>
						   </div>
                         <div class="mb-3 col-md-6">
                <label for="language" class="form-label">Sub</label>
               <div class="row gy-3">
                    <div class="col-md">
                        <!-- Première colonne -->
                        <?php foreach ($firstColumnValues as $value): ?>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="sub[]" value="<?php echo $value; ?>" id="Check<?php echo $value; ?>"
                                <?php echo in_array($value, $formData['sub']) ? 'checked' : ''; ?> />
                                <label class="form-check-label" for="Check<?php echo $value; ?>">
                                    <?php echo $value; ?>
                                </label>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <div class="col-md">
                        <!-- Deuxième colonne -->
                        <?php foreach ($secondColumnValues as $value): ?>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="sub[]" value="<?php echo $value; ?>" id="Check<?php echo $value; ?>"
                                <?php echo in_array($value, $formData['sub']) ? 'checked' : ''; ?> />
                                <label class="form-check-label" for="Check<?php echo $value; ?>">
                                    <?php echo $value; ?>
                                </label>
                            </div>
                        <?php endforeach; ?>
                    </div>
					<div class="col-md">
                        <!-- Deuxième colonne -->
                        <?php foreach ($thirdColumnValues as $value): ?>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="sub[]" value="<?php echo $value; ?>" id="Check<?php echo $value; ?>"
                                <?php echo in_array($value, $formData['sub']) ? 'checked' : ''; ?> />
                                <label class="form-check-label" for="Check<?php echo $value; ?>">
                                    <?php echo $value; ?>
                                </label>
                            </div>
                        <?php endforeach; ?>
                    </div>
					
                </div>

            </div>
                          
                          
                        </div>
                        <div class=" text-end">
    <input type="submit" name="Suivant" class="btn btn-primary me-2" value="Suivant">
    
    <a href="ajouter-informations-generales.php" class="btn btn-outline-secondary">Annuler</a>
</div>

                      </form>
                    </div>
                    <!-- /Account -->
                  </div>
                  
                </div>
              </div>
            </div>
            <!-- / Content -->

            

            <div class="content-backdrop fade"></div>
          </div>
          <!-- Content wrapper -->
        </div>
        <!-- / Layout page -->
      </div>

      <!-- Overlay -->
      <div class="layout-overlay layout-menu-toggle"></div>
    </div>
    <!-- / Layout wrapper -->



    <!-- Core JS -->
    <!-- build:js assets/vendor/js/core.js -->
    <script src="../assets/vendor/libs/jquery/jquery.js"></script>
    <script src="../assets/vendor/libs/popper/popper.js"></script>
    <script src="../assets/vendor/js/bootstrap.js"></script>
    <script src="../assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>

    <script src="../assets/vendor/js/menu.js"></script>
    <!-- endbuild -->

    <!-- Vendors JS -->
<script>
        document.addEventListener('DOMContentLoaded', function() {
            <?php if (!empty($errors)): ?>
                var firstErrorField = document.querySelector('.error');
                if (firstErrorField) {
                    firstErrorField.focus(); // Mettre le focus sur le premier champ avec l'erreur
                }
            <?php endif; ?>
        });
    </script>
    <!-- Main JS -->
    <script src="../assets/js/main.js"></script>

    <!-- Page JS -->
    <script src="../assets/js/pages-account-settings-account.js"></script>

    <!-- Place this tag in your head or just before your close body tag. -->
    <script async defer src="https://buttons.github.io/buttons.js"></script>
  </body>
</html>
