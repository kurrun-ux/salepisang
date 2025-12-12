<?php 
include 'templates/header.php'; 
?>

<div class="container my-5">
  <div class="row justify-content-center">
    <div class="col-lg-7">
      <div class="card product-card mb-4">
        <div class="card-body p-5">
          <h2 class="card-title text-center mb-4">Cari Detail Pesanan</h2>
          <form onsubmit="window.location.href = 'struk.php?id_pesanan=' + document.getElementById('id_pesanan').value; return false;">
            <div class="mb-4">
              <label for="id_pesanan" class="form-label">Masukkan Nomor Pesanan Anda</label>
              <input type="text" class="form-control form-control-lg" id="id_pesanan" name="id_pesanan" required>
            </div>
            <div class="d-grid">
              <button type="submit" class="btn btn-primary btn-lg">Cari Pesanan</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

<?php include 'templates/footer.php'; ?>
