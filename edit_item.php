<?php
include 'config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$pageTitle = 'Edit Barang'; // Set judul halaman
$error = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_barang'])) {
    $id = $_POST['id'];
    $nama_barang = $_POST['nama_barang'];
    $stok_barang = $_POST['stok_barang'];
    $harga = $_POST['harga'];

    $stmt = $pdo->prepare("UPDATE items SET nama_barang = ?, stok_barang = ?, harga = ? WHERE id = ? AND gudang_id = ?");
    if ($stmt->execute([$nama_barang, $stok_barang, $harga, $id, $_SESSION['gudang_id']])) {
        header('Location: items.php?success=1');
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
        header('Location: items.php');
        exit();
    }
} else {
    header('Location: items.php');
    exit();
}

include 'layout/header.php';
include 'layout/sidebar.php';
?>

<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Edit Barang</h1>
                </div>
            </div>
        </div>
    </div>
    <section class="content">
        <div class="container-fluid">
            <?php if (isset($error)): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?php echo $error; ?>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            <?php endif; ?>

            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Form Edit Barang</h3>
                </div>
                <form method="POST">
                    <input type="hidden" name="id" value="<?php echo $item['id']; ?>">
                    <div class="card-body">
                        <div class="form-group">
                            <label for="nama_barang">Nama Barang:</label>
                            <input type="text" id="nama_barang" name="nama_barang" value="<?php echo htmlspecialchars($item['nama_barang']); ?>" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="stok_barang">Stok Barang:</label>
                            <input type="number" id="stok_barang" name="stok_barang" value="<?php echo $item['stok_barang']; ?>" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="harga">Harga:</label>
                            <input type="number" step="0.01" id="harga" name="harga" value="<?php echo $item['harga']; ?>" class="form-control" required>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" name="update_barang" class="btn btn-primary">Update Barang</button>
                        <a href="items.php" class="btn btn-secondary">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </section>
</div>

<?php
include 'layout/footer.php';
?>