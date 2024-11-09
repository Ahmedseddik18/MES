<?php
session_start();

if (!isset($_SESSION['user_id'])) {
     header("Location: ../../index.php");
    exit();
}
include("../php/db.php");
include("../php/fonction.php");
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
// Définir la commande et l'article (assurez-vous de les obtenir de manière sécurisée)
$id = $_GET['id'];
$etat = "Termine";
$alertClass = ''; // Valeur par défaut pour éviter l'erreur "Undefined variable"
$message = '';    // Valeur par défaut pour éviter l'erreur "Undefined variable"
$focusId = '';
// Sélectionner les champs nécessaires
$selectFields = '*';
$fromTables = 'trace';
$whereConditions = "id='$id'";
$orderBy = ''; // Assurez-vous de définir $orderBy ici si nécessaire

// Préparer et exécuter la requête pour récupérer les données
$result = select($conn, $selectFields, $fromTables, $whereConditions, $orderBy);


// Vérifier si la requête a retourné un résultat
if ($result) {
    $row = $result->fetch(PDO::FETCH_ASSOC);
    
    $phase = $row['phase'];
    $article = $row['article'];
    $commande = $row['commande'];
    $atelier = $row['atelier'];
    $dateTrace = $row['date'];
    
	
} else {
    // Gestion d'erreur si la requête échoue
    $alertClass = 'alert-danger';
    $message = 'Erreur lors de la récupération des données.';
}

if (isset($_POST['Suivant'])) {
    // Récupération des données du formulaire avec vérifications
    $formData = [
        'metrage' => isset($_POST['metrage']) ? $_POST['metrage'] : '',
        'fin' => isset($_POST['fin']) ? $_POST['fin'] : '',
        'laize' => isset($_POST['laize']) ? $_POST['laize'] : ''
        
    ];

    // Calcul de la semaine de dateChargement
    $formData['semaine'] = $formData['fin'] ? date('W', strtotime($formData['fin'])) : '';

    // Vérification des variables pour éviter les valeurs nulles ou vides
    $fields = [
        'metrage' => $formData['metrage'],
        'fin' => $formData['fin'],
        'semaine' => $formData['semaine']
    ];



    if (empty($message)) {
        // Définir les conditions pour vérifier si l'article et la commande existent déjà
        $table = 'trace'; // Remplacez par le nom de votre table
                $Conditions = "id = :id "; // Utiliser des paramètres pour la sécurité
$params = [
    'id' => $id
];

        // Utiliser la fonction checkData pour vérifier l'existence
        $existingCount = checkData($table, $Conditions,$params, $conn);

        if ($existingCount > 0) {
            // Si la combinaison existe déjà, définir les conditions pour la mise à jour
$updateFields = [
    'metrage' => $formData['metrage'],
    'laize' => $formData['laize'],
    'fin' => $formData['fin'],
    'etat' => $etat,                
    'semaine' => $formData['semaine']
];

// Préparer les clauses de mise à jour
$setClauses = [];
$params = []; // Pour stocker les paramètres à lier
foreach ($updateFields as $key => $value) {
    $setClauses[] = "$key = :$key"; // Utiliser des placeholders
    $params[":$key"] = $value; // Lier les paramètres
}

$setClause = implode(', ', $setClauses);

// Exécuter la requête de mise à jour
$updateQuery = "UPDATE $table SET $setClause WHERE id = :id";
$params[":id"] = $id; // Lier l'identifiant

try {
    $stmt = $conn->prepare($updateQuery);
    $stmt->execute($params); // Exécuter avec les paramètres liés

    if ($stmt->rowCount() > 0) { // Vérifier si la mise à jour a réussi
        // Assigner les valeurs des alertes
        $alertClass = 'alert-success';
        $message = "Les informations ont été mises à jour avec succès.";
        
        header("Location: liste-trace.php");
        exit(); // Toujours appeler exit() après une redirection pour stopper l'exécution du script
    } else {
        // Gestion des erreurs de mise à jour
        $alertClass = 'alert-danger';
        $message = "Erreur lors de la mise à jour des informations.";
    }
} catch (PDOException $e) {
    // Gestion des erreurs de base de données
    $alertClass = 'alert-danger';
    $message = "Erreur lors de la mise à jour des informations : " . $e->getMessage();
}

        } else {
            // Si la combinaison n'existe pas, définir un message d'erreur
            $alertClass = 'alert-danger';
            $message = "Cette commande n'existe pas dans la base de données.";
        }
    }
}





