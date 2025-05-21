<?php
ob_start(); 

$pageTitle = "Ajouter un avis";
include('includes/header.php');
require('../../config/connect.php');

# Vérifier que l'utilisateur est bien connecté
if (!isset($_SESSION['auth']) || $_SESSION['auth'] !== true) {
    header('Location: ../../login.php');
    exit;
}

$userId = $_SESSION['loggedInUser']['user_id'];

// Récupère l'ID du covoiturage via GET
$carpoolingId = isset($_GET['carpooling_id']) ? intval($_GET['carpooling_id']) : null;

if (!$carpoolingId) {
    echo "<div class='alert alert-danger'>Covoiturage non trouvé.</div>";
    exit;
}

// Empêche les doublons
$stmt = $db->prepare("SELECT COUNT(*) FROM reviews WHERE reviewer_id = ? AND carpooling_id = ?");
$stmt->execute([$userId, $carpoolingId]);
if ($stmt->fetchColumn() > 0) {
    echo "<div class='alert alert-warning'><strong>Attention...</strong> Vous avez déjà laissé un avis pour ce trajet.</div>";
    exit;
}

// Récupère le chauffeur
$stmt = $db->prepare("SELECT driver_id FROM carpools WHERE carpooling_id = ?");
$stmt->execute([$carpoolingId]);
$driverId = $stmt->fetchColumn();

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $rating = intval($_POST['rating']);
    $comment = trim($_POST['comment']);

    $stmt = $db->prepare("INSERT INTO reviews (user_id, reviewer_id, carpooling_id, rating, comment, status)
                          VALUES (?, ?, ?, ?, ?, 'En attente')");

    $stmt->execute([$driverId, $userId, $carpoolingId, $rating, $comment]);

    // On ajoute le message de succès
    $_SESSION['status'] = "Votre avis a été ajouté avec succès.";

    // Redirection après insertion pour éviter les re-posts
    header("Location: dashboard_users.php");
    exit;
}
?>

<?php if (isset($_GET['success']) && $_GET['success'] == 1): ?>
    <div class='alert alert-success'><strong>Merci !</strong> Votre avis a été envoyé pour validation.</div>
<?php endif; ?>

<div class="card">
    <div class="card-body">
    <h2 class="mt-4">Laisser un avis sur votre trajet</h2>
    <form method="POST" class="mt-3">
        <label for="rating">Note :</label>
        <select name="rating" class="form-select w-25" required>
            <option value="5">⭐⭐⭐⭐⭐</option>
            <option value="4">⭐⭐⭐⭐</option>
            <option value="3">⭐⭐⭐</option>
            <option value="2">⭐⭐</option>
            <option value="1">⭐</option>
        </select>

        <br>
        <label for="comment">Votre commentaire :</label><br>
        <textarea name="comment" class="form-control w-50" rows="4" required></textarea><br>
        <button type="submit" class="btn btn-primary">Envoyer l'avis</button>
    </form>
    </div>
</div>


<?php 
include('includes/footer.php'); 
ob_end_flush();
?>
