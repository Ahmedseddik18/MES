<?php
session_start();

if (!isset($_SESSION['user_id'])) {
     header("Location: ../../index.php");
    exit();
}
include("../php/db.php");
include("../php/fonction.php");

// Définir la commande et l'article (assurez-vous de les obtenir de manière sécurisée)
$commande = $_GET['commande'];
$article = $_GET['article'];
$commandeAncien = $_GET['commande'];
$articleAncien = $_GET['article'];
$article2 = strtolower($_GET['article']);
$alertClass = ''; // Valeur par défaut pour éviter l'erreur "Undefined variable"
$message = '';    // Valeur par défaut pour éviter l'erreur "Undefined variable"
$focusId = '';
// Sélectionner les champs nécessaires
$selectFields = ' id,phase, categorie, traitement, relaxation, tissu, quantiteDemandee, nbPartie, dateChargement';
$fromTables = 'db';
$whereConditions = "commande='$commande' and article='$article2'";
$orderBy = ''; // Assurez-vous de définir $orderBy ici si nécessaire

// Préparer et exécuter la requête pour récupérer les données
$result = select($conn, $selectFields, $fromTables, $whereConditions, $orderBy);


// Vérifier si la requête a retourné un résultat
if ($result) {
    $row = $result->fetch(PDO::FETCH_ASSOC);
    
    $phase = $row['phase'];
    $categorie = $row['categorie'];
    $traitement = $row['traitement'];
    $relaxation = $row['relaxation'];
    $tissu = $row['tissu'];
	$id = $row['id'];
    $quantite = $row['quantiteDemandee'];
    $nbPartie = $row['nbPartie'];
	$dateChargement = $row['dateChargement'];
} else {
    // Gestion d'erreur si la requête échoue
    $alertClass = 'alert-danger';
    $message = 'Erreur lors de la récupération des données.';
}
// Vérifier si le formulaire a été soumis
// Vérifier si le formulaire a été soumis
if (isset($_POST['Suivant'])) {
    // Récupération des données du formulaire avec vérifications
    $formData = [
        'commande' => isset($_POST['commande']) ? $_POST['commande'] : '',
        'article' => isset($_POST['article']) ? $_POST['article'] : '',
        'phase' => isset($_POST['phase']) ? $_POST['phase'] : '',
        'categorie' => isset($_POST['categorie']) ? $_POST['categorie'] : '',
        'traitement' => isset($_POST['traitement']) ? implode(',', $_POST['traitement']) : '',
        'relaxation' => isset($_POST['relaxation']) ? $_POST['relaxation'] : '',
        'tissu' => isset($_POST['tissu']) ? strtoupper($_POST['tissu']) : '',
        'quantite' => isset($_POST['quantite']) ? $_POST['quantite'] : '',
        'nbPartie' => isset($_POST['nbPartie']) ? $_POST['nbPartie'] : '',
        'dateChargement' => isset($_POST['dateChargement']) ? $_POST['dateChargement'] : '',
        'nbSub' => isset($_POST['sub']) ? count($_POST['sub']) : 0, // Compte le nombre de checkboxes sélectionnées
        'subValues' => isset($_POST['sub']) ? implode(',', $_POST['sub']) : ''
    ];

    // Calcul de la semaine de dateChargement
    $formData['semaine'] = $formData['dateChargement'] ? date('W', strtotime($formData['dateChargement'])) : '';

    // Vérification des variables pour éviter les valeurs nulles ou vides
    $fields = [
        'commande' => $formData['commande'],
        'article' => $formData['article'],
        'phase' => $formData['phase'],
        'categorie' => $formData['categorie'],
        'traitement' => $formData['traitement'],
        'relaxation' => $formData['relaxation'],
        'tissu' => $formData['tissu'],
        'quantite' => $formData['quantite'],
        'nbPartie' => $formData['nbPartie'],
        'dateChargement' => $formData['dateChargement'],
        'nbSub' => $formData['nbSub'],
        'semaine' => $formData['semaine']
    ];

    foreach ($fields as $key => $value) {
        if (empty($value) && $key !== 'nbSub' && $key !== 'semaine') {
            // Si une des variables importantes est vide (en ignorant nbSub et semaine), afficher une erreur
            $alertClass = 'alert-danger';
            $message = "Le champ '$key' ne peut pas être vide.";
            $focusId = $key; // Définir l'identifiant du champ à focaliser
            break;
        }
    }
    if(!$focusId) {
        // Vérification spécifique pour les cases à cocher "sub"
        if (empty($formData['subValues'])) {
            $alertClass = 'alert-danger';
            $message = "Vous devez sélectionner au moins un sub.";
            $focusId = 'Check01'; // Vous pouvez définir un focus sur l'une des premières cases à cocher
        }
    }

    if (empty($message)) {
        // Définir les conditions pour vérifier si l'article et la commande existent déjà
        $table = 'db'; // Remplacez par le nom de votre table
        $conditions = "id = '$id'";

        // Utiliser la fonction checkData pour vérifier l'existence
        $existingCount = checkData($table, $conditions, $conn);

        if ($existingCount > 0) {
            // Si la combinaison existe déjà, définir les conditions pour la mise à jour
            $updateFields = [
			'commande' => $formData['commande'],
        'article' => $formData['article'],
                'phase' => $formData['phase'],
                'categorie' => $formData['categorie'],
                'traitement' => $formData['traitement'],
                'relaxation' => $formData['relaxation'],
                'tissu' => $formData['tissu'],
                'quantiteDemandee' => $formData['quantite'],
                'nbPartie' => $formData['nbPartie'],
                'dateChargement' => $formData['dateChargement'],
                'nbSub' => $formData['nbSub'],
                
                'semaine' => $formData['semaine']
            ];

            $setClauses = [];
            foreach ($updateFields as $key => $value) {
                $setClauses[] = "$key = '$value'";
            }

            $setClause = implode(', ', $setClauses);

            // Exécuter la requête de mise à jour
            $updateQuery = "UPDATE $table SET $setClause WHERE id = '$id'";
            $updateResult = $conn->exec($updateQuery);

            if ($updateResult !== false) {
                // Assigner les valeurs des alertes
                $alertClass = 'alert-success';
                $message = "Les informations ont été mises à jour avec succès.";

                // Redirection vers la page ajouter-details-matelas.php avec les paramètres
                $nbPartie = urlencode($formData['nbPartie']);
                $subValues = urlencode($formData['subValues']);
                $commande = urlencode($formData['commande']);
                $article = urlencode($formData['article']);
                header("Location: ajouter-details-matelas.php?nbPartie=$nbPartie&subValues=$subValues&commande=$commande&article=$article");
                exit(); // Toujours appeler exit() après une redirection pour stopper l'exécution du script
            } else {
                // Gestion des erreurs de mise à jour
                $alertClass = 'alert-danger';
                $message = "Erreur lors de la mise à jour des informations.";
            }
        } else {
            // Si la combinaison n'existe pas, définir un message d'erreur
            $alertClass = 'alert-danger';
            $message = "Cette commande n'existe pas dans la base de données.";
        }
    }
}




