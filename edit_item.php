<?php
include 'config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_barang'])) {
    $id = $_POST['id'];
    $nama_barang = $_POST['nama_barang'];
    $stok_barang = $_POST['stok_barang'];
    $harga = $_POST['harga'];

    $stmt = $pdo->prepare("UPDATE items SET nama_barang = ?, stok_barang = ?, harga = ? WHERE id = ? AND gudang_id = ?");
    if ($stmt->execute([$nama_barang, $stok_barang, $harga, $id, $_SESSION['gudang_id']])) {
        header('Location: index.php?success=1');
        exit();
    } else {
        $error = "Gagal mengupdate barang";
    }
}

// Get item data
if (isset($_GET['id'])) {
    $stmt = $pdo->prepare("SELECT * FROM items WHERE id = ? AND gudang_id = ?");
    $stmt->execute([$_GET['id'], $_SESSION['gudang_id']]);
    $item = $stmt->fetch();

    if (!$item) {
        header('Location: index.php');
        exit();
    }
} else {
    header('Location: index.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Barang - Sistem Gudang</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Edit Barang</h1>
        </div>

        <div class="navbar">
            <a href="index.php">Kembali</a>
            <a href="logout.php" class="logout-btn">Logout</a>
        </div>

        <?php if (isset($error)): ?>
            <div class="message error"><?php echo $error; ?></div>
        <?php endif; ?>

        <div class="item-form">
            <form method="POST">
                <input type="hidden" name="id" value="<?php echo $item['id']; ?>">
                <div class="form-group">
                    <label for="nama_barang">Nama Barang:</label>
                    <input type="text" id="nama_barang" name="nama_barang" value="<?php echo htmlspecialchars($item['nama_barang']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="stok_barang">Stok Barang:</label>
                    <input type="number" id="stok_barang" name="stok_barang" value="<?php echo $item['stok_barang']; ?>" required>
                </div>
                <div class="form-group">
                    <label for="harga">Harga:</label>
                    <input type="number" step="0.01" id="harga" name="harga" value="<?php echo $item['harga']; ?>" required>
                </div>
                <button type="submit" name="update_barang" class="btn">Update Barang</button>
            </form>
        </div>
    </div>
</body>
</html>