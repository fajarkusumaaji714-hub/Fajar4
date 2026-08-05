<?php
require 'koneksi.php';
if (!isset($_SESSION['user'])) { header("Location: index.php"); exit; }

$produk = mysqli_query($conn, "SELECT * FROM produk WHERE stok > 0");

// Proses Simpan Transaksi
if (isset($_POST['proses_transaksi'])) {
    $kode_transaksi = "TRX-" . date('YmdHis');
    $total_belanja = $_POST['val_total_belanja'];
    $pajak = $_POST['val_pajak'];
    $grand_total = $_POST['val_grand_total'];
    $bayar = $_POST['bayar'];
    $kembalian = $_POST['kembalian'];
    $kasir = $_SESSION['user'];

    // Simpan ke tabel transaksi
    mysqli_query($conn, "INSERT INTO transaksi (kode_transaksi, total_belanja, pajak, grand_total, bayar, kembalian, kasir) VALUES ('$kode_transaksi', '$total_belanja', '$pajak', '$grand_total', '$bayar', '$kembalian', '$kasir')");

    // Simpan detail & kurangi stok
    $items = json_decode($_POST['cart_items'], true);
    foreach ($items as $item) {
        $nama = $item['nama'];
        $harga = $item['harga'];
        $qty = $item['qty'];
        $subtotal = $item['subtotal'];

        mysqli_query($conn, "INSERT INTO detail_transaksi (kode_transaksi, nama_barang, harga, jumlah, subtotal) VALUES ('$kode_transaksi', '$nama', '$harga', '$qty', '$subtotal')");
        mysqli_query($conn, "UPDATE produk SET stok = stok - $qty WHERE nama_barang = '$nama'");
    }

    echo "<script>alert('Transaksi Berhasil!'); window.location='cetak_struk.php?kode=$kode_transaksi';</script>";
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Halaman Kasir</title>
    <?php include 'style.css'; ?>
    <style>
        .pos-grid { display: flex; gap: 20px; }
        .pos-left { flex: 1; }
        .pos-right { width: 400px; }
        .total-box { background: #f8f9fc; padding: 15px; border-radius: 5px; margin-top: 15px; }
        .total-row { display: flex; justify-content: space-between; margin-bottom: 8px; font-size: 15px; }
        .total-row.grand { font-size: 18px; font-weight: bold; color: #4e73df; border-top: 2px dashed #d1d3e2; padding-top: 8px; }
    </style>
</head>
<body>
<div class="wrapper">
    <?php include 'sidebar.php'; ?>
    <div class="main-content">
        <h2>Halaman Kasir (POS)</h2>
        <div class="pos-grid">
            <!-- Sisi Kiri: Input Item -->
            <div class="pos-left">
                <div class="card">
                    <h3>Pilih Barang</h3>
                    <label>Nama Barang & Harga</label>
                    <select id="pilih_produk" class="form-control">
                        <option value="">-- Pilih Barang --</option>
                        <?php while($p = mysqli_fetch_assoc($produk)): ?>
                            <option value="<?= $p['nama_barang']; ?>" data-harga="<?= $p['harga']; ?>" data-stok="<?= $p['stok']; ?>">
                                <?= $p['nama_barang']; ?> - Rp <?= number_format($p['harga'], 0, ',', '.'); ?> (Stok: <?= $p['stok']; ?>)
                            </option>
                        <?php endwhile; ?>
                    </select>
                    <label>Jumlah (Qty)</label>
                    <input type="number" id="qty" class="form-control" value="1" min="1">
                    <button type="button" class="btn btn-success" onclick="tambahKeKeranjang()">Tambah ke Keranjang</button>
                </div>
            </div>

            <!-- Sisi Kanan: Keranjang & Kalkulasi -->
            <div class="pos-right">
                <div class="card">
                    <h3>Keranjang Belanja</h3>
                    <table id="tabel-keranjang">
                        <tr>
                            <th>Barang</th>
                            <th>Qty</th>
                            <th>Subtotal</th>
                            <th>Aksi</th>
                        </tr>
                    </table>

                    <form method="POST" id="form-transaksi">
                        <input type="hidden" name="cart_items" id="cart_items">
                        <input type="hidden" name="val_total_belanja" id="val_total_belanja">
                        <input type="hidden" name="val_pajak" id="val_pajak">
                        <input type="hidden" name="val_grand_total" id="val_grand_total">

                        <div class="total-box">
                            <div class="total-row"><span>Total Belanja:</span> <span id="txt_total">Rp 0</span></div>
                            <div class="total-row"><span>Pajak (11%):</span> <span id="txt_pajak">Rp 0</span></div>
                            <div class="total-row grand"><span>Grand Total:</span> <span id="txt_grand">Rp 0</span></div>
                        </div>

                        <label style="margin-top: 15px; display:block;">Uang Bayar (Rp)</label>
                        <input type="number" name="bayar" id="bayar" class="form-control" onkeyup="hitungKembalian()" required>
                        
                        <div class="total-row" style="margin-top: 10px;">
                            <span>Kembalian:</span> <span id="txt_kembalian" style="font-weight: bold; color: #1cc88a;">Rp 0</span>
                        </div>
                        <input type="hidden" name="kembalian" id="val_kembalian">

                        <button type="submit" name="proses_transaksi" class="btn btn-primary" style="width: 100%; margin-top: 15px; padding: 12px;" onclick="return validasiBayar()">PROSES & CETAK STRUK</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
let keranjang = [];

function tambahKeKeranjang() {
    const select = document.getElementById('pilih_produk');
    const selectedOption = select.options[select.selectedIndex];
    if(!select.value) { alert('Pilih barang terlebih dahulu!'); return; }

    const nama = select.value;
    const harga = parseFloat(selectedOption.getAttribute('data-harga'));
    const stok = parseInt(selectedOption.getAttribute('data-stok'));
    const qty = parseInt(document.getElementById('qty').value);

    if(qty > stok) { alert('Jumlah melebihi stok yang tersedia!'); return; }

    let existing = keranjang.find(item => item.nama === nama);
    if(existing) {
        if((existing.qty + qty) > stok) { alert('Total jumlah melebihi stok!'); return; }
        existing.qty += qty;
        existing.subtotal = existing.qty * harga;
    } else {
        keranjang.push({ nama, harga, qty, subtotal: harga * qty });
    }
    renderKeranjang();
}

function hapusItem(index) {
    keranjang.splice(index, 1);
    renderKeranjang();
}

function renderKeranjang() {
    let tbody = document.getElementById('tabel-keranjang');
    tbody.innerHTML = `<tr><th>Barang</th><th>Qty</th><th>Subtotal</th><th>Aksi</th></tr>`;
    
    let totalBelanja = 0;
    keranjang.forEach((item, index) => {
        totalBelanja += item.subtotal;
        tbody.innerHTML += `
            <tr>
                <td>${item.nama}</td>
                <td>${item.qty}</td>
                <td>Rp ${item.subtotal.toLocaleString('id-ID')}</td>
                <td><button type="button" class="btn btn-danger" style="padding: 3px 8px; font-size: 11px;" onclick="hapusItem(${index})">X</button></td>
            </tr>
        `;
    });

    let pajak = totalBelanja * 0.11;
    let grandTotal = totalBelanja + pajak;

    document.getElementById('txt_total').innerText = 'Rp ' + totalBelanja.toLocaleString('id-ID');
    document.getElementById('txt_pajak').innerText = 'Rp ' + pajak.toLocaleString('id-ID');
    document.getElementById('txt_grand').innerText = 'Rp ' + grandTotal.toLocaleString('id-ID');

    document.getElementById('val_total_belanja').value = totalBelanja;
    document.getElementById('val_pajak').value = pajak;
    document.getElementById('val_grand_total').value = grandTotal;
    document.getElementById('cart_items').value = JSON.stringify(keranjang);
    hitungKembalian();
}

function hitungKembalian() {
    let grandTotal = parseFloat(document.getElementById('val_grand_total').value) || 0;
    let bayar = parseFloat(document.getElementById('bayar').value) || 0;
    let kembalian = bayar - grandTotal;

    document.getElementById('txt_kembalian').innerText = 'Rp ' + (kembalian >= 0 ? kembalian.toLocaleString('id-ID') : 0);
    document.getElementById('val_kembalian').value = kembalian >= 0 ? kembalian : 0;
}

function validasiBayar() {
    let grandTotal = parseFloat(document.getElementById('val_grand_total').value) || 0;
    let bayar = parseFloat(document.getElementById('bayar').value) || 0;
    if(keranjang.length === 0) { alert('Keranjang masih kosong!'); return false; }
    if(bayar < grandTotal) { alert('Uang bayar kurang!'); return false; }
    return true;
}
</script>
</body>
</html>