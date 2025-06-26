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
$stats_stmt = $pdo->prepare("SELECT 
    SUM(stok_barang) as total_stock, 
    COUNT(id) as item_count,
    SUM(stok_barang * harga) as total_value,
    ROUND(AVG(calculate_item_total(stok_barang, harga)),2) as avg_harga
    FROM items 
    WHERE gudang_id = ?");
$stats_stmt->execute([$_SESSION['gudang_id']]);
$stats = $stats_stmt->fetch();

// Get all items for this warehouse
$stmt = $pdo->prepare("SELECT *, calculate_item_total(stok_barang, harga) as total_harga FROM items WHERE gudang_id = ? ORDER BY nama_barang");
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
    <link rel="stylesheet" href="style.css">
    <style>
        .dashboard {
            display: flex;
            gap: 20px;
            margin-bottom: 30px;
        }
        .card {
            flex: 1;
            background-color: white;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            text-align: center;
        }
        .card h3 {
            margin-top: 0;
            color: var(--primary-dark);
        }
        .card .value {
            font-size: 2.5rem;
            font-weight: bold;
            color: var(--primary-color);
            margin: 10px 0;
        }
        .card .label {
            color: #666;
            font-size: 0.9rem;
        }
        .total-row {
            font-weight: bold;
            background-color: #f0e6ff !important;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Manajemen Gudang <?php echo $_SESSION['gudang_id']; ?></h1>
            <p>Selamat datang, <?php echo htmlspecialchars($_SESSION['username']); ?></p>
        </div>

        <div class="navbar">
            <a href="index.php">Home</a>
            <a href="logout.php" class="logout-btn">Logout</a>
        </div>

        <?php if (isset($message)): ?>
            <div class="message <?php echo $message_class; ?>">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>

        <!-- Dashboard Cards -->
        <div class="dashboard">
            <div class="card">
                <h3>Total Stok</h3>
                <div class="value"><?php echo $stats['total_stock'] ?? 0; ?></div>
                <div class="label">Unit Barang</div>
            </div>
            <div class="card">
                <h3>Jumlah Barang</h3>
                <div class="value"><?php echo $stats['item_count'] ?? 0; ?></div>
                <div class="label">Jenis Barang</div>
            </div>
            <div class="card">
                <h3>Total Nilai</h3>
                <div class="value"><?php echo format_rupiah($stats['total_value'] ?? 0); ?></div>
                <div class="label">Nilai Gudang</div>
            </div>
            <div class="card">
                <h3>Rata rata harga</h3>
                <div class="value"><?php echo format_rupiah($stats['avg_harga'] ?? 0); ?></div>
                <div class="label">Rata</div>
            </div>
        </div>

        <div class="item-form">
            <h2>Tambah Barang Baru</h2>
            <form method="POST">
                <div class="form-group">
                    <label for="nama_barang">Nama Barang:</label>
                    <input type="text" id="nama_barang" name="nama_barang" required>
                </div>
                <div class="form-group">
                    <label for="stok_barang">Stok:</label>
                    <input type="number" id="stok_barang" name="stok_barang" min="0" required>
                </div>
                <div class="form-group">
                    <label for="harga">Harga (Rp):</label>
                    <input type="number" id="harga" name="harga" min="0" step="100" required>
                </div>
                <button type="submit" name="tambah_barang" class="btn">Tambah Barang</button>
            </form>
        </div>

        <div class="items-list">
            <h2>Daftar Barang</h2>
            <?php if (count($items) > 0): ?>
                <table class="table">
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
                                <a href="edit_item.php?id=<?php echo $item['id']; ?>" class="btn" style="background-color: var(--primary-light); padding: 5px 10px;">Edit</a>
                                <a href="delete_item.php?id=<?php echo $item['id']; ?>" class="btn" style="background-color: #f44336; padding: 5px 10px;" onclick="return confirm('Yakin ingin menghapus barang ini?')">Hapus</a>
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
</body>
</html>