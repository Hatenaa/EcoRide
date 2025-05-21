<?php
ob_start();
session_start();

// Inclure la connexion à la base de données
require '../../config/connect.php';

// Inclure le fichier header.php
include('../../includes/header.php');

// Traitement si l'utilisateur clique sur "Rejoindre"
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['join_carpool'], $_POST['carpooling_id'])) {

    // Vérifier si l'utilisateur est connecté
    if (!isset($_SESSION['auth']) || $_SESSION['auth'] !== true) {
        
        // Si inactive, on ajoute une session provisoire pour afficher le message d'erreur.
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        } 

        $_SESSION['message'] = "Pour participer à un covoiturage, vous devez vous connecter.";
        $_SESSION['message_type'] = 'danger';
        
        session_write_close(); 
        header('Location: login.php');
        exit; 
    }    

    $carpooling_id = (int)$_POST['carpooling_id'];

    // Vérifier que le trajet existe et qu'il reste des places
    $check_stmt = $db->prepare("SELECT nb_place FROM ecoride.carpools WHERE carpooling_id = :carpooling_id AND status = 'Disponible'");
    $check_stmt->execute([':carpooling_id' => $carpooling_id]);
    $carpool = $check_stmt->fetch(PDO::FETCH_ASSOC);


    if ($carpool && (int)$carpool['nb_place'] > 0) {

        // Vérifier les crédits de l'utilisateur
        $userId = $_SESSION['loggedInUser']['user_id'];
        $userCredits = getUserCredits($userId);

        if ($userCredits >= 2) {

            // Déduire 2 crédits et mettre à jour les crédits dans `users.json`
            $newCredits = $userCredits - 2;
            updateUserCreditsInJson($userId, $newCredits);

            // Mettre à jour les places disponibles dans la base de données
            $update_stmt = $db->prepare("UPDATE ecoride.carpools SET nb_place = nb_place - 1 WHERE carpooling_id = :carpooling_id");
            $update_stmt->execute([':carpooling_id' => $carpooling_id]);

            // Insérer l'utilisateur dans la table carpools_as_users
            $insert_stmt = $db->prepare("INSERT INTO ecoride.carpools_as_users (carpooling_id, user_id) VALUES (:carpooling_id, :user_id)");
            $insert_stmt->execute([
                ':carpooling_id' => $carpooling_id,
                ':user_id' => $userId
            ]);

            // Mettre à jour la session avec les nouveaux crédits
            $_SESSION['loggedInUser']['credits'] = $newCredits;

            // Si tout s'est bien passé, alors on redirige vers l'accueil et on informe de client
            /*$_SESSION['message'] = "Votre place a été réservée avec succès. Il vous reste ' . $newCredits . ' crédits.";
            $_SESSION['message_type'] = "success";
            header('Location: index.php');
            exit;*/

            redirect('home.php', 'Votre place a été réservée avec succès. Il vous reste ' . $newCredits . ' crédits.');
            
        } else {

            if ($userId == 1) {
                redirect('home.php', "Vous ne pouvez pas rejoindre un covoiturage avec un compte administrateur.");
            }

            redirect('home.php', "Vous n'avez pas assez de crédits pour participer à ce voyage.");
            
        }
        
    } else {

        // Stocker le message dans la session
        $_SESSION['status'] = "<div class='alert alert-danger text-center'>Vous n'avez pas assez de crédits pour participer à ce voyage.</div>";

        // Redirection vers index.php
        header('Location: home.php');
        exit;

    }
    exit;
}

