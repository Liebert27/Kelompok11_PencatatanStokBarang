<?php
include 'config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$pageTitle = 'Daftar Barang'; // Set judul halaman

// Get all items for this warehouse
$stmt = $pdo->prepare("CALL SelectAllItems (?)");
$stmt->execute([$_SESSION['gudang_id']]);
$items = $stmt->fetchAll();
$stmt->closeCursor(); 

// Get dashboard statistics for total value only for the summary row
$stats_stmt = $pdo->prepare("SELECT total_value FROM cardAgr WHERE gudang_id = ?");
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
                    <h1 class="m-0">Daftar Barang</h1>
                </div>
            </div>
        </div>
    </div>
    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Barang di Gudang Anda</h3>
                    <div class="card-tools">
                        <a href="add_item.php" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus"></i> Tambah Barang Baru
                        </a>
                    </div>
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

<?php
include 'layout/footer.php';
?>