<?php
// Définir la page courante
$currentPage = basename($_SERVER['PHP_SELF']); // Récupère le nom de la page actuelle
?>
<!-- Menu -->
<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
  <div class="app-brand demo">
    <a href="index.php" class="app-brand-link">
      <span class="app-brand-logo demo">
        <img src="../assets/img/favicon/benetton.png" width="180" alt=""/>
      </span>
    </a>

    <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
      <i class="bx bx-chevron-left bx-sm align-middle"></i>
    </a>
  </div>

  <div class="menu-inner-shadow"></div>

  <ul class="menu-inner py-1">
    <?php if (isset($_SESSION['role'])): ?>
      <?php if ($_SESSION['role'] == 'admin'): ?>
        <!-- Dashboard -->
        <li class="menu-item <?= $currentPage == 'index.php' ? 'active' : ''; ?>">
          <a href="index.php" class="menu-link">
            <i class="menu-icon tf-icons bx bx-home-circle"></i>
            <div data-i18n="Analytics">Dashboard</div>
          </a>
        </li>
		<li class="menu-item <?= $currentPage == 'calander.php' ? 'active' : ''; ?>">
          <a href="calander.php" class="menu-link">
            <i class="menu-icon tf-icons bx bx-calendar"></i>
            <div data-i18n="Analytics">Calendrier</div>
          </a>
        </li>
        <!-- Production -->
        <li class="menu-header small text-uppercase">
          <span class="menu-header-text">Production</span>
        </li>
		<li class="menu-item <?= $currentPage == 'ajouter-prevision.php' ? 'active' : ''; ?>">
          <a href="ajouter-prevision.php" class="menu-link">
		  
            <i class="menu-icon tf-icons bx bx-layer-plus"></i>
            <div data-i18n="Basic">Prévision</div>
          </a>
        </li>
        <li class="menu-item <?= $currentPage == 'wip.php' ? 'active' : ''; ?>">
          <a href="wip.php" class="menu-link">
            <i class="menu-icon tf-icons bx bx-collection"></i>
            <div data-i18n="Basic">WIP</div>
          </a>
        </li>
		<li class="menu-item <?= $currentPage == 'kanban.php' ? 'active' : ''; ?>">
          <a href="kanban.php" class="menu-link">
		  
            <i class="menu-icon tf-icons bx bx-grid"></i>
            <div data-i18n="Basic">KanBan</div>
          </a>
        </li>
        <?php
    // Vérifier si l'une des pages enfants est active
    $isActiveToggle = in_array($currentPage, [
        'ajouter-informations-generales.php',
        'ajouter-details-matelas.php',
		'ajouter-details-matelas1.php',
		'modifier-commandes.php',
        'ajouter-prix-unitaire.php',
		'ajouter-temps-standard1.php',
		'chargement-t1.php',
		't1.php',
        'ajouter-temps-standard.php'
    ]);
    ?>
    <li class="menu-item <?= $isActiveToggle ? 'active open' : ''; ?>">
        <a href="javascript:void(0);" class="menu-link menu-toggle">
            <i class="menu-icon tf-icons bx bx-dock-top"></i>
            <div data-i18n="Layouts">Commandes</div>
        </a>

        <ul class="menu-sub">
		            <li class="menu-item <?= $currentPage == 't1.php'|| $currentPage == 'chargement-t1.php' ? 'active' : ''; ?>">
    <a href="t1.php" class="menu-link no-wrap">
        <div >Prévision </div>
    </a>
</li>
            <li class="menu-item <?= $currentPage == 'ajouter-informations-generales.php' ? 'active' : ''; ?>">
    <a href="ajouter-informations-generales.php" class="menu-link no-wrap">
        <div >Informations&nbsp;Générales </div>
    </a>
