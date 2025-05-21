<?php

require_once '../../config/function.php';

if (isset($_POST['saveUser'])) {

    $name = validate($_POST['name']);
    $firstname = validate($_POST['firstname']);
    $email = validate($_POST['email']);
    $password = validate($_POST['password']);
    $phone = validate($_POST['phone']);
    $address = validate($_POST['address']);
    $date_of_birth = validate($_POST['date_of_birth']);
    $nickname = validate($_POST['nickname']);
    $suspended = isset($_POST['suspended']) && $_POST['suspended'] == '1' ? 1 : 0;
    $role_id = isset($_POST['role']) ? intval($_POST['role']) : null;

    if (!empty($name) && !empty($firstname) && !empty($email) && !empty($password) && !empty($nickname) && !empty($role_id)) {

        $query = "INSERT INTO users (name, firstname, email, password, phone, address, date_of_birth, nickname, suspended) 
                  VALUES (:name, :firstname, :email, :password, :phone, :address, :date_of_birth, :nickname, :suspended)";
        
        $stmt = $db->prepare($query);

        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':firstname', $firstname);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $hashed_password);
        $stmt->bindParam(':phone', $phone);
        $stmt->bindParam(':address', $address);
        $stmt->bindParam(':date_of_birth', $date_of_birth);
        $stmt->bindParam(':nickname', $nickname);
        $stmt->bindParam(':suspended', $suspended);

        if ($stmt->execute()) {

            $user_id = $db->lastInsertId();

            if (!$user_id) {
                echo "Erreur critique : lastInsertId() vide.";
                exit;
            }

            if (isset($_FILES['photo']) && $_FILES['photo']['error'] === 0) {

                $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
                $fileType = mime_content_type($_FILES['photo']['tmp_name']);

                if (in_array($fileType, $allowedTypes)) {
                    $extension = pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION);
                    $newFileName = 'user_' . $user_id . '.' . $extension;

                    // Construction du chemin d'upload absolu sans realpath
                    $uploadDir = __DIR__ . '/../images/profile_photos/';
                    $uploadPath = $uploadDir . $newFileName;

                    // Vérifie que le dossier existe, sinon le crée
                    if (!is_dir($uploadDir)) {
                        mkdir($uploadDir, 0755, true);
                    }

                    if (move_uploaded_file($_FILES['photo']['tmp_name'], $uploadPath)) {
                        // Met à jour la colonne photo
                        $stmtPhoto = $db->prepare("UPDATE users SET photo = :photo WHERE user_id = :user_id");
                        $stmtPhoto->execute([
                            ':photo'   => $newFileName,
                            ':user_id' => $user_id,
                        ]);
                    }
                }
            }


            $roleQuery = "INSERT INTO user_as_role (user_id, role_id) VALUES (:user_id, :role_id)";
            $roleStmt = $db->prepare($roleQuery);
            $roleStmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
            $roleStmt->bindParam(':role_id', $role_id, PDO::PARAM_INT);

            if ($roleStmt->execute()) {
                $_SESSION['status'] = "Utilisateur ajouté avec succès.";
                header("Location: users.php"); 
                exit;
            } else {
                echo "Erreur lors de l'insertion du rôle.";
                exit;
            }

        } else {
            echo "Échec de l'insertion de l'utilisateur.";
        }

    } else {
        echo "Champs requis manquants.";
    }

} elseif (isset($_POST['loginUser'])) {
    
    // Fonctionnalité de connexion utilisateur
    $email = validate($_POST['email']);
    $password = validate($_POST['password']);

    $query = "SELECT 
                users.user_id, 
                users.name, 
                users.firstname, 
                users.email, 
                users.phone, 
                users.address, 
                users.date_of_birth, 
                users.photo, 
                users.nickname, 
                users.password, 
                users.suspended,
                roles.role_id, 
                roles.label AS role_label
              FROM 
                users
              INNER JOIN 
                user_as_role ON users.user_id = user_as_role.user_id
              INNER JOIN 
                roles ON user_as_role.role_id = roles.role_id
              WHERE 
                users.email = :email
              LIMIT 1";

    $stmt = $db->prepare($query);
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $stmt->execute();

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    

    if ($user && password_verify($password, $user['password'])) {

        if ($user['suspended'] == 1) {
            
            $_SESSION['message'] = "Votre compte a été suspendu. Veuillez contacter un administrateur.";
            $_SESSION['message_type'] = "danger";
            header("Location: login.php");
            exit;
        }
    
        // Authentification OK
        echo "Utilisateur authentifié : " . $user['name'] . ", rôle : " . $user['role_label'];

    }  else {
        // Échec de l'authentification
        echo "Email ou mot de passe incorrect.";
    }
}


