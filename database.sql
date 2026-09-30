-- Database Dapur Tyas by Techno Garage
CREATE DATABASE IF NOT EXISTS `dapur_tyas` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `dapur_tyas`;

-- 1. Tabel Menu
CREATE TABLE IF NOT EXISTS `tabel_menu` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `nama_menu` VARCHAR(150) NOT NULL,
  `kategori` VARCHAR(50) NOT NULL,
  `harga` INT NOT NULL,
  `deskripsi` TEXT NULL,
  `foto` VARCHAR(255) NULL,
  `tag` VARCHAR(50) NULL,
  `tersedia` TINYINT(1) DEFAULT 1,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- 2. Tabel Pesanan (Header)
CREATE TABLE IF NOT EXISTS `tabel_pesanan` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `kode_pesanan` VARCHAR(30) NOT NULL UNIQUE,
  `nomor_meja` VARCHAR(20) NOT NULL,
  `nama_pelanggan` VARCHAR(100) NULL DEFAULT 'Pelanggan',
  `catatan` TEXT NULL,
  `subtotal` INT NOT NULL DEFAULT 0,
  `pajak` INT NOT NULL DEFAULT 0,
  `total_bayar` INT NOT NULL DEFAULT 0,
  `uang_diterima` INT DEFAULT 0,
  `uang_kembali` INT DEFAULT 0,
  `status_pesanan` ENUM('menunggu_kasir', 'dimasak_dapur', 'siap_saji', 'selesai', 'batal') NOT NULL DEFAULT 'menunggu_kasir',
  `status_bayar` ENUM('belum_bayar', 'lunas') NOT NULL DEFAULT 'belum_bayar',
  `metode_bayar` VARCHAR(50) DEFAULT 'Kasir',
  `diterima_kasir_oleh` VARCHAR(100) NULL,
  `peringatan_keluar` TINYINT(1) DEFAULT 0,
  `waktu_pesan` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `waktu_update` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- 3. Tabel Detail Pesanan (Items)
CREATE TABLE IF NOT EXISTS `tabel_detail_pesanan` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `pesanan_id` INT NOT NULL,
  `menu_id` INT NOT NULL,
  `nama_menu` VARCHAR(150) NOT NULL,
  `harga_satuan` INT NOT NULL,
  `jumlah` INT NOT NULL,
  `subtotal` INT NOT NULL,
  FOREIGN KEY (`pesanan_id`) REFERENCES `tabel_pesanan`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB;

-- 4. Tabel Staf / Karyawan (Admin & Kasir)
CREATE TABLE IF NOT EXISTS `tabel_staf` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `username` VARCHAR(50) NOT NULL UNIQUE,
  `password` VARCHAR(255) NOT NULL,
  `nama_lengkap` VARCHAR(100) NOT NULL,
  `role` ENUM('admin', 'kasir', 'dapur') NOT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- 5. Tabel Meja Restoran (Dikelola oleh Admin)
CREATE TABLE IF NOT EXISTS `tabel_meja` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `nomor_meja` VARCHAR(20) NOT NULL UNIQUE,
  `nama_area` VARCHAR(50) DEFAULT 'Utama',
  `kapasitas` INT DEFAULT 4,
  `status` ENUM('tersedia', 'terisi', 'reservasi') DEFAULT 'tersedia',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Masukkan Data Staf Awal (Password default admin123tyas dan kasir123tyas)
INSERT INTO `tabel_staf` (`username`, `password`, `nama_lengkap`, `role`) VALUES
('admin', '$2y$10$wO35h271G3l.4GZ1zJ59eOMZ2YlG4U5c9pkm8K3F9w9mZ/8J6sDqu', 'Administrator Tyas', 'admin'),
('kasir', '$2y$10$f48j9nN7j88Qe4z1E9/N2uXh.kYc7D2/c7K6u4k5i7zY5c/8d4z6e', 'Kasir Front Office', 'kasir')
ON DUPLICATE KEY UPDATE `nama_lengkap` = VALUES(`nama_lengkap`);

