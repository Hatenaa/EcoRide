<?php
ob_start();
require('../../config/connect.php');

// Inclure le fichier header.php
$pageTitle = 'Ajouter un Covoiturage';
include('includes/header.php');

# var_dump($_SESSION);

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['auth']) || $_SESSION['auth'] !== true) {
    $_SESSION['status'] = "Vous devez être connecté pour ajouter un covoiturage.";
    header('Location: ../../login.php');
    exit;
}

$userId = $_SESSION['loggedInUser']['user_id'];

# Récupérer le rôle de l'utilisateur
$stmt = $db->prepare("
    SELECT r.label 
    FROM user_as_role ur
    JOIN roles r ON ur.role_id = r.role_id
    WHERE ur.user_id = ?
");

$stmt->execute([$userId]);
$userRoleLabel = $stmt->fetchColumn();

# Vérifier si l'utilisateur est bien chauffeur
# var_dump($userRoleLabel);


# Récupérer les véhicules du chauffeur avec leur marque
$stmt = $db->prepare("
    SELECT c.car_id, b.label AS brand_name, c.car_model 
    FROM ecoride.cars c
    JOIN ecoride.brands b ON c.car_brand_id = b.brand_id
    WHERE c.car_user_id = ?
");
$stmt->execute([$userId]);
$vehicles = $stmt->fetchAll(PDO::FETCH_ASSOC);


if ($userRoleLabel !== 'Chauffeur' && $userRoleLabel !== 'Passager & Chauffeur') {
    $_SESSION['status'] = "<div class='alert alert-warning'>Seuls les chauffeurs peuvent ajouter un covoiturage.</div>";
    header('Location: dashboard_users.php');
    exit;
}

# Vérifier si le formulaire a été soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['next_step'])) {

    $departure_place = trim($_POST['departure_place']);
    $departure_date = trim($_POST['departure_date']);
    $departure_time = trim($_POST['departure_time']);
    $arrival_place = trim($_POST['arrival_place']);
    $arrival_date = trim($_POST['arrival_date']);
    $arrival_time = trim($_POST['arrival_time']);
    $nb_place = trim($_POST['nb_place']);
    $ride_price = trim($_POST['ride_price']);
    $car_id = isset($_POST['car_id']) ? trim($_POST['car_id']) : null; // ✅ Vérification de la sélection
    $status = 'Disponible';
    $driver_id = $userId;

    // 🔹 Validation des entrées
    if (empty($departure_place) || empty($arrival_place) || empty($ride_price) || empty($nb_place) || empty($car_id)) {
        $_SESSION['status'] = "<div class='alert alert-danger'>Veuillez remplir tous les champs et sélectionner un véhicule.</div>";
        header('Location: new_ride.php');
        exit;
    }

    // 🔹 Insérer le covoiturage dans la base de données
    $insertRide = $db->prepare("
        INSERT INTO ecoride.carpools (car_id, departure_date, departure_time, departure_place, 
        arrival_date, arrival_time, arrival_place, status, nb_place, person_price, driver_id) 
        VALUES (:car_id, :departure_date, :departure_time, :departure_place, :arrival_date, 
        :arrival_time, :arrival_place, :status, :nb_place, :person_price, :driver_id)
    ");

    $success = $insertRide->execute([
        ':car_id' => $car_id,
        ':driver_id' => $driver_id,
        ':departure_date' => $departure_date,
        ':departure_time' => $departure_time,
        ':departure_place' => $departure_place,
        ':arrival_date' => $arrival_date,
        ':arrival_time' => $arrival_time,
        ':arrival_place' => $arrival_place,
        ':status' => $status,
        ':nb_place' => $nb_place,
        ':person_price' => $ride_price
    ]);

   if ($_SESSION['loggedInUser']['credits'] < 2) {
        $_SESSION['message'] = "Vous n'avez pas assez de crédits: Veuillez recharger votre compte.";
        $_SESSION['message_type'] = "warning";
        header('Location: ../../credits.php');
        exit;
        
    } 

if ($success) {

    // Débiter les crédits
    $creditStatus = updateCredits($userId, -2);
    
    if (strpos($creditStatus, "Crédits mis à jour avec succès") !== false) {
        $_SESSION['loggedInUser']['credits'] -= 2;
        $_SESSION['status'] = "Votre covoiturage a été enregistré avec succès.";
    } else {
        $_SESSION['status'] = "$creditStatus";
    }

    // Ajouter le conducteur dans la table carpools_as_users
    $insertParticipation = $db->prepare("
        INSERT INTO carpools_as_users (carpooling_id, user_id, completed)
        VALUES (LAST_INSERT_ID(), ?, 0)
    ");
    $insertParticipation->execute([$userId]);

    require_once '../../config/function.php';
    updateDailyStats('rides_created');

    unset($_SESSION['new_ride']);
    header('Location: dashboard_users.php');
    exit;

} else {
        $_SESSION['status'] = "<div class='alert alert-danger'>Erreur lors de l'enregistrement du covoiturage.</div>";
        header('Location: new_ride.php');
        exit;
    }
}


?>

<?php if (isset($_SESSION['status'])): ?>
    <?= $_SESSION['status']; unset($_SESSION['status']); ?>
<?php endif; ?>


<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h1 class="h3">Ajouter une nouvelle course</h1>
            </div>

            <div class="card-body">
                <form action="new_ride.php" method="POST">

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="departure_place">Adresse de départ</label>
                        <input class="form-control" type="text" name="departure_place" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="arrival_place">Adresse d'arrivé</label>
                        <input class="form-control" type="text" name="arrival_place" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="departure_date">Date de départ</label>
                        <input class="form-control" type="date" name="departure_date" required>
                    </div>  
                    <div class="col-md-6 mb-3">
                        <label for="arrival_date">Date d'arrivé</label>
                        <input class="form-control" type="date" name="arrival_date" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="departure_time">Horaire de départ</label>
                        <input class="form-control" type="time" name="departure_time" required>
                    </div>                     
                    <div class="col-md-6 mb-3">
                        <label for="arrival_time">Horaire de d'arrivé</label>
                        <input class="form-control" type="time" name="arrival_time" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="nb_place">Nombre de places disponibles</label>
                        <input class="form-control" type="number" name="nb_place" min="1" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="ride_price">Prix du trajet</label>
                        <input class="form-control" type="number" name="ride_price" required>
                    </div>
                    
                </div>
                    <button class="btn btn-primary" type="submit" name="next_step">Suivant</button>
                    
                    
                    <?php 

                    # Nous allons vérifier si l'utilisateur a une voiture, dans le cas où il n'en a pas:
                    if (empty($vehicles)): 
                    
                    ?>
                        <div class="alert alert-warning">
                            Vous n'avez aucun véhicule enregistré. <br>
                            <a href="settings.php" class="btn btn-warning" style="margin-top: 10px;">Ajouter un véhicule</a>
                        </div>
                    <?php 

                    # Sinon, on affiche bien la selection du véhicule
                    else: 

                    ?>
                        <h3>Sélectionnez votre véhicule</h3>
                        <div class="vehicle-selection">
                            <?php foreach ($vehicles as $vehicle): ?>
                                <label class="vehicle-card">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" style="width: 20px;height: 20px;" type="radio" name="car_id" value="<?= htmlspecialchars($vehicle['car_id']) ?>" required>
                                        <span class="form-check-label"><?= htmlspecialchars($vehicle['brand_name']) ?> <?= htmlspecialchars($vehicle['car_model']) ?></label>
                                    </div>
                                </label>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>



                </form>
            </div>
        </div>
    </div>
</div>


<?php

// On inclut aussi footer.php
include('includes/footer.php');
ob_end_flush();

?>