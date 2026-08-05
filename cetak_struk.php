<?php
require 'koneksi.php';
$kode = $_GET['kode'];
$trx = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM transaksi WHERE kode_transaksi='$kode'"));
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Struk Belanja - <?= $kode; ?></title>
    <style>
        body { font-family: 'Courier New', Courier, monospace; font-size: 14px; width: 300px; margin: 20px auto; }
        .center { text-align: center; }
        .line { border-bottom: 1px dashed #000; margin: 10px 0; }
        table { width: 100%; font-size: 13px; }
        .right { text-align: right; }
        @media print {
            body { margin: 0; width: 100%; }
            .no-print { display: none; }
        }
    </style>
</head>
<body onload="window.print()">
    <div class="center">
        <h3>TOKO KASIR POS</h3>
        <p>Jl. Contoh No. 123, Yogyakarta</p>
    </div>
    <div class="line"></div>
    <p>No Trans : <?= $trx['kode_transaksi']; ?><br>
       Tanggal  : <?= $trx['tanggal']; ?><br>
       Kasir    : <?= $trx['kasir']; ?></p>
    <div class="line"></div>
    <table>
        <?php 
        $detail = mysqli_query($conn, "SELECT * FROM detail_transaksi WHERE kode_transaksi='$kode'");
        while($d = mysqli_fetch_assoc($detail)):
        ?>
        <tr>
            <td colspan="2"><?= $d['nama_barang']; ?></td>
        </tr>
        <tr>
            <td><?= $d['jumlah']; ?> x <?= number_format($d['harga'], 0, ',', '.'); ?></td>
            <td class="right"><?= number_format($d['subtotal'], 0, ',', '.'); ?></td>
        </tr>
        <?php endwhile; ?>
    </table>
    <div class="line"></div>
    <table>
        <tr><td>Total Belanja</td><td class="right"><?= number_format($trx['total_belanja'], 0, ',', '.'); ?></td></tr>
        <tr><td>Pajak (11%)</td><td class="right"><?= number_format($trx['pajak'], 0, ',', '.'); ?></td></tr>
        <tr><td><b>Grand Total</b></td><td class="right"><b><?= number_format($trx['grand_total'], 0, ',', '.'); ?></b></td></tr>
        <tr><td>Bayar</td><td class="right"><?= number_format($trx['bayar'], 0, ',', '.'); ?></td></tr>
        <tr><td>Kembalian</td><td class="right"><?= number_format($trx['kembalian'], 0, ',', '.'); ?></td></tr>
    </table>
    <div class="line"></div>
    <div class="center">
        <p>Terima Kasih Atas Kunjungan Anda!</p>
    </div>
    <div class="center no-print" style="margin-top: 20px;">
        <a href="kasir.php" style="padding: 8px 15px; background: #4e73df; color: #fff; text-decoration: none; border-radius: 5px;">Kembali ke Kasir</a>
    </div>
</body>
</html>