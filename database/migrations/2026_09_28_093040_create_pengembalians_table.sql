CREATE TABLE IF NOT EXISTS `pengembalian` (
    `id_pengembalian`      INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `id_peminjaman`        INT NOT NULL,
    `tanggal_pengembalian` DATETIME NOT NULL,
    `terlambat_hari`       INT UNSIGNED DEFAULT 0,
    `denda`                INT DEFAULT 0,
    `kondisi_barang`       VARCHAR(100) DEFAULT 'Baik',
    `catatan`              TEXT NULL,
    `created_at`           DATETIME NULL,
    `updated_at`           DATETIME NULL,

    CONSTRAINT `fk_pengembalian_peminjaman` 
        FOREIGN KEY (`id_peminjaman`) REFERENCES `peminjaman` (`id_peminjaman`) 
        ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;