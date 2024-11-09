        <?php
		session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../../index.php");
    exit();
	
}
include("../php/db.php");
                                        include("../php/fonction.php");
// Initialiser les variables pour éviter les erreurs
$alertClass = ''; // Classe par défaut pour l'alerte
$message = '';    // Message par défaut vide

                                        

                                        $query_hs = "
    SELECT 
        DATE(datetime_debut) AS date, 
        operateur1 AS operateur,
		phase,
        SUM(CAST(HS AS DECIMAL(10, 2))) AS total_HS,
        SUM(CASE WHEN phase = 'relaxation' THEN CAST(HS AS DECIMAL(10, 2)) ELSE 0 END) AS total_relaxation,
        SUM(CASE WHEN phase = 'matelassage' THEN CAST(HS AS DECIMAL(10, 2)) ELSE 0 END) AS total_matelassage,
        SUM(CASE WHEN phase = 'coupe' THEN CAST(HS AS DECIMAL(10, 2)) ELSE 0 END) AS total_coupe,
        SUM(CASE WHEN phase = 'etiquetage' THEN CAST(HS AS DECIMAL(10, 2)) ELSE 0 END) AS total_etiquetage
    FROM 
        planification
    WHERE 
        DATE(datetime_debut) = CURDATE()
    GROUP BY 
        DATE(datetime_debut), 
        operateur1
    
    UNION ALL

    SELECT 
        DATE(datetime_debut) AS date, 
        operateur2 AS operateur,
		phase,
        SUM(CAST(HS AS DECIMAL(10, 2))) AS total_HS,
        SUM(CASE WHEN phase = 'relaxation' THEN CAST(HS AS DECIMAL(10, 2)) ELSE 0 END) AS total_relaxation,
        SUM(CASE WHEN phase = 'matelassage' THEN CAST(HS AS DECIMAL(10, 2)) ELSE 0 END) AS total_matelassage,
        SUM(CASE WHEN phase = 'coupe' THEN CAST(HS AS DECIMAL(10, 2)) ELSE 0 END) AS total_coupe,
        SUM(CASE WHEN phase = 'etiquetage' THEN CAST(HS AS DECIMAL(10, 2)) ELSE 0 END) AS total_etiquetage
    FROM 
        planification
    WHERE 
        operateur2 <> '0' AND DATE(datetime_debut) = CURDATE()
    GROUP BY 
        DATE(datetime_debut), 
        operateur2
    ORDER BY 
        date ASC, 
        operateur ASC;
";

$stmt_hs = $conn->prepare($query_hs);
$stmt_hs->execute();

                                        // Récupérer les résultats des heures supplémentaires
                                        $results_hs = $stmt_hs->fetchAll(PDO::FETCH_ASSOC);

              $totalHS = 0;
$totalRelaxation = 0;
$totalMatelassage = 0;
$totalCoupe = 0;
$totalEtiquetage = 0;

if ($results_hs && count($results_hs) > 0) {
    foreach ($results_hs as $row) {
        // Ajouter à chaque phase et total global
        $totalHS += (float) $row['total_HS']; // Total des heures supplémentaires
        $totalRelaxation += (float) $row['total_relaxation']; // Total relaxation
        $totalMatelassage += (float) $row['total_matelassage']; // Total matelassage
        $totalCoupe += (float) $row['total_coupe']; // Total coupe
        $totalEtiquetage += (float) $row['total_etiquetage']; // Total étiquetage
    }
}                    
     

                                        $query = "
    SELECT 
        commande,
		article,
		quantiteDemandee
    FROM 
        db
    WHERE 
        etat = 'En attente' or etat = 'En cours'
    
    
    
";

$stmt = $conn->prepare($query);
$stmt->execute();

                                        // Récupérer les résultats des heures supplémentaires
                                        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
										
										                                        $queryOperateur = "
    SELECT 
        matricule,
		nom,
		prenom,
		fonction
    FROM 
        effectif
    WHERE 
        nature = 'direct'
    
    
    
