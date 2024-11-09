<?php


session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../../index.php");
    exit();
}
include("../php/db.php");
include("../php/fonction.php");
$lundi = date('Y-m-d', strtotime('monday this week'));
$mardi = date('Y-m-d', strtotime('monday this week +1 day'));
$mercredi = date('Y-m-d', strtotime('monday this week +2 days'));
$jeudi = date('Y-m-d', strtotime('monday this week +3 days'));
$vendredi = date('Y-m-d', strtotime('monday this week +4 days'));
$selectFields = "
    SUM(CASE WHEN etat = 'Termine' THEN quantitedemandee ELSE 0 END) AS termine,
    SUM(CASE WHEN etat = 'En cours' THEN quantitedemandee ELSE 0 END) AS en_cours,
    SUM(CASE WHEN etat = 'En attente' THEN quantitedemandee ELSE 0 END) AS en_attente,
    SUM(CASE WHEN etat = 'T1' THEN quantitedemandee ELSE 0 END) AS t1,
    SUM(CASE WHEN etat = 'Bloque' THEN quantitedemandee ELSE 0 END) AS Bloque,
    SUM(CASE WHEN etat = 'Termine'  THEN prixtotal ELSE 0 END) AS totalfacturesemaine
";
$fromTables = "db";
$whereConditions = "1 = 1"; // Condition de base toujours vraie
$orderBy = ''; // Si nécessaire, définissez l'ordre

// Exécution de la requête
$result = select($conn, $selectFields, $fromTables, $whereConditions, $orderBy);

// Récupérer les résultats sous forme de tableau
$row = $result->fetch(PDO::FETCH_ASSOC);

// Encodage des résultats en JSON
$chartData = [
    'termine' => $row['termine'] ?? 0,   // Si $row['termine'] est null, utiliser 0
    'en_cours' => $row['en_cours'] ?? 0, // Si $row['en_cours'] est null, utiliser 0
    'en_attente' => $row['en_attente'] ?? 0, // Si $row['en_attente'] est null, utiliser 0
    't1' => $row['t1'] ?? 0, // Si $row['T1'] est null, utiliser 0
    'bloque' => $row['bloque'] ?? 0, // Si $row['T1'] est null, utiliser 0
    'totalfacturesemaine' => $row['totalfacturesemaine'] ?? 0 // Si $row['T1'] est null, utiliser 0
];



?>

<!DOCTYPE html>
<html lang="en" class="light-style layout-menu-fixed" dir="ltr" data-theme="theme-default" data-assets-path="../assets/" data-template="vertical-menu-template-free">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
    <title>Coupe Interne | Dashboard</title>
    <meta name="description" content="" />

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="../assets/img/favicon/favicon.ico" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet" />

    <!-- Icons -->
    <link rel="stylesheet" href="../assets/vendor/fonts/boxicons.css" />

    <!-- Core CSS -->
    <link rel="stylesheet" href="../assets/vendor/css/core.css" class="template-customizer-core-css" />
    <link rel="stylesheet" href="../assets/vendor/css/theme-default.css" class="template-customizer-theme-css" />
    <link rel="stylesheet" href="../assets/css/demo.css" />

    <!-- Vendors CSS -->
    <link rel="stylesheet" href="../assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />
    <link rel="stylesheet" href="../assets/vendor/libs/apex-charts/apex-charts.css" />

    <!-- Helpers -->
    <script src="../assets/vendor/js/helpers.js"></script>

    <!-- Config JS -->
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
<?php if ($_SESSION['role'] == 'admin'): ?>
            <div class="flex-grow-1 container-p-y container-fluid">