</li>

            <li class="menu-item <?= $currentPage == 'ajouter-details-matelas.php' || $currentPage == 'ajouter-details-matelas1.php' ? 'active' : ''; ?>">
                <a href="ajouter-details-matelas1.php" class="menu-link">
                    <div >Détails Matelas</div>
                </a>
            </li>
			<li class="menu-item <?= $currentPage == 'modifier-commandes.php' ||$currentPage == 'ajouter-prix-unitaire.php' ? 'active' : ''; ?>">
          <a href="modifier-commandes.php" class="menu-link">
                    <div >Cout Unitaire</div>
                </a>
            </li>
            <li class="menu-item <?= $currentPage == 'ajouter-temps-standard.php' || $currentPage == 'ajouter-temps-standard1.php' ? 'active' : ''; ?>">
                <a href="ajouter-temps-standard1.php" class="menu-link">
                    <div>Temps Standard</div>
                </a>
            </li>
           
        </ul>
    </li>
	<li class="menu-item <?= $currentPage == 'pilotage-plannification.php' ? 'active' : ''; ?>">
          <a href="pilotage-plannification.php" class="menu-link">
		  
    <i class='menu-icon tf-icons bx bx-menu-alt-left'></i>
    <div data-i18n="User interface">Plannification</div>
</a>

        </li>
        <?php
    // Vérifier si l'une des pages enfants est active
    $isActiveToggleArchive = in_array($currentPage, [
        'pilotage-archive.php',
        'archive.php',
		'pilotage-archive-prix.php',
        'pilotage-archive-temps.php',
		'archive-prix.php',
		'archive-temps.php',
		'modifier-prix-unitaire.php',
		'modifier-temps-standard.php'
    ]);
    ?>
	<li class="menu-item <?= $isActiveToggleArchive ? 'active open' : ''; ?>">
        <a  class="menu-link menu-toggle">
            <i class="menu-icon tf-icons bx bx-dock-top"></i>
            <div data-i18n="Layouts">Archive</div>
        </a>

        <ul class="menu-sub">

            <li class="menu-item <?= $currentPage == 'pilotage-archive.php' ||$currentPage == 'archive.php' ? 'active' : ''; ?>">
          <a href="pilotage-archive.php" class="menu-link">
                    <div >Commande</div>
                </a>
            </li>
			<li class="menu-item <?= $currentPage == 'pilotage-archive-prix.php' ||$currentPage == 'archive-prix.php' ? 'active' : ''; ?>">
          <a href="pilotage-archive-prix.php" class="menu-link">
                    <div >Prix</div>
                </a>
            </li>
            <li class="menu-item <?= $currentPage == 'pilotage-archive-temsp.php' || $currentPage == 'archive-temps.php' ? 'active' : ''; ?>">
                <a href="pilotage-archive-temps.php" class="menu-link">
                    <div>Temps Standard</div>
                </a>
            </li>
        </ul>
    </li>
		<li class="menu-item <?= $currentPage == 'pointage.php' ? 'active' : ''; ?>">
          <a href="pointage.php" class="menu-link">
            <i class="menu-icon tf-icons bx bx-user"></i>
            <div >Pointage</div>
          </a>
        </li>
			<li class="menu-item <?= $currentPage == 'pilotage-production.php' ? 'active' : ''; ?>">
          <a href="pilotage-production.php" class="menu-link">
            <i class=' menu-icon bx bx-briefcase'></i>
            <div >Production</div>
          </a>
        </li>
		<!-- Traçé -->
        <li class="menu-header small text-uppercase">
          <span class="menu-header-text">Traçé</span>
        </li>
		<li class="menu-item <?= $currentPage == 'ajouter-trace.php' ? 'active' : ''; ?>">
          <a href="ajouter-trace.php" class="menu-link">
		 <i class='menu-icon bx bx-extension'></i>
            
            <div >Ajouter</div>
          </a>
        </li>
		<li class="menu-item <?= $currentPage == 'liste-trace.php' ? 'active' : ''; ?>">
          <a href="liste-trace.php" class="menu-link">
		 <i class='menu-icon bx bx-trim'></i>
           
            <div >Liste Des Traçés</div>
          </a>
        </li>
		<li class="menu-item <?= $currentPage == 'facture-trace.php' ? 'active' : ''; ?>">
          <a href="facture-trace.php" class="menu-link">
		 <i class='menu-icon tf-icons bx bx-food-menu'></i>
           
            <div >Facture</div>
          </a>
        </li>
		
        <!-- Ressources -->
        <li class="menu-header small text-uppercase">
          <span class="menu-header-text">Ressources</span>
        </li>
        <li class="menu-item <?= $currentPage == 'list-effectif.php' ? 'active' : ''; ?>">
          <a href="list-effectif.php" class="menu-link">
            <i class="menu-icon tf-icons bx bx-group"></i>
            <div data-i18n="Authentications">Effectif</div>
          </a>
        </li>
        <li class="menu-item <?= $currentPage == 'list-materiel.php' ? 'active' : ''; ?>">
          <a href="list-materiel.php" class="menu-link">
            <i class="menu-icon tf-icons bx bx-buildings"></i>
            <div data-i18n="Authentications">Matériel</div>
          </a>
        </li>
        <li class="menu-item <?= $currentPage == 'list-tissu.php' ? 'active' : ''; ?>">
          <a href="list-tissu.php" class="menu-link">
            <i class="menu-icon tf-icons bx bx-rectangle"></i>
            <div data-i18n="Authentications">Tissu</div>
          </a>
        </li>
        <!-- Authentifications -->
        <li class="menu-header small text-uppercase">
          <span class="menu-header-text">Authentifications</span>
        </li>
        <li class="menu-item <?= $currentPage == 'ajouter-utilisateur.php' ? 'active' : ''; ?>">
          <a href="ajouter-utilisateur.php" class="menu-link">
            <i class="menu-icon tf-icons bx bx-user-plus"></i>
            <div data-i18n="Authentications">Ajouter Utilisateur</div>
          </a>
        </li>
        <li class="menu-item <?= $currentPage == 'list-utilisateur.php' ? 'active' : ''; ?>">
          <a href="list-utilisateur.php" class="menu-link">
            <i class="menu-icon tf-icons bx bx-user"></i>
            <div data-i18n="Authentications">Utilisateurs</div>
          </a>
        </li>
      <?php endif; ?>

      <!-- Role: Agent -->
      <?php if ($_SESSION['role'] == 'agent-bureautique'): ?>
	  <!-- Dashboard -->
        <li class="menu-item <?= $currentPage == 'index.php' ? 'active' : ''; ?>">
          <a href="index.php" class="menu-link">
            <i class="menu-icon tf-icons bx bx-home-circle"></i>
            <div data-i18n="Analytics">Dashboard</div>
          </a>
        </li>
	  <!-- Production -->
        <li class="menu-header small text-uppercase">
          <span class="menu-header-text">Production</span>
        </li>
    <?php
    // Vérifier si l'une des pages enfants est active
    $isActiveToggle = in_array($currentPage, [
        'ajouter-informations-generales.php',
        'ajouter-details-matelas.php',
        't1.php',
        'chargement-t1.php',
		'ajouter-details-matelas1.php'
    ]);
    ?>
	<li class="menu-item <?= $currentPage == 'wip.php' ? 'active' : ''; ?>">
          <a href="wip.php" class="menu-link">
            <i class="menu-icon tf-icons bx bx-collection"></i>
            <div data-i18n="Basic">WIP</div>
          </a>
        </li>
    <li class="menu-item <?= $isActiveToggle ? 'active open' : ''; ?>">
        <a href="javascript:void(0);" class="menu-link menu-toggle">
            <i class="menu-icon tf-icons bx bx-dock-top"></i>
            <div data-i18n="Layouts">Commandes</div>
        </a>

        <ul class="menu-sub">
				            <li class="menu-item <?= $currentPage == 't1.php'|| $currentPage == 'chargement-t1.php' ? 'active' : ''; ?>">
    <a href="t1.php" class="menu-link no-wrap">
        <div >Prévision </div>
    </a>
