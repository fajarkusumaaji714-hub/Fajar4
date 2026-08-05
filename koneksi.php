<?php
$host = "localhost";
$user = "root";
$pass = "";
$db   = "db_kasir";

$conn = mysqli_connect($host, $user, $pass, $db);
if (!$conn) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>
<style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');
    
    * { font-family: 'Poppins', sans-serif; box-sizing: border-box; }
    body { background-color: #f4f7f6; margin: 0; padding: 0; }

    /* Loading Overlay Animation */
    #loading-overlay {
        position: fixed; top: 0; left: 0; width: 100%; height: 100%;
        background: rgba(255, 255, 255, 0.8); display: flex;
        justify-content: center; align-items: center; z-index: 9999;
        transition: opacity 0.3s ease;
    }
    .spinner {
        width: 50px; height: 50px; border: 5px solid #f3f3f3;
        border-top: 5px solid #4e73df; border-radius: 50%;
        animation: spin 0.8s linear infinite;
    }
    @keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }

    /* Sidebar & Layout */
    .wrapper { display: flex; width: 100%; min-height: 100vh; }
    .sidebar { width: 250px; background: #2c3e50; color: #fff; position: fixed; height: 100%; padding-top: 20px; }
    .sidebar h2 { text-align: center; font-size: 22px; margin-bottom: 30px; color: #1abc9c; }
    .sidebar a {
        padding: 15px 20px; display: block; color: #bdc3c7; text-decoration: none;
        transition: all 0.3s ease; border-left: 4px solid transparent;
    }
    .sidebar a:hover, .sidebar a.active {
        background: #34495e; color: #fff; border-left-color: #1abc9c;
        padding-left: 25px;
    }
    .main-content { margin-left: 250px; width: calc(100% - 250px); padding: 30px; }

    /* Card & Table */
    .card { background: #fff; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); padding: 20px; margin-bottom: 20px; }
    table { width: 100%; border-collapse: collapse; margin-top: 10px; }
    table th, table td { padding: 12px; text-align: left; border-bottom: 1px solid #eee; }
    table th { background: #f8f9fc; color: #4e73df; font-weight: 600; }
    
    /* Buttons & Inputs */
    .btn { padding: 8px 15px; border: none; border-radius: 5px; cursor: pointer; font-weight: 500; transition: 0.2s; text-decoration: none; display: inline-block; }
    .btn-primary { background: #4e73df; color: #fff; }
    .btn-primary:hover { background: #2e59d9; }
    .btn-success { background: #1cc88a; color: #fff; }
    .btn-success:hover { background: #17a673; }
    .btn-danger { background: #e74a3b; color: #fff; }
    .btn-danger:hover { background: #be2617; }
    .form-control { width: 100%; padding: 10px; border: 1px solid #d1d3e2; border-radius: 5px; margin-top: 5px; margin-bottom: 15px; }
</style>

<!-- Script Loading Otomatis -->
<div id="loading-overlay"><div class="spinner"></div></div>
<script>
    window.addEventListener("load", function() {
        const loader = document.getElementById("loading-overlay");
        loader.style.opacity = "0";
        setTimeout(() => loader.style.display = "none", 300);
    });
</script>