<?php
require 'koneksi.php';
if (!isset($_SESSION['user'])) { header("Location: index.php"); exit; }
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Riwayat Transaksi</title>
    <?php include 'style.css'; ?>
</head>
<body>
<div class="wrapper">
    <?php include 'sidebar.php'; ?>
    <div class="main-content">
        <h2>Riwayat Transaksi</h2>
        <div class="card">
            <table>
                <tr>
                    <th>No</th>
                    <th>Kode Transaksi</th>
                    <th>Tanggal</th>
                    <th>Grand Total</th>
                    <th>Kasir</th>
                    <th>Aksi</th>
                </tr>
                <?php 
                $no = 1;
                $data = mysqli_query($conn, "SELECT * FROM transaksi ORDER BY id DESC");
                while($d = mysqli_fetch_assoc($data)):
                ?>
                <tr>
                    <td><?= $no++; ?></td>
                    <td><?= $d['kode_transaksi']; ?></td>
                    <td><?= $d['tanggal']; ?></td>
                    <td>Rp <?= number_format($d['grand_total'], 0, ',', '.'); ?></td>
                    <td><?= $d['kasir']; ?></td>
                    <td>
                        <a href="detail_transaksi.php?kode=<?= $d['kode_transaksi']; ?>" class="btn btn-primary" style="padding: 5px 10px; font-size: 12px;">Detail</a>
                        <a href="cetak_struk.php?kode=<?= $d['kode_transaksi']; ?>" target="_blank" class="btn btn-success" style="padding: 5px 10px; font-size: 12px;">Struk</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </table>
        </div>
    </div>
</div>
</body>
</html>