/**/


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['updateUser'])) {

    // Nettoyage strict des entrées
    function clean($val) {
        return htmlspecialchars(trim($val), ENT_QUOTES, 'UTF-8');
    }

    $user_id = isset($_POST['user_id']) ? (int) $_POST['user_id'] : null;
    $name = clean($_POST['name'] ?? '');
    $firstname = clean($_POST['firstname'] ?? '');
    $email = clean($_POST['email'] ?? '');
    $phone = clean($_POST['phone'] ?? '');
    $address = clean($_POST['address'] ?? '');
    $dob = clean($_POST['date_of_birth'] ?? '');
    $nickname = clean($_POST['nickname'] ?? '');
    $role_id = isset($_POST['role']) ? (int) $_POST['role'] : null;
    $suspended = isset($_POST['suspended']) ? 1 : 0;

    // Mise à jour et traitement de la photo
        if (isset($_FILES['photo']) && $_FILES['photo']['error'] === 0) {

            $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
            $fileType = mime_content_type($_FILES['photo']['tmp_name']);

            if (in_array($fileType, $allowedTypes)) {

                $extension = pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION);
                $newFileName = 'user_' . $user_id . '.' . $extension;
                $uploadPath = __DIR__ . '/../../images/profile_photos/' . $newFileName;

                move_uploaded_file($_FILES['photo']['tmp_name'], $uploadPath);

                // update users table avec la photo
                $stmtPhoto = $db->prepare("UPDATE users SET photo = :photo WHERE user_id = :user_id");
                $stmtPhoto->execute([
                    ':photo'    => $newFileName,
                    ':user_id'  => $user_id
                ]);
            }
        }

    #var_dump($_POST, $_FILES); exit;

    if (!$user_id || !$name || !$firstname || !$email || !$nickname || ($user_id != 1 && !$role_id)) {
        echo "Champs obligatoires manquants.";
        exit;
    }


    try {
        $db->beginTransaction();

        // Mise à jour des données de l'utilisateur
        $stmt = $db->prepare("UPDATE users SET
                                name = :name,
                                firstname = :firstname,
                                email = :email,
                                phone = :phone,
                                address = :address,
                                date_of_birth = :dob,
                                nickname = :nickname,
                                suspended = :suspended
                            WHERE user_id = :user_id");

        $stmt->execute([
            ':name'      => $name,
            ':firstname' => $firstname,
            ':email'     => $email,
            ':phone'     => $phone,
            ':address'   => $address,
            ':dob'       => $dob,
            ':nickname'  => $nickname,
            ':suspended' => $suspended,
            ':user_id'   => $user_id
        ]);

        // Mise à jour du rôle dans la table user_as_role (uniquement quand l'utilisateur n'est pas l'admin)
        if ($user_id != 1) {
            $stmtRole = $db->prepare("UPDATE user_as_role SET role_id = :role_id WHERE user_id = :user_id");
            $stmtRole->execute([
                ':role_id' => $role_id,
                ':user_id' => $user_id
            ]);
        }

        $db->commit();

        
        $_SESSION['status'] = "Utilisateur mis à jour avec succès.";
        header("Location: users.php");
        exit;

    } catch (PDOException $e) {
        $db->rollBack();
        echo "Erreur BDD : " . $e->getMessage();
    }
} 