<div class="col mb-4 ">
  <div class="card  ">
    <div class="card-body row p-0 ">
      <!-- Première carte -->
      <div class="col-md-3 card-separator">
        
          <div class="p-6">
            <div class="card-title d-flex align-items-start justify-content-between">
              <h5 class="mb-0">Facture Coupe</h5>
             
            </div>
            <div class="d-flex justify-content-between mb-0" style="position: relative;">
              <div class="mt-6">
                <h3 class="mb-1"><?php echo round($chartData['totalfacturesemaine'],0); ?>€</h3>
                <small class="text-success text-nowrap fw-medium"><i class="bx bx-up-arrow-alt"></i> +13.24%</small>
              </div>
             <div id="FactureCoupe" ></div>
            </div>
          </div>
        
      </div>

      <!-- Deuxième carte -->
      <div class="col-md-3 card-separator">
          <div class="p-6">
            <div class="card-title d-flex align-items-start justify-content-between">
              <h5 class="mb-0">Facture Plotter</h5>
              
            </div>
            <div class="d-flex justify-content-between" style="position: relative;">
              <div class="mt-6">
                <h3 class="mb-1">23%</h3>
                <small class="text-danger text-nowrap fw-medium"><i class="bx bx-down-arrow-alt"></i> -13.24%</small>
              </div>
             <div id="FacturePlotter" ></div>
            </div>
          </div>
        
      </div>

      <!-- Troisième carte -->
      <div class="col-md-3 card-separator">
          <div class="p-6">
            <div class="card-title d-flex align-items-start justify-content-between">
              <h5 class="mb-0">Surplus Production</h5>
             
            </div>
            <div class="d-flex align-items-start justify-content-between" style="position: relative;">
              <div class="mt-6">
                <h3 class="mb-1">82%</h3>
                <small class="text-success text-nowrap fw-medium"><i class="bx bx-up-arrow-alt"></i> 24.8%</small>
              </div>
              <div id="profileReportChart2" style="min-height: 120px;"></div>
            </div>
          </div>
        
      </div>

      <!-- Quatrième carte -->
<div class="col-md-3">
  <div class="p-6">
    <div class="card-title d-flex align-items-start justify-content-between">
      <h5 class="mb-0">Efficience</h5>
     
    </div>
    <div class="d-flex justify-content-between align-items-center" style="position: relative;">
      <div class="mt-6">
        <h3 class="mb-1">82%</h3>
        <small class="text-success text-nowrap fw-medium"><i class="bx bx-up-arrow-alt"></i> 24.8%</small>
      </div>
      <!-- Espace ajouté ici avec une marge -->
      <div id="profileReportChart" style="width: 150px;"></div>
    </div>
  </div>
</div>



    </div>
  </div>
