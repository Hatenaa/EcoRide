<?php

// Connexion à la base de données
require('../../config/connect.php');
require '../../config/function.php';

// Vérifier si `car_id` est bien passé dans l'URL
if (!isset($_GET['car_id']) || empty($_GET['car_id'])) {
    die("<div class='alert alert-danger'>Aucun véhicule sélectionné.</div>");
}

$carId = intval($_GET['car_id']);

// Vérifier si le véhicule existe avant suppression
$stmt = $db->prepare("SELECT car_id FROM cars WHERE car_id = ?");
$stmt->execute([$carId]);
$vehicle = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$vehicle) {
    die("<div class='alert alert-danger'>Ce véhicule n'existe pas.</div>");
}

// Supprimer le véhicule
$stmt = $db->prepare("DELETE FROM cars WHERE car_id = ? LIMIT 1");
$stmt->execute([$carId]);

// Vérifier si la suppression a réussi
if ($stmt->rowCount() > 0) {

    echo "<div class='alert alert-success'>Véhicule supprimé avec succès.</div>";
    header("Location: settings.php");
    exit;

} else {
    echo "<div class='alert alert-warning'>Impossible de supprimer ce véhicule.</div>";
}
?>