/*if (isset($_POST['updateUser'])) {

    // Validez et nettoyez les entrées utilisateur
    $name = validate($_POST['name']);
    $firstname = validate($_POST['firstname']);
    $email = validate($_POST['email']);
    $phone = validate($_POST['phone']);
    $address = validate($_POST['address']);
    $date_of_birth = validate($_POST['date_of_birth']);
    $nickname = validate($_POST['nickname']);
    $photo = NULL;
    $suspended = isset($_POST['suspended']) && $_POST['suspended'] === 'on' ? 1 : 0;
    #$suspended = isset($_POST['suspended']) && $_POST['suspended'] == '1' ? 1 : 0;
    $role_id = validate($_POST['role']); // Récupérer le rôle sélectionné
    $user_id = isset($_POST['user_id']) ? validate($_POST['user_id']) : null;   // Récupérer l'ID utilisateur

    if (!$user_id) {
        echo "Erreur : ID utilisateur manquant.";
        exit;
    }

    // Vérifiez que tous les champs obligatoires sont remplis
    if (!empty($name) && !empty($firstname) && !empty($email) && !empty($nickname) && !empty($role_id)) {

        $query = "UPDATE users 
                  SET name = :name, 
                      firstname = :firstname, 
                      email = :email, 
                      phone = :phone, 
                      address = :address, 
                      date_of_birth = :date_of_birth, 
                      nickname = :nickname, 
                      photo = :photo, 
                      suspended = :suspended
                  WHERE user_id = :user_id";

        $stmt = $db->prepare($query);
        
        $stmt->bindParam(':name', $name, PDO::PARAM_STR);
        $stmt->bindParam(':firstname', $firstname, PDO::PARAM_STR);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->bindParam(':phone', $phone, PDO::PARAM_STR);
        $stmt->bindParam(':address', $address, PDO::PARAM_STR);
        $stmt->bindParam(':date_of_birth', $date_of_birth, PDO::PARAM_STR);
        $stmt->bindParam(':nickname', $nickname, PDO::PARAM_STR);
        $stmt->bindParam(':photo', $photo, PDO::PARAM_NULL);
        $stmt->bindParam(':suspended', $suspended, PDO::PARAM_INT);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);


        // Exécuter la requête et vérifier le résultat
        if ($stmt->execute()) {

            // Mise à jour du rôle
            $updateRoleQuery = "UPDATE user_as_role SET role_id = :role_id WHERE user_id = :user_id";
            $updateRoleStmt = $db->prepare($updateRoleQuery);
            $updateRoleStmt->bindParam(':role_id', $role_id, PDO::PARAM_INT);
            $updateRoleStmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
            $updateRoleStmt->execute();

            echo "Utilisateur mis à jour avec succès.";
        } else {

            $errorInfo = $stmt->errorInfo();
            echo "Erreur lors de la mise à jour de l'utilisateur : " . $errorInfo[2];
        }
    } else {
        echo "Veuillez remplir tous les champs obligatoires.";
    }
} */

if (isset($_POST['saveSetting'])) {

    $email2 = validate($_POST['email2']);
    $phone1 = validate($_POST['phone1']);
    $phone2 = validate($_POST['phone2']);
    $address = validate($_POST['address']);
    $setting_id = validate($_POST['setting_id']);
    $title = validate($_POST['title']);
    $slug = validate($_POST['slug']);
    $description = validate($_POST['description']);
    $email1 = validate($_POST['email1']);

    if ($setting_id == 'insert') {

        $query = "INSERT INTO settings (title, slug, description, email1, email2, phone1, phone2, address) 
                  VALUES (:title, :slug, :description, :email1, :email2, :phone1, :phone2, :address)";

        $result = $db->prepare($query);
        
        $result->bindParam(':title', $title, PDO::PARAM_STR);
        $result->bindParam(':slug', $slug, PDO::PARAM_STR);
        $result->bindParam(':description', $description, PDO::PARAM_STR);
        $result->bindParam(':email1', $email1, PDO::PARAM_STR);
        $result->bindParam(':email2', $email2, PDO::PARAM_STR);
        $result->bindParam(':phone1', $phone1, PDO::PARAM_STR);
        $result->bindParam(':phone2', $phone2, PDO::PARAM_STR);
        $result->bindParam(':address', $address, PDO::PARAM_STR);

        // Exécuter la requête et vérifier le résultat
        if ($result->execute()) {
            redirect('/admin/settings.php', 'Mise à jour réussie du paramétrage');
        } else {
            $errorInfo = $result->errorInfo();
            redirect('/admin/settings.php', 'Quelque chose a mal tourné : ' . $errorInfo[2]);
        }
    } else {

        $query = "UPDATE settings 
                  SET title = :title, 
                      slug = :slug, 
                      description = :description, 
                      email1 = :email1, 
                      email2 = :email2, 
                      phone1 = :phone1, 
                      phone2 = :phone2, 
                      address = :address 
                  WHERE setting_id = :setting_id";

        $result = $db->prepare($query);
        
        $result->bindParam(':title', $title, PDO::PARAM_STR);
        $result->bindParam(':slug', $slug, PDO::PARAM_STR);
        $result->bindParam(':description', $description, PDO::PARAM_STR);
        $result->bindParam(':email1', $email1, PDO::PARAM_STR);
        $result->bindParam(':email2', $email2, PDO::PARAM_STR);
        $result->bindParam(':phone1', $phone1, PDO::PARAM_STR);
        $result->bindParam(':phone2', $phone2, PDO::PARAM_STR);
        $result->bindParam(':address', $address, PDO::PARAM_STR);
        $result->bindParam(':setting_id', $setting_id, PDO::PARAM_INT);

        // Exécuter la requête et vérifier le résultat
        if ($result->execute()) {
            redirect('/admin/settings.php', 'Mise à jour réussie du paramétrage');
        } else {
            $errorInfo = $result->errorInfo();
            redirect('/admin/settings.php', 'Quelque chose a mal tourné : ' . $errorInfo[2]);
        }
    }
} 

?>