</div>


              
                

                <div class="row">
                <!-- Total Revenue -->
                <div class="col-12 col-lg-8 order-2 order-md-3 order-lg-2 mb-4">
                  <div class="card">
                    <div class="row row-bordered g-0">
                      <div class="col-md-8">
                        <h5 class="card-header m-0 me-2 pb-3">Total Revenue</h5>
                        <div id="totalRevenueChart" class="px-2"></div>
                      </div>
                      <div class="col-md-4">
                        <div class="card-body">
                          <div class="text-center">
                            <div class="dropdown">
                              <button
                                class="btn btn-sm btn-outline-primary dropdown-toggle"
                                type="button"
                                id="growthReportId"
                                data-bs-toggle="dropdown"
                                aria-haspopup="true"
                                aria-expanded="false"
                              >
                                2022
                              </button>
                              <div class="dropdown-menu dropdown-menu-end" aria-labelledby="growthReportId">
                                <a class="dropdown-item" href="javascript:void(0);">2021</a>
                                <a class="dropdown-item" href="javascript:void(0);">2020</a>
                                <a class="dropdown-item" href="javascript:void(0);">2019</a>
                              </div>
                            </div>
                          </div>
                        </div>
                        <div id="growthChart"></div>
                        <div class="text-center fw-semibold pt-3 mb-2">62% Company Growth</div>

                        <div class="d-flex px-xxl-4 px-lg-2 p-4 gap-xxl-3 gap-lg-1 gap-3 justify-content-between">
                          <div class="d-flex">
                            <div class="me-2">
                              <span class="badge bg-label-primary p-2"><i class="bx bx-dollar text-primary"></i></span>
                            </div>
                            <div class="d-flex flex-column">
                              <small>2022</small>
                              <h6 class="mb-0">$32.5k</h6>
                            </div>
                          </div>
                          <div class="d-flex">
                            <div class="me-2">
                              <span class="badge bg-label-info p-2"><i class="bx bx-wallet text-info"></i></span>
                            </div>
                            <div class="d-flex flex-column">
                              <small>2021</small>
                              <h6 class="mb-0">$41.2k</h6>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <!--/ Total Revenue -->
                <div class="col-12 col-md-8 col-lg-4 order-3 order-md-2">
                  <div class="row">
                    <div class="col-6 mb-4">
                      <div class="card">
                        <div class="card-body">
                          <div class="card-title d-flex align-items-start justify-content-between">
                            <div class="avatar flex-shrink-0">
                              <img src="../assets/img/icons/unicons/paypal.png" alt="Credit Card" class="rounded" />
                            </div>
                            <div class="dropdown">
                              <button
                                class="btn p-0"
                                type="button"
                                id="cardOpt4"
                                data-bs-toggle="dropdown"
                                aria-haspopup="true"
                                aria-expanded="false"
                              >
                                <i class="bx bx-dots-vertical-rounded"></i>
                              </button>
                              <div class="dropdown-menu dropdown-menu-end" aria-labelledby="cardOpt4">
                                <a class="dropdown-item" href="javascript:void(0);">View More</a>
                                <a class="dropdown-item" href="javascript:void(0);">Delete</a>
                              </div>
                            </div>
                          </div>
                          <span class="d-block mb-1">Payments</span>
                          <h3 class="card-title text-nowrap mb-2">$2,456</h3>
                          <small class="text-danger fw-semibold"><i class="bx bx-down-arrow-alt"></i> -14.82%</small>
                        </div>
                      </div>
                    </div>
                    <div class="col-6 mb-4">
                      <div class="card">
                        <div class="card-body">
                          <div class="card-title d-flex align-items-start justify-content-between">
                            <div class="avatar flex-shrink-0">
                              <img src="../assets/img/icons/unicons/cc-primary.png" alt="Credit Card" class="rounded" />
                            </div>
                            <div class="dropdown">
                              <button
                                class="btn p-0"
                                type="button"
                                id="cardOpt1"
                                data-bs-toggle="dropdown"
                                aria-haspopup="true"
                                aria-expanded="false"
                              >
                                <i class="bx bx-dots-vertical-rounded"></i>
                              </button>
                              <div class="dropdown-menu" aria-labelledby="cardOpt1">
                                <a class="dropdown-item" href="javascript:void(0);">View More</a>
                                <a class="dropdown-item" href="javascript:void(0);">Delete</a>
                              </div>
                            </div>
                          </div>
                          <span class="fw-semibold d-block mb-1">Transactions</span>
                          <h3 class="card-title mb-2">$14,857</h3>
                          <small class="text-success fw-semibold"><i class="bx bx-up-arrow-alt"></i> +28.14%</small>
                        </div>
                      </div>
                    </div>
                    <!-- </div>
    <div class="row"> -->
                    <div class="col-12 mb-4">
                      <div class="card">
                        <div class="card-body">
                          <div class="d-flex justify-content-between flex-sm-row flex-column gap-3">
                            <div class="d-flex flex-sm-column flex-row align-items-start justify-content-between">
                              <div class="card-title">
                                <h5 class="text-nowrap mb-2">Profile Report</h5>
                                <span class="badge bg-label-warning rounded-pill">Year 2021</span>
                              </div>
                              <div class="mt-sm-auto">
                                <small class="text-success text-nowrap fw-semibold"
                                  ><i class="bx bx-chevron-up"></i> 68.2%</small
                                >
                                <h3 class="mb-0">$84,686k</h3>
                              </div>
                            </div>
                            <div id="profileReportChart"></div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="row">
                <!-- Order Statistics -->
                <div class="col-md-6 col-lg-4 col-xl-4 order-0 mb-4">
                  <div class="card h-100">
                    <div class="card-header d-flex align-items-center justify-content-between pb-0">
                      <div class="card-title mb-0">
                        <h5 class="m-0 me-2">Statistique De Production</h5>
                        
                      </div>
                      
                    </div>
                    <div class="card-body">
                      <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="d-flex flex-column align-items-center gap-1">
                          <h2 class="mb-2"><?php echo $chartData['termine']; ?></h2>
                          <span>Totale Quantité Produite</span>
                        </div>
                        <div id="statistiqueProduction"></div>
                      </div>
                      <ul class="p-0 m-0">
                        <li class="d-flex mb-4 pb-1">
                          <div class="avatar flex-shrink-0 me-3">
                            <span class="avatar-initial rounded bg-label-info"
                              ><i class="bx bxs-truck"></i
                            ></span>
                          </div>
                          <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                            <div class="me-2">
                              <h6 class="mb-0">T1</h6>
                              
                            </div>
                            <div class="user-progress">
                              <small class="fw-semibold"><?php echo $chartData['t1']; ?></small>
                            </div>
                          </div>
                        </li>
                        <li class="d-flex mb-4 pb-1">
                          <div class="avatar flex-shrink-0 me-3">
                            <span class="avatar-initial rounded bg-label-secondary"><i class='bx bx-cube'></i></span>
                          </div>
                          <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                            <div class="me-2">
                              <h6 class="mb-0">En Attente</h6>
                              
                            </div>
                            <div class="user-progress">
                              <small class="fw-semibold"><?php echo $chartData['en_attente']; ?></small>
                            </div>
                          </div>
                        </li>
                        <li class="d-flex mb-4 pb-1">
                          <div class="avatar flex-shrink-0 me-3">
                            <span class="avatar-initial rounded bg-label-warning"><i class="bx bx-loader"></i></span>
                          </div>
                          <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                            <div class="me-2">
                              <h6 class="mb-0">En Cours</h6>
                              
                            </div>
                            <div class="user-progress">
                              <small class="fw-semibold"><?php echo $chartData['en_cours']; ?></small>
                            </div>
                          </div>
                        </li>
                        <li class="d-flex">
                          <div class="avatar flex-shrink-0 me-3">
                            <span class="avatar-initial rounded bg-label-danger"
                              ><i class="bx bx-error"></i
                            ></span>
                          </div>
                          <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                            <div class="me-2">
                              <h6 class="mb-0">Bloqué</h6>
                              
                            </div>
                            <div class="user-progress">
                              <small class="fw-semibold"><?php echo $chartData['bloque']; ?></small>
                            </div>
                          </div>
                        </li>
                      </ul>
                    </div>
                  </div>
                </div>
                <!--/ Order Statistics -->

                <!-- Expense Overview -->
                <div class="col-md-6 col-lg-4 order-1 mb-4">
                  <div class="card h-100">
                    <div class="card-header">
                      <ul class="nav nav-pills" role="tablist">
                        <li class="nav-item">
                          <button
                            type="button"
                            class="nav-link active"
                            role="tab"
                            data-bs-toggle="tab"
                            data-bs-target="#navs-tabs-line-card-income"
                            aria-controls="navs-tabs-line-card-income"
                            aria-selected="true"
                          >
                            Income
                          </button>
                        </li>
                        <li class="nav-item">
                          <button type="button" class="nav-link" role="tab">Expenses</button>
                        </li>
                        <li class="nav-item">
                          <button type="button" class="nav-link" role="tab">Profit</button>
                        </li>
                      </ul>
                    </div>
                    <div class="card-body px-0">
                      <div class="tab-content p-0">
                        <div class="tab-pane fade show active" id="navs-tabs-line-card-income" role="tabpanel">
                          <div class="d-flex p-4 pt-3">
                            <div class="avatar flex-shrink-0 me-3">
                              <img src="../assets/img/icons/unicons/wallet.png" alt="User" />
                            </div>
                            <div>
                              <small class="text-muted d-block">Total Balance</small>
                              <div class="d-flex align-items-center">
                                <h6 class="mb-0 me-1">$459.10</h6>
                                <small class="text-success fw-semibold">
                                  <i class="bx bx-chevron-up"></i>
                                  42.9%
                                </small>
                              </div>
                            </div>
                          </div>
                          <div id="incomeChart"></div>
                          <div class="d-flex justify-content-center pt-4 gap-2">
                            <div class="flex-shrink-0">
                              <div id="expensesOfWeek"></div>
                            </div>
                            <div>
                              <p class="mb-n1 mt-1">Expenses This Week</p>
                              <small class="text-muted">$39 less than last week</small>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <!--/ Expense Overview -->

                <!-- Transactions -->
                <div class="col-md-6 col-lg-4 order-2 mb-4">
                  <div class="card h-100">
                    <div class="card-header d-flex align-items-center justify-content-between">
                      <h5 class="card-title m-0 me-2">Transactions</h5>
                      <div class="dropdown">
                        <button
                          class="btn p-0"
                          type="button"
                          id="transactionID"
                          data-bs-toggle="dropdown"
                          aria-haspopup="true"
                          aria-expanded="false"
                        >
                          <i class="bx bx-dots-vertical-rounded"></i>
                        </button>
                        <div class="dropdown-menu dropdown-menu-end" aria-labelledby="transactionID">
                          <a class="dropdown-item" href="javascript:void(0);">Last 28 Days</a>
                          <a class="dropdown-item" href="javascript:void(0);">Last Month</a>
                          <a class="dropdown-item" href="javascript:void(0);">Last Year</a>
                        </div>
                      </div>
                    </div>
                    <div class="card-body">
                      <ul class="p-0 m-0">
                        <li class="d-flex mb-4 pb-1">
                          <div class="avatar flex-shrink-0 me-3">
                            <img src="../assets/img/icons/unicons/paypal.png" alt="User" class="rounded" />
                          </div>
                          <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                            <div class="me-2">
                              <small class="text-muted d-block mb-1">Paypal</small>
                              <h6 class="mb-0">Send money</h6>
                            </div>
                            <div class="user-progress d-flex align-items-center gap-1">
                              <h6 class="mb-0">+82.6</h6>
                              <span class="text-muted">USD</span>
                            </div>
                          </div>
                        </li>
                        <li class="d-flex mb-4 pb-1">
                          <div class="avatar flex-shrink-0 me-3">
                            <img src="../assets/img/icons/unicons/wallet.png" alt="User" class="rounded" />
                          </div>
                          <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                            <div class="me-2">
                              <small class="text-muted d-block mb-1">Wallet</small>
                              <h6 class="mb-0">Mac'D</h6>
                            </div>
                            <div class="user-progress d-flex align-items-center gap-1">
                              <h6 class="mb-0">+270.69</h6>
                              <span class="text-muted">USD</span>
                            </div>
                          </div>
                        </li>
                        <li class="d-flex mb-4 pb-1">
                          <div class="avatar flex-shrink-0 me-3">
                            <img src="../assets/img/icons/unicons/chart.png" alt="User" class="rounded" />
                          </div>
                          <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                            <div class="me-2">
                              <small class="text-muted d-block mb-1">Transfer</small>
                              <h6 class="mb-0">Refund</h6>
                            </div>
                            <div class="user-progress d-flex align-items-center gap-1">
                              <h6 class="mb-0">+637.91</h6>
                              <span class="text-muted">USD</span>
                            </div>
                          </div>
                        </li>
                        <li class="d-flex mb-4 pb-1">
                          <div class="avatar flex-shrink-0 me-3">
                            <img src="../assets/img/icons/unicons/cc-success.png" alt="User" class="rounded" />
                          </div>
                          <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                            <div class="me-2">
                              <small class="text-muted d-block mb-1">Credit Card</small>
                              <h6 class="mb-0">Ordered Food</h6>
                            </div>
                            <div class="user-progress d-flex align-items-center gap-1">
                              <h6 class="mb-0">-838.71</h6>
                              <span class="text-muted">USD</span>
                            </div>
                          </div>
                        </li>
                        <li class="d-flex mb-4 pb-1">
                          <div class="avatar flex-shrink-0 me-3">
                            <img src="../assets/img/icons/unicons/wallet.png" alt="User" class="rounded" />
                          </div>
                          <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                            <div class="me-2">
                              <small class="text-muted d-block mb-1">Wallet</small>
                              <h6 class="mb-0">Starbucks</h6>
                            </div>
                            <div class="user-progress d-flex align-items-center gap-1">
                              <h6 class="mb-0">+203.33</h6>
                              <span class="text-muted">USD</span>
                            </div>
                          </div>
                        </li>
                        <li class="d-flex">
                          <div class="avatar flex-shrink-0 me-3">
                            <img src="../assets/img/icons/unicons/cc-warning.png" alt="User" class="rounded" />
                          </div>
                          <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                            <div class="me-2">
                              <small class="text-muted d-block mb-1">Mastercard</small>
                              <h6 class="mb-0">Ordered Food</h6>
                            </div>
                            <div class="user-progress d-flex align-items-center gap-1">
                              <h6 class="mb-0">-92.45</h6>
                              <span class="text-muted">USD</span>
                            </div>
                          </div>
                        </li>
                      </ul>
                    </div>
                  </div>
                </div>
                <!--/ Transactions -->
              </div>
            </div>
			<?php endif; ?>