// Traitement de la recherche si la méthode est GET
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['departure'], $_GET['arrival'], $_GET['date'], $_GET['passengers'])) {

    // Évaluer les filtres dynamiques
    $eco_only = isset($_GET['eco_only']) && $_GET['eco_only'] === '1';
    $min_price = isset($_GET['min_price']) && $_GET['min_price'] !== '' ? (float)$_GET['min_price'] : null;
    $max_price = isset($_GET['max_price']) && $_GET['max_price'] !== '' ? (float)$_GET['max_price'] : null;
    $max_duration = isset($_GET['max_duration']) && (int)$_GET['max_duration'] > 0 ? (int)$_GET['max_duration'] : null;
    $min_rating = isset($_GET['min_rating']) && $_GET['min_rating'] !== '' ? (float)$_GET['min_rating'] : null;

    // Récupérer les autres paramètres
    $departure = $_GET['departure'];
    $arrival = $_GET['arrival'];
    $date = date('Y-m-d', strtotime($_GET['date']));
    $date_end = date('Y-m-d', strtotime('+7 days', strtotime($date)));
    $passengers = (int)$_GET['passengers'];

    // Construction de la requête avec les conditions dynamiques
    $sql = "
        SELECT
            c.*,
            u.nickname,
            u.photo,
            v.car_energy,
            r.rating,
            TIMESTAMPDIFF(MINUTE,
                STR_TO_DATE(CONCAT(c.departure_date, ' ', c.departure_time), '%Y-%m-%d %H:%i:%s'),
                STR_TO_DATE(CONCAT(c.arrival_date, ' ', c.arrival_time), '%Y-%m-%d %H:%i:%s')
            ) AS duration
        FROM
            ecoride.carpools c
        LEFT JOIN 
            ecoride.users u ON c.driver_id = u.user_id
        LEFT JOIN 
            ecoride.cars v ON c.car_id = v.car_id
        LEFT JOIN 
            ecoride.reviews r ON c.carpooling_id = r.review_id
        WHERE
            c.departure_place = :departure
            AND c.arrival_place = :arrival
            AND STR_TO_DATE(c.departure_date, '%Y-%m-%d') BETWEEN :date AND :date_end
            AND c.status = 'Disponible'
    ";


    // Ajoutons des conditions dynamiques dans le cas où les filtres sont définis
    
    switch (true) {
        case $eco_only:
            $sql .= " AND v.car_energy = 'Électrique' OR v.car_energy = 'Hybride'";
            break;
        case $min_price !== null:
            $sql .= " AND c.person_price >= :min_price";
            break;
        case $max_price !== null:
            $sql .= " AND c.person_price <= :max_price";
            break;
        case $max_duration !== null:
            $sql .= " AND TIMESTAMPDIFF(MINUTE, 
                        STR_TO_DATE(CONCAT(c.departure_date, ' ', c.departure_time), '%Y-%m-%d %H:%i:%s'),
                        STR_TO_DATE(CONCAT(c.arrival_date, ' ', c.arrival_time), '%Y-%m-%d %H:%i:%s')
                    ) <= :max_duration";
            break;
        case $min_rating !== null:
            $sql .= " AND r.rating >= :min_rating";
            break;
    }

    $sql .= " ORDER BY STR_TO_DATE(c.departure_date, '%Y-%m-%d') ASC";

    // Préparer la requête
    $stmt = $db->prepare($sql);

    // Lier les paramètres principaux
    $stmt->bindValue(':departure', $departure, PDO::PARAM_STR);
    $stmt->bindValue(':arrival', $arrival, PDO::PARAM_STR);
    $stmt->bindValue(':date', $date, PDO::PARAM_STR);
    $stmt->bindValue(':date_end', $date_end, PDO::PARAM_STR);

    // Lier les filtres dynamiques uniquement s'ils sont définis
    switch (true) {
        case $min_price !== null:
            $stmt->bindValue(':min_price', $min_price, PDO::PARAM_STR);
            break;
        case $max_price !== null:
            $stmt->bindValue(':max_price', $max_price, PDO::PARAM_STR);
            break;
        case $max_duration !== null:
            $stmt->bindValue(':max_duration', $max_duration, PDO::PARAM_INT);
            break;
        case $min_rating !== null:
            $stmt->bindValue(':min_rating', $min_rating, PDO::PARAM_STR);
            break;
    }

    // Exécuter la requête
    $stmt->execute();

    // Récupérer les résultats
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

} else {
    echo "Veuillez renseigner tous les champs du formulaire.";
    exit;
}

?>


<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Résultats de recherche</title>
    
