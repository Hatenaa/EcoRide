<?php

require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../../class/LegalNotice.php';

include('../../includes/header.php');

use Ecoride\Class\LegalNotice;

$legal = new LegalNotice();

$pageTitle = 'Mentions Légales | EcoRide';

?>



<head>
    <script src="https://cdn.tailwindcss.com"></script>
    <meta name="robots" content="noindex">
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

        .btn-eco {
            background: linear-gradient(135deg, #22c55e 0%, #15803d 100%);
            box-shadow: 0 4px 15px rgba(34, 197, 94, 0.3);
            transition: all 0.3s ease;
        }
        
        .btn-eco:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(34, 197, 94, 0.4);
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

        .popular-badge {
            position: absolute;
            top: -12px;
            right: 20px;
            background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
            color: white;
            font-size: 0.75rem;
            font-weight: 600;
            padding: 0.25rem 1rem;
            border-radius: 9999px;
            box-shadow: 0 4px 6px rgba(59, 130, 246, 0.2);
        }

        .feature-item {
            transition: all 0.2s ease;
        }

        .feature-item:hover {
            transform: translateX(4px);
        }

        /* Custom styles for legal notice content */
        .legal-content h2 {
            font-size: 1.5rem;
            font-weight: 600;
            color: #1a1a1a;
            margin-top: 2rem;
            margin-bottom: 1rem;
        }
        
        .legal-content h3 {
            font-size: 1.25rem;
            font-weight: 500;
            color: #1a1a1a;
            margin-top: 1.5rem;
            margin-bottom: 0.75rem;
        }
        
        .legal-content p {
            margin-bottom: 1rem;
            line-height: 1.6;
            color: #4a5568;
        }
        
        .legal-content ul, .legal-content ol {
            margin-bottom: 1rem;
            padding-left: 1.5rem;
            line-height: 1.6;
            color: #4a5568;
        }
        
        .legal-content li {
            margin-bottom: 0.5rem;
        }
        
        .legal-content a {
            color: #22c55e;
            text-decoration: none;
            font-weight: 500;
        }
        
        .legal-content a:hover {
            text-decoration: underline;
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

<div class="container" style="max-width: 800px; margin: 40px auto;">
    <div class="glass-card" style="padding: 30px;">
            <h1 class="text-3xl md:text-4xl font-bold text-gray-800">Mentions Légales</h1>

            
            <div class="legal-content" style="line-height: 1.6;">
                <!-- Ton texte ou ton contenu actuel ici -->
                <div class="prose prose-lg prose-green max-w-none">
                    <?= $legal->getHtml(); ?>
                </div>
            </div>
            
            <div class="text-center" style="margin: 50px 0px 25px; ">
                <a href="/" class="btn-eco w-full text-white font-semibold py-3 px-8 rounded-full text-lg transition-all duration-300 transform hover:scale-105">
                    <i class="fas fa-arrow-left mr-2"></i> 
                    Retour
                </a>
            </div>

    </div>
</div>

</body>

<?php include('../../includes/footer.php'); ?>