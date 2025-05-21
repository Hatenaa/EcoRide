<?php

ob_start();
require('../../config/connect.php');

$pageTitle = 'Paramètres du compte';
include('includes/header.php');

# Vérifier que l'utilisateur est bien connecté
if (!isset($_SESSION['auth']) || $_SESSION['auth'] !== true) {
    header('Location: ../../login.php');
    exit;
}

# Récupérons l'ID de l'utilisateur connecté
$userId = $_SESSION['loggedInUser']['user_id'];

# Et toutes les infos le concernant
$stmt = $db->prepare("SELECT * FROM users WHERE user_id = :user_id LIMIT 1");
$stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
$stmt->execute();

$user = $stmt->fetch(PDO::FETCH_ASSOC);


if (!$user) {
    echo "Erreur : utilisateur non trouvé.";
    exit;
}

# On vas maintenant récupérer tous les véhicules
$stmt = $db->prepare("
    SELECT 
        c.car_id, 
        c.car_model, 
        c.car_color, 
        c.car_registration, 
        c.car_energy, 
        c.first_registration_date,
        b.label AS brand_name
    FROM cars c
    LEFT JOIN brands b ON c.car_brand_id = b.brand_id
    WHERE c.car_user_id = ?
");

$stmt->execute([$userId]);
$vehicles = $stmt->fetchAll(PDO::FETCH_ASSOC);


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_vehicule'])) {

    $vehicle = $_POST['new_vehicle'];

    $brand = isset($vehicle['brand']) ? htmlspecialchars($vehicle['brand']) : null;
    $model = isset($vehicle['model']) ? htmlspecialchars($vehicle['model']) : null;
    $color = isset($vehicle['color']) ? htmlspecialchars($vehicle['color']) : null;
    $plate = isset($vehicle['plate']) ? htmlspecialchars($vehicle['plate']) : null;
    $energy = isset($vehicle['energy']) ? htmlspecialchars($vehicle['energy']) : null;
    $registrationDate = isset($vehicle['first_registration_date']) ? htmlspecialchars($vehicle['first_registration_date']) : null;


    # Vérifions maintenant que tous les champs sont remplis

    if (!$brand || !$model || !$color || !$plate || !$energy || !$registrationDate) {

        echo '<div class="alert alert-warning" role="alert">
                    <strong>Attention!</strong> Tous les champs sont obligatoires.
              </div>';

    } else {

        $brandId = null;

        # On vérifie si la marque existe ou pas
        $stmt = $db->prepare("SELECT brand_id FROM brands WHERE LOWER(label) = ?");
        $stmt->execute([$brand]);
        $brandId = $stmt->fetchColumn();

        # Si la marque n'est pas là, on l'ajoute
        if(!$brandId) {
            $normalizedBrand = ucfirst(strtolower($brand)); # Exemple -> "bmw" deviendra "Bmw"
            $stmt = $db->prepare("INSERT INTO brands (label) VALUES (?)");
            $stmt->execute([$normalizedBrand]);
            $brandId = $db->lastInsertId(); # On récupère l'ID de la nouvelle marque
        }

        # Vérifions également si le véhicule existe déjà
        $checkStmt = $db->prepare("SELECT car_id FROM cars WHERE UPPER(car_registration) = UPPER(?)");
        $checkStmt->execute([$plate]);
        $existingCar = $checkStmt->fetchColumn(); 

        
        # Si le véhicule a déjà été enregistré, alors...
        if ($existingCar) {

            $_SESSION['status'] = '<div class="alert alert-warning" role="alert">
                <strong>Ce véhicule est déjà enregistré par un autre usager.</strong><br>
                Vous n’arrivez pas à enregistrer votre voiture ? <a href="contact.php">Contactez-nous</a>.
              </div>';
              
            header("Location: settings.php"); # Vas nous permettre de rafraîchir la page
            exit;

        } else {

            # Sinon, on insère le véhicule dans la table `cars`
                $stmt = $db->prepare("
                INSERT INTO cars (car_user_id, car_brand_id, car_model, car_color, car_registration, car_energy, first_registration_date)
                VALUES (?, ?, ?, ?, ?, ?, ?)
            ");
            $stmt->execute([$userId, $brandId, $model, $color, $plate, $energy, $registrationDate]);

            $_SESSION['status'] = "<div class= \"alert alert-info\" role=\"alert\">
                    Véhicule ajouté avec succès.
                </div>";

            header("Location: settings.php"); 
            exit;

        }
        
    }
 
}

# Pour récupérer le rôle actuel de l'utilisateur
$stmt = $db->prepare("
    SELECT ur.role_id, r.label 
    FROM user_as_role ur
    JOIN roles r ON ur.role_id = r.role_id
    WHERE ur.user_id = ?
");

$stmt->execute([$userId]);

$userRoleData = $stmt->fetch(PDO::FETCH_ASSOC);

$userRoleId = $userRoleData['role_id'] ?? null;
$userRoleLabel = $userRoleData['label'] ?? null;

# ✅ Si aucun rôle n'est défini → Attribuer "Passager" par défaut
if (!$userRoleId) {
    $stmt = $db->prepare("SELECT role_id FROM roles WHERE label = 'Passager'");
    $stmt->execute();
    $defaultRoleId = $stmt->fetchColumn();

    if ($defaultRoleId) {
        $stmt = $db->prepare("INSERT INTO user_as_role (user_id, role_id) VALUES (?, ?)");
        $stmt->execute([$userId, $defaultRoleId]);

        $userRoleId = $defaultRoleId;
        $userRoleLabel = "Passager";
    }
}

$userRole = $stmt->fetchColumn(); // Ex: "passenger", "driver", "both"

# Vérifier si l'utilisateur a déjà un rôle dans user_as_role
$stmt = $db->prepare("
    SELECT r.label 
    FROM user_as_role ur
    JOIN roles r ON ur.role_id = r.role_id
    WHERE ur.user_id = ?
");

$stmt->execute([$userId]);
$userRole = $stmt->fetchColumn();

# Récupérer la liste des rôles disponibles
$stmt = $db->prepare("SELECT role_id, label FROM roles WHERE label IN ('Passager', 'Chauffeur', 'Passager & Chauffeur') ORDER BY role_id ASC");
$stmt->execute();
$roles = $stmt->fetchAll(PDO::FETCH_ASSOC);


# Initialiser un tableau vide pour éviter l'erreur "Undefined variable"
$userPreferences = [];

# Récupérer les préférences de l'utilisateur
$stmt = $db->prepare("SELECT configuration_name, configuration_value FROM configurations WHERE configuration_user_id = ?");
$stmt->execute([$userId]);

while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $userPreferences[$row['configuration_name']] = $row['configuration_value'];
}

# Définir des valeurs par défaut si elles n'existent pas
$userPreferences['accepts_smokers'] = $userPreferences['accepts_smokers'] ?? 0;
$userPreferences['accepts_pets'] = $userPreferences['accepts_pets'] ?? 0;


# TRAITEMENT DU FORMULAIRE (Mise à jour du rôle)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['saveSettings'])) {
    
    $selectedRoleId = intval($_POST['role']); // Rôle sélectionné dans le formulaire

    if ($userRoleId != $selectedRoleId) {
        
        # Mise à jour du rôle uniquement si nécessaire
        $stmt = $db->prepare("UPDATE user_as_role SET role_id = ? WHERE user_id = ?");
        $stmt->execute([$selectedRoleId, $userId]);

        # Vérifier si le rôle sélectionné est chauffeur
        $stmt = $db->prepare("SELECT label FROM roles WHERE role_id = ?");
        $stmt->execute([$selectedRoleId]);
        $selectedRoleLabel = $stmt->fetchColumn();

        if ($selectedRoleLabel === 'Chauffeur' || $selectedRoleLabel === 'Passager & Chauffeur') {

            // Ajouter l'utilisateur comme chauffeur
            $stmt = $db->prepare("UPDATE users SET driver_id = ? WHERE user_id = ?");
            $stmt->execute([$userId, $userId]);
        } else {

            // Si l'utilisateur redevient passager, supprimer le driver_id
            $stmt = $db->prepare("UPDATE users SET driver_id = NULL WHERE user_id = ?");
            $stmt->execute([$userId]);
        }

        $_SESSION['status'] = "<div class='alert alert-success'>Votre rôle a été mis à jour avec succès.</div>";
    } else {
        # L'utilisateur a sélectionné son rôle actuel
        $_SESSION['status'] = "<div class='alert alert-warning'>Vous avez sélectionné le même rôle.</div>";
    }

    # Gestion des préférences du Conducteur
    $acceptsSmokers = isset($_POST['accepts_smokers']) ? intval($_POST['accepts_smokers']) : 0;
    $acceptsPets = isset($_POST['accepts_pets']) ? intval($_POST['accepts_pets']) : 0;
    $hasAc = isset($_POST['has_ac']) ? intval($_POST['has_ac']) : 0;
    $hasUsb = isset($_POST['has_usb']) ? intval($_POST['has_usb']) : 0;
    $hasRecliningSeats = isset($_POST['has_reclining_seats']) ? intval($_POST['has_reclining_seats']) : 0;
    $hasWifi = isset($_POST['has_wifi']) ? intval($_POST['has_wifi']) : 0;
    $hasLargeTrunk = isset($_POST['has_large_trunk']) ? intval($_POST['has_large_trunk']) : 0;

    # Liste des préférences à enregistrer
    $preferences = [
        'accepts_smokers' => $acceptsSmokers,
        'accepts_pets' => $acceptsPets,
        'has_ac' => $hasAc,
        'has_usb' => $hasUsb,
        'has_reclining_seats' => $hasRecliningSeats,
        'has_wifi' => $hasWifi,
        'has_large_trunk' => $hasLargeTrunk
    ];    

    foreach ($preferences as $name => $value) {

        # Vérifier si la configuration existe déjà pour cet utilisateur
        $stmt = $db->prepare("SELECT COUNT(*) FROM configurations WHERE configuration_user_id = ? AND configuration_name = ?");
        $stmt->execute([$userId, $name]);
        $exists = $stmt->fetchColumn();
    
        if ($exists) {

            # Mise à jour si la préférence existe déjà
            $stmt = $db->prepare("UPDATE configurations SET configuration_value = ? WHERE configuration_user_id = ? AND configuration_name = ?");
            $stmt->execute([$value, $userId, $name]);

        } else {

            # Insertion si la préférence n'existe pas encore
            $stmt = $db->prepare("INSERT INTO configurations (configuration_user_id, configuration_name, configuration_value) VALUES (?, ?, ?)");
            $stmt->execute([$userId, $name, $value]);
            
        }
    }
    
    $_SESSION['status'] = "<div class='alert alert-success'>Vos préférences ont été mises à jour avec succès.</div>";

    # Rafraîchir la page pour afficher les modifications
    header("Location: settings.php");
    exit;
}

?>

<?php 

# AFFICHAGE DES MESSAGES DE SESSION
if (isset($_SESSION['status'])) {
    echo $_SESSION['status'];
    unset($_SESSION['status']);
} 


/**
 * Ici, nous allons gérer le formulaire d'édition du compte.
 * Quand un utilisateur va soumettre le formulaire, ses informations seront validées et mises à jour en base de données.
 * Cela inclut son nom, prénom, email, téléphone, adresse, date de naissance, pseudonyme, suspension éventuelle et rôle.
 * Si l'ID de l'utilisateur est manquant ou invalide, une erreur sera retournée.
 * Une fois la mise à jour réussie, l'utilisateur est redirigé avec un message de confirmation.
 */

if (isset($_POST['saveUsers'])) {

   // Récupération des données du formulaire
    $name = $_POST['name'];
    $firstname = $_POST['firstname'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // On hash le mot de passe ici
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $date_of_birth = $_POST['date_of_birth'];
    $nickname = $_POST['nickname'];
    $user_id = $_POST['user_id']; // Assurez-vous que ce champ est bien dans le formulaire

    // Préparation de la requête de mise à jour
    $stmt = $db->prepare("UPDATE users SET 
        name = :name, 
        firstname = :firstname,
        nickname = :nickname,
        password = :password,
        email = :email,
        phone = :phone,
        address = :address,
        date_of_birth = :date_of_birth
        WHERE user_id = :user_id
    ");

    // Exécution avec les paramètres
    $stmt->execute([
        ':name' => $name,
        ':firstname' => $firstname,
        ':nickname' => $nickname,
        ':password' => $password,
        ':email' => $email,
        ':phone' => $phone,
        ':address' => $address,
        ':date_of_birth' => $date_of_birth,
        ':user_id' => $user_id
    ]);

    // Gestion de la photo si un fichier a été uploadé
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] === 0) {
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        $fileType = mime_content_type($_FILES['photo']['tmp_name']);

        if (in_array($fileType, $allowedTypes)) {
            $extension = pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION);
            $newFileName = 'user_' . $user_id . '.' . $extension;
            $uploadPath = __DIR__ . '/../../images/profile_photos/' . $newFileName;

            if (move_uploaded_file($_FILES['photo']['tmp_name'], $uploadPath)) {
                $stmtPhoto = $db->prepare("UPDATE users SET photo = :photo WHERE user_id = :user_id");
                $stmtPhoto->execute([
                    ':photo' => $newFileName,
                    ':user_id' => $user_id
                ]);
            }
        }
    }

    $_SESSION['message'] = "Votre profile a été mis à jour avec succès.";
    $_SESSION['message_type'] = "success";
    header("Location: settings.php");
    exit;

}

