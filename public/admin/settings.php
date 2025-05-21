<?php 
ob_start();

$pageTitle = 'Paramètres';

require_once realpath($_SERVER['DOCUMENT_ROOT'] . '/../class/LegalNotice.php');

use Ecoride\Class\LegalNotice;

// Crée une instance de la classe
$legal = new LegalNotice();

include('includes/header.php');

// Si le formulaire est soumis, on sauvegarde
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $legal->save($_POST['content']);
    header('Location: settings.php?success=1'); // Redirection pour éviter double post
    exit;
}

// On récupère le contenu si existant
$content = $legal->getRaw();

// Pour éxecuter la requête et récupérer les données
$query = "SELECT * FROM settings WHERE setting_id = 1 LIMIT 1";
$stmt = $db->prepare($query);
$stmt->execute();

$setting = [];
if ($stmt->rowCount() > 0) {
    $setting['data'] = $stmt->fetch(PDO::FETCH_ASSOC);
} else {
    $setting['data'] = [
        'setting_id' => 'insert',
        'slug' => 'insert',
        'title' => 'insert'
    ];
}
 
?>

<div class="row">
    <div class="col-md-12">
        <div class="card">

            <div class="card-header">
                <h4>Paramètre du site</h4>
            </div>
            
            <div class="card-body">

                <?= alertMessage(); ?>

                <form action="/admin/controller.php" method="POST">

                   <input type="hidden" name="setting_id" value="<?= $setting['data']['setting_id'] ?>" hidden />

                    <div class="mb-3">
                        <label>URL</label>
                        <input type="text" name="slug" value="<?= $setting['data']['slug'] ?>" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label>Titre</label>
                        <input type="text" name="title" value="<?= $setting['data']['title'] ?>" class="form-control">
                    </div>
                    
                    <div class="mb-3">
                        <label>Description</label>
                        <textarea type="text" name="description" class="form-control" rows="3"><?= $setting['data']['description'] ?? 'insert' ?></textarea>
                    </div>



                    <div class="row">
                        
                            <div class="col-md-6 mb-3">
                                <label>Email 1</label>
                                <input type="email" name="email1" value="<?= $setting['data']['email1'] ?? 'insert' ?>" class="form-control">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label>Email 2</label>
                                <input type="email" name="email2" value="<?= $setting['data']['email2'] ?? 'insert' ?>" class="form-control">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label>Téléphone 1</label>
                                <input type="text" name="phone1" value="<?= $setting['data']['phone1'] ?? 'insert' ?>" class="form-control">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label>Téléphone 2</label>
                                <input type="text" name="phone2" value="<?= $setting['data']['phone2'] ?? 'insert' ?>" class="form-control">
                            </div>

                            <div class="col-md-12 mb-3">
                                <label>Addresse</label>
                                <textarea name="address" value="<?= $setting['data']['address'] ?? 'insert' ?>" class="form-control" rows="3"></textarea>
                            </div>
                    </div>
                    <div class="mb-3">
                            <button type="submit" name="saveSetting" class="btn btn-primary">Enregistrer les paramètres</button>
                    </div>

                </form>
            </div>
        </div>
    </div>


    <div class="col-md-12" style="padding-top: 20px;">
        <div class="card">

            <div class="card-header">
                <h4>Mentions Légales</h4>

                     <?php 

                        if (isset($_SESSION['message'])) {

                            $type = $_SESSION['message_type'] ?? 'info'; // 'success', 'danger', 'warning', etc.
                            echo "<div class='alert alert-{$type} fade show mt-3' role='alert'>
                                        <h4 style=\"font-size: 15px; padding-top: 4px; color: --bs-alert-color: var(--bs-{$type}-text-emphasis);\">
                                            {$_SESSION['message']}
                                        </h4>
                                    </div>";
                            unset($_SESSION['message']);
                            unset($_SESSION['message_type']);
                        } 

                        if (isset($_GET['success'])) {
                            $_SESSION['message'] = 'Vos mentions légaux ont été mis à jour avec succès!';
                            $_SESSION['message_type'] = "success";

                            header('Location: /admin/settings.php');
                            exit;
                        }

                    ?>
            </div>
            
            <div class="card-body">
                
                <!-- Ici, on vas mettre le code pour la mention légale -->
                 <form method="POST">
                    <textarea name="content" class="
                    form-control
                    " rows="10" cols="50"><?= htmlspecialchars($content) ?></textarea>
                    <br>
                    <button type="submit" class="btn btn-primary">Enregistrer les mentions</button>
                </form>

            </div>
        </div>
    </div>
</div>

<?php include('includes/footer.php'); ob_end_flush(); ?>