<?php if ($_SESSION['role'] != 'admin'): ?>
<!-- Under Maintenance -->
<div class="container-xxl d-flex align-items-center justify-content-center min-vh-100 p-0" style="margin-top: -80px;">
    <div class="text-center">
        <h2 class="mb-2">Site en Construction!</h2>
<p class="mb-4">Nous sommes actuellement en train de construire quelque chose de nouveau. Merci de votre patience!</p>

        
        <div class="mt-4">
            <img
                src="../assets/img/illustrations/girl-doing-yoga-light.png"
                alt="girl-doing-yoga-light"
                width="500"
                class="img-fluid"
                data-app-dark-img="illustrations/girl-doing-yoga-dark.png"
                data-app-light-img="illustrations/girl-doing-yoga-light.png"
            />
        </div>
    </div>
</div>
<!-- /Under Maintenance -->
<?php endif; ?>


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
    <script src="../assets/vendor/libs/jquery/jquery.js"></script>
    <script src="../assets/vendor/libs/popper/popper.js"></script>
    <script src="../assets/vendor/js/bootstrap.js"></script>
    <script src="../assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>
    <script src="../assets/vendor/js/menu.js"></script>

    <!-- Vendors JS -->
    <script src="../assets/vendor/libs/apex-charts/apexcharts.js"></script>

    <!-- Main JS -->
    <script src="../assets/js/main.js"></script>

    <!-- Page JS -->
    <script src="../assets/js/dashboards-analytics.js"></script>
