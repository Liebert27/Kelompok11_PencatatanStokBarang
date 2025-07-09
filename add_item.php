<?php
include 'config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$pageTitle = 'Tambah Barang'; // Set judul halaman
$message = '';
$message_class = '';

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

include 'layout/header.php';
include 'layout/sidebar.php';
?>

<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Tambah Barang Baru</h1>
                </div>
            </div>
        </div>
    </div>
    <section class="content">
        <div class="container-fluid">
            <?php if ($message): ?>
                <div class="alert alert-<?php echo ($message_class == 'success') ? 'success' : 'danger'; ?> alert-dismissible fade show" role="alert">
                    <?php echo $message; ?>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            <?php endif; ?>

            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Form Tambah Barang</h3>
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
        </div>
    </section>
</div>

<?php
include 'layout/footer.php';
?>