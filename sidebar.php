<div class="sidebar">
    <h2>POS System</h2>
    <a href="dashboard.php" class="<?= basename($_SERVER['PHP_SELF']) == 'dashboard.php' ? 'active':'' ?>">Dashboard</a>
    <a href="kasir.php" class="<?= basename($_SERVER['PHP_SELF']) == 'kasir.php' ? 'active':'' ?>">Halaman Kasir</a>
    <a href="riwayat.php" class="<?= basename($_SERVER['PHP_SELF']) == 'riwayat.php' ? 'active':'' ?>">Riwayat Transaksi</a>
    <?php if($_SESSION['role'] == 'admin'): ?>
        <a href="produk.php" class="<?= basename($_SERVER['PHP_SELF']) == 'produk.php' ? 'active':'' ?>">Manajemen Produk</a>
        <a href="user.php" class="<?= basename($_SERVER['PHP_SELF']) == 'user.php' ? 'active':'' ?>">Manajemen User</a>
    <?php endif; ?>
    <a href="logout.php" style="color: #e74a3b; margin-top: 50px;">Logout</a>
</div>