// Préparer la requête pour récupérer les valeurs des cases à cocher
$query = "SELECT sub FROM commande WHERE commande = :commande AND article = :article";
$stmt = $conn->prepare($query);
$stmt->bindParam(':commande', $commande);
$stmt->bindParam(':article', $article);
$stmt->execute();

// Récupérer les valeurs des cases à cocher
$checkedValues = $stmt->fetchAll(PDO::FETCH_COLUMN, 0);
$firstColumnValues = ['01', '21', '31', '51']; // Valeurs pour la première colonne
$secondColumnValues = ['02', '32']; // Valeurs pour la deuxième colonne
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
              <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Commandes /</span> Modifier</h4>

              <div class="row">
                <div class="col-md-12">
                  <ul class="nav nav-pills flex-column flex-md-row mb-3">
                    <li class="nav-item">
                      <a class="nav-link " href="javascript:void(0);"><i class='bx bx-plus'></i> Ajouter</a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link active" href="pages-account-settings-notifications.html"
                        ><i class='bx bxs-pencil ' ></i> Modifier</a
                      >
                    </li>
                    
                  </ul>
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
				
            />
        </div>

        <div class="mb-3 col-md-6">
            <label for="phase" class="form-label">Phase</label>
            <select id="phase" name="phase" class="select2 form-select" >
                <option value=""></option>
                <option value="Production" <?php echo $phase == 'Production' ? 'selected' : ''; ?>>Production</option>
                <option value="Campionario" <?php echo $phase == 'Campionario' ? 'selected' : ''; ?>>Campionario</option>
                <option value="Integration" <?php echo $phase == 'Integration' ? 'selected' : ''; ?>>Integration</option>
            </select>
        </div>

        <div class="mb-3 col-md-6">
            <label for="categorie" class="form-label">Catégorie</label>
            <select id="categorie" name="categorie" class="select2 form-select"  >
                <option value=""></option>
                <option value="Uni" <?php echo $categorie == 'Uni' ? 'selected' : ''; ?>>Uni</option>
                <option value="Raye" <?php echo $categorie == 'Raye' ? 'selected' : ''; ?>>Rayé</option>
            </select>
        </div>

        <div class="mb-3 col-md-6">
            <label class="form-label" for="traitement">Traitement</label>
            <div class="row gy-3">
                <div class="col-md">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="traitement[]" value="SM" id="SM" <?php echo in_array('SM', explode(',', $traitement)) ? 'checked' : ''; ?>  />
                        <label class="form-check-label" for="SM"> SM </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="traitement[]" value="RM" id="RM" <?php echo in_array('RM', explode(',', $traitement)) ? 'checked' : ''; ?>  />
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
                        <input name="relaxation" class="form-check-input" type="radio" value="Oui" id="RadioOUI" <?php echo $relaxation === 'Oui' ? 'checked' : ''; ?>   />
                        <label class="form-check-label" for="RadioOUI"> Oui </label>
                    </div>
                    <div class="form-check">
                        <input name="relaxation" class="form-check-input" type="radio" value="Non" id="RadioNon" <?php echo $relaxation === 'Non' ? 'checked' : ''; ?>  />
                        <label class="form-check-label" for="RadioNon"> Non </label>
                    </div>
                </div>
            </div>
        </div>

        <div class="mb-3 col-md-6">
            <label for="state" class="form-label">Tissu</label>
            <input class="form-control" type="text" id="tissu" name="tissu" maxlength="3" style="text-transform: uppercase;"  title="Veuillez entrer seulement les 3 lettres majuscules de code tissu" value="<?php echo $tissu; ?>" />
        </div>

        <div class="mb-3 col-md-6">
            <label for="quantite" class="form-label">Quantité</label>
            <input
                type="number"
                class="form-control"
                id="quantite"
                name="quantite"
                value="<?php echo $quantite; ?>"
				
            />
        </div>

        <div class="col">
            <div class="col-md">
                <label class="form-label" for="nbPartie">Nombre Partie</label>
                <input
                    type="number"
                    class="form-control"
                    id="nbPartie"
                    name="nbPartie"
                    value="<?php echo $nbPartie; ?>"
					
                />
            </div>
            
       		<div class="mb-3 col-md">
    <label for="language" class="form-label">Date Chargement</label>
    <input
        type="date"
        class="form-control"
        id="dateChargement"
        name="dateChargement"
        value="<?php echo $dateChargement; ?>"
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
                    <?php echo in_array($value, $checkedValues) ? 'checked' : ''; ?> />
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
                    <?php echo in_array($value, $checkedValues) ? 'checked' : ''; ?> />
                    <label class="form-check-label" for="Check<?php echo $value; ?>">
                        <?php echo $value; ?>
                    </label>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>



    </div>
    <div class="mt-2 text-end">
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

    <!-- Main JS -->
    <script src="../assets/js/main.js"></script>

    <!-- Page JS -->
    <script src="../assets/js/pages-account-settings-account.js"></script>

    <!-- Place this tag in your head or just before your close body tag. -->
    <script async defer src="https://buttons.github.io/buttons.js"></script>
  </body>
</html>