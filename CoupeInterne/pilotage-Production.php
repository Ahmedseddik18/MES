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
              <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Commandes /</span> Archive</h4>

              <div class="row">

			  
                <div class="col">
                 
				  
<div class="card ">
<h5 class="card-header">Pilotage Des Commandes</h5>
    <div class="card-body">
<?php

include("../php/db.php");
include("../php/fonction.php");

// Initialisation des variables
$sort = isset($_GET['sort']) ? $_GET['sort'] : 'code';
$order = isset($_GET['order']) ? $_GET['order'] : 'ASC';

// Évitez les injections SQL en validant les colonnes
$validSortColumns = ['commande', 'article', 'partie', 'sub', 'codeMatelas', 'phase', 'operateur1', 'operateur2', 'quantiteMatelas', 'tabl'];
if (!in_array($sort, $validSortColumns)) {
    $sort = 'commande';
}

// Évitez les injections SQL en validant l'ordre
$order = $order === 'desc' ? 'DESC' : 'ASC';

$selectFields = '*';
$fromTables = "planification";
$whereConditions = "DATE(datetime_debut) = CURDATE()"; // Condition de base toujours vraie

$orderBy = "$sort $order"; // Assurez-vous de définir $orderBy si nécessaire

// Insertion des données en utilisant la fonction existante
$result = select($conn, $selectFields, $fromTables, $whereConditions, $orderBy);

$distinctRelaxation = []; // Tableau pour stocker les commandes et articles distincts

// Fonction pour afficher la flèche
function getSortArrow($column, $sort, $order) {
    if ($column === $sort) {
        return $order === 'ASC' ? '↑' : '↓'; // Flèche vers le haut ou le bas
    }
    return ''; // Pas de flèche si ce n'est pas la colonne triée
}

