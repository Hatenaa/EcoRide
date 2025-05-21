<?php

// Inclure le fichier des fonctions
include realpath($_SERVER['DOCUMENT_ROOT'] . '/../config/function.php');

// Démarrer la session si ce n'est pas déjà fait
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Vérifier si l'utilisateur est authentifié
if (isset($_SESSION['auth'])) {
    
    // Déconnecter l'utilisateur en détruisant la session
    logoutSession();

    // Rediriger l'utilisateur avec un message de succès
    redirect('login.php', 'Vous avez été déconnecté.');
} else {
    // Si l'utilisateur n'est pas authentifié, le rediriger vers la page de connexion
    redirect('login.php', 'Aucune session active n\'a été trouvée.');
}
?>
