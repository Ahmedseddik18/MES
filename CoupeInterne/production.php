<?php
session_start();


// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../index.php");
    exit();
}

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
    'duree' => '',
    

    'quantite' => '',
    
    'date' => '',
    'operateur1' => '',
    'operateur2' => '',
    'sub' => ''
];

// Vérification de la soumission du formulaire
if (isset($_POST['Suivant'])) {
    // Collecter et filtrer les données du formulaire
    $formData = [
        'commande' => $_POST['commande'] ?? '',
        'article' => strtoupper($_POST['article'] ?? ''),
        'phase' => $_POST['phase'] ?? '',
        'duree' => $_POST['duree'] ?? '',
        'quantite' => $_POST['quantite'] ?? '',
        'operateur1' => $_POST['op1'] ?? '',
        'operateur2' => $_POST['op2'] ?? '',
        
        'date' => $_POST['date'] ?? '',
        
        'sub' => $_POST['sub'] ?? '',
        
    ];





    // Procéder à l'insertion des données si tout est correct
    if (empty($message)) {
        $table = 'production';
        $conditions = "commande = '{$formData['commande']}' AND article = '{$formData['article']}' and phase = '{$formData['phase']}' and quantite = '{$formData['quantite']}' and HR = '{$formData['duree']}' and date = '{$formData['date']}' and operateur1 ='{$formData['operateur1']}' and operateur2 ='{$formData['operateur2']}'  and sub = '{$formData['sub']}' ";
        $existingCount = checkData($table, $conditions, $conn);

        // Vérification de l'existence de la commande
        if ($existingCount > 0) {
            $alertClass = 'alert-danger';
            $message = "Cette commande existe déjà dans la base de données.";
        } else {
            
           
                
                $data = 'commande, article, phase,   quantite,HR,  date, operateur1, operateur2,  sub';
                $values = "'{$formData['commande']}', '{$formData['article']}', '{$formData['phase']}',   '{$formData['quantite']}', '{$formData['duree']}', '{$formData['date']}', '{$formData['operateur1']}', '{$formData['operateur2']}',  '{$formData['sub']}'";

                // Insertion des données dans la base
                $result = insertData($table, $data, $values, $conn);

                // Affichage des résultats
                $alertClass = $result['alertClass'];
                $message = $result['message'];


          
        }
    }
}

$allSubValues = ['01', '21', '31', '51', '02', '32'];
$firstColumnValues = ['01', '21', '31','41', '51'];
$secondColumnValues = ['02','22', '32','42','52'];
$thirdColumnValues = ['03', '23', '33'];

$formData['sub'] = isset($_POST['sub']) ? $_POST['sub'] : [];


// Affichage des alertes
echo '<div class="alert ' . $alertClass . ' fixed-top-right" role="alert" style="display: none;">';
echo $message;
echo '</div>';

// Script pour afficher/masquer les alertes
echo '<script>';
echo 'setTimeout(function() { document.querySelector(".alert.fixed-top-right").style.display = "block"; }, 200);';
echo 'setTimeout(function() { document.querySelector(".alert.fixed-top-right").style.display = "none"; }, 20000);';
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
              <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Commandes /</span> Production</h4>

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
    <label for="phase" class="form-label">Phase</label>
    <select required  id="phase" name="phase" class="select2 form-select">
        <option value=""></option>
        <option value="relaxation" >Relaxation</option>
        <option value="matelassage" >Matelassage</option>
        <option value="coupe" >Coupe</option>
		<option value="etiquetage" >Post-Coupe</option>
    </select>
</div>

                          <div class="mb-3 col-md-6">
    <label for="state" class="form-label">Durée</label>
    <input class="form-control" type="TEXT" id="duree" name="duree" maxlength="" style="text-transform: uppercase;"  required  />
</div>



                        


                          <div class="mb-3 col-md-6">
    <label for="state" class="form-label">Opérateur 1</label>
    <input class="form-control" type="text" id="op1" name="op1" maxlength="4" style="text-transform: uppercase;"  required  />
</div>
                          <div class="mb-3 col-md-6">
    <label for="state" class="form-label">Opérateur 2</label>
    <input class="form-control" type="text" id="op2" name="op2" maxlength="4" style="text-transform: uppercase;"   />
</div>

<div class=" col-md">
                            <label class="form-label" for="quantite">Quantité</label>
                            <input
                              type="number"
                              class="form-control"
                              id="quantite"
                              name="quantite"
                              required 
                            />
                          </div>

						  <div class="col-md">
  <label class="form-label" for="dateChargement">Date Production</label>
  <input
    type="date"
    class="form-control"
    id="date"
    name="date"
    max="<?php echo date('Y-m-d'); ?>" 
    required
  />
</div>

						   </div>
<div class="mb-3 col-md-6 my-2">
    <label for="language" class="form-label">Sub</label>
    <div class="row gy-3">
        <div class="col-md">
            <!-- Première colonne -->
            <?php foreach ($firstColumnValues as $value): ?>
                <div class="form-check">
                    <input  class="form-check-input" type="radio" name="sub" value="<?php echo $value; ?>" id="Radio<?php echo $value; ?>">
                    
                    <label class="form-check-label" for="Radio<?php echo $value; ?>">
                        <?php echo $value; ?>
                    </label>
                </div>
            <?php endforeach; ?>
        </div>
        <div class="col-md">
            <!-- Deuxième colonne -->
            <?php foreach ($secondColumnValues as $value): ?>
                <div class="form-check">
                    <input  class="form-check-input" type="radio" name="sub" value="<?php echo $value; ?>" id="Radio<?php echo $value; ?>" >
                    
                    <label class="form-check-label" for="Radio<?php echo $value; ?>">
                        <?php echo $value; ?>
                    </label>
                </div>
            <?php endforeach; ?>
        </div>
        <div class="col-md">
            <!-- Troisième colonne -->
            <?php foreach ($thirdColumnValues as $value): ?>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="sub" value="<?php echo $value; ?>" id="Radio<?php echo $value; ?>"
                     />
                    <label class="form-check-label" for="Radio<?php echo $value; ?>">
                        <?php echo $value; ?>
                    </label>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

                          
                          
                        
                        <div class=" text-end">
    <input type="submit" name="Suivant" class="btn btn-primary me-2" value="Suivant">
    
    <a href="production.php" class="btn btn-outline-secondary">Annuler</a>
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
