<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../../index.php");
    exit();
}

include("../php/db.php");
include("../php/fonction.php");

$commande = $_GET['commande'];
$article = strtoupper($_GET['article']);

$sub = $_GET['sub']; // Sub

if (isset($_POST['Suivant'])) {
    // Collecter les données du formulaire
    $data = [
        'TSR' => $_POST['TSR'] ?? '',
        'TSM' => $_POST['TSM'] ?? '',
        'TSC' => $_POST['TSC'] ?? '',
        'TSE' => $_POST['TSE'] ?? '',
    ];

    // Définir les conditions pour la mise à jour
    $conditions = "`commande` = '$commande' AND `article` = '$article' AND `sub` = '$sub'";

    // Appeler la fonction pour mettre à jour les données
    $result = updateData('commande', $data, $conditions, $conn);

    // Préparer le message d'alerte
    $alertClass = $result['alertClass'];
    $message = $result['message'];

    // Vérifier si la mise à jour a réussi
    if ($alertClass === 'alert-success') {
        // Rediriger vers une autre page en cas de succès
        header('Location: ajouter-temps-standard1.php');
        exit;
    }

    // Afficher le message d'alerte
    echo '<div class="alert ' . $alertClass . ' fixed-top-right" role="alert" style="display: none;">';
    echo $message;
    echo '</div>';

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

    <form  method="post">
        <!-- Conteneur pour les parties -->
        
                <h5 class="fw-bold">
                    <span class="text-muted fw-light">Sub <?php echo $sub;?></span>
                </h5>
                <div class="row">
                    <div class="col-md">
                        <label for="TSR${sub}" class="form-label">TS Relaxation</label>
                        <input 
                            class="form-control"
                            type="text"
                            name="TSR"
                            id="TSR}"
                        />
                    </div>
                    <div class="col-md">
                        <label for="TSM${sub}" class="form-label">TS Matelassage</label>
                        <input 
                            class="form-control"
                            type="text"
                            name="TSM"
                            id="TSM"
                        />
                    </div>
                    <div class="col-md">
                        <label for="TSC${sub}" class="form-label">TS Coupe</label>
                        <input 
                            class="form-control"
                            type="text"
                            name="TSC"
                            id="TSC"
                        />
                    </div>
					<div class="col-md">
                        <label for="TSE${sub}" class="form-label">TS Étiquetage</label>
                        <input 
                            class="form-control"
                            type="text"
                            name="TSE"
                            id="TSE"
                        />
                    </div>
                </div>
         
        
        <!-- Boutons de navigation -->
    <div class="text-end mt-4">
    <input type="submit" name="Suivant" class="btn btn-primary" value="Ajouter">
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






  </body>
</html>
