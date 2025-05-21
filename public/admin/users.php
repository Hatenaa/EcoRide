<?php 
$pageTitle = 'Utilisateurs';

include __DIR__ . '/includes/header.php';

$users = getAll('users');

if (empty($users)) {
    echo "Aucun utilisateur trouvé.";
}

?>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h4>Liste des utilisateurs
                    <a href="/admin/create_users.php" class="btn btn-primary float-end">Ajouter un utilisateur</a>
                </h4>
            </div>
            <div class="card-body">
                <?= function_exists('alertMessage') ? alertMessage() : ''; ?>

                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Id</th>
                            <th>Nom</th>
                            <th>Email</th>
                            <th>Téléphone</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>

                        <?php if (!empty($users)) : ?>

                            <?php foreach ($users as $user) : ?>

                                <?php # var_dump($user['user_id'], $user['name'], $user['email'], $user['phone']); exit;?>
                                
                                <tr>
                                    <td><?= htmlspecialchars($user['user_id']); ?></td>
                                    <td><?= htmlspecialchars($user['name']); ?></td>
                                    <td><?= htmlspecialchars($user['email']); ?></td>
                                    <td><?= htmlspecialchars($user['phone']); ?></td>
                                    <td>
                                        <a href="/admin/edit_users.php?id=<?= htmlspecialchars($user['user_id']) ?>" class="btn btn-success btn-sm">Modifier</a>
                                        <?php if ($user['user_id'] != 1) : ?>
                                            <a href="#" class="btn btn-danger btn-sm mx-2" data-bs-toggle="modal" data-bs-target="#deleteModal<?= htmlspecialchars($user['user_id']); ?>">Supprimer</a>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                
                                <!-- Modal de suppression -->
                                <div class="modal fade" id="deleteModal<?= htmlspecialchars($user['user_id']); ?>" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Confirmer la suppression</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                Êtes-vous sûr de vouloir <strong>définitivement</strong> supprimer cet utilisateur ?
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                                <?php if ($user['user_id'] != 1) : ?>
                                                    <a href="/admin/delete_users.php?id=<?= htmlspecialchars($user['user_id']) ?>" class="btn btn-danger">Supprimer</a>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            <?php endforeach; ?>
                            
                        <?php else : ?>
                            <tr>
                                <td colspan="6" class="text-center text-muted">No Record Found</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>

            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>