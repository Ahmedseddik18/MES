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
$selectFields = 'ref, longueur, largeur';
$fromTables = 'materiel';
$whereConditions = "id = '$id'";

// Préparer et exécuter la requête pour récupérer les données
$result = select($conn, $selectFields, $fromTables, $whereConditions, '');

// Vérifier si la requête a retourné un résultat
if ($result) {
    $row = $result->fetch(PDO::FETCH_ASSOC);
    $ref = $row['ref'];
    $longueur = $row['longueur'];
    $largeur = $row['largeur'];
    
} else {
    $alertClass = 'alert-danger';
    $message = 'Erreur lors de la récupération des données.';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupérer les valeurs du formulaire
    $ref1 = isset($_POST['ref']) ? trim($_POST['ref']) : '';
    $longueur1 = isset($_POST['longueur']) ? trim($_POST['longueur']) : '';
    $largeur1 = isset($_POST['largeur']) ? trim($_POST['largeur']) : '';


    // Vérifier que les valeurs ne sont pas nulles ou vides
    if (empty($ref1)) {
        $alertClass = 'alert-danger';
        $message = 'Le champ "Ref" doit être rempli.';
    } elseif (empty($longueur1)) {
        $alertClass = 'alert-danger';
        $message = 'Le champ "Longueur" doit être rempli.';
    } elseif (empty($largeur1)) {
        $alertClass = 'alert-danger';
        $message = 'Le champ "Largeur" doit être rempli.';
    }  else {
        // Préparer les données pour la mise à jour
        $table = 'materiel';
        $data = array(
            'ref' => $ref1,
            'longueur' => $longueur1,
            'largeur' => $largeur1
            
        );

        $conditions = "id = '$id'";

        // Mise à jour des données en utilisant la fonction existante
        $result = updateData($table, $data, $conditions, $conn);

        // Vérifier le résultat de la mise à jour
        if ($result['alertClass'] === 'alert-success') {
            header('Location: list-materiel.php');
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

    <title>Coupe Interne | Modifier Un Personnel</title>

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
              <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Ressources /</span> Modifier Matériel</h4>

              <div class="row">
                <div class="col-md-12">
                  
                  <div class="card mb-4">
                    <h5 class="card-header">Informations du Matériel</h5>
                    <!-- Account -->
                    
                   <div class="card-body">
  <form method="POST" enctype="multipart/form-data">
    <div class="row">
      <div class="mb-3 col-md-6">
        <label for="ref" class="form-label">Ref</label>
        <input
          class="form-control"
          type="text"
          id="ref"
          name="ref"
          value="<?php echo htmlspecialchars($ref); ?>"
        />
      </div>
      <div class="mb-3 col-md-6">
        <label for="longueur" class="form-label">Longueur</label>
        <input
          class="form-control"
          type="text"
          name="longueur"
          id="longueur"
          value="<?php echo htmlspecialchars($longueur); ?>"
        />
      </div>
      <div class="mb-3 col-md-6">
        <label for="largeur" class="form-label">Largeur</label>
        <input
          type="text"
          class="form-control"
          id="largeur"
          name="largeur"
          value="<?php echo htmlspecialchars($largeur); ?>"
        />
      </div>
      
			            

    <div class="mt-2 text-end">
      <button type="submit" name="submit" class="btn btn-primary me-2">Enregistrer</button>
      <button type="button" class="btn btn-outline-secondary" onclick="window.location.href='list-effectif.php';">Annuler</button>

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
