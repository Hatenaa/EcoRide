<?php

$pageTitle = 'EcoRide | Covoiturage écologique, Allez partout à petit prix';
$pageDescritption = '';

include('../../includes/header.php');

/*
echo '<pre>';
print_r($_SESSION);
echo '</pre>';
*/


?>

<body>
<link href="./assets/css/styles.css" rel="stylesheet">

    <?= alertMessage(); ?>

    <?php 
        if (isset($_SESSION['message'])) {

            $type = $_SESSION['message_type'] ?? 'info'; // 'success', 'danger', 'warning', etc.
                        
            echo "<div class='alert alert-{$type} fade show mt-3' role='alert'>
                    <h4 style=\"font-size: 18px; text-align: center; color: --bs-alert-color: var(--bs-{$type}-text-emphasis);\">
                        {$_SESSION['message']}
                    </h4>
                   </div>";

                    unset($_SESSION['message']);
                    unset($_SESSION['message_type']);
                    
            } 
    ?>

    <style>
        /*
        .landing-sections {
            max-width: 80%; 
            border-radius: 30px;
            position: relative;
        }

        .navbar-custom {
            position: absolute;
            top: 85%;
            left: 14%;
            width: 70%;
            background-color: rgba(255, 255, 255, 0.8); /* Semi-transparent background *//*
            border-radius: 7px;
        }

        .bg--img {
            width: 260px; 
            height: 180px; 
            border-radius: 10px; 
            top: 20px; 
            right: 20px;
        }*/

        .form-overlay {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 10;
            width: 90%;
            max-width: 1000px;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border-radius: 22px;
            padding: 2rem;
            box-shadow: 0 10px 25px rgba(0,0,0,0.3);
        }

        .form-glass {
            border: 1px solid rgba(255, 255, 255, 0.2);
            background: rgba(255, 255, 255, 0.95);   /* Fond blanc semi-transparent */
            backdrop-filter: blur(10px);           /* Flou sur l’arrière-plan */
            color: #111;                           /* Texte noir ou très foncé */
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.1);
        }
        

        .relative-container img {
            display: block;
            width: 100%;
        }

        .relative-container::before {
            content: "";
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            background: rgba(0,0,0,0.25); /* voile sombre */
            z-index: 1;
        }

        .btn-eco {
            background: linear-gradient(35deg, #22c55e 0%, #15803d 100%);
            box-shadow: 0 4px 15px rgba(34, 197, 94, 0.3);
            transition: all 0.3s ease;
        }
        
        .btn-eco:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(34, 197, 94, 0.4);
        }

        .bg-white {
            background: rgba(255, 255, 255, 0.9);
            border-radius: 36px;
            border: 1px solid rgba(255, 255, 255, 0.53);
        }

        .backdrop-blur-md {
            --tw-backdrop-blur: blur(50px);
            color: black
        }

        input::placeholder, textarea::placeholder {
            opacity: 1;
            color: #0000008c;
        }

        form.grid {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr 1fr; /* 4 colonnes par défaut */
            gap: 1rem; /* ou selon ton design */
        }

        glass-card {
            backdrop-filter: blur(16px) saturate(180%);
            -webkit-backdrop-filter: blur(16px) saturate(180%);
            background-color: rgba(255, 255, 255, 0.15);
            border: 1px solid rgba(255, 255, 255, 0.125);
        }
        .input-glass {
            backdrop-filter: blur(16px) saturate(180%);
            -webkit-backdrop-filter: blur(16px) saturate(180%);
            background-color: rgba(255, 255, 255, 0.25);
            border: 1px solid rgba(255, 255, 255, 0.125);
        }
        .btn-glow:hover {
            box-shadow: 0 0 15px rgba(99, 102, 241, 0.7);
        }

        /* Responsive mobile */
        @media (max-width: 768px) {
            form.grid {
                grid-template-columns: 1fr 1fr; /* 2 colonnes sur mobile */
            }

            form.grid .col-span-2 {
                grid-column: span 2 / span 2;
                display: flex;
                justify-content: center;
            }

            form.grid button {
                width: 100%;
                max-width: 250px; /* optionnel */
            }
        }

        .input-glass {
            backdrop-filter: blur(16px) saturate(180%);
            -webkit-backdrop-filter: blur(16px) saturate(180%);
            background-color: rgba(255, 255, 255, 0.25);
            border: 1px solid rgba(255, 255, 255, 0.125);
        }

        .btn-eco {
            background: linear-gradient(35deg, #22c55e 0%, #15803d 100%);
            box-shadow: 0 4px 15px rgba(34, 197, 94, 0.3);
            transition: all 0.3s ease;
        }
        .btn-eco:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(34, 197, 94, 0.4);
        }

        .about-img {
            background-image: url('../../images/homepage2.jpg');
            background-size: cover;
            background-position: center;
            border-radius: 0 1rem 1rem 0;
        }

    </style>