</li>
            <li class="menu-item <?= $currentPage == 'ajouter-informations-generales.php' ? 'active' : ''; ?>">
    <a href="ajouter-informations-generales.php" class="menu-link no-wrap">
        <div >Informations&nbsp;Générales </div>
    </a>
</li>

            <li class="menu-item <?= $currentPage == 'ajouter-details-matelas.php' || $currentPage == 'ajouter-details-matelas1.php' ? 'active' : ''; ?>">
                <a href="ajouter-details-matelas1.php" class="menu-link">
                    <div >Détails Matelas</div>
                </a>
            </li>
           
        </ul>
    </li>
	<li class="menu-item <?= $currentPage == 'production.php' ? 'active' : ''; ?>">
          <a href="production.php" class="menu-link">
            <i class=' menu-icon bx bx-briefcase'></i>
            <div >Production</div>
          </a>
        </li>
<li class="menu-item <?= $currentPage == 'pointage.php' ? 'active' : ''; ?>">
          <a href="pointage.php" class="menu-link">
            <i class="menu-icon tf-icons bx bx-user"></i>
            <div >Pointage</div>
          </a>
        </li>
		
		<?php
    // Vérifier si l'une des pages enfants est active
    $isActiveToggleArchive = in_array($currentPage, [
        'pilotage-archive.php',
        'archive.php',
		'pilotage-archive-prix.php',
        'pilotage-archive-temps.php',
		'archive-prix.php',
		'archive-temps.php',
		'modifier-prix-unitaire.php',
		'modifier-temps-standard.php'
    ]);
    ?>
		<li class="menu-item <?= $isActiveToggleArchive ? 'active open' : ''; ?>">
        <a  class="menu-link menu-toggle">
            <i class="menu-icon tf-icons bx bx-dock-top"></i>
            <div data-i18n="Layouts">Archive</div>
        </a>

        <ul class="menu-sub">

            <li class="menu-item <?= $currentPage == 'pilotage-archive.php' ||$currentPage == 'archive.php' ? 'active' : ''; ?>">
          <a href="pilotage-archive.php" class="menu-link">
                    <div >Commande</div>
                </a>
            </li>
			
        </ul>
    </li>
    <!-- Traçé -->
        <li class="menu-header small text-uppercase">
          <span class="menu-header-text">Traçé</span>
        </li>
		<li class="menu-item <?= $currentPage == 'ajouter-trace.php' ? 'active' : ''; ?>">
          <a href="ajouter-trace.php" class="menu-link">
		 <i class='menu-icon bx bx-extension'></i>
            
            <div >Ajouter</div>
          </a>
        </li>
		<li class="menu-item <?= $currentPage == 'liste-trace.php' ? 'active' : ''; ?>">
          <a href="liste-trace.php" class="menu-link">
		 <i class='menu-icon bx bx-trim'></i>
           
            <div >Liste Des Traçés</div>
          </a>
        </li>
		<li class="menu-item <?= $currentPage == 'facture-trace.php' ? 'active' : ''; ?>">
          <a href="facture-trace.php" class="menu-link">
		 <i class='menu-icon tf-icons bx bx-food-menu'></i>
           
            <div >Facture</div>
          </a>
        </li>
	
	<!-- Ressources -->
        <li class="menu-header small text-uppercase">
          <span class="menu-header-text">Ressources</span>
        </li>
        <li class="menu-item <?= $currentPage == 'list-effectif.php' ? 'active' : ''; ?>">
          <a href="list-effectif.php" class="menu-link">
            <i class="menu-icon tf-icons bx bx-group"></i>
            <div data-i18n="Authentications">Effectif</div>
          </a>
        </li>
        <li class="menu-item <?= $currentPage == 'list-materiel.php' ? 'active' : ''; ?>">
          <a href="list-materiel.php" class="menu-link">
            <i class="menu-icon tf-icons bx bx-buildings"></i>
            <div data-i18n="Authentications">Matériel</div>
          </a>
        </li>
        <li class="menu-item <?= $currentPage == 'list-tissu.php' ? 'active' : ''; ?>">
          <a href="list-tissu.php" class="menu-link">
            <i class="menu-icon tf-icons bx bx-rectangle"></i>
            <div data-i18n="Authentications">Tissu</div>
          </a>
        </li>