if ($result && $result->rowCount() > 0) {
    // Affichage du tableau
    echo '<div class="table-responsive text-nowrap">
    <table id="myTable" class="table table-hover">
        <thead class="text-dark fs-4">
            <tr>
                <th class="border-bottom-0" data-sort="commande">Commande <span class="sort-arrow"></span></th>
                <th class="border-bottom-0" data-sort="article">Article <span class="sort-arrow"></span></th>
                <th class="border-bottom-0" data-sort="partie">Partie <span class="sort-arrow"></span></th>
                <th class="border-bottom-0" data-sort="sub">Sub <span class="sort-arrow"></span></th>
                <th class="border-bottom-0" data-sort="codeMatelas">Matelas <span class="sort-arrow"></span></th>
                <th class="border-bottom-0" data-sort="etat">Etat <span class="sort-arrow"></span></th>
                <th class="border-bottom-0" data-sort="phase">Phase <span class="sort-arrow"></span></th>
                <th class="border-bottom-0" data-sort="operateur1">Opérateur 1 <span class="sort-arrow"></span></th>
                <th class="border-bottom-0" data-sort="operateur2">Opérateur 2 <span class="sort-arrow"></span></th>
                <th class="border-bottom-0" data-sort="quantiteMatelas">Quantité <span class="sort-arrow"></span></th>
                <th class="border-bottom-0" data-sort="tabl">Table <span class="sort-arrow"></span></th>
            </tr>
        </thead>
        <tbody id="tableBody">';

    
    // Utilisation de fetch() pour récupérer les résultats avec PDO
    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        echo "<tr class='table-row ' 
            data-commande='" . htmlspecialchars($row['commande']) . "' 
            data-article='" . strtoupper(htmlspecialchars($row['article'])) . "' 
            data-phase='" . htmlspecialchars($row['phase']) . "' 
            data-quantite='" . htmlspecialchars($row['quantiteMatelas']) . "'
			data-operateur1='" . htmlspecialchars($row['operateur1']) . "'
			data-operateur2='" . htmlspecialchars($row['operateur2']) . "'
			data-sub='" . htmlspecialchars($row['sub']) . "'
			data-codeMatelas='" . htmlspecialchars($row['codeMatelas']) . "'
			data-table='" . htmlspecialchars($row['tabl']) . "'
			>";
        if ($row['phase'] == 'matelassage' || $row['phase'] == 'coupe') {
            echo "<td class='border-bottom-0' data-commande='" . htmlspecialchars($row['commande']) . "'>" . htmlspecialchars($row['commande']) . "</td>";
            echo "<td class='border-bottom-0' data-article='" . strtoupper(htmlspecialchars($row['article'])) . "'>" . strtoupper(htmlspecialchars($row['article'])) . "</td>";
            echo "<td class='border-bottom-0'>" . strtoupper(htmlspecialchars($row['partie'])) . "</td>";
            echo "<td class='border-bottom-0'>" . strtoupper(htmlspecialchars($row['sub'])) . "</td>";
            echo "<td class='border-bottom-0'>" . strtoupper(htmlspecialchars(substr($row['codeMatelas'], -3))) . "</td>";
			if (htmlspecialchars($row['etat']) == 'En attente') {
                echo "<td class='data-etat border-bottom-0'><span class='badge bg-label-warning me-1'>" . htmlspecialchars($row['etat']) . "</span></td>";
            } elseif (htmlspecialchars($row['etat']) === 'Termine') {
                echo "<td class='data-etat border-bottom-0'><span class='badge bg-label-success me-1'>" . htmlspecialchars($row['etat']) . "</span></td>";
            }
			
            
            
            if (htmlspecialchars($row['phase']) == 'matelassage') {
                echo "<td class='border-bottom-0'><span class='badge bg-label-primary me-1'>" . htmlspecialchars($row['phase']) . "</span></td>";
            } elseif (htmlspecialchars($row['phase']) == 'coupe') {
                echo "<td class='border-bottom-0'><span class='badge bg-label-success me-1'>" . htmlspecialchars($row['phase']) . "</span></td>";
            } elseif (htmlspecialchars($row['phase']) == 'etiquetage') {
                echo "<td class='border-bottom-0'><span class='badge bg-label-dark me-1'>" . htmlspecialchars($row['phase']) . "</span></td>";
            } else {
                echo "<td class='border-bottom-0'><span class='badge bg-label-warning me-1'>" . htmlspecialchars($row['phase']) . "</span></td>";
            }

            echo "<td class='border-bottom-0'>" . strtoupper(htmlspecialchars($row['operateur1'])) . "</td>";

            if ($row['operateur2'] !== '0') {
                echo "<td class='border-bottom-0'>" . strtoupper(htmlspecialchars($row['operateur2'])) . "</td>";
            } else {
                echo "<td class='border-bottom-0'></td>";
            }

            echo "<td class='border-bottom-0'>" . strtoupper(htmlspecialchars($row['quantiteMatelas'])) . "</td>";

            if ($row['phase'] == 'matelassage' || $row['phase'] == 'coupe') {
                echo "<td class='border-bottom-0'>" . strtoupper(htmlspecialchars($row['tabl'])) . "</td>";
            } else {
                echo "<td class='border-bottom-0'></td>";
            }

        } elseif ($row['phase'] == 'relaxation') {
            // Gérer les commandes et articles distincts pour la phase relaxation
            $uniqueKey = $row['commande'] . '-' . $row['article'];
            if (!in_array($uniqueKey, $distinctRelaxation)) {
                echo "<td class='border-bottom-0'>" . htmlspecialchars($row['commande']) . "</td>";
                echo "<td class='border-bottom-0'>" . strtoupper(htmlspecialchars($row['article'])) . "</td>";
                echo "<td class='border-bottom-0'></td>";
                echo "<td class='border-bottom-0'></td>";
                echo "<td class='border-bottom-0'></td>";
				if (htmlspecialchars($row['etat']) == 'En attente') {
                echo "<td class=' data-etat border-bottom-0'><span class='badge bg-label-warning me-1'>" . htmlspecialchars($row['etat']) . "</span></td>";
            } elseif (htmlspecialchars($row['etat']) == 'Termine') {
                echo "<td class='data-etat border-bottom-0'><span class='badge bg-label-success me-1'>" . htmlspecialchars($row['etat']) . "</span></td>";
            }
                echo "<td class='border-bottom-0'><span class='badge bg-label-info me-1'>RELAXATION</span></td>";
                echo "<td class='border-bottom-0'>" . strtoupper(htmlspecialchars($row['operateur1'])) . "</td>";
                echo "<td class='border-bottom-0'>" . strtoupper(htmlspecialchars($row['operateur2'])) . "</td>";
                echo "<td class='border-bottom-0'></td>";
                echo "<td class='border-bottom-0'></td>";
                
                $distinctRelaxation[] = $uniqueKey;
            }
        }
		
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
<!-- Modal -->
<div class="modal fade" id="detailModal" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalLabel">Détails</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <!-- Contenu du modal, sera rempli dynamiquement -->
        <p><strong>Commande:</strong> <span id="modalCommande"></span></p>
        <p><strong>Article:</strong> <span id="modalArticle"></span></p>
        <p><strong>Phase:</strong> <span id="modalPhase"></span></p>
        <p><strong>Quantité:</strong> <span id="modalQuantite"></span></p>
        <p><strong>Opérateur1:</strong> <span id="modaloperateur1"></span></p>
        <p><strong>Opérateur2:</strong> <span id="modaloperateur2"></span></p>
		<div class="row">
    <!-- Colonne pour Heures Réelle -->
    <div class="col-md-6">
        <p><strong>Heures Réelle:</strong>
            <input type="text" id="heures" class="form-control" />
        </p>
    </div>

    <!-- Colonne pour Note -->
    <div class="col-md-6">
        <p><strong>Note:</strong>
            <input type="text" id="note" class="form-control" />
        </p>
    </div>
</div>

          
        <!-- Ajoute plus de détails ici si nécessaire -->
      </div>
      <div class="modal-footer">
        <button type="button" id="saveDataBtn" class="btn btn-label-success" data-bs-dismiss="modal">Enregistrer</button>
      </div>
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
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const tableBody = document.getElementById('tableBody');
        const headers = document.querySelectorAll('#myTable th[data-sort]');
        
        headers.forEach(header => {
            header.addEventListener('click', () => {
                const sortField = header.getAttribute('data-sort');
                const rows = Array.from(tableBody.rows);
                const currentOrder = header.classList.contains('asc') ? 'asc' : 'desc';
                const newOrder = currentOrder === 'asc' ? 'desc' : 'asc';

                rows.sort((a, b) => {
                    const aText = a.cells[Array.from(headers).indexOf(header)].textContent.trim();
                    const bText = b.cells[Array.from(headers).indexOf(header)].textContent.trim();

                    if (newOrder === 'asc') {
                        return aText.localeCompare(bText);
                    } else {
                        return bText.localeCompare(aText);
                    }
                });

                // Réinitialise l'ordre des classes de tri
                headers.forEach(h => h.classList.remove('asc', 'desc'));
                header.classList.toggle('asc', newOrder === 'asc');
                header.classList.toggle('desc', newOrder === 'desc');

                // Met à jour le corps du tableau avec les lignes triées
                rows.forEach(row => tableBody.appendChild(row));
            });
        });
    });

    function getBadgeClass(phase) {
        switch (phase) {
            case 'matelassage':
                return 'bg-label-primary me-1';
            case 'coupe':
                return 'bg-label-success me-1';
            case 'etiquetage':
                return 'bg-label-dark me-1';
            default:
                return 'bg-label-warning me-1';
        }
    }
