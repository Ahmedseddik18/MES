
<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../../index.php");
    exit();
}

// Vérifier s'il y a un message à afficher
if (isset($_SESSION['message'])) {
    $alertClass = $_SESSION['alertClass'];
    $message = $_SESSION['message'];
    
    echo '<div class="alert ' . $alertClass . ' fixed-top-right" role="alert" style="display: none;">';
echo $message;
echo '</div>';
    
    // Supprimer le message de la session après l'affichage pour éviter les duplications
    unset($_SESSION['alertClass']);
    unset($_SESSION['message']);
}
// Initialiser les variables pour éviter les erreurs
$alertClass = ''; // Valeur par défaut pour éviter l'erreur "Undefined variable"
$message = '';    // Valeur par défaut pour éviter l'erreur "Undefined variable"




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

    <title>Coupe Interne | Liste Du Personnel</title>

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
		.bg-label-violet {
    background-color: #9c27b0 ; /* Couleur violet */
    color: white;
}

.bg-label-brown {
    background-color: #795548; /* Couleur marron */
    color: white;
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
              <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Ressources /</span> Effectif</h4>

              <div class="row">
                <div class="col-md-12">



<div class="card mb-4">
    <div class="card-body">
	
        <div class="table-responsive text-nowrap">
                <table class="table table-hover">
                    <thead class="text-dark fs-4">
                        <tr>
                            <th class="border-bottom-0">
                                <h6 class="fw-semibold mb-0">Matricule</h6>
                            </th>
                            <th class="border-bottom-0">
                                <h6 class="fw-semibold mb-0">Nom</h6>
                            </th>
                            
							<th class="border-bottom-0">
                                <h6 class="fw-semibold mb-0">Prénom</h6>
                            </th>
							<th class="border-bottom-0">
                                <h6 class="fw-semibold mb-0">Fonction</h6>
                            </th>
							<th class="border-bottom-0">
                                <h6 class="fw-semibold mb-0">Nature</h6>
                            </th>
							<?php if ($_SESSION['role'] == 'admin'): ?>
                            <th class="border-bottom-0">
                                <h6 class="fw-semibold mb-0">Action</h6>
                            </th>
							<?php endif; ?>
                        </tr>
                    </thead>
                    <tbody>
					<?php
					include("../php/db.php");
include("../php/fonction.php");
$selectFields = '*';
$fromTables = 'effectif';
$whereConditions = '1=1'; // Ajoutez des conditions de filtrage si nécessaire
$orderBy = ''; // Assurez-vous de définir $orderBy ici si nécessaire

// Appel de la fonction select pour obtenir les résultats
$result = select($conn, $selectFields, $fromTables, $whereConditions, $orderBy);

// Vérifiez si la requête a réussi
if ($result) {            // Utilisation de fetch() pour récupérer les résultats avec PDO
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                echo "<tr>";
				echo "<td class='border-bottom-0'>" . htmlspecialchars($row['matricule']) . "</td>";
                echo "<td class='border-bottom-0'>" . htmlspecialchars($row['nom']) . "</td>";
                echo "<td class='border-bottom-0'>" . strtoupper(htmlspecialchars($row['prenom'])) . "</td>";

                if (htmlspecialchars($row['fonction']) == 'agent-bureautique') {
                    echo "<td class='border-bottom-0'><span class='badge bg-label-primary me-1'>" . htmlspecialchars($row['fonction']) . "</span></td>";
                } elseif (htmlspecialchars($row['fonction']) == 'matelasseur') {
                    echo "<td class='border-bottom-0'><span class='badge bg-label-success me-1'>" . htmlspecialchars($row['fonction']) . "</span></td>";
                } elseif (htmlspecialchars($row['fonction']) == 'coupeur') {
                    echo "<td class='border-bottom-0'><span class='badge bg-label-warning me-1'>" . htmlspecialchars($row['fonction']) . "</span></td>";
                }elseif (htmlspecialchars($row['fonction']) == 'manutention') {
                    echo "<td class='border-bottom-0'><span class='badge bg-label-secondary me-1'>" . htmlspecialchars($row['fonction']) . "</span></td>";
                }elseif (htmlspecialchars($row['fonction']) == 'controle') {
                    echo "<td class='border-bottom-0'><span class='badge bg-label-brown me-1'>" . htmlspecialchars($row['fonction']) . "</span></td>";
                }elseif (htmlspecialchars($row['fonction']) == 'chef-equipe') {
                    echo "<td class='border-bottom-0'><span class='badge bg-label-violet me-1'>" . htmlspecialchars($row['fonction']) . "</span></td>";
                }else{
					echo "<td class='border-bottom-0'><span class='badge bg-label-info me-1'>" . htmlspecialchars($row['fonction']) . "</span></td>";
				}
                
				if (htmlspecialchars($row['nature']) == 'direct') {
                    echo "<td class='border-bottom-0'><span class='badge bg-label-danger me-1'>" . htmlspecialchars($row['nature']) . "</span></td>";
                } else {
                    echo "<td class='border-bottom-0'><span class='badge bg-label-dark me-1'>" . htmlspecialchars($row['nature']) . "</span></td>";
                } 
				if ($_SESSION['role'] == 'admin'){
                echo "<td class='border-bottom-0'>";
                echo "<a href='modifier-effectif.php?id=" . htmlspecialchars($row['id']) . "'><i class='bx bxs-pencil'></i></a>"; // Icône d'édition
				echo "  ";
                echo "<a href='supprimer-effectif.php?id=" . htmlspecialchars($row['id']) . "'><i class='bx bxs-trash'></i></a>"; // Icône d'édition
                }
				echo "</td>";
				
                echo "</tr>";
            }
}

        ?>
					
					</tbody></table></div>
					<?php if ($_SESSION['role'] == 'admin'): ?>
					<div class="mt-2 text-end">
                          <button type="submit" name "Suivant" class="btn btn-primary me-2" onclick="window.location.href='ajouter-effectif.php';">Ajouter</button>
                          
                        </div>
						<?php endif; ?>
    </div>
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
