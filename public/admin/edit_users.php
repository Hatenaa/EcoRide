<?php 
$pageTitle = 'Modifier un utilisateur'; 
include __DIR__ . '/includes/header.php';

?>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h4>
                    Modifier un utilisateur
                    <a href="/admin/users.php" class="btn btn-danger float-end">Retour</a>
                </h4>
            </div>
            <div class="card-body">

                <?= alertMessage(); ?>

                <?php

                    $user_id = intval($_GET['id']);
                    $query = "SELECT u.*, r.role_id 
                              FROM users u
                              LEFT JOIN user_as_role r ON u.user_id = r.user_id
                              WHERE u.user_id = :user_id
                              LIMIT 1";
                    
                    $stmt = $db->prepare($query);
                    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
                    $stmt->execute();
                    $user_edit = $stmt->fetch(PDO::FETCH_ASSOC);
                    

                    if ($user_edit) {

                        #$user = $users['data'];
                ?>

                <form action="/admin/controller.php" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="user_id" value="<?= $user_edit['user_id'] ?>">
                    <input type="hidden" name="updateUser" value="1">

                    <div class="row">
                        <!-- Nom & Prénom -->
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label>Nom</label>
                                <input type="text" name="name" value="<?= htmlspecialchars($user_edit['name']) ?>" required class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label>Prénom</label>
                                <input type="text" name="firstname" value="<?= htmlspecialchars($user_edit['firstname']) ?>" required class="form-control">
                            </div>
                        </div>

                        <!-- Email & Téléphone -->
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label>Email</label>
                                <input type="email" name="email" value="<?= htmlspecialchars($user_edit['email']) ?>" required class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label>Téléphone</label>
                                <input type="text" name="phone" value="<?= htmlspecialchars($user_edit['phone']) ?>" class="form-control">
                            </div>
                        </div>

                        <!-- Adresse & Date de naissance -->
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label>Adresse</label>
                                <input type="text" name="address" value="<?= htmlspecialchars($user_edit['address']) ?>" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label>Date de naissance</label>
                                <input type="date" name="date_of_birth" value="<?= htmlspecialchars($user_edit['date_of_birth']) ?>" class="form-control">
                            </div>
                        </div>

                        <!-- Pseudo -->
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label>Pseudonyme</label>
                                <input type="text" name="nickname" value="<?= htmlspecialchars($user_edit['nickname']) ?>" class="form-control">
                            </div>
                        </div>

                        <!-- Rôle (si pas admin) + Suspension -->
                        <?php if ($user_edit['user_id'] != 1) : ?>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label>Rôle</label>
                                <select name="role" class="form-select" required>
                                    <option value="">Sélectionner un rôle</option>
                                    <?php
                                        $query = "SELECT role_id, label FROM roles";
                                        $stmt = $db->query($query);
                                        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                            $selected = ($row['role_id'] == $user_edit['role_id']) ? 'selected' : '';
                                            echo '<option value="' . htmlspecialchars($row['role_id']) . '" ' . $selected . '>' . htmlspecialchars($row['label']) . '</option>';
                                        }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <?php endif; ?>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="suspended" class="form-label fw-bold">Suspendre le compte</label><br>
                                <input type="checkbox" name="suspended" id="suspended" <?= $user_edit['suspended'] ? 'checked' : '' ?>>
                            </div>
                        </div>
                    </div>

                    <!-- Photo de profil (en dehors de la grille) -->
                    <div class="mb-4">
                        <label>Photo de profil</label>
                        <input type="file" name="photo" accept="image/*" class="form-control">
                        
                        <?php if (!empty($user_edit['photo']) && $user_id != 1): ?>
                            <div class="mb-2" style="padding-top: 20px;">
                                <img src="/images/profile_photos/<?= htmlspecialchars($user_edit['photo']); ?>" alt="Photo actuelle" class="img-thumbnail" style="max-width: 150px;">
                            </div>
                        <?php elseif ($user_id == 1): ?>
                            <div class="mb-2" style="padding-top: 20px;">
                                <img src="/images/profile_photos/user_1.jpg" alt="Photo actuelle" class="img-thumbnail" style="max-width: 150px;">
                            </div>

                            
                        <?php endif; ?>
                    </div>

                    <!-- Bouton -->
                    <div class="text-end">
                        <button type="submit" class="btn btn-primary">Enregistrer</button>
                    </div>
                </form>


                <?php } else { echo 'Erreur : utilisateur non trouvé.'; } ?>

            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>