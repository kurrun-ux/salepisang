<?php
session_start();

if (!isset($_SESSION['username'])) {
  header('Location: login.php');
  exit();
}

include 'config/koneksi.php';
include 'templates/header.php';

$pesanan = null;
$detail_pesanan = [];

if (isset($_GET['id_pesanan'])) {
  $id_pesanan = $koneksi->real_escape_string($_GET['id_pesanan']);
  
  // Ambil data pesanan utama
  $query_pesanan = "SELECT * FROM pesanan WHERE id_pesanan = '$id_pesanan'";
  $result_pesanan = mysqli_query($koneksi, $query_pesanan);
  if ($result_pesanan && mysqli_num_rows($result_pesanan) > 0) {
    $pesanan = mysqli_fetch_assoc($result_pesanan);
    
    // Ambil item-item detail pesanan
    $query_detail = "SELECT * FROM detail_pesanan WHERE id_pesanan = '$id_pesanan'";
    $result_detail = mysqli_query($koneksi, $query_detail);
    while ($row = mysqli_fetch_assoc($result_detail)) {
      $detail_pesanan[] = $row;
    }
  }
}
?>

<div class="container my-5">
  <div class="row justify-content-center">
    <div class="col-lg-8">
      <?php if ($pesanan): ?>
      <div class="card product-card">
        <div class="card-header">
          <h2 class="card-title text-center mb-0">Detail Pesanan #<?= htmlspecialchars($pesanan['id_pesanan']) ?></h2>
        </div>
        <div class="card-body p-4">
          
          <h5 class="mb-3">Informasi Pelanggan</h5>
          <ul class="list-group list-group-flush mb-4">
            <li class="list-group-item"><strong>Nama:</strong> <?= htmlspecialchars($pesanan['nama']) ?></li>
            <li class="list-group-item"><strong>Nomor WhatsApp:</strong> <?= htmlspecialchars($pesanan['no_wa'] ?? '') ?></li>
            <li class="list-group-item"><strong>Alamat:</strong> <?= nl2br(htmlspecialchars($pesanan['alamat'])) ?></li>
            <li class="list-group-item"><strong>Tanggal Pesan:</strong> <?= date('d F Y', strtotime($pesanan['tanggal_pesan'])) ?></li>
            <li class="list-group-item"><strong>Status:</strong> <span class="badge bg-info text-dark"><?= htmlspecialchars($pesanan['status']) ?></span></li>
          </ul>

          <h5 class="mb-3">Item Pesanan</h5>
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

          <div class="text-center mt-4">
            <a href="admin.php" class="btn btn-secondary">Kembali ke Dashboard</a>
          </div>
        </div>
      </div>
      <?php else: // No id_pesanan provided or found ?>
        <div class="alert alert-warning" role="alert">
          Nomor pesanan tidak valid atau tidak ditemukan.
        </div>
      <?php endif; ?>
    </div>
  </div>
</div>

<?php include 'templates/footer.php'; ?>