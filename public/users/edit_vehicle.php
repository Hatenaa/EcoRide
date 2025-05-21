<?php

$pageTitle = "Éditer le véhicule";
include('includes/header.php');
require('../../config/connect.php');

# --- TRAITEMENT DU FORMULAIRE ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_vehicle'])) {

    $carId = intval($_POST['car_id']);
    $brandId = intval($_POST['brand']);
    $model = htmlspecialchars($_POST['model']);
    $color = htmlspecialchars($_POST['color']);
    $plate = htmlspecialchars($_POST['plate']);
    $energy = htmlspecialchars($_POST['energy']);
    $firstRegistrationDate = htmlspecialchars($_POST['first_registration_date']);

    # Vérifier que les champs ne sont pas vides
    if (!$brandId || !$model || !$color || !$plate || !$energy || !$firstRegistrationDate) {
        echo "<div class='alert alert-warning'>Erreur : Certains champs sont vides !</div>";
        exit;
    }

    # Mettre à jour la voiture
    $stmt = $db->prepare("UPDATE cars SET car_brand_id = ?, car_model = ?, car_color = ?, car_registration = ?, car_energy = ?, first_registration_date = ? WHERE car_id = ?");
    $stmt->execute([$brandId, $model, $color, $plate, $energy, $firstRegistrationDate, $carId]);

    # Vérifier si la mise à jour a bien eu lieu
    if ($stmt->rowCount() > 0) {
        echo "<div class='alert alert-success'>Mise à jour réussie.</div>";
    } else {
        echo "<div class='alert alert-danger'>Aucune modification effectuée. Peut-être que les données sont identiques ?</div>";
    }
}

# Vérifier si `car_id` est bien dans l'URL
if (!isset($_GET['car_id'])) {
    echo '<div class="alert alert-info">Redirection en cours... <a href="settings.php">Cliquez ici si vous n\'êtes pas redirigé.</a>
        </div>
        <script>
            setTimeout(function() {
                window.location.href = "settings.php";
            }, 800); // Redirection après 5 secondes
        </script>';
    exit;
}

$carId = intval($_GET['car_id']);

# Récupérer les infos du véhicule
$stmt = $db->prepare("SELECT car_id, car_brand_id, car_model, car_color, car_registration, car_energy, first_registration_date FROM cars WHERE car_id = ?");
$stmt->execute([$carId]);
$vehicle = $stmt->fetch(PDO::FETCH_ASSOC);



if (!$vehicle) {
    echo '<div class="alert alert-danger">Erreur : Le véhicule n\'existe pas.</div>';
    exit;
}

# Récupérer les marques pour le menu déroulant
$stmt = $db->prepare("SELECT brand_id, label FROM brands ORDER BY label ASC");
$stmt->execute();
$brands = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h1 class="h3">Modifier le véhicule <a href="settings.php" class="btn btn-danger float-end">Annuler</a></h1>
            </div>
            <div class="card-body">
                <form method="POST" action="edit_vehicle.php">
                    <input type="hidden" name="car_id" value="<?= $vehicle['car_id'] ?>">
                    
                    <div class="col-md-12">
                        <div class="mb-3">
                            <label for="brand" class="form-label">Marque</label>
                            <select class="form-control" name="brand" required>
                                <?php foreach ($brands as $brand) : ?>
                                    <option value="<?= $brand['brand_id']; ?>" <?= ($vehicle['car_brand_id'] === $brand['brand_id']) ? 'selected="selected"' : ''; ?>>
                                        <?= htmlspecialchars($brand['label']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>        
                    </div>   

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>Modèle</label>
                            <input class="form-control" type="text" name="model" value="<?= htmlspecialchars($vehicle['car_model']); ?>" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label>Couleur</label>
                            <input class="form-control" type="text" name="color" value="<?= htmlspecialchars($vehicle['car_color']); ?>" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label>Plaque</label>
                            <input class="form-control" type="text" name="plate" value="<?= htmlspecialchars($vehicle['car_registration']); ?>" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label>Énergie</label>
                            <input class="form-control" type="text" name="energy" value="<?= htmlspecialchars($vehicle['car_energy']); ?>" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label>Date du première enregistrement</label>
                            <input class="form-control" type="date" name="first_registration_date" value="<?= htmlspecialchars($vehicle['first_registration_date']); ?>" required>
                        </div>
                    </div>

                    <div class="col-md text-end">
                        <button type="submit" name="update_vehicle" class="btn btn-primary">Modifier</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php
include('includes/footer.php');
?>
