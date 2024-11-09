
<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../../index.php");
    exit();
}


// Initialiser les variables pour éviter les erreurs
$alertClass = ''; // Valeur par défaut pour éviter l'erreur "Undefined variable"
$message = '';    // Valeur par défaut pour éviter l'erreur "Undefined variable"
// Obtenez les paramètres de tri depuis l'URL
$sort = $_GET['sort'] ?? 'code'; // Colonne par défaut pour le tri
$order = $_GET['order'] ?? 'asc'; // Ordre par défaut pour le tri



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

    <title>Coupe Interne | Liste Des Tissus</title>

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
              <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Ressources /</span> Tissu</h4>

              <div class="row">
                <div class="col-md-12">

<div class="card mb-4">
    <div class="card-body">
   
        <div class="table-responsive text-nowrap">
                <table id="myTable" class="table table-hover">



                    <thead class="text-dark fs-4">
                        <tr>
            <th class="border-bottom-0">
                <a href="?sort=code&order=<?php echo $order === 'asc' ? 'desc' : 'asc'; ?>">
                    <h6 class="fw-semibold mb-0">Code <?php echo getSortArrow('code', $sort, $order); ?></h6>
                </a>
            </th>
            <th class="border-bottom-0">
                <a href="?sort=fournisseur&order=<?php echo $order === 'asc' ? 'desc' : 'asc'; ?>">
                    <h6 class="fw-semibold mb-0">Fournisseur <?php echo getSortArrow('fournisseur', $sort, $order); ?></h6>
                </a>
            </th>
            <th class="border-bottom-0">
                <a href="?sort=typologie&order=<?php echo $order === 'asc' ? 'desc' : 'asc'; ?>">
                    <h6 class="fw-semibold mb-0">Typologie <?php echo getSortArrow('typologie', $sort, $order); ?></h6>
                </a>
            </th>
            <th class="border-bottom-0">
                <a href="?sort=composition&order=<?php echo $order === 'asc' ? 'desc' : 'asc'; ?>">
                    <h6 class="fw-semibold mb-0">Composition <?php echo getSortArrow('composition', $sort, $order); ?></h6>
                </a>
            </th>
            <th class="border-bottom-0">
                <a href="?sort=base&order=<?php echo $order === 'asc' ? 'desc' : 'asc'; ?>">
                    <h6 class="fw-semibold mb-0">Base <?php echo getSortArrow('base', $sort, $order); ?></h6>
                </a>
            </th>
            <th class="border-bottom-0">
                <a href="?sort=largeur&order=<?php echo $order === 'asc' ? 'desc' : 'asc'; ?>">
                    <h6 class="fw-semibold mb-0">Largeur <?php echo getSortArrow('largeur', $sort, $order); ?></h6>
                </a>
            </th>
            <th class="border-bottom-0">
                <a href="?sort=poids&order=<?php echo $order === 'asc' ? 'desc' : 'asc'; ?>">
                    <h6 class="fw-semibold mb-0">Poids g/m² <?php echo getSortArrow('poids', $sort, $order); ?></h6>
                </a>
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



// Évitez les injections SQL en validant les colonnes
$validSortColumns = ['code', 'fournisseur', 'typologie', 'composition', 'base', 'largeur', 'poids'];
if (!in_array($sort, $validSortColumns)) {
    $sort = 'code';
}

// Évitez les injections SQL en validant l'ordre
$order = $order === 'desc' ? 'DESC' : 'ASC';

// Définir les champs à sélectionner et la table
$selectFields = '*';
$fromTables = 'tissu';
$whereConditions = '1=1'; // Ajoutez des conditions de filtrage si nécessaire
$orderBy = "$sort $order"; // Définir l'ordre de tri

// Appel de la fonction select pour obtenir les résultats
$result = select($conn, $selectFields, $fromTables, $whereConditions, $orderBy);

// Fonction pour afficher la flèche
function getSortArrow($column, $sort, $order) {
    if ($column === $sort) {
        return $order === 'asc' ? '↑' : '↓'; // Flèche vers le haut ou le bas
    }
    return ''; // Pas de flèche si ce n'est pas la colonne triée
}
// Vérifiez si la requête a réussi
if ($result) { 
    // Utilisation de fetch() pour récupérer les résultats avec PDO
    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        echo "<tr>";
        echo "<td class='border-bottom-0'>" . htmlspecialchars($row['code']) . "</td>";
        echo "<td class='border-bottom-0'>" . htmlspecialchars($row['fournisseur']) . "</td>";

        $typologie = htmlspecialchars($row['typologie']);
        $badgeClass = 'bg-label-info'; // Classe par défaut

        // Définir la classe de badge en fonction de la typologie
        switch ($typologie) {
            case 'Jersey':
                $badgeClass = 'bg-label-primary';
                break;
            case 'Piquet':
                $badgeClass = 'bg-label-success';
                break;
            case 'Costa':
                $badgeClass = 'bg-label-warning';
                break;
            case 'Felpa':
                $badgeClass = 'bg-label-danger';
                break;
        }
        echo "<td class='border-bottom-0'><span class='badge $badgeClass me-1'>$typologie</span></td>";

        echo "<td class='border-bottom-0'>" . htmlspecialchars($row['composition']) . "</td>";
        echo "<td class='border-bottom-0'>" . htmlspecialchars($row['base']) . "</td>";
        echo "<td class='border-bottom-0'>" . htmlspecialchars($row['largeur']) . "</td>";
        echo "<td class='border-bottom-0'>" . htmlspecialchars($row['poids']) . "</td>";
if ($_SESSION['role'] == 'admin'){
        echo "<td class='border-bottom-0'>";
        echo "<a href='modifier-tissu.php?id=" . htmlspecialchars($row['id']) . "'><i class='bx bxs-pencil'></i></a>"; // Icône d'édition
        echo "  ";
        echo "<a href='supprimer-tissu.php?id=" . htmlspecialchars($row['id']) . "'><i class='bx bxs-trash'></i></a>"; // Icône de suppression
        echo "</td>";
}
        echo "</tr>";
    }
}
?>

					
					</tbody></table></div>
					<?php if ($_SESSION['role'] == 'admin'): ?>
            
			 
					<div class="mt-2 text-end">
                          <button type="submit" name "Suivant" class="btn btn-primary me-2" onclick="window.location.href='ajouter-tissu.php';">Ajouter</button>
                          
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
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Vérifie si le champ de recherche existe sur la page
        const searchInput = document.getElementById('tableSearch');
        if (searchInput) {
            searchInput.addEventListener('keyup', function() {
                const filter = this.value.toLowerCase();
                const rows = document.querySelectorAll('#myTable tbody tr'); // Sélectionne les lignes du tableau

                rows.forEach(row => {
                    const text = row.textContent.toLowerCase();
                    row.style.display = text.includes(filter) ? '' : 'none'; // Affiche ou masque la ligne
                });
            });
        }
    });
</script>

    <!-- Page JS -->
    <script src="../assets/js/pages-account-settings-account.js"></script>

    <!-- Place this tag in your head or just before your close body tag. -->
    <script async defer src="https://buttons.github.io/buttons.js"></script>
  </body>
</html>
