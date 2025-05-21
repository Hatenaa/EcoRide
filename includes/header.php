<?php

$current_page = basename($_SERVER['PHP_SELF']);

include realpath($_SERVER['DOCUMENT_ROOT'] . '/../config/function.php');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$credits = 0;

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($pageTitle) ? $pageTitle : 'Device Services'; ?></title>
    <!-- Lien Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Lien CSS Local -->
    <link rel="stylesheet" href="http://localhost/EcoRide/assets/css/styles.css">
    

</head>

<?php /*
    <div class="container">
    <!-- Barre de navigation -->
    <nav class="navbar navbar-expand-lg navbar-white bg-white" style="border-radius: 20px;">
        <div class="container-fluid">
            <!-- Logo ou titre de l'application -->
            <span class="text-xl font-bold text-gray-800">Eco<span class="text-green-600">Ride</span></span>

            <!-- Bouton pour la version mobile -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- Menu -->
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    
                    <!-- Lien vers la page des covoiturages -->
                    <li class="nav-item">
                        <a class="nav-link" href="search.php">Covoiturages</a>
                    </li>

                    <!-- Lien vers la page de contact -->
                        <li class="nav-item">
                            <a class="nav-link" href="contact.php">Contact</a>
                        </li>
                    <!-- Lien vers la page de connexion -->

                    <?php if (isset($_SESSION['auth']) && $_SESSION['auth'] === true) : ?>
                        <?php if (isset($_SESSION['is_admin']) && $_SESSION['is_admin'] === true) : ?>
                            <li class="nav-item">
                                <a class="nav-link" href="dashboard/admin/dashboard_admin.php">Dashboard</a>
                            </li>
                        <?php endif; ?>

                        <?php 

                        $user = $_SESSION['loggedInUser'];

                        if (($user['user_id'] != 1)) : 
                        
                        ?> 
                        <li class="nav-item">
                            <a class="nav-link" href="dashboard/users/dashboard_users.php">Dashboard</a>
                        </li>
                        <a href="credits.php" class="btn btn-outline-dark position-relative"> 
                            Crédits
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" id="credits-badge">

                                <?= $_SESSION['loggedInUser']['credits'] ?? 'Crédits indisponibles'; ?>
                                <span class="visually-hidden"></span>
                            </span>
                        </a>      
                        <?php endif; ?>

                        <li class="nav-item d-flex align-items-center">
                            <script src='https://kit.fontawesome.com/a076d05399.js' crossorigin='anonymous'></script>
                            <a class="btn btn-dark ms-2" href="dashboard/logout.php">Déconnexion</a>
                        </li>
                    <?php else : ?>
                        

                        <li class="nav-item">
                            <a class="btn btn-outline-success me-2" href="login.php">Connexion</a>
                        </li>

                        <!-- Lien vers la page d'inscription -->
                        <li class="nav-item">
                            <a class="btn btn-success" href="register.php">Inscription</a>
                        </li>
                    <?php endif; ?>
                    
                </ul>
            </div>
        </div>
    </nav>
</div> */ ?>

