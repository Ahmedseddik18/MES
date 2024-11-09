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

    <title>Coupe Interne | Wip</title>

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
              <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Production /</span> Wip</h4>

              <div class="row">
                <div class="col">
                 
				  
<div class="card ">
<div class="card-header d-flex justify-content-between align-items-center">
    <h5 class="mb-0">Pilotage Des Commandes</h5>
    <a href="wip-excel.php" class="text-decoration-none">
        <img src="../assets/img/avatars/excel.svg" alt="Icon" style="width: 30px; height: 30px;">
    </a>
</div>

<div class="card-body">
<?php
include("../php/db.php");
include("../php/fonction.php");

$selectFields = '*';
$fromTables = "db";
$whereConditions = "Etat IN ('En cours', 'En attente', 'Bloque', 'T1') 
   OR (Etat = 'Termine' AND DATE_PART('week', fin) = DATE_PART('week', CURRENT_DATE))"; // Condition de base toujours vraie
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
                        <h6 class="fw-semibold mb-0">Date Chargement</h6>
                    </th>
                    <th class="border-bottom-0">
                        <h6 class="fw-semibold mb-0">Commande</h6>
                    </th>
                    <th class="border-bottom-0">
                        <h6 class="fw-semibold mb-0">Article</h6>
                    </th>
                    <th class="border-bottom-0">
                        <h6 class="fw-semibold mb-0">Phase</h6>
                    </th>
                    <th class="border-bottom-0">
                        <h6 class="fw-semibold mb-0">Etat</h6>
                    </th>
                    <th class="border-bottom-0">
                        <h6 class="fw-semibold mb-0">Quantité</h6>
                    </th>
                    <th class="border-bottom-0">
                        <h6 class="fw-semibold mb-0">Prix</h6>
                    </th>
                    <th class="border-bottom-0">
                        <h6 class="fw-semibold mb-0">Document</h6>
                    </th>
                </tr>
            </thead>
            <tbody>';
    $index = 1; // Pour générer des IDs uniques
    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        // Ajout des valeurs aux sommes
        $totalQuantite += (float)$row['quantitedemandee'];
        $totalPrix += (float)$row['prixtotal'];

        // Identifiant unique pour chaque ligne
        $collapseId = "collapseRow" . $index;

        echo "<tr data-bs-toggle='collapse' data-bs-target='#$collapseId' class='clickable-row'>";
        echo "<td class='border-bottom-0'>" . htmlspecialchars($row['datechargement']) . "</td>";
        echo "<td class='border-bottom-0'>" . htmlspecialchars($row['commande']) . "</td>";
        echo "<td class='border-bottom-0'>" . strtoupper(htmlspecialchars($row['article'])) . "</td>";

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
        } elseif (htmlspecialchars($row['etat']) == 'Bloque') {
            echo "<td class='border-bottom-0'><span class='badge bg-label-danger me-1'>" . htmlspecialchars($row['etat']) . "</span></td>";
        }

        echo "<td class='border-bottom-0'>" . strtoupper(htmlspecialchars($row['quantitedemandee'])) . "</td>";
		
			echo "<td class='border-bottom-0' style='white-space: nowrap;'>" . strtoupper(htmlspecialchars($row['prixtotal'])) . " €</td>";
		
       


$fileNames = explode(',', $row['fichier']);
$fileUrls = [];

foreach ($fileNames as $fileName) {
    $fileName = trim($fileName);
    if (!empty($fileName)) {
        $fileUrls[] = "uploads/" . htmlspecialchars($row['article']) . "/" . $fileName;
    }
}
echo "<td class='border-bottom-0'>" ;
  foreach ($fileUrls as $fileUrl) {
            $fileName = basename($fileUrl); // Extraire le nom du fichier pour l'utiliser comme titre
            echo "<a href='#' class='pdf-link' data-file-url='" . htmlspecialchars($fileUrl) . "' data-file-name='" . htmlspecialchars($fileName) . "'><i class='bx bxs-file-pdf'></i> </a>";
        }

