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

    <title>Coupe Interne | Liste Des Traçés</title>

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
              <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Traçé /</span> Liste Des Traçés</h4>

              <div class="row">
                <div class="col">
                 
				  
<div class="card ">
<div class="card-header d-flex justify-content-between align-items-center">
    <h5 class="mb-0">Pilotage Des Traçés</h5>

</div>

<div class="card-body">
<?php
include("../php/db.php");
include("../php/fonction.php");

$selectFields = '*';
$fromTables = "trace";
$whereConditions = "etat IN ('En cours', 'En attente') 
   "; // Condition de base toujours vraie
$orderBy = 'article ASC'; // Assurez-vous de définir $orderBy si nécessaire

// Insertion des données en utilisant la fonction existante
$result = select($conn, $selectFields, $fromTables, $whereConditions, $orderBy);

if ($result && $result->rowCount() > 0) {
    // Initialisation des sommes
    $totalPrix = 0;
    $totalQuantite = 0;

    // Affichage du tableau
    echo '<div class="table-responsive text-nowrap">
        <table class="table table-hover" id="wipTable">
            <thead class="text-dark fs-4">
                <tr>
                    <th class="border-bottom-0">
                        <h6 class="fw-semibold mb-0">Date Traçé</h6>
                    </th>
                    <th class="border-bottom-0">
                        <h6 class="fw-semibold mb-0">Commande</h6>
                    </th>
                    <th class="border-bottom-0">
                        <h6 class="fw-semibold mb-0">Article</h6>
                    </th>
					<th class="border-bottom-0">
                        <h6 class="fw-semibold mb-0">Atelier</h6>
                    </th>
                    <th class="border-bottom-0">
                        <h6 class="fw-semibold mb-0">Phase</h6>
                    </th>
                    <th class="border-bottom-0">
                        <h6 class="fw-semibold mb-0">Etat</h6>
                    </th>
                    
                    
                    <th class="border-bottom-0">
                        <h6 class="fw-semibold mb-0">Document</h6>
                    </th>
					<th class="border-bottom-0">
                        <h6 class="fw-semibold mb-0">Action</h6>
                    </th>
                </tr>
            </thead>
            <tbody>';
    $index = 1; // Pour générer des IDs uniques
    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        // Ajout des valeurs aux sommes
        $totalQuantite += (float)$row['metrage'];
        $totalPrix += (float)$row['PrixTotale'];

        // Identifiant unique pour chaque ligne
        $collapseId = "collapseRow" . $index;

        echo "<tr data-bs-toggle='collapse' data-bs-target='#$collapseId' class='clickable-row'>";
        echo "<td class='border-bottom-0'>" . htmlspecialchars($row['date']) . "</td>";
        echo "<td class='border-bottom-0'>" . htmlspecialchars($row['commande']) . "</td>";
        echo "<td class='border-bottom-0'>" . strtoupper(htmlspecialchars($row['article'])) . "</td>";
        echo "<td class='border-bottom-0'>" . strtoupper(htmlspecialchars($row['atelier'])) . "</td>";

        if (htmlspecialchars($row['phase']) == 'Production') {
            echo "<td class='border-bottom-0'><span class='badge bg-label-primary me-1'>" . htmlspecialchars($row['phase']) . "</span></td>";
        } elseif (htmlspecialchars($row['phase']) == 'Campionario') {
            echo "<td class='border-bottom-0'><span class='badge bg-label-success me-1'>" . htmlspecialchars($row['phase']) . "</span></td>";
        } else {
            echo "<td class='border-bottom-0'><span class='badge bg-label-warning me-1'>" . htmlspecialchars($row['phase']) . "</span></td>";
        }

        if (htmlspecialchars($row['etat']) == 'En attente') {
            echo "<td class='border-bottom-0'><span class='badge bg-label-secondary me-1'>" . htmlspecialchars($row['etat']) . "</span></td>";
        }elseif (htmlspecialchars($row['etat']) == 'En cours') {
            echo "<td class='border-bottom-0'><span class='badge bg-label-warning me-1'>" . htmlspecialchars($row['etat']) . "</span></td>";
        }elseif (htmlspecialchars($row['etat']) == 'T1') {
            echo "<td class='border-bottom-0'><span class='badge bg-label-info me-1'>" . htmlspecialchars($row['etat']) . "</span></td>";
        }elseif (htmlspecialchars($row['etat']) == 'Termine') {
            echo "<td class='border-bottom-0'><span class='badge bg-label-success me-1'>" . htmlspecialchars($row['etat']) . "</span></td>";
        } 

$fileNames = explode(',', $row['fichier']);
$fileUrls = [];
$atelier = urlencode($row['atelier']);
$commande = urlencode($row['commande']);
$article = urlencode($row['article']);

foreach ($fileNames as $fileName) {
    $fileName = trim($fileName);

    // Vérifier si le fichier a l'extension .pdf
    if (!empty($fileName) ) {
        $filePath =  "uploads/trace/" . 
            $article . "/" . 
            $commande . "/" . $fileName;

        // Debug: Afficher les chemins des fichiers
        echo "<!-- Chemin fichier : " . htmlspecialchars($filePath) . " -->";

        // Vérifier si le fichier existe et ajouter l'URL
        if ($filePath !== false) {
            $fileUrls[] = $filePath;
        } else {
            error_log("Fichier introuvable : " . $fileName); // Log en cas d'erreur
            echo "<!-- Erreur : Fichier introuvable : " . htmlspecialchars($fileName) . " -->";
        }
    }
}

// Affichage dans la cellule HTML avec une icône de téléchargement
echo "<td class='border-bottom-0'>";

// Si aucun fichier trouvé
if (empty($fileUrls)) {
    echo "<!-- Aucun fichier PDF disponible -->";
}

foreach ($fileUrls as $fileUrl) {
    $fileName = basename($fileUrl);

    // Vérifier si l'extension du fichier est '.pdf'
    if (strtolower(pathinfo($fileName, PATHINFO_EXTENSION)) === 'pdf') {
        echo "<a href='#' class='pdf-link' data-file-url='" . htmlspecialchars($fileUrl) . "' 
               data-file-name='" . htmlspecialchars($fileName) . "'>
                <i class='bx bxs-file-pdf'></i>
              </a>";
    }
}


$filePath2=  "uploads/trace/" . 
            $article . "/" . 
            $commande . "/" ;

echo "<a href='../php/download-trace.php?files=$filePath2&atelier=$atelier&commande=$commande&article=$article' 
      class='download-all' title='Télécharger tous les fichiers'>
        <i class='bx bxs-download'></i>
      </a>";

echo "</td>";
echo "<td class='border-bottom-0'>";
echo "<a href='modifier-trace.php?id=" . htmlspecialchars($row['id']) . "'><i class='bx bxs-pencil'></i></a>";
echo "</td>";
echo "</tr>";

        $index++;
    }





    echo '</tbody></table></div>';
} else {
    // Afficher l'erreur si aucune donnée n'est trouvée
    echo '<div class="misc-wrapper text-center">
        <h2 class="mb-2 mx-2">Aucune commande trouvée !!</h2>
        <p class="mb-4 mx-2">Malheureusement, nous n\'avons pas trouvé de commandes correspondant à votre recherche.</p>
       
        <div class="mt-3">
            <img src="../assets/img/illustrations/404.png" alt="page-misc-error-light" width="500" class="img-fluid" />
        </div>
    </div>';
}
?>



    </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function () {
    var pdfLinks = document.querySelectorAll('.pdf-link');

    pdfLinks.forEach(function (link) {
        link.addEventListener('click', function (event) {
            event.preventDefault(); // Empêcher le comportement par défaut

            var fileUrl = this.getAttribute('data-file-url');
            var fileName = this.getAttribute('data-file-name') || 'Document';

            // Débogage : Vérifier les URLs et noms de fichiers dans la console
            console.log("URL du fichier :", fileUrl);
            console.log("Nom du fichier :", fileName);

            // Ouvrir le fichier dans une nouvelle fenêtre ou onglet
            var newWindow = window.open(fileUrl, '_blank');

            if (newWindow) {
                newWindow.onload = function () {
                    newWindow.document.title = fileName;
                };
            } else {
                alert('Veuillez autoriser les popups pour ouvrir le document.');
                console.error("Impossible d'ouvrir le fichier :", fileUrl);
            }
        });
    });
});
</script>

















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
