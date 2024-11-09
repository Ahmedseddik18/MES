<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location:../../index.php");
    exit();
}

$nbPartie = htmlspecialchars($_GET['nbPartie']);
$subValues = htmlspecialchars($_GET['subValues']);
$commande = htmlspecialchars($_GET['commande']);
$article = strtoupper(htmlspecialchars($_GET['article']));
$subParts = explode(',', $subValues);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    include("../php/db.php");

    try {
        // Préparation de la requête d'insertion
        $stmt = $conn->prepare("INSERT INTO commande (partie, sub, longueurMatelas, nombrePlies, quantiteMatelas, commande, article) VALUES (?, ?, ?, ?, ?, ?, ?)");
        
        $success = true;
        $errorMessage = '';

        // Boucle à travers chaque partie et sous-partie pour l'insertion
// Boucle à travers chaque partie et sous-partie pour l'insertion
for ($partieId = 1; $partieId <= $nbPartie; $partieId++) {
    foreach ($subParts as $subPart) {
        $longueurMatelasArray = $_POST["longueurMatelas{$partieId}{$subPart}"] ?? [];
        $nombreDePliesArray = $_POST["nombreDePlies{$partieId}{$subPart}"] ?? [];
        $quantiteParMatelasArray = $_POST["quantiteParMatelas{$partieId}{$subPart}"] ?? [];

        // Validation pour les tableaux reçus
        if (is_array($longueurMatelasArray) && is_array($nombreDePliesArray) && is_array($quantiteParMatelasArray)) {
            $rowsCount = count($longueurMatelasArray);

            // Insérer chaque ligne de données
            for ($i = 0; $i < $rowsCount; $i++) {
                // Validation et conversion des valeurs
                $longueurMatelas = isset($longueurMatelasArray[$i]) ? number_format(floatval($longueurMatelasArray[$i]), 2, '.', '') : null;
                $nombreDePlies = isset($nombreDePliesArray[$i]) ? intval($nombreDePliesArray[$i]) : null;
                $quantiteParMatelas = isset($quantiteParMatelasArray[$i]) ? intval($quantiteParMatelasArray[$i]) : null;

                // Vérifiez que les valeurs requises ne sont pas nulles ou incorrectes
                if ($longueurMatelas === null || $longueurMatelas === '0.00') {
                   
                    
                    continue 2; // Sortir des deux boucles en cas d'erreur
                }

                if (!$stmt->execute([$partieId, $subPart, $longueurMatelas, $nombreDePlies, $quantiteParMatelas, $commande, $article])) {
                    $success = false;
                    $errorMessage = 'Erreur lors de l\'enregistrement des données.';
                    break 2; // Sortir des deux boucles en cas d'erreur
                }
            }
        }
    }
}


        // Affichage du message de succès ou d'erreur
        $alertClass = $success ? 'alert-success' : 'alert-danger';
        $message = $success ? 'Les données ont été enregistrées avec succès !' : $errorMessage;
        if ($success) {
            header('Location: ajouter-informations-generales.php');
            exit;
        }

        // Affichage des alertes
        echo "<div class='alert $alertClass fixed-top-right' role='alert' style='display: none;'>$message</div>";
        echo "<script>
                setTimeout(() => { document.querySelector('.alert.fixed-top-right').style.display = 'block'; }, 200);
                setTimeout(() => { document.querySelector('.alert.fixed-top-right').style.display = 'none'; }, 20000);
              </script>";

    } catch (PDOException $e) {
        $alertClass = 'alert-danger';
        $message = 'Erreur lors de l\'enregistrement des données : ' . $e->getMessage();
        echo "<div class='alert $alertClass fixed-top-right' role='alert' style='display: none;'>$message</div>";
        echo "<script>
                setTimeout(() => { document.querySelector('.alert.fixed-top-right').style.display = 'block'; }, 200);
                setTimeout(() => { document.querySelector('.alert.fixed-top-right').style.display = 'none'; }, 20000);
              </script>";
    }
}
?>













<!DOCTYPE html>


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
                    <h5 class="card-header">Détails Matelas</h5>
                   
                    
                    <div class="card-body">
<form id="wizardForm" method = "post">
            <!-- Conteneur pour les parties -->
            <div id="wizardContainer"></div>
            
            <!-- Boutons de navigation -->
            <div class="d-flex justify-content-between mt-4">
                <button type="button" id="prevBtn" class="btn btn-outline-secondary" disabled>Précédent</button>
                <button type="button" id="nextBtn" class="btn btn-primary">Suivant</button>
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

    <!-- Main JS -->
    <script src="../assets/js/main.js"></script>

    <!-- Page JS -->
    <script src="../assets/js/pages-account-settings-account.js"></script>

    <!-- Place this tag in your head or just before your close body tag. -->
    <script async defer src="https://buttons.github.io/buttons.js"></script>
	<!-- Ajouter nouvelle ligne -->
<script>
// Fonction pour obtenir les paramètres de l'URL
function getQueryParam(param) {
    const urlParams = new URLSearchParams(window.location.search);
    return urlParams.get(param);
}

// Extraire nbPartie et subValues depuis l'URL
const nbPartie = parseInt(getQueryParam('nbPartie'), 10);
const subValues = getQueryParam('subValues');

// Convertir subValues en tableau
const subParts = subValues ? subValues.split(',') : [];

