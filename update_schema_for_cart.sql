-- Create detail_pesanan table
CREATE TABLE detail_pesanan (
    id_detail_pesanan INT(11) NOT NULL AUTO_INCREMENT,
    id_pesanan INT(11) NOT NULL,
    jenis_pisang VARCHAR(255) NOT NULL,
    jumlah INT(11) NOT NULL,
    harga_satuan DECIMAL(10, 2) NOT NULL,
    PRIMARY KEY (id_detail_pesanan),
    FOREIGN KEY (id_pesanan) REFERENCES pesanan(id_pesanan) ON DELETE CASCADE
);

-- Modify pesanan table
ALTER TABLE pesanan
ADD COLUMN total_harga DECIMAL(10, 2) NOT NULL DEFAULT 0.00;

-- Drop jenis_pisang and jumlah columns from pesanan table
ALTER TABLE pesanan
DROP COLUMN jenis_pisang,
DROP COLUMN jumlah;
