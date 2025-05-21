<?php 

$carpooling_id = (int)$_GET['carpooling_id'];
$departure = $_GET['departure'];
$arrival = $_GET['arrival'];

$pageTitle = 'Détails du trajet de ' . $departure . ' à ' . $arrival . ' | EcoRide';


include('../../includes/header.php');

$date = $_GET['date'] ?? ''; // Format attendu : '2025-05-06'
$date_fr = '';

if (!empty($date)) {
    $datetime = DateTime::createFromFormat('Y-m-d', $date);
    if ($datetime) {

        $jours = ['Dimanche','Lundi','Mardi','Mercredi','Jeudi','Vendredi','Samedi'];
        $mois  = ['Janvier','Février','Mars','Avril','Mai','Juin','Juillet','Août','Septembre','Octobre','Novembre','Décembre'];

        $jour = $jours[(int)$datetime->format('w')];
        $jourNum = $datetime->format('j');
        $moisNom = $mois[(int)$datetime->format('n') - 1];
        $annee = $datetime->format('Y');

        $date_fr = ucfirst("$jour $jourNum $moisNom");
    }
}

$passengers = htmlspecialchars($_GET['passengers'] ?? '');

setlocale(LC_TIME, 'fr_FR');
date_default_timezone_set('Europe/Paris');


