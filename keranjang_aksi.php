<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Pastikan keranjang ada di session
if (!isset($_SESSION['keranjang'])) {
    $_SESSION['keranjang'] = [];
}

// Ambil aksi dan data dari form
$action = $_POST['action'] ?? $_GET['action'] ?? null;
$jenis_pisang = $_POST['jenis_pisang'] ?? null;
$jumlah = (int)($_POST['jumlah'] ?? 1);
$harga = (float)($_POST['harga'] ?? 0);

switch ($action) {
    case 'add':
        if ($jenis_pisang && $jumlah > 0 && $harga > 0) {
            // Cek apakah produk sudah ada di keranjang
            $product_exists = false;
            foreach ($_SESSION['keranjang'] as $key => $item) {
                if ($item['jenis_pisang'] === $jenis_pisang) {
                    // Jika ada, tambahkan jumlahnya
                    $_SESSION['keranjang'][$key]['jumlah'] += $jumlah;
                    $product_exists = true;
                    break;
                }
            }

            // Jika produk belum ada, tambahkan sebagai item baru
            if (!$product_exists) {
                $_SESSION['keranjang'][] = [
                    'jenis_pisang' => $jenis_pisang,
                    'jumlah' => $jumlah,
                    'harga' => $harga
                ];
            }
            $_SESSION['message'] = 'Produk berhasil ditambahkan ke keranjang.';
        }
        break;

    case 'update':
        $updates = $_POST['updates'] ?? [];
        foreach ($updates as $key => $new_jumlah) {
            if (isset($_SESSION['keranjang'][$key])) {
                $_SESSION['keranjang'][$key]['jumlah'] = (int)$new_jumlah;
            }
        }
        $_SESSION['message'] = 'Keranjang berhasil diperbarui.';
        break;

    case 'remove':
        $key_to_remove = $_GET['key'] ?? null;
        if ($key_to_remove !== null && isset($_SESSION['keranjang'][$key_to_remove])) {
            unset($_SESSION['keranjang'][$key_to_remove]);
            // Re-index array to prevent issues
            $_SESSION['keranjang'] = array_values($_SESSION['keranjang']);
            $_SESSION['message'] = 'Produk berhasil dihapus dari keranjang.';
        }
        break;

    case 'clear':
        $_SESSION['keranjang'] = [];
        $_SESSION['message'] = 'Keranjang telah dikosongkan.';
        break;
}

// Redirect kembali ke halaman sebelumnya atau ke halaman keranjang
$redirect_url = $_POST['redirect_to'] ?? 'keranjang.php';
header("Location: " . $redirect_url);
exit();
