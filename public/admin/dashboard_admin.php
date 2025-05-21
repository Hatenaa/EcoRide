<?php 
$pageTitle = 'Espace Administrateur';

$statsPath = __DIR__ . '/../admin/stats.json';

include __DIR__ . '/includes/header.php';

$statsContent = file_get_contents($statsPath);
$stats = json_decode($statsContent, true);

// On prépare un tableau au format attendu pour le JS
$data = [];

foreach ($stats as $date => $values) {
    $data[] = [
        'date' => date('d/m', strtotime($date)),
        'rides' => $values['rides_created'] ?? 0,
        'credits' => ($values['rides_completed'] ?? 0) * 3 // 1 trajet terminé = 3 crédits gagnés
    ];
}

$today = date('Y-m-d');
$ridesToday = 0;
$creditsToday = 0;

$statsPath = realpath('stats.json');

if (file_exists($statsPath)) {
    $statsContent = file_get_contents($statsPath);
    $stats = json_decode($statsContent, true);

    if (isset($stats[$today])) {
        $ridesToday = $stats[$today]['rides_created'] ?? 0;
        $creditsToday = ($stats[$today]['rides_completed'] ?? 0) * 3;
    }
}

?>

<head>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<script>
  const labels = <?= json_encode(array_column($data, 'date')) ?>;
  const ridesData = <?= json_encode(array_column($data, 'rides')) ?>;
  const creditsData = <?= json_encode(array_column($data, 'credits')) ?>;
</script>

<style>
  .chart-canvas {
    height: 300px !important;
    max-height: 300px !important;
  }
</style>


    
    <div class="row">
        <div class="col-md-12">
            <?= function_exists('alertMessage') ? alertMessage() : ''; ?>
        </div>

        <div class="col-md-6 mb-4">
            <div class="card card-body p-3">
                 <p class="text-sm mb-0 text-capitalize font-weight-bold">Covoiturages réalisé (aujourd'hui)</p>
                 <h5 class="font-weight-bolder mb-0">
                    <?= number_format($ridesToday, 0, ',', ' ') ?>
                 </h5>
            </div>
        </div>

        <div class="col-md-6 mb-2">
            <div class="card card-body p-3">
                 <p class="text-sm mb-0 text-capitalize font-weight-bold">Crédit gagnés</p>
                 <h5 class="font-weight-bolder mb-0">
                    <?= number_format($creditsToday, 0, ',', ' ') ?>
                 </h5>
            </div>
        </div> 

        <!-- 
        <div class="col-md-3 mb-4">
            <div class="card card-body p-3">
                 <p class="text-sm mb-0 text-capitalize font-weight-bold">Online drivers</p>
                 <h5 class="font-weight-bolder mb-0">
                    527
                 </h5>
            </div>
        </div>
        <div class="col-md-3 mb-4">
            <div class="card card-body p-3">
                 <p class="text-sm mb-0 text-capitalize font-weight-bold">Today's Users</p>
                 <h5 class="font-weight-bolder mb-0">
                    27
                 </h5>
            </div>
        </div>  --> 

        
    </div>
    <div class="row mt-4" style="margin-top: -0px !important;">
    <div class="col-lg-6 mb-4">
        <div class="card z-index-2">
        <div class="card-header pb-0">
            <h6>Covoiturages par jour</h6>
        </div>
        <div class="card-body">
            <canvas id="ridesChart" class="chart-canvas" height="300"></canvas>
        </div>
        </div>
    </div>
    <div class="col-lg-6 mb-4">
        <div class="card z-index-2">
        <div class="card-header pb-0">
            <h6>Crédits gagnés</h6>
        </div>
        <div class="card-body">
            <canvas id="creditsChart" class="chart-canvas" height="300"></canvas>
        </div>
        </div>
    </div>
</div>

<script>
  const ridesChart = document.getElementById("ridesChart").getContext("2d");
  new Chart(ridesChart, {
    type: "line",
    data: {
      labels: labels,
      datasets: [{
        label: "Covoiturages",
        tension: 0.4,
        borderWidth: 3,
        pointRadius: 4,
        borderColor: "#101010",
        backgroundColor: "rgba(61, 61, 61, 0.24)",
        fill: true,
        data: ridesData
      }]
    },
    options: {
      responsive: true,
      scales: { y: { beginAtZero: true } }
    }
  });

  const creditsChart = document.getElementById("creditsChart").getContext("2d");
  new Chart(creditsChart, {
    type: "line",
    data: {
      labels: labels,
      datasets: [{
        label: "Crédits",
        tension: 0.4,
        borderWidth: 3,
        pointRadius: 4,
        borderColor: "#2dce89",
        backgroundColor: "rgba(45, 206, 137, 0.1)",
        fill: true,
        data: creditsData
      }]
    },
    options: {
      responsive: true,
      scales: { y: { beginAtZero: true } }
    }
  });
</script>

<?php include __DIR__ . '/includes/footer.php'; ?>