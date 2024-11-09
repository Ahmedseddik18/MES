 <?php
		session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../../index.php");
    exit();
}
include("../php/db.php");
include("../php/fonction.php");

// Définir les colonnes spécifiques à sélectionner
$selectFields2 = "COUNT(DISTINCT atelier) AS clients, 
                   COUNT(commande) AS trace, 
                   SUM(prixtotal) AS facture, 
                   SUM(metrage) AS metrage";

$fromTables2 = "trace";

$whereConditions2 = "EXTRACT(MONTH FROM date) = EXTRACT(MONTH FROM CURRENT_DATE) 
                     AND etat = 'Termine'"; 

$sql = "SELECT $selectFields2 
        FROM $fromTables2 
        WHERE $whereConditions2";

 // Regrouper par les colonnes non agrégées

// Exécuter la requête
$result = $conn->query($sql);

// Vérifier si la requête a renvoyé des résultats
if ($result) {
    $row = $result->fetch(PDO::FETCH_ASSOC);
} else {
    // Gestion des erreurs
    echo "Erreur lors de l'exécution de la requête : " . $conn->errorInfo()[2];
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

    <title>Coupe Interne | Liste Traçé</title>

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
div.dt-button-info {
    position: fixed;
    top: 50%;
    left: 50%;
    width: 400px;
    margin-top: -100px;
    margin-left: -200px;
    background-color: white;
    border-radius: 0.75em;
    box-shadow: 1px 2px 5px rgba(0, 0, 0, 0.3); /* Ombre réduite */
    text-align: center;
    z-index: 2003;
    overflow: hidden;
    opacity: 0;
    transition: opacity 0.5s ease-in-out;
}

div.dt-button-info.show {
    opacity: 1;
}

div.dt-button-info h2 {
    padding: 2rem 2rem 1rem 2rem;
    margin: 0;
    font-weight: normal;
}

div.dt-button-info > div {
    padding: 1em 2em 2em 2em;
}

div.dtb-popover-close {
    position: absolute;
    top: 6px;
    right: 6px;
    width: 22px;
    height: 22px;
    text-align: center;
    border-radius: 3px;
    cursor: pointer;
    z-index: 2003;
}

button.dtb-hide-drop {
    display: none !important;
}
.no-select {
    user-select: none; /* Désactive la sélection de texte */
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
              

              <div class="row">
                <div class="col">
                 <div class="card mb-6">
  <div class="card-widget-separator-wrapper">
    <div class="card-body card-widget-separator">
      <div class="row gy-4 gy-sm-1">
        <div class="col-sm-6 col-lg-3">
          <div class="d-flex justify-content-between align-items-center card-widget-1 border-end pb-4 pb-sm-0">
            <div>
              <h4 class="mb-0"><?php echo $row['clients']; ?></h4>
              <p class="mb-0">Clients</p>
            </div>
            <div class="avatar me-sm-6">
              <span class="avatar-initial rounded bg-label-secondary text-heading">
                <i class="bx bx-user bx-26px"></i>
              </span>
            </div>
          </div>
          <hr class="d-none d-sm-block d-lg-none me-6">
        </div>
        <div class="col-sm-6 col-lg-3">
          <div class="d-flex justify-content-between align-items-center card-widget-2 border-end pb-4 pb-sm-0">
            <div>
              <h4 class="mb-0"><?php echo $row['trace']; ?></h4>
              <p class="mb-0">Traçés</p>
            </div>
            <div class="avatar me-lg-6">
              <span class="avatar-initial rounded bg-label-secondary text-heading">
                <i class="bx bx-file bx-26px"></i>
              </span>
            </div>
          </div>
          <hr class="d-none d-sm-block d-lg-none">
        </div>
        <div class="col-sm-6 col-lg-3">
          <div class="d-flex justify-content-between align-items-center border-end pb-4 pb-sm-0 card-widget-3">
            <div>
              <h4 class="mb-0"><?php echo $row['facture']; ?> €</h4>
              <p class="mb-0">Facture</p>
            </div>
            <div class="avatar me-sm-6">
              <span class="avatar-initial rounded bg-label-secondary text-heading">
                <i class="bx bx-check-double bx-26px"></i>
              </span>
            </div>
          </div>
        </div>
        <div class="col-sm-6 col-lg-3">
          <div class="d-flex justify-content-between align-items-center">
            <div>
              <h4 class="mb-0"><?php echo $row['metrage']; ?></h4>
              <p class="mb-0">Métrage</p>
            </div>
            <div class="avatar">
              <span class="avatar-initial rounded bg-label-secondary text-heading">
                <i class="bx bx-error-circle bx-26px"></i>
              </span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
				  
<div class="card ">
<div class="card-header d-flex justify-content-between align-items-center">
    <h5 class="mb-0">Pilotage Des Traçés</h5>

	<div class="btn-group">
  <button type="button" class="btn btn-label-primary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
    <i class="bx bx-export bx-m me-sm-2"></i>Exporter
  </button>
  <ul class="dropdown-menu">
    <li><a class="dropdown-item" onclick="exportToPrint()"> <i class="bx bx-printer me-1"></i>Print</a></li>
    <li><a class="dropdown-item" onclick="exportToCSV()"> <i class="bx bx-file me-1"></i>Csv</a></li>
    <li><a class="dropdown-item" onclick="exportToExcel()"> <i class="bx bxs-file-export me-1"></i>Excel</a></li>
    <li><a class="dropdown-item" onclick="exportToPDF()"> <i class="bx bxs-file-pdf me-1"></i>Pdf</a></li>
    <li><a class="dropdown-item" onclick="copyContent()"> <i class="bx bx-copy me-1"></i>Copier</a></li>
	<div id="popup" class="popup"></div>
  </ul>
</div>
<div id="customAlert" class="dt-button-info hidden">
    
    <h2>Succès !</h2>
    <div id="copyMessage">Copied X rows to clipboard</div>
</div>


</div>

<div class="card-body">
<?php
// Définir les colonnes spécifiques à sélectionner
$selectFields = "*";
$fromTables = "trace";
$whereConditions = "EXTRACT(MONTH FROM date) = EXTRACT(MONTH FROM CURRENT_DATE)  and etat = 'Termine' "; // Condition de base toujours vraie
$orderBy = 'date ASC'; // Assurez-vous de définir $orderBy si nécessaire

// Créer la requête avec GROUP BY
$sql2 = "SELECT $selectFields 
        FROM $fromTables 
        WHERE $whereConditions 
         
        ORDER BY $orderBy";

// Insertion des données en utilisant la fonction existante
$result2 = $conn->query($sql2);




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
                        <h6 class="fw-semibold mb-0">Métrage</h6>
                    </th>
					<th class="border-bottom-0">
                        <h6 class="fw-semibold mb-0">Prix Total</h6>
                    </th>
                </tr>
            </thead>
            <tbody>';
    $index = 1; // Pour générer des IDs uniques
    while ($row2 = $result2->fetch(PDO::FETCH_ASSOC)) {
        // Ajout des valeurs aux sommes
        $totalQuantite += (float)$row2['metrage'];
        $totalPrix += (float)$row2['PrixTotale'];

        // Identifiant unique pour chaque ligne
        $collapseId = "collapseRow" . $index;

        echo "<tr data-bs-toggle='collapse' data-bs-target='#$collapseId' class='clickable-row'>";
        echo "<td class='border-bottom-0'>" . htmlspecialchars($row2['date']) . "</td>";
        echo "<td class='border-bottom-0'>" . htmlspecialchars($row2['commande']) . "</td>";
        echo "<td class='border-bottom-0'>" . strtoupper(htmlspecialchars($row2['article'])) . "</td>";
        echo "<td class='border-bottom-0'>" . strtoupper(htmlspecialchars($row2['atelier'])) . "</td>";

        if (htmlspecialchars($row2['phase']) == 'Production') {
            echo "<td class='border-bottom-0'><span class='badge bg-label-primary me-1'>" . htmlspecialchars($row2['phase']) . "</span></td>";
        } elseif (htmlspecialchars($row2['phase']) == 'Campionario') {
            echo "<td class='border-bottom-0'><span class='badge bg-label-success me-1'>" . htmlspecialchars($row2['phase']) . "</span></td>";
        } else {
            echo "<td class='border-bottom-0'><span class='badge bg-label-warning me-1'>" . htmlspecialchars($row2['phase']) . "</span></td>";
        }

        
echo "<td class='border-bottom-0'>" . strtoupper(htmlspecialchars($row2['metrage'])) . "</td>";
echo "<td class='border-bottom-0'>" . strtoupper(htmlspecialchars($row2['prixtotal'])) . "&nbsp;€</td>";







        
        
        echo "</tr>";

        $index++;
    }





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
// Exporter en CSV
function exportToCSV() {
  let table = document.getElementById('wipTable');
  let rows = Array.from(table.rows);
  let csvContent = rows.map(row => 
    Array.from(row.cells).map(cell => cell.textContent).join(',')
  ).join('\n');

  let blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
  let link = document.createElement('a');
  link.href = URL.createObjectURL(blob);
  link.download = 'export.csv';
  link.click();
}

// Exporter en Excel
function exportToExcel() {
  let tableHTML = document.getElementById('wipTable').outerHTML;
  let blob = new Blob([tableHTML], { type: 'application/vnd.ms-excel' });
  let link = document.createElement('a');
  link.href = URL.createObjectURL(blob);
  link.download = 'export.xls';
  link.click();
}

// Exporter en PDF
function exportToPDF() {
  window.print(); // Simple pour impression/pdf
}

// Imprimer le contenu
function exportToPrint() {
  let content = document.getElementById('wipTable').outerHTML;
  let newWindow = window.open('', '', 'width=800,height=600');
  newWindow.document.write('<html><head><title>Impression</title></head><body>');
  newWindow.document.write(content);
  newWindow.document.write('</body></html>');
  newWindow.document.close();
  newWindow.print();
}

function copyContent() {
    let table = document.getElementById('wipTable');
    let rows = table.querySelectorAll('tbody tr'); // Sélectionne toutes les lignes dans le corps du tableau
    let rowCount = 0;

    // Compte le nombre de lignes non vides (sauf l'en-tête)
    rows.forEach(row => {
        if (row.style.display !== 'none') { // Ignore les lignes cachées si applicable
            rowCount++;
        }
    });

    // Ajoute la classe pour désactiver la sélection
    document.body.classList.add('no-select');

    // Copie le contenu
    let range = document.createRange();
    range.selectNode(table);
    window.getSelection().removeAllRanges();
    window.getSelection().addRange(range);
    document.execCommand('copy');

    // Retire la classe pour réactiver la sélection
    document.body.classList.remove('no-select');

    // Affiche le nombre de lignes copiées dans l'alerte
    let alertBox = document.getElementById('customAlert');
    let copyMessage = document.getElementById('copyMessage');
    copyMessage.textContent = `Copied ${rowCount} rows to clipboard`; // Mise à jour du message
    alertBox.classList.add('show');
    alertBox.classList.remove('hidden');

    // Masque l'alerte après 2 secondes si l’utilisateur ne la ferme pas manuellement
    setTimeout(() => hideAlert(), 2000);
}

function hideAlert() {
    let alertBox = document.getElementById('customAlert');
    alertBox.classList.remove('show');
    setTimeout(() => alertBox.classList.add('hidden'), 500); // Attendre que la transition se termine
}





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
</html