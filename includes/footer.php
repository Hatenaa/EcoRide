    <script src="asstets/js/jquery-3.7.1.min.js"></script>
    <script src="asstets/js/bootstrap.bundle.min.js"></script>



</html>

<?php 

// Requête pour récupérer les emails depuis la table settings
try {

  // Utilisation de l'objet $db défini dans connect.php
  $stmt = $db->query('SELECT email1, email2 FROM settings LIMIT 1');
  $settings = $stmt->fetch();

  if ($settings) {
      $email1 = htmlspecialchars($settings['email1']);
      $email2 = htmlspecialchars($settings['email2']);
  } else {
      $email1 = 'Aucun email trouvé';
      $email2 = '';
  }
} catch (PDOException $e) {
  // Gestion des erreurs
  echo 'Erreur : ' . $e->getMessage();
  exit;
}

?>

<script src="https://cdn.tailwindcss.com"></script>

  <footer class="bg-white border-t border-slate-100 mt-12 py-8" style="width: 100%; border-radius: 20px;">
      <div class="container mx-auto px-4 max-w-6xl">
        <div class="flex flex-col md:flex-row justify-between items-center">

          <div class="mb-4 md:mb-0">
            <h2 class="text-xl font-bold text-slate-800 flex items-center">
              <i class="fas fa-leaf text-green-500 mr-2"></i>
                EcoRide
            </h2>
            <p class="text-sm text-slate-500 mt-1">Le covoiturage durable pour tous</p>
            
          </div>

          <div class="flex space-x-6">
             <a href="/pages/legal.php" class="text-sm text-slate-600 hover:text-slate-800">Mentions Légales</a>
              <?php if (!empty($email1)): ?>
                <span class="text-sm text-slate-600"><?= $email1 ?></span>
              <?php endif; ?>
              <?php if (!empty($email2)): ?>
                <span class="text-sm text-slate-600"><?= $email2 ?></span>
              <?php endif; ?>
          </div>
          <div class="mt-6 text-center">
              <p class="text-xs text-slate-400">© 2025 EcoRide. Tous droits réservés.</p>
          </div> 
        </div>
      </div>
  </footer>


  <?php /* 
  <footer class="d-flex flex-wrap justify-content-between align-items-center py-3 my-4 border-top">
    <p class="col-md-4 mb-0 text-muted">© 2025 EcoRide</p>

    <a href="/" class="col-md-4 d-flex align-items-center justify-content-center mb-3 mb-md-0 me-md-auto link-dark text-decoration-none">
      <svg class="bi me-2" width="40" height="32"><use xlink:href="#bootstrap"></use></svg>
    </a>

    <ul class="nav col-md-4 justify-content-end">
      <li class="nav-item"><a href="legal_information.php" class="nav-link px-2 text-muted"></a></li>
      <?php if (!empty($email1)): ?>
        <li class="nav-item"><span class="nav-link px-2 text-muted"></span></li>
      <?php endif; ?>
      <?php if (!empty($email2)): ?>
        <li class="nav-item"><span class="nav-link px-2 text-muted"><?= $email2 ?></span></li>
      <?php endif; ?>
    </ul>
  </footer>
  */ ?>



















