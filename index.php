<?php include 'templates/header.php'; ?>

<!-- Hero Section -->
<div class="hero-section text-center">
  <div class="container">
    <h1 class="display-4">Toko salee</h1>
    <p class="lead">Dibuat dari pisang pilihan dengan resep tradisional yang terjaga.</p>
    <a href="#produk" class="btn btn-primary btn-lg mt-3">Lihat Produk</a>
  </div>
</div>

<div class="container mt-5">

  <?php 
  if (isset($_SESSION['message'])):
  ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
      <?= $_SESSION['message']; ?>
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  <?php 
    unset($_SESSION['message']);
  endif;
  ?>

  <!-- Features Section -->
  <div class="row text-center features-section mb-5">
    <div class="col-md-4">
      <div class="feature-icon mb-3"><i class="fas fa-leaf"></i></div>
      <h4>1900% Alami</h4>
      <p>Tanpa tambahan pemanis atau bahan pengawet.</p>
    </div>
    <div class="col-md-4">
      <div class="feature-icon mb-3"><i class="fas fa-truck"></i></div>
      <h4>Pengiriman Cepat</h4>
      <p>Pesanan Anda kami antar langsung ke depan pintu.</p>
    </div>
    <div class="col-md-4">
      <div class="feature-icon mb-3"><i class="fas fa-star"></i></div>
      <h4>Rasa Terjamin</h4>
      <p>Kualitas rasa yang konsisten dan memuaskan.</p>
    </div>
  </div>

  <!-- Produk Section -->
  <div id="produk" class="row">
    <h2 class="text-center mb-5 display-5 fw-bold">Produk Kami</h2>
    
    <?php
    $products = [
        ['name' => 'Sale Pisang Kepok', 'price' => 25000, 'img' => 'sale1.jpg'],
        ['name' => 'Sale Pisang Raja', 'price' => 30000, 'img' => 'sale2.jpg'],
        ['name' => 'Sale Pisang Ambon', 'price' => 28000, 'img' => 'sale3.jpg'],
        ['name' => 'Sale Pisang Mas', 'price' => 22000, 'img' => 'sale4.jpg'],
        ['name' => 'Sale Pisang Tanduk', 'price' => 35000, 'img' => 'sale5.jpg'],
        ['name' => 'Sale Pisang Susu', 'price' => 27000, 'img' => 'sale6.jpg']
    ];

    foreach ($products as $product):
    ?>
    <div class="col-lg-4 col-md-6 mb-4">
      <div class="card product-card h-100">
        <img src="assets/img/<?= $product['img']; ?>" class="card-img-top" alt="<?= $product['name']; ?>">
        <div class="card-body d-flex flex-column">
          <h5 class="card-title"><?= $product['name']; ?></h5>
          <p class="card-text">Rp <?= number_format($product['price'], 0, ',', '.'); ?> / kg</p>
          <form action="keranjang_aksi.php" method="POST" class="mt-auto">
            <input type="hidden" name="action" value="add">
            <input type="hidden" name="jenis_pisang" value="<?= $product['name']; ?>">
            <input type="hidden" name="harga" value="<?= $product['price']; ?>">
            <input type="hidden" name="redirect_to" value="index.php#produk">
            <div class="input-group">
              <input type="number" class="form-control" name="jumlah" value="1" min="1">
              <button type="submit" class="btn btn-primary"><i class="fas fa-cart-plus"></i></button>
            </div>
          </form>
        </div>
      </div>
    </div>
    <?php endforeach; ?>

  </div>
</div>

<?php include 'templates/footer.php'; ?>