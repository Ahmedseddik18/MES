        <?php
		session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../../index.php");
    exit();
}
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
		<style>
.card-body {
    width: 100%; /* Largeur ajustée à 80% de son conteneur */
    
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
              <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Commandes /</span> Archive Temps Standard</h4>

              <div class="row">
                <div class="col">
                 
				  
<div class="card ">
<h5 class="card-header">Archive Des Temps Standards</h5>
    <div class="card-body">
        <?php

		include("../php/db.php");
include("../php/fonction.php");
		$commande = isset($_GET['commande']) ? $_GET['commande'] : '';
$article = isset($_GET['article']) ? $_GET['article'] : '';
$phase = isset($_GET['phase']) ? $_GET['phase'] : '';
$categorie = isset($_GET['categorie']) ? $_GET['categorie'] : '';
$tissu = isset($_GET['tissu']) ? $_GET['tissu'] : '';
$dateChargement = isset($_GET['dateChargement']) ? $_GET['dateChargement'] : '';
$semaine = isset($_GET['semaine']) ? $_GET['semaine'] : '';
       $selectFields = 'distinct sub, *';
$fromTables = "commande";
$whereConditions = "1 = 1"; // Condition de base toujours vraie

// Ajout des conditions dynamiques
if (!empty($article)) {
    $whereConditions .= " AND article LIKE '%$article%'";
}
if (!empty($commande)) {
    $whereConditions .= " AND commande LIKE '%$commande%'";
}
if (!empty($phase)) {
    $whereConditions .= " AND phase LIKE '%$phase%'";
}
if (!empty($categorie)) {
    $whereConditions .= " AND categorie LIKE '%$categorie%'";
}
if (!empty($tissu)) {
    $whereConditions .= " AND tissu LIKE '%$tissu%'";
}
if (!empty($dateChargement)) {
    $whereConditions .= " AND dateChargement LIKE '%$dateChargement%'";
}
if (!empty($semaine)) {
    $whereConditions .= " AND semaine LIKE '%$semaine%'";
}
$whereConditions .= " GROUP BY article,commande,sub,id";
$orderBy = ' article,commande DESC'; // Assurez-vous de définir $orderBy si nécessaires

// Insertion des données en utilisant la fonction existante
$result = select($conn, $selectFields, $fromTables, $whereConditions, $orderBy);


        if ($result && $result->rowCount() > 0) {
            // Affichage du tableau
            echo '<div class="table-responsive text-nowrap">
                <table class="table table-hover">
                    <thead class="text-dark fs-4">
                        <tr>
						
							
                            <th class="border-bottom-0">
                                <h6 class="fw-semibold mb-0">Commande</h6>
                            </th>
                            <th class="border-bottom-0">
                                <h6 class="fw-semibold mb-0">Article</h6>
                            </th>
                            <th class="border-bottom-0">
                                <h6 class="fw-semibold mb-0">Sub</h6>
                            </th>
							
							
							<th class="border-bottom-0">
                                <h6 class="fw-semibold mb-0">TS Relaxation</h6>
                            </th>
							
							<th class="border-bottom-0">
                                <h6 class="fw-semibold mb-0">TS Matelassage</h6>
                            </th>
							<th class="border-bottom-0">
                                <h6 class="fw-semibold mb-0">TS Coupe</h6>
                            </th>
							<th class="border-bottom-0">
                                <h6 class="fw-semibold mb-0">TS Etiquetage</h6>
                            </th>
							
							<th class="border-bottom-0">
                                <h6 class="fw-semibold mb-0">Action</h6>
                            </th>
                            
                        </tr>
                    </thead>
                    <tbody>';
            // Utilisation de fetch() pour récupérer les résultats avec PDO
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
    echo "<tr>";
    
    echo "<td class='border-bottom-0'>" . htmlspecialchars($row['commande']) . "</td>";
    echo "<td class='border-bottom-0'>" . strtoupper(htmlspecialchars($row['article'])) . "</td>";
    echo "<td class='border-bottom-0'>" . strtoupper(htmlspecialchars($row['sub'])) . "</td>";
    

    echo "<td class='border-bottom-0'>" . strtoupper(htmlspecialchars($row['tsr'])) . "</td>";
    echo "<td class='border-bottom-0'>" . strtoupper(htmlspecialchars($row['tsm'])) . " </td>";
    echo "<td class='border-bottom-0' style='white-space: nowrap;'>" . strtoupper(htmlspecialchars($row['tsc'])) . " </td>";
    echo "<td class='border-bottom-0' style='white-space: nowrap;'>" . strtoupper(htmlspecialchars($row['tse'])) . " </td>";

    
    

    // Lien avec l'icône du crayon pour modifier
    echo "<td class='border-bottom-0'>";
	
    echo "<a href='modifier-temps-standard.php?&commande=" . $row['commande'] . "&article=" . urlencode($row['article']) . "&sub=" . urlencode($row['sub']) . " &tsr=" . urlencode($row['tsr']) . "&tsm=" . urlencode($row['tsm']) . "&tsc=" . urlencode($row['tsc']) . "&tse=" . urlencode($row['tse']) . "'>";

    echo "<i class='bx bx-pencil'></i>";
    echo "</a>";
    echo "</td>";

    echo "</tr>";
}

            echo '</tbody></table></div>'; // Fermeture des balises table et div
        } 
        ?>
    
</div>
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

    <!-- Main JS -->
    <script src="../assets/js/main.js"></script>

    <!-- Page JS -->
    <script src="../assets/js/pages-account-settings-account.js"></script>

    <!-- Place this tag in your head or just before your close body tag. -->
    <script async defer src="https://buttons.github.io/buttons.js"></script>
  </body>
</html>