</head>
<style>
        :root {
            --eco-green: #22c55e;
            --eco-dark-green: #15803d;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8fafc;
            position: relative;
            overflow-x: hidden;
            min-height: 100vh;
        }
        
        body::before {
            content: "";
            position: fixed;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(34, 197, 94, 0.08) 0%, rgba(255, 255, 255, 0) 70%);
            z-index: -1;
            pointer-events: none;
        }
        
        .glass-card {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border-radius: 20px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            transition: all 0.3s ease;
        }
        
        .glass-card:hover {
            box-shadow: 0 12px 40px rgba(0, 0, 0, 0.15);
            transform: translateY(-2px);
        }
        
        .btn-eco {
            background: linear-gradient(135deg, var(--eco-green), var(--eco-dark-green));
            box-shadow: 0 4px 15px rgba(34, 197, 94, 0.3);
            color: white;
            border-radius: 30px;
            transition: all 0.3s ease;
        }
        
        .btn-eco:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(34, 197, 94, 0.4);
        }
        
        .btn-outline {
            border: 1px solid var(--eco-green);
            color: var(--eco-green);
            border-radius: 30px;
            transition: all 0.3s ease;
        }
        
        .btn-outline:hover {
            background-color: rgba(34, 197, 94, 0.1);
        }
        
        .input-icon {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--eco-green);
        }
        
        .input-with-icon {
            padding-left: 45px !important;
        }
        
        .notification-badge {
            position: absolute;
            top: -5px;
            right: -5px;
            width: 18px;
            height: 18px;
            border-radius: 50%;
            background-color: #ef4444;
            color: white;
            font-size: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
        }
        
        .avatar-placeholder {
            background-color: #e2e8f0;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #64748b;
            font-weight: 600;
        }
        
        .floating-shape {
            position: absolute;
            border-radius: 50%;
            background-color: rgba(34, 197, 94, 0.1);
            z-index: -1;
            pointer-events: none;
        }

        .glass-filter {
        background: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
        border-radius: 20px;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        display: flex;
        flex-wrap: wrap;
        gap: 1rem;
        }

        .filter-group {
        display: flex;
        flex-direction: column;
        min-width: 150px;
        }

        .filter-group label {
        font-weight: 600;
        font-size: 0.85rem;
        color: #1a1a1a;
        margin-bottom: 4px;
        }

        .filter-group input[type="number"],
        .filter-group input[type="text"] {
        padding: 8px 12px;
        border: none;
        border-radius: 12px;
        background: rgba(255, 255, 255, 0.4);
        color: #1a1a1a;
        font-size: 0.9rem;
        backdrop-filter: blur(5px);
        }

        .filter-group.checkbox {
        display: flex;
        align-items: center;
        }

        .filter-group.checkbox label {
        font-size: 0.85rem;
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 6px;
        margin-top: 22px;
        }

        .filter-card {
        background: rgba(255, 255, 255, 0.6);
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
        border-radius: 20px;
        padding: 1.5rem;
        margin: 2rem auto;
        max-width: 100%;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        }

        .filter-form {
        display: flex;
        flex-direction: row;
        flex-wrap: wrap;
        align-items: flex-end;
        justify-content: space-between;
        gap: 1rem;
        }

        .filter-fields {
        display: flex;
        flex-direction: row;
        flex-wrap: wrap;
        gap: 1rem;
        align-items: flex-end;
        width: 100%;
        }

        .filter-field {
        display: flex;
        flex-direction: column;
        min-width: 150px;
        flex: 1;
        }

        .filter-field label {
        font-weight: 600;
        font-size: 0.9rem;
        margin-bottom: 5px;
        }

        .filter-field input {
        padding: 8px 12px;
        border: none;
        border-radius: 12px;
        background: rgba(255, 255, 255, 0.5);
        backdrop-filter: blur(5px);
        font-size: 0.95rem;
        box-shadow: inset 0 1px 3px rgba(0,0,0,0.05);
        }

        .filter-checkbox {
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: 0.9rem;
        font-weight: 500;
        margin-bottom: 4px;
        }

        .filter-btn-wrapper {
        display: flex;
        align-items: center;
        }

        @media (max-width: 768px) {
            .glass-card {
                backdrop-filter: blur(5px);
                -webkit-backdrop-filter: blur(5px);
            }
        }
    </style>
