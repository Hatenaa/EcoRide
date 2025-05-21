<?php

require_once('../../config/function.php');
require_once('../../config/connect.php');

// Traitement
$reviewId = intval($_POST['review_id'] ?? 0);
$action = $_POST['action'] ?? '';

// Récupération de l’auteur du commentaire
$stmt = $db->prepare("
    SELECT u.firstname, u.name 
    FROM reviews r 
    JOIN users u ON r.reviewer_id = u.user_id 
    WHERE r.review_id = ?
");
$stmt->execute([$reviewId]);
$author = $stmt->fetch(PDO::FETCH_ASSOC);
$authorName = $author ? "{$author['firstname']} {$author['name']}" : "utilisateur inconnu";

// Validation / refus du commentaire
if ($reviewId && in_array($action, ['accept', 'reject'])) {
    if ($action === 'accept') {
        $stmt = $db->prepare("UPDATE reviews SET status = 'Validé' WHERE review_id = ?");
        $stmt->execute([$reviewId]);
        $_SESSION['status'] = "<div class='alert alert-success mt-4'>L'avis de <strong>$authorName</strong> a été validé.</div>";
    } else {
        $stmt = $db->prepare("UPDATE reviews SET status = 'Refusé' WHERE review_id = ?");
        $stmt->execute([$reviewId]);
        $_SESSION['status'] = "<div class=\"alert alert-warning mt-4\">L'avis de <strong>$authorName</strong> a été mis à la corbeille.</div>";
    }
} else {
    $_SESSION['status'] = "<div class='alert alert-danger mt-4'>Requête invalide.</div>";
}

header("Location: reviews_suggestions.php");
exit;

