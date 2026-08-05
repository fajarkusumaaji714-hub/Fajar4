<?php
require 'koneksi.php';
if (!isset($_SESSION['user']) || $_SESSION['role'] != 'admin') { header("Location: dashboard.php"); exit; }

// Tambah/Edit Produk
if (isset($_POST['simpan'])) {
    $id = $_POST['id'];
    $nama = mysqli_real_escape_string($conn, $_POST['nama_barang']);
    $harga = $_POST['harga'];
    $stok = $_POST['stok'];

    if ($id == "") {
        mysqli_query($conn, "INSERT INTO produk (nama_barang, harga, stok) VALUES ('$nama', '$harga', '$stok')");
    } else {
        mysqli_query($conn, "UPDATE produk SET nama_barang='$nama', harga='$harga', stok='$stok' WHERE id='$id'");
    }
    header("Location: produk.php");
    exit;
}

// Hapus Produk
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    mysqli_query($conn, "DELETE FROM produk WHERE id='$id'");
    header("Location: produk.php");
    exit;
}

$edit_data = ['id' => '', 'nama_barang' => '', 'harga' => '', 'stok' => ''];
if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $edit_data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM produk WHERE id='$id'"));
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Manajemen Produk</title>
    <?php include 'style.css'; ?>
</head>
<body>
<div class="wrapper">
    <?php include 'sidebar.php'; ?>
    <div class="main-content">
        <h2>Manajemen Produk</h2>
        <div class="card" style="max-width: 500px;">
            <h3><?= $edit_data['id'] ? 'Edit Produk' : 'Tambah Produk'; ?></h3>
            <form method="POST">
                <input type="hidden" name="id" value="<?= $edit_data['id']; ?>">
                <label>Nama Barang</label>
                <input type="text" name="nama_barang" class="form-control" value="<?= $edit_data['nama_barang']; ?>" required>
                <label>Harga Satuan (Rp)</label>
                <input type="number" name="harga" class="form-control" value="<?= $edit_data['harga']; ?>" required>
                <label>Stok</label>
                <input type="number" name="stok" class="form-control" value="<?= $edit_data['stok']; ?>" required>
                <button type="submit" name="simpan" class="btn btn-primary">Simpan</button>
                <?php if($edit_data['id']): ?>
                    <a href="produk.php" class="btn btn-danger">Batal</a>
                <?php endif; ?>
            </form>
        </div>

        <div class="card">
            <h3>Daftar Produk</h3>
            <table>
                <tr>
                    <th>No</th>
                    <th>Nama Barang</th>
                    <th>Harga</th>
                    <th>Stok</th>
                    <th>Aksi</th>
                </tr>
                <?php 
                $no = 1;
                $data = mysqli_query($conn, "SELECT * FROM produk");
                while($d = mysqli_fetch_assoc($data)):
                ?>
                <tr>
                    <td><?= $no++; ?></td>
                    <td><?= $d['nama_barang']; ?></td>
                    <td>Rp <?= number_format($d['harga'], 0, ',', '.'); ?></td>
                    <td><?= $d['stok']; ?></td>
                    <td>
                        <a href="produk.php?edit=<?= $d['id']; ?>" class="btn btn-primary" style="padding: 5px 10px; font-size: 12px;">Edit</a>
                        <a href="produk.php?hapus=<?= $d['id']; ?>" class="btn btn-danger" style="padding: 5px 10px; font-size: 12px;" onclick="return confirm('Yakin hapus?')">Hapus</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </table>
        </div>
    </div>
</div>
</body>
</html>