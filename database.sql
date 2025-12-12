CREATE DATABASE salepisang;

USE salepisang;

CREATE TABLE users (
  id_user INT(11) NOT NULL AUTO_INCREMENT,
  username VARCHAR(255) NOT NULL,
  password VARCHAR(255) NOT NULL,
  PRIMARY KEY (id_user)
);

CREATE TABLE pesanan (
  id_pesanan INT(11) NOT NULL AUTO_INCREMENT,
  nama VARCHAR(255) NOT NULL,
  jenis_pisang VARCHAR(255) NOT NULL,
  jumlah INT(11) NOT NULL,
  alamat TEXT NOT NULL,
  tanggal_pesan DATE NOT NULL,
  status VARCHAR(255) NOT NULL,
  PRIMARY KEY (id_pesanan)
);

INSERT INTO users (username, password) VALUES ('admin', '21232f297a57a5a743894a0e4a801fc3');