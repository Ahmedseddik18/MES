
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

    <title>Coupe Interne | Disponibilité Effectif</title>

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
              <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Production /</span> Disponibilité Effectif</h4>

              <div class="row">
                <div class="col-md-12">

<div class="card mb-4">
    <div class="card-body">
				<?php if ($_SESSION['role'] == 'admin' || $_SESSION['role'] == 'agent-bureautique' || $_SESSION['role'] == 'responsable-utm') : ?>


  <div class="row">
    <!-- Colonne Existante -->
    <div class="col-md-6 pe-md-2">
      <div class="d-flex align-items-start align-items-sm-center gap-4">
        <img
          src="../assets/img/avatars/excel.png"
          alt="excel avatar"
          class="d-block rounded"
          height="70"
          width="100"
          id="uploadedAvatar"
        />
        <form action="upload-presence.php" method="post" enctype="multipart/form-data" id="uploadForm">
          <div class="button-wrapper">
            <input
              type="file"
              name="file"
              id="upload"
              accept=".xlsx, .xls, .csv"
              style="display: none;"
            />
            <label for="upload" class="btn btn-primary me-2 mb-4" tabindex="0">
              <span class="d-none d-sm-block">Sélectionner</span>
              <i class="bx bx-upload d-block d-sm-none"></i>
            </label>

            <!-- Submit Button -->
            <button type="button" class="btn btn-secondary me-2 mb-4" id="submitButton">
              Importer
            </button>

            <!-- Cancel Button -->
            <p  id="fileMessage" class="text-muted mb-0"></p>
          </div>
        </form>
      </div>
    </div>

    <!-- Ligne Verticale -->
    <div class="col-md-1 px-0 border-start"></div>

    <!-- Nouvelle Colonne à Droite -->
<div class="col-md-5 ps-md-2">
    <form action="pointage.php" method="get"> <!-- Modification de l'action et de la méthode -->
        <label for="dates" class="form-label">Choisissez une Date :</label>
        <div class="d-flex align-items-center">
            <div class="form-group me-2 flex-grow-1">
                <?php
                include("../php/db.php");
                include("../php/fonction.php");

                // Définir les paramètres de la requête
                $selectFields = 'DISTINCT date';
                $fromTables = 'disponibilite';
                $whereConditions = '1=1'; // Ajoutez des conditions de filtrage si nécessaire
                $orderBy = 'date DESC'; // Triez les dates si nécessaire

                // Appel de la fonction select pour obtenir les résultats
                $result = select($conn, $selectFields, $fromTables, $whereConditions, $orderBy);

                if ($result) {
                    // Afficher le select
                    echo '<select name="dates" id="dates" class="form-select">';
                    
                    // Utilisation de fetch() pour récupérer les résultats avec PDO
                    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                        // Afficher chaque option du select
                        echo '<option value="' . htmlspecialchars($row['date']) . '">' . htmlspecialchars($row['date']) . '</option>';
                    }
                    
                    echo '</select>';
                } else {
                    echo 'Aucune date trouvée.';
                }
                ?>
            </div>
            <button type="submit" class="btn btn-primary">Sélectionner</button>
        </div>
    </form>
</div>

  </div>

<?php endif; ?>





