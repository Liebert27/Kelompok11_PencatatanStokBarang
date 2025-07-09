<?php
// Pastikan $_SESSION sudah tersedia
?>
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <a href="index.php" class="brand-link">
        <span class="brand-text font-weight-light">Sistem Gudang</span>
    </a>
    <div class="sidebar">
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="info">
                <a href="#" class="d-block">Selamat datang, <?php echo htmlspecialchars($_SESSION['username'] ?? 'User'); ?></a>
            </div>
        </div>
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <li class="nav-item">
                    <a href="index.php" class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'index.php') ? 'active' : ''; ?>">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>Dashboard</p>
                    </a>
                </li>
                <li class="nav-item <?php echo (basename($_SERVER['PHP_SELF']) == 'items.php' || basename($_SERVER['PHP_SELF']) == 'add_item.php' || basename($_SERVER['PHP_SELF']) == 'edit_item.php') ? 'menu-open' : ''; ?>">
                    <a href="#" class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'items.php' || basename($_SERVER['PHP_SELF']) == 'add_item.php' || basename($_SERVER['PHP_SELF']) == 'edit_item.php') ? 'active' : ''; ?>">
                        <i class="nav-icon fas fa-boxes"></i>
                        <p>
                            Manajemen Barang
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="items.php" class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'items.php' || basename($_SERVER['PHP_SELF']) == 'edit_item.php' || basename($_SERVER['PHP_SELF']) == 'add_item.php') ? 'active' : ''; ?>">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Daftar Barang</p>
                            </a>
                        </li>
                        </ul>
                </li>
                </ul>
        </nav>
    </div>
</aside>