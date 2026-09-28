CREATE TABLE IF NOT EXISTS `peminjaman` (
    id_peminjaman INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    id_user INT UNSIGNED NOT NULL,
    id_alat INT UNSIGNED NOT NULL,
    jumlah INT NOT NULL DEFAULT 1,
    tanggal_pinjam DATE NOT NULL,
    tanggal_kembali DATE DEFAULT NULL,
    status ENUM('Pending', 'Disetujui', 'Ditolak', 'Dipinjam', 'Dikembalikan') DEFAULT 'Pending',
    denda INT DEFAULT 0,
    created_at DATETIME NULL,
    updated_at DATETIME NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;