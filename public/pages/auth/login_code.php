<?php

require_once realpath($_SERVER['DOCUMENT_ROOT'] . '/../config/function.php');

if(isset($_POST['loginBtn'])) {

    $emailInput = validate($_POST['email']);
    $passwordInput = validate($_POST['password']);
    $email = filter_var($emailInput, FILTER_SANITIZE_EMAIL);
    $password = filter_var($passwordInput, FILTER_SANITIZE_FULL_SPECIAL_CHARS); // Utilisation de FILTER_SANITIZE_FULL_SPECIAL_CHARS pour le mot de passe

    if ($email != '' && $password != '') {

        $query =  "SELECT * FROM `users` WHERE `email` = :email AND `password` = :password LIMIT 1"; 
        
        $stmt = $db->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $password);
        $stmt->execute();

        if ($stmt->rowCount() === 1) {

            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            // Ici, on écupére les crédits depuis users.json
            $credits = getUserCredits($user['user_id']);

            // Vérifiez si l'utilisateur est l'administrateur

            if ($user['user_id'] == 1) {

                $_SESSION['auth'] = true;
                $_SESSION['is_admin'] = true; // Indique que l'utilisateur est un administrateur
                $_SESSION['loggedInUser'] = [
                    'email' => $user['email'],
                    'user_id' => $user['user_id'],
                    'name' => $user['name'],
                    'firstname' => $user['firstname'],
                    'nickname' => $user['nickname'], 
                ];

                // Rediriger vers la page d'administration

                redirect('/admin/dashboard_admin.php', 'Bonjour ' . $user['firstname'] . ', vous êtes bien connecté en temps qu\'administrateur.');
                exit;


            /* 
                On se réfère au user_id pour l'administrateur, car son compte est unique 
                (étant donné qu'il est le seul à pouvoir créer des comptes utilisateurs et que son pouvoir est absolu).
                Quant aux autres utilisateurs, il va plutôt falloir se référer à leur rôle.
            */

            } elseif (($user['user_id'] != 1)) { 

                // Vérifie si l'utilisateur est suspendu
                if ($user['suspended'] == 1) {
                    $_SESSION['message'] = "Votre compte a été suspendu.";
                    $_SESSION['message_type'] = "danger";
                    header("Location: /pages/login.php");
                    exit;
                }

                // Si l'utilisateur n'est pas l'administrateur, alors on l'affiche comme étant "Connecté".
                $_SESSION['auth'] = true;
                $_SESSION['is_admin'] = false; // Indique que l'utilisateur n'est pas un administrateur
                $_SESSION['loggedInUser'] = [
                    'email' => $user['email'],
                    'user_id' => $user['user_id'],
                    'name' => $user['name'],
                    'firstname' => $user['firstname'],
                    'nickname' => $user['nickname'], 
                    'credits' => $credits
                ];
                
                redirect('/pages/home.php', 'Bonjour ' . $user['firstname'] . ', vous êtes maintenant connecté!');
            }

        } else {

            // Vérifiez si $user est false
            
                $_SESSION['status'] = 'Email ou mot de passe invalide.';
                header('Location: /pages/login.php');
                exit;


            // Affichez le user_id pour le débogage
            // var_dump($user['user_id']);


            // Si les informations de connexion sont incorrectes, afficher un message d'erreur
            /* $_SESSION['status'] = 'Email ou mot de passe invalide.'; 
            header('Location: login.php');
            exit;
            */
        }

    } else {
        // Si les champs sont vides, afficher un message d'erreur
        $_SESSION['status'] = 'Veuillez remplir tous les champs.';
        header('Location: /pages/login.php');
        exit;
    }
}

?>