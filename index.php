<?php
include 'config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$pageTitle = 'Dashboard Gudang'; // Set judul halaman

// Get dashboard statistics
$stats_stmt = $pdo->prepare("SELECT * FROM cardAgr WHERE gudang_id = ?");
$stats_stmt->execute([$_SESSION['gudang_id']]);
$stats = $stats_stmt->fetch();

// Function to format currency
function format_rupiah($value) {
    return 'Rp ' . number_format($value, 0, ',', '.');
}

include 'layout/header.php';
include 'layout/sidebar.php';
?>

<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Dashboard Gudang <?php echo $_SESSION['gudang_id']; ?></h1>
                </div>
            </div>
        </div>
    </div>
    <section class="content">
        <div class="container-fluid">
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
        </div>
    </section>
</div>

<?php
include 'layout/footer.php';
?>