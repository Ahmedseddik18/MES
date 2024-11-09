<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../../index.php");
    exit();
}


include("../php/db.php");
include("../php/fonction.php");

// Récupérer les valeurs passées via GET (par exemple dans l'URL)
$commande = htmlspecialchars($_GET['commande']);
$article = htmlspecialchars($_GET['article']);
$phase = htmlspecialchars($_GET['phase']);
$atelier = htmlspecialchars($_GET['atelier']);
$note = htmlspecialchars($_GET['note']);
$dateTrace = htmlspecialchars($_GET['date']);
$etat = htmlspecialchars($_GET['etat']);

// Fonction d'upload et de mise à jour de la base de données
function handleFileUpload($fileArray, $commande, $article, $phase, $atelier, $note, $dateTrace, $etat, $conn) {
    $uploadFileDir = '../CoupeInterne/uploads/trace/' . $article . '/' . $commande;
    $result = ['status' => 'success', 'files' => [], 'errors' => []];

    // Créer le répertoire si nécessaire
    if (!is_dir($uploadFileDir)) {
        if (!mkdir($uploadFileDir, 0755, true)) {
            $result['status'] = 'error';
            $result['errors'][] = "Erreur lors de la création du répertoire: $uploadFileDir";
            return $result;
        }
    }

    // Parcourir les fichiers et traiter chaque upload
    foreach ($fileArray['tmp_name'] as $key => $tmp_name) {
        $fileName = basename($fileArray['name'][$key]);
        $fileTmpPath = $fileArray['tmp_name'][$key];
        $fileError = $fileArray['error'][$key];
        $dest_path = $uploadFileDir . '/' . $fileName;

        // Gestion des erreurs d'upload
        switch ($fileError) {
            case UPLOAD_ERR_OK:
                // Déplacer le fichier téléchargé vers le répertoire cible
                if (move_uploaded_file($fileTmpPath, $dest_path)) {
                    $result['files'][] = $fileName;

                    // Mettre à jour la base de données avec le chemin du fichier
                    $query = "
                        UPDATE trace
                        SET fichier = :file_path
                        WHERE commande = :commande 
                        AND article = :article
                        AND phase = :phase
                        AND atelier = :atelier
                        AND note = :note
                        AND date = :date
                        AND etat = :etat
                    ";

                    $stmt = $conn->prepare($query);
                    $stmt->bindParam(':file_path', $dest_path);
                    $stmt->bindParam(':commande', $commande);
                    $stmt->bindParam(':article', $article);
                    $stmt->bindParam(':phase', $phase);
                    $stmt->bindParam(':atelier', $atelier);
                    $stmt->bindParam(':note', $note);
                    $stmt->bindParam(':date', $dateTrace);
                    $stmt->bindParam(':etat', $etat);

                    try {
                        $stmt->execute();
                    } catch (PDOException $e) {
                        $result['status'] = 'error';
                        $result['errors'][] = "Erreur lors de la mise à jour de la base de données: " . $e->getMessage();
                    }
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

// Traitement de l'upload lorsque le formulaire est soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_FILES['file']) && !empty($_FILES['file']['name'][0])) {    
        // Boucle à travers tous les fichiers téléchargés
        foreach ($_FILES['file']['name'] as $key => $fileName) {
            $fileTmpPath = $_FILES['file']['tmp_name'][$key];
            $fileSize = $_FILES['file']['size'][$key];
            $fileType = $_FILES['file']['type'][$key];
            $fileError = $_FILES['file']['error'][$key];
            
            // Vérifier s'il y a eu une erreur pendant l'upload
            if ($fileError === UPLOAD_ERR_OK) {
                // Validation du fichier (par exemple, vérifier la taille et le type du fichier)
                $allowedTypes = ['image/jpeg', 'image/png', 'application/pdf']; // Types de fichiers autorisés
                if (in_array($fileType, $allowedTypes) && $fileSize < 5000000) { // Taille limite du fichier (5MB)
                    $destination = 'uploads/' . basename($fileName);

                    // Déplacer le fichier vers le répertoire d'upload
                    if (move_uploaded_file($fileTmpPath, $destination)) {
                        echo "Le fichier $fileName a été téléchargé avec succès.<br>";
                    } else {
                        echo "Erreur lors de l'upload du fichier $fileName.<br>";
                    }
                } else {
                    echo "Le fichier $fileName est trop grand ou de type invalide.<br>";
                }
            } else {
                echo "Erreur lors de l'upload du fichier $fileName (Erreur code: $fileError).<br>";
            }
        }
    } else {
        echo "Aucun fichier sélectionné.";
    }
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
    <link rel="stylesheet" href="../assets/vendor/libs/dropzone/dropzone.css" />
	

    
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
                    <h5 class="card-header">Fichiers</h5>
                   
                    
                    <div class="card-body">
					

<form id="uploadForm" action="ajouter-files-trace.php" method="POST" enctype="multipart/form-data">
    <div class="dropzone needsclick" id="dropzone-multi">
        <!-- Zone de dépôt pour Dropzone -->
        <div class="dz-message needsclick">
            Déposez vos fichiers ici ou cliquez pour sélectionner
            <span class="note needsclick">Taille maximale de 5 Mo.</span>
        </div>
        <div class="fallback">
            <input name="file" type="file" />
        </div>
    </div>
    
    <div class="text-end">
        <!-- Le bouton Enregistrer qui déclenche l'envoi du formulaire -->
        <button type="button" id="saveButton" class="btn btn-primary me-2">Enregistrer</button>
        <a href="ajouter-trace.php" class="btn btn-outline-secondary">Annuler</a>
    </div>
</form>

<script>
    !function() {
        var previewTemplate = `
            <div class="dz-preview dz-file-preview">
                <div class="dz-details">
                    <div class="dz-thumbnail">
                        <img data-dz-thumbnail>
                        <span class="dz-nopreview">No preview</span>
                        <div class="dz-success-mark"></div>
                        <div class="dz-error-mark"></div>
                        <div class="dz-error-message"><span data-dz-errormessage></span></div>
                        <div class="progress">
                            <div class="progress-bar progress-bar-primary" role="progressbar" aria-valuemin="0" aria-valuemax="100" data-dz-uploadprogress></div>
                        </div>
                    </div>
                    <div class="dz-filename" data-dz-name></div>
                    <div class="dz-size" data-dz-size></div>
                </div>
            </div>`;

        // Initialisation de Dropzone
        var dropzoneMultiElement = document.querySelector("#dropzone-multi");
        if (dropzoneMultiElement && !dropzoneMultiElement.classList.contains("dz-clickable")) {
            var myDropzone = new Dropzone(dropzoneMultiElement, {
                previewTemplate: previewTemplate,
                parallelUploads: 100,
                maxFilesize: 5, // Taille max de fichier en Mo
                addRemoveLinks: true,
                url: "ajouter-files-trace.php", // L'URL où envoyer les fichiers
                autoProcessQueue: false, // Empêche Dropzone de soumettre automatiquement
                acceptedFiles: ".jpg,.jpeg,.png,.pdf", // Types de fichiers acceptés
                dictDefaultMessage: "Déposez vos fichiers ici ou cliquez pour sélectionner"
            });

            // Bouton Enregistrer
            document.getElementById("saveButton").addEventListener("click", function() {
                // Soumettre les fichiers via AJAX
                if (myDropzone.getAcceptedFiles().length > 0) {
                    myDropzone.processQueue(); // Traite les fichiers si des fichiers sont acceptés
                } else {
                    alert("Veuillez sélectionner des fichiers à télécharger.");
                }
            });

            // Événement pour gérer la soumission des fichiers
            myDropzone.on("sending", function() {
                // Désactive le bouton pendant l'upload
                document.getElementById("saveButton").disabled = true;
            });

            myDropzone.on("success", function(file, response) {
                // Si l'upload est réussi, réactiver le bouton
                document.getElementById("saveButton").disabled = false;
                alert("Le fichier a été téléchargé avec succès.");
                // Vous pouvez éventuellement rediriger ou réinitialiser la zone après le succès
            });

            myDropzone.on("error", function(file, errorMessage) {
                // Si l'upload échoue, réactiver le bouton et afficher un message d'erreur
                document.getElementById("saveButton").disabled = false;
                alert("Erreur lors de l'upload: " + errorMessage);
            });
        }
    }();
</script>




















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
	<script src="../assets/vendor/libs/dropzone/dropzone.js"></script>
    <script src="../assets/js/main.js"></script>

    <!-- Page JS -->
    <script src="../assets/js/pages-account-settings-account.js"></script>


    <!-- Place this tag in your head or just before your close body tag. -->
    <script async defer src="https://buttons.github.io/buttons.js"></script>
  </body>
</html>
