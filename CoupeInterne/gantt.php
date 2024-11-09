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

    <title>Coupe Interne | Archive Des Commande</title>

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
    <div class="card-body">
    
    <div id="chart"></div> <!-- Conteneur pour le graphique -->
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
<script src="../assets/vendor/libs/apex-charts/apexcharts.js"></script>
<script>
var barHeight = '100%'; // Définit la hauteur des barres
    var options = {
        series: [
            {
                name: 'Commande 1',
                data: [
                    {
                        x: 'Commande 1',
                        y: [
                            new Date('2019-03-01').getTime(),
                            new Date('2019-03-10').getTime() // Durée totale pour la commande 1
                        ]
                    },
                    {
                        x: 'Commande 1 - Relaxation',
                        y: [
                            new Date('2019-03-01').getTime(),
                            new Date('2019-03-03').getTime() // Durée de relaxation pour la commande 1
                        ]
                    },
                    {
                        x: 'Commande 1 - Matelassage',
                        y: [
                            new Date('2019-03-03').getTime(),
                            new Date('2019-03-05').getTime() // Durée de matelassage pour la commande 1
                        ]
                    },
                    {
                        x: 'Commande 1 - Coupe',
                        y: [
                            new Date('2019-03-05').getTime(),
                            new Date('2019-03-06').getTime() // Durée de coupe pour la commande 1
                        ]
                    },
                    {
                        x: 'Commande 1 - Étiquetage',
                        y: [
                            new Date('2019-03-06').getTime(),
                            new Date('2019-03-07').getTime() // Durée d'étiquetage pour la commande 1
                        ]
                    }
                ]
            },
            {
                name: 'Commande 2',
                data: [
                    {
                        x: 'Commande 2 - Durée Totale',
                        y: [
                            new Date('2019-03-11').getTime(),
                            new Date('2019-03-20').getTime() // Durée totale pour la commande 2
                        ]
                    },
                    {
                        x: 'Commande 2 - Relaxation',
                        y: [
                            new Date('2019-03-11').getTime(),
                            new Date('2019-03-13').getTime() // Durée de relaxation pour la commande 2
                        ]
                    },
                    {
                        x: 'Commande 2 - Matelassage',
                        y: [
                            new Date('2019-03-13').getTime(),
                            new Date('2019-03-15').getTime() // Durée de matelassage pour la commande 2
                        ]
                    },
                    {
                        x: 'Commande 2 - Coupe',
                        y: [
                            new Date('2019-03-15').getTime(),
                            new Date('2019-03-17').getTime() // Durée de coupe pour la commande 2
                        ]
                    },
                    {
                        x: 'Commande 2 - Étiquetage',
                        y: [
                            new Date('2019-03-17').getTime(),
                            new Date('2019-03-18').getTime() // Durée d'étiquetage pour la commande 2
                        ]
                    }
                ]
            },
            {
                name: 'Commande 3',
                data: [
                    {
                        x: 'Commande 3 - Durée Totale',
                        y: [
                            new Date('2019-03-19').getTime(),
                            new Date('2019-03-29').getTime() // Durée totale pour la commande 3
                        ]
                    },
                    {
                        x: 'Commande 3 - Relaxation',
                        y: [
                            new Date('2019-03-19').getTime(),
                            new Date('2019-03-21').getTime() // Durée de relaxation pour la commande 3
                        ]
                    },
                    {
                        x: 'Commande 3 - Matelassage',
                        y: [
                            new Date('2019-03-21').getTime(),
                            new Date('2019-03-23').getTime() // Durée de matelassage pour la commande 3
                        ]
                    },
                    {
                        x: 'Commande 3 - Coupe',
                        y: [
                            new Date('2019-03-23').getTime(),
                            new Date('2019-03-25').getTime() // Durée de coupe pour la commande 3
                        ]
                    },
                    {
                        x: 'Commande 3 - Étiquetage',
                        y: [
                            new Date('2019-03-25').getTime(),
                            new Date('2019-03-27').getTime() // Durée d'étiquetage pour la commande 3
                        ]
                    }
                ]
            }
        ],
chart: {
    height: 600, // Hauteur du graphique
    type: 'rangeBar', // Type de graphique
    toolbar: {
        show: true,
        offsetX: 0,
        offsetY: 0,
        tools: {
            download: true,
            selection: true,
            zoom: true,
            zoomin: true,
            zoomout: true,
            pan: true,
            reset: true | '<img src="/static/icons/reset.png" width="20">', // Permet de réinitialiser le zoom
            customIcons: [] // Icônes personnalisées
        },
        export: {
            csv: {
                filename: undefined, // Nom du fichier CSV
                columnDelimiter: ',', // Délimiteur des colonnes CSV
                headerCategory: 'category', // En-tête pour les catégories
                headerValue: 'value', // En-tête pour les valeurs
                categoryFormatter(x) { // Formatteur pour les catégories (dates)
                    return new Date(x).toDateString(); // Convertit les timestamps en dates
                },
                valueFormatter(y) { // Formatteur pour les valeurs
                    return y; // Retourne simplement la valeur
                }
            },
            svg: {
                filename: undefined, // Nom du fichier SVG
            },
            png: {
                filename: undefined, // Nom du fichier PNG
            }
        },
        autoSelected: 'zoom' // Sélection automatique de l'outil de zoom
    }
},

plotOptions: {
    bar: {
        horizontal: true, // Les barres sont horizontales
        barHeight: '100%', // Ajuste la hauteur des barres (augmente pour les rendre plus larges)
        columnWidth: '100%',
    }
},
xaxis: {
    type: 'datetime', // Axe X basé sur les dates
    min: new Date('2019-03-01').getTime(), // Date de départ
    
},
grid: {
    show: true, // Afficher le quadrillage
    borderColor: '#e0e0e0', // Couleur des lignes de quadrillage
    position: 'back', // Position des lignes de quadrillage à l'arrière
    xaxis: {
        lines: {
            show: true // Afficher les lignes verticales sur l'axe X
        }
    },
    
},
stroke: {
    width: 0 // Largeur du trait de contour des barres
},
fill: {
    type: 'solid', // Type de remplissage (solide)
    opacity: 0.6 // Plus d'opacité pour rendre les barres plus visibles
},
legend: {
    show: false
  },
        
    };

    var chart = new ApexCharts(document.querySelector("#chart"), options);
    chart.render();
</script>




    <!-- Main JS -->
    <script src="../assets/js/main.js"></script>

    <!-- Page JS -->
    <script src="../assets/js/pages-account-settings-account.js"></script>

    <!-- Place this tag in your head or just before your close body tag. -->
    <script async defer src="https://buttons.github.io/buttons.js"></script>
  </body>
</html>
