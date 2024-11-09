<?php
// Sécurité : Ajoutez ces en-têtes avant toute sortie
header('X-Content-Type-Options: nosniff'); // Empêche le sniffing du contenu MIME
header('X-Frame-Options: DENY'); // Bloque le chargement de la page dans un iframe
header('X-XSS-Protection: 1; mode=block'); // Active la protection contre les attaques XSS dans certains navigateurs
header('Strict-Transport-Security: max-age=31536000; includeSubDomains'); // Force l'utilisation de HTTPS
header("Content-Security-Policy: default-src 'self'; script-src 'self' 'unsafe-inline' 'unsafe-eval'; style-src 'self' 'unsafe-inline';");

session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../../index.php");
    exit();
}
?>
<!DOCTYPE html>


<html lang="en" class="light-style layout-navbar-fixed layout-menu-fixed layout-compact " dir="ltr" data-theme="theme-default" data-assets-path="../assets/" data-template="vertical-menu-template" data-style="light">

  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    <title>Demo : User View - Pages | sneat - Bootstrap Dashboard PRO</title>

    
    <meta name="description" content="Most Powerful &amp; Comprehensive Bootstrap 5 Admin Dashboard built for developers!" />
    <meta name="keywords" content="dashboard, bootstrap 5 dashboard, bootstrap 5 design, bootstrap 5">
    <!-- Canonical SEO -->
    <link rel="canonical" href="https://themeselection.com/item/sneat-dashboard-pro-bootstrap/">
    
    

    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="../assets/img/favicon/favicon.ico" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    

    <!-- Icons -->
    <link rel="stylesheet" href="../assets/vendor/fonts/boxicons.css" />
    <link rel="stylesheet" href="../assets/vendor/fonts/fontawesome.css" />
    <link rel="stylesheet" href="../assets/vendor/fonts/flag-icons.css" />

    <!-- Core CSS -->
    <link rel="stylesheet" href="../assets/vendor/css/core.css" class="template-customizer-core-css" />
    <link rel="stylesheet" href="../assets/vendor/css/theme-default.css" class="template-customizer-theme-css" />
    <link rel="stylesheet" href="../assets/css/demo.css" />
    
    <!-- Vendors CSS -->
    <link rel="stylesheet" href="../assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />
    <link rel="stylesheet" href="../assets/vendor/libs/typeahead-js/typeahead.css" /> 
    <link rel="stylesheet" href="../assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css">
<link rel="stylesheet" href="../assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css">
<link rel="stylesheet" href="../assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.css">
<link rel="stylesheet" href="../assets/vendor/libs/animate-css/animate.css" />
<link rel="stylesheet" href="../assets/vendor/libs/sweetalert2/sweetalert2.css" />
<link rel="stylesheet" href="../assets/vendor/libs/select2/select2.css" />
<link rel="stylesheet" href="../assets/vendor/libs/@form-validation/form-validation.css" />

    <!-- Page CSS -->
    
<link rel="stylesheet" href="../assets/vendor/css/pages/page-user-view.css" />

    <!-- Helpers -->
    <script src="../assets/vendor/js/helpers.js"></script>
    <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->
    <!--? Template customizer: To hide customizer set displayCustomizer value false in config.js.  -->
    <script src="../assets/vendor/js/template-customizer.js"></script>
    <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
    <script src="../assets/js/config.js"></script>
    
</head>

<body>

  
  <!-- ?PROD Only: Google Tag Manager (noscript) (Default ThemeSelection: GTM-5DDHKGP, PixInvent: GTM-5J3LMKC) -->
  <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-5DDHKGP" height="0" width="0" style="display: none; visibility: hidden"></iframe></noscript>
  <!-- End Google Tag Manager (noscript) -->
  
  <!-- Layout wrapper -->
<div class="layout-wrapper layout-content-navbar  ">
  <div class="layout-container">

    
    




<?php include 'menu.php'; ?>

    

    <!-- Layout container -->
    <div class="layout-page">
      
      



<?php include 'navbar.php'; ?>

      

      <!-- Content wrapper -->
      <div class="content-wrapper">

        <!-- Content -->
        
          <div class="container-xxl flex-grow-1 container-p-y">
            
            