<script>
        // Injection des données PHP dans le JavaScript
        const chartData = {
            termine: <?php echo json_encode($chartData['termine']); ?>,
            en_cours: <?php echo json_encode($chartData['en_cours']); ?>,
            en_attente: <?php echo json_encode($chartData['en_attente']); ?>,
            T1: <?php echo json_encode($chartData['t1'] !== null ? $chartData['t1'] : 0); ?> ,// Remplace 'null' par 0 si nécessaire
            Bloque: <?php echo json_encode($chartData['bloque'] !== null ? $chartData['bloque'] : 0); ?> // Remplace 'null' par 0 si nécessaire
        };

        console.log('Chart Data:', chartData); // Vérifiez ici que les données s’affichent correctement

        // Configuration du graphique
        const productionChartConfig = {
            chart: {
                height: 165,
                width: 130,
                type: 'donut'
            },
            labels: ['Terminé', 'En cours', 'En attente', 'T1','Bloque'],
            series: [chartData.termine, chartData.en_cours, chartData.en_attente, chartData.T1, chartData.Bloque],
            colors: [config.colors.success, config.colors.warning, config.colors.secondary, config.colors.info, config.colors.danger],
            stroke: {
                width: 5,
                colors: ['#FFFFFF']
            },
            dataLabels: {
                enabled: false,
                formatter: function (val) {
                    return parseInt(val) ;
                }
            },
            legend: {
                show: false
            },
            grid: {
                padding: {
                    top: 0,
                    bottom: 0,
                    right: 15
                }
            },
            plotOptions: {
                pie: {
                    donut: {
                        size: '75%',
                        labels: {
                            show: true,
                            value: {
                                fontSize: '1.5rem',
                                fontFamily: 'Public Sans',
                                color: '#5E5873',
                                offsetY: -15,
                                formatter: function (val) {
                                    return parseInt(val);
                                }
                            },
                            name: {
                                offsetY: 20,
                                fontFamily: 'Public Sans'
                            },
                            total: {
                                show: true,
                                fontSize: '0.8125rem',
                                color: '#b9b9c3',
                                label: 'Total',
                                formatter: function (w) {
                                    return w.globals.seriesTotals.reduce((a, b) => a + b, 0);
                                }
                            }
                        }
                    }
                }
            }
        };

        // Création et affichage du graphique
        document.addEventListener('DOMContentLoaded', function () {
            const chartElement = document.querySelector('#statistiqueProduction');
            if (chartElement !== null) {
                const productionChart = new ApexCharts(chartElement, productionChartConfig);
                productionChart.render();
            } else {
                console.error("L'élément #statistiqueProduction n'a pas été trouvé dans le DOM.");
            }
        });
    </script>
	
 <script>
    // S'assurer que le DOM est entièrement chargé avant d'exécuter le script
    document.addEventListener('DOMContentLoaded', function () {
      // Sélection de l'élément du graphique
      const weeklyRevenueEl = document.querySelector('#FactureCoupe');
      
      // Configuration du graphique à barres
      const weeklyRevenueConfig = {
        series: [{
          name: 'Revenue',
          data: [76, 85, 101, 98, 87] // Valeurs des revenus
        }],
        chart: {
          type: 'bar', // Type "bar" pour les barres
          height: 100,
          width: 150, // Hauteur de l'élément
          sparkline: { enabled: true }, // Désactive la grille et les axes
        },
        plotOptions: {
          bar: {
                barHeight: "80%",
                columnWidth: "75%",
                startingShape: "rounded",
                endingShape: "rounded",
                borderRadius: 4,
                distributed: !0
            }
        },
        dataLabels: {
          enabled: false, // Désactiver l'affichage des valeurs sur les barres
        },
        xaxis: {
          categories: [], // Pas de catégories
          axisBorder: { show: false }, // Pas de bordure sur l'axe des X
          axisTicks: { show: false }, // Pas de ticks sur l'axe des X
        },
        yaxis: {
          labels: { show: false }, // Pas d'étiquettes sur l'axe des Y
          axisBorder: { show: false }, // Pas de bordure sur l'axe des Y
          axisTicks: { show: false }, // Pas de ticks sur l'axe des Y
        },
        grid: {
            show: !1,
            padding: {
                top: -20,
                bottom: -12,
                left: -10,
                right: 0
            }
        },
        fill: {
          colors: ['#e1e2ff'], // Couleur des barres
        },
        
      };

      // Vérifier que l'élément existe avant de rendre le graphique
      if (weeklyRevenueEl) {
        const weeklyRevenue = new ApexCharts(weeklyRevenueEl, weeklyRevenueConfig);
        weeklyRevenue.render();
      }
    });
  </script>
