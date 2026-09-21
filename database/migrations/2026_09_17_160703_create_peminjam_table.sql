-- create_peminjam_table

CREATE TABLE IF NOT EXISTS `peminjam` (
  `id_peminjam` int NOT NULL AUTO_INCREMENT,
  `id_user` int unsigned NOT NULL,
  `nis` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama_peminjam` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `kelas` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id_peminjam`),
  KEY `fk_peminjam_id_user_cd0e1f` (`id_user`),
  CONSTRAINT `fk_peminjam_id_user_cd0e1f` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