<?php endif; ?>



      <!-- Role: UTM -->
      <?php if ($_SESSION['role'] == 'responsable-utm'): ?>
	  <!-- Dashboard -->
        <li class="menu-item <?= $currentPage == 'index.php' ? 'active' : ''; ?>">
          <a href="index.php" class="menu-link">
            <i class="menu-icon tf-icons bx bx-home-circle"></i>
            <div data-i18n="Analytics">Dashboard</div>
          </a>
        </li>
		<li class="menu-header small text-uppercase">
          <span class="menu-header-text">Production</span>
        </li>
		    <?php
    // Vérifier si l'une des pages enfants est active
    $isActiveToggle = in_array($currentPage, [
        'modifier-commandes.php',
        'ajouter-prix-unitaire.php',
		'ajouter-temps-standard1.php',
        'ajouter-temps-standard.php'
    ]);
    ?>
    <li class="menu-item <?= $isActiveToggle ? 'active open' : ''; ?>">
        <a  class="menu-link menu-toggle">
            <i class="menu-icon tf-icons bx bx-dock-top"></i>
            <div data-i18n="Layouts">Commandes</div>
        </a>

        <ul class="menu-sub">

            <li class="menu-item <?= $currentPage == 'modifier-commandes.php' ||$currentPage == 'ajouter-prix-unitaire.php' ? 'active' : ''; ?>">
          <a href="modifier-commandes.php" class="menu-link">
                    <div >Cout Unitaire</div>
                </a>
            </li>
            <li class="menu-item <?= $currentPage == 'ajouter-temps-standard.php' || $currentPage == 'ajouter-temps-standard1.php' ? 'active' : ''; ?>">
                <a href="ajouter-temps-standard1.php" class="menu-link">
                    <div>Temps Standard</div>
                </a>
            </li>
        </ul>
    </li>
	
	<?php
    // Vérifier si l'une des pages enfants est active
    $isActiveToggleArchive = in_array($currentPage, [
        'pilotage-archive.php',
        'archive.php',
		'pilotage-archive-prix.php',
        'pilotage-archive-temps.php',
		'archive-prix.php',
		'archive-temps.php',
		'modifier-prix-unitaire.php',
		'modifier-temps-standard.php'
    ]);
    ?>
	<li class="menu-item <?= $isActiveToggleArchive ? 'active open' : ''; ?>">
        <a  class="menu-link menu-toggle">
            <i class="menu-icon tf-icons bx bx-dock-top"></i>
            <div data-i18n="Layouts">Archive</div>
        </a>

        <ul class="menu-sub">

            <li class="menu-item <?= $currentPage == 'pilotage-archive.php' ||$currentPage == 'archive.php' ? 'active' : ''; ?>">
          <a href="pilotage-archive.php" class="menu-link">
                    <div >Commande</div>
                </a>
            </li>
			<li class="menu-item <?= $currentPage == 'pilotage-archive-prix.php' ||$currentPage == 'archive-prix.php' ? 'active' : ''; ?>">
          <a href="pilotage-archive-prix.php" class="menu-link">
                    <div >Prix</div>
                </a>
            </li>
            <li class="menu-item <?= $currentPage == 'pilotage-archive-temps.php' || $currentPage == 'archive-temps.php' ? 'active' : ''; ?>">
                <a href="pilotage-archive-temps.php" class="menu-link">
                    <div>Temps Standard</div>
                </a>
            </li>
        </ul>
    </li>
        
		
		<li class="menu-item <?= $currentPage == 'pointage.php' ? 'active' : ''; ?>">
          <a href="pointage.php" class="menu-link">
            <i class="menu-icon tf-icons bx bx-user"></i>
            <div >Pointage</div>
          </a>
        </li>
		
		<!-- Ressources -->
        <li class="menu-header small text-uppercase">
          <span class="menu-header-text">Ressources</span>
        </li>
        <li class="menu-item <?= $currentPage == 'list-effectif.php' ? 'active' : ''; ?>">
          <a href="list-effectif.php" class="menu-link">
            <i class="menu-icon tf-icons bx bx-group"></i>
            <div data-i18n="Authentications">Effectif</div>
          </a>
        </li>
        <li class="menu-item <?= $currentPage == 'list-materiel.php' ? 'active' : ''; ?>">
          <a href="list-materiel.php" class="menu-link">
            <i class="menu-icon tf-icons bx bx-buildings"></i>
            <div data-i18n="Authentications">Matériel</div>
          </a>
        </li>
        <li class="menu-item <?= $currentPage == 'list-tissu.php' ? 'active' : ''; ?>">
          <a href="list-tissu.php" class="menu-link">
            <i class="menu-icon tf-icons bx bx-rectangle"></i>
            <div data-i18n="Authentications">Tissu</div>
          </a>
        </li>
      <?php endif; ?>
	  <!-- responsable-coupe -->
	  <?php if ($_SESSION['role'] == 'responsable-coupe'): ?>
        <!-- Dashboard -->
        <li class="menu-item <?= $currentPage == 'index.php' ? 'active' : ''; ?>">
          <a href="index.php" class="menu-link">
            <i class="menu-icon tf-icons bx bx-home-circle"></i>
            <div data-i18n="Analytics">Dashboard</div>
          </a>
        </li>
		<li class="menu-item <?= $currentPage == 'calander.php' ? 'active' : ''; ?>">
          <a href="calander.php" class="menu-link">
            <i class="menu-icon tf-icons bx bx-calendar"></i>
            <div data-i18n="Analytics">Calendrier</div>
          </a>
        </li>
        <!-- Production -->
        <li class="menu-header small text-uppercase">
          <span class="menu-header-text">Production</span>
        </li>
        <li class="menu-item <?= $currentPage == 'wip.php' ? 'active' : ''; ?>">
          <a href="wip.php" class="menu-link">
            <i class="menu-icon tf-icons bx bx-collection"></i>
            <div data-i18n="Basic">WIP</div>
          </a>
        </li>
		<li class="menu-item <?= $currentPage == 'kanban.php' ? 'active' : ''; ?>">
          <a href="kanban.php" class="menu-link">
		  
            <i class="menu-icon tf-icons bx bx-grid"></i>
            <div data-i18n="Basic">KanBan</div>
          </a>
        </li>
        <?php
    // Vérifier si l'une des pages enfants est active
    $isActiveToggle = in_array($currentPage, [
        'ajouter-informations-generales.php',
        'ajouter-details-matelas.php',
		'ajouter-details-matelas1.php',
		'modifier-commandes.php',
        'ajouter-prix-unitaire.php',
		'ajouter-temps-standard1.php',
        'ajouter-temps-standard.php'
    ]);
    ?>
    <li class="menu-item <?= $isActiveToggle ? 'active open' : ''; ?>">
        <a href="javascript:void(0);" class="menu-link menu-toggle">
            <i class="menu-icon tf-icons bx bx-dock-top"></i>
            <div data-i18n="Layouts">Commandes</div>
        </a>

        <ul class="menu-sub">
            <li class="menu-item <?= $currentPage == 'ajouter-informations-generales.php' ? 'active' : ''; ?>">
    <a href="ajouter-informations-generales.php" class="menu-link no-wrap">
        <div >Informations&nbsp;Générales </div>
    </a>
