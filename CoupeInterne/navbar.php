<nav
            class="layout-navbar navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme container-fluid"
            id="layout-navbar"
          >
            <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
              <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
                <i class="bx bx-menu bx-sm"></i>
              </a>
            </div>

            <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
<!-- Search -->
<div class="navbar-nav align-items-center">
  <div class="nav-item d-flex align-items-center">
    <i class='bx bxs-bell fs-4'></i>
    
    <?php if (basename($_SERVER['PHP_SELF']) == 'list-tissu.php'): ?>
      <div class="nav-item d-flex align-items-center ms-2">
        <i class="bx bx-search fs-4 lh-0"></i>
        <input
          type="text"
          id="tableSearch"
          class="form-control border-0 shadow-none ms-2"
          placeholder="Search..."
          aria-label="Search..."
        />
      </div>
    <?php endif; ?>
	<?php if (basename($_SERVER['PHP_SELF']) == 'pilotage-production.php'): ?>
      <div class="nav-item d-flex align-items-center ms-2">
        <i class="bx bx-search fs-4 lh-0"></i>
        <input
          type="text"
          id="tableSearch"
          class="form-control border-0 shadow-none ms-2"
          placeholder="Search..."
          aria-label="Search..."
        />
      </div>
    <?php endif; ?>
	<?php if (basename($_SERVER['PHP_SELF']) == 'wip.php'): ?>
      <div class="nav-item d-flex align-items-center ms-2">
        <i class="bx bx-search fs-4 lh-0"></i>
        <input
          type="text"
          id="tableSearch"
          class="form-control border-0 shadow-none ms-2"
          placeholder="Search..."
          aria-label="Search..."
        />
      </div>
    <?php endif; ?>
  </div>
</div>
<!-- /Search -->


              <ul class="navbar-nav flex-row align-items-center ms-auto">
                <!-- Place this tag where you want the button to render. -->
                

                <!-- User -->
                <li class="nav-item navbar-dropdown dropdown-user dropdown">
                  <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
                    <div class="avatar avatar-online">
					<?php if ($_SESSION['role'] == 'admin'): ?>
                      <img src="../assets/img/avatars/1.png" alt class="w-px-40 h-auto rounded-circle" />
                    </div>
                  </a>
                  <ul class="dropdown-menu dropdown-menu-end">
                    <li>
                      <a class="dropdown-item" href="#">
                        <div class="d-flex">
                          <div class="flex-shrink-0 me-3">
                            <div class="avatar avatar-online">
							
                              <img src="../assets/img/avatars/1.png" alt class="w-px-40 h-auto rounded-circle" />
							  <?php endif; ?>
							  <?php if ($_SESSION['role'] == 'agent-bureautique'): ?>
                      <img src="../assets/img/avatars/knouz.jpg" alt class="w-px-40 h-auto rounded-circle" />
                    </div>
                  </a>
                  <ul class="dropdown-menu dropdown-menu-end">
                    <li>
                      <a class="dropdown-item" href="#">
                        <div class="d-flex">
                          <div class="flex-shrink-0 me-3">
                            <div class="avatar avatar-online">
							
                              <img src="../assets/img/avatars/knouz.jpg" alt class="w-px-40 h-auto rounded-circle" />
							  <?php endif; ?>
							  <?php if ($_SESSION['role'] == 'responsable-utm'): ?>
                      <img src="../assets/img/avatars/6.png" alt class="w-px-40 h-auto rounded-circle" />
                    </div>
                  </a>
                  <ul class="dropdown-menu dropdown-menu-end">
                    <li>
                      <a class="dropdown-item" href="#">
                        <div class="d-flex">
                          <div class="flex-shrink-0 me-3">
                            <div class="avatar avatar-online">
							
                              <img src="../assets/img/avatars/6.png" alt class="w-px-40 h-auto rounded-circle" />
							  <?php endif; ?>
							  <?php if ($_SESSION['role'] == 'responsable-coupe'): ?>
                      <img src="../assets/img/avatars/7.png" alt class="w-px-40 h-auto rounded-circle" />
                    </div>
                  </a>
                  <ul class="dropdown-menu dropdown-menu-end">
                    <li>
                      <a class="dropdown-item" href="#">
                        <div class="d-flex">
                          <div class="flex-shrink-0 me-3">
                            <div class="avatar avatar-online">
							
                              <img src="../assets/img/avatars/7.png" alt class="w-px-40 h-auto rounded-circle" />
							  <?php endif; ?>
                            </div>
                          </div>
                          <div class="flex-grow-1">
                            <span class="fw-semibold d-block"><?php echo $_SESSION['nom'];echo " "; echo $_SESSION['prenom']; ?></span>
                            <small class="text-muted"><?php echo $_SESSION['role'] ?></small>
                          </div>
                        </div>
                      </a>
                    </li>
                    <li>
                      <div class="dropdown-divider"></div>
                    </li>
                    
                    
                    
                    
                    <li>
                      <a class="dropdown-item" href="logout.php">
                        <i class="bx bx-power-off me-2"></i>
                        <span class="align-middle">Log Out</span>
                      </a>
                    </li>
                  </ul>
                </li>
                <!--/ User -->
              </ul>
            </div>
          </nav>

          <!-- / Navbar -->