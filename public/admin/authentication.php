<?php

include realpath($_SERVER['DOCUMENT_ROOT'] . '/../config/connect.php');

// Démarrer la session si nécessaire
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Vérification de l'authentification
if (isset($_SESSION['auth']) && $_SESSION['auth'] === true) {

    if (isset($_SESSION['loggedInUser'])) {

        $user_id = validate($_SESSION['loggedInUser']['user_id']);
        $email = validate($_SESSION['loggedInUser']['email']);

        // Requête sécurisée avec des paramètres
        $query = "SELECT * FROM `users` WHERE `user_id` = :user_id AND `email` = :email LIMIT 1";

        // Préparer la requête
        $stmt = $db->prepare($query);

        // Lier les paramètres
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);

        // Exécuter la requête
        $stmt->execute();

        // Récupérer le résultat
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result) {

            // Utilisateur valide
            $user = $result;

            // Vérifier si l'utilisateur est un administrateur
            if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {

                // Rediriger vers la page d'accueil si l'utilisateur n'est pas un administrateur
                redirect('../../public/pages/home.php', 'Accès refusé. Administrateur seulement.');
                exit();
            }

        } else {
            // Si l'utilisateur n'est pas trouvé, on déconnecte la session
            logoutSession();
            redirect('../../login.php', 'Accès refusé.');
        }

    } else {
        // Rediriger vers la page de connexion si l'utilisateur n'est pas authentifié
        redirect('../../login.php', 'Accès refusé. Veuillez vous connecter avant.');
    }

} else {
    // Rediriger vers la page de connexion si l'utilisateur n'est pas authentifié
    redirect('../../login.php', 'Accès refusé. Veuillez vous connecter avant.');
}

if ($result) {
    // Vérification si l'utilisateur est suspendu
    if ($result['suspended'] == 1) {
        logoutSession();
        redirect('../../login.php', 'Accès refusé. Veuillez vous connecter avant.');
        exit();
    }

    // Utilisateur valide
    $user = $result;

    // Vérifier si l'utilisateur est un administrateur
    if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
        redirect('/', 'Accès refusé. Administrateur seulement.');
        exit();
    }
    
} else {
    logoutSession();
    redirect('/connexion', 'Accès refusé.');
}


?>
