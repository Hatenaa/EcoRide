<?php 

ob_start();
$pageTitle = 'Courses à venir';
include('includes/header.php'); 
require '../../config/connect.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

require '../../vendor/autoload.php';

# Vérifier que l'utilisateur est bien connecté
if (!isset($_SESSION['auth']) || $_SESSION['auth'] !== true) {
    header('Location: ../../login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    echo "<pre>";
    print_r($_POST);
    echo "</pre>";
}

$userId = $_SESSION['loggedInUser']['user_id'];

// Ici, on met à jour les trajets terminés
$stmt = $db->prepare("
    UPDATE carpools_as_users cu
    JOIN carpools c ON cu.carpooling_id = c.carpooling_id
    SET cu.completed = 1
    WHERE cu.user_id = ? 
    AND CONCAT(c.arrival_date, ' ', c.arrival_time) <= NOW()
    AND cu.completed = 0
");
$stmt->execute([$userId]);

// Et là, on récupère les trajets actifs
$stmt = $db->prepare("
    SELECT c.carpooling_id, c.departure_place, c.arrival_place, c.departure_date, c.departure_time, c.arrival_date, c.arrival_time, c.driver_id
    FROM carpools c
    LEFT JOIN carpools_as_users cu ON c.carpooling_id = cu.carpooling_id
    WHERE (cu.user_id = :user_id OR c.driver_id = :user_id)
    AND c.status = 'Disponible' AND cu.completed = 0
");

$stmt->execute([':user_id' => $userId]);
$activeCarpools = $stmt->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cancel_carpool'])) {
    $userId = intval($_POST['user_id']);
    $carpoolingId = intval($_POST['carpooling_id']);

    // Sécurité supplémentaire
    if (!$userId || !$carpoolingId) {
        $_SESSION['status'] = "<div class='alert alert-danger'>Requête invalide.</div>";
        header("Location: records.php");
        exit;
    }

    $stmt = $db->prepare("SELECT driver_id FROM carpools WHERE carpooling_id = ?");
    $stmt->execute([$carpoolingId]);
    $driverId = $stmt->fetchColumn();

    if ($driverId === false) {
        $_SESSION['status'] = "<div class='alert alert-danger'>Covoiturage introuvable.</div>";
        header("Location: records.php");
        exit;
    }

    try {
        $db->beginTransaction();

        if ($userId == $driverId) {
            // ✅ Annulation par le conducteur
            $stmt = $db->prepare("SELECT u.email, u.firstname, u.name, c.departure_place, c.arrival_place, c.departure_date, c.departure_time
                                  FROM carpools_as_users cu
                                  JOIN users u ON cu.user_id = u.user_id
                                  JOIN carpools c ON cu.carpooling_id = c.carpooling_id
                                  WHERE cu.carpooling_id = ? AND cu.user_id != ?");
            $stmt->execute([$carpoolingId, $userId]);
            $passengers = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($passengers as $passenger) {
                $mail = new PHPMailer(true);
                try {
                    $mail->isSMTP();
                    $mail->Host = 'smtp.gmail.com';
                    $mail->SMTPAuth = true;
                    $mail->Username = 'ecoride.no.reply@gmail.com';
                    $mail->Password = 'iclu cznf ulfh mems';
                    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                    $mail->Port = 587;
                    $mail->CharSet = 'UTF-8';

                    $mail->setFrom('ecoride.no.reply@gmail.com', 'EcoRide');
                    $mail->addAddress($passenger['email'], $passenger['firstname'] . ' ' . $passenger['name']);

                    $mail->isHTML(true);
                    $mail->Subject = 'Annulation de votre covoiturage';
                    $messageBody = "
                        <h2>Annulation de votre covoiturage</h2>
                        <p>Bonjour {$passenger['firstname']} {$passenger['name']},</p>
                        <p>Votre covoiturage a été annulé par le conducteur.</p>
                        <ul>
                            <li>Départ : {$passenger['departure_place']}</li>
                            <li>Arrivée : {$passenger['arrival_place']}</li>
                            <li>Date : {$passenger['departure_date']}</li>
                            <li>Heure : {$passenger['departure_time']}</li>
                        </ul>
                        <p>Nous vous invitons à réserver un autre trajet sur EcoRide.</p>
                    ";
                    $mail->Body = $messageBody;
                    $mail->AltBody = strip_tags($messageBody);
                    $mail->send();

                } catch (Exception $e) {
                    error_log("Erreur d'envoi à {$passenger['email']}: {$mail->ErrorInfo}");
                }
            }

            $db->prepare("DELETE FROM carpools_as_users WHERE carpooling_id = ?")->execute([$carpoolingId]);
            $db->prepare("UPDATE carpools SET status = 'Annulé' WHERE carpooling_id = ?")->execute([$carpoolingId]);
            $db->prepare("UPDATE carpools SET nb_place = nb_place + 1 WHERE carpooling_id = ?")->execute([$carpoolingId]);

            updateCredits($userId, 2);
            $_SESSION['loggedInUser']['credits'] += 2;

            $_SESSION['status'] = "<div class='alert alert-success'>Le covoiturage a été annulé. Les passagers ont été notifiés.</div>";
        } else {
            // ✅ Annulation par un passager
            $db->prepare("DELETE FROM carpools_as_users WHERE user_id = ? AND carpooling_id = ?")->execute([$userId, $carpoolingId]);
            $db->prepare("UPDATE carpools SET nb_place = nb_place + 1 WHERE carpooling_id = ?")->execute([$carpoolingId]);
            updateCredits($userId, 2);
            $_SESSION['loggedInUser']['credits'] += 2;
            $_SESSION['status'] = "<div class='alert alert-info'>Vous avez annulé votre participation à ce covoiturage.</div>";
        }

        $db->commit();
        header("Location: records.php");
        exit;

    } catch (Exception $e) {
        $db->rollBack();
        $_SESSION['status'] = "<div class='alert alert-danger'>Erreur : {$e->getMessage()}</div>";
        header("Location: records.php");
        exit;
    }
}

if (isset($_SESSION['status'])) {
    echo $_SESSION['status'];
    unset($_SESSION['status']);
}

// var_dump($_SESSION)

?> 

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h1 class="h3">Covoiturages à venir</h1>                        
            </div>
            
            <div class="card-body">
            <?php
                // Récupérer les trajets actifs avec le driver_id
                $stmt = $db->prepare("
                    SELECT 
                        c.carpooling_id,
                        c.departure_place,
                        c.arrival_place,
                        c.departure_date,
                        c.departure_time,
                        c.arrival_date,
                        c.arrival_time,
                        c.status,
                        c.driver_id
                    FROM carpools_as_users cu
                    JOIN carpools c ON cu.carpooling_id = c.carpooling_id
                    WHERE cu.user_id = ? AND cu.completed = 0
                ");
                $stmt->execute([$userId]);
                $activeCarpools = $stmt->fetchAll(PDO::FETCH_ASSOC);
            ?>

<?php if (!empty($activeCarpools)): ?>
    <?php foreach ($activeCarpools as $carpool): ?>
        <?php

        // Création des objets DateTime
        $departureDateTime = new DateTime($carpool['departure_date'] . ' ' . $carpool['departure_time']);
        $arrivalDateTime = new DateTime($carpool['arrival_date'] . ' ' . ($carpool['arrival_time'] ?? '00:00'));
        $now = new DateTime();

        // Calculs de timestamp
        $departureTimestamp = $departureDateTime->getTimestamp();
        $arrivalTimestamp = $arrivalDateTime->getTimestamp();
        $nowTimestamp = $now->getTimestamp();

        // Calcul du temps restant avant départ
        $timeDiff = $departureTimestamp - $nowTimestamp;

        // Affichage du compte à rebours
        $countdown = $timeDiff > 0 
            ? "Début dans " . floor($timeDiff / 3600) . " h " . floor(($timeDiff % 3600) / 60) . " min" 
            : "En cours";

        // Vérifions si l'utilisateur est le conducteur
        $isDriver = ($carpool['driver_id'] == $userId);

        // Calcul de la durée totale du covoiturage
        $durationSeconds = $arrivalTimestamp - $departureTimestamp;
        $hours = floor($durationSeconds / 3600);
        $minutes = floor(($durationSeconds % 3600) / 60);
        $carpoolDuration = $hours . ' h ' . $minutes . ' min';

        // Autorisation ou non d'annuler
        $canCancel = $timeDiff > 86400; // 24h

        ?>

        <?php // var_dump($carpool) ?>
        <div class='alert alert-secondary'>

    <?php if ($isDriver): ?>
        <span class="badge bg-info">Vous êtes le conducteur</span><br>
    <?php endif; ?>

    🛣 Trajet de <strong><?= htmlspecialchars($carpool['departure_place']) ?></strong> à <strong><?= htmlspecialchars($carpool['arrival_place']) ?></strong><br>
    📅 Départ : <?= htmlspecialchars($carpool['departure_date']) ?> à <?= htmlspecialchars($carpool['departure_time']) ?><br>
    
    <?php if ($carpool['status'] == 'Disponible') : ?> 
        ⏳ <strong><?= $countdown ?></strong><br>
        <?php elseif ($carpool['status'] == 'En cours') : ?>
        ⏳ <strong>Trajet en cours...</strong><br> 
        <?php else : ?>
        ⏳ Durée : <strong><?= $carpoolDuration ?></strong><br> 
    <?php endif ?>

    <?php if ($isDriver): ?>

        <?php
        // Vérification du nombre de passagers
        $stmt = $db->prepare("SELECT COUNT(*) FROM carpools_as_users WHERE carpooling_id = ? AND user_id != ?");
        $stmt->execute([$carpool['carpooling_id'], $userId]);
        $passengerCount = $stmt->fetchColumn();
        ?>

        <?php if ($passengerCount == 0): ?>
            <span class="badge bg-secondary">Aucun autre utilisateur ne participe à ce covoiturage</span>

            <?php if ($isDriver && $carpool['status'] === 'Disponible' && $canCancel): ?>
                <form method="POST" action="records.php" style="margin-top: 10px;">
                    <input type="hidden" name="carpooling_id" value="<?= $carpool['carpooling_id'] ?>">
                    <input type="hidden" name="user_id" value="<?= $userId ?>">
                    <button type="submit" name="cancel_carpool" class="btn btn-secondary btn-sml">Annuler le covoiturage</button>
                </form>
            <?php endif; ?>

        <?php else: ?>

            <?php

            $departureDateTime = strtotime($carpool['departure_date'] . ' ' . $carpool['departure_time']);
            $arrivalDateTime = strtotime($carpool['arrival_date'] . ' ' . $carpool['arrival_time']);
            $now = time();
            $startWindow = $departureDateTime - (15 * 60);
            $endWindow = $arrivalDateTime + (30 * 60); 


            if (isset($_POST['start_carpool'])) {
                $carpoolingId = $_POST['carpooling_id'];
                
                // Mettre à jour le statut à "En cours"
                $sql = "UPDATE carpools SET status = 'En cours' WHERE carpooling_id = ?";
                $stmt = $db->prepare($sql);
                $stmt->execute([$carpoolingId]);
                
                // Rediriger ou recharger la page
                header("Location: records.php"); 
                exit;
            }
            
            if (isset($_POST['end_carpool'])) {

                $carpoolingId = intval($_POST['carpooling_id']);

                $stmt = $db->prepare("SELECT driver_id FROM carpools WHERE carpooling_id = ?");
                $stmt->execute([$carpoolingId]);

                
                $driverId = $stmt->fetchColumn();

                $carpoolingId = intval($_POST['carpooling_id']);
                
                // Mettre à jour le statut à "Terminé"
                $sql = "UPDATE carpools SET status = 'Terminé' WHERE carpooling_id = ?";
                $stmt = $db->prepare($sql);
                $stmt->execute([$carpoolingId]);

                require_once '../../config/function.php';
                updateDailyStats('rides_completed');

                // On récupère les mails de passagers
                $sql = "SELECT u.email, u.firstname, u.name, c.departure_place, c.arrival_place, c.departure_date, c.departure_time
                        FROM carpools_as_users cu
                        JOIN users u ON cu.user_id = u.user_id
                        JOIN carpools c ON cu.carpooling_id = c.carpooling_id
                        WHERE cu.carpooling_id = ? AND cu.user_id != ?";

                $stmt = $db->prepare($sql);
                $stmt->execute([$carpoolingId, $driverId]);
                $passengers = $stmt->fetchAll(PDO::FETCH_ASSOC);
                
                
                /**
                 * Maintenant, on vas se charger d'envoyer individuellement chaque
                 * mail à chaque passager pour : confirmer que c'est terminé; mettre les crédits
                 * du chauffeur à jour si le trajer s'est bien passé; demandé au passager 
                 * de soumettre un avis négatif ou positif (qui sera approuvé ou non par un employé)
                 */

                 foreach ($passengers as $passenger) {
                    $mail = new PHPMailer(true);
                
                    try {
                        $mail->isSMTP();
                        $mail->Host = 'smtp.gmail.com';
                        $mail->SMTPAuth = true;
                        $mail->Username = 'ecoride.no.reply@gmail.com';
                        $mail->Password = 'iclu cznf ulfh mems';
                        $mail->SMTPSecure = 'tls';
                        $mail->Port = 587;
                        $mail->CharSet = 'UTF-8';
                
                        $mail->setFrom('ecoride.no.reply@gmail.com', 'EcoRide');
                        $mail->addAddress($passenger['email'], $passenger['firstname'] . ' ' . $passenger['name']);
                        $mail->isHTML(true);
                        $mail->Subject = 'Confirmez votre trajet EcoRide';
                        $mail->Body = "
                            Bonjour {$passenger['firstname']},
                
                            Votre trajet de {$passenger['departure_place']} à {$passenger['arrival_place']} du {$passenger['departure_date']} à {$passenger['departure_time']} est maintenant terminé.
                
                            Merci de vous connecter à votre espace pour :
                            - valider que le trajet s’est bien déroulé,
                            - laisser une note et un avis sur le chauffeur.
                
                            À très vite sur EcoRide !";
                
                        if ($mail->send()) {
                            echo "✅ Mail envoyé à {$passenger['email']}<br>";
                        } else {
                            echo "❌ Échec d'envoi pour {$passenger['email']} : {$mail->ErrorInfo}<br>";
                        }
                
                    } catch (Exception $e) {
                        echo "🚨 Exception PHPMailer : " . $mail->ErrorInfo . "<br>";
                    }
                
                }

                header("Location: records.php");
                exit;
            }

           
            

            ?>

            <div style="display: flex; gap: 10px; margin-top: 10px;flex-wrap: wrap;align-items: center;">
                
                <?php 
                if ($now >= $startWindow && $now <= $departureDateTime) {
                    if ($carpool['status'] == 'Disponible') {
                ?>
                        <!-- Bouton Démarrer -->
                        <form method="POST" action="records.php">
                            <input type="hidden" name="user_id" value="<?= $userId ?>">
                            <input type="hidden" name="carpooling_id" value="<?= $carpool['carpooling_id'] ?>">
                            <button type="submit" name="start_carpool" class="btn btn-primary btn-sm">Démarrer</button>
                        </form>
                <?php
                    } 
                }

                if ($carpool['status'] == 'En cours') {
                ?>
                        <form method="POST" action="records.php">
                            <input type="hidden" name="user_id" value="<?= $userId ?>">
                            <input type="hidden" name="carpooling_id" value="<?= $carpool['carpooling_id'] ?>">
                            <button type="submit" name="end_carpool" class="btn btn-success btn-sm">Arrivée à destination</button>
                        </form>
                <?php
                    } elseif ($carpool['status'] == 'Terminé') {
                        echo "<span class='badge' style=\"background-color: white;color: black;\">Trajet terminé</span>";
                    }
                    
                ?>
                

                

                <?php if ($isDriver): ?>
                    <?php if ($canCancel): ?>
                        <form method="POST" action="cancel_carpool.php">
                            <input type="hidden" name="carpooling_id" value="<?= $carpool['carpooling_id'] ?>">
                            <button type="submit" class="btn-cancel">Annuler le covoiturage</button>
                        </form>
                    <?php else: ?>
                        <div style="display: flex; gap: 10px; margin-top: ;margin-bottom: 7px;">
                            <span class="badge bg-secondary">Annulation impossible à moins de 24h</span>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>

                

                <!-- Bouton Arrivée -->
                <?php if ($carpool['status'] == 'en cours' && $now <= $endWindow): ?>
                    <form method="POST" action="records.php">
                        <input type="hidden" name="user_id" value="<?= $userId ?>">
                        <input type="hidden" name="carpooling_id" value="<?= $carpool['carpooling_id'] ?>">
                        <button type="submit" name="end_carpool" class="btn btn-success btn-sm">Arrivée à destination</button>
                    </form>
                <?php endif; ?>
            </div>

        <?php endif; ?>

    <?php else: ?>
        <?php if ($carpool['status'] == 'Disponible' && $carpool['status'] != 'En cours' && $carpool['status'] != 'Terminé') : ?>
            <!-- Si l'utilisateur n'est pas le conducteur -->
            <form method="POST" action="records.php" onsubmit="return confirm('Êtes-vous sûr de vouloir annuler votre covoiturage ?');">
                <input type="hidden" name="user_id" value="<?= $userId ?>">
                <input type="hidden" name="carpooling_id" value="<?= $carpool['carpooling_id'] ?>">
                <button type="submit" name="cancel_carpool" class="btn btn-danger btn-sm mt-2">Annuler le covoiturage</button>
            </form>
        <?php endif; ?>
        <?php if ($carpool['status'] == 'Terminé' && $userId != $carpool['driver_id']) {

            // Est-ce que le passager a validé ?
            $stmt = $db->prepare("SELECT completed FROM carpools_as_users WHERE user_id = ? AND carpooling_id = ?");
            $stmt->execute([$userId, $carpool['carpooling_id']]);
            $completed = $stmt->fetchColumn();

            if (isset($_POST['validate_arrival'])) {

                $carpoolingId = $_POST['carpooling_id'];
            
                // 1. Marquer ce passager comme ayant validé
                $stmt = $db->prepare("UPDATE carpools_as_users SET completed = 1 WHERE user_id = ? AND carpooling_id = ?");
                $stmt->execute([$userId, $carpoolingId]);
            
                // 2. Vérifier s’il reste des passagers non validés
                $sql = "SELECT COUNT(*) FROM carpools_as_users 
                        WHERE carpooling_id = ? 
                          AND user_id != (SELECT driver_id FROM carpools WHERE carpooling_id = ?) 
                          AND completed = 0";

                $stmt = $db->prepare($sql);
                $stmt->execute([$carpoolingId, $carpoolingId]);
                $nonValidés = $stmt->fetchColumn();
            
                if ($nonValidés == 0) {

                    // Si tous les passagers ont validé..
                    $stmt = $db->prepare("SELECT driver_id FROM carpools WHERE carpooling_id = ?");
                    $stmt->execute([$carpoolingId]);
                    $driverId = $stmt->fetchColumn();
            
                    // Alors, -> On crédite le chauffeur de trois:
                    $result = updateCredits($driverId, 3);
                    
                }
            
                header("Location: records.php?success=1");
                exit;
            }


            if (!$completed) {
                ?>
                <form method='POST' action='records.php' style="padding-top: 10px;">
                    <input type='hidden' name='carpooling_id' value='<?= $carpool['carpooling_id'] ?>'>
                    <button type='submit' name='validate_arrival' class='btn btn-success'>Je suis bien arrivé !</button>
                </form>
                <?php
            } else {
                ?>
                    <span class='badge bg-success'>Trajet validé</span>
                <?php
                }
            }

        ?>
    <?php endif; ?>

</div>

                
        <?php endforeach; ?>
            <?php else: ?>
                <div class='alert alert-warning'>Vous ne participez à aucun covoiturage.</div>
                <a href='../../search.php' class='btn btn-primary'>Rechercher un covoiturage</a>
            <?php endif; ?>
                 
            </div>
        </div>
    </div>
</div>

<?php

include('includes/footer.php');
ob_end_flush();

?>
