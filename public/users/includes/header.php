<?php 

$current_page = basename($_SERVER['PHP_SELF']);
require '../../config/function.php';


?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="apple-touch-icon" sizes="76x76" href="assets/img/apple-icon.png">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  
  <link rel="icon" type="image/png" href="assets/img/favicon.png">
  <title>
    <?= isset($pageTitle) ? $pageTitle : 'Device Services'; ?>
  </title>

  <link href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700,800" rel="stylesheet" />

  <link href="https://demos.creative-tim.com/soft-ui-dashboard/assets/css/nucleo-icons.css" rel="stylesheet" />
  <link href="https://demos.creative-tim.com/soft-ui-dashboard/assets/css/nucleo-svg.css" rel="stylesheet" />

  <!-- Font Awesome Icons -->
  <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>

  <!-- CSS Files -->
  <link id="pagestyle" href="assets/css/soft-ui-dashboard.css?v=1.1.0" rel="stylesheet" />

</head>

<body class="g-sidenav-show  bg-gray-100">

    <?php include('sidebar.php'); ?>

       <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">

            <?php include('navbar.php'); ?>

                <div class="container-fluid py-4">