</li>

            <li class="menu-item <?= $currentPage == 'ajouter-details-matelas.php' || $currentPage == 'ajouter-details-matelas1.php' ? 'active' : ''; ?>">
                <a href="ajouter-details-matelas1.php" class="menu-link">
                    <div >Détails Matelas</div>
                </a>
            </li>
			<li class="menu-item <?= $currentPage == 'modifier-commandes.php' ||$currentPage == 'ajouter-prix-unitaire.php' ? 'active' : ''; ?>">
          <a href="modifier-commandes.php" class="menu-link">
                    <div >Cout Unitaire</div>
                </a>
            </li>
            <li class="menu-item <?= $currentPage == 'ajouter-temps-standard.php' || $currentPage == 'ajouter-temps-standard1.php' ? 'active' : ''; ?>">
                <a href="ajouter-temps-standard1.php" class="menu-link">
                    <div>Temps Standard</div>
                </a>
            </li>
           
        </ul>
    </li>
	<li class="menu-item <?= $currentPage == 'pilotage-plannification.php' ? 'active' : ''; ?>">
          <a href="pilotage-plannification.php" class="menu-link">
		  
    <i class='menu-icon tf-icons bx bx-menu-alt-left'></i>
    <div data-i18n="User interface">Plannification</div>
</a>

        </li>
        <?php
    // Vérifier si l'une des pages enfants est active
    $isActiveToggleArchive = in_array($currentPage, [
        'pilotage-archive.php',
        'archive.php',
		'pilotage-archive-prix.php',
        'pilotage-archive-temps.php',
		'archive-prix.php',
		'archive-temps.php',
		'modifier-prix-unitaire.php',
		'modifier-temps-standard.php'
    ]);
    ?>
	<li class="menu-item <?= $isActiveToggleArchive ? 'active open' : ''; ?>">
        <a  class="menu-link menu-toggle">
            <i class="menu-icon tf-icons bx bx-dock-top"></i>
            <div data-i18n="Layouts">Archive</div>
        </a>

        <ul class="menu-sub">

            <li class="menu-item <?= $currentPage == 'pilotage-archive.php' ||$currentPage == 'archive.php' ? 'active' : ''; ?>">
          <a href="pilotage-archive.php" class="menu-link">
                    <div >Commande</div>
                </a>
            </li>
			<li class="menu-item <?= $currentPage == 'pilotage-archive-prix.php' ||$currentPage == 'archive-prix.php' ? 'active' : ''; ?>">
          <a href="pilotage-archive-prix.php" class="menu-link">
                    <div >Prix</div>
                </a>
            </li>
            <li class="menu-item <?= $currentPage == 'pilotage-archive-temsp.php' || $currentPage == 'archive-temps.php' ? 'active' : ''; ?>">
                <a href="pilotage-archive-temps.php" class="menu-link">
                    <div>Temps Standard</div>
                </a>
            </li>
        </ul>
    </li>
		<li class="menu-item <?= $currentPage == 'pointage.php' ? 'active' : ''; ?>">
          <a href="pointage.php" class="menu-link">
            <i class="menu-icon tf-icons bx bx-user"></i>
            <div >Pointage</div>
          </a>
        </li>
			<li class="menu-item <?= $currentPage == 'production.php' ? 'active' : ''; ?>">
          <a href="production.php" class="menu-link">
            <i class=' menu-icon bx bx-briefcase'></i>
            <div >Production</div>
          </a>
        </li>
        <!-- Ressources -->
        <li class="menu-header small text-uppercase">
          <span class="menu-header-text">Ressources</span>
        </li>
        <li class="menu-item <?= $currentPage == 'list-effectif.php' ? 'active' : ''; ?>">
          <a href="list-effectif.php" class="menu-link">
            <i class="menu-icon tf-icons bx bx-group"></i>
            <div data-i18n="Authentications">Effectif</div>
          </a>
        </li>
        <li class="menu-item <?= $currentPage == 'list-materiel.php' ? 'active' : ''; ?>">
          <a href="list-materiel.php" class="menu-link">
            <i class="menu-icon tf-icons bx bx-buildings"></i>
            <div data-i18n="Authentications">Matériel</div>
          </a>
        </li>
        <li class="menu-item <?= $currentPage == 'list-tissu.php' ? 'active' : ''; ?>">
          <a href="list-tissu.php" class="menu-link">
            <i class="menu-icon tf-icons bx bx-rectangle"></i>
            <div data-i18n="Authentications">Tissu</div>
          </a>
        </li>
        <!-- Authentifications -->
        <li class="menu-header small text-uppercase">
          <span class="menu-header-text">Authentifications</span>
        </li>
        <li class="menu-item <?= $currentPage == 'ajouter-utilisateur.php' ? 'active' : ''; ?>">
          <a href="ajouter-utilisateur.php" class="menu-link">
            <i class="menu-icon tf-icons bx bx-user-plus"></i>
            <div data-i18n="Authentications">Ajouter Utilisateur</div>
          </a>
        </li>
        <li class="menu-item <?= $currentPage == 'list-utilisateur.php' ? 'active' : ''; ?>">
          <a href="list-utilisateur.php" class="menu-link">
            <i class="menu-icon tf-icons bx bx-user"></i>
            <div data-i18n="Authentications">Utilisateurs</div>
          </a>
        </li>
      <?php endif; ?>
    <?php endif; ?>
  </ul>
</aside>
<!-- / Menu -->
