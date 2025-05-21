<?php 

$pageTitle = 'Créer un utilisateur';
unset($user); # Permet de nettoyer et d'éviter les conflits

include __DIR__ . '/includes/header.php';

?>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h4>
                    Ajouter un utilisateur
                    <a href="/admin/users.php" class="btn btn-danger float-end">Retour</a>
                </h4>
            </div>
            <div class="card-body">
                
                <?= alertMessage(); ?>

                <form action="controller.php" method="POST" enctype="multipart/form-data">
                    <div class="row">

                    
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label>Nom</label>
                                <input type="text" name="name" class="form-control">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label>Prénom</label>
                                <input type="text" name="firstname" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label>Email</label>
                                <input type="email" name="email" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label>Mot de passe</label>
                                <input type="password" name="password" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label>Téléphone</label>
                                <input type="text" name="phone" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label>Adresse</label>
                                <input type="text" name="address" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label>Date de naissance</label>
                                <input type="date" name="date_of_birth" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label>Pseudonyme</label>
                                <input type="text" name="nickname" class="form-control">
                            </div>
                        </div>


                            <!-- Selection de Role -->
                            <div class="col-md-14">
                                <div class="mb-2">
                                    <label for="role">Sélection de rôle</label>
                                    <select name="role" class="form-select" required>
                                        <option value="">Sélection de rôle</option>
                                        <?php
                                        try {
                                            $query = "SELECT role_id, label FROM roles";
                                            $stmt = $db->query($query);
                                            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                                $selected = ($row['role_id'] == $user['role_id']) ? 'selected' : '';
                                                echo '<option value="' . htmlspecialchars($row['role_id']) . '" ' . $selected . '>' . htmlspecialchars($row['label']) . '</option>';
                                            }
                                        } catch (PDOException $e) {
                                            echo '<option value="">Error loading roles</option>';
                                        }
                                        ?>
                                    </select>
                                    
                                </div>
                                
                            </div>  

                                <!-- Photo de profil (en dehors de la grille) -->
                                <div class="mb-4">
                                    <label>Photo de profil</label>
                                    <input type="file" name="photo" accept="image/*" class="form-control">
                                    
                                    <?php if (isset($user) && !empty($user['photo'])): ?>
                                        <div class="mb-2" style="padding-top: 20px;">
                                            <img src="/images/profile_photos/<?= htmlspecialchars($user['photo']) ?>" alt="Photo actuelle" class="img-thumbnail" style="max-width: 150px;">
                                        </div>
                                    <?php endif; ?>
                                </div>

                                <!-- Bouton Save -->
                                <div class="col-md-3 d-flex align-items-end">
                                    <div class="mb-3 w-80">
                                        <button type="submit" name="saveUser" class="btn btn-primary w-80">Enregistrer</button>
                                    </div>
                                </div>

                </form>
            </div>
        </div>
    </div>
</div>


<?php include __DIR__ . '/includes/footer.php'; ?>