<section class="relative-container relative h-[600px] md:h-[620px] overflow-hidden">
    <!-- Image de fond -->
    <img src="/../images/homepage1.jpg" class="w-full h-full object-cover object-center absolute inset-0 z-0" alt="Responsive image">

    <!-- Overlay sombre -->
    <div class="absolute inset-0 bg-black/30 z-0"></div>

    <!-- Formulaire -->
    <div class="absolute top-[55%] md:top-[75%] left-1/2 transform -translate-x-1/2 -translate-y-1/2 
                w-[92%] sm:w-[90%] md:w-[80%] max-w-6xl 
                bg-white/30 backdrop-blur-md  border-white/20 shadow-2xl 
                rounded-2xl px-4 py-6 md:p-8 z-10">
        
        <form action="/pages/results.php" method="GET" class="flex flex-col md:flex-row md:items-end gap-4 md:gap-6">

            <!-- Depart -->
            <div class="flex-1">
                <label for="departure" class="block text-sm font-medium text-white/80 mb-1">Départ</label>
                <input name="departure" type="text" id="departure" placeholder="De"
                    class="input-glass w-full rounded-xl px-4 py-3 text-white placeholder-white/60 
                           focus:outline-none focus:ring-2 focus:ring-green-400 transition-all" />
            </div>

            <!-- Destination -->
            <div class="flex-1">
                <label for="destination" class="block text-sm font-medium text-white/80 mb-1">Destination</label>
                <input name="arrival" type="text" id="destination" placeholder="À"
                    class="input-glass w-full rounded-xl px-4 py-3 text-white placeholder-white/60 
                           focus:outline-none focus:ring-2 focus:ring-green-400 transition-all" />
            </div>

            <!-- Date -->
            <div class="flex-1">
                <label for="date" class="block text-sm font-medium text-white/80 mb-1">Date</label>
                <input name="date" type="date" id="date"
                    class="input-glass w-full rounded-xl px-4 py-3 text-white 
                           focus:outline-none focus:ring-2 focus:ring-green-400 transition-all" />
            </div>

            <!-- Places -->
            <div class="flex-1">
                <label for="seats" class="block text-sm font-medium text-white/80 mb-1">Nombre</label>
                <select name="passengers" id="seats"
                    class="input-glass w-full rounded-xl px-4 py-3 text-white 
                           focus:outline-none focus:ring-2 focus:ring-green-400 transition-all">
                    <option style="color: black;" value="1">1 Passager</option>
                    <option style="color: black;" value="2">2 Passagers</option>
                    <option style="color: black;" value="3">3 Passagers</option>
                    <option style="color: black;" value="4">4 Passagers</option>
                </select>
            </div>

            <!-- Reserve Button -->
            <div class="flex justify-center md:justify-end w-full md:w-auto pt-2 md:pt-0">
                <button type="submit"
                    class="btn-eco text-white font-semibold py-3 px-8 rounded-full text-lg 
                           transition-all duration-300 transform hover:scale-105 w-full md:w-[200px]">
                    <i class="fas fa-search mr-2"></i> Réserver
                </button>
            </div>
        </form>
    </div>
</section>