<script>
  // S'assurer que le DOM est entièrement chargé avant d'exécuter le script
  document.addEventListener('DOMContentLoaded', function () {
    // Sélection de l'élément du graphique
    const FacturePlotterEl = document.querySelector('#FacturePlotter');
    
    // Définir les couleurs (Assurez-vous que config.colors.success et r sont définis ailleurs)
    const config = {
      colors: {
        success: "#8DE45F"  // Remplacez par la couleur de votre choix
      }
    };
    const r = "#333";  // Remplacez par la couleur des étiquettes de votre choix
    
    // Configuration du graphique à barres
    const FacturePlotterConfig = {
      chart: {
        height: 100,
        width: 220,
        parentHeightOffset: 0,
        toolbar: {
          show: false
        },
        type: "area"
      },
      dataLabels: {
        enabled: false
      },
      stroke: {
        width: 2,
        curve: "smooth"
      },
      series: [{
        data: [15, 22, 17, 40, 12, 35, 25]
      }],
      colors: [config.colors.success],
      fill: {
        type: "gradient",
        gradient: {
          shade: "light",  // "light" ou "dark" selon l'effet souhaité
          shadeIntensity: 0.8,
          opacityFrom: 0.8,
          opacityTo: 0.25,
          stops: [0, 85, 100]
        }
      },
      grid: {
        show: false,
        padding: {
          top: -20,
          bottom: -8
        }
      },
      legend: {
        show: false
      },
       xaxis: {
        axisBorder: {
          show: false
        },
        axisTicks: {
          show: false
        },
        labels: {
          show: false  // Désactive les étiquettes de l'axe x
        }
      },
      yaxis: {
        labels: {
          show: false
        }
      }
    };

    // Vérifier que l'élément existe avant de rendre le graphique
    if (FacturePlotterEl) {
      const FacturePlotterE = new ApexCharts(FacturePlotterEl, FacturePlotterConfig);
      FacturePlotterE.render();
    }
  });
</script>

  <script src="../../assets/js/cards-statistics.js"></script>
    <!-- GitHub Buttons -->
    <script async defer src="https://buttons.github.io/buttons.js"></script>
</body>
</html>
