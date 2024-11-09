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
$selectFields = 'nom, prenom, matricule, fonction, nature';
$fromTables = 'effectif';
$whereConditions = "id = '$id'";

// Préparer et exécuter la requête pour récupérer les données
$result = select($conn, $selectFields, $fromTables, $whereConditions, '');

// Vérifier si la requête a retourné un résultat
if ($result) {
    $row = $result->fetch(PDO::FETCH_ASSOC);
    $nom = $row['nom'];
    $prenom = $row['prenom'];
    $matricule = $row['matricule'];
    $fonction = $row['fonction'];
    $nature = $row['nature'];
} else {
    $alertClass = 'alert-danger';
    $message = 'Erreur lors de la récupération des données.';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupérer les valeurs du formulaire
    $nom1 = isset($_POST['nom']) ? trim($_POST['nom']) : '';
    $prenom1 = isset($_POST['prenom']) ? trim($_POST['prenom']) : '';
    $matricule1 = isset($_POST['matricule']) ? trim($_POST['matricule']) : '';
    $fonction1 = isset($_POST['fonction']) ? trim($_POST['fonction']) : '';
    $nature1 = isset($_POST['nature']) ? trim($_POST['nature']) : '';

    // Vérifier que les valeurs ne sont pas nulles ou vides
    if (empty($nom1)) {
        $alertClass = 'alert-danger';
        $message = 'Le champ "Nom" doit être rempli.';
    } elseif (empty($prenom1)) {
        $alertClass = 'alert-danger';
        $message = 'Le champ "Prénom" doit être rempli.';
    } elseif (empty($matricule1)) {
        $alertClass = 'alert-danger';
        $message = 'Le champ "Matricule" doit être rempli.';
    } elseif (empty($fonction1)) {
        $alertClass = 'alert-danger';
        $message = 'Le champ "Fonction" doit être rempli.';
    } elseif (empty($nature1)) {
        $alertClass = 'alert-danger';
        $message = 'Le champ "Nature" doit être rempli.';
    } else {
        // Préparer les données pour la mise à jour
        $table = 'effectif';
        $data = array(
            'nom' => $nom1,
            'prenom' => $prenom1,
            'matricule' => $matricule1,
            'fonction' => $fonction1,
            'nature' => $nature1
        );

        $conditions = "id = '$id'";

        // Mise à jour des données en utilisant la fonction existante
        $result = updateData($table, $data, $conditions, $conn);

        // Vérifier le résultat de la mise à jour
        if ($result['alertClass'] === 'alert-success') {
            header('Location: list-effectif.php');
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
              <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Ressources /</span> Modifier Effectif</h4>

              <div class="row">
                <div class="col-md-12">
                  
                  <div class="card mb-4">
                    <h5 class="card-header">Informations du Personnel</h5>
                    <!-- Account -->
                    
                   <div class="card-body">
  <form method="POST" enctype="multipart/form-data">
    <div class="row">
      <div class="mb-3 col-md-6">
        <label for="nom" class="form-label">Nom</label>
        <input
          class="form-control"
          type="text"
          id="nom"
          name="nom"
          value="<?php echo htmlspecialchars($nom); ?>"
        />
      </div>
      <div class="mb-3 col-md-6">
        <label for="prenom" class="form-label">Prénom</label>
        <input
          class="form-control"
          type="text"
          name="prenom"
          id="prenom"
          value="<?php echo htmlspecialchars($prenom); ?>"
        />
      </div>
      <div class="mb-3 col-md-6">
        <label for="matricule" class="form-label">Matricule</label>
        <input
          type="text"
          class="form-control"
          id="matricule"
          name="matricule"
          value="<?php echo htmlspecialchars($matricule); ?>"
        />
      </div>
      <div class="mb-3 col-md-6">
                <label for="fonction" class="form-label">Fonction</label>
                <select id="fonction" name="fonction" class="select2 form-select">
                    <option value=""></option>
                    <option value="agent-bureautique" <?php echo htmlspecialchars($fonction) == 'agent-bureautique' ? 'selected' : ''; ?>>Agent Bureautique</option>
                    <option value="matelasseur" <?php echo htmlspecialchars($fonction) == 'matelasseur' ? 'selected' : ''; ?>>Matelasseur</option>
                    <option value="coupeur" <?php echo htmlspecialchars($fonction) == 'coupeur' ? 'selected' : ''; ?>>Coupeur</option>
                    <option value="etiquetage" <?php echo htmlspecialchars($fonction) == 'etiquetage' ? 'selected' : ''; ?>>Étiquetage</option>
                </select>
            </div>
			            <div class="mb-3 col-md-6">
                <label for="nature" class="form-label">Nature</label>
                <select id="nature" name="nature" class="select2 form-select">
                    <option value=""></option>
                    <option value="direct" <?php echo htmlspecialchars($nature) == 'direct' ? 'selected' : ''; ?>>Direct</option>
                    <option value="indirect" <?php echo htmlspecialchars($nature) == 'indirect' ? 'selected' : ''; ?>>Indirect</option>
                </select>
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