<script>
    document.addEventListener('DOMContentLoaded', function() {
        const uploadInput = document.getElementById('upload');
        const submitButton = document.getElementById('submitButton');
        const uploadForm = document.getElementById('uploadForm');
        const fileMessage = document.getElementById('fileMessage'); // Assurez-vous d'avoir un élément avec cet ID pour afficher les messages

        // Fonction pour mettre à jour le message du fichier
        function updateFileMessage() {
            if (uploadInput.files.length > 0) {
                var fileName = uploadInput.files[0].name;
                fileMessage.textContent = fileName; // Affiche le nom du fichier sélectionné
            } else {
                fileMessage.textContent = 'Formats autorisés : XLSX, XLS ou CSV. Taille maximale de 2 Mo.'; // Affiche le message par défaut
            }
        }

        // Écoute le changement dans le champ de téléchargement de fichiers
        uploadInput.addEventListener('change', updateFileMessage);

        // Écoute l'événement click du bouton de soumission
        submitButton.addEventListener('click', function() {
            if (uploadInput.files.length > 0) { // Vérifie qu'un fichier est sélectionné
                uploadForm.submit(); // Soumet le formulaire
            } else {
                alert('Veuillez sélectionner un fichier avant de soumettre.'); // Alerte si aucun fichier n’est sélectionné
            }
        });

        // Mise à jour du message du fichier lors du chargement de la page
        updateFileMessage();
    });
</script>






               <!-- /upload -->	
        <div class="table-responsive text-nowrap">
                <table class="table table-hover">
                    <thead class="text-dark fs-4">
                        <tr>
						
							<th class="border-bottom-0">
                                <h6 class="fw-semibold mb-0">Date</h6>
                            </th>
                            <th class="border-bottom-0">
                                <h6 class="fw-semibold mb-0">Matricule</h6>
                            </th>
                            <th class="border-bottom-0">
                                <h6 class="fw-semibold mb-0">Nom</h6>
                            </th>
							<th class="border-bottom-0">
                                <h6 class="fw-semibold mb-0">Prenom</h6>
                            </th>
                            
							
							<th class="border-bottom-0">
                                <h6 class="fw-semibold mb-0">Fonction</h6>
                            </th>
							
							<th class="border-bottom-0">
                                <h6 class="fw-semibold mb-0">E1</h6>
                            </th>
							<th class="border-bottom-0">
                                <h6 class="fw-semibold mb-0">S1</h6>
                            </th>
							<th class="border-bottom-0">
                                <h6 class="fw-semibold mb-0">E2</h6>
                            </th>
							<th class="border-bottom-0">
                                <h6 class="fw-semibold mb-0">S2</h6>
                            </th>
							<th class="border-bottom-0">
                                <h6 class="fw-semibold mb-0">Presence</h6>
                            </th>
							<th class="border-bottom-0">
                                <h6 class="fw-semibold mb-0">Absence</h6>
                            </th>
							<th class="border-bottom-0">
                                <h6 class="fw-semibold mb-0">Modifier</h6>
                            </th>
							
                        </tr>
                    </thead>
                    <tbody>
<?php


// Récupération de la date sélectionnée depuis l'URL
$selectedDate = isset($_GET['dates']) ? htmlspecialchars($_GET['dates']) : '';

// Vérification si une date a été fournie, sinon on récupère la date maximale
if (empty($selectedDate)) {
    // Définir les paramètres pour récupérer la date maximale
    $maxDateResult = $conn->query('SELECT MAX(date) as max_date FROM disponibilite');

    // Vérifier si la requête a réussi et récupérer la date
    if ($maxDateResult && $row = $maxDateResult->fetch(PDO::FETCH_ASSOC)) {
        $selectedDate = $row['max_date'];
    } else {
        echo "<tr><td colspan='10'>Erreur : Impossible de récupérer la date la plus récente.</td></tr>";
        exit;
    }
}

// Vérifiez la valeur de $selectedDate
if (empty($selectedDate)) {
    echo "<tr><td colspan='10'>Erreur : La date sélectionnée est vide.</td></tr>";
    exit;
}

// Vérification du format de la date
$dateTime = DateTime::createFromFormat('Y-m-d', $selectedDate);
if (!$dateTime) {
    echo "<tr><td colspan='10'>Erreur : Format de date invalide.</td></tr>";
    exit;
}



