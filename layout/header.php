<?php
// Pastikan $pdo dan $_SESSION sudah tersedia jika dibutuhkan
// file ini akan di-include, jadi config.php harusnya sudah di-include di file utama
if (!isset($_SESSION)) {
    session_start(); // Pastikan session dimulai jika belum
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle ?? 'Sistem Manajemen Gudang'; ?></title>
    <link rel="stylesheet" href="assets/adminlte/dist/css/adminlte.min.css">
    <link rel="stylesheet" href="assets/adminlte/plugins/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <style>
        /* CSS umum yang mungkin dibutuhkan di semua halaman */
        .total-row {
            font-weight: bold;
            background-color: #f0e6ff !important;
        }
        /* Custom styles for login/register pages if header is used there */
        .login-page, .register-page {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background-color: #f4f6f9;
        }
        .login-box, .register-box {
            width: 360px;
        }
        .login-card-body, .register-card-body {
            padding: 2rem;
        }
        .form-group label { /* General form label style */
            font-weight: bold;
        }
    </style>
</head>
<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">
        <nav class="main-header navbar navbar-expand navbar-white navbar-light">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
                </li>
                <li class="nav-item d-none d-sm-inline-block">
                    <a href="index.php" class="nav-link">Home</a>
                </li>
                 <li class="nav-item d-none d-sm-inline-block">
                    <a href="items.php" class="nav-link">Manajemen Barang</a>
                </li>
            </ul>
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" href="logout.php">Logout</a>
                </li>
            </ul>
        </nav>