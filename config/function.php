<?php

// Si on a pas de session, on en créer une nouvelle
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
} 

// On se connecte à notre BDD
require_once __DIR__ . '/../config/connect.php';

function validate($inputData) {

    // Si la donnée est null, retourne une chaîne vide directement
    if (is_null($inputData)) {
        return '';
    }

    // Supprime les espaces avant et après
    $inputData = trim($inputData);

    // Supprime les balises HTML et PHP
    $inputData = strip_tags($inputData);

    // Convertit les caractères spéciaux en entités HTML
    $inputData = htmlspecialchars($inputData, ENT_QUOTES, 'UTF-8');

    return $inputData;
}


function logoutSession() {

    unset($_SESSION['auth']);
    unset($_SESSION['loggedInUserRole']);
    unset($_SESSION['loggedInUser']);

}


function redirect($url, $status) {

    $_SESSION['status'] = $status;
    header('Location: ' . $url);
    exit(0);

}

function alertMessage() {

    if (isset($_SESSION['status'])) {

        // Vérifier si le message contient "vous n'avez pas assez de crédits"
        $message = $_SESSION['status'];
        $alertClass = (stripos($message, "vous n'avez pas assez de crédits") !== false) 
            ? 'alert-warning' 
            : 'alert-success';

        // Afficher l'alerte avec la classe appropriée
        echo '<div class="alert ' . $alertClass . '">
            <h4 style="font-size: 18px; text-align: center; color: --bs-alert-color: var(--bs-success-text-emphasis);">' . $message . '</h4>
        </div>';

        // Supprimer le message de la session après l'affichage
        unset($_SESSION['status']);
    }
}

function warningMessage($errors) {
    
    // Vérification des erreurs en session
    if (isset($_SESSION['status'])) {
        echo '<div class="alert alert-warning">
            <h4 style="font-size: 18px;text-align: center;margin-top: 5px;">' . $_SESSION['status'] . '</h4>
        </div>';
        unset($_SESSION['status']);
    }

    // Affichage des erreurs courantes
    if (!empty($errors)) {
        echo '<div class="alert alert-danger" >';
        echo '<ul style="margin-bottom: 5px;">';
        foreach ($errors as $error) {
            echo '<li>' . htmlspecialchars($error) . '</li>';
        }
        echo '</ul>';
        echo '</div>';
    }
}

function checkParamId($paramType) {

    // Vérifie si le paramètre existe
    if (isset($_GET[$paramType])) { 

        // Vérifie si la valeur est non nulle
        if ($_GET[$paramType] != null) { 

            // Retourne la valeur
            return $_GET[$paramType]; 
        } else {

            // Retourne un message si la valeur est vide
            return 'No Id Found'; 
        }
    } else {

        // Retourne un message si la clé n'existe pas
        return 'No Id Given'; 
    }
}


function getAll($tableName) {

    global $db;

    // Valider le nom de la table pour éviter les injections SQL
    $table = validate($tableName);

    // Pour préparer et exécuter la requête
    $query = "SELECT * FROM $table";
    $stmt = $db->query($query);

    // Récupérer tous les résultats sous forme de tableau associatif
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getById($table, $id) {

    global $db;
    
    $table = validate($table);

    // Identifier la clé primaire attendue (ex: user_id, carpooling_id...)
    $primaryKey = $table === 'users' ? 'user_id' : 'id';

    $query = "SELECT * FROM $table WHERE $primaryKey = :id LIMIT 1";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);

    try {
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $data = $stmt->fetch(PDO::FETCH_ASSOC);
            return [
                'status' => 200,
                'data' => $data,
            ];
        } else {
            return [
                'status' => 404,
                'message' => 'Record not found',
            ];
        }

    } catch (PDOException $e) {
        return [
            'status' => 500,
            'message' => 'Database error: ' . $e->getMessage(),
        ];
    }
}


function deleteQuery($tableName, $id) {
    
    global $db;

    // Validation des entrées
    $table = validate($tableName);
    $id = validate($id);

    try {
        
        // Préparer la requête de suppression
        $query = "DELETE FROM $table WHERE $id = :id LIMIT 1";
        $stmt = $db->prepare($query);

        // Associer le paramètre
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        // Exécuter la requête
        $stmt->execute();

        // Retourner true si une ligne a été supprimée
        return $stmt->rowCount() > 0;

    } catch (PDOException $e) {
        // Afficher un message d'erreur (pour le debug uniquement)
        error_log("Error in deleteQuery: " . $e->getMessage());
        return false;
    }
}


// Fonction pour déconnecter l'utilisateur et rediriger avec un message

function logoutAndRedirect($message) {
    
    logoutSession();
    redirect('connexion', $message);
    exit;
}


// Charger les utilisateurs depuis le fichier XML
function loadUsersFromXML($filename) {
    if (!file_exists($filename)) {
        $xml = new SimpleXMLElement('<users></users>');
        $xml->asXML($filename);
    }

    // Gestion des erreurs lors du chargement
    libxml_use_internal_errors(true);
    $xml = simplexml_load_file($filename);

    if ($xml === false) {
        // Si le fichier est corrompu, réinitialiser le fichier
        $xml = new SimpleXMLElement('<users></users>');
        $xml->asXML($filename);
    }

    return $xml;
}


