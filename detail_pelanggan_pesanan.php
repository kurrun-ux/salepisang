<?php
include 'config/koneksi.php';
include 'templates/header.php';

if (isset($_GET['id_pesanan'])) {
    $id_pesanan = $_GET['id_pesanan'];

    // Ambil data pesanan untuk mendapatkan informasi pelanggan
    $query_pesanan = mysqli_query($koneksi, "SELECT nama_pelanggan, alamat, no_hp FROM pesanan WHERE id_pesanan='$id_pesanan'");
    $data_pesanan = mysqli_fetch_array($query_pesanan);

    if ($data_pesanan) {
?>

<div class="container mt-5">
    <div class="card">
        <div class="card-header text-center">
            <h2>Detail Informasi Pelanggan</h2>
        </div>
        <div class="card-body">
            <p><strong>ID Pesanan:</strong> <?php echo $id_pesanan; ?></p>
            <p><strong>Nama Pelanggan:</strong> <?php echo $data_pesanan['nama_pelanggan']; ?></p>
            <p><strong>Alamat:</strong> <?php echo $data_pesanan['alamat']; ?></p>
            <p><strong>No. HP:</strong> <?php echo $data_pesanan['no_hp']; ?></p>
            <div class="text-center mt-4">
                <a href="struk.php?id_pesanan=<?php echo $id_pesanan; ?>" class="btn btn-secondary">Kembali ke Struk</a>
                <a href="index.php" class="btn btn-primary">Kembali ke Beranda</a>
            </div>
        </div>
    </div>
</div>

<?php
    } else {
        echo "<div class='container mt-5'><div class='alert alert-danger'>Data pesanan tidak ditemukan.</div></div>";
    }
} else {
    echo "<div class='container mt-5'><div class='alert alert-danger'>ID Pesanan tidak ditemukan.</div></div>";
}
include 'templates/footer.php';
?>