// Définition de la requête SQL
$query = "
    SELECT DISTINCT 
        disponibilite.semaine, 
        disponibilite.date, 
        disponibilite.presence, 
        disponibilite.id, 
        disponibilite.absence, 
        disponibilite.matricule, 
        effectif.nom, 
        effectif.prenom, 
        effectif.fonction, 
        effectif.nature, 
        disponibilite.e1, 
        disponibilite.s1, 
        disponibilite.e2, 
        disponibilite.s2 
    FROM 
        disponibilite 
    full JOIN 
        effectif ON disponibilite.matricule = effectif.matricule 
    WHERE 
        disponibilite.date = '$selectedDate'
";

try {
    // Préparer et exécuter la requête
    $stmt = $conn->prepare($query);
    $stmt->execute();
    
    // Récupérer tous les résultats
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Vérification si la requête a retourné des résultats
    if ($result) {
        foreach ($result as $row) {
            echo "<tr>";
            echo "<td class='border-bottom-0'>" . htmlspecialchars($row['date']) . "</td>";
            echo "<td class='border-bottom-0'>" . htmlspecialchars($row['matricule']) . "</td>";
            echo "<td class='border-bottom-0'>" . htmlspecialchars($row['nom']) . "</td>";
            echo "<td class='border-bottom-0'>" . htmlspecialchars($row['prenom']) . "</td>";

            // Attribution des badges en fonction des valeurs de 'fonction'
            switch ($row['fonction']) {
                case 'agent-bureautique':
                    echo "<td class='border-bottom-0'><span class='badge bg-label-primary me-1'>" . htmlspecialchars($row['fonction']) . "</span></td>";
                    break;
                case 'matelasseur':
                    echo "<td class='border-bottom-0'><span class='badge bg-label-success me-1'>" . htmlspecialchars($row['fonction']) . "</span></td>";
                    break;
                case 'coupeur':
                    echo "<td class='border-bottom-0'><span class='badge bg-label-warning me-1'>" . htmlspecialchars($row['fonction']) . "</span></td>";
                    break;
                case 'manutention':
                    echo "<td class='border-bottom-0'><span class='badge bg-label-secondary me-1'>" . htmlspecialchars($row['fonction']) . "</span></td>";
                    break;
                case 'chef-equipe':
                    echo "<td class='border-bottom-0'><span class='badge bg-label-violet me-1'>" . htmlspecialchars($row['fonction']) . "</span></td>";
                    break;
                default:
                    echo "<td class='border-bottom-0'><span class='badge bg-label-info me-1'>" . htmlspecialchars($row['fonction']) . "</span></td>";
                    break;
            }

            echo "<td class='border-bottom-0'>" . htmlspecialchars($row['e1']) . "</td>";
            echo "<td class='border-bottom-0'>" . htmlspecialchars($row['s1']) . "</td>";
            echo "<td class='border-bottom-0'>" . htmlspecialchars($row['e2']) . "</td>";
            echo "<td class='border-bottom-0'>" . htmlspecialchars($row['s2']) . "</td>";
            echo "<td class='border-bottom-0'>" . htmlspecialchars($row['presence']) . "</td>";
            
            // Code pour afficher la valeur de l'absence
            $absenceValue = htmlspecialchars($row['absence']);
            // Déterminer la classe CSS en fonction de la valeur
            $absenceClass = ($absenceValue != '0.00') ? 'text-danger' : '';
            echo "<td class='border-bottom-0 $absenceClass'>" . $absenceValue . "</td>";
            echo "<td class='border-bottom-0'>";
            echo "<a href='modifier-pointage.php?id=" . htmlspecialchars($row['id']) . "'><i class='bx bxs-pencil'></i></a>"; // Icône d'édition
            echo "</td>";
            echo "</tr>";
        }
    } else {
        // Message en cas de résultats vides
        echo "<tr><td colspan='10'>Aucune donnée trouvée.</td></tr>";
    }
} catch (PDOException $e) {
    echo "<tr><td colspan='10'>Erreur : " . htmlspecialchars($e->getMessage()) . "</td></tr>";
}
?>


					
					</tbody></table></div>
			
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
