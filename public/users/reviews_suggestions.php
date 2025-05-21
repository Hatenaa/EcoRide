<?php 
ob_start();

$pageTitle = "Validation des avis";
include('includes/header.php');
require('../../config/connect.php');

$currentUserId = $_SESSION['loggedInUser']['user_id'] ?? null;

if (!$currentUserId) {
    header('Location: ../../login.php');
    exit;
}

// On récupère le rôle de l'utilisateur
$stmt = $db->prepare("SELECT role_id FROM user_as_role WHERE user_id = ? LIMIT 1");
$stmt->execute([$currentUserId]);
$roleId = $stmt->fetchColumn();

// Vérifie si l'utilisateur est employé (role_id == 2)
if ($roleId != 2) {
    $_SESSION['status'] = "Page inaccessible, espace réservé aux employés.";
    header('Location: dashboard_users.php');
    exit;
}

$query = "
SELECT 
    r.review_id, r.comment, r.rating, r.status, r.carpooling_id,

    CONCAT(passenger.firstname, ' ', passenger.name) AS passager_name,
    passenger.email AS passager_email,

    CONCAT(driver.firstname, ' ', driver.name) AS chauffeur_name,
    driver.email AS chauffeur_email,

    c.departure_place, c.arrival_place, c.departure_date, c.arrival_date, c.departure_time, c.arrival_time

FROM reviews r

LEFT JOIN users passenger ON r.reviewer_id = passenger.user_id
LEFT JOIN users driver ON r.user_id = driver.user_id
LEFT JOIN carpools c ON r.carpooling_id = c.carpooling_id

WHERE r.status = 'En attente'
ORDER BY r.review_id DESC
";

$stmt = $db->query($query);
$reviews = $stmt->fetchAll();

/*var_dump($reviews);
exit;*/


?>
<head>
    <!-- Bootstrap Bundle avec Popper (requis pour les modales) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</head>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h4>Commentaires à valider
                    <a href="dashboard_users.php" class="btn btn-secondary float-end">Retour</a>
                </h4>
            </div>
            <div class="card-body">
                
                <?php 

                if (isset($_SESSION['status'])) {
                    echo $_SESSION['status'];
                    unset($_SESSION['status']);
                }
     
                ?>

                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Auteur</th>
                            <th>Chauffeur</th>
                            <th>Commentaire</th>
                            <th>Note</th>
                            <th>Trajet</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($reviews)) : ?>
                            <?php foreach ($reviews as $review) : ?>
                                <tr>
                                    <td>
                                        <?= htmlspecialchars($review['passager_name'] ?? '') ?><br>
                                        <small class="text-muted"><?= htmlspecialchars($review['passager_email'] ?? '') ?></small>
                                    </td>
                                    <td>
                                        <?= htmlspecialchars($review['chauffeur_name'] ?? '') ?><br>
                                        <small class="text-muted"><?= htmlspecialchars($review['chauffeur_email'] ?? '') ?></small>
                                    </td>
                                    <td><?= nl2br(htmlspecialchars($review['comment'] ?? '')) ?></td>
                                    <td><?= (int) $review['rating'] ?>/5</td>
                                    <td>
                                        <?= htmlspecialchars($review['departure_place'] ?? '') ?> → <?= htmlspecialchars($review['arrival_place'] ?? '') ?><br>
                                        <small>
                                            Départ : <?= htmlspecialchars($review['departure_date'] ?? '') ?> à <?= htmlspecialchars($review['departure_time'] ?? '') ?><br>
                                            Arrivée : <?= htmlspecialchars($review['arrival_date'] ?? '') ?> à <?= htmlspecialchars($review['arrival_time'] ?? '') ?>
                                        </small>
                                    </td>
                                    <td>
                                        <form method="POST" action="validate_review.php" class="d-flex flex-column">
                                            <input type="hidden" name="review_id" value="<?= htmlspecialchars($review['review_id']) ?>">
                                            <button name="action" value="accept" class="btn btn-success btn-sm mb-1">Valider</button>
                                            <button name="action" value="reject" class="btn btn-danger btn-sm mb-1">Refuser</button>

                                            
                                        </form>
                                        <?php if ((int) $review['rating'] <= 2) : ?> 
                                                <button class="btn btn-secondary btn-sm mb-1 w-100" data-bs-toggle="modal" data-bs-target="#modalDetail<?= $review['review_id'] ?>">
                                                    Détail
                                                </button>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <!-- Modal de détail -->
                                <div class="modal fade" id="modalDetail<?= $review['review_id'] ?>" tabindex="-1" aria-labelledby="modalLabel<?= $review['review_id'] ?>" aria-hidden="true">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="modalLabel<?= $review['review_id'] ?>">Détails du covoiturage</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                                    </div>
                                    <div class="modal-body">
                                        <ul class="list-group">
                                            <li class="list-group-item"><strong>ID du covoiturage :</strong> <?= htmlspecialchars($review['carpooling_id'] ?? 'N/A') ?></li>
                                            <li class="list-group-item"><strong>Passager :</strong> <?= htmlspecialchars($review['passager_name']) ?> - <?= htmlspecialchars($review['passager_email']) ?></li>
                                            <li class="list-group-item"><strong>Chauffeur :</strong> <?= htmlspecialchars($review['chauffeur_name']) ?> - <?= htmlspecialchars($review['chauffeur_email']) ?></li>
                                            <li class="list-group-item"><strong>Départ :</strong> <?= $review['departure_place'] ?> le <?= $review['departure_date'] ?> à <?= $review['departure_time'] ?></li>
                                            <li class="list-group-item"><strong>Arrivée :</strong> <?= $review['arrival_place'] ?> le <?= $review['arrival_date'] ?> à <?= $review['arrival_time'] ?></li>
                                            <li class="list-group-item"><strong>Remarque :</strong> <?= $review['comment'] ?></li>
                                        </ul>
                                    </div>
                                        <div class="modal-footer">
                                            <a href="mailto:<?= $review['chauffeur_email'] ?>?subject=Avis sur votre covoiturage&body=Bonjour, nous avons une remarque concernant le trajet n°<?= $review['carpooling_id'] ?>..." class="btn btn-primary">
                                                Contacter le chauffeur
                                            </a>
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                                        </div>
                                    </div>
                                </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <tr>
                                <td colspan="7" class="text-center text-muted">Aucun commentaire en attente</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>

            </div>
        </div>
    </div>
</div>



<?php ob_end_flush(); ?>