</script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    let selectedRow = null; // Variable globale pour stocker la ligne sélectionnée

    const tableRows = document.querySelectorAll('.table-row');
    
    tableRows.forEach(row => {
        row.addEventListener('click', function() {
            // Stocker la ligne cliquée dans la variable selectedRow
            selectedRow = row;

            // Récupérer les attributs de données de la ligne cliquée
            const commande = row.getAttribute('data-commande');
            const article = row.getAttribute('data-article');
            const phase = row.getAttribute('data-phase');
            const quantite = row.getAttribute('data-quantite');
            const operateur1 = row.getAttribute('data-operateur1');
            const operateur2 = row.getAttribute('data-operateur2');
            
            // Remplir le contenu du modal avec ces données
            document.getElementById('modalCommande').innerText = commande;
            document.getElementById('modalArticle').innerText = article;
            document.getElementById('modalPhase').innerText = phase;
            document.getElementById('modalQuantite').innerText = quantite;
            document.getElementById('modaloperateur1').innerText = operateur1;
            
            if (operateur2 === '0') {
                document.getElementById('modaloperateur2').innerText = '';
            } else {
                document.getElementById('modaloperateur2').innerText = operateur2;
            }

            // Afficher le modal
            const modal = new bootstrap.Modal(document.getElementById('detailModal'));
            modal.show();
        });
    });

    // Écouter le bouton Enregistrer et envoyer les données au serveur
    document.getElementById('saveDataBtn').addEventListener('click', function() {
        if (selectedRow) { // Vérifier si une ligne a été sélectionnée
            const sub = selectedRow.getAttribute('data-sub');
            const tabl = selectedRow.getAttribute('data-table');
            const codeMatelas = selectedRow.getAttribute('data-codeMatelas');
            const commande = document.getElementById('modalCommande').innerText;
            const article = document.getElementById('modalArticle').innerText;
            const phase = document.getElementById('modalPhase').innerText;
            const quantite = document.getElementById('modalQuantite').innerText;
            const operateur1 = document.getElementById('modaloperateur1').innerText;
            const operateur2 = document.getElementById('modaloperateur2').innerText;
            const heures = document.getElementById('heures').value;
            const note = document.getElementById('note').value;

            // Créer l'objet de données à envoyer
            const formData = {
                commande: commande,
                article: article,
                sub: sub,
                tabl: tabl,
                codeMatelas: codeMatelas,
                phase: phase,
                quantite: quantite,
                operateur1: operateur1,
                operateur2: operateur2,
                heures: heures,
                note: note
            };

            // Envoyer les données via fetch()
            fetch('enregistrer-production.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(formData)
            })
            .then(response => response.json())
            .then(data => {
                console.log(data); // Corrigé : utiliser 'data' et non 'response'
                if (data.success) {
                    
                    
                    // Mettre à jour l'état dans le tableau
                    const statusCell = selectedRow.querySelector('.data-etat'); // Supposons que vous ayez une cellule pour l'état
                    if (statusCell) {
                        statusCell.innerHTML = "<span class='badge bg-label-success me-1'>Termine</span>"; // Mettre à jour l'état à 'Terminé'
                    }

                    // Fermer le modal
                    const modal = bootstrap.Modal.getInstance(document.getElementById('detailModal'));
                    modal.hide();
                } else {
                    alert('Erreur lors de l\'enregistrement des données.');
                }
            })
            .catch(error => {
                console.error('Erreur:', error);
            });
        } else {
            alert("Aucune ligne sélectionnée.");
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
