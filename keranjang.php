<?php
include 'templates/header.php';

// Ambil item dari session
$keranjang = $_SESSION['keranjang'] ?? [];

$total_harga = 0;
foreach ($keranjang as $item) {
    $total_harga += $item['harga'] * $item['jumlah'];
}
?>

<div class="container my-5">
    <h1 class="mb-4">Keranjang Belanja</h1>

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

    <?php if (empty($keranjang)): ?>
        <div class="alert alert-info">Keranjang belanja Anda kosong.</div>
        <a href="index.php" class="btn btn-primary">Mulai Belanja</a>
    <?php else: ?>
        <form action="keranjang_aksi.php" method="POST">
            <input type="hidden" name="action" value="update">
            <div class="table-responsive">
                <table class="table table-bordered align-middle">
                    <thead>
                        <tr>
                            <th>Produk</th>
                            <th class="text-end">Harga Satuan</th>
                            <th class="text-center" style="width: 120px;">Jumlah</th>
                            <th class="text-end">Subtotal</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($keranjang as $key => $item): ?>
                            <tr>
                                <td><?= htmlspecialchars($item['jenis_pisang']) ?></td>
                                <td class="text-end">Rp <?= number_format($item['harga'], 0, ',', '.') ?></td>
                                <td class="text-center">
                                    <input type="number" name="updates[<?= $key ?>]" class="form-control text-center quantity-input" value="<?= $item['jumlah'] ?>" min="1" data-price="<?= $item['harga'] ?>" data-key="<?= $key ?>">
                                </td>
                                <td class="text-end subtotal" id="subtotal-<?= $key ?>">Rp <?= number_format($item['harga'] * $item['jumlah'], 0, ',', '.') ?></td>
                                <td class="text-center">
                                    <a href="keranjang_aksi.php?action=remove&key=<?= $key ?>" class="btn btn-sm btn-danger" title="Hapus item" onclick="return confirm('Yakin ingin menghapus item ini?')"><i class="fas fa-trash"></i></a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="3" class="text-end">Total</th>
                            <th class="text-end" id="grand-total">Rp <?= number_format($total_harga, 0, ',', '.') ?></th>
                            <th></th>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <div class="d-flex justify-content-between align-items-center mt-4">
                <div>
                    <button type="submit" class="btn btn-secondary">Perbarui Keranjang</button>
                    <a href="keranjang_aksi.php?action=clear" class="btn btn-outline-danger" onclick="return confirm('Yakin ingin mengosongkan keranjang?')">Kosongkan Keranjang</a>
                </div>
                <a href="form_pesanan.php" class="btn btn-primary btn-lg">Lanjutkan ke Checkout</a>
            </div>
        </form>
    <?php endif; ?>
</div>

<?php include 'templates/footer.php'; ?>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const quantityInputs = document.querySelectorAll('.quantity-input');
    const grandTotalElement = document.getElementById('grand-total');

    function formatRupiah(number) {
        return 'Rp ' + new Intl.NumberFormat('id-ID').format(number);
    }

    function updateGrandTotal() {
        let grandTotal = 0;
        document.querySelectorAll('.subtotal').forEach(subtotalEl => {
            const subtotalValue = parseInt(subtotalEl.textContent.replace(/[^0-9]/g, ''), 10);
            if (!isNaN(subtotalValue)) {
                grandTotal += subtotalValue;
            }
        });
        grandTotalElement.textContent = formatRupiah(grandTotal);
    }

    quantityInputs.forEach(input => {
        input.addEventListener('input', function() {
            const key = this.dataset.key;
            const price = parseFloat(this.dataset.price);
            const quantity = parseInt(this.value, 10);
            
            const subtotalEl = document.getElementById('subtotal-' + key);

            if (quantity >= 1) {
                const subtotal = price * quantity;
                subtotalEl.textContent = formatRupiah(subtotal);
            } else {
                // Handle case where quantity is invalid (e.g., empty or 0)
                subtotalEl.textContent = formatRupiah(0);
            }
            updateGrandTotal();
        });
    });
});
</script>
