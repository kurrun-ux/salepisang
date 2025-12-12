<?php
session_start();

// Force a no-cache policy
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

if (!isset($_SESSION['username'])) {
  header('Location: login.php');
  exit();
}

include 'templates/header.php';

if (isset($_GET['action']) && isset($_GET['id'])) {
  $action = $_GET['action'];
  $id = (int)$_GET['id'];

  if ($action == 'terima') {
    $query = "UPDATE pesanan SET status='diterima' WHERE id_pesanan=$id";
    mysqli_query($koneksi, $query);
  } elseif ($action == 'tolak') {
    $query = "UPDATE pesanan SET status='ditolak' WHERE id_pesanan=$id";
    mysqli_query($koneksi, $query);
  } elseif ($action == 'hapus') {
    $delete_query = "DELETE FROM pesanan WHERE id_pesanan=$id";
    mysqli_query($koneksi, $delete_query);

    // After deletion, reset AUTO_INCREMENT to ensure the next ID is sequential from the current max.
    // This does not fill gaps, but ensures the next ID is MAX(id_pesanan) + 1 of existing IDs.
    $max_id_query = "SELECT MAX(id_pesanan) AS max_id FROM pesanan";
    $max_id_result = mysqli_query($koneksi, $max_id_query);
    $row = mysqli_fetch_assoc($max_id_result);
    $next_auto_increment = ($row['max_id'] !== null) ? $row['max_id'] + 1 : 1;
    $alter_query = "ALTER TABLE pesanan AUTO_INCREMENT = $next_auto_increment";
    mysqli_query($koneksi, $alter_query);
  }
  header('Location: admin.php'); // Redirect to avoid re-execution on refresh
  exit();
}

$query = "SELECT * FROM pesanan ORDER BY id_pesanan DESC";

$result = mysqli_query($koneksi, $query);

?>



<div class="container my-5">

  <div class="row mb-4">

    <div class="col d-flex justify-content-between align-items-center">

      <h1 class="h2">Dashboard Admin</h1>

      <a href="logout.php" class="btn btn-danger rounded-pill"><i class="fas fa-sign-out-alt me-2"></i>Logout</a>

    </div>

  </div>



  <div class="card product-card">

    <div class="card-body">

      <div class="table-responsive">

        <table class="table table-hover align-middle">
          <thead>
            <tr>
              <th>ID</th>
              <th>Tanggal</th>
              <th>Nama</th>
              <th>No. WA</th>
              <th>Total Harga</th>
              <th>Alamat</th>
              <th>Status</th>
              <th class="text-center">Aksi</th>
            </tr>
          </thead>
          <tbody>
            <?php 
            if ($result && mysqli_num_rows($result) > 0):
              while ($row = mysqli_fetch_assoc($result)):
            ?>
                <tr>
                  <td>#<?= $row['id_pesanan']; ?></td>
                  <td><?= date('d M Y', strtotime($row['tanggal_pesan'])); ?></td>
                  <td><?= htmlspecialchars($row['nama']); ?></td>
                  <td><?= htmlspecialchars($row['no_wa'] ?? ''); ?></td>
                  <td>Rp <?= number_format($row['total_harga'], 0, ',', '.'); ?></td>
                  <td><?= htmlspecialchars($row['alamat']); ?></td>
                  <td>
                    <span class="badge bg-<?= $row['status'] == 'diterima' ? 'success' : ($row['status'] == 'ditolak' ? 'danger' : 'warning'); ?>">
                      <?= ucfirst($row['status']); ?>
                    </span>
                  </td>
                  <td class="text-center">
                    <a href="detail_pesanan_lengkap.php?id_pesanan=<?= $row['id_pesanan']; ?>" class="btn btn-sm btn-info" title="Lihat Detail Pesanan"><i class="fas fa-eye"></i></a>
                    <?php if($row['status'] == 'pending'): ?>
                      <a href="admin.php?action=terima&id=<?= $row['id_pesanan']; ?>" class="btn btn-sm btn-success" title="Terima Pesanan"><i class="fas fa-check"></i></a>
                      <a href="admin.php?action=tolak&id=<?= $row['id_pesanan']; ?>" class="btn btn-sm btn-warning" title="Tolak Pesanan"><i class="fas fa-times"></i></a>
                    <?php endif; ?>
                    <a href="admin.php?action=hapus&id=<?= $row['id_pesanan']; ?>" class="btn btn-sm btn-danger" title="Hapus Pesanan" onclick="return confirm('Yakin ingin menghapus pesanan ini?')"><i class="fas fa-trash"></i></a>
                  </td>
                </tr>
              <?php 
              endwhile;
            else:
              $error_message = $result ? "Belum ada pesanan." : "Gagal memuat data pesanan. Error: " . mysqli_error($koneksi);
            ?>
              <tr>
                <td colspan="8" class="text-center"><?= $error_message; ?></td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<?php include 'templates/footer.php'; ?>