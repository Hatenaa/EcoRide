<?php include('../../includes/header.php'); ?>

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
            min-height: 100vh;
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
    </style>
</head>
<body class="min-h-screen bg-[#f4fef7] flex flex-col items-center justify-center p-4 md:p-8">

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

    <div class="w-full max-w-4xl" style="padding-top: 50px;">
        
        <!-- Header -->
        <div class="text-center mb-8">
            <h1 class="text-4xl md:text-5xl font-bold text-gray-800 mb-2">Eco<span class="text-green-600">Ride</span></h1>
            <p class="text-gray-600 text-lg">Optimisez vos trajets et réduisez votre empreinte carbone</p>
        </div>
        
        <!-- Main search card -->
        <div class="glass-card p-6 md:p-8 w-full" >
            <h2 class="text-2xl font-semibold text-gray-800 mb-6">Rechercher un covoiturage écologique</h2>
            
            <form action="/pages/results.php" method="GET">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                    <!-- Departure location -->
                    <div>
                        <label for="departure" class="block text-sm font-medium text-gray-700 mb-1">Lieu de départ</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-map-marker-alt location-icon"></i>
                            </div>
                            <input type="text" name="departure" id="departure" class="input-glass w-full pl-10 pr-3 py-3 rounded-lg focus:outline-none focus:ring-1 focus:ring-green-500" placeholder="D'où est-ce que vous partez?">
                        </div>
                    </div>
                    
                    <!-- Arrival location -->
                    <div>
                        <label for="arrival" class="block text-sm font-medium text-gray-700 mb-1">Lieu d'arrivée</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-map-marker-alt location-icon"></i>
                            </div>
                            <input type="text" name="arrival" id="arrival" class="input-glass w-full pl-10 pr-3 py-3 rounded-lg focus:outline-none focus:ring-1 focus:ring-green-500" placeholder="Vers quelle destination?">
                        </div>
                    </div>
                    
                    <!-- Date -->
                    <div>
                        <label for="date" class="block text-sm font-medium text-gray-700 mb-1">Date</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-calendar-day date-icon"></i>
                            </div>
                            <input type="date" name="date" id="date" class="input-glass w-full pl-10 pr-3 py-3 rounded-lg focus:outline-none focus:ring-1 focus:ring-green-500">
                        </div>
                    </div>
                    
                    <!-- Passengers -->
                    <div>
                        <label for="passengers" class="block text-sm font-medium text-gray-700 mb-1">Passagers</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-leaf leaf-icon"></i>
                            </div>
                            <input type="number" name="passengers" id="passengers" min="1" max="8" class="input-glass w-full pl-10 pr-3 py-3 rounded-lg focus:outline-none focus:ring-1 focus:ring-green-500" placeholder="Combien de covoitureurs?">
                        </div>
                    </div>
                </div>
                
                <div class="flex justify-center">
                    <button type="submit" class="btn-eco text-white font-semibold py-3 px-8 rounded-full text-lg transition-all duration-300 transform hover:scale-105">
                        <i class="fas fa-search mr-2"></i> Rechercher un trajet
                    </button>
                </div>
            </form>
        </div>
        
        <!-- Eco benefits section -->
        <div class="glass-card mt-6 p-6">
            <div class="flex flex-wrap justify-center gap-4 text-center">
                <div class="flex items-center px-4 py-2 rounded-full bg-green-50 bg-opacity-50">
                    <i class="fas fa-leaf text-green-600 mr-2"></i>
                    <span class="text-sm font-medium text-gray-700">Réduction de l'empreinte carbone</span>
                </div>
                <div class="flex items-center px-4 py-2 rounded-full bg-green-50 bg-opacity-50">
                    <i class="fas fa-users text-green-600 mr-2"></i>
                    <span class="text-sm font-medium text-gray-700">Chaque trajet devient un moment d’entraide</span>
                </div>
                <div class="flex items-center px-4 py-2 rounded-full bg-green-50 bg-opacity-50">
                    <i class="fas fa-piggy-bank text-green-600 mr-2"></i>
                    <span class="text-sm font-medium text-gray-700">Partagez les frais, maximisez l’efficacité</span>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Set default date to today
        document.addEventListener('DOMContentLoaded', function() {
            const today = new Date().toISOString().split('T')[0];
            document.getElementById('date').value = today;
            document.getElementById('date').min = today;
            
            // Simple animation for the search card
            const card = document.querySelector('.glass-card');
            setTimeout(() => {
                card.classList.add('opacity-100', 'translate-y-0');
            }, 100);
        });
    </script>
</body>
</html>

<?php include('../../includes/footer.php'); ?>