";

$stmtOperateur = $conn->prepare($queryOperateur);
$stmtOperateur->execute();

                                        // Récupérer les résultats des heures supplémentaires
                                        $resultsOperateur = $stmtOperateur->fetchAll(PDO::FETCH_ASSOC);
	 
										                                        $queryTable = "
    SELECT 
        ref,
		id,
		longueur,
		largeur
    FROM 
        materiel
   
    
    
    
";

$stmtTable = $conn->prepare($queryTable);
$stmtTable->execute();

                                        // Récupérer les résultats des heures supplémentaires
                                        $resultsTable = $stmtTable->fetchAll(PDO::FETCH_ASSOC);
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

    <title>Coupe Interne | Plannification</title>

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
<script src="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.14.0/Sortable.min.js"></script>

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
        /* Ajoute un style pour les lignes survolées */
        tr.dragging {
            background-color: #f0f8ff; /* Couleur de fond lors du drag */
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
		  <div class="flex-grow-1 container-p-y container-fluid">
            <!-- Content -->
<div class="row g-6 mb-6">
  <div class="col-sm-6 col-xl-3">
    <div class="card">
      <div class="card-body">
        <div class="d-flex align-items-start justify-content-between">
          <div class="content-left">
            <span class="text-heading">Relaxation</span>
            <div class="d-flex align-items-center my-1">
              <h4 class="mb-0 me-2"><?php   echo 	$totalRelaxation	;	  ?> Heures</h4>
              
            </div>
            
          </div>
                    <div class="avatar">
  
    <img src="../assets/img/icons/unicons/relaxing-fabric.png" alt="Relaxing Fabric" style="width: 100%; height: auto;" />
  
</div>
        </div>
      </div>
    </div>
  </div>
  <div class="col-sm-6 col-xl-3">
    <div class="card">
      <div class="card-body">
        <div class="d-flex align-items-start justify-content-between">
          <div class="content-left">
            <span class="text-heading">Matelassage</span>
            <div class="d-flex align-items-center my-1">
              <h4 class="mb-0 me-2"><?php   echo 	$totalMatelassage	;	  ?> Heures</h4>
              
            </div>
            
          </div>
          <div class="avatar">
  
    <img src="../assets/img/icons/unicons/matelassage.png" alt="matelassage Fabric" style="width: 100%; height: auto;" />
  
</div>

        </div>
      </div>
    </div>
  </div>
  <div class="col-sm-6 col-xl-3">
    <div class="card">
      <div class="card-body">
        <div class="d-flex align-items-start justify-content-between">
          <div class="content-left">
            <span class="text-heading">Coupe</span>
            <div class="d-flex align-items-center my-1">
              <h4 class="mb-0 me-2"><?php   echo 	$totalCoupe	;	  ?> Heures</h4>
              
            </div>
            
          </div>
                    <div class="avatar">
  
    <img src="../assets/img/icons/unicons/coupe.png" alt="coupe Fabric" style="width: 100%; height: auto;" />
  
</div>
        </div>
      </div>
    </div>
  </div>
  <div class="col-sm-6 col-xl-3">
    <div class="card">
      <div class="card-body">
        <div class="d-flex align-items-start justify-content-between">
          <div class="content-left">
            <span class="text-heading">Etiquetage</span>
            <div class="d-flex align-items-center my-1">
              <h4 class="mb-0 me-2"><?php   echo 	$totalEtiquetage	;	  ?> Heures</h4>
             
            </div>
           
          </div>
                              <div class="avatar">
  
    <img src="../assets/img/icons/unicons/etiquetage.png" alt="etiquetage Fabric" style="width: 100%; height: auto;" />
  
</div>
        </div>
      </div>
    </div>
  </div>
</div>
            
              

              <div class="row">

			  
                <div class="col">
                 
				  
<div class="card ">
<form id="updateForm" >
<div class="card-header d-flex justify-content-between align-items-center">
    <h5 class="mb-0">Plannification De Travail</h5>
    
        <div class=" btn-group flex-wrap">
            
                <div class="btn-group">
        <button class="btn buttons-collection dropdown-toggle btn-label-primary" tabindex="0" aria-controls="DataTables_Table_0" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
            <span><i class="bx bx-export bx-18px me-2"></i> 
            <span class="d-none d-sm-inline-block">Export</span></span>
        </button>
        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
<li>
    <a class="dropdown-item d-flex align-items-center" href="#">
        <img src="../assets/img/icons/unicons/gantt.png" alt="gantt" style="width: 24px; height: 24px; margin-right: 8px;" />
        Diagramme de Gantt
    </a>
</li>

            <li><a class="dropdown-item" href="wip-operateur.php">
			<img src="../assets/img/icons/unicons/wip-operateur.png" alt="wip-operateur" style="width: 24px; height: 24px; margin-right: 2px;" />
			Wip Opérateur</a></li>
            
        </ul>
    </div>
           
<a href="#" class="btn btn-label-secondary" id="updateButton" data-bs-toggle="modal" data-bs-target="#updateModal">
    <span class="bx bx-revision" style="font-size: 18px; margin-right: 8px;"></span>Mise à Jour
</a>


        </div>
    
</div>
</form>




<div class="modal-onboarding modal fade " id="updateModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">

  <div class="modal-dialog modal-lg" role="document">
 
    <div class="modal-content text-center">
	 <div class="modal-header">
        
        <button type="button" class="btn-close"  id="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      


      <!-- Carousel Section -->
      <div id="modalCarouselControls" class="carousel slide pb-6 mb-2" data-bs-interval="false">
        <!-- Indicators -->
        <div class="carousel-indicators">
          <button type="button" data-bs-target="#modalCarouselControls" data-bs-slide-to="0" class="active"></button>
          <button type="button" data-bs-target="#modalCarouselControls" data-bs-slide-to="1"></button>
          <button type="button" data-bs-target="#modalCarouselControls" data-bs-slide-to="2"></button>
        </div>

        <!-- Carousel inner (slides) -->
        <div class="carousel-inner">
          
           <!-- Slide 1 avec tableau -->
<div class="carousel-item active first-slide-wide" >
    <div class="onboarding-content">
        <div class="mx-3">
            <!-- Ici commence le tableau avec les données de la base -->
            <h4 class="onboarding-title text-body">Priorité des Commandes</h4>
<form method="POST" action="ton_action_php.php"> <!-- Formulaire pour soumettre les choix -->
    <table class="table table-responsive" id="sortableTable">
        <thead>
            <tr>
                <th></th> <!-- Colonne pour les checkboxes -->
                <th>Article</th>
                <th>Commande</th>
                <th>Quantité</th>
            </tr>
        </thead>
        <tbody>
            <?php
            

            // Affichage des données dans le tableau
            if (!empty($results)) {
                foreach ($results as $row) {
                    echo "<tr draggable='true' ondragstart='drag(event)' ondragover='allowDrop(event)' ondrop='drop(event)'>";
                    echo "<td class='dt-checkboxes-cell'><input type='checkbox' name='selected_commande[]' class='dt-checkboxes form-check-input' value='" . htmlspecialchars($row['commande']) . "' checked></td>"; // Checkbox pour chaque ligne
                    echo "<td>" . htmlspecialchars($row['article']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['commande']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['quantiteDemandee']) . "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='4'>Aucune donnée trouvée</td></tr>";
            }
            ?>
        </tbody>
    </table>
    
</form>



            <!-- Fin du tableau -->
        </div>
    </div>
</div>


          <!-- Slide 2 -->
          <div class="carousel-item">
            
            <div class="onboarding-content">
              <h4 class="onboarding-title text-body">Disponibilité des Opérateurs</h4>
              <form>
                
                <table class="table table-responsive" id="operatorTable" >
                  <thead>
                    <tr>
					  <th></th>
                      <th>Matricule</th>
                      <th>Nom</th>
                      <th>Prenom</th>
                      <th>Fonction</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    // Affichage des données dans le tableau
                    if (!empty($resultsOperateur)) {
                        foreach ($resultsOperateur as $row) {
                            echo "<tr>";
							echo "<td class='dt-checkboxes-cell'><input type='checkbox' name='selected_effectif[]' class='dt-checkboxes form-check-input' value='" . htmlspecialchars($row['matricule']) . "' checked></td>";
                            echo "<td>" . htmlspecialchars($row['matricule']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['nom']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['prenom']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['fonction']) . "</td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='3'>Aucune donnée trouvée</td></tr>";
                    }
                    ?>
                  </tbody>
                </table>
              </form>
            </div>
          </div>

          <!-- Slide 3 -->
          <div class="carousel-item">
            
            <div class="onboarding-content">
              <h4 class="onboarding-title text-body">Disponibilité des Tables</h4>
              <form>
                <table class="table table-responsive" id="Table" >
                  <thead>
                    <tr>
					  <th></th>
                      <th>Table</th>
                      <th>Longueur</th>
                      <th>Largeur</th>
                      
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    // Affichage des données dans le tableau
                    if (!empty($resultsTable)) {
                        foreach ($resultsTable as $row) {
                            echo "<tr>";
							echo "<td class='dt-checkboxes-cell'><input type='checkbox' name='selected_effectif[]' class='dt-checkboxes form-check-input' value='" . htmlspecialchars($row['id']) . "' checked></td>";
                            echo "<td>" . htmlspecialchars($row['id']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['longueur']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['largeur']) . "</td>";
                            
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='3'>Aucune donnée trouvée</td></tr>";
                    }
                    ?>
                  </tbody>
                </table>
              </form>
            </div>
          </div>
        </div>

        <!-- Carousel controls -->
         <a class="carousel-control-prev" href="#modalCarouselControls" role="button" data-bs-slide="prev">
        <i class="bx bx-chevrons-left lh-1"></i><span>Précédent</span>
    </a>
        <a class="carousel-control-next" href="#modalCarouselControls" role="button" id="nextButton">
        <span>Suivant</span><i class="bx bx-chevrons-right lh-1"></i>
    </a>
		<!-- Bouton pour enregistrer -->

<a class="carousel-control-next" href="#modalCarouselControls" role="button" id="saveButton">
        <span>Enregistrer</span>
    </a>
      </div>
    </div>
  </div>
</div>







<div class="card-body">
 <?php
                                       

                                        if ($results_hs && count($results_hs) > 0) {
                                            // Affichage du tableau
                                            echo '<div class="table-responsive text-nowrap">
                                            <table id="myTable" class="table table-hover">
                                                <thead class="text-dark fs-4">
                                                    <tr>
                                                        <th class="border-bottom-0">Date </th>
                                                        <th class="border-bottom-0">Phase </th>
                                                        <th class="border-bottom-0">Opérateur 1 </th>
                                                        <th class="border-bottom-0">HS </th>
                                                    </tr>
                                                </thead>
                                                <tbody id="tableBody">';

                                            foreach ($results_hs as $row) {
                                                echo "<tr>";

                                                echo "<td class='border-bottom-0'>" . htmlspecialchars($row['date']) . "</td>";
												echo "<td class='border-bottom-0'><span class='badge " . 
                                                    (($row['phase'] == 'matelassage') ? 'bg-label-primary' :
                                                    (($row['phase'] == 'coupe') ? 'bg-label-success' :
                                                    (($row['phase'] == 'etiquetage') ? 'bg-label-dark' :
                                                    'bg-label-warning'))) . " me-1'>" . 
                                                    htmlspecialchars($row['phase']) . "</span></td>";
                                                echo "<td class='border-bottom-0'>" . strtoupper(htmlspecialchars($row['operateur'])) . "</td>";
                                                echo "<td class='border-bottom-0'>" . strtoupper(htmlspecialchars($row['total_HS'])) . "</td>";
                                                
                                                

                                                echo "</tr>";
                                            }

                                            echo '</tbody></table></div>';
                                        } else {
                                            echo '<div class="alert alert-warning" role="alert">Aucune donnée trouvée pour aujourd\'hui.</div>';
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

 <div id="responseMessage" style="margin-top: 20px;"></div>
<script>
    let draggedRow = null;
    const selectedOrders = []; // Tableau pour stocker les commandes sélectionnées
    const selectedOperators = []; // Tableau pour stocker les commandes sélectionnées
    const selectedTables = [];
    function drag(event) {
        draggedRow = event.target; // Sauvegarder la ligne actuellement déplacée
        event.target.classList.add('dragging'); // Ajouter une classe pour le style
    }

    function allowDrop(event) {
        event.preventDefault(); // Empêcher le comportement par défaut
    }

    function drop(event) {
        event.preventDefault();
        if (event.target.tagName === "TD") {
            const droppedRow = event.target.parentNode; // La ligne où c'est déposé
            if (draggedRow !== droppedRow) {
                // Échanger les lignes
                const parentNode = draggedRow.parentNode;
                parentNode.insertBefore(draggedRow, droppedRow.nextSibling);
            }
        }
        draggedRow.classList.remove('dragging'); // Enlever la classe de glissement
        draggedRow = null; // Réinitialiser la variable
    }

    function getSelectedOrders() {
        // Réinitialiser le tableau
        selectedOrders.length = 0; 
        const rows = document.querySelectorAll('#sortableTable tbody tr');
        
        rows.forEach(row => {
            const checkbox = row.querySelector('input[type="checkbox"]');
            if (checkbox.checked) {
                const article = row.cells[1].innerText; // Article
                const commande = row.cells[2].innerText; // Commande
                selectedOrders.push({ article, commande });
            }
        });

        console.log(selectedOrders); // Afficher le tableau dans la console
        // Ici, vous pouvez faire une requête AJAX pour soumettre les données ou traiter comme désiré
    }
function getOperators() {
    // Réinitialiser le tableau
    selectedOperators.length = 0; 
    const rows = document.querySelectorAll('#operatorTable tbody tr');
    
    rows.forEach(row => {
        const checkboxoperateur = row.querySelector('input[type="checkbox"]');
        if (checkboxoperateur.checked) {
            const matricule = row.cells[1].innerText; // Récupérer le matricule
            const fonction = row.cells[4].innerText;
            // Correction de la syntaxe ici
            selectedOperators.push({ matricule: matricule,fonction }); // Utiliser la bonne syntaxe pour ajouter un objet
        }
    });

    console.log(selectedOperators); // Afficher le tableau dans la console
    // Ici, vous pouvez faire une requête AJAX pour soumettre les données ou traiter comme désiré
}
function getTables() {
    // Réinitialiser le tableau
    selectedTables.length = 0; 
    const rows = document.querySelectorAll('#Table tbody tr');
    
    rows.forEach(row => {
        const checkboxtable = row.querySelector('input[type="checkbox"]');
        if (checkboxtable.checked) {
            const id = row.cells[1].innerText; // Récupérer le matricule
            
            // Correction de la syntaxe ici
            selectedTables.push({ id: id }); // Utiliser la bonne syntaxe pour ajouter un objet
        }
    });

    console.log(selectedTables); // Afficher le tableau dans la console
    // Ici, vous pouvez faire une requête AJAX pour soumettre les données ou traiter comme désiré
}

     let currentStep = 0; // Commence à la première diapositive
    const steps = document.querySelectorAll(".carousel-item");
    const nextButton = document.getElementById("nextButton");
    const saveButton = document.getElementById("saveButton");
    const indicators = document.querySelectorAll(".carousel-indicators button");
    // Fonction pour afficher la diapositive actuelle
    function showStep(stepIndex) {
        steps.forEach((step, index) => {
            step.classList.toggle('active', index === stepIndex);
        });

        // Mettre à jour les indicateurs
        indicators.forEach((indicator, index) => {
            indicator.classList.toggle('active', index === stepIndex);
        });

        // Gérer la visibilité des boutons
        if (stepIndex === 0) {
            nextButton.style.display = "inline-block"; // Affiche le bouton "Suivant"
            saveButton.style.display = "none"; // Cache le bouton "Enregistrer"
        } else if (stepIndex === 1) {
            nextButton.style.display = "inline-block"; // Affiche le bouton "Suivant"
            saveButton.style.display = "none"; // Cache le bouton "Enregistrer"
        } else if (stepIndex === 2) {
            nextButton.style.display = "none"; // Cache le bouton "Suivant"
            saveButton.style.display = "inline-block"; // Affiche le bouton "Enregistrer"
        }
    }

    // Gérer le clic sur le bouton "Suivant"
    nextButton.addEventListener("click", () => {
    if (currentStep === 0) {
        // Logic pour enregistrer des données ici si besoin
        getSelectedOrders(); // Appeler la fonction pour obtenir les commandes sélectionnées
        currentStep++; // Passer à l'étape suivante
        showStep(currentStep);
    } else if (currentStep === 1) {
        getOperators();
		currentStep++; // Passer à l'étape suivante
        showStep(currentStep);
    }else if (currentStep < steps.length - 1) {
        currentStep++; // Passer à l'étape suivante
        showStep(currentStep); // Afficher l'étape suivante
    }
});


    // Gérer le clic sur le bouton "Précédent"
document.querySelector(".carousel-control-prev").addEventListener("click", () => {
    if (currentStep > 0) {
        currentStep--;
        showStep(currentStep); // Affiche la diapositive précédente
    }
});


    // Afficher la première étape au démarrage
    showStep(currentStep);

    // Gérer l'événement de clic sur le bouton de sauvegarde
    saveButton.addEventListener('click', function(e) {
    e.preventDefault();  // Empêcher l'envoi de formulaire par défaut

    // Appeler les fonctions pour récupérer les données
    getSelectedOrders();
    getOperators();
    getTables();

    // Votre requête AJAX ou toute autre logique de sauvegarde ici
    $.ajax({
        url: 'plannification.php',  // URL du fichier de traitement
        type: 'POST',               // Type de requête (POST)
        data: { 
            action: 'update', 
            selectedOrders: selectedOrders,    // Inclure les données des commandes sélectionnées
            selectedOperators: selectedOperators,  // Inclure les données des opérateurs sélectionnés
            selectedTables: selectedTables    // Inclure les données des tables sélectionnées
        },
        success: function(response) {
            // Afficher le message de succès
            $('#responseMessage').html('<div class="alert alert-success fixed-top-right" role="alert" style="display: none;">' + response.message + '</div>');

            // Script pour afficher et masquer l'alerte avec des délais
            setTimeout(function() { 
                $('#responseMessage .alert').fadeIn(); // Afficher l'alerte
            }, 200); // Afficher après 200 ms

            setTimeout(function() { 
                $('#responseMessage .alert').fadeOut(); // Masquer l'alerte après 20 secondes
            }, 20000); 
        },
        error: function() {
            // Gérer les erreurs
            $('#responseMessage').html('<div class="alert alert-danger fixed-top-right" role="alert" style="display: none;">Erreur lors de la mise à jour.</div>');
            
            // Script pour afficher et masquer l'alerte avec des délais
            setTimeout(function() { 
                $('#responseMessage .alert').fadeIn(); // Afficher l'alerte
            }, 200); // Afficher après 200 ms

            setTimeout(function() { 
                $('#responseMessage .alert').fadeOut(); // Masquer l'alerte après 20 secondes
            }, 20000);
        }
    });
});




  
</script>




    <!-- Page JS -->
    <script src="../assets/js/pages-account-settings-account.js"></script>

    <!-- Place this tag in your head or just before your close body tag. -->
    <script async defer src="https://buttons.github.io/buttons.js"></script>
  </body>
</html>