if (isset($_GET['carpooling_id']) && is_numeric($_GET['carpooling_id'])) {
    $carpooling_id = (int) $_GET['carpooling_id'];

    $sql = $db->prepare("
        SELECT 
            cp.carpooling_id,
            cp.driver_id,
            cp.departure_place,
            cp.departure_time,
            cp.arrival_place,
            cp.arrival_time,
            cp.person_price,
            cp.nb_place,

            u.nickname AS driver_nickname,
            u.firstname AS driver_firstname,

            r.comment AS driver_comment,
            r.rating AS driver_rating,

            c.car_model AS car_model,
            c.car_energy AS car_energy,
            b.label AS car_brand,

            (SELECT configuration_value 
            FROM configurations 
            WHERE configuration_user_id = u.user_id AND configuration_name = 'accepts_pets') AS accepts_pets,

            (SELECT configuration_value 
            FROM configurations 
            WHERE configuration_user_id = u.user_id AND configuration_name = 'accepts_smokers') AS accepts_smokers

        FROM carpools cp
        JOIN users u ON cp.driver_id = u.user_id
        JOIN cars c ON cp.car_id = c.car_id
        LEFT JOIN brands b ON c.car_brand_id = b.brand_id
        LEFT JOIN reviews r ON r.user_id = u.user_id AND r.carpooling_id = cp.carpooling_id
        WHERE cp.carpooling_id = :carpooling_id
    ");

    
    if (!$sql) {
        die("Erreur de préparation : " . implode(" - ", $db->errorInfo()));
    }

    $success = $sql->execute(['carpooling_id' => $carpooling_id]);

    if (!$success) {
        die("Erreur d'exécution : " . implode(" - ", $sql->errorInfo()));
    }

    $details = $sql->fetch(PDO::FETCH_ASSOC);

   #var_dump($details); exit; 

    if (!$details) {
        echo "
            <div class='text-center font-semibold mt-10'>
            <span class='text-red-600'>Aucun covoiturage trouvé.</span></br>
            <a class='text-center font-semibold mt-10' href='home.php'>Revenir à la page d'accueil</a>
            </div>
        ";
        include('../../includes/footer.php');
        exit;
    }


    // On met un format plus simple à lire pour l'internaute
    $heureDepart = (new DateTime($details['departure_time']))->format('H:i');
    $heureArrivee = (new DateTime($details['arrival_time']))->format('H:i');

} else {

    echo "ID de covoiturage invalide.";
}

?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EcoRide - Sustainable Ridesharing</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #e4f0e8 100%);
            /*min-height: 100vh;*/
            margin: 0;
            padding: 0;
            position: relative;
            overflow-x: hidden;
        }
        
        .glass-card {
            background: rgba(255, 255, 255, 0.75);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border-radius: 20px;
            border: 1px solid rgba(255, 255, 255, 0.18);
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.1);
        }
        
        .input-glass {
            background: rgba(255, 255, 255, 0.7);
            border: 1px solid rgba(209, 213, 219, 0.3);
            backdrop-filter: blur(5px);
            -webkit-backdrop-filter: blur(5px);
        }
        
        .input-glass:focus {
            border-color: rgba(34, 197, 94, 0.5);
            box-shadow: 0 0 0 3px rgba(34, 197, 94, 0.1);
        }
        
        .eco-bg {
            position: absolute;
            z-index: -1;
            opacity: 0.1;
        }
        
        .eco-bg-1 {
            top: 10%;
            left: 5%;
            transform: rotate(15deg);
        }
        
        .eco-bg-2 {
            bottom: 15%;
            right: 5%;
            transform: rotate(-10deg);
        }
        
        .eco-bg-3 {
            top: 50%;
            right: 15%;
            transform: rotate(5deg);
        }

        .max-width-66 {
            max-width: 66rem;
        }
        
        .btn-eco {
            background: linear-gradient(135deg, #22c55e 0%, #15803d 100%);
            box-shadow: 0 4px 15px rgba(34, 197, 94, 0.3);
            transition: all 0.3s ease;
        }
        
        .btn-eco:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(34, 197, 94, 0.4);
        }
        
        .leaf-icon {
            color: #22c55e;
        }
        
        .location-icon {
            color: #3b82f6;
        }
        
        .date-icon {
            color: #8b5cf6;
        }

        .trip-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        }

        .eco-badge {
            background-color: #e6fbe8;
            border-left: 4px solid #34d399;
            border-radius: 20px;
            padding: 20px;
            margin-top: 30px;
            display: flex;
            align-items: center;
            width: 100%;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
            --tw-border-opacity: 1 !important;
            border-color: rgb(201, 248, 217) !important;
        }

        .timeline-dot {
            width: 16px;
            height: 16px;
            border: 3px solid #10b981;
        }
        .timeline-line {
            width: 2px;
            background: #e2e8f0;
        }
        .btn-reserve {
            background: #10b981;
            transition: all 0.2s ease;
        }
        .btn-reserve:hover {
            background: #059669;
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(16, 185, 129, 0.3);
        }
        .feature-icon {
            width: 24px;
            height: 24px;
            background: #ecfdf5;
            color: #10b981;
        }

        .ecolo-badge {
            position: absolute;
            top: -12px;
            right: 20px;
            background: linear-gradient(135deg,rgb(63, 205, 115) 0%,rgb(13, 122, 53) 100%); 
            color: white; 
            font-size: 0.75rem;
            font-weight: 600;
            padding: 0.25rem 1rem;
            border-radius: 9999px;
            box-shadow: 0 4px 6px rgba(22, 163, 74, 0.2); 
        }


        .hybrid-badge {
            background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
            color: white;
            font-size: 0.75rem;
            font-weight: 600;
            padding: 0.25rem 1rem;
            border-radius: 9999px;
            box-shadow: 0 4px 6px rgba(59, 130, 246, 0.2);
        }

        .electric-badge {
            background: linear-gradient(135deg, #facc15 0%, #eab308 100%); 
            color: #1f2937; 
            font-size: 0.75rem;
            font-weight: 600;
            padding: 0.25rem 1rem;
            border-radius: 9999px;
            box-shadow: 0 4px 6px rgba(234, 179, 8, 0.2); 
        }

        .standard-badge {
            background: linear-gradient(135deg, #6b7280 0%, #374151 100%); 
            color: #f9fafb; 
            font-size: 0.75rem;
            font-weight: 600;
            padding: 0.25rem 1rem;
            border-radius: 9999px;
            box-shadow: 0 4px 6px rgba(55, 65, 81, 0.2); 
        }

        
    </style>
</head>

<body class="min-h-screen bg-[#f4fef7] flex flex-col items-center justify-center p-4 md:p-8">
    
    <div class="container mx-auto px-4 py-8 max-width-66"> 


                <?php
                    $isLoggedIn = isset($_SESSION['auth']) && $_SESSION['auth'] === true;
                    $userId = $isLoggedIn ? $_SESSION['loggedInUser']['user_id'] : null;
                    $isParticipating = false;

                    if ($isLoggedIn) {

                        $checkParticipation = $db->prepare("SELECT COUNT(*) FROM carpools_as_users WHERE carpooling_id = :carpooling_id AND user_id = :user_id");
                        $checkParticipation->execute([
                            ':carpooling_id' => $carpooling_id,
                            ':user_id' => $userId
                        ]);
                        $isParticipating = $checkParticipation->fetchColumn() > 0;
                    }
                ?>


            
            <!-- Bouton de retour -->
            <a href="results.php?departure=<?= urlencode($departure) ?>&arrival=<?= urlencode($arrival) ?>&date=<?= urlencode($date) ?>&passengers=<?= urlencode($passengers) ?>" class="inline-flex items-center text-sm text-slate-600 hover:text-slate-800 mb-6">
                <i class="fas fa-arrow-left mr-2"></i> Retour aux résultats
            </a>


            <!-- Date -->
            <h1 class="text-2xl md:text-3xl font-semibold text-slate-800 mb-6"><?= $date_fr ?></h1>

                <!-- Eco background elements -->
                <div class="eco-bg eco-bg-1">
                    <svg width="120" height="120" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M12 2C13.1046 2 14 2.89543 14 4C14 5.10457 13.1046 6 12 6C10.8954 6 10 5.10457 10 4C10 2.89543 10.8954 2 12 2Z" fill="#22c55e"/>
                        <path d="M6.34315 6.34315C7.46771 5.21858 9.07107 4.58579 10.7574 4.58579C12.4437 4.58579 14.047 5.21858 15.1716 6.34315C16.2961 7.46771 16.9289 9.07107 16.9289 10.7574C16.9289 12.4437 16.2961 14.047 15.1716 15.1716L12 18.3431L8.82843 15.1716C7.70386 14.047 7.07107 12.4437 7.07107 10.7574C7.07107 9.07107 7.70386 7.46771 8.82843 6.34315L6.34315 6.34315Z" fill="#22c55e"/>
                    </svg>
                </div>
                
                <div class="eco-bg eco-bg-2">
                    <svg width="180" height="180" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M12 2C6.48 2 2 6.48 2 12C2 17.52 6.48 22 12 22C17.52 22 22 17.52 22 12C22 6.48 17.52 2 12 2ZM12 20C7.59 20 4 16.41 4 12C4 7.59 7.59 4 12 4C16.41 4 20 7.59 20 12C20 16.41 16.41 20 12 20Z" fill="#22c55e"/>
                        <path d="M12 6C8.69 6 6 8.69 6 12C6 15.31 8.69 18 12 18C15.31 18 18 15.31 18 12C18 8.69 15.31 6 12 6ZM12 16C9.79 16 8 14.21 8 12C8 9.79 9.79 8 12 8C14.21 8 16 9.79 16 12C16 14.21 14.21 16 12 16Z" fill="#22c55e"/>
                    </svg>
                </div>
                
                <div class="eco-bg eco-bg-3">
                    <svg width="150" height="150" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M12 3C10.22 3 8.47991 3.52784 6.99987 4.51677C5.51983 5.50571 4.36628 6.91131 3.68509 8.55585C3.0039 10.2004 2.82567 12.01 3.17294 13.7558C3.5202 15.5016 4.37737 17.1053 5.63604 18.364C6.89472 19.6226 8.49836 20.4798 10.2442 20.8271C11.99 21.1743 13.7996 20.9961 15.4442 20.3149C17.0887 19.6337 18.4943 18.4802 19.4832 17.0001C20.4722 15.5201 21 13.78 21 12C21 9.61305 20.0518 7.32387 18.364 5.63604C16.6761 3.94821 14.3869 3 12 3ZM12 19C10.4178 19 8.87104 18.5308 7.55544 17.6518C6.23985 16.7727 5.21447 15.5233 4.60897 14.0615C4.00347 12.5997 3.84504 10.9911 4.15372 9.43928C4.4624 7.88743 5.22433 6.46197 6.34315 5.34315C7.46197 4.22433 8.88743 3.4624 10.4393 3.15372C11.9911 2.84504 13.5997 3.00347 15.0615 3.60897C16.5233 4.21447 17.7727 5.23985 18.6518 6.55544C19.5308 7.87104 20 9.41775 20 11C20 13.1217 19.1572 15.1566 17.6569 16.6569C16.1566 18.1571 14.1217 19 12 19Z" fill="#22c55e"/>
                    </svg>
                </div>
            </div>

        
            <div class="flex flex-col lg:flex-row justify-center gap-8">
                
                <!-- Left column - Timeline -->
                <div class="trip-card p-6 lg:w-2/5 sticky top-24">
                    <div class="flex items-start mb-8">
                        <div class="flex flex-col justify-between items-center h-full mr-4">
                            <div class="timeline-dot rounded-full mb-2"></div>
                                <div class="timeline-line h-28"></div>
                            <div class="w-4 h-4 rounded-full bg-slate-300"></div>
                        </div>
                        <div class="flex-grow">
                            <div class="pb-10">
                                <h2 class="text-lg font-semibold text-slate-800"><?= $details["departure_place"]?></h2>
                                <?php # <p class="text-slate-500">Porte Maillot</p> ?>
                                <div class="flex items-center mt-2 text-slate-600">
                                    <i class="far fa-clock mr-2"></i>
                                    <span class="font-medium"><?= $heureDepart ?></span>
                                </div>
                            </div>
                            <div>
                                <h2 class="text-lg font-semibold text-slate-800"><?= $details["arrival_place"]?></h2>
                                <?php # <p class="text-slate-500">Centrale Station</p> ?>
                                <div class="flex items-center mt-2 text-slate-600">
                                    <i class="far fa-clock mr-2"></i>
                                    <span class="font-medium"><?= $heureArrivee ?></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <?php 
                    /* <!-- Durée du trajet (Mise à jour potentiel) -->
                    <div class="flex items-center text-slate-600 mb-8">
                        <i class="fas fa-road mr-2"></i>
                        <span>Approx. 6h 30m • 850 km</span>
                    </div> */ 
                    ?>

                    <!-- Info du conducteur -->
                    <div class="border-t border-slate-100 pt-6 mb-8">
                        <div class="flex items-center mb-4">
                            <div class="w-12 h-12 rounded-full bg-slate-200 flex items-center justify-center mr-4 overflow-hidden">
                                <?php 
                                    $profilePath = '/images/profile_photos/user_' . $details['driver_id'] . '.jpg';
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
                            <div>
                                <h3 class="font-medium text-slate-800"><?= $details['driver_firstname'] ?></h3>
                                <p class="text-sm text-slate-500">Conducteur</p>
                            </div>
                        </div>
                        <div class="flex items-center text-slate-600">
                            <div>
                                <?= $details['car_brand'] ?> <?= $details['car_model'] ?>
                                </br>
                                <?php if($details['car_energy'] == 'Hybride'): ?>
                                    <span class="ecolo-badge">Trajet Écologique</span>
                                    <span class="hybrid-badge"><i class="fas fa-car mr-2"></i><?= $details['car_energy'] ?></span>                                                                       
                                <?php elseif($details['car_energy'] == 'Électrique'): ?>
                                    <span class="ecolo-badge">Trajet Écologique</span>
                                    <span class="electric-badge"><i class="fa-solid fa-bolt mr-1"></i><?= $details['car_energy'] ?></span>
                                <?php else: ?>
                                    <span class="standard-badge"><i class="fas fa-car mr-2"></i><?= $details['car_energy'] ?></span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <!-- Préferences -->
                    <div class="border-t border-slate-100 pt-6 mb-8">
                        <h3 class="font-medium text-slate-800 mb-3">Préferences</h3>
                        <div class="flex flex-wrap gap-3">

                            <?php if ($details["accepts_pets"] == 0): ?>
                                <div class="flex items-center bg-slate-50 rounded-full px-3 py-1 text-sm">
                                    <i class="fas fa-ban text-slate-500 mr-1"></i>
                                    <span class="text-slate-600">Animaux Non-autorisés</span>
                                </div>
                            <?php else: ?>
                                <div class="flex items-center bg-green-50 rounded-full px-3 py-1 text-sm">
                                    <i class="fas fa-paw text-green-500 p-1 mr-1"></i>
                                    <span class="text-slate-600">Animaux autorisés</span>
                                </div>
                            <?php endif; ?>

                            
                            <?php if ($details["accepts_smokers"] == 0): ?>
                                <div class="flex items-center bg-slate-50 rounded-full px-3 py-1 text-sm">
                                    <i class="fas fa-smoking-ban text-slate-500 mr-1" style="padding-right: 5px;"></i>
                                    <span class="text-slate-600">Fumeurs Non-autorisés</span>
                                </div>
                            <?php else: ?>    
                                <div class="flex items-center bg-green-50 rounded-full px-3 py-1 text-sm">
                                    <i class="fas fa-smoking text-green-500 mr-1" style="padding-right: 5px;"></i>
                                    <span class="text-slate-600">Fumeurs autorisés</span>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <?php

                        $equipmentsStmt = $db->prepare("
                            SELECT configuration_name, configuration_value
                            FROM configurations
                            WHERE configuration_user_id = :user_id
                        ");
                        $equipmentsStmt->execute(['user_id' => $details['driver_id']]);
                        $equipments = $equipmentsStmt->fetchAll(PDO::FETCH_KEY_PAIR); // [nom => valeur]

                    ?>

                    <?php if (!empty($equipments)): ?>
                        
                        <!-- Informations -->
                        <div class="border-t border-slate-100 pt-6 mb-4">
                            <h3 class="font-medium text-slate-800 mb-4">Informations sur la voiture</h3>
                            <div class="grid grid-cols-2 md:grid-cols-3 gap-4">

                                <?php if (isset($equipments['has_ac']) && $equipments['has_ac'] == 1): ?>
                                    <div class="flex items-center">
                                        <div class="feature-icon rounded-full flex items-center justify-center mr-3">
                                            <i class="fas fa-snowflake text-xs"></i>
                                        </div>
                                        <span class="text-slate-600">Climatisé</span>
                                    </div>
                                <?php endif; ?>


                                <?php if (isset($equipments["has_usb"]) && $equipments["has_usb"] == 1): ?>
                                    <div class="flex items-center">
                                        <div class="feature-icon rounded-full flex items-center justify-center mr-3">
                                            <i class="fas fa-plug text-xs"></i>
                                        </div>
                                        <span class="text-slate-600">Ports USB</span>
                                    </div>
                                <?php endif; ?>


                                <?php if (isset($equipments["has_reclining_seats"]) && $equipments["has_reclining_seats"] == 1): ?>
                                    <div class="flex items-center">
                                        <div class="feature-icon rounded-full flex items-center justify-center mr-3">
                                            <i class="fas fa-chair text-xs"></i>
                                        </div>
                                        <span class="text-slate-600">Sièges inclinables</span>
                                    </div>
                                <?php endif; ?>


                                <?php if (isset($equipments["has_wifi"]) && $equipments["has_wifi"] == 1): ?>
                                    <div class="flex items-center">
                                        <div class="feature-icon rounded-full flex items-center justify-center mr-3">
                                            <i class="fas fa-wifi text-xs"></i>
                                        </div>
                                        <span class="text-slate-600">WiFi</span>
                                    </div>
                                <?php endif; ?>


                                <?php if (isset($equipments["has_large_trunk"]) && $equipments["has_large_trunk"] == 1): ?>
                                    <div class="flex items-center">
                                        <div class="feature-icon rounded-full flex items-center justify-center mr-3">
                                            <i class="fas fa-suitcase text-xs"></i>
                                        </div>
                                        <span class="text-slate-600">Grand coffre</span>
                                    </div>
                                <?php endif; ?>    
                            </div>
                        <?php endif; ?> 
                    </div>
                </div>

                <!-- Right column - Booking card -->
                <div class="trip-card p-6 lg:w-1/3 h-fit sticky top-24">
                    <div class="flex justify-between items-center mb-6">
                        <span class="text-slate-500">Prix par personne</span>
                        <span class="text-3xl font-bold text-slate-800">35€</span>
                    </div>

                    <div class="border-t border-slate-100 pt-4 mb-6">
                        <div class="flex items-center text-slate-600 mb-3">
                            <i class="far fa-user mr-3"></i>
                            <span><?= $details['nb_place'] == 0 ? 'Aucune place restante' : ($details['nb_place'] == 1 ? '1 place restante' : $details['nb_place'] . ' places restantes') ?></span>
                        </div>
                        <?php

                            $carpoolingId = $_GET['carpooling_id'] ?? null;

                            if ($carpoolingId) {

                                // Étape 1 : récupérer le conducteur du covoiturage
                                $sqlDriver = "
                                    SELECT driver_id
                                    FROM carpools
                                    WHERE carpooling_id = :carpooling_id
                                    LIMIT 1
                                ";
                                $stmtDriver = $db->prepare($sqlDriver);
                                $stmtDriver->execute(['carpooling_id' => $carpoolingId]);
                                $driverId = $stmtDriver->fetchColumn();
                            
                                if ($driverId) {

                                    // Étape 2 : récupérer tous les avis sur ce conducteur pour ce trajet
                                    $sqlReviews = "
                                        SELECT 
                                            r.review_id,
                                            r.comment,
                                            r.rating,
                                            r.status,
                                            u.firstname AS reviewer_firstname,
                                            u.photo AS reviewer_photo
                                        FROM reviews r
                                        JOIN users u ON r.reviewer_id = u.user_id
                                        WHERE r.user_id = :driver_id
                                        AND r.carpooling_id = :carpooling_id
                                        ORDER BY r.review_id DESC;
                                    ";
                            
                                    $stmt = $db->prepare($sqlReviews);
                                    $stmt->execute([
                                        'driver_id' => $driverId,
                                        'carpooling_id' => $carpoolingId
                                    ]);

                                    $reviews = $stmt->fetchAll(PDO::FETCH_ASSOC);                  
                            
                                    // DEBUG
                                    #var_dump($reviews); exit;
                            
                                    // Utilisation des données :
                                    /*
                                    foreach ($reviews as $review) {
                                        echo "<div class='mb-4'>";
                                        echo "<p class='font-semibold'>{$review['reviewer_firstname']} :</p>";
                                        echo "<p class='italic text-slate-600'>\"{$review['comment']}\"</p>";
                                        echo "<p class='text-sm text-slate-500'>Note : {$review['rating']} / 5</p>";
                                        echo "</div>";
                                    }
                                    */
                                } else {
                                    echo "Conducteur introuvable pour ce covoiturage.";
                                }
                            } else {
                                echo "Covoiturage non spécifié.";
                            }     

                        ?>

                        <?php

                        // Calcul de la moyenne
                        $total = 0;
                        $count = 0;

                        foreach ($reviews as $review) {
                            if ($review['status'] === "Validé" && is_numeric($review['rating'])) {
                                $total += (int)$review['rating'];
                                $count++;
                            }
                        }

                        $average = $count > 0 ? round($total / $count, 1) : null;

                        ?>

                        <div class="flex items-center text-slate-600">
                            <i class="far fa-star mr-3"></i>
                            <?php
                                if ($average !== null) {
                                    echo "$average / 5";
                                } else {
                                    echo "Pas encore évaluée";
                                }
                            ?>
                        </div>
                    </div>

                    <!-- <button class=" w-full py-3 rounded-lg text-white ">
                        
                    </button> -->

                    <?php if ($isLoggedIn && !$isParticipating): ?>

                        <form method="POST" action="results.php" onsubmit="return confirmParticipation();" class="relative z-10 flex justify-center" style="padding-bottom: 15px;">
                            <input type="hidden" name="carpooling_id" value="<?= htmlspecialchars($details['carpooling_id']) ?>">
                            <button type="submit" class="btn-eco pb-[15] text-white font-semibold py-3 px-8 rounded-full text-lg 
                                shadow-lg transition-all duration-300 transform hover:scale-105 w-full md:w-[220px]" style="width: 100%; padding-bottom: 15px;">
                                <i class="fas fa-plus-circle mr-2"></i> Réserver ma place
                            </button>
                        </form>
                    <?php elseif (!$isLoggedIn): ?>
                        <div class="rounded-[20px] bg-orange-100 border-l-4 border-orange-500 text-orange-700 p-4" role="alert">
                            <p class="font-bold">Attention!</p>
                            <p>Vous devez vous connectez pour réserver ce trajet et utiliser vos crédits.</p>
                        </div>
                    <?php else: ?>
                        <div class="rounded-[20px] bg-gray-100 border-l-4 border-gray-500 text-gray-700 p-4" role="alert">
                            <p class="font-bold">Information</p>
                            <p>Vous participez déjà à ce covoiturage.</p>
                        </div>
                    <?php endif; ?>
                    

                    <?php if ($isLoggedIn): ?>
                            <script>
                                function confirmParticipation() {
                                    return confirm("Are you sure you want to spend 2 credits to join this ride?");
                                }
                            </script>
                    <?php endif; ?>


                    <!-- Cancellation policy -->
                    <div class="border-t border-slate-100 pt-4">
                        <h3 class="font-medium text-slate-800 mb-3 flex items-center">
                            <i class="fas fa-shield-alt text-slate-400 mr-2"></i>
                            Politique d'annulation
                        </h3>
                        <p class="text-sm text-slate-600 mb-2">Annulation gratuite jusqu'à 24 heures avant le départ.</p>
                        <p class="text-sm text-slate-600">Le remboursement est de 50 % si l'annulation intervient au moins 1 heure avant le départ.</p>
                    </div>
                </div>

                
            </div>


                

                <?php if (!empty($reviews)): ?>
                    <div class="w-full px-4 sm:px-6 lg:px-8 mt-8">
                        <div class="bg-white p-6 rounded-2xl shadow-md mx-auto" style="max-width: 63rem;">
                            <h2 class="text-lg font-semibold text-slate-800 mb-4">Avis sur le conducteur</h2>

                            <?php foreach ($reviews as $review): ?>
                                <div class="bg-green-50 border-l-4 border-green-400 rounded-xl p-4 mb-3">
                                    <div class="flex items-center mb-1">
                                        <i class="fas fa-user-circle text-green-600 mr-2"></i>
                                        <span class="font-semibold text-slate-800"><?= $review['reviewer_firstname']; ?></span>
                                        <span class="text-slate-500 ml-2 text-sm">– <?= $review['rating']; ?> <i class="fa fa-star" aria-hidden="true"></i></span>
                                    </div>
                                    <p class="italic text-slate-700 text-sm"><?= $review['comment']; ?></p>
                                </div>
                            <?php endforeach; ?>

                        </div>
                    </div>
                <?php endif; ?>






            <!-- Eco impact -->
            <div class="container mx-auto px-4 max-width-66 mt-8">
                <div class="eco-badge w-full p-6 bg-green-50 border border-green-100 rounded-lg">
                    <div class="flex items-center">
                        <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center mr-4 text-green-600">
                            <i class="fas fa-leaf"></i>
                        </div>
                        <div>
                            <h3 class="font-medium text-slate-800">Voyage écologique</h3>
                            <p class="text-sm text-slate-600">Ce covoiturage réduit les émissions de CO₂ de ~30 kg par rapport à la conduite seule.</p>
                        </div>
                    </div>
                </div>
            </div>


    
            <?php /*
                <!-- Header de la carte
                <div class="text-center mb-8">
                    <p class="text-gray-600 text-lg">Informations supplémentaires sur le trajet</p>
                </div>

                Corps de la carte 
                <div class="glass-card p-6 md:p-8 w-full" >
                    <h2 class="text-2xl font-semibold text-gray-800 mb-6">De <?= $departure ?> à <?= $arrival ?></h2>

                    <p class="text-gray-700 mb-2">
                        <strong>Départ :</strong> <?= htmlspecialchars($details['departure_place']) ?> à <?= htmlspecialchars($details['departure_time']) ?>
                    </p>
                    <p class="text-gray-700 mb-2">
                        <strong>Arrivée :</strong> <?= htmlspecialchars($details['arrival_place']) ?> à <?= htmlspecialchars($details['arrival_time']) ?>
                    </p>
                    <p class="text-gray-700 mb-2">
                        <strong>Conducteur :</strong> <?= htmlspecialchars($details['driver_nickname']) ?>
                    </p>
                    <p class="text-gray-700 mb-2">
                        <strong>Véhicule :</strong> <?= htmlspecialchars($details['car_model']) ?> - <?= htmlspecialchars($details['car_energy']) ?>
                    </p>
                    <p class="text-gray-700 mb-2">
                        <strong>Préférences :</strong>
                        <?= $details['accepts_pets'] ? 'Animaux acceptés' : 'Animaux non acceptés' ?> /
                        <?= $details['accepts_smokers'] ? 'Fumeurs acceptés' : 'Non-fumeur' ?>
                    </p>
                    <p class="text-gray-700 mb-2">
                        <strong>Prix :</strong> <?= htmlspecialchars($details['person_price']) ?>€
                    </p>
                    <p class="text-gray-700 mb-2">
                        <strong>Avis :</strong>
                        <?= $details['driver_rating'] !== null ? htmlspecialchars($details['driver_rating']) . ' / 5' : 'Non noté' ?>
                    </p>

                    <?php
                        $isLoggedIn = isset($_SESSION['auth']) && $_SESSION['auth'] === true;
                        $userId = $isLoggedIn ? $_SESSION['loggedInUser']['user_id'] : null;
                        $isParticipating = false;

                        if ($isLoggedIn) {
                            $checkParticipation = $db->prepare("SELECT COUNT(*) FROM carpools_as_users WHERE carpooling_id = :carpooling_id AND user_id = :user_id");
                            $checkParticipation->execute([
                                ':carpooling_id' => $carpooling_id,
                                ':user_id' => $userId
                            ]);
                            $isParticipating = $checkParticipation->fetchColumn() > 0;
                        }
                    ?>

                    <div class="text-center mt-6">
                        <a class="btn btn-outline-dark position-relative rounded-3xl" href="results.php?departure=<?= urlencode($departure) ?>&arrival=<?= urlencode($arrival) ?>&date=<?= urlencode($date) ?>&passengers=<?= urlencode($passengers) ?>">
                            Retour aux résultats
                        </a>

                    <?php if ($isLoggedIn && !$isParticipating): ?>
                        
                            <form method="POST" action="results.php" onsubmit="return confirmParticipation();" class="inline-block">
                                <input type="hidden" name="carpooling_id" value="<?= htmlspecialchars($details['carpooling_id']) ?>">
                                <button type="submit" name="join_carpool" class="btn-eco px-5 py-2 rounded-full font-semibold text-white bg-green-500 hover:bg-green-600 transition">
                                    Rejoindre ce covoiturage
                                </button>
                            </form>
                        
                    <?php endif; ?>
                    </div>

                    <?php if ($isLoggedIn): ?>
                            <script>
                                function confirmParticipation() {
                                    return confirm("Are you sure you want to spend 2 credits to join this ride?");
                                }
                            </script>
                    <?php endif; ?>
                </div> -->

                */ 
            ?>
            
    </div>

</body>
<?php include('../../includes/footer.php'); ?>