<header class="w-full sticky top-0 z-50 glass-card text-white shadow-lg h-20 flex items-center">
  <div class="container mx-auto px-4 py-2">
            <div class="flex justify-between items-center h-16">
                
                <!-- Logo -->
                <div class="flex items-center">                    
                    <h2 class="text-xl font-bold text-slate-800 flex items-center">
                        <i class="fas fa-leaf text-green-500 mr-2"></i>
                            <a href="/">
                                EcoRide
                            </a>
                    </h2>                
                </div>
                
                <!-- Desktop Navigation -->
                <nav class="hidden md:flex items-center space-x-8">
                    <a href="/pages/search.php" class="nav-link text-black hover:text-primary-400">Covoiturages</a>
                    <a href="/pages/contact.php" class="nav-link text-black hover:text-primary-400">Contact</a>
                    
                    
                    <?php if (isset($_SESSION['auth']) && $_SESSION['auth'] === true) : ?>
                        <?php if (isset($_SESSION['is_admin']) && $_SESSION['is_admin'] === true) : ?>

                                <a class="nav-link text-black hover:text-primary-400" href="/admin/dashboard_admin.php">Dashboard</a>

                        <?php endif; ?>

                        <?php 

                        $user = $_SESSION['loggedInUser'];

                        if (($user['user_id'] != 1)) : 
                        
                        ?> 

                        <a class="nav-link text-black hover:text-primary-400" href="/users/dashboard_users.php">Dashboard</a>
                        <a href="/pages/credits.php" class="btn btn-outline-dark position-relative rounded-3xl"> 
                            Crédits
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" id="credits-badge">

                                <?= $_SESSION['loggedInUser']['credits'] ?? 'Crédits indisponibles'; ?>
                                <span class="visually-hidden"></span>
                            </span>
                        </a>      
                        <?php endif; ?>
                        <li class="nav-item d-flex align-items-center">
                            <script src='https://kit.fontawesome.com/a076d05399.js' crossorigin='anonymous'></script>
                            <a class="px-4 py-2 rounded-full border border-black text-black hover:bg-white hover:bg-opacity-10 transition" href="/pages/auth/logout.php">Déconnexion</a>
                        </li>
                        <?php else : ?>
                        <div class="flex items-center space-x-4 ml-8">
                            <a href="/pages/auth/login.php" class="px-4 py-2 rounded-full border border-black text-black hover:bg-white hover:bg-opacity-10 transition">Connexion</a>
                            <a href="/pages/auth/register.php" class="px-4 py-2 rounded-full bg-primary-600 hover:bg-primary-700 transition">Inscription</a>
                        </div>
                    <?php endif; ?>
                </nav>
                
                <!-- Mobile Menu Button -->
                <button id="mobile-menu-button" class="md:hidden text-white focus:outline-none">
                    <svg style="color: rgba(0, 0, 0, 0.8);" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
            </div>
            
            <!-- Mobile Menu -->
            <div id="mobile-menu" class="mobile-menu hidden md:hidden mt-4 pb-4">
                <div class="rounded-xl px-4 py-5 flex flex-col space-y-3 shadow-lg">
                    <!-- Liens classiques -->
                    <a href="/pages/search.php" class="block px-3 py-2 rounded-md text-white hover:bg-white hover:bg-opacity-10">Covoiturages</a>
                    <a href="/pages/contact.php" class="block px-3 py-2 rounded-md text-white hover:bg-white hover:bg-opacity-10">Contact</a>

                    <?php if (isset($_SESSION['auth']) && $_SESSION['auth'] === true) : ?>
                        <?php if (isset($_SESSION['is_admin']) && $_SESSION['is_admin'] === true) : ?>
                            <a class="block px-3 py-2 rounded-md text-white hover:bg-white hover:bg-opacity-10" href="dashboard/admin/dashboard_admin.php">Dashboard</a>
                        <?php endif; ?>

                        <?php $user = $_SESSION['loggedInUser']; ?>
                            <?php if (($user['user_id'] != 1)) : ?>
                                <a class="block px-3 py-2 rounded-md text-white hover:bg-white hover:bg-opacity-10" href="dashboard/users/dashboard_users.php">Dashboard</a>
                            <?php endif; ?>

                        <!-- Crédits -->
                        <a href="/pages/credits.php" class="relative text-black bg-white text-sm font-semibold px-4 py-2 rounded-full text-center w-full hover:bg-gray-100">
                            Crédits
                            <span class="absolute -top-2 -right-1 -mt-1 -mr-2 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white bg-red-600 rounded-full">
                                <?= $_SESSION['loggedInUser']['credits'] ?? '0' ?>
                            </span>
                        </a>

                        <!-- Déconnexion -->
                        <a href="dashboard/logout.php" class="text-black bg-white text-sm font-semibold px-4 py-2 rounded-full text-center w-full hover:bg-gray-100">
                            Déconnexion
                        </a>
                    <?php else : ?>
                        <!-- Connexion / Inscription si non connecté -->
                        <a href="/pages/login.php" class="block px-3 py-2 rounded-full text-white border border-white text-center hover:bg-white hover:text-black">Connexion</a>
                        <a href="/pages/register.php" class="block px-3 py-2 rounded-full bg-primary-600 text-white text-center hover:bg-primary-700">Inscription</a>
                    <?php endif; ?>
                </div>
            </div>

        </div>
    </header>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#f0fdf4',
                            100: '#dcfce7',
                            200: '#bbf7d0',
                            300: '#86efac',
                            400: '#4ade80',
                            500: '#22c55e',
                            600: '#16a34a',
                            700: '#15803d',
                            800: '#166534',
                            900: '#14532d',
                        },
                        dark: {
                            900: '#0a0a0a',
                            800: '#1e1e1e',
                            700: '#2d2d2d',
                        }
                    },
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    },
                }
            }
        }
    </script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8fafc;
            color: #1e293b;
        }
        
        .text-white {
            text-shadow: 0 1px 2px rgba(0,0,0,0.08) !important;
        }
        
        .glass-card {
            background: rgba(235, 235, 235, 0.8);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .hero-bg {
            background-image: linear-gradient(rgba(0, 0, 0, 0.4), rgba(0, 0, 0, 0.4)), url('https://images.unsplash.com/photo-1500382010550-9a8bfb545941?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2070&q=80');
            background-size: cover;
            background-position: center;
        }
        
        .about-img {
            background-image: url('https://images.unsplash.com/photo-1493238792000-8113da705763?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2070&q=80');
            background-size: cover;
            background-position: center;
            border-radius: 0 1rem 1rem 0;
        }
        
        .leaf-animation {
            animation: float 3s ease-in-out infinite;
        }
        
        .car-animation {
            animation: float 3s ease-in-out infinite 0.5s;
        }
        
        @keyframes float {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
            100% { transform: translateY(0px); }
        }
        
        .nav-link {
            position: relative;
        }
        
        .nav-link::after {
            content: '';
            position: absolute;
            width: 0;
            height: 2px;
            bottom: -2px;
            left: 0;
            background-color: #22c55e;
            transition: width 0.3s ease;
        }
        
        .nav-link:hover::after {
            width: 100%;
        }
        
        .benefit-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
        }
        
        .benefit-card {
            transition: all 0.3s ease;
        }
        
        .mobile-menu {
            transition: all 0.3s ease;
        }
        
        @media (max-width: 768px) {
            .about-img {
                border-radius: 1rem 1rem 0 0;
                height: 250px;
            }
        }

        #mobile-menu {
            background-color: rgba(0, 0, 0, 0.8); /* Fond semi-transparent noir */
            color: black;
            border-radius: 0.5rem;
        }
        
    </style>
<script>
    function updateCredits() {
        // Appeler le script PHP pour récupérer les crédits actualisés
        fetch('update_credits.php')
            .then(response => response.json())
            .then(data => {
                if (data.credits !== undefined) {
                    // Mettre à jour le badge des crédits dans le header
                    document.getElementById('credits-badge').textContent = data.credits;
                } else {
                    console.error('Erreur : ', data.error || 'Crédits indisponibles');
                }
            })
            .catch(error => {
                console.error('Erreur de mise à jour des crédits : ', error);
            });
    }

    // Mettre à jour les crédits toutes les 10 secondes ou après une action
    setInterval(updateCredits, 10000);  // Actualisation toutes les 10 secondes
    updateCredits();  // Mise à jour initiale

    // Active le menu déroulant sur mobile
    document.addEventListener("DOMContentLoaded", function () {
        const menuBtn = document.getElementById("mobile-menu-button");
        const menu = document.getElementById("mobile-menu");

        menuBtn.addEventListener("click", () => {
            menu.classList.toggle("hidden");
        });
    });
</script>

