<?php
include 'config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Handle item addition
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['tambah_barang'])) {
    $nama_barang = $_POST['nama_barang'];
    $stok_barang = $_POST['stok_barang'];
    $harga = $_POST['harga'];
    $gudang_id = $_SESSION['gudang_id'];

    $stmt = $pdo->prepare("INSERT INTO items (gudang_id, nama_barang, stok_barang, harga) VALUES (?, ?, ?, ?)");
    if ($stmt->execute([$gudang_id, $nama_barang, $stok_barang, $harga])) {
        $message = "Barang berhasil ditambahkan!";
        $message_class = "success";
    } else {
        $message = "Gagal menambahkan barang!";
        $message_class = "error";
    }
}

// Get dashboard statistics
$stats_stmt = $pdo->prepare("SELECT * FROM cardAgr WHERE gudang_id = ?");
$stats_stmt->execute([$_SESSION['gudang_id']]);
$stats = $stats_stmt->fetch();

// Get all items for this warehouse
$stmt = $pdo->prepare("CALL SelectAllItems (?)");
$stmt->execute([$_SESSION['gudang_id']]);
$items = $stmt->fetchAll();

// Function to format currency
function format_rupiah($value) {
    return 'Rp ' . number_format($value, 0, ',', '.');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gudang - Sistem Manajemen Barang</title>
    <link rel="stylesheet" href="assets/adminlte/dist/css/adminlte.min.css">
    <link rel="stylesheet" href="assets/adminlte/plugins/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">

    <style>
        /* Your custom styles can go here if needed, or adjust AdminLTE classes */
        .dashboard {
            display: flex;
            gap: 20px;
            margin-bottom: 30px;
            flex-wrap: wrap; /* Allow cards to wrap on smaller screens */
        }
        .card-custom { /* Custom class for dashboard cards if you want specific spacing */
            flex: 1;
            min-width: 300px; /* Adjust as needed */
        }
        .total-row {
            font-weight: bold;
            background-color: #f0e6ff !important;
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
            </ul>
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" href="logout.php">Logout</a>
                </li>
            </ul>
        </nav>
        <aside class="main-sidebar sidebar-dark-primary elevation-4">
            <a href="index.php" class="brand-link">
                <span class="brand-text font-weight-light">Sistem Gudang</span>
            </a>
            <div class="sidebar">
                <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                    <div class="info">
                        <a href="#" class="d-block">Selamat datang, <?php echo htmlspecialchars($_SESSION['username']); ?></a>
                    </div>
                </div>
                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                        <li class="nav-item">
                            <a href="index.php" class="nav-link active">
                                <i class="nav-icon fas fa-th"></i>
                                <p>Dashboard</p>
                            </a>
                        </li>
                        </ul>
                </nav>
            </div>
        </aside>

        <div class="content-wrapper">
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0">Manajemen Gudang <?php echo $_SESSION['gudang_id']; ?></h1>
                        </div>
                    </div>
                </div>
            </div>
            <section class="content">
                <div class="container-fluid">

                    <?php if (isset($message)): ?>
                        <div class="alert alert-<?php echo ($message_class == 'success') ? 'success' : 'danger'; ?> alert-dismissible fade show" role="alert">
                            <?php echo $message; ?>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    <?php endif; ?>

                    <div class="row">
                        <div class="col-lg-3 col-6">
                            <div class="small-box bg-info">
                                <div class="inner">
                                    <h3><?php echo $stats['total_stock'] ?? 0; ?></h3>
                                    <p>Total Stok</p>
                                </div>
                                <div class="icon">
                                    <i class="ion ion-bag"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-6">
                            <div class="small-box bg-success">
                                <div class="inner">
                                    <h3><?php echo $stats['item_count'] ?? 0; ?></h3>
                                    <p>Jumlah Barang</p>
                                </div>
                                <div class="icon">
                                    <i class="ion ion-stats-bars"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-6">
                            <div class="small-box bg-warning">
                                <div class="inner">
                                    <h3><?php echo format_rupiah($stats['total_value'] ?? 0); ?></h3>
                                    <p>Total Nilai</p>
                                </div>
                                <div class="icon">
                                    <i class="ion ion-person-add"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-6">
                            <div class="small-box bg-danger">
                                <div class="inner">
                                    <h3><?php echo format_rupiah($stats['avg_harga'] ?? 0); ?></h3>
                                    <p>Rata-rata Harga</p>
                                </div>
                                <div class="icon">
                                    <i class="ion ion-pie-graph"></i>
                                </div>
                            </div>
                        </div>
                         <div class="col-lg-3 col-6">
                            <div class="small-box bg-primary">
                                <div class="inner">
                                    <h3><?php echo format_rupiah($stats['max_price'] ?? 0); ?></h3>
                                    <p>Bundle Termahal</p>
                                </div>
                                <div class="icon">
                                    <i class="ion ion-cash"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-6">
                            <div class="small-box bg-secondary">
                                <div class="inner">
                                    <h3><?php echo format_rupiah($stats['min_price'] ?? 0); ?></h3>
                                    <p>Bundle Termurah</p>
                                </div>
                                <div class="icon">
                                    <i class="ion ion-arrow-down-a"></i>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Tambah Barang Baru</h3>
                        </div>
                        <form method="POST">
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="nama_barang">Nama Barang:</label>
                                    <input type="text" id="nama_barang" name="nama_barang" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label for="stok_barang">Stok:</label>
                                    <input type="number" id="stok_barang" name="stok_barang" min="0" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label for="harga">Harga (Rp):</label>
                                    <input type="number" id="harga" name="harga" min="0" step="100" class="form-control" required>
                                </div>
                            </div>
                            <div class="card-footer">
                                <button type="submit" name="tambah_barang" class="btn btn-primary">Tambah Barang</button>
                            </div>
                        </form>
                    </div>

                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Daftar Barang</h3>
                        </div>
                        <div class="card-body">
                            <?php if (count($items) > 0): ?>
                                <table id="example1" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nama Barang</th>
                                            <th>Stok</th>
                                            <th>Harga Satuan</th>
                                            <th>Harga Keseluruhan</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($items as $index => $item): ?>
                                        <tr>
                                            <td><?php echo $index + 1; ?></td>
                                            <td><?php echo htmlspecialchars($item['nama_barang']); ?></td>
                                            <td><?php echo $item['stok_barang']; ?></td>
                                            <td><?php echo format_rupiah($item['harga']); ?></td>
                                            <td><?php echo format_rupiah($item['total_harga']); ?></td>
                                            <td>
                                                <a href="edit_item.php?id=<?php echo $item['id']; ?>" class="btn btn-info btn-sm">Edit</a>
                                                <a href="delete_item.php?id=<?php echo $item['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus barang ini?')">Hapus</a>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                        <tr class="total-row">
                                            <td colspan="3">TOTAL</td>
                                            <td><?php echo format_rupiah(array_sum(array_column($items, 'harga'))); ?></td>
                                            <td><?php echo format_rupiah($stats['total_value'] ?? 0); ?></td>
                                            <td></td>
                                        </tr>
                                    </tbody>
                                </table>
                            <?php else: ?>
                                <p>Belum ada barang di gudang ini.</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </section>
        </div>

        <footer class="main-footer">
            <div class="float-right d-none d-sm-block">
                <b>Version</b> 3.1.0
            </div>
            <strong>Copyright &copy; 2025 <a href="#">Kelompok 11</a>.</strong> All rights reserved.
        </footer>

        <aside class="control-sidebar control-sidebar-dark">
            </aside>
        </div>
    <script src="assets/adminlte/plugins/jquery/jquery.min.js"></script>
    <script src="assets/adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="assets/adminlte/dist/js/adminlte.min.js"></script>
</body>
</html>