// Ici, on vas afficher le message de session une fois qu'on aura valider notre formulaire
if (isset($_SESSION['message'])) {
    $type = $_SESSION['message_type'] ?? 'info'; // 'success', 'danger', 'warning', etc.
    echo "<div class='alert alert-{$type} fade show mt-3' role='alert'>
                <h4 style=\"font-size: 17px; color: --bs-alert-color: var(--bs-{$type}-text-emphasis);\">
                    {$_SESSION['message']}
                </h4>
            </div>";
    unset($_SESSION['message']);
    unset($_SESSION['message_type']);
} 

?>



<form action="settings.php" method="POST" enctype="multipart/form-data" style="padding-bottom: 20px;">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <input type="hidden" name="user_id" value="<?= $user['user_id'] ?>">
                    <input type="hidden" name="saveUsers" value="1">

                    <div class="row">
                        <!-- Nom -->
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="name">Nom</label>
                                <input type="text" name="name" id="name" class="form-control" required
                                    value="<?= isset($user['name']) ? htmlspecialchars($user['name']) : '' ?>">
                            </div>
                        </div>

                        <!-- Prénom -->
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="firstname">Prénom</label>
                                <input type="text" name="firstname" id="firstname" class="form-control" required
                                    value="<?= isset($user['firstname']) ? htmlspecialchars($user['firstname']) : '' ?>">
                            </div>
                        </div>

                        <!-- Email -->
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="email">Email</label>
                                <input type="email" name="email" id="email" class="form-control" required
                                    value="<?= isset($user['email']) ? htmlspecialchars($user['email']) : '' ?>">
                            </div>
                        </div>

                        <!-- Mot de passe -->
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="password">Mot de passe</label>
                                <input type="password" name="password" id="password" class="form-control">
                            </div>
                        </div>

                        <!-- Téléphone -->
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="phone">Téléphone</label>
                                <input type="text" name="phone" id="phone" class="form-control"
                                    value="<?= isset($user['phone']) ? htmlspecialchars($user['phone']) : '' ?>">
                            </div>
                        </div>

                        <!-- Adresse -->
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="address">Adresse</label>
                                <input type="text" name="address" id="address" class="form-control"
                                    value="<?= htmlspecialchars($user['address']) ?>">
                            </div>
                        </div>

                        <!-- Date de naissance -->
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="date_of_birth">Date de naissance</label>
                                <input type="date" name="date_of_birth" id="date_of_birth" class="form-control"
                                    value="<?= isset($user['date_of_birth']) ? htmlspecialchars($user['date_of_birth']) : '' ?>">
                            </div>
                        </div>

                        <!-- Pseudo -->
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="nickname">Pseudo</label>
                                <input type="text" name="nickname" id="nickname" class="form-control"
                                    value="<?= isset($user['nickname']) ? htmlspecialchars($user['nickname']) : '' ?>">
                            </div>
                        </div>

                        <!-- Photo de profil -->
                        <div class="mb-4">
                            <label>Photo de profil</label>
                            <input type="file" name="photo" accept="image/*" class="form-control">

                            <?php if (isset($user) && !empty($user['photo'])): ?>
                                <div class="mb-2" style="padding-top: 20px;">
                                    <img src="/images/profile_photos/<?= htmlspecialchars($user['photo']) ?>" alt="Photo actuelle" class="img-thumbnail" style="max-width: 150px;">
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <button class="btn btn-primary" type="submit">Enregistrer</button>
                </div>
            </div>
        </div>
    </div>
