<?php 

$pageTitle = 'Accueil';
include('includes/header.php');

# Vérifier que l'utilisateur est bien connecté
if (!isset($_SESSION['auth']) || $_SESSION['auth'] !== true) {
    header('Location: ../../login.php');
    exit;
}

// var_dump($_SESSION);


?>

<?php alertMessage(); ?>

<h1>Bonjour <?= $_SESSION['loggedInUser']['firstname'] != '' ? $_SESSION['loggedInUser']['firstname'] : $_SESSION['loggedInUser']['nickname'] ?></h1>


<?php include('includes/footer.php'); ?>