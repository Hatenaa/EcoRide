<?php
/*


define('BASE_PATH', realpath(__DIR__ . '/../'));
require_once BASE_PATH . '/vendor/autoload.php';

$path = $_SERVER['REQUEST_URI'];

// Convertit en chemin de fichier physique
$fullPath = realpath(__DIR__ . parse_url($path, PHP_URL_PATH));

// Vérifie si le fichier existe et est lisible
if ($fullPath && is_file($fullPath) && str_ends_with($fullPath, '.php')) {
    require $fullPath;
    exit;
}





$router = new AltoRouter();

$router->map('GET', '/', function() {
    require_once BASE_PATH . '/includes/header.php';
    require_once BASE_PATH . '/public/pages/home.php';
    require_once BASE_PATH . '/includes/footer.php';
});

$router->map('GET', '/nous-contacter', function (){
    require_once BASE_PATH . '/includes/header.php';
    require_once BASE_PATH . '/public/pages/contact.php';
    require_once BASE_PATH . '/includes/footer.php';
});

$router->map('GET', '/recherche', function (){
    require_once BASE_PATH . '/includes/header.php';
    require_once BASE_PATH . '/public/pages/search.php';
    require_once BASE_PATH . '/includes/footer.php';
});

$router->map('GET', '/mentions-legales', function (){
    require_once BASE_PATH . '/includes/header.php';
    require_once BASE_PATH . '/public/pages/legal.php';
    require_once BASE_PATH . '/includes/footer.php';
});

$router->map('GET', '/connexion', function (){
    require_once BASE_PATH . '/includes/header.php';
    require_once BASE_PATH . '/public/pages/connexion.php';
    require_once BASE_PATH . '/includes/footer.php';
});

$router->map('GET', '/inscription', function (){
    require_once BASE_PATH . '/includes/header.php';
    require_once BASE_PATH . '/public/pages/inscription.php';
    require_once BASE_PATH . '/includes/footer.php';
});

$router->map('GET', '/gestionnaire-de-connexion', function (){
    require __DIR__ . '/pages/auth/login_code.php';
});

$router->map('GET', '/deconnexion', function (){
    require BASE_PATH . '/config/logout.php';
});

$router->map('GET', '/credits', function (){
    require_once BASE_PATH . '/includes/header.php';
    require_once BASE_PATH . '/public/pages/credits.php';
    require_once BASE_PATH . '/includes/footer.php';
});

# Gestion des URL Admins


$router->map('GET', '/admin', function (){
    require_once BASE_PATH . '/admin/includes/header.php';
    require_once BASE_PATH . '/admin/dashboard_admin.php';
    require_once BASE_PATH . '/admin/includes/footer.php';
});

$router->map('GET', '/admin/utilisateurs', function (){
    require_once BASE_PATH . '/admin/includes/header.php';
    require_once BASE_PATH . '/admin/users.php';
    require_once BASE_PATH . '/admin/includes/footer.php';
});

$router->map('GET', '/admin/ajouter-utilisateur', function (){
    require_once BASE_PATH . '/admin/includes/header.php';
    require_once BASE_PATH . '/admin/create_users.php';
    require_once BASE_PATH . '/admin/includes/footer.php';
});

$router->map('GET', '/admin/modifier-utilisateur', function (){
    require_once BASE_PATH . '/admin/includes/header.php';
    require_once BASE_PATH . '/admin/edit_users.php';
    require_once BASE_PATH . '/admin/includes/footer.php';
});

$router->map('GET', '/admin/supprimer-utilisateur', function (){
    require_once BASE_PATH . '/admin/includes/header.php';
    require_once BASE_PATH . '/admin/edit_users.php';
    require_once BASE_PATH . '/admin/includes/footer.php';
});

$router->map('GET', '/admin/parametres', function (){
    require_once BASE_PATH . '/admin/includes/header.php';
    require_once BASE_PATH . '/admin/settings.php';
    require_once BASE_PATH . '/admin/includes/footer.php';
});

$router->map('GET', '/admin/controller', function (){
    require_once BASE_PATH . '/admin/controller.php';
});

$router->map('GET', '/admin/modifier-utilisateur-[i:id]', function ($id) {
    require_once BASE_PATH . '/admin/includes/header.php';

    $_GET['id'] = $id; 
    require_once BASE_PATH . '/admin/edit_users.php';

    require_once BASE_PATH . '/admin/includes/footer.php';
});

$router->map('GET', '/admin/supprimer-utilisateur-[i:id]', function ($id) {
    require_once BASE_PATH . '/admin/includes/header.php';

    $_GET['id'] = $id; 
    require_once BASE_PATH . '/admin/delete_users.php';

    require_once BASE_PATH . '/admin/includes/footer.php';
});

$router->map('GET', '/admin-css', function (){
    header('Content-Type: text/css');
    readfile(BASE_PATH . '/admin/assets/css/soft-ui-dashboard.css');
});


// Lancer la correspondance de route
$match = $router->match();


if ($match && is_callable($match['target'])) {

    call_user_func_array($match['target'], $match['params']);
} 


*/
?>