// Sauvegarder les utilisateurs dans le fichier XML
function saveUsersToXML($filename, $xml) {
    $xml->asXML($filename);
}


// Fonction pour lire les utilisateurs depuis le fichier JSON
/*function readUsersFromJson() {

    // Déterminer le chemin absolu du fichier JSON
    $filePath = $_SERVER['DOCUMENT_ROOT'] . '/users.json';

    // Vérifier si le fichier existe
    if (!file_exists($filePath)) {
        return ['users' => []];
    }

    // Lire le contenu du fichier JSON
    $jsonData = file_get_contents($filePath);

    // Retourner les données décodées
    return json_decode($jsonData, true);
}*/

function readUsersFromJson() {

    $filePath = __DIR__ . '/../users.json'; 

    // Test : Vérifier si le fichier existe
    if (!file_exists($filePath)) {
        die("Erreur : Le fichier JSON n'existe pas à ce chemin -> " . $filePath);
    }

    $jsonData = file_get_contents($filePath);

    // Test : Vérifier si les données JSON sont valides
    if (empty($jsonData)) {
        die("Erreur : Le fichier JSON est vide.");
    }

    $decodedData = json_decode($jsonData, true);

    // Test : Vérifier si le JSON est bien décodé
    if (json_last_error() !== JSON_ERROR_NONE) {
        die("Erreur de décodage JSON : " . json_last_error_msg());
    }

    return $decodedData;
}



function saveUsersToJson($data) {

    // Déterminer le chemin absolu du fichier JSON
    $filePath = __DIR__ . '/../users.json';

    // Convertir les données en JSON
    $jsonData = json_encode($data, JSON_PRETTY_PRINT);

    // Écrire les données dans le fichier JSON
    file_put_contents($filePath, $jsonData);
}


// Fonction pour récupérer un utilisateur par son ID
function getUserById($userId) {
    $usersData = readUsersFromJson();
    foreach ($usersData['users'] as $user) {
        if ($user['user_id'] == $userId) {
            return $user;
        }
    }
    return null;
}

// Fonction pour mettre à jour les crédits d'un utilisateur
function updateCredits($userId, $amount) {

    $usersData = readUsersFromJson();
    foreach ($usersData['users'] as &$user) {
        if ($user['user_id'] == $userId) {

            // Vérifier avant de modifier
            if ($user['credits'] + $amount < 0) {
                return "<strong>Erreur</strong> : Vous n'avez pas assez de crédits.";
            }

            // Modifier les crédits seulement si c'est possible
            $user['credits'] += $amount;
            saveUsersToJson($usersData);
            
            return "Crédits mis à jour avec succès. Nouveaux crédits : " . $user['credits'];
        }
    }
    
    // Si l'utilisateur n'est pas trouvé, essayer de l'ajouter
    if (addUserToJsonFromSession()) {
        return updateCredits($userId, $amount);  // Relancer après l'ajout
    }

    return "Erreur : impossible d'ajouter l'utilisateur.";
}


// Fonction pour ajouter un utilisateur dans `users.json` à partir des données de session
function addUserToJsonFromSession() {
    if (!isset($_SESSION['loggedInUser'])) {
        return false;
    }

    $userData = [
        'user_id' => $_SESSION['loggedInUser']['user_id'],
        'email' => $_SESSION['loggedInUser']['email'],
        'name' => $_SESSION['loggedInUser']['name'],
        'firstname' => $_SESSION['loggedInUser']['firstname'],
        'nickname' => $_SESSION['loggedInUser']['nickname'],
        'credits' => 20  // Crédits par défaut
    ];

    $usersData = readUsersFromJson();
    $usersData['users'][] = $userData;
    saveUsersToJson($usersData);

    return true;
}

// Ajouter des crédits
function addCreditsById($userId, $amount) {
    $usersData = readUsersFromJson();
    foreach ($usersData['users'] as &$user) {
        if ($user['user_id'] == $userId) {
            $user['credits'] += $amount;
            saveUsersToJson($usersData);
            return "Crédits ajoutés avec succès.";
        }
    }
    return "Utilisateur non trouvé.";
}

// Retirer des crédits
function removeCreditsById($userId, $amount) {
    $usersData = readUsersFromJson();
    foreach ($usersData['users'] as &$user) {
        if ($user['user_id'] == $userId) {
            if ($user['credits'] >= $amount) {
                $user['credits'] -= $amount;
                saveUsersToJson($usersData);
                return "Crédits retirés avec succès.";
            } else {
                return "Pas assez de crédits.";
            }
        }
    }
    return "Utilisateur non trouvé.";
}

// Fonction pour consulter les crédits d'un utilisateur
function getCreditsById($userId) {
    $user = getUserById($userId);
    if ($user) {
        return "L'utilisateur {$user['firstname']} {$user['name']} a {$user['credits']} crédits.";
    }
    return "Utilisateur non trouvé.";
}