// Fonction pour créer une section pour chaque partie et sous-partie
function createSection(partieId, subPartId) {
    return `
        <div class="wizard-step" id="partie${partieId}">
            <h5 class="fw-bold">
                <span class="text-muted fw-light">Partie ${partieId} /</span> Sub ${subPartId}
            </h5>
            <div class="row">
                <div class="col-md">
                    <label for="longueurMatelas${partieId}${subPartId}" class="form-label">Longueur Matelas</label>
                    <input 
                        class="form-control"
                        type="text"
                        name="longueurMatelas${partieId}${subPartId}[]"
                        id="longueurMatelas${partieId}${subPartId}"
                    />
                </div>
                <div class="col-md">
                    <label for="nombreDePlies${partieId}${subPartId}" class="form-label">Nombre De Plies</label>
                    <input 
                        class="form-control"
                        type="text"
                        name="nombreDePlies${partieId}${subPartId}[]"
                        id="nombreDePlies${partieId}${subPartId}"
                    />
                </div>
                <div class="col-md">
                    <label for="quantiteParMatelas${partieId}${subPartId}" class="form-label">Quantité Par Matelas</label>
                    <input 
                        class="form-control"
                        type="text"
                        name="quantiteParMatelas${partieId}${subPartId}[]"
                        id="quantiteParMatelas${partieId}${subPartId}"
                    />
                </div>
            </div>
            <div id="additionalRows${partieId}${subPartId}"></div>
            <div class="mt-3 text-end">
                <button type="button" class="btn btn-secondary" id="addRowButton${partieId}${subPartId}">Ajouter une ligne</button>
            </div>
        </div>
    `;
}

// Fonction pour ajouter une nouvelle ligne d'entrée
function addRow(partieId, subPartId) {
    const newRow = `
        <div class="row mt-3">
            <div class="col-md">
                <label for="longueurMatelas${partieId}${subPartId}" class="form-label">Longueur Matelas</label>
                <input 
                    class="form-control"
                    type="text"
                    name="longueurMatelas${partieId}${subPartId}[]"
                    id="longueurMatelas${partieId}${subPartId}"
                />
            </div>
            <div class="col-md">
                <label for="nombreDePlies${partieId}${subPartId}" class="form-label">Nombre De Plies</label>
                <input 
                    class="form-control"
                    type="text"
                    name="nombreDePlies${partieId}${subPartId}[]"
                    id="nombreDePlies${partieId}${subPartId}"
                />
            </div>
            <div class="col-md">
                <label for="quantiteParMatelas${partieId}${subPartId}" class="form-label">Quantité Par Matelas</label>
                <input 
                    class="form-control"
                    type="text"
                    name="quantiteParMatelas${partieId}${subPartId}[]"
                    id="quantiteParMatelas${partieId}${subPartId}"
                />
            </div>
        </div>
    `;
    document.getElementById(`additionalRows${partieId}${subPartId}`).insertAdjacentHTML('beforeend', newRow);
}


    function initializeWizard() {
        const container = document.getElementById('wizardContainer');
        if (nbPartie) {
            // Génère des sections pour chaque partie et chaque sous-partie
            for (let partieId = 1; partieId <= nbPartie; partieId++) {
                subParts.forEach(subPart => {
                    container.insertAdjacentHTML('beforeend', createSection(partieId, subPart));
                });
            }

            // Afficher la première étape
            showStep(1);

            // Attache les événements pour chaque bouton "Ajouter une ligne"
            subParts.forEach(subPart => {
                for (let partieId = 1; partieId <= nbPartie; partieId++) {
                    document.getElementById(`addRowButton${partieId}${subPart}`).addEventListener('click', function() {
                        addRow(partieId, subPart);
                    });
                }
            });

            // Attache les événements pour les boutons de navigation
            document.getElementById('nextBtn').addEventListener('click', function() {
                nextStep();
            });

            document.getElementById('prevBtn').addEventListener('click', function() {
                prevStep();
            });
        } else {
            console.error('Le paramètre nbPartie est manquant.');
        }
    }

    function showStep(step) {
        const steps = document.querySelectorAll('.wizard-step');
        steps.forEach((stepElement, index) => {
            stepElement.style.display = index === step - 1 ? 'block' : 'none';
        });

        // Activer/désactiver les boutons de navigation
        document.getElementById('prevBtn').disabled = step === 1;
        document.getElementById('nextBtn').textContent = step === steps.length ? 'Terminer' : 'Suivant';
    }

    function nextStep() {
        const steps = document.querySelectorAll('.wizard-step');
        let currentStep = Array.from(steps).findIndex(stepElement => stepElement.style.display === 'block');
        if (currentStep < steps.length - 1) {
            showStep(currentStep + 2);
        } else {
            // Soumettre le formulaire à la fin du wizard
            document.getElementById('wizardForm').submit();
        }
    }

    function prevStep() {
        const steps = document.querySelectorAll('.wizard-step');
        let currentStep = Array.from(steps).findIndex(stepElement => stepElement.style.display === 'block');
        if (currentStep > 0) {
            showStep(currentStep);
        }
    }




    // Initialise le wizard lorsque le document est prêt
    document.addEventListener('DOMContentLoaded', initializeWizard);
</script>






  </body>
</html>
