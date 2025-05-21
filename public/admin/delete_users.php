<?php

$pageTitle = 'Delete Users';
require '../../config/connect.php';
require '../../config/function.php';

// Vérifier et valider l'ID de l'utilisateur
$userId = isset($_GET['id']) && is_numeric($_GET['id']) ? validate($_GET['id']) : null;

if (!$userId || !getById('users', $userId)) {
    redirect('/admin/users.php', 'ID utilisateur invalide ou manquant');
    exit();
}

try {
    global $db;

    $stmt = $db->prepare("DELETE FROM users WHERE user_id = :id LIMIT 1");
    $stmt->bindParam(':id', $userId, PDO::PARAM_INT);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        redirect('/admin/users.php', 'L\'utilisateur a été supprimé avec succès');
    } else {
        redirect('/admin/users.php', 'Échec de la suppression de l\'utilisateur');
    }
} catch (Exception $e) {
    error_log("Error deleting user: " . $e->getMessage());
    redirect('/admin/users.php', 'Une erreur inattendue s\'est produite');
}

exit;

// Tenter de supprimer l'utilisateur
try {
    if (deleteQuery('users', $userId)) {
        
        redirect('/admin/users.php', 'L\'utilisateur a été supprimé avec succès');
    } else {
        redirect('/admin/users.php', 'Échec de la suppression de l\'utilisateur');
    }
} catch (Exception $e) {
    error_log("Error deleting user: " . $e->getMessage());
    redirect('/admin/users.php', 'Une erreur inattendue s\'est produite');
}
