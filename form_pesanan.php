<?php
// All PHP logic goes before any HTML output
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include 'config/koneksi.php';

// Redirect if cart is empty
if (empty($_SESSION['keranjang'])) {
    header('Location: keranjang.php');
    exit();
}

// Handle form submission
if (isset($_POST['pesan'])) {
    $nama = $koneksi->real_escape_string($_POST['nama']);
    $no_wa = $koneksi->real_escape_string($_POST['no_wa']);
    $alamat = $koneksi->real_escape_string($_POST['alamat']);
    $tanggal_pesan = date('Y-m-d');

    // Calculate total price from session
    $total_harga = 0;
    foreach ($_SESSION['keranjang'] as $item) {
        $total_harga += $item['harga'] * $item['jumlah'];
    }

    // Use a transaction for data integrity
    mysqli_begin_transaction($koneksi);

    try {
        // 1. Insert into pesanan table
        $query_pesanan = "INSERT INTO pesanan (nama, no_wa, alamat, tanggal_pesan, status, total_harga) VALUES (?, ?, ?, ?, 'pending', ?)";
        $stmt_pesanan = mysqli_prepare($koneksi, $query_pesanan);
        mysqli_stmt_bind_param($stmt_pesanan, 'ssssd', $nama, $no_wa, $alamat, $tanggal_pesan, $total_harga);
        mysqli_stmt_execute($stmt_pesanan);
        $id_pesanan = mysqli_insert_id($koneksi);

        // 2. Insert into detail_pesanan table
        $query_detail = "INSERT INTO detail_pesanan (id_pesanan, jenis_pisang, jumlah, harga_satuan) VALUES (?, ?, ?, ?)";
        $stmt_detail = mysqli_prepare($koneksi, $query_detail);

        foreach ($_SESSION['keranjang'] as $item) {
            mysqli_stmt_bind_param($stmt_detail, 'isid', $id_pesanan, $item['jenis_pisang'], $item['jumlah'], $item['harga']);
            mysqli_stmt_execute($stmt_detail);
        }

        // If all queries succeed, commit the transaction
        mysqli_commit($koneksi);

        // Clear the cart and redirect
        unset($_SESSION['keranjang']);
        header("Location: struk.php?id_pesanan=$id_pesanan&success=true");
        exit();

    } catch (mysqli_sql_exception $exception) {
        mysqli_rollback($koneksi);
        $error_message = "Pesanan GAGAL Dibuat. Terjadi kesalahan database.";
        // For debugging: $error_message = $exception->getMessage();
    }
}

// Now, start HTML output
include 'templates/header.php';

// Recalculate for display
$keranjang = $_SESSION['keranjang'] ?? [];
$total_harga_display = 0;
foreach ($keranjang as $item) {
    $total_harga_display += $item['harga'] * $item['jumlah'];
}
?>

<div class="container my-5">
  <div class="row g-5">
    <!-- Order Summary -->
    <div class="col-md-5 col-lg-4 order-md-last">
      <h4 class="d-flex justify-content-between align-items-center mb-3">
        <span class="text-primary">Ringkasan Pesanan</span>
        <span class="badge bg-primary rounded-pill"><?= count($keranjang) ?></span>
      </h4>
      <ul class="list-group mb-3">
        <?php foreach ($keranjang as $item): ?>
        <li class="list-group-item d-flex justify-content-between lh-sm">
          <div>
            <h6 class="my-0"><?= htmlspecialchars($item['jenis_pisang']) ?></h6>
            <small class="text-muted">Jumlah: <?= $item['jumlah'] ?> kg</small>
          </div>
          <span class="text-muted">Rp <?= number_format($item['harga'] * $item['jumlah'], 0, ',', '.') ?></span>
        </li>
        <?php endforeach; ?>
        <li class="list-group-item d-flex justify-content-between">
          <span>Total (IDR)</span>
          <strong>Rp <?= number_format($total_harga_display, 0, ',', '.') ?></strong>
        </li>
      </ul>
    </div>

    <!-- Checkout Form -->
    <div class="col-md-7 col-lg-8">
      <h4 class="mb-3">Alamat Pengiriman & Kontak</h4>
      <?php if (isset($error_message)): ?>
        <div class="alert alert-danger"><?= $error_message ?></div>
      <?php endif; ?>
      <form action="form_pesanan.php" method="POST" class="needs-validation" novalidate>
        <div class="row g-3">
          <div class="col-12">
            <label for="nama" class="form-label">Nama Lengkap</label>
            <input type="text" class="form-control" id="nama" name="nama" required>
            <div class="invalid-feedback">Nama lengkap wajib diisi.</div>
          </div>

          <div class="col-12">
            <label for="no_wa" class="form-label">Nomor WhatsApp</label>
            <input type="tel" class="form-control" id="no_wa" name="no_wa" placeholder="081234567890" required>
            <div class="invalid-feedback">Nomor WhatsApp yang valid wajib diisi.</div>
          </div>

          <div class="col-12">
            <label for="alamat" class="form-label">Alamat Pengiriman</label>
            <textarea class="form-control" id="alamat" name="alamat" rows="4" required></textarea>
            <div class="invalid-feedback">Alamat pengiriman wajib diisi.</div>
          </div>
        </div>

        <hr class="my-4">

        <h4 class="mb-3">Metode Pembayaran</h4>
        <div class="my-3">
          <div class="form-check">
            <input id="cod" name="paymentMethod" type="radio" class="form-check-input" checked required>
            <label class="form-check-label" for="cod">Bayar di Tempat (Cash on Delivery)</label>
          </div>
        </div>

        <hr class="my-4">

        <button class="w-100 btn btn-primary btn-lg" type="submit" name="pesan">Buat Pesanan</button>
      </form>
    </div>
  </div>
</div>

<?php 
include 'templates/footer.php'; 
?>
