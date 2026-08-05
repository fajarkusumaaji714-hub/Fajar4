<?php
require 'koneksi.php';
if (!isset($_SESSION['user'])) { header("Location: index.php"); exit; }
$kode = $_GET['kode'];
$trx = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM transaksi WHERE kode_transaksi='$kode'"));
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Detail Transaksi</title>
    <?php include 'style.css'; ?>
</head>
<body>
<div class="wrapper">
    <?php include 'sidebar.php'; ?>
    <div class="main-content">
        <h2>Detail Transaksi: <?= $kode; ?></h2>
        <div class="card" style="max-width: 700px;">
            <p><b>Tanggal:</b> <?= $trx['tanggal']; ?></p>
            <p><b>Kasir:</b> <?= $trx['kasir']; ?></p>
            <table>
                <tr>
                    <th>Nama Barang</th>
                    <th>Harga</th>
                    <th>Qty</th>
                    <th>Subtotal</th>
                </tr>
                <?php 
                $detail = mysqli_query($conn, "SELECT * FROM detail_transaksi WHERE kode_transaksi='$kode'");
                while($d = mysqli_fetch_assoc($detail)):
                ?>
                <tr>
                    <td><?= $d['nama_barang']; ?></td>
                    <td>Rp <?= number_format($d['harga'], 0, ',', '.'); ?></td>
                    <td><?= $d['jumlah']; ?></td>
                    <td>Rp <?= number_format($d['subtotal'], 0, ',', '.'); ?></td>
                </tr>
                <?php endwhile; ?>
            </table>
            <hr style="border: 0; border-top: 1px solid #eee; margin: 15px 0;">
            <p><b>Total Belanja:</b> Rp <?= number_format($trx['total_belanja'], 0, ',', '.'); ?></p>
            <p><b>Pajak 11%:</b> Rp <?= number_format($trx['pajak'], 0, ',', '.'); ?></p>
            <p><b>Grand Total:</b> Rp <?= number_format($trx['grand_total'], 0, ',', '.'); ?></p>
            <p><b>Bayar:</b> Rp <?= number_format($trx['bayar'], 0, ',', '.'); ?></p>
            <p><b>Kembalian:</b> Rp <?= number_format($trx['kembalian'], 0, ',', '.'); ?></p>
            <br>
            <a href="riwayat.php" class="btn btn-primary">Kembali</a>
            <a href="cetak_struk.php?kode=<?= $kode; ?>" target="_blank" class="btn btn-success">Cetak Struk</a>
        </div>
    </div>
</div>
</body>
</html>