</form>




<!-- FORMULAIRE POUR MODIFIER LE RÔLE -->
<form method="POST" action="settings.php" style="padding-bottom: 20px;">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="mb-3">
                        <label for="role" class="form-label">Choisissez votre rôle :</label>
                        <select name="role" id="role" class="form-select">
                            <?php foreach ($roles as $role): ?>
                                <option value="<?= htmlspecialchars($role['role_id']); ?>" 
                                    <?= ($userRoleId == $role['role_id']) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($role['label']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                                
                    
                    <?php if ($userRole === 'Chauffeur' || $userRole === 'Passager & Chauffeur') : ?>             
                        <div class="row container">
                            
                            <!-- Fumeurs -->
                            <div class="col-md-6 col-lg-3 mb-3">
                                <label class="form-label">Acceptez-vous les fumeurs ?</label><br>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="accepts_smokers" value="1"
                                        <?= (isset($userPreferences['accepts_smokers']) && $userPreferences['accepts_smokers'] == 1) ? 'checked' : ''; ?>>
                                    <label class="form-check-label">Oui</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="accepts_smokers" value="0"
                                        <?= (!isset($userPreferences['accepts_smokers']) || $userPreferences['accepts_smokers'] == 0) ? 'checked' : ''; ?>>
                                    <label class="form-check-label">Non</label>
                                </div>
                            </div>

                            <!-- Animaux -->
                            <div class="col-md-6 col-lg-3 mb-3">
                                <label class="form-label">Acceptez-vous les animaux ?</label><br>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="accepts_pets" value="1"
                                        <?= (isset($userPreferences['accepts_pets']) && $userPreferences['accepts_pets'] == 1) ? 'checked' : ''; ?>>
                                    <label class="form-check-label">Oui</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="accepts_pets" value="0"
                                        <?= (!isset($userPreferences['accepts_pets']) || $userPreferences['accepts_pets'] == 0) ? 'checked' : ''; ?>>
                                    <label class="form-check-label">Non</label>
                                </div>
                            </div>

                            <!-- Climatisé -->
                            <div class="col-md-6 col-lg-3 mb-3">
                                <label class="form-label">Votre voiture est-elle climatisée ?</label><br>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="has_ac" value="1"
                                        <?= (isset($userPreferences['has_ac']) && $userPreferences['has_ac'] == 1) ? 'checked' : ''; ?>>
                                    <label class="form-check-label">Oui</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="has_ac" value="0"
                                        <?= (!isset($userPreferences['has_ac']) || $userPreferences['has_ac'] == 0) ? 'checked' : ''; ?>>
                                    <label class="form-check-label">Non</label>
                                </div>
                            </div>

                            <!-- USB -->
                            <div class="col-md-6 col-lg-3 mb-3">
                                <label class="form-label">Ports USB disponibles ?</label><br>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="has_usb" value="1"
                                        <?= (isset($userPreferences['has_usb']) && $userPreferences['has_usb'] == 1) ? 'checked' : ''; ?>>
                                    <label class="form-check-label">Oui</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="has_usb" value="0"
                                        <?= (!isset($userPreferences['has_usb']) || $userPreferences['has_usb'] == 0) ? 'checked' : ''; ?>>
                                    <label class="form-check-label">Non</label>
                                </div>
                            </div>

                            <!-- Sièges inclinables -->
                            <div class="col-md-6 col-lg-3 mb-3">
                                <label class="form-label">Sièges inclinables ?</label><br>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="has_reclining_seats" value="1"
                                        <?= (isset($userPreferences['has_reclining_seats']) && $userPreferences['has_reclining_seats'] == 1) ? 'checked' : ''; ?>>
                                    <label class="form-check-label">Oui</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="has_reclining_seats" value="0"
                                        <?= (!isset($userPreferences['has_reclining_seats']) || $userPreferences['has_reclining_seats'] == 0) ? 'checked' : ''; ?>>
                                    <label class="form-check-label">Non</label>
                                </div>
                            </div>

                            <!-- WiFi -->
                            <div class="col-md-6 col-lg-3 mb-3">
                                <label class="form-label">WiFi dans la voiture ?</label><br>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="has_wifi" value="1"
                                        <?= (isset($userPreferences['has_wifi']) && $userPreferences['has_wifi'] == 1) ? 'checked' : ''; ?>>
                                    <label class="form-check-label">Oui</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="has_wifi" value="0"
                                        <?= (!isset($userPreferences['has_wifi']) || $userPreferences['has_wifi'] == 0) ? 'checked' : ''; ?>>
                                    <label class="form-check-label">Non</label>
                                </div>
                            </div>

                            <!-- Grand coffre -->
                            <div class="col-md-6 col-lg-3 mb-3">
                                <label class="form-label">Grand coffre ?</label><br>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="has_large_trunk" value="1"
                                        <?= (isset($userPreferences['has_large_trunk']) && $userPreferences['has_large_trunk'] == 1) ? 'checked' : ''; ?>>
                                    <label class="form-check-label">Oui</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="has_large_trunk" value="0"
                                        <?= (!isset($userPreferences['has_large_trunk']) || $userPreferences['has_large_trunk'] == 0) ? 'checked' : ''; ?>>
                                    <label class="form-check-label">Non</label>
                                </div>
                            </div>

                        </div>
                        <?php endif; ?>

                    
                    <button class="btn btn-primary" type="submit" name="saveSettings">Enregistrer</button>
                </div>
            </div>     
        </div>    
    </div>
</form>

<!-- Afficher le formulaire du véhicule UNIQUEMENT si le rôle est chauffeur -->
<?php if ($userRole === 'Chauffeur' || $userRole === 'Passager & Chauffeur') : ?>
            <form method="POST" action="settings.php" style="padding-bottom: 20px;">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                        <div class="card-header">

                        <!-- <h4 style="padding-bottom: 10px;">Paramètres Utilisateur</h4> -->
                        <h1 class="h3">Quel véhicule voulez-vous ajouter <span style="color: #0D1679;"><?= htmlspecialchars($_SESSION['loggedInUser']['firstname']); ?></span>?</h1>
                        
                        </div>
                        
                        <div class="card-body">
                            <?php
                            if (isset($_SESSION['status'])) {
                                    echo $_SESSION['status']; // Affiche le message
                                    unset($_SESSION['status']); // Supprime après affichage
                                }
                            ?>
                                <div class="row">
                            
                                    <div class="col-md-6 mb-3">
                                            <input class="form-control" type="text" name="new_vehicle[brand]" placeholder="Exemple -> BMW" required>
                                            <label for="brand" class="form-label">Marque</label>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                            <input class="form-control" type="text" name="new_vehicle[model]" placeholder="Exemple -> Série 3" required>
                                            <label for="brand" class="form-label">Modèle</label>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                            <input class="form-control" type="text" name="new_vehicle[color]" placeholder="Exemple -> Noir" required>
                                            <label for="brand" class="form-label">Couleur</label>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                            <input class="form-control" type="text" name="new_vehicle[plate]"  placeholder="Exemple -> AB-123-CD" required>
                                            <label for="brand" class="form-label">Plaque d'Immatriculation</label>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                            <input class="form-control" type="text" name="new_vehicle[energy]"  placeholder="Exemple -> Essence" required>
                                            <label for="brand" class="form-label">Énergie du véhicule</label>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                            <input class="form-control" type="date" name="new_vehicle[first_registration_date]" required>
                                            <label for="brand" class="form-label"></label>
                                    </div>
                                    <div class="col-md-12 mb-3">
                                        <button class="btn btn-primary" type="sumbit" name="add_vehicule">Ajouter ce véhicule</button>
                                    </div>
                                </div>
                            </div>
                            
                        </div> 
                    </div>
                </div>
            </form>
<?php endif; ?>

<?php if (!empty($vehicles)) : ?>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3><?= ($userRole === 'Passager') ? 'Ancien(s)': '' ?>Véhicule(s) enregistré(s)</h3>
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Marque</th>
                                    <th>Modèle</th>
                                    <th>Couleur</th>
                                    <th>Plaque</th>
                                    <th>Énergie</th>
                                    <th>Date d'immatriculation</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($vehicles as $vehicle): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($vehicle['brand_name']); ?></td>
                                        <td><?= htmlspecialchars($vehicle['car_model']); ?></td>
                                        <td><?= htmlspecialchars($vehicle['car_color']); ?></td>
                                        <td><?= htmlspecialchars($vehicle['car_registration']); ?></td>
                                        <td><?= htmlspecialchars($vehicle['car_energy']); ?></td>
                                        <td><?= htmlspecialchars($vehicle['first_registration_date']); ?></td>
                                        <td>
                                            <a href="edit_vehicle.php?car_id=<?= $vehicle['car_id'];?>" class="btn btn-warning btn-sm">Modifier</a>
                                            <a href="delete_vehicle.php?car_id=<?= $vehicle['car_id']?>" class="btn btn-danger btn-sm" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce véhicule ?');">Supprimer</a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                </div>
            </div>
        </div>
    </div>

<?php endif; ?>

<?php 

include('includes/footer.php'); 
ob_end_flush();

?>