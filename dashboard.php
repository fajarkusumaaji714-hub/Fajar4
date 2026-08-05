<?php
require 'koneksi.php';
if (!isset($_SESSION['user'])) { header("Location: index.php"); exit; }

$jml_produk = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM produk"))['total'];
$jml_transaksi = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM transaksi"))['total'];
$pendapatan = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(grand_total) as total FROM transaksi"))['total'] ?? 0;
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard - POS</title>
    <?php include 'style.css'; ?>
    <style>
        .stats-container { display: flex; gap: 20px; margin-top: 20px; }
        .stat-box { flex: 1; background: #fff; padding: 25px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); border-left: 5px solid #4e73df; }
        .stat-box h3 { margin: 0; color: #5a5c69; font-size: 16px; }
        .stat-box p { font-size: 24px; font-weight: bold; margin: 10px 0 0 0; color: #2e59d9; }
    </style>
</head>
<body>
<div class="wrapper">
    <?php include 'sidebar.php'; ?>
    <div class="main-content">
        <h2>Dashboard</h2>
        <div class="card">
            <p>Selamat datang, <b><?= $_SESSION['user']; ?></b>! Anda login sebagai <b><?= ucfirst($_SESSION['role']); ?></b>.</p>
        </div>
        <div class="stats-container">
            <div class="stat-box">
                <h3>Total Produk</h3>
                <p><?= $jml_produk; ?></p>
            </div>
            <div class="stat-box" style="border-left-color: #1cc88a;">
                <h3>Total Transaksi</h3>
                <p><?= $jml_transaksi; ?></p>
            </div>
            <div class="stat-box" style="border-left-color: #f6c23e;">
                <h3>Pendapatan</h3>
                <p>Rp <?= number_format($pendapatan, 0, ',', '.'); ?></p>
            </div>
        </div>
    </div>
</div>
</body>
</html>