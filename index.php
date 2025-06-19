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

// Get all items for this warehouse
$stmt = $pdo->prepare("SELECT * FROM items WHERE gudang_id = ? ORDER BY nama_barang");
$stmt->execute([$_SESSION['gudang_id']]);
$items = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gudang - Sistem Manajemen Barang</title>
    <link rel="stylesheet" href="style.css">
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
                            <th>Harga</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($items as $index => $item): ?>
                            <tr>
                                <td><?php echo $index + 1; ?></td>
                                <td><?php echo htmlspecialchars($item['nama_barang']); ?></td>
                                <td><?php echo $item['stok_barang']; ?></td>
                                <td>Rp <?php echo number_format($item['harga'], 0, ',', '.'); ?></td>
                                <td>
                                    <a href="edit_item.php?id=<?php echo $item['id']; ?>" class="btn"
                                        style="background-color: var(--primary-light); padding: 5px 10px;">Edit</a>
                                    <a href="delete_item.php?id=<?php echo $item['id']; ?>" class="btn"
                                        style="background-color: #f44336; padding: 5px 10px;"
                                        onclick="return confirm('Yakin ingin menghapus?')">Hapus</a>
                                </td>
                            
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>Belum ada barang di gudang ini.</p>
            <?php endif; ?>
        </div>
    </div>
</body>

</html>