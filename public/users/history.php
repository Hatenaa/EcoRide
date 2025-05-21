<?php

$pageTitle = "Historique de voyage";
include('includes/header.php');
require('../../config/connect.php');

# Vérifier que l'utilisateur est bien connecté
if (!isset($_SESSION['auth']) || $_SESSION['auth'] !== true) {
    header('Location: ../../login.php');
    exit;
}

// Afficher l'historique des covoiturages terminés
$stmt = $db->prepare("
    SELECT c.carpooling_id, c.departure_place, c.arrival_place, c.departure_date, c.departure_time, c.arrival_date, c.arrival_time
    FROM carpools_as_users cu
    JOIN carpools c ON cu.carpooling_id = c.carpooling_id
    WHERE cu.user_id = ? AND cu.completed = 1
    ORDER BY c.arrival_date DESC, c.arrival_time DESC
");
$stmt->execute([$userId]);
$completedCarpools = $stmt->fetchAll(PDO::FETCH_ASSOC);



?>



<div class="row">
    <div class="col-md-12">

        <div class="card">
            <div class="card-header">
                <h1 class="h3">Historique des covoiturages</h1>

                <?php if (!empty($completedCarpools)): ?>
                    <div class='alert alert-success mt-4'><strong>Liste des trajets correctement clôturés</strong></div>
                        <?php foreach ($completedCarpools as $carpool) : ?>
                            <div class='alert alert-light'>
                                🛣 Trajet de <strong><?= $carpool['departure_place'] ?></strong> à <strong><?= $carpool['arrival_place'] ?></strong> 
                                <br> 📅 Départ : <?= $carpool['departure_date'] ?> à <?= $carpool['departure_time'] ?> | Arrivée : <?= $carpool['arrival_date'] ?> à <?= $carpool['arrival_time'] ?>
                                <br> ✅ <strong>Trajet terminé</strong>

                                <?php

                                $carpoolId = $carpool['carpooling_id'];

                                // Vérifie si cet utilisateur a déjà laissé un avis
                                $stmt = $db->prepare("SELECT COUNT(*) FROM reviews WHERE review_id = ? AND carpooling_id = ?");
                                $stmt->execute([$userId, $carpoolId]);
                                $alreadyReviewed = $stmt->fetchColumn();

                                ?>

                                    <form method="GET" action="reviews.php" style="padding-top: 10px;">
                                        <input type="hidden" name="carpooling_id" value="<?= $carpoolId ?>">
                                        <button type="submit" class="btn btn-success" style="margin-bottom: -5px;">Écrire un avis</button>
                                    </form>

                            </div>
                            
                        <?php endforeach ?>
                <?php else: ?>    
                    <p>Vous n'avez effectué aucun covoiturages.</p>
                <?php endif ?>
            </div>

        </div>
    </div>
</div>

<?php
include('includes/footer.php');
?>