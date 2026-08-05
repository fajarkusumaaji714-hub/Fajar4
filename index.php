<?php
require 'koneksi.php';
$error = "";

if (isset($_POST['login'])) {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = md5($_POST['password']);

    $query = mysqli_query($conn, "SELECT * FROM users WHERE username='$username' AND password='$password'");
    if (mysqli_num_rows($query) > 0) {
        $row = mysqli_fetch_assoc($query);
        $_SESSION['user'] = $row['nama_lengkap'];
        $_SESSION['role'] = $row['role'];
        header("Location: dashboard.php");
        exit;
    } else {
        $error = "Username atau Password salah!";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login - Aplikasi Kasir</title>
    <?php include 'style.css'; ?>
    <style>
        body { display: flex; justify-content: center; align-items: center; height: 100vh; background: linear-gradient(135deg, #4e73df, #1cc88a); }
        .login-card { width: 380px; background: #fff; padding: 30px; border-radius: 10px; box-shadow: 0 10px 25px rgba(0,0,0,0.2); }
        .login-card h2 { text-align: center; margin-bottom: 20px; color: #4e73df; }
        .alert-error { background: #ffe6e6; color: #d63031; padding: 10px; border-radius: 5px; margin-bottom: 15px; font-size: 14px; text-align: center; }
    </style>
</head>
<body>
    <div class="login-card">
        <h2>POS LOGIN</h2>
        <?php if($error): ?>
            <div class="alert-error"><?= $error; ?></div>
        <?php endif; ?>
        <form method="POST">
            <label>Username</label>
            <input type="text" name="username" class="form-control" required>
            <label>Password</label>
            <input type="password" name="password" class="form-control" required>
            <button type="submit" name="login" class="btn btn-primary" style="width: 100%; padding: 12px; margin-top: 10px;">MASUK</button>
        </form>
    </div>
</body>
</html>