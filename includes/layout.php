<?php

// On inclut le header.php si...
if (isset($AdminHeader) && $AdminHeader === true) {

    // C'est une page administrateur.
    require_once BASE_PATH . '/admin/includes/header.php';

} else {

    // Si c'est une page standard.
    require_once __DIR__ . '/../includes/header.php';
}