<body class="min-h-screen bg-[#f4fef7] flex flex-col items-center justify-center p-4 md:p-8">
    <div class="container mt-5">


        <div class="text-center mb-10">
            <h1 class="text-4xl font-bold text-gray-800 mb-2">Résultats de recherche</h1>
            <p class="text-gray-600 text-lg">pour les courses vers 
                <span class="text-primary-500"><?= $arrival ?></span>
            </p>
        </div>

        <!-- Floating background shapes -->
        <div class="floating-shape" style="width: 300px; height: 300px; top: 10%; left: 5%;"></div>
        <div class="floating-shape" style="width: 200px; height: 200px; bottom: 15%; right: 10%;"></div>
        <div class="floating-shape" style="width: 150px; height: 150px; top: 30%; right: 20%;"></div>

        <!-- Formulaire de recherche -->
        <div class="filter-card">
            <form method="GET" action="results.php" class="filter-form">
                <!-- Champs cachés -->
                <input type="hidden" name="departure" value="<?= htmlspecialchars($departure) ?>">
                <input type="hidden" name="arrival" value="<?= htmlspecialchars($arrival) ?>">
                <input type="hidden" name="date" value="<?= htmlspecialchars($date) ?>">
                <input type="hidden" name="passengers" value="<?= htmlspecialchars($passengers) ?>">

                <!-- Groupe des filtres -->
                <div class="filter-fields">
                <div class="filter-field">
                    <label for="min_price">Prix minimum</label>
                    <input class="input-glass w-full rounded-xl px-4 py-2 text-white placeholder-white/60 
                           focus:outline-none focus:ring-2 focus:ring-green-400 transition-all" type="number" name="min_price" id="min_price" step="0.01" value="<?= htmlspecialchars($_GET['min_price'] ?? '') ?>">
                </div>

                <div class="filter-field">
                    <label for="max_price">Prix maximum</label>
                    <input class="input-glass w-full rounded-xl px-4 py-2 text-white placeholder-white/60 
                           focus:outline-none focus:ring-2 focus:ring-green-400 transition-all"
                           type="number" name="max_price" id="max_price" step="0.01" value="<?= htmlspecialchars($_GET['max_price'] ?? '') ?>">
                </div>

                <div class="filter-field">
                    <label for="max_duration">Durée maximale</label>
                    <input class="input-glass w-full rounded-xl px-4 py-2 text-white 
                           focus:outline-none focus:ring-2 focus:ring-green-400 transition-all"
                    type="number" name="max_duration" id="max_duration" value="<?= htmlspecialchars($_GET['max_duration'] ?? '') ?>">
                </div>

                <div class="filter-field">
                    <label for="min_rating">Note minimale</label>
                    <input class="input-glass w-full rounded-xl px-4 py-2 text-white placeholder-white/60 
                           focus:outline-none focus:ring-2 focus:ring-green-400 transition-all" type="number" name="min_rating" id="min_rating" step="0.1" min="0" max="5" value="<?= htmlspecialchars($_GET['min_rating'] ?? '') ?>">
                </div>

                <div class="filter-checkbox">
                    <input type="hidden" name="eco_only" value="0">
                    <label>
                    <input type="checkbox" name="eco_only" id="eco_only" value="1" <?= (isset($_GET['eco_only']) && $_GET['eco_only'] === '1') ? 'checked' : '' ?>>
                    Voyages écologiques
                    </label>
                </div>

                <div class="filter-btn-wrapper">
                    <button type="submit" class="btn-outline px-4 py-2 font-medium">Appliquer les filtres</button>
                </div>
                </div>
            </form>
        </div>

   

        <?php if (!empty($results)): ?>
            <h2 class="text-xl font-semibold mb-4 text-gray-800">Véhicules disponibles correspondant à vos critères</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

                <?php foreach ($results as $result): ?>
                        <?php 
                            $isLoggedIn = isset($_SESSION['auth']) && $_SESSION['auth'] === true;
                            $userId = $isLoggedIn ? $_SESSION['loggedInUser']['user_id'] : null;
                            $isParticipating = false;
                            if ($isLoggedIn) {
                                $checkParticipation = $db->prepare("SELECT COUNT(*) FROM ecoride.carpools_as_users WHERE carpooling_id = :carpooling_id AND user_id = :user_id");
                                $checkParticipation->execute([
                                    ':carpooling_id' => $result['carpooling_id'],
                                    ':user_id' => $userId
                                ]);
                                $isParticipating = $checkParticipation->fetchColumn() > 0;
                            }
                        ?>

                <?php if ((int)$result['nb_place'] >= $passengers): ?>
                    
                    <div class="glass-card p-6">
                        <div class="flex justify-between items-start mb-4">
                            <div>
                                <h3 class="font-bold text-lg text-gray-800">
                                    <?= htmlspecialchars($result['departure_place'] ?? 'Lieu de départ inconnu') ?> 
                                    <i class="fas fa-arrow-right mx-1 text-gray-400"></i> 
                                    <?= htmlspecialchars($result['arrival_place'] ?? 'Lieu d\'arrivée inconnu') ?>
                                </h3>
                                <div class="text-sm text-gray-600 mt-3 space-y-2">
                                    <div class="flex items-center">
                                        <i class="far fa-calendar-alt mr-2 text-green-600"></i>
                                        <span><?= htmlspecialchars($result['departure_date'] ?? 'Date inconnue') ?> • <?= htmlspecialchars($result['departure_time'] ?? 'Heure inconnue') ?></span>
                                    </div>
                                    <div class="flex items-center">
                                        <i class="fas fa-flag-checkered mr-2 text-green-600"></i>
                                        <span><?= htmlspecialchars($result['arrival_date'] ?? 'Date inconnue') ?> • <?= htmlspecialchars($result['arrival_time'] ?? 'Heure inconnue') ?></span>
                                    </div>
                                </div>
                            </div>
                            <div class="text-right">
                                <span class="text-2xl font-bold text-green-600"><?= htmlspecialchars($result['person_price'] ?? '0') ?>€</span>
                                <div class="text-sm text-gray-600 mt-1">
                                    <i class="fas fa-user-friends mr-1"></i> <?= htmlspecialchars($result['nb_place']) ?> <?= htmlspecialchars($result['nb_place']) > 1 ? 'places disponibles' : 'place disponible' ?>

                                </div>
                                <?php if ($result['car_energy'] === 'Électrique' || $result['car_energy'] === 'Hybride'): ?>
                                    <span class="inline-block mt-1 px-2 py-1 bg-green-100 text-green-800 text-xs rounded-full">
                                        <i class="fas fa-leaf mr-1"></i> Trajet Écologique
                                    </span>
                                <?php endif; ?>
                                <?php if ($isParticipating) : ?>
                                    <div>
                                        <span class="inline-flex items-center gap-2 mt-1 px-3 py-1 bg-yellow-100 text-yellow-800 text-xs rounded-full font-small" >
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z" />
                                            </svg>
                                            Vous participez déjà à ce covoiturage
                                        </span>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="flex items-center mt-4 pt-4 border-t border-gray-100">
                            <div class="w-12 h-12 rounded-full bg-slate-200 flex items-center justify-center mr-4 overflow-hidden">
                                    <?php 
                                        $profilePath = '/images/profile_photos/user_' . $result['driver_id'] . '.jpg';
                                        $absolutePath = $_SERVER['DOCUMENT_ROOT'] . $profilePath;

                                        if (file_exists($absolutePath)) {
                                            // Afficher la photo si le fichier existe
                                            echo '<img src="' . $profilePath . '" alt="Photo" class="w-full h-full object-cover">';
                                        } else {
                                            // Sinon icône utilisateur par défaut
                                            echo '<i class="fas fa-user text-slate-400 text-xl"></i>';
                                        }
                                    ?>
                            </div>

                            <?php // <img src="<?= htmlspecialchars($result['photo'] ?? 'default_photo.jpg') ?><?php // " class="w-10 h-10 rounded-full mr-3 object-cover" alt="Photo du chauffeur"> ?>
                            <div>
                                <div class="font-medium text-gray-800">
                                    <?= htmlspecialchars($result['nickname'] ?? 'Anonyme') ?>
                                </div>
                                <div class="flex items-center text-sm text-gray-600">
                                    <i class="fas fa-star text-yellow-400 mr-1"></i>
                                    <span><?= htmlspecialchars($result['rating'] ?? '0') ?> (<?= htmlspecialchars($result['nb_reviews'] ?? '0') ?> reviews)</span>
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-end mt-4 space-x-2">
                            <a href="details.php?carpooling_id=<?= htmlspecialchars($result['carpooling_id']) ?>&departure=<?= htmlspecialchars($departure) ?>&arrival=<?= htmlspecialchars($arrival) ?>&date=<?= htmlspecialchars($date) ?>&passengers=<?= htmlspecialchars($passengers) ?>" class="btn-outline px-4 py-2 font-medium">
                                Details
                            </a>

                            

                            <?php if (!$isParticipating): ?>
                                <form method="POST" action="results.php" class="m-0" style="padding-left: 8px;">
                                    <input type="hidden" name="carpooling_id" value="<?= htmlspecialchars($result['carpooling_id']) ?>">
                                    <button type="submit" name="join_carpool" class="btn-eco px-4 py-2 font-medium" onclick="return confirmParticipation()">
                                        <i class="fa-solid fa-right-to-bracket pr-[5px]"></i>
                                        Rejoindre
                                    </button>
                                </form>
                            <?php else: ?>
                                <!-- <div class="text-green-600 text-sm font-medium">You already joined</div> -->
                            <?php endif; ?>

                            <?php if ($isLoggedIn): ?>
                                <script>
                                    function confirmParticipation() {
                                        return confirm("Are you sure you want to spend 2 credits to join this ride?");
                                    }
                                </script>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>                            
        <?php else: ?>
            <p class="text-center">Aucun trajet trouvé pour ces critères.</p>
        <?php endif; ?>
    </div>
</body>
</html>

<?php
ob_end_flush();
// On inclut aussi footer.php
include('../../includes/footer.php');
?>
