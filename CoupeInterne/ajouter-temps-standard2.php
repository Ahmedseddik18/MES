<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../../index.php");
    exit();
}
include("../php/db.php");
include("../php/fonction.php");

$commande = $_GET['commande'];
$article = $_GET['article'];

// Définir les champs de sélection, les tables, et les conditions
$selectFields = 'DISTINCT sub';
$fromTables = "commande";
$whereConditions = "commande = '$commande' AND article = '$article'"; // Note: No extra space after '$commande'
$orderBy = ''; // Keep this as it is if no ordering is required

// Insertion des données en utilisant la fonction existante
$result = select($conn, $selectFields, $fromTables, $whereConditions, $orderBy);


// Récupération des valeurs sous forme de tableau
$subs = $result->fetchAll(PDO::FETCH_COLUMN);

// Encodage des valeurs en JSON pour utilisation en JavaScript
$subsJson = json_encode($subs);

// Décoder le JSON pour obtenir un tableau PHP
$subParts = json_decode($subsJson, true);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Préparer la requête de mise à jour
    $stmt = $conn->prepare("
        UPDATE commande
        SET TSR = ?, TSM = ?, TSC = ?, TSE = ?
        WHERE commande = ? AND article = ? AND sub = ?
    ");

    $success = true;
    $errorMessage = '';

    // Boucle sur les sous-parties
    foreach ($subParts as $subPart) {
        // Récupération des valeurs des champs
        $TSRArray = $_POST["TSR{$subPart}"] ?? [];
        $TSMArray = $_POST["TSM{$subPart}"] ?? [];
        $TSCArray = $_POST["TSC{$subPart}"] ?? [];
        $TSEArray = $_POST["TSE{$subPart}"] ?? [];

        // Vérifier que les tableaux ne sont pas vides et ont la même taille
        if (is_array($TSRArray) && is_array($TSMArray) && is_array($TSCArray) && is_array($TSEArray)) {
            $rowsCount = count($TSRArray);

            for ($i = 0; $i < $rowsCount; $i++) {
                $TSR = $TSRArray[$i] ?? '';
                $TSM = $TSMArray[$i] ?? '';
                $TSC = $TSCArray[$i] ?? '';
                $TSE = $TSEArray[$i] ?? '';

                // Exécution de la requête de mise à jour
                if (!$stmt->execute([$TSR, $TSM, $TSC, $TSE, $commande, $article, $subPart])) {
                    $success = false;
                    $errorMessage = 'Erreur lors de l\'enregistrement des données.';
                    break 2; // Sortir des deux boucles en cas d'erreur
                }
            }
        }
    }

    // Déterminer le message et la classe d'alerte en fonction du succès
    if ($success) {
        $message = 'Les données ont été enregistrées avec succès.';
        $alertClass = 'alert-success';
		header("Location: index.php");
        exit();
    } else {
        $message = $errorMessage;
        $alertClass = 'alert-danger';
    }

    // Afficher l'alerte
    echo '<div class="alert ' . $alertClass . ' fixed-top-right" role="alert" style="display: none;">';
    echo $message;
    echo '</div>';

    // Afficher l'alerte avec un délai de 2 secondes (2000 millisecondes)
    echo '<script>';
    echo 'setTimeout(function() { document.querySelector(".alert.fixed-top-right").style.display = "block"; }, 200);';
    echo 'setTimeout(function() { document.querySelector(".alert.fixed-top-right").style.display = "none"; }, 20000);'; // Masquer après 20 secondes
    echo '</script>';
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
                    <h5 class="card-header">Temps Standard</h5>
                   
                    
                    <div class="card-body">
<?php if (!empty($result)) : ?>
    <form id="wizardForm" method="post">
        <!-- Conteneur pour les parties -->
        <div id="wizardContainer"></div>
        
        <!-- Boutons de navigation -->
        <div class="d-flex justify-content-between mt-4">
            <button type="button" id="prevBtn" class="btn btn-outline-secondary" disabled>Précédent</button>
            <button type="button" id="nextBtn" class="btn btn-primary">Suivant</button>
        </div>
    </form>
<?php else : ?>


    <div class="misc-wrapper text-center">
               <h2 class="mb-2 mx-2">Aucune donnée trouvée</h2>
<p class="mb-4 mx-2">Malheureusement, aucune donnée n'a été trouvée. Nous avons informé les personnes concernées. Merci de revenir ultérieurement.</p>
<a href="index.php" class="btn btn-primary">Retour</a>

                <div class="mt-3">
                    <img
                        src="../assets/img/illustrations/404.png"
                        alt="page-misc-error-light"
                        width="500"
                        class="img-fluid"
                        
                    />
                </div>
            </div>
			<?php 
require '../PHPMailer/src/PHPMailer.php';
require '../PHPMailer/src/Exception.php';
$subject = "Demande des informations pour la commande : '$commande'";
            
            // Préparer le message
            $message = "Bonjour,<br><br>
Merci de vous connecter au MES pour saisir les détails des matelas relatifs à la commande <strong>'$commande'</strong> pour l'article <strong>'$article'</strong>.<br><br>";

$message .= "
 Merci de votre collaboration.<br><br>
MES.";
// Définir les variables de test
$testRecipients = ['ahmed.zoghlami@benetton.com']; // Remplacez par une adresse email de test valide
$cc = ['zoghlami.ahmedseddik@gmail.com'];
// Appeler la fonction pour tester l'envoi
sendEmail($testRecipients, $subject, $message , $cc);
?>
<?php endif; ?>


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
    // Récupération des valeurs des sous-parties depuis PHP
    const subParts = <?php echo $subsJson; ?>;

    function createSection(sub) {
        return `
            <div class="wizard-step" id="sub${sub}">
                <h5 class="fw-bold">
                    <span class="text-muted fw-light">Sub ${sub}</span>
                </h5>
                <div class="row">
                    <div class="col-md">
                        <label for="TSR${sub}" class="form-label">TS Relaxation</label>
                        <input 
                            class="form-control"
                            type="text"
                            name="TSR${sub}[]"
                            id="TSR${sub}"
                        />
                    </div>
                    <div class="col-md">
                        <label for="TSM${sub}" class="form-label">TS Matelassage</label>
                        <input 
                            class="form-control"
                            type="text"
                            name="TSM${sub}[]"
                            id="TSM${sub}"
                        />
                    </div>
                    <div class="col-md">
                        <label for="TSC${sub}" class="form-label">TS Coupe</label>
                        <input 
                            class="form-control"
                            type="text"
                            name="TSC${sub}[]"
                            id="TSC${sub}"
                        />
                    </div>
					<div class="col-md">
                        <label for="TSE${sub}" class="form-label">TS Étiquetage</label>
                        <input 
                            class="form-control"
                            type="text"
                            name="TSE${sub}[]"
                            id="TSE${sub}"
                        />
                    </div>
                </div>
                
            </div>
        `;
    }

    function initializeWizard() {
        const container = document.getElementById('wizardContainer');
        
        // Génère des sections pour chaque sous-partie
        if (subParts && subParts.length > 0) {
            subParts.forEach(subPart => {
                container.insertAdjacentHTML('beforeend', createSection(subPart));
            });

            // Afficher la première étape
            showStep(1);

            // Attache les événements pour les boutons de navigation
            document.getElementById('nextBtn').addEventListener('click', function() {
                nextStep();
            });

            document.getElementById('prevBtn').addEventListener('click', function() {
                prevStep();
            });
        } else {
            console.error('Le paramètre subParts est manquant ou vide.');
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
