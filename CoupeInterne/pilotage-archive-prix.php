<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../../index.php");
    exit();
}
include("../php/db.php");
include("../php/fonction.php");

// Initialiser les variables pour éviter les erreurs
$alertClass = ''; // Valeur par défaut pour éviter l'erreur "Undefined variable"
$message = '';    // Valeur par défaut pour éviter l'erreur "Undefined variable"
if (isset($_POST['Filtrer'])) {
$commande = $_POST['commande'];
$article = $_POST['article'];
$phase = $_POST['phase'];
$categorie = $_POST['categorie'];
$tissu = $_POST['tissu'];
$dateChargement = $_POST['dateChargement'];
$semaine = $_POST['semaine'];

        
        header("Location: archive-prix.php?commande=$commande&article=$article&phase=$phase&categorie=$categorie&tissu=$tissu&dateChargement=$dateChargement&semaine=$semaine");
        exit(); // Toujours appeler exit() après une redirection pour stopper l'exécution du script
    


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

    <title>Coupe Interne | Archive Des Prix</title>

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
              <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Commandes /</span> Pilotage</h4>

              <div class="row">
                <div class="col-md-12">
                 
				  
<div class="card mb-4">
<h5 class="card-header">Pilotage Des Commandes</h5>
<form  method="POST" enctype="multipart/form-data" >
    <div class="card-body">


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
        
        
        title="Veuillez entrer une commande au format XXXXXXXXX"
        style="text-transform: uppercase;"
		
    />
</div>


                         <div class="mb-3 col-md-6">
    <label for="phase" class="form-label">Phase</label>
    <select id="phase" name="phase" class="select2 form-select">
        <option value=""></option>
        <option value="Production" >Production</option>
        <option value="Campionario" >Campionario</option>
        <option value="Integration" >Integration</option>
    </select>
</div>

                          <div class="mb-3 col-md-6">
    <label for="categorie" class="form-label">Catégorie</label>
    <select id="categorie" name="categorie" class="select2 form-select">
        <option value=""></option>
        <option value="Uni" >Uni</option>
        <option value="Raye" >Rayé</option>
    </select>
</div>



                        
                            


                          <div class="mb-3 col-md-6">
    <label for="tissu" class="form-label">Tissu</label>
    <input class="form-control" type="text" id="tissu" name="tissu"  style="text-transform: uppercase;"  title="Veuillez entrer seulement les 3 lettres majuscules de code tissu"  />
</div>
                          <div class="mb-3 col-md-6">
    <label for="semaine" class="form-label">Semaine</label>
    <input class="form-control" type="text" id="semaine" name="semaine" maxlength="2"   />
</div>
                          
						  
						  <div class=" col-md-6">
                            <label class="form-label" for="dateChargement">Date Chargement</label>
                            <input
                              type="date"
                              class="form-control"
                              id="dateChargement"
                              name="dateChargement"
                              
                            />
                          </div>
						   
                        
                          
                          
                        </div>
	                        <div class="mt-2 text-end">
    <input type="submit" name="Filtrer" class="btn btn-primary me-2" value="Filtrer">
    
    
</div>
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