function getUserCredits($userId) {
    $usersData = readUsersFromJson();
    foreach ($usersData['users'] as $user) {
        if ($user['user_id'] == $userId) {
            return $user['credits'] ?? 0;
        }
    }
    return 0;  // Retourne 0 si aucun crédit trouvé
}


function addUserToJson($userData, $userIdFromDb) {
    $usersData = readUsersFromJson();

    // Utiliser l'ID utilisateur de la base de données
    $userData['user_id'] = $userIdFromDb;

    // Ajouter les crédits par défaut si non définis
    if (!isset($userData['credits'])) {
        $userData['credits'] = 20;
    }

    // Ajouter l'utilisateur au tableau JSON
    $usersData['users'][] = $userData;

    // Enregistrer les données mises à jour dans `users.json`
    saveUsersToJson($usersData);

    return true;
}


function updateUserCreditsInJson($userId, $newCredits) {
    $usersData = readUsersFromJson();

    foreach ($usersData['users'] as &$user) {
        if ($user['user_id'] == $userId) {
            $user['credits'] = $newCredits;
            saveUsersToJson($usersData);
            return true;
        }
    }

    return false;
}

/**
 * Met à jour les statistiques journalières dans stats.json
 * 
 * @param string $action Type d'action : 'rides_created', 'rides_completed', 'rides_viewed'
 * @return bool true si l'action a été enregistrée, false sinon
 */

function updateDailyStats($action) {
    $filePath = __DIR__ . '/../dashboard/admin/stats.json';
    $today = date('Y-m-d');

    // Actions autorisées
    $allowedActions = ['rides_created', 'rides_completed', 'rides_viewed'];

    // Si l'action n'est pas reconnue, on arrête
    if (!in_array($action, $allowedActions)) {
        return false;
    }

    // Créer un fichier vide si nécessaire
    if (!file_exists($filePath)) {
        file_put_contents($filePath, json_encode([], JSON_PRETTY_PRINT));
    }

    // Lecture des données existantes
    $stats = json_decode(file_get_contents($filePath), true);

    // Si la date n'existe pas encore, initialiser la structure
    if (!isset($stats[$today])) {
        $stats[$today] = [
            'rides_created' => 0,
            'rides_completed' => 0,
            'rides_viewed' => 0
        ];
    }

    // Incrémentation de l'action concernée
    $stats[$today][$action]++;

    // Enregistrement des données mises à jour
    file_put_contents($filePath, json_encode($stats, JSON_PRETTY_PRINT));
    return true;
}


// Enregistrer un nouvel utilisateur
function registerUserToXML($filename, $nickname, $password, $email) {
    $xml = loadUsersFromXML($filename);

    // Vérifier si le pseudo ou l'email existe déjà
    foreach ($xml->user as $user) {
        if ((string) $user->username === $nickname) {
            throw new Exception("Le pseudo est déjà pris.");
        }
        if ((string) $user->email === $email) {
            throw new Exception("L'adresse e-mail est déjà utilisée.");
        }
    }

    // Générer un nouvel ID utilisateur
    $newId = (count($xml->user) > 0) ? ((int) $xml->user[count($xml->user) - 1]['id'] + 1) : 1;

    // Ajouter le nouvel utilisateur
    $newUser = $xml->addChild('user');
    $newUser->addAttribute('id', $newId);
    $newUser->addChild('username', $nickname);
    $newUser->addChild('password', password_hash($password, PASSWORD_DEFAULT));
    $newUser->addChild('email', $email);
    $newUser->addChild('credits', 20);  // Crédit initial

    // Sauvegarder dans le fichier XML
    saveUsersToXML($filename, $xml);

    return $newId;
}

// Dépenser des crédits
function spendCredits($filename, $userId, $amount) {
    $xml = loadUsersFromXML($filename);

    foreach ($xml->user as $user) {
        if ((int) $user['id'] === $userId) {
            if ((int) $user->credits < $amount) {
                throw new Exception("Crédits insuffisants.");
            }

            // Mise à jour des crédits
            $user->credits = (int) $user->credits - $amount;

            // Sauvegarder les modifications
            saveUsersToXML($filename, $xml);

            return true;
        }
    }

    throw new Exception("Utilisateur non trouvé.");
}

// Récupérer les crédits d'un utilisateur
function getUserCreditsFromXML($filename, $userId) {
    $xml = loadUsersFromXML($filename);

    foreach ($xml->user as $user) {
        if ((int) $user['id'] === $userId) {
            return (int) $user->credits;
        }
    }

    throw new Exception("Utilisateur non trouvé.");
}

// Permet de charger les utilisateurs
function loadUsers($filename) {
    if (!file_exists($filename)) {
        return [];
    }

    $users = [];
    $lines = file($filename, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

    foreach ($lines as $line) {
        list($id, $username, $passwordHash, $email, $credits) = explode('|', $line);
        $users[$id] = [
            'id' => $id,
            'username' => $username,
            'password' => $passwordHash,
            'email' => $email,
            'credits' => (int)$credits
        ];
    }

    return $users;
}

