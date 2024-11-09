<?php
session_start();


// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../index.php");
    exit();
}
// Enable error reporting
ini_set('display_errors', 1); // Show errors on the page
ini_set('display_startup_errors', 1); // Show startup errors
error_reporting(E_ALL); // Report all types of errors

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
    'atelier' => '',
    'note' => '',
    'dateTrace' => ''
];

// Vérification de la soumission du formulaire
if (isset($_POST['Suivant'])) {
    // Collecter et filtrer les données du formulaire
    $formData = [
        'commande' => $_POST['commande'] ?? '',
        'article' => $_POST['article'] ?? '',
        'phase' => $_POST['phase'] ?? '',
        'atelier' => $_POST['atelier'] ?? '',
       
        'note' => $_POST['note'] ?? '',

        'dateTrace' => $_POST['dateTrace'] ?? ''
       
    ];




    // Procéder à l'insertion des données si tout est correct
    if (empty($message)) {
        $table = 'trace';
$conditions = "commande = :commande AND article = :article AND phase = :phase AND atelier = :atelier AND date = :date";
$params = [
    ':commande' => $formData['commande'],
    ':article' => $formData['article'],
    ':phase' => $formData['phase'],
    ':atelier' => $formData['atelier'],
    ':date' => $formData['dateTrace']
];

// Check for existing data
$existingCount = checkData($table, $conditions, $params, $conn);




        // Vérification de l'existence de la commande
        if ($existingCount > 0) {
            $alertClass = 'alert-danger';
            $message = "Cette commande existe déjà dans la base de données.";
        } else {
            


                $data = 'commande, article, phase, atelier, note, date,  Etat';
                $values = "'{$formData['commande']}', '{$formData['article']}', '{$formData['phase']}', '{$formData['atelier']}', '{$formData['note']}', '{$formData['dateTrace']}' , '$etat'";

                // Insertion des données dans la base
                $result = insertData($table, $data, $values, $conn);


        // Si l'insertion échoue, vous pouvez gérer l'erreur ici
        $alertClass = $result['alertClass'];
        $message = $result['message'];
    

                // Redirection après un succès d'insertion
                
            
        }
    }
}

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
function handleFileUpload($fileArray, $article, $commande) {
    $uploadFileDir = '../CoupeInterne/uploads/trace/' . $article . '/' . $commande;
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
        $fileName = basename($fileArray['name'][$key]); // Basename pour éviter les inclusions non sûres
        $fileTmpPath = $fileArray['tmp_name'][$key];
        $fileError = $fileArray['error'][$key];
        $dest_path = $uploadFileDir . '/' . $fileName;

        // Gestion des erreurs d'upload
        switch ($fileError) {
            case UPLOAD_ERR_OK:
                // Déplacer le fichier téléchargé vers le répertoire cible
                if (move_uploaded_file($fileTmpPath, $dest_path)) {
                    $result['files'][] = $fileName;
                } else {
                    $result['status'] = 'error';
                    $result['errors'][] = "Erreur lors de l'upload du fichier $fileName.";
                }
                break;

            case UPLOAD_ERR_INI_SIZE:
                $result['status'] = 'error';
                $result['errors'][] = "Le fichier $fileName dépasse la taille maximale autorisée par php.ini.";
                break;

            case UPLOAD_ERR_FORM_SIZE:
                $result['status'] = 'error';
                $result['errors'][] = "Le fichier $fileName dépasse la taille maximale autorisée par le formulaire.";
                break;

            case UPLOAD_ERR_PARTIAL:
                $result['status'] = 'error';
                $result['errors'][] = "Le fichier $fileName n'a été que partiellement téléchargé.";
                break;

            case UPLOAD_ERR_NO_FILE:
                $result['status'] = 'error';
                $result['errors'][] = "Aucun fichier fourni pour le téléchargement.";
                break;

            case UPLOAD_ERR_NO_TMP_DIR:
                $result['status'] = 'error';
                $result['errors'][] = "Répertoire temporaire manquant.";
                break;

            case UPLOAD_ERR_CANT_WRITE:
                $result['status'] = 'error';
                $result['errors'][] = "Échec de l'écriture du fichier $fileName sur le disque.";
                break;

            case UPLOAD_ERR_EXTENSION:
                $result['status'] = 'error';
                $result['errors'][] = "Une extension PHP a arrêté le téléchargement.";
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

    <title>Coupe Interne | Ajouter Un Traçé</title>

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
              <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Traçé /</span> Ajouter Un Traçé</h4>

              <div class="row">
                <div class="col-md-12">
                  
                  <div class="card mb-4">
                    <h5 class="card-header">Informations Générales</h5>
                   
                    
                    <div class="card-body">
					
                     <form method="POST" action="" enctype="multipart/form-data">






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
            required
            oninput="formatInput(this)"
			
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
        required
        title="Veuillez entrer une commande au format XXXXXXXXX"
        style="text-transform: uppercase;"
		
    />
</div>


                         <div class="mb-3 col-md-6">
    <label for="atelier" class="form-label">Atelier</label>
    <select id="atelier" name="atelier" class="select2 form-select" required>
        <option value=""></option>
        <option value="BIT TG">BIT TG</option>
        <option value="CREATION">CREATION</option>
        <option value="IAS">IAS</option>
        <option value="BSP">BSP</option>
        <option value="AH">AH</option>
        <option value="PRIMA">PRIMA</option>
        <option value="STARLETTE">STARLETTE</option>
        <option value="ROYAUME">ROYAUME</option>
        <option value="ES">ES</option>
        <option value="BABY">BABY</option>
        <option value="LEOMINOR">LEOMINOR</option>
        <option value="BHA">BHA</option>
        <option value="SOCAF">SOCAF</option>
        <option value="GNC">GNC</option>
        <option value="CQPF">CQPF</option>
        <option value="RAYEN">RAYEN</option>
        <option value="SPERANZA">SPERANZA</option>
        <option value="CYC">CYC</option>
        <option value="MAHDCO">MAHDCO</option>
        <option value="ENFAVET">ENFAVET</option>
        <option value="GSC">GSC</option>
        <option value="AFRO">AFRO</option>
        <option value="TEXPRINT">TEXPRINT</option>
        
    </select>
</div>

                          <div class="mb-3 col-md-6">
    <label for="phase" class="form-label">Phase</label>
    <select id="phase" name="phase" class="select2 form-select" required>
        <option value=""></option>
        <option value="Production" >Production</option>
        <option value="Campionario">Campionario</option>
        <option value="integration">Intégration</option>
        <option value="controle">Controle</option>
    </select>
</div>



                        
                            




                          
						 
						  <div class=" col-md-6">
                            <label class="form-label" for="dateChargement">Date Traçé</label>
                            <input
                              type="date"
                              class="form-control"
                              id="dateTrace"
                              name="dateTrace"
                              required
							  max="<?php echo date('Y-m-d'); ?>" 
							  value="<?php echo date('Y-m-d'); ?>" 
                            />
                          </div>
						   <div class="mb-3 col-md-6">
    <label for="article" class="form-label">Note</label>
    <input 
        class="form-control"
        type="text"
        name="note"
        id="note"
        
        
        
    />
</div>
                        
                          
                          
                        </div>
                        <div class=" text-end">
    <input type="submit" name="Suivant" class="btn btn-primary me-2" value="Enregistrer">
    
    <a href="ajouter-trace.php" class="btn btn-outline-secondary">Annuler</a>
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
