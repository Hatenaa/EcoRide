<?php 

# Récupérons l'ID de l'utilisateur connecté
$userId = $_SESSION['loggedInUser']['user_id'];

# Vérifier si l'utilisateur a déjà un rôle dans user_as_role
$stmt = $db->prepare("
    SELECT r.label 
    FROM user_as_role ur
    JOIN roles r ON ur.role_id = r.role_id
    WHERE ur.user_id = ?
");

$stmt->execute([$userId]);
$userRole = $stmt->fetchColumn();

# Récupérer la liste des rôles disponibles
$stmt = $db->prepare("SELECT role_id, label FROM roles WHERE label IN ('Passager', 'Chauffeur', 'Passager & Chauffeur') ORDER BY role_id ASC");
$stmt->execute();
$roles = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<aside class="sidenav navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-3 " id="sidenav-main">
    <div class="sidenav-header">
      <i class="fas fa-times p-3 cursor-pointer text-secondary opacity-5 position-absolute end-0 top-0 d-none d-xl-none" aria-hidden="true" id="iconSidenav"></i>
      <a class="navbar-brand m-0" href="/pages/home.php">
        <h4>EcoRide</h4>
      </a>
    </div>

    <hr class="horizontal dark mt-0">
    <div class="collapse navbar-collapse  w-auto " id="sidenav-collapse-main">

      <ul class="navbar-nav">


        <li class="nav-item">
          <a class="nav-link <?= $current_page === 'dashboard_users.php' ? 'active' : ''; ?>" href="dashboard_users.php">
            <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
              <i class="fa fa-home <?= $current_page === 'dashboard_users.php' ? 'text-white' : 'text-dark'; ?> text-lg" style="<?= $current_page === 'dashboard_users.php' ? 'padding-bottom: 2px;' : 'padding-bottom: 5px;'; ?>">
                
              </i>
            </div>
            <span class="nav-link-text ms-1">Accueil</span>
          </a>
        </li>


        <?php if ($userRole === 'Chauffeur' || $userRole === 'Passager & Chauffeur') : ?> 
            <li class="nav-item">
              <a class="nav-link <?= $current_page === 'new_ride.php' ? 'active' : ''; ?>" href="new_ride.php">
                <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                  <i class=" <?= $current_page === 'new_ride.php' ? 'text-white' : 'text-dark'; ?> text-lg" style="<?= $current_page === 'new_ride.php' ? 'padding-bottom: 2px;' : 'padding-bottom: 5px;'; ?>">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-car-front" viewBox="0 0 16 16">
                      <path d="M4 9a1 1 0 1 1-2 0 1 1 0 0 1 2 0m10 0a1 1 0 1 1-2 0 1 1 0 0 1 2 0M6 8a1 1 0 0 0 0 2h4a1 1 0 1 0 0-2zM4.862 4.276 3.906 6.19a.51.51 0 0 0 .497.731c.91-.073 2.35-.17 3.597-.17s2.688.097 3.597.17a.51.51 0 0 0 .497-.731l-.956-1.913A.5.5 0 0 0 10.691 4H5.309a.5.5 0 0 0-.447.276"/>
                      <path d="M2.52 3.515A2.5 2.5 0 0 1 4.82 2h6.362c1 0 1.904.596 2.298 1.515l.792 1.848c.075.175.21.319.38.404.5.25.855.715.965 1.262l.335 1.679q.05.242.049.49v.413c0 .814-.39 1.543-1 1.997V13.5a.5.5 0 0 1-.5.5h-2a.5.5 0 0 1-.5-.5v-1.338c-1.292.048-2.745.088-4 .088s-2.708-.04-4-.088V13.5a.5.5 0 0 1-.5.5h-2a.5.5 0 0 1-.5-.5v-1.892c-.61-.454-1-1.183-1-1.997v-.413a2.5 2.5 0 0 1 .049-.49l.335-1.68c.11-.546.465-1.012.964-1.261a.8.8 0 0 0 .381-.404l.792-1.848ZM4.82 3a1.5 1.5 0 0 0-1.379.91l-.792 1.847a1.8 1.8 0 0 1-.853.904.8.8 0 0 0-.43.564L1.03 8.904a1.5 1.5 0 0 0-.03.294v.413c0 .796.62 1.448 1.408 1.484 1.555.07 3.786.155 5.592.155s4.037-.084 5.592-.155A1.48 1.48 0 0 0 15 9.611v-.413q0-.148-.03-.294l-.335-1.68a.8.8 0 0 0-.43-.563 1.8 1.8 0 0 1-.853-.904l-.792-1.848A1.5 1.5 0 0 0 11.18 3z"/>
                    </svg>
                  </i>
                </div>
                <span class="nav-link-text ms-1">Nouveau Covoiturage</span>
              </a>
            </li>
        <?php endif ?>



        <li class="nav-item mt-3">
          <h6 class="ps-4 ms-2 text-uppercase text-xs font-weight-bolder opacity-6">Ajustements</h6>
        </li>
        <li class="nav-item">
          <a class="nav-link <?= $current_page === 'records.php' ? 'active' : ''; ?>" href="records.php">
            <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                <i class="fa fa-bullhorn <?= $current_page === 'records.php' ? 'text-white' : 'text-dark'; ?> text-lg" style="<?= $current_page === 'records.php' ? 'padding-bottom: 2px;' : 'padding-bottom: 5px;'; ?>"></i>
            </div>
            <span class="nav-link-text ms-1">Covoiturages</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link <?= $current_page === 'settings.php' ? 'active' : ''; ?>" href="settings.php">
            <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                <i class="fa fa-cog <?= $current_page === 'settings.php' ? 'text-white' : 'text-dark'; ?> text-lg" style="<?= $current_page === 'settings.php' ? 'padding-bottom: 2px;' : 'padding-bottom: 5px;'; ?>"></i>
            </div>
            <span class="nav-link-text ms-1">Paramètres</span>
          </a>
        </li>

        <li class="nav-item">
          <a class="nav-link <?= $current_page === 'history.php' ? 'active' : ''; ?>" href="history.php">
            <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                <i class="<?= $current_page === 'history.php' ? 'text-white' : 'text-dark'; ?> text-lg" style="<?= $current_page === 'history.php' ? 'padding-bottom: 2px;' : 'padding-bottom: 5px;'; ?>"><svg class="text-dark" width="16px" height="16px" viewBox="0 0 40 44" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"> <title>document</title> <g id="Basic-Elements" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"> <g id="Rounded-Icons" transform="translate(-1870.000000, -591.000000)" fill="#FFFFFF" fill-rule="nonzero"> <g id="Icons-with-opacity" transform="translate(1716.000000, 291.000000)"> <g id="document" transform="translate(154.000000, 300.000000)"> <path class="color-background" d="M40,40 L36.3636364,40 L36.3636364,3.63636364 L5.45454545,3.63636364 L5.45454545,0 L38.1818182,0 C39.1854545,0 40,0.814545455 40,1.81818182 L40,40 Z" id="Path" opacity="0.603585379"></path> <path class="color-background" d="M30.9090909,7.27272727 L1.81818182,7.27272727 C0.814545455,7.27272727 0,8.08727273 0,9.09090909 L0,41.8181818 C0,42.8218182 0.814545455,43.6363636 1.81818182,43.6363636 L30.9090909,43.6363636 C31.9127273,43.6363636 32.7272727,42.8218182 32.7272727,41.8181818 L32.7272727,9.09090909 C32.7272727,8.08727273 31.9127273,7.27272727 30.9090909,7.27272727 Z M18.1818182,34.5454545 L7.27272727,34.5454545 L7.27272727,30.9090909 L18.1818182,30.9090909 L18.1818182,34.5454545 Z M25.4545455,27.2727273 L7.27272727,27.2727273 L7.27272727,23.6363636 L25.4545455,23.6363636 L25.4545455,27.2727273 Z M25.4545455,20 L7.27272727,20 L7.27272727,16.3636364 L25.4545455,16.3636364 L25.4545455,20 Z" id="Shape"></path> </g> </g> </g> </g> </svg>
                </i>
            </div>
            <span class="nav-link-text ms-1">Historique</span>
          </a>
        </li>

        <?php 

        $currentUserId = $_SESSION['loggedInUser']['user_id'] ?? null;
        $roleId = null;
        if ($currentUserId) {
            $stmt = $db->prepare("SELECT role_id FROM user_as_role WHERE user_id = ? LIMIT 1");
            $stmt->execute([$currentUserId]);
            $roleId = $stmt->fetchColumn();
        }
        
        ?>

        <?php if ($roleId == 2): ?>

        <li class="nav-item">
          <a class="nav-link <?= $current_page === 'reviews_suggestions.php' ? 'active' : ''; ?>" href="reviews_suggestions.php">
            <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                <i class="<?= $current_page === 'reviews_suggestions.php' ? 'text-white' : 'text-dark'; ?> text-lg" style="<?= $current_page === 'reviews_suggestions.php' ? 'padding-bottom: 2px;' : 'padding-bottom: 5px;'; ?>">
                  <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pen-fill" viewBox="0 0 16 16">
                    <path d="m13.498.795.149-.149a1.207 1.207 0 1 1 1.707 1.708l-.149.148a1.5 1.5 0 0 1-.059 2.059L4.854 14.854a.5.5 0 0 1-.233.131l-4 1a.5.5 0 0 1-.606-.606l1-4a.5.5 0 0 1 .131-.232l9.642-9.642a.5.5 0 0 0-.642.056L6.854 4.854a.5.5 0 1 1-.708-.708L9.44.854A1.5 1.5 0 0 1 11.5.796a1.5 1.5 0 0 1 1.998-.001"/>
                  </svg>
                </i>
            </div>
            <span class="nav-link-text ms-1">Avis</span>
          </a>
        </li>

        <?php endif ?>

      </ul>
    </div>
    <div class="sidenav-footer mx-3 ">
      
      <a class="btn btn-dark mt-3 w-100" href="../logout.php">
        Déconnexion
      </a>

    </div>
  </aside>