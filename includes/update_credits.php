<?php

include('includes/header.php');

// Vérifier que l'utilisateur est connecté
if (!isset($_SESSION['loggedInUser'])) {
    echo json_encode(['error' => 'Utilisateur non connecté']);
    exit;
}

// Récupérer l'ID de l'utilisateur connecté
$userId = $_SESSION['loggedInUser']['user_id'];

// Récupérer les crédits à partir du fichier JSON
$credits = getUserCredits($userId);

// Mettre à jour la session avec les crédits actualisés
$_SESSION['loggedInUser']['credits'] = $credits;

// Envoyer les crédits mis à jour en réponse JSON
echo json_encode(['credits' => $credits]);
