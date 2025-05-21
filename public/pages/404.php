<?php include('../../includes/header.php'); ?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page Not Found | EcoRide</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #f0fff4 0%, #e6fffa 100%);
            min-height: 100vh;
        }
        .glass-card {
            background: rgba(255, 255, 255, 0.25);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border-radius: 20px;
            border: 1px solid rgba(255, 255, 255, 0.18);
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.1);
        }
        .btn-primary {
            background: linear-gradient(135deg, #22c55e 0%, #15803d 100%);
            box-shadow: 0 4px 15px rgba(34, 197, 94, 0.3);
            transition: all 0.3s ease;
        }
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(34, 197, 94, 0.4);
        }
        .sun-flare {
            position: absolute;
            width: 300px;
            height: 300px;
            background: radial-gradient(circle, rgba(255,255,255,0.6) 0%, rgba(255,255,255,0) 70%);
            border-radius: 50%;
            filter: blur(20px);
            z-index: -1;
        }
        .leaf {
            position: absolute;
            opacity: 0.6;
            filter: blur(1px);
            z-index: -1;
        }
        .error-number {
            text-shadow: 0 4px 20px rgba(34, 197, 94, 0.3);
            color: transparent;
            background: linear-gradient(135deg, #22c55e 0%, #15803d 100%);
            -webkit-background-clip: text;
            background-clip: text;
        }
        .blur-circle {
            position: absolute;
            border-radius: 50%;
            filter: blur(40px);
            opacity: 0.4;
            z-index: -1;
        }
    </style>
</head>

<body class="min-h-screen bg-[#f4fef7] flex flex-col items-center justify-center p-4 md:p-8">
    
    <!-- Background elements -->
    <div class="sun-flare top-1/4 -right-20"></div>
    <div class="sun-flare bottom-1/4 -left-20"></div>
    
    <div class="blur-circle bg-[#22c55e] w-80 h-80 top-1/3 left-1/4"></div>
    <div class="blur-circle bg-[#15803d] w-64 h-64 bottom-1/4 right-1/4"></div>

    <!-- Main content -->
    <div class="glass-card mt-[30px] p-12 max-w-2xl mx-4 text-center relative overflow-hidden">
        <h1 class="error-number text-9xl font-bold mb-4">404</h1>
        <h2 class="text-3xl font-semibold text-gray-800 mb-4 mt-[-25px]">Oups!</h2>
        <p class="text-gray-600 mb-8">La page que vous recherchez a peut-être été supprimée, son nom a été modifié ou elle est temporairement indisponible.</p>
        
        <div class="flex justify-center">
            <a href="/" class="btn-primary text-white font-medium rounded-full px-8 py-3">
                <i class="fa fa-arrow-left mr-[10px]" aria-hidden="true"></i>
                Retour à la page d'Accueil
            </a>
        </div>
        
        <div class="mt-8 text-gray-500 text-sm">
            <p>Besoin d'aide ? <a href="/nous-contacter" class="text-[#15803d] hover:underline">Contactez notre équipe d'assistance</a></p>
        </div>
        
        <!-- EcoRide logo -->
        <div class="mt-10">
            <div class="inline-flex items-center">
                <h2 class="text-xl font-bold text-slate-800 flex items-center">
                <i class="fas fa-leaf text-green-500 mr-2"></i>
                    EcoRide
                </h2>
            </div>
            <p class="text-xs text-gray-400 mt-1">Le covoiturage durable pour tous</p>
        </div>
    </div>

    <script>        
            // Animation pour les boutons
            const button = document.querySelector('.btn-primary');
            button.addEventListener('click', function(e) {
                e.preventDefault();
                
                // Create ripple element
                const ripple = document.createElement('span');
                ripple.className = 'absolute bg-white opacity-40 rounded-full animate-ripple';
                ripple.style.width = '10px';
                ripple.style.height = '10px';
                ripple.style.left = (e.offsetX - 5) + 'px';
                ripple.style.top = (e.offsetY - 5) + 'px';
                
                this.appendChild(ripple);
                
                // Remove ripple after animation
                setTimeout(() => {
                    ripple.remove();
                    window.location.href = '/';
                }, 700);
            });
        });
    </script>
</body>
</html>

<?php include('../../includes/footer.php'); ?>