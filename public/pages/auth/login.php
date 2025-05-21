<?php 

$pageTitle = 'Connexion | EcoRide';

if(isset($_SESSION['auth'])) {
    redirect('/accueil', 'Vous êtes déjà connecté.');
}

// Inclure le fichier header.php
include('../../../includes/header.php');

?>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #e4f0e8 100%);
            margin: 0;
            align-items: center;
            position: relative;
        }

        .card-glass {
            background: rgba(255, 255, 255, 0.35);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-radius: 20px;
            border: 1px solid rgba(255, 255, 255, 0.3);
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.1);
            padding: 2.5rem;
            width: 100%;
            max-width: 400px;
            z-index: 10;
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
            background: rgba(255, 255, 255, 0.3);
            backdrop-filter: blur(5px);
            -webkit-backdrop-filter: blur(5px);
            border: 1px solid rgba(255, 255, 255, 0.4);
            border-radius: 12px;
            transition: all 0.3s ease;
        }

        .input-glass:focus {
            background: rgba(255, 255, 255, 0.5);
            border-color: rgba(46, 125, 50, 0.3);
            box-shadow: 0 0 0 3px rgba(34, 197, 94, 0.1);
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

        .ec0-bg {
            position: absolute;
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(5px);
            -webkit-backdrop-filter: blur(5px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 12px;
            z-index: -1;
            animation: float 6s ease-in-out infinite;
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

        .alert-message {
            background: rgba(255, 255, 255, 0.4);
            backdrop-filter: blur(5px);
            border: 1px solid rgba(255, 255, 255, 0.5);
            border-radius: 12px;
            padding: 0.75rem 1rem;
            margin-bottom: 1.5rem;
            color: #15803d;
            font-weight: 500;
            display: flex;
            align-items: center;
            justify-content: center; 
            margin-left: auto;  
            margin-right: auto;     
            width: fit-content;      
            max-width: 100%;          
        }


        @keyframes float {
            0% {
                transform: translateY(0) rotate(0deg);
            }
            50% {
                transform: translateY(-20px) rotate(5deg);
            }
            100% {
                transform: translateY(0) rotate(0deg);
            }
        }

        .link-eco {
            color: #15803d;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.2s ease;
        }

        .link-eco:hover {
            color: #22c55e;
            text-decoration: underline;
        }

        @keyframes fadeUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .card-glass {
            animation: fadeUp 0.6s ease-out;
        }

        .eco-badge {
            border-color: rgb(201, 248, 217) !important;
        }

    </style>
</head>


<body class="min-h-screen bg-[#f4fef7] flex flex-col items-center justify-center p-4 md:p-8">

    <!-- Petits éléments décoratifs -->
    <div class="ec0-bg" style="width: 100px; height: 100px; top: 15%; left: 10%; animation-delay: 0s;"></div>
    <div class="ec0-bg" style="width: 80px; height: 80px; top: 70%; left: 80%; animation-delay: 1s;"></div>
    <div class="ec0-bg" style="width: 60px; height: 60px; top: 30%; left: 85%; animation-delay: 2s;"></div>
    <div class="ec0-bg" style="width: 120px; height: 120px; top: 75%; left: 15%; animation-delay: 3s;"></div>
    
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



    <div class="px-4 pt-[30px] w-full">
        <div class="card-glass mx-auto">


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

            <h1 class="text-3xl font-bold text-center text-gray-800 mb-6">Connexion</h1>

            <form action="login_code.php" method="POST">
                <div class="mb-4">
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input type="email" name="email" id="email" class="input-glass w-full px-4 py-3 focus:outline-none" placeholder="votre@email.com" required>
                </div>

                <div class="mb-6">
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Mot de passe</label>
                    <input type="password" name="password" id="password" class="input-glass w-full px-4 py-3 focus:outline-none" placeholder="••••••••" required>
                </div>

                <div class="flex justify-center">
                    <button type="submit" name="loginBtn" class="btn-eco w-full text-white font-semibold py-3 px-8 rounded-full text-lg transition-all duration-300 transform hover:scale-105">
                        <i class="fa fa-sign-in" aria-hidden="true" style="padding-right: 5px;"></i> Connexion
                    </button>
                </div>

                <?php
                    /**
                     * Ici, on a un bouton pour le mdp oublié. On le gèrera + tard
                        *<div class="text-center text-sm text-gray-600">
                        *    <a href="#" class="link-eco">Mot de passe oublié ?</a>
                        *</div>
                    */
                ?>

            </form>

            <div class="mt-6 text-center text-sm text-gray-600">
                Vous n'avez pas encore de compte ? <a href="inscription" class="link-eco">Inscrivez-vous !</a>
            </div>
        </div>
    </div>


    <div class="container max-w-[450px] px-4 mt-8">
        <div class="eco-badge w-full p-6 bg-green-50 rounded-[20px] border border-green-100 shadow-md">
            <div class="flex items-center">
                <div>
                    <h3 class="text-lg font-semibold text-slate-800">Merci !</h3>
                    <p class="text-sm text-slate-600">
                        Chaque covoiturage que vous réalisez contribue à réduire les émissions de CO₂ d’environ 
                        <span class="font-medium text-green-700">30 kg tous les 100 km parcourus</span>.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Add some random movement to floating elements
        document.addEventListener('DOMContentLoaded', function() {
            const floaters = document.querySelectorAll('.ec0-bg');
            floaters.forEach((floater, index) => {
                // Randomize initial positions slightly
                const randomX = Math.random() * 20 - 10;
                const randomY = Math.random() * 20 - 10;
                floater.style.transform = `translate(${randomX}px, ${randomY}px)`;
                
                // Randomize animation duration
                const duration = 6 + Math.random() * 4;
                floater.style.animationDuration = `${duration}s`;
            });
        });
    </script>

</body>

<?php /*
    <div class="py-5">
        <div class="container">
            <div class="row justify-content-center">

                <div class="col-md-4">
                    <div class="card shadow-sm">
                        <!-- <div class="card-header">
                            <h4>Connexion</h4>
                        </div> -->
                        <div class="card-body">

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

                            <form action="login_code.php" method="POST">
                                <div class="mb-3">
                                    <label>Email</label>
                                    <input type="email" name="email" class="form-control">
                                </div>
                                <div class="mb-3">
                                    <label>Mot de passe</label>
                                    <input type="password" name="password" class="form-control">
                                </div>
                                <div class="mb-3">
                                    <button type="submit" name="loginBtn" class="btn btn-dark w-100">Connexion</button>
                                </div>
                            </form>
                            <p class="text-center">Vous n'avez pas encore de compte ? <a href="register.php" 
                            class="link-success link-offset-2 link-underline-opacity-25 link-underline-opacity-100-hover">Inscrivez-vous</a>!</p>
                        </div>
                    </div>
                </div>
                
            </div>
        </div>
    </div>
    */

// Inclure le fichier footer.php
include('../../../includes/footer.php');

?>