echo "</td>";




        
        echo "</tr>";

        // Contenu supplémentaire affiché en collapse
        echo "<tr id='$collapseId' class='collapse'>";
        echo "<td colspan='9'>";
        echo "<div class='p-3'>";
        echo "<strong>Informations Supplémentaires :</strong><br>";
        // Ajouter les informations supplémentaires ici
        echo '<table style="width: 100%;">';
        echo '<tr>';
        echo '<td style="width: 50%; vertical-align: top;">';
        echo "Relaxation : " . htmlspecialchars($row['relaxation']) . "<br>";
        echo "Catégorie : " . htmlspecialchars($row['categorie']) . "<br>";
        echo "Traitement : " . htmlspecialchars($row['traitement']) . "<br>";
        echo '</td>';
        echo '<td style="width: 50%; vertical-align: top;">';
        echo "Tissu : " . htmlspecialchars($row['tissu']) . "<br>";
        echo "Nombre Partie : " . htmlspecialchars($row['nbpartie']) . "<br>";
        echo "Nombre Sub : " . htmlspecialchars($row['nbsub']) . "<br>";
        echo '</td>';

        echo '</tr>';
        echo '</table>';

        echo "</div>";
        echo "</td>";
        echo "</tr>";

        $index++;
    }

    // Ligne supplémentaire pour afficher les totaux sous les colonnes correspondantes
echo "<tr class='fw-semibold'>
        <td colspan='5' class='text-end'></td>
        <td id='totalQuantite'>" . htmlspecialchars($totalQuantite) . "</td> <!-- Cellule modifiable en JS -->
        <td id='totalPrix'>" . number_format($totalPrix, 2, ',', ' ') . "&nbsp;€</td> <!-- Cellule modifiable en JS -->
        <td></td>
      </tr>";



    echo '</tbody></table></div>';
} else {
    // Afficher l'erreur si aucune donnée n'est trouvée
    echo '<div class="misc-wrapper text-center">
        <h2 class="mb-2 mx-2">Aucune commande trouvée !!</h2>
        <p class="mb-4 mx-2">Malheureusement, nous n\'avons pas trouvé de commandes correspondant à votre recherche.</p>
        <a href="pilotage-archive.php" class="btn btn-primary">Retour</a>
        <div class="mt-3">
            <img src="../assets/img/illustrations/404.png" alt="page-misc-error-light" width="500" class="img-fluid" />
        </div>
    </div>';
}
?>



    </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var pdfLinks = document.querySelectorAll('.pdf-link');

    pdfLinks.forEach(function(link) {
        link.addEventListener('click', function(event) {
            event.preventDefault(); // Empêche le comportement par défaut du lien
            
            // Récupérer les URLs des fichiers et les noms de fichiers depuis les attributs de données
            var fileUrls = this.getAttribute('data-file-url').split(',');
            var fileNames = this.getAttribute('data-file-name').split(',');

            fileUrls.forEach(function(url, index) {
                var fileName = fileNames[index] || 'Document';
                window.open(url, '_blank').document.title = fileName; // Essayer de définir le titre de la fenêtre
            });
        });
    });
});
</script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const searchInput = document.getElementById('tableSearch');
    const rows = document.querySelectorAll('#wipTable tbody tr:not(.collapse)'); // Sélectionner uniquement les lignes visibles, exclure les lignes collapse
    const totalQuantiteCell = document.getElementById('totalQuantite');
    const totalPrixCell = document.getElementById('totalPrix');

    function recalculerTotaux() {
        let totalQuantite = 0;
        let totalPrix = 0;
        let ligneVisible = false;

        // Recalculer les totaux en excluant la dernière ligne de totaux
        rows.forEach(row => {
            // Ignorer la ligne des totaux
            if (!row.classList.contains('fw-semibold')) {
                if (row.style.display !== 'none') {
                    ligneVisible = true; // Au moins une ligne visible

                    const quantite = parseFloat(row.querySelector('td:nth-child(6)').textContent) || 0;
                    const prix = parseFloat(
                        row.querySelector('td:nth-child(7)').textContent.replace(' €', '').replace(',', '.')
                    ) || 0;

                    totalQuantite += quantite;
                    totalPrix += prix;
                }
            }
        });

        // Mettre à jour les totaux ou afficher 0 si aucune ligne visible
        totalQuantiteCell.textContent = ligneVisible ? totalQuantite : '0';
        totalPrixCell.textContent = ligneVisible
            ? totalPrix.toLocaleString('fr-FR', { minimumFractionDigits: 2 }) + ' €'
            : '0,00 €';
    }

    if (searchInput) {
        searchInput.addEventListener('keyup', function () {
            const filter = this.value.toLowerCase();

            rows.forEach(row => {
                // Ignorer la ligne des totaux et les lignes "collapse"
                if (!row.classList.contains('fw-semibold') && !row.classList.contains('collapse')) {
                    const text = row.textContent.toLowerCase();
                    row.style.display = text.includes(filter) ? '' : 'none';
                }
            });

            recalculerTotaux(); // Recalculer les totaux après le filtrage
        });
    }

    recalculerTotaux(); // Calcul initial des totaux
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
