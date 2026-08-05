<?php
require 'koneksi.php';
if (!isset($_SESSION['user']) || $_SESSION['role'] != 'admin') { header("Location: dashboard.php"); exit; }

// Proses Tambah atau Edit User
if (isset($_POST['simpan'])) {
    $id = $_POST['id'];
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $nama = mysqli_real_escape_string($conn, $_POST['nama_lengkap']);
    $role = $_POST['role'];

    if ($id == "") {
        // Tambah User Baru (Password wajib diisi)
        $password = md5($_POST['password']);
        mysqli_query($conn, "INSERT INTO users (username, password, nama_lengkap, role) VALUES ('$username', '$password', '$nama', '$role')");
    } else {
        // Edit User (Cek apakah password diisi atau tidak)
        if (!empty($_POST['password'])) {
            $password = md5($_POST['password']);
            mysqli_query($conn, "UPDATE users SET username='$username', password='$password', nama_lengkap='$nama', role='$role' WHERE id='$id'");
        } else {
            // Jika password kosong, jangan ubah password lama
            mysqli_query($conn, "UPDATE users SET username='$username', nama_lengkap='$nama', role='$role' WHERE id='$id'");
        }
    }
    header("Location: user.php");
    exit;
}

// Proses Hapus User
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    mysqli_query($conn, "DELETE FROM users WHERE id='$id'");
    header("Location: user.php");
    exit;
}

// Ambil data untuk Form Edit
$edit_data = ['id' => '', 'username' => '', 'nama_lengkap' => '', 'role' => 'kasir'];
if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $query_edit = mysqli_query($conn, "SELECT * FROM users WHERE id='$id'");
    if (mysqli_num_rows($query_edit) > 0) {
        $edit_data = mysqli_fetch_assoc($query_edit);
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Manajemen User</title>
    <?php include 'style.css'; ?>
</head>
<body>
<div class="wrapper">
    <?php include 'sidebar.php'; ?>
    <div class="main-content">
        <h2>Manajemen User</h2>
        
        <!-- Form Tambah / Edit User -->
        <div class="card" style="max-width: 500px;">
            <h3><?= $edit_data['id'] ? 'Edit User' : 'Tambah User Baru'; ?></h3>
            <form method="POST">
                <input type="hidden" name="id" value="<?= $edit_data['id']; ?>">
                
                <label>Username</label>
                <input type="text" name="username" class="form-control" value="<?= $edit_data['username']; ?>" required>
                
                <label>Password <?= $edit_data['id'] ? '<small style="color:red;">(Kosongkan jika tidak ingin mengubah password)</small>' : ''; ?></label>
                <input type="password" name="password" class="form-control" <?= $edit_data['id'] ? '' : 'required'; ?>>
                
                <label>Nama Lengkap</label>
                <input type="text" name="nama_lengkap" class="form-control" value="<?= $edit_data['nama_lengkap']; ?>" required>
                
                <label>Role</label>
                <select name="role" class="form-control">
                    <option value="kasir" <?= $edit_data['role'] == 'kasir' ? 'selected' : ''; ?>>Kasir</option>
                    <option value="admin" <?= $edit_data['role'] == 'admin' ? 'selected' : ''; ?>>Admin</option>
                </select>
                
                <button type="submit" name="simpan" class="btn btn-primary"><?= $edit_data['id'] ? 'Perbarui User' : 'Simpan User'; ?></button>
                <?php if($edit_data['id']): ?>
                    <a href="user.php" class="btn btn-danger">Batal</a>
                <?php endif; ?>
            </form>
        </div>

        <!-- Tabel Daftar User -->
        <div class="card">
            <h3>Daftar User</h3>
            <table>
                <tr>
                    <th>No</th>
                    <th>Username</th>
                    <th>Nama Lengkap</th>
                    <th>Role</th>
                    <th>Aksi</th>
                </tr>
                <?php 
                $no = 1;
                $data = mysqli_query($conn, "SELECT * FROM users");
                while($d = mysqli_fetch_assoc($data)):
                ?>
                <tr>
                    <td><?= $no++; ?></td>
                    <td><?= $d['username']; ?></td>
                    <td><?= $d['nama_lengkap']; ?></td>
                    <td><?= ucfirst($d['role']); ?></td>
                    <td>
                        <a href="user.php?edit=<?= $d['id']; ?>" class="btn btn-primary" style="padding: 5px 10px; font-size: 12px;">Edit</a>
                        <?php if($d['username'] != 'admin'): ?>
                            <a href="user.php?hapus=<?= $d['id']; ?>" class="btn btn-danger" style="padding: 5px 10px; font-size: 12px;" onclick="return confirm('Yakin hapus user ini?')">Hapus</a>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endwhile; ?>
            </table>
        </div>
    </div>
</div>
</body>
</html>