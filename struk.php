<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include 'config/koneksi.php';
include 'templates/header.php';

$pesanan = null;
$detail_pesanan = [];

if (!isset($_GET['id_pesanan'])) {
    echo "<div class='container my-5'><div class='alert alert-danger'>ID Pesanan tidak ditemukan.</div></div>";
    include 'templates/footer.php';
    exit();
}

$id_pesanan = (int)$_GET['id_pesanan'];

// Ambil data pesanan utama
$query_pesanan = "SELECT * FROM pesanan WHERE id_pesanan = $id_pesanan";
$result_pesanan = mysqli_query($koneksi, $query_pesanan);

if ($result_pesanan && mysqli_num_rows($result_pesanan) > 0) {
    $pesanan = mysqli_fetch_assoc($result_pesanan);
    
    // Ambil item-item detail pesanan
    $query_detail = "SELECT * FROM detail_pesanan WHERE id_pesanan = $id_pesanan";
    $result_detail = mysqli_query($koneksi, $query_detail);
    while ($row = mysqli_fetch_assoc($result_detail)) {
      $detail_pesanan[] = $row;
    }
?>

<div class="container my-5">
  <div class="row justify-content-center">
    <div class="col-lg-8">
      <div class="card">
        <div class="card-header bg-primary text-white">
          <h4 class="mb-0">Struk Pesanan</h4>
        </div>
        <div class="card-body p-4">
          <?php if(isset($_GET['success'])): ?>
            <div class="alert alert-success">
              Pesanan Anda telah berhasil dibuat. Terima kasih!
            </div>
          <?php endif; ?>

          <h5 class="card-title">Detail Pesanan #<?php echo htmlspecialchars($pesanan['id_pesanan']); ?></h5>
          
          <ul class="list-group list-group-flush mb-4">
            <li class="list-group-item"><strong>Nama:</strong> <?php echo htmlspecialchars($pesanan['nama']); ?></li>
            <li class="list-group-item"><strong>No. WhatsApp:</strong> <?php echo htmlspecialchars($pesanan['no_wa']); ?></li>
            <li class="list-group-item"><strong>Alamat Pengiriman:</strong> <?php echo nl2br(htmlspecialchars($pesanan['alamat'])); ?></li>
            <li class="list-group-item"><strong>Tanggal Pesan:</strong> <?php echo date("d F Y", strtotime($pesanan['tanggal_pesan'])); ?></li>
            <li class="list-group-item"><strong>Status:</strong> <span class="badge bg-warning text-dark"><?php echo htmlspecialchars($pesanan['status']); ?></span></li>
          </ul>

          <h5 class="mb-3">Item yang Dipesan</h5>
          <div class="table-responsive">
            <table class="table table-bordered">
              <thead>
                <tr>
                  <th>Jenis Pisang</th>
                  <th class="text-center">Jumlah</th>
                  <th class="text-end">Harga Satuan</th>
                  <th class="text-end">Subtotal</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach($detail_pesanan as $item): ?>
                <tr>
                  <td><?= htmlspecialchars($item['jenis_pisang']) ?></td>
                  <td class="text-center"><?= htmlspecialchars($item['jumlah']) ?> kg</td>
                  <td class="text-end">Rp <?= number_format($item['harga_satuan'], 0, ',', '.') ?></td>
                  <td class="text-end">Rp <?= number_format($item['jumlah'] * $item['harga_satuan'], 0, ',', '.') ?></td>
                </tr>
                <?php endforeach; ?>
              </tbody>
              <tfoot>
                <tr>
                  <th colspan="3" class="text-end">Total Harga</th>
                  <th class="text-end">Rp <?= number_format($pesanan['total_harga'], 0, ',', '.') ?></th>
                </tr>
              </tfoot>
            </table>
          </div>

        </div>
        <div class="card-footer text-center">
          <a href="index.php" class="btn btn-primary">Kembali ke Beranda</a>
          <p class="text-muted mt-3">Silakan hubungi admin untuk konfirmasi pembayaran.</p>
        </div>
      </div>
    </div>
  </div>
</div>

<?php
} else {
    echo "<div class='container my-5'><div class='alert alert-danger'>Pesanan tidak ditemukan.</div></div>";
}

include 'templates/footer.php';
?>