<!-- Benefits Section -->
<section class="py-16 bg-white">
        <div class="container mx-auto px-4">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-800 mb-4">Pourquoi choisir EcoRide ?</h2>
                <p class="text-lg text-gray-600 max-w-2xl mx-auto">Découvrez les avantages de notre plateforme de covoiturage écologique</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                <!-- Benefit 1 -->
                <div class="benefit-card bg-white p-6 rounded-xl shadow-md border border-gray-100">
                    <div class="w-14 h-14 bg-primary-100 rounded-full flex items-center justify-center mb-4">
                        <i class="fas fa-leaf text-2xl text-primary-600"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-800 mb-2">Écologique</h3>
                    <p class="text-gray-600">Réduisez votre empreinte carbone en partageant vos trajets et diminuez les émissions de CO₂.</p>
                </div>
                
                <!-- Benefit 2 -->
                <div class="benefit-card bg-white p-6 rounded-xl shadow-md border border-gray-100">
                    <div class="w-14 h-14 bg-primary-100 rounded-full flex items-center justify-center mb-4">
                        <i class="fas fa-euro-sign text-2xl text-primary-600"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-800 mb-2">Économique</h3>
                    <p class="text-gray-600">Partagez les frais de carburant et réduisez vos dépenses de transport jusqu'à 75%.</p>
                </div>
                
                <!-- Benefit 3 -->
                <div class="benefit-card bg-white p-6 rounded-xl shadow-md border border-gray-100">
                    <div class="w-14 h-14 bg-primary-100 rounded-full flex items-center justify-center mb-4">
                        <i class="fas fa-users text-2xl text-primary-600"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-800 mb-2">Convivial</h3>
                    <p class="text-gray-600">Rencontrez des personnes partageant les mêmes valeurs écologiques que vous.</p>
                </div>
                
                <!-- Benefit 4 -->
                <div class="benefit-card bg-white p-6 rounded-xl shadow-md border border-gray-100">
                    <div class="w-14 h-14 bg-primary-100 rounded-full flex items-center justify-center mb-4">
                        <i class="fas fa-shield-alt text-2xl text-primary-600"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-800 mb-2">Sécurisé</h3>
                    <p class="text-gray-600">Tous nos utilisateurs sont vérifiés pour garantir des trajets en toute sécurité.</p>
                </div>
            </div>
        </div>
    </section>       

    <!-- About Section -->
    <section class="py-16 bg-gray-50">
        <div class="container mx-auto px-4">
            <div class="flex flex-col md:flex-row bg-white rounded-xl shadow-md overflow-hidden">
                <div class="md:w-1/2 p-8 md:p-12">
                    <h2 class="text-3xl font-bold text-gray-800 mb-6">Ensemble pour un avenir plus vert</h2>
                    <p class="text-gray-600 mb-6">EcoRide est né d'une conviction simple : le transport doit évoluer pour préserver notre planète. Nous croyons que chaque petit geste compte, et le covoiturage est l'une des solutions les plus efficaces pour réduire notre impact environnemental.</p>
                    <p class="text-gray-600 mb-8">Notre plateforme met en relation des conducteurs et des passagers partageant les mêmes trajets, permettant ainsi de diminuer le nombre de véhicules sur les routes et les émissions de gaz à effet de serre. Rejoignez notre communauté engagée pour une mobilité plus durable.</p>
                    
                    <div class="flex items-center">
                        <div class="w-10 h-10 rounded-full bg-primary-100 flex items-center justify-center mr-3 overflow-hidden">
                            <div class="w-10 h-10 rounded-full bg-primary-100 flex items-center justify-center overflow-hidden">
                                <?php if (file_exists('images/profile_photos/user_1.jpg')): ?>
                                    <img src="/images/profile_photos/user_1.jpg" alt="Photo profil" class="w-full h-full object-cover">
                                <?php else: ?>
                                    <i class="fas fa-user text-primary-600"></i>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Par</p>
                            <p class="font-medium text-gray-800">José REISS</p>
                        </div>
                    </div>
                </div>
                <div class="md:w-1/2 about-img min-h-[300px] md:min-h-full"></div>
            </div>
        </div>
    </section>



<?php /* <section class="container py-5 landing-sections" style="margin-top: -20px;">
    <div class="py-5" style="background-color: #f8f9fa; border-radius: 22px; padding: 200px;">
        <div class="container">
            <div class="row align-items-center">
                <!-- Texte -->
                <div class="col-lg-6">
                    <h2 class="mb-3">Ensemble pour un avenir plus vert</h2>
                    <p class="lead text-muted mb-4">La solution de covoiturage écologique et économique mise en place par EcoRide</p>
                    <p class="mb-4">
                        Nous prônons une mobilité plus responsable en réduisant l'impact environnemental des déplacements en voiture.
                        En effet, en rejoignant notre communauté, vous devenez un acteur du changement en optant pour une solution plus verte et économique.
                        Chaque kilomètre parcouru ensemble, c'est moins de CO₂ émis et plus d'économies pour tous!
                    </p>
                    <div class="d-flex align-items-center">
                        <div class="rounded-circle bg-dark text-white d-flex justify-content-center align-items-center" style="width: 40px; height: 40px;">
                            <span>🌱</span>
                        </div>
                        <span class="ml-3" style="padding-left: 15px;">Auteur : José Reiss</span>
                    </div>
                </div>

                <!-- Image -->
                <div class="col-lg-6 d-flex justify-content-center position-relative">

                    <img src="images/homepage2" alt="Image de paysage" class="rounded shadow-lg" style="height: 220px; object-fit: cover; z-index: 2;">
                    <div class="position-absolute bg-white bg--img"></div>
                    
                </div>
            </div>
        </div>
    </div>
</section> */ ?>


<?php include('../../includes/footer.php'); ?>
    
    