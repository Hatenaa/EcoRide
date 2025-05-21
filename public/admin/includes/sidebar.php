<?php 

$current_page = basename(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH)); 
# var_dump($current_page); exit;

?>

<aside class="sidenav navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-3 " id="sidenav-main">
    <div class=" sidenav-header w-auto">
      <i class="fas fa-times p-3 cursor-pointer text-secondary opacity-5 position-absolute end-0 top-0 d-none d-xl-none" aria-hidden="true" id="iconSidenav"></i>
      
        <div class="sidenav-header">
          <i class="fas fa-times p-3 cursor-pointer text-secondary opacity-5 position-absolute end-0 top-0 d-none d-xl-none" aria-hidden="true" id="iconSidenav"></i>
          <a class="navbar-brand m-0" href="/">
            <h4>EcoRide</h4>
          </a>
        </div>
    </div>

    <hr class="horizontal dark mt-0">
    <div class="collapse navbar-collapse  w-auto " id="sidenav-collapse-main">

      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link <?= $current_page === 'dashboard_admin.php' ? 'active' : ''; ?>" href="/admin/dashboard_admin.php">
            <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center" style="<?= $current_page != 'dashboard_admin.php' ? 'padding-bottom: 2px;' : '' ?>">
              <i class="fa fa-home text-lg <?= $current_page === 'dashboard_admin.php' ? 'text-white' : 'text-dark'; ?>"></i>
            </div>
            <span class="nav-link-text ms-1">Accueil</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link <?= ($current_page === 'users.php' || $current_page === 'edit_users.php' || $current_page === 'create_users.php') ? 'active' : ''; ?>" href="/admin/users.php">
            <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                <i class="fa fa-user-plus text-lg <?= ($current_page === 'users.php' || $current_page === 'edit_users.php' || $current_page === 'create_users.php') ? 'text-white' : 'text-dark'; ?>" style="<?= ($current_page === 'users.php' || $current_page === 'edit_users.php' || $current_page === 'create_users.php') ? 'padding-bottom: 2px;' : 'padding-bottom: 5px;'; ?>"></i>
            </div>
            <span class="nav-link-text ms-1">Utilisateurs</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link <?= $current_page === 'settings.php' ? 'active' : ''; ?>" href="/admin/settings.php">
            <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                <i class="fa fa-cog <?= $current_page === 'settings.php' ? 'text-white' : 'text-dark'; ?> text-lg" style="<?= $current_page === 'settings.php' ? '' : 'padding-bottom: 5px;'; ?>"></i>
            </div>
            <span class="nav-link-text ms-1">Paramètres</span>
          </a>
        </li>
        

      </ul>
    </div>
    <div class="sidenav-footer mx-3 ">
      
      <a class="btn btn-dark mt-3 w-100" href="../pages/auth/logout.php">
        Déconnexion
      </a>

    </div>
  </aside>