-- Masukkan Menu Awal Dapur Tyas
INSERT INTO `tabel_menu` (`id`, `nama_menu`, `kategori`, `harga`, `deskripsi`, `foto`, `tag`, `tersedia`) VALUES
(1, 'Salad Buah Premium Dapur Tyas', 'Salad & Sehat', 25000, 'Kombinasi apel fuji, pir madu, melon, anggur merah dengan saus yoghurt keju creamy segar.', 'https://images.unsplash.com/photo-1519996529931-28324d5a630e?w=600&auto=format&fit=crop&q=80', 'Favorit', 1),
(2, 'Nasi Bakar Cumi Cabe Ijo', 'Makanan Utama', 28000, 'Nasi gurih beraroma daun pisang bakar dengan isian cumi asin empuk dan sambal cabe ijo khas Tyas.', 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?w=600&auto=format&fit=crop&q=80', 'Best Seller', 1),
(3, 'Ceker Mercon Dapur Tyas (Pedas Nampol)', 'Jajanan & Pedas', 22000, 'Ceker ayam empuk tanpa tulang lumer berbalut bumbu cabai rawit pedas gurih nagih seperti mie gacoan.', 'https://images.unsplash.com/photo-1563379091339-03b21ab4a4f8?w=600&auto=format&fit=crop&q=80', 'Pedas Level 1-5', 1),
(4, 'Ayam Geprek Sambal Matah Kecombrang', 'Makanan Utama', 27000, 'Ayam crispy fillet renyah disajikan dengan sambal matah segar harum kecombrang dan lalapan segar.', 'https://images.unsplash.com/photo-1626082927389-6cd097cdc6ec?w=600&auto=format&fit=crop&q=80', 'Rekomendasi', 1),
(5, 'Soto Ayam Kampung Segar', 'Makanan Utama', 24000, 'Kuah kaldu kuning bening segar dengan suwiran ayam kampung, soun, tauge, kol, koya dan perasan jeruk nipis.', 'https://images.unsplash.com/photo-1572656631137-7935297eff55?w=600&auto=format&fit=crop&q=80', 'Kuah Segar', 1),
(6, 'Dimsum Ayam Udang Dapur Tyas (4 pcs)', 'Jajanan & Pedas', 18000, 'Dimsum homemade daging ayam juicy dan udang segar disajikan hangat dengan chili oil pedas gurih.', 'https://images.unsplash.com/photo-1496116218417-1a781b1c416c?w=600&auto=format&fit=crop&q=80', 'Snack', 1),
(7, 'Pangsit Goreng Renyah Tyas (5 pcs)', 'Jajanan & Pedas', 16000, 'Pangsit goreng isi olahan ayam renyah keemasan dilengkapi saus asam manis pedas khas resto modern.', 'https://images.unsplash.com/photo-1541696432-82c6da8ce7bf?w=600&auto=format&fit=crop&q=80', 'Snack', 1),
(8, 'Es Cincau Gula Aren Susu', 'Minuman', 12000, 'Cincau hijau kenyal segar berpadu dengan gurihnya susu evaporasi dan manis legit gula aren murni.', 'https://images.unsplash.com/photo-1556881286-fc6915169721?w=600&auto=format&fit=crop&q=80', 'Dingin', 1),
(9, 'Es Lemon Tea Sereh Madu Segar', 'Minuman', 14000, 'Perasan lemon segar, seduhan teh wangi, batang serai dan madu alami yang sangat menyehatkan tubuh.', 'https://images.unsplash.com/photo-1513558161293-cdaf765ed2fd?w=600&auto=format&fit=crop&q=80', 'Sehat', 1),
(10, 'Infused Water Chia Seeds & Mint', 'Minuman', 10000, 'Air mineral segar dengan irisan mentimun, daun mint aromatik, lemon dan biji chia kaya serat.', 'https://images.unsplash.com/photo-1559839914-17aae19cec71?w=600&auto=format&fit=crop&q=80', 'Detox', 1)
ON DUPLICATE KEY UPDATE `nama_menu` = VALUES(`nama_menu`);
