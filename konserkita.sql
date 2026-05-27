-- ============================================================
--  KONSERKITA - Database Schema
--  Import file ini di phpMyAdmin: Import > pilih file ini
-- ============================================================

DROP DATABASE IF EXISTS `konserkita`;

CREATE DATABASE `konserkita`
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE `konserkita`;

-- ------------------------------------------------------------
-- Tabel: users
-- ------------------------------------------------------------
CREATE TABLE `users` (
  `id`         INT UNSIGNED    NOT NULL AUTO_INCREMENT,
  `nama`       VARCHAR(100)    NOT NULL,
  `username`   VARCHAR(50)     NOT NULL UNIQUE,
  `email`      VARCHAR(100)    DEFAULT NULL,
  `password`   VARCHAR(255)    NOT NULL,
  `role`       ENUM('admin','user') NOT NULL DEFAULT 'user',
  `created_at` TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Semua password di bawah adalah plain text, otomatis di-hash saat pertama login
INSERT INTO `users` (`nama`, `username`, `email`, `password`, `role`) VALUES
('Administrator', 'admin', 'admin@konserkita.id', 'admin123', 'admin'),
('Budi Santoso',  'user1', 'budi@email.com',      'admin123', 'user'),
('Siti Rahayu',   'siti',  'siti@email.com',      'admin123', 'user'),
('Andi Wijaya',   'andi',  'andi@email.com',       'admin123', 'user'),
('Dewi Lestari',  'dewi',  'dewi@email.com',       'admin123', 'user');

-- ------------------------------------------------------------
-- Tabel: konser
-- ------------------------------------------------------------
CREATE TABLE `konser` (
  `id`         INT UNSIGNED    NOT NULL AUTO_INCREMENT,
  `nama`       VARCHAR(150)    NOT NULL,
  `kategori`   ENUM('Pop','Rock','Jazz','K-Pop') NOT NULL,
  `kota`       VARCHAR(100)    NOT NULL,
  `tanggal`    DATE            NOT NULL,
  `harga`      INT UNSIGNED    NOT NULL,
  `status`     ENUM('Tersedia','Sisa Sedikit','Sold Out') NOT NULL DEFAULT 'Tersedia',
  `gambar`     VARCHAR(255)    DEFAULT NULL,
  `created_at` TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `konser` (`nama`, `kategori`, `kota`, `tanggal`, `harga`, `status`) VALUES
('Konser Raisa',                   'Pop',   'Gedung Kesenian Cirebon',  '2026-07-10', 500000,   'Tersedia'),
('Konser Tulus',                   'Pop',   'Taman Air Mancur Cirebon', '2026-07-15', 450000,   'Tersedia'),
('Konser Slank',                   'Rock',  'Lapangan Kebumen Cirebon', '2026-07-22', 350000,   'Sisa Sedikit'),
('Konser Noah',                    'Rock',  'GOR Bima Cirebon',         '2026-08-01', 400000,   'Tersedia'),
('Java Jazz Festival',             'Jazz',  'Keraton Kasepuhan Cirebon','2026-08-05', 750000,   'Tersedia'),
('Jazz Night Cirebon',             'Jazz',  'Alun-Alun Kejaksan Cirebon','2026-08-12', 600000,  'Sold Out'),
('BTS Live Concert',               'K-Pop', 'GOR Bima Cirebon',         '2026-08-20', 2000000,  'Sold Out'),
('Blackpink World Tour',           'K-Pop', 'Stadion Bima Cirebon',     '2026-08-28', 1800000,  'Sisa Sedikit'),
('Sheila On 7 "Tunggu Aku Di"',   'Pop',   'Gedung Kesenian Cirebon',  '2026-07-15', 1500000,  'Tersedia'),
('Coldplay Live in Cirebon',       'Pop',   'Stadion Bima Cirebon',     '2026-08-20', 1200000,  'Sisa Sedikit'),
('Dewa 19 All Stars',              'Rock',  'Alun-Alun Kejaksan Cirebon','2026-08-12', 800000,  'Tersedia'),
('Jazz Traffic Festival',          'Jazz',  'Keraton Kanoman Cirebon',  '2026-10-05', 650000,   'Tersedia'),
('Electronic Music Fest',          'Pop',   'Taman Ade Irma Cirebon',   '2026-09-20', 550000,   'Tersedia');

-- ------------------------------------------------------------
-- Tabel: tiket
-- ------------------------------------------------------------
CREATE TABLE `tiket` (
  `id`         INT UNSIGNED    NOT NULL AUTO_INCREMENT,
  `kode`       VARCHAR(20)     NOT NULL UNIQUE,
  `user_id`    INT UNSIGNED    NOT NULL,
  `konser_id`  INT UNSIGNED    NOT NULL,
  `kategori`   ENUM('Regular','VIP','SVIP','Festival') NOT NULL DEFAULT 'Regular',
  `harga`      INT UNSIGNED    NOT NULL,
  `status`     ENUM('Aktif','Menunggu Pembayaran','Dibatalkan') NOT NULL DEFAULT 'Menunggu Pembayaran',
  `created_at` TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`user_id`)   REFERENCES `users`(`id`)  ON DELETE CASCADE,
  FOREIGN KEY (`konser_id`) REFERENCES `konser`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `tiket` (`kode`, `user_id`, `konser_id`, `kategori`, `harga`, `status`) VALUES
('KNSR1234', 2,  9, 'VIP',      1500000, 'Aktif'),
('KNSR5678', 2, 10, 'Festival', 1200000, 'Menunggu Pembayaran'),
('KNSR9012', 3,  1, 'Regular',  500000,  'Aktif'),
('KNSR3456', 4,  5, 'VIP',      750000,  'Aktif'),
('KNSR7890', 5,  7, 'SVIP',     2000000, 'Dibatalkan');