<div class="row">
  <!-- User Sidebar -->
  
  <div class="col-md-6">
    <div class="card h-100">
      <div class="card-body row widget-separator g-0">
        <div class="col-sm-5 border-shift border-end pe-sm-6">
          <h3 class="text-primary d-flex align-items-center gap-2 mb-2">4.89<i class="bx bxs-star bx-30px"></i></h3>
          <p class="h6 mb-2">Total 187 reviews</p>
          <p class="pe-2 mb-2">All reviews are from genuine customers</p>
          <span class="badge bg-label-primary mb-4 mb-sm-0">+5 This week</span>
          <hr class="d-sm-none">
        </div>

        <div class="col-sm-7 gap-2 text-nowrap d-flex flex-column justify-content-between ps-sm-6 pt-2 py-sm-2">
          <div class="d-flex align-items-center gap-2">
            <small>5 Star</small>
            <div class="progress w-100 bg-label-primary" style="height:8px;">
              <div class="progress-bar bg-primary" role="progressbar" style="width: 85%" aria-valuenow="61.50" aria-valuemin="0" aria-valuemax="100"></div>
            </div>
            <small class="w-px-20 text-end">124</small>
          </div>
          <div class="d-flex align-items-center gap-2">
            <small>4 Star</small>
            <div class="progress w-100 bg-label-primary" style="height:8px;">
              <div class="progress-bar bg-primary" role="progressbar" style="width: 50%" aria-valuenow="24" aria-valuemin="0" aria-valuemax="100"></div>
            </div>
            <small class="w-px-20 text-end">40</small>
          </div>
          <div class="d-flex align-items-center gap-2">
            <small>3 Star</small>
            <div class="progress w-100 bg-label-primary" style="height:8px;">
              <div class="progress-bar bg-primary" role="progressbar" style="width: 35%" aria-valuenow="12" aria-valuemin="0" aria-valuemax="100"></div>
            </div>
            <small class="w-px-20 text-end">12</small>
          </div>
          <div class="d-flex align-items-center gap-2">
            <small>2 Star</small>
            <div class="progress w-100 bg-label-primary" style="height:8px;">
              <div class="progress-bar bg-primary" role="progressbar" style="width: 18%" aria-valuenow="7" aria-valuemin="0" aria-valuemax="100"></div>
            </div>
            <small class="w-px-20 text-end">7</small>
          </div>
          <div class="d-flex align-items-center gap-2">
            <small>1 Star</small>
            <div class="progress w-100 bg-label-primary" style="height:8px;">
              <div class="progress-bar bg-primary" role="progressbar" style="width: 10%" aria-valuenow="2" aria-valuemin="0" aria-valuemax="100"></div>
            </div>
            <small class="w-px-20 text-end">2</small>
          </div>

        </div>
      </div>
    </div>
  </div>
  <div class="col-md-6">
    <div class="card h-100">
      <div class="card-body row">
        <div class="col-sm-5">
          <div class="mb-12">
            <h5 class="mb-2 text-nowrap">Reviews statistics</h5>
            <p class="mb-0"> <span class="me-2">12 New reviews</span> <span class="badge bg-label-success">+8.4%</span></p>
          </div>

          <div>
            <h6 class="mb-2 fw-normal">
              <span class="text-success me-1">87%</span>Positive reviews
            </h6>
            <small>Weekly Report</small>
          </div>
        </div>
        <div class="col-sm-7 d-flex justify-content-sm-end align-items-end" style="position: relative;">
          <div id="reviewsChart" style="min-height: 165px;"><div id="apexcharts2irdbbaw" class="apexcharts-canvas apexcharts2irdbbaw apexcharts-theme-light" style="width: 190px; height: 150px;"><svg id="SvgjsSvg1232" width="190" height="150" xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:svgjs="http://svgjs.dev" class="apexcharts-svg" xmlns:data="ApexChartsNS" transform="translate(0, 0)" style="background: transparent;"><g id="SvgjsG1234" class="apexcharts-inner apexcharts-graphical" transform="translate(22, 5)"><defs id="SvgjsDefs1233"><linearGradient id="SvgjsLinearGradient1237" x1="0" y1="0" x2="0" y2="1"><stop id="SvgjsStop1238" stop-opacity="0.4" stop-color="rgba(216,227,240,0.4)" offset="0"></stop><stop id="SvgjsStop1239" stop-opacity="0.5" stop-color="rgba(190,209,230,0.5)" offset="1"></stop><stop id="SvgjsStop1240" stop-opacity="0.5" stop-color="rgba(190,209,230,0.5)" offset="1"></stop></linearGradient><clipPath id="gridRectMask2irdbbaw"><rect id="SvgjsRect1242" width="162" height="117.72999999999999" x="-2" y="0" rx="0" ry="0" opacity="1" stroke-width="0" stroke="none" stroke-dasharray="0" fill="#fff"></rect></clipPath><clipPath id="forecastMask2irdbbaw"></clipPath><clipPath id="nonForecastMask2irdbbaw"></clipPath><clipPath id="gridRectMarkerMask2irdbbaw"><rect id="SvgjsRect1243" width="162" height="121.72999999999999" x="-2" y="-2" rx="0" ry="0" opacity="1" stroke-width="0" stroke="none" stroke-dasharray="0" fill="#fff"></rect></clipPath></defs><rect id="SvgjsRect1241" width="9.028571428571428" height="117.72999999999999" x="119.42856794084821" y="0" rx="0" ry="0" opacity="1" stroke-width="0" stroke-dasharray="3" fill="url(#SvgjsLinearGradient1237)" class="apexcharts-xcrosshairs" y2="117.72999999999999" filter="none" fill-opacity="0.9" x1="119.42856794084821" x2="119.42856794084821"></rect><g id="SvgjsG1262" class="apexcharts-xaxis" transform="translate(0, 0)"><g id="SvgjsG1263" class="apexcharts-xaxis-texts-g" transform="translate(0, -4)"><text id="SvgjsText1265" font-family="Helvetica, Arial, sans-serif" x="11.285714285714286" y="146.73" text-anchor="middle" dominant-baseline="auto" font-size="13px" font-weight="400" fill="#a7acb2" class="apexcharts-text apexcharts-xaxis-label " style="font-family: Helvetica, Arial, sans-serif;"><tspan id="SvgjsTspan1266">M</tspan><title>M</title></text><text id="SvgjsText1268" font-family="Helvetica, Arial, sans-serif" x="33.85714285714286" y="146.73" text-anchor="middle" dominant-baseline="auto" font-size="13px" font-weight="400" fill="#a7acb2" class="apexcharts-text apexcharts-xaxis-label " style="font-family: Helvetica, Arial, sans-serif;"><tspan id="SvgjsTspan1269">T</tspan><title>T</title></text><text id="SvgjsText1271" font-family="Helvetica, Arial, sans-serif" x="56.42857142857144" y="146.73" text-anchor="middle" dominant-baseline="auto" font-size="13px" font-weight="400" fill="#a7acb2" class="apexcharts-text apexcharts-xaxis-label " style="font-family: Helvetica, Arial, sans-serif;"><tspan id="SvgjsTspan1272">W</tspan><title>W</title></text><text id="SvgjsText1274" font-family="Helvetica, Arial, sans-serif" x="79" y="146.73" text-anchor="middle" dominant-baseline="auto" font-size="13px" font-weight="400" fill="#a7acb2" class="apexcharts-text apexcharts-xaxis-label " style="font-family: Helvetica, Arial, sans-serif;"><tspan id="SvgjsTspan1275">T</tspan><title>T</title></text><text id="SvgjsText1277" font-family="Helvetica, Arial, sans-serif" x="101.57142857142857" y="146.73" text-anchor="middle" dominant-baseline="auto" font-size="13px" font-weight="400" fill="#a7acb2" class="apexcharts-text apexcharts-xaxis-label " style="font-family: Helvetica, Arial, sans-serif;"><tspan id="SvgjsTspan1278">F</tspan><title>F</title></text><text id="SvgjsText1280" font-family="Helvetica, Arial, sans-serif" x="124.14285714285715" y="146.73" text-anchor="middle" dominant-baseline="auto" font-size="13px" font-weight="400" fill="#a7acb2" class="apexcharts-text apexcharts-xaxis-label " style="font-family: Helvetica, Arial, sans-serif;"><tspan id="SvgjsTspan1281">S</tspan><title>S</title></text><text id="SvgjsText1283" font-family="Helvetica, Arial, sans-serif" x="146.71428571428575" y="146.73" text-anchor="middle" dominant-baseline="auto" font-size="13px" font-weight="400" fill="#a7acb2" class="apexcharts-text apexcharts-xaxis-label " style="font-family: Helvetica, Arial, sans-serif;"><tspan id="SvgjsTspan1284">S</tspan><title>S</title></text></g></g><g id="SvgjsG1287" class="apexcharts-grid"><g id="SvgjsG1288" class="apexcharts-gridlines-horizontal" style="display: none;"><line id="SvgjsLine1290" x1="0" y1="0" x2="158" y2="0" stroke="#e0e0e0" stroke-dasharray="0" stroke-linecap="butt" class="apexcharts-gridline"></line><line id="SvgjsLine1291" x1="0" y1="29.432499999999997" x2="158" y2="29.432499999999997" stroke="#e0e0e0" stroke-dasharray="0" stroke-linecap="butt" class="apexcharts-gridline"></line><line id="SvgjsLine1292" x1="0" y1="58.864999999999995" x2="158" y2="58.864999999999995" stroke="#e0e0e0" stroke-dasharray="0" stroke-linecap="butt" class="apexcharts-gridline"></line><line id="SvgjsLine1293" x1="0" y1="88.29749999999999" x2="158" y2="88.29749999999999" stroke="#e0e0e0" stroke-dasharray="0" stroke-linecap="butt" class="apexcharts-gridline"></line><line id="SvgjsLine1294" x1="0" y1="117.72999999999999" x2="158" y2="117.72999999999999" stroke="#e0e0e0" stroke-dasharray="0" stroke-linecap="butt" class="apexcharts-gridline"></line></g><g id="SvgjsG1289" class="apexcharts-gridlines-vertical" style="display: none;"></g><line id="SvgjsLine1296" x1="0" y1="117.72999999999999" x2="158" y2="117.72999999999999" stroke="transparent" stroke-dasharray="0" stroke-linecap="butt"></line><line id="SvgjsLine1295" x1="0" y1="1" x2="0" y2="117.72999999999999" stroke="transparent" stroke-dasharray="0" stroke-linecap="butt"></line></g><g id="SvgjsG1244" class="apexcharts-bar-series apexcharts-plot-series"><g id="SvgjsG1245" class="apexcharts-series" rel="1" seriesName="seriesx1" data:realIndex="0"><path id="SvgjsPath1249" d="M 6.771428571428572 111.72999999999999L 6.771428571428572 104.10833333333332Q 6.771428571428572 98.10833333333332 12.771428571428572 98.10833333333332L 9.8 98.10833333333332Q 15.8 98.10833333333332 15.8 104.10833333333332L 15.8 104.10833333333332L 15.8 111.72999999999999Q 15.8 117.72999999999999 9.8 117.72999999999999L 12.771428571428572 117.72999999999999Q 6.771428571428572 117.72999999999999 6.771428571428572 111.72999999999999z" fill="#71dd3729" fill-opacity="1" stroke-opacity="1" stroke-linecap="round" stroke-width="0" stroke-dasharray="0" class="apexcharts-bar-area" index="0" clip-path="url(#gridRectMask2irdbbaw)" pathTo="M 6.771428571428572 111.72999999999999L 6.771428571428572 104.10833333333332Q 6.771428571428572 98.10833333333332 12.771428571428572 98.10833333333332L 9.8 98.10833333333332Q 15.8 98.10833333333332 15.8 104.10833333333332L 15.8 104.10833333333332L 15.8 111.72999999999999Q 15.8 117.72999999999999 9.8 117.72999999999999L 12.771428571428572 117.72999999999999Q 6.771428571428572 117.72999999999999 6.771428571428572 111.72999999999999z" pathFrom="M 6.771428571428572 111.72999999999999L 6.771428571428572 111.72999999999999L 15.8 111.72999999999999L 15.8 111.72999999999999L 15.8 111.72999999999999L 15.8 111.72999999999999L 15.8 111.72999999999999L 6.771428571428572 111.72999999999999" cy="98.10833333333332" cx="29.342857142857145" j="0" val="20" barHeight="19.621666666666666" barWidth="9.028571428571428"></path><path id="SvgjsPath1251" d="M 29.342857142857145 111.72999999999999L 29.342857142857145 84.48666666666665Q 29.342857142857145 78.48666666666665 35.34285714285714 78.48666666666665L 32.371428571428574 78.48666666666665Q 38.371428571428574 78.48666666666665 38.371428571428574 84.48666666666665L 38.371428571428574 84.48666666666665L 38.371428571428574 111.72999999999999Q 38.371428571428574 117.72999999999999 32.371428571428574 117.72999999999999L 35.34285714285714 117.72999999999999Q 29.342857142857145 117.72999999999999 29.342857142857145 111.72999999999999z" fill="#71dd3729" fill-opacity="1" stroke-opacity="1" stroke-linecap="round" stroke-width="0" stroke-dasharray="0" class="apexcharts-bar-area" index="0" clip-path="url(#gridRectMask2irdbbaw)" pathTo="M 29.342857142857145 111.72999999999999L 29.342857142857145 84.48666666666665Q 29.342857142857145 78.48666666666665 35.34285714285714 78.48666666666665L 32.371428571428574 78.48666666666665Q 38.371428571428574 78.48666666666665 38.371428571428574 84.48666666666665L 38.371428571428574 84.48666666666665L 38.371428571428574 111.72999999999999Q 38.371428571428574 117.72999999999999 32.371428571428574 117.72999999999999L 35.34285714285714 117.72999999999999Q 29.342857142857145 117.72999999999999 29.342857142857145 111.72999999999999z" pathFrom="M 29.342857142857145 111.72999999999999L 29.342857142857145 111.72999999999999L 38.371428571428574 111.72999999999999L 38.371428571428574 111.72999999999999L 38.371428571428574 111.72999999999999L 38.371428571428574 111.72999999999999L 38.371428571428574 111.72999999999999L 29.342857142857145 111.72999999999999" cy="78.48666666666665" cx="51.91428571428572" j="1" val="40" barHeight="39.24333333333333" barWidth="9.028571428571428"></path><path id="SvgjsPath1253" d="M 51.91428571428572 111.72999999999999L 51.91428571428572 64.86499999999998Q 51.91428571428572 58.86499999999998 57.91428571428572 58.86499999999998L 54.94285714285715 58.86499999999998Q 60.94285714285715 58.86499999999998 60.94285714285715 64.86499999999998L 60.94285714285715 64.86499999999998L 60.94285714285715 111.72999999999999Q 60.94285714285715 117.72999999999999 54.94285714285715 117.72999999999999L 57.91428571428572 117.72999999999999Q 51.91428571428572 117.72999999999999 51.91428571428572 111.72999999999999z" fill="#71dd3729" fill-opacity="1" stroke-opacity="1" stroke-linecap="round" stroke-width="0" stroke-dasharray="0" class="apexcharts-bar-area" index="0" clip-path="url(#gridRectMask2irdbbaw)" pathTo="M 51.91428571428572 111.72999999999999L 51.91428571428572 64.86499999999998Q 51.91428571428572 58.86499999999998 57.91428571428572 58.86499999999998L 54.94285714285715 58.86499999999998Q 60.94285714285715 58.86499999999998 60.94285714285715 64.86499999999998L 60.94285714285715 64.86499999999998L 60.94285714285715 111.72999999999999Q 60.94285714285715 117.72999999999999 54.94285714285715 117.72999999999999L 57.91428571428572 117.72999999999999Q 51.91428571428572 117.72999999999999 51.91428571428572 111.72999999999999z" pathFrom="M 51.91428571428572 111.72999999999999L 51.91428571428572 111.72999999999999L 60.94285714285715 111.72999999999999L 60.94285714285715 111.72999999999999L 60.94285714285715 111.72999999999999L 60.94285714285715 111.72999999999999L 60.94285714285715 111.72999999999999L 51.91428571428572 111.72999999999999" cy="58.86499999999999" cx="74.4857142857143" j="2" val="60" barHeight="58.865" barWidth="9.028571428571428"></path><path id="SvgjsPath1255" d="M 74.4857142857143 111.72999999999999L 74.4857142857143 45.243333333333325Q 74.4857142857143 39.243333333333325 80.4857142857143 39.243333333333325L 77.51428571428572 39.243333333333325Q 83.51428571428572 39.243333333333325 83.51428571428572 45.243333333333325L 83.51428571428572 45.243333333333325L 83.51428571428572 111.72999999999999Q 83.51428571428572 117.72999999999999 77.51428571428572 117.72999999999999L 80.4857142857143 117.72999999999999Q 74.4857142857143 117.72999999999999 74.4857142857143 111.72999999999999z" fill="#71dd3729" fill-opacity="1" stroke-opacity="1" stroke-linecap="round" stroke-width="0" stroke-dasharray="0" class="apexcharts-bar-area" index="0" clip-path="url(#gridRectMask2irdbbaw)" pathTo="M 74.4857142857143 111.72999999999999L 74.4857142857143 45.243333333333325Q 74.4857142857143 39.243333333333325 80.4857142857143 39.243333333333325L 77.51428571428572 39.243333333333325Q 83.51428571428572 39.243333333333325 83.51428571428572 45.243333333333325L 83.51428571428572 45.243333333333325L 83.51428571428572 111.72999999999999Q 83.51428571428572 117.72999999999999 77.51428571428572 117.72999999999999L 80.4857142857143 117.72999999999999Q 74.4857142857143 117.72999999999999 74.4857142857143 111.72999999999999z" pathFrom="M 74.4857142857143 111.72999999999999L 74.4857142857143 111.72999999999999L 83.51428571428572 111.72999999999999L 83.51428571428572 111.72999999999999L 83.51428571428572 111.72999999999999L 83.51428571428572 111.72999999999999L 83.51428571428572 111.72999999999999L 74.4857142857143 111.72999999999999" cy="39.243333333333325" cx="97.05714285714286" j="3" val="80" barHeight="78.48666666666666" barWidth="9.028571428571428"></path><path id="SvgjsPath1257" d="M 97.05714285714286 111.72999999999999L 97.05714285714286 25.621666666666655Q 97.05714285714286 19.621666666666655 103.05714285714286 19.621666666666655L 100.08571428571429 19.621666666666655Q 106.08571428571429 19.621666666666655 106.08571428571429 25.621666666666655L 106.08571428571429 25.621666666666655L 106.08571428571429 111.72999999999999Q 106.08571428571429 117.72999999999999 100.08571428571429 117.72999999999999L 103.05714285714286 117.72999999999999Q 97.05714285714286 117.72999999999999 97.05714285714286 111.72999999999999z" fill="rgba(113,221,55,0.85)" fill-opacity="1" stroke-opacity="1" stroke-linecap="round" stroke-width="0" stroke-dasharray="0" class="apexcharts-bar-area" index="0" clip-path="url(#gridRectMask2irdbbaw)" pathTo="M 97.05714285714286 111.72999999999999L 97.05714285714286 25.621666666666655Q 97.05714285714286 19.621666666666655 103.05714285714286 19.621666666666655L 100.08571428571429 19.621666666666655Q 106.08571428571429 19.621666666666655 106.08571428571429 25.621666666666655L 106.08571428571429 25.621666666666655L 106.08571428571429 111.72999999999999Q 106.08571428571429 117.72999999999999 100.08571428571429 117.72999999999999L 103.05714285714286 117.72999999999999Q 97.05714285714286 117.72999999999999 97.05714285714286 111.72999999999999z" pathFrom="M 97.05714285714286 111.72999999999999L 97.05714285714286 111.72999999999999L 106.08571428571429 111.72999999999999L 106.08571428571429 111.72999999999999L 106.08571428571429 111.72999999999999L 106.08571428571429 111.72999999999999L 106.08571428571429 111.72999999999999L 97.05714285714286 111.72999999999999" cy="19.621666666666655" cx="119.62857142857143" j="4" val="100" barHeight="98.10833333333333" barWidth="9.028571428571428"></path><path id="SvgjsPath1259" d="M 119.62857142857143 111.72999999999999L 119.62857142857143 45.243333333333325Q 119.62857142857143 39.243333333333325 125.62857142857143 39.243333333333325L 122.65714285714287 39.243333333333325Q 128.65714285714287 39.243333333333325 128.65714285714287 45.243333333333325L 128.65714285714287 45.243333333333325L 128.65714285714287 111.72999999999999Q 128.65714285714287 117.72999999999999 122.65714285714287 117.72999999999999L 125.62857142857143 117.72999999999999Q 119.62857142857143 117.72999999999999 119.62857142857143 111.72999999999999z" fill="#71dd3729" fill-opacity="1" stroke-opacity="1" stroke-linecap="round" stroke-width="0" stroke-dasharray="0" class="apexcharts-bar-area" index="0" clip-path="url(#gridRectMask2irdbbaw)" pathTo="M 119.62857142857143 111.72999999999999L 119.62857142857143 45.243333333333325Q 119.62857142857143 39.243333333333325 125.62857142857143 39.243333333333325L 122.65714285714287 39.243333333333325Q 128.65714285714287 39.243333333333325 128.65714285714287 45.243333333333325L 128.65714285714287 45.243333333333325L 128.65714285714287 111.72999999999999Q 128.65714285714287 117.72999999999999 122.65714285714287 117.72999999999999L 125.62857142857143 117.72999999999999Q 119.62857142857143 117.72999999999999 119.62857142857143 111.72999999999999z" pathFrom="M 119.62857142857143 111.72999999999999L 119.62857142857143 111.72999999999999L 128.65714285714287 111.72999999999999L 128.65714285714287 111.72999999999999L 128.65714285714287 111.72999999999999L 128.65714285714287 111.72999999999999L 128.65714285714287 111.72999999999999L 119.62857142857143 111.72999999999999" cy="39.243333333333325" cx="142.20000000000002" j="5" val="80" barHeight="78.48666666666666" barWidth="9.028571428571428"></path><path id="SvgjsPath1261" d="M 142.20000000000002 111.72999999999999L 142.20000000000002 64.86499999999998Q 142.20000000000002 58.86499999999998 148.20000000000002 58.86499999999998L 145.22857142857146 58.86499999999998Q 151.22857142857146 58.86499999999998 151.22857142857146 64.86499999999998L 151.22857142857146 64.86499999999998L 151.22857142857146 111.72999999999999Q 151.22857142857146 117.72999999999999 145.22857142857146 117.72999999999999L 148.20000000000002 117.72999999999999Q 142.20000000000002 117.72999999999999 142.20000000000002 111.72999999999999z" fill="#71dd3729" fill-opacity="1" stroke-opacity="1" stroke-linecap="round" stroke-width="0" stroke-dasharray="0" class="apexcharts-bar-area" index="0" clip-path="url(#gridRectMask2irdbbaw)" pathTo="M 142.20000000000002 111.72999999999999L 142.20000000000002 64.86499999999998Q 142.20000000000002 58.86499999999998 148.20000000000002 58.86499999999998L 145.22857142857146 58.86499999999998Q 151.22857142857146 58.86499999999998 151.22857142857146 64.86499999999998L 151.22857142857146 64.86499999999998L 151.22857142857146 111.72999999999999Q 151.22857142857146 117.72999999999999 145.22857142857146 117.72999999999999L 148.20000000000002 117.72999999999999Q 142.20000000000002 117.72999999999999 142.20000000000002 111.72999999999999z" pathFrom="M 142.20000000000002 111.72999999999999L 142.20000000000002 111.72999999999999L 151.22857142857146 111.72999999999999L 151.22857142857146 111.72999999999999L 151.22857142857146 111.72999999999999L 151.22857142857146 111.72999999999999L 151.22857142857146 111.72999999999999L 142.20000000000002 111.72999999999999" cy="58.86499999999999" cx="164.7714285714286" j="6" val="60" barHeight="58.865" barWidth="9.028571428571428"></path><g id="SvgjsG1247" class="apexcharts-bar-goals-markers" style="pointer-events: none"><g id="SvgjsG1248" className="apexcharts-bar-goals-groups"></g><g id="SvgjsG1250" className="apexcharts-bar-goals-groups"></g><g id="SvgjsG1252" className="apexcharts-bar-goals-groups"></g><g id="SvgjsG1254" className="apexcharts-bar-goals-groups"></g><g id="SvgjsG1256" className="apexcharts-bar-goals-groups"></g><g id="SvgjsG1258" className="apexcharts-bar-goals-groups"></g><g id="SvgjsG1260" className="apexcharts-bar-goals-groups"></g></g></g><g id="SvgjsG1246" class="apexcharts-datalabels" data:realIndex="0"></g></g><line id="SvgjsLine1297" x1="0" y1="0" x2="158" y2="0" stroke="#b6b6b6" stroke-dasharray="0" stroke-width="1" stroke-linecap="butt" class="apexcharts-ycrosshairs"></line><line id="SvgjsLine1298" x1="0" y1="0" x2="158" y2="0" stroke-dasharray="0" stroke-width="0" stroke-linecap="butt" class="apexcharts-ycrosshairs-hidden"></line><g id="SvgjsG1299" class="apexcharts-yaxis-annotations"></g><g id="SvgjsG1300" class="apexcharts-xaxis-annotations"></g><g id="SvgjsG1301" class="apexcharts-point-annotations"></g></g><g id="SvgjsG1285" class="apexcharts-yaxis" rel="0" transform="translate(-8, 0)"><g id="SvgjsG1286" class="apexcharts-yaxis-texts-g"></g></g><g id="SvgjsG1235" class="apexcharts-annotations"></g></svg><div class="apexcharts-legend" style="max-height: 75px;"></div><div class="apexcharts-tooltip apexcharts-theme-light" style="left: 33.646px; top: 10.5px;"><div class="apexcharts-tooltip-title" style="font-family: Helvetica, Arial, sans-serif; font-size: 12px;">S</div><div class="apexcharts-tooltip-series-group apexcharts-active" style="order: 1; display: flex;"><span class="apexcharts-tooltip-marker" style="background-color: rgba(113, 221, 55, 0.16);"></span><div class="apexcharts-tooltip-text" style="font-family: Helvetica, Arial, sans-serif; font-size: 12px;"><div class="apexcharts-tooltip-y-group"><span class="apexcharts-tooltip-text-y-label">series-1: </span><span class="apexcharts-tooltip-text-y-value">80</span></div><div class="apexcharts-tooltip-goals-group"><span class="apexcharts-tooltip-text-goals-label"></span><span class="apexcharts-tooltip-text-goals-value"></span></div><div class="apexcharts-tooltip-z-group"><span class="apexcharts-tooltip-text-z-label"></span><span class="apexcharts-tooltip-text-z-value"></span></div></div></div></div><div class="apexcharts-yaxistooltip apexcharts-yaxistooltip-0 apexcharts-yaxistooltip-left apexcharts-theme-light"><div class="apexcharts-yaxistooltip-text"></div></div></div></div>
        <div class="resize-triggers"><div class="expand-trigger"><div style="width: 283px; height: 180px;"></div></div><div class="contract-trigger"></div></div></div>

      </div>
    </div>
  </div>
</div>
  <div class="col-xl-4 col-lg-5 order-1 order-md-0">
    <!-- User Card -->
    <div class="card mb-6">
      <div class="card-body pt-12">
        <div class="user-avatar-section">
          <div class=" d-flex align-items-center flex-column">
            <img class="img-fluid rounded mb-4" src="../assets/img/avatars/1.png" height="120" width="120" alt="User avatar" />
            <div class="user-info text-center">
              <h5>Violet Mendoza</h5>
              <span class="badge bg-label-secondary">Author</span>
            </div>
          </div>
        </div>
        <div class="d-flex justify-content-around flex-wrap my-6 gap-0 gap-md-3 gap-lg-4">
          <div class="d-flex align-items-center me-5 gap-4">
            <div class="avatar">
              <div class="avatar-initial bg-label-primary rounded w-px-40 h-px-40">
                <i class='bx bx-check bx-lg'></i>
              </div>
            </div>
            <div>
              <h5 class="mb-0">1.23k</h5>
              <span>Task Done</span>
            </div>
          </div>
          <div class="d-flex align-items-center gap-4">
            <div class="avatar">
              <div class="avatar-initial bg-label-primary rounded w-px-40 h-px-40">
                <i class='bx bx-customize bx-lg'></i>
              </div>
            </div>
            <div>
              <h5 class="mb-0">568</h5>
              <span>Project Done</span>
            </div>
          </div>
        </div>
        <h5 class="pb-4 border-bottom mb-4">Details</h5>
        <div class="info-container">
          <ul class="list-unstyled mb-6">
            <li class="mb-2">
              <span class="h6">Username:</span>
              <span>@violet.dev</span>
            </li>
            <li class="mb-2">
              <span class="h6">Email:</span>
              <span>vafgot@vultukir.org</span>
            </li>
            <li class="mb-2">
              <span class="h6">Status:</span>
              <span>Active</span>
            </li>
            <li class="mb-2">
              <span class="h6">Role:</span>
              <span>Author</span>
            </li>
            <li class="mb-2">
              <span class="h6">Tax id:</span>
              <span>Tax-8965</span>
            </li>
            <li class="mb-2">
              <span class="h6">Contact:</span>
              <span>(123) 456-7890</span>
            </li>
            <li class="mb-2">
              <span class="h6">Languages:</span>
              <span>French</span>
            </li>
            <li class="mb-2">
              <span class="h6">Country:</span>
              <span>England</span>
            </li>
          </ul>
          <div class="d-flex justify-content-center">
            <a href="javascript:;" class="btn btn-primary me-4" data-bs-target="#editUser" data-bs-toggle="modal">Edit</a>
            <a href="javascript:;" class="btn btn-label-danger suspend-user">Suspend</a>
          </div>
        </div>
      </div>
    </div>
    <!-- /User Card -->

  </div>
  <!--/ User Sidebar -->


  <!-- User Content -->
  <div class="col-xl-8 col-lg-7 order-0 order-md-1">






    <!-- Invoice table -->
    <div class="card mb-4">
      <div class="card-datatable table-responsive">
    <div id="DataTables_Table_1_wrapper" class="dataTables_wrapper dt-bootstrap5 no-footer">
        <div class="row mx-6">
            <div class="col-sm-6 col-12 d-flex align-items-center justify-content-center justify-content-sm-start mt-6 mt-sm-0">
                <div class="head-label">
                    <h5 class="card-title mb-0">Invoice List</h5>
                </div>
            </div>
            <div class="col-sm-6 col-12 d-flex justify-content-center justify-content-md-end align-items-baseline">
                <div class="dt-action-buttons d-flex justify-content-center flex-md-row align-items-baseline gap-4">
                    <div class="dataTables_length" id="DataTables_Table_1_length"><label><select name="DataTables_Table_1_length" aria-controls="DataTables_Table_1" class="form-select mx-0">
                                <option value="10">10</option>
                                <option value="25">25</option>
                                <option value="50">50</option>
                                <option value="100">100</option>
                            </select></label></div>
                    <div class="dt-buttons btn-group flex-wrap">
                        <div class="btn-group"><button class="btn btn-secondary buttons-collection dropdown-toggle btn-label-secondary float-sm-end mb-3 mb-sm-0" tabindex="0" aria-controls="DataTables_Table_1" type="button" aria-haspopup="dialog" aria-expanded="false"><span><i class="bx bx-export bx-sm me-2"></i>Export</span></button></div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="row mx-6">
            <div class="col-sm-12 col-xxl-6 text-center text-xxl-start pb-md-2 pb-xxl-0">
                <div class="dataTables_info" id="DataTables_Table_1_info" role="status" aria-live="polite">Showing 1 to 10 of 50 entries</div>
            </div>
            <div class="col-sm-12 col-xxl-6 d-md-flex justify-content-xxl-end justify-content-center">
                <div class="dataTables_paginate paging_simple_numbers" id="DataTables_Table_1_paginate">
                    <ul class="pagination">
                        <li class="paginate_button page-item previous disabled" id="DataTables_Table_1_previous"><a aria-controls="DataTables_Table_1" aria-disabled="true" role="link" data-dt-idx="previous" tabindex="-1" class="page-link"><i class="bx bx-chevron-left bx-18px"></i></a></li>
                        <li class="paginate_button page-item active"><a href="#" aria-controls="DataTables_Table_1" role="link" aria-current="page" data-dt-idx="0" tabindex="0" class="page-link">1</a></li>
                        <li class="paginate_button page-item "><a href="#" aria-controls="DataTables_Table_1" role="link" data-dt-idx="1" tabindex="0" class="page-link">2</a></li>
                        <li class="paginate_button page-item "><a href="#" aria-controls="DataTables_Table_1" role="link" data-dt-idx="2" tabindex="0" class="page-link">3</a></li>
                        <li class="paginate_button page-item "><a href="#" aria-controls="DataTables_Table_1" role="link" data-dt-idx="3" tabindex="0" class="page-link">4</a></li>
                        <li class="paginate_button page-item "><a href="#" aria-controls="DataTables_Table_1" role="link" data-dt-idx="4" tabindex="0" class="page-link">5</a></li>
                        <li class="paginate_button page-item next" id="DataTables_Table_1_next"><a href="#" aria-controls="DataTables_Table_1" role="link" data-dt-idx="next" tabindex="0" class="page-link"><i class="bx bx-chevron-right bx-18px"></i></a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
    </div>
    <!-- /Invoice table -->
  </div>
  <!--/ User Content -->
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
    
    
    <!-- Drag Target Area To SlideIn Menu On Small Screens -->
    <div class="drag-target"></div>
    
  </div>
  <!-- / Layout wrapper -->

  

  

  

  <!-- Core JS -->
  <!-- build:js assets/vendor/js/core.js -->
  
  <script src="../assets/vendor/libs/jquery/jquery.js"></script>
  <script src="../assets/vendor/libs/popper/popper.js"></script>
  <script src="../assets/vendor/js/bootstrap.js"></script>
  <script src="../assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>
  <script src="../assets/vendor/libs/hammer/hammer.js"></script>
  <script src="../assets/vendor/libs/i18n/i18n.js"></script>
  <script src="../assets/vendor/libs/typeahead-js/typeahead.js"></script>
  <script src="../assets/vendor/js/menu.js"></script>
  
  <!-- endbuild -->

  <!-- Vendors JS -->
  <script src="../assets/vendor/libs/moment/moment.js"></script>
<script src="../assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js"></script>
<script src="../assets/vendor/libs/sweetalert2/sweetalert2.js"></script>
<script src="../assets/vendor/libs/cleavejs/cleave.js"></script>
<script src="../assets/vendor/libs/cleavejs/cleave-phone.js"></script>
<script src="../assets/vendor/libs/select2/select2.js"></script>
<script src="../assets/vendor/libs/@form-validation/popular.js"></script>
<script src="../assets/vendor/libs/@form-validation/bootstrap5.js"></script>
<script src="../assets/vendor/libs/@form-validation/auto-focus.js"></script>

  <!-- Main JS -->
  <script src="../assets/js/main.js"></script>
 <script>
 
</script> 

  <!-- Page JS -->
  <script src="../assets/js/modal-edit-user.js"></script>
<script src="../assets/js/app-user-view.js"></script>
<script src="../assets/js/app-user-view-account.js"></script>
  
</body>

</html>

<!-- beautify ignore:end -->