// Afficher l'alerte
echo '<div class="alert ' . htmlspecialchars($alertClass) . ' fixed-top-right" role="alert" style="display: none;">';
echo htmlspecialchars($message);
echo '</div>';

// Afficher l'alerte avec un délai de 2 secondes (2000 millisecondes)
echo '<script>';
echo 'setTimeout(function() { document.querySelector(".alert.fixed-top-right").style.display = "block"; }, 200);';
echo 'setTimeout(function() { document.querySelector(".alert.fixed-top-right").style.display = "none"; }, 2000);'; // Masquer après 20 secondes
echo '</script>';
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
              <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Traçés /</span> Modifier</h4>

              <div class="row">
                <div class="col-md-12">
                  
                  <div class="card mb-4">
                    <h5 class="card-header">Informations Générales</h5>
                   
                    
                    <div class="card-body">
                      <form method="POST" enctype="multipart/form-data">
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
                value="<?php echo $commande; ?>"
				disabled
            />
        </div>

        <div class="mb-3 col-md-6">
            <label for="article" class="form-label">Article</label>
            <input 
                class="form-control"
                type="text"
                name="article"
                id="article"
                maxlength="9"
                title="Veuillez entrer une commande au format XXXXXXXXX"
                style="text-transform: uppercase;"
                value="<?php echo $article; ?>"
				disabled
            />
        </div>

        <div class="mb-3 col-md-6">
            <label for="phase" class="form-label">Phase</label>
            <select id="phase" name="phase" class="select2 form-select" disabled>
                <option value=""></option>
                <option value="Production" <?php echo $phase == 'Production' ? 'selected' : ''; ?>>Production</option>
                <option value="Campionario" <?php echo $phase == 'Campionario' ? 'selected' : ''; ?>>Campionario</option>
                <option value="Integration" <?php echo $phase == 'Integration' ? 'selected' : ''; ?>>Integration</option>
            </select>
        </div>







        <div class="mb-3 col-md-6">
            <label for="state" class="form-label">Atelier</label>
            <input class="form-control" disabled type="text" id="tissu" name="tissu" maxlength="3" style="text-transform: uppercase;"  title="Veuillez entrer seulement les 3 lettres majuscules de code tissu" value="<?php echo $atelier; ?>" />
        </div>

        <div class="mb-3 col-md-6">
            <label for="quantite" class="form-label">Métrage</label>
            <input
                type="decimal"
                class="form-control"
                id="metrage"
                name="metrage"
                required
				
            />
        </div>
		<div class="mb-3 col-md-6">
            <label for="laize" class="form-label">Laize</label>
            <input
                type="NUMBER"
                class="form-control"
                id="laize"
                name="laize"
                required
				
            />
        </div>


            
       		<div class="mb-3 col-md-6">
    <label for="language" class="form-label">Date Fin</label>
    <input
        type="date"
        class="form-control"
        id="fin"
        name="fin"
        max="<?php echo date('Y-m-d'); ?>" 
		required
    />
	</div>





    </div>
    <div class="mt-2 text-end">
        <input type="submit" name="Suivant" class="btn btn-primary me-2" value="Enregistrer">
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

    <!-- Main JS -->
    <script src="../assets/js/main.js"></script>

    <!-- Page JS -->
    <script src="../assets/js/pages-account-settings-account.js"></script>

    <!-- Place this tag in your head or just before your close body tag. -->
    <script async defer src="https://buttons.github.io/buttons.js"></script>
  </body>
</html>
