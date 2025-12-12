-- Perintah ini untuk memperbarui struktur database Anda.
-- Jalankan ini di phpMyAdmin atau tools database lainnya.

-- 1. Menambahkan kolom `nama` dan `alamat` ke tabel `users`
-- Ini digunakan untuk menyimpan data lengkap pengguna saat registrasi.
ALTER TABLE `users`
ADD `nama` VARCHAR(255) NOT NULL AFTER `password`,
ADD `alamat` TEXT NOT NULL AFTER `nama`;

-- 2. Menambahkan kolom `id_user` ke tabel `pesanan`
-- Ini penting untuk menghubungkan setiap pesanan dengan pengguna yang membuatnya.
ALTER TABLE `pesanan`
ADD `id_user` INT(11) NOT NULL AFTER `id_pesanan`;
