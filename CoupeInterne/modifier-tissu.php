<?php
session_start();

if (!isset($_SESSION['user_id'])) {
     header("Location: ../../index.php");
    exit();
}

include("../php/db.php");
include("../php/fonction.php");

// Récupérer l'ID à partir de la requête GET
$id = isset($_GET['id']) ? trim($_GET['id']) : '';

// Initialiser les variables pour éviter les erreurs
$alertClass = '';
$message = '';

// Sélectionner les champs nécessaires
$selectFields = '*';
$fromTables = 'tissu';
$whereConditions = "id = '$id'";

// Préparer et exécuter la requête pour récupérer les données
$result = select($conn, $selectFields, $fromTables, $whereConditions, '');

// Vérifier si la requête a retourné un résultat
if ($result) {
    $row = $result->fetch(PDO::FETCH_ASSOC);
    $code = $row['code'];
    $fournisseur = $row['fournisseur'];
    $typologie = $row['typologie'];
    $composition = $row['composition'];
    $base = $row['base'];
	$largeur = $row['largeur'];
    $poids = $row['poids'];
    
} else {
    $alertClass = 'alert-danger';
    $message = 'Erreur lors de la récupération des données.';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupérer les valeurs du formulaire
    $code1 = isset($_POST['code']) ? trim($_POST['code']) : '';
    $fournisseur1 = isset($_POST['fournisseur']) ? trim($_POST['fournisseur']) : '';
    $typologie1 = isset($_POST['typologie']) ? trim($_POST['typologie']) : '';
    $composition1 = isset($_POST['composition']) ? trim($_POST['composition']) : '';
    $base1 = isset($_POST['base']) ? trim($_POST['base']) : '';
    $largeur1 = isset($_POST['largeur']) ? trim($_POST['largeur']) : '';
    $poids1 = isset($_POST['poids']) ? trim($_POST['poids']) : '';
    // Vérifier que les valeurs ne sont pas nulles ou vides
    if (empty($code1)) {
        $alertClass = 'alert-danger';
        $message = 'Le champ "Code" doit être rempli.';
    } elseif (empty($fournisseur1)) {
        $alertClass = 'alert-danger';
        $message = 'Le champ "Fournisseur" doit être rempli.';
    } elseif (empty($typologie1)) {
        $alertClass = 'alert-danger';
        $message = 'Le champ "Typologie" doit être rempli.';
    } elseif (empty($composition1)) {
        $alertClass = 'alert-danger';
        $message = 'Le champ "Composition" doit être rempli.';
    } elseif (empty($base1)) {
        $alertClass = 'alert-danger';
        $message = 'Le champ "Base" doit être rempli.';
    } elseif (empty($largeur1)) {
        $alertClass = 'alert-danger';
        $message = 'Le champ "Largeur" doit être rempli.';
    }elseif (empty($poids1)) {
        $alertClass = 'alert-danger';
        $message = 'Le champ "Poids" doit être rempli.';
    }	else {
        // Préparer les données pour la mise à jour
        $table = 'tissu';
        $data = array(
            'code' => $code1,
            'fournisseur' => $fournisseur1,
            'typologie' => $typologie1,
            'composition' => $composition1,
			'base' => $base1,
            'largeur' => $largeur1,
            'poids' => $poids1
            
        );

        $conditions = "id = '$id'";

        // Mise à jour des données en utilisant la fonction existante
        $result = updateData($table, $data, $conditions, $conn);

        // Vérifier le résultat de la mise à jour
        if ($result['alertClass'] === 'alert-success') {
            header('Location: list-tissu.php');
            exit;
        }

        // Assigner les valeurs de l'alerte
        $alertClass = $result['alertClass'];
        $message = $result['message'];
    }

    // Afficher l'alerte avec un délai
    echo '<div class="alert ' . htmlspecialchars($alertClass) . ' fixed-top-right" role="alert" style="display: none;">';
    echo htmlspecialchars($message);
    echo '</div>';

    echo '<script>';
    echo 'setTimeout(function() { document.querySelector(".alert.fixed-top-right").style.display = "block"; }, 200);';
    echo 'setTimeout(function() { document.querySelector(".alert.fixed-top-right").style.display = "none"; }, 2000);';
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

    <title>Coupe Interne | Modifier Un Tissu</title>

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
          <?php include 'navbar.php'; ?>

          <!-- Content wrapper -->
          <div class="content-wrapper">
            <!-- Content -->

            <div class="flex-grow-1 container-p-y container-fluid">
              <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Ressources /</span> Modifier Tissu</h4>

              <div class="row">
                <div class="col-md-12">
                  
                  <div class="card mb-4">
                    <h5 class="card-header">Informations du Tissu</h5>
                    <!-- Account -->
<div class="card-body">
    <form method="POST" enctype="multipart/form-data">
        <div class="row">
            <div class="mb-3 col-md-6">
                <label for="code" class="form-label">Code Tissu</label>
                <input class="form-control" type="text" id="code" name="code" value="<?php echo htmlspecialchars($code); ?>"/>
            </div>
            <div class="mb-3 col-md-6">
                <label for="fournisseur" class="form-label">Fournisseur</label>
                <input class="form-control" type="text" name="fournisseur" id="fournisseur" value="<?php echo htmlspecialchars($fournisseur); ?>"/>
            </div>

            <div class="mb-3 col-md-6">
                <label for="typologie" class="form-label">Typologie</label>
                <input type="text" class="form-control" id="typologie" name="typologie" value="<?php echo htmlspecialchars($typologie); ?>"/>
            </div>

            <div class="mb-3 col-md-6">
                <label for="composition" class="form-label">Composition</label>
                <input type="text" class="form-control" id="composition" name="composition" value="<?php echo htmlspecialchars($composition); ?>"/>
            </div>

            <div class="mb-3 col-md-6">
                <label for="base" class="form-label">Base</label>
                <input type="text" class="form-control" id="base" name="base" value="<?php echo htmlspecialchars($base); ?>" />
            </div>
			<div class="mb-3 col-md-6">
                <label for="largeur" class="form-label">Largeur</label>
                <input type="text" class="form-control" id="largeur" name="largeur" value="<?php echo htmlspecialchars($largeur); ?>" />
            </div>
			<div class="mb-3 col-md-6">
                <label for="poids" class="form-label">Poids</label>
                <input type="text" class="form-control" id="poids" name="poids" value="<?php echo htmlspecialchars($poids); ?>" />
            </div>
        </div>
        <div class="mt-2 text-end">
            <button type="submit" name="submit" class="btn btn-primary me-2">Enregistrer</button>
            <button type="button" class="btn btn-outline-secondary" onclick="window.location.href='list-tissu.php';">Annuler</button>
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
  </body>
</html>
