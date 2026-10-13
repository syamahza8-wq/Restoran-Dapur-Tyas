-- =====================================================================
-- DATABASE DAPUR TYAS - READY FOR INFINITYFREE / CPANEL PHPMYADMIN
-- Generated: 2026-10-13 04:16:36
-- Keterangan: File SQL ini aman diimpor ke phpMyAdmin InfinityFree
-- (Tanpa CREATE DATABASE / USE database yang diblokir oleh shared hosting)
-- =====================================================================

SET FOREIGN_KEY_CHECKS = 0;
SET SQL_MODE = 'NO_AUTO_VALUE_ON_ZERO';
SET time_zone = '+07:00';

-- --------------------------------------------------------
-- Struktur Tabel `tabel_staf`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `tabel_staf`;
CREATE TABLE `tabel_staf` (
  `id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama_lengkap` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` enum('admin','kasir','dapur') COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data untuk tabel `tabel_staf` (2 baris)
INSERT INTO `tabel_staf` (`id`, `username`, `password`, `nama_lengkap`, `role`, `created_at`) VALUES
('1', 'admin', '$2y$10$wHhWmTBsy5Fsf/C1Tu2Si.5NwGBVg.R4.GBGsvSgRdhOzTQGUmqru', 'Administrator Tyas', 'admin', '2026-09-22 09:51:38'),
('2', 'kasir', '$2y$10$Ptx/1JiRfH2ojwndGvQ/m.4c3dDN3KwdiL9EPEw01BSlIGdQsDC2u', 'Kasir Front Office', 'kasir', '2026-09-22 09:51:38');

-- --------------------------------------------------------
-- Struktur Tabel `tabel_meja`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `tabel_meja`;
CREATE TABLE `tabel_meja` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nomor_meja` varchar(20) NOT NULL,
  `nama_area` varchar(50) DEFAULT 'Utama',
  `kapasitas` int DEFAULT '4',
  `status` enum('tersedia','terisi','reservasi') DEFAULT 'tersedia',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nomor_meja` (`nomor_meja`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data untuk tabel `tabel_meja` (12 baris)
INSERT INTO `tabel_meja` (`id`, `nomor_meja`, `nama_area`, `kapasitas`, `status`, `created_at`) VALUES
('1', '01', 'Indoor AC', '4', 'tersedia', '2026-09-22 12:03:06'),
('2', '02', 'Indoor AC', '4', 'tersedia', '2026-09-22 12:03:06'),
('3', '03', 'Indoor AC', '4', 'tersedia', '2026-09-22 12:03:06'),
('4', '04', 'Indoor AC', '4', 'tersedia', '2026-09-22 12:03:06'),
('5', '05', 'Indoor AC', '4', 'tersedia', '2026-09-22 12:03:06'),
('6', '06', 'Indoor AC', '4', 'tersedia', '2026-09-22 12:03:06'),
('7', '07', 'Outdoor Garden', '4', 'tersedia', '2026-09-22 12:03:06'),
('8', '08', 'Outdoor Garden', '4', 'tersedia', '2026-09-22 12:03:06'),
('9', '09', 'Outdoor Garden', '4', 'tersedia', '2026-09-22 12:03:06'),
('10', '10', 'Outdoor Garden', '4', 'tersedia', '2026-09-22 12:03:06'),
('11', '11', 'Outdoor Garden', '4', 'tersedia', '2026-09-22 12:03:06'),
('12', '12', 'Outdoor Garden', '4', 'tersedia', '2026-09-22 12:03:06');

-- --------------------------------------------------------
-- Struktur Tabel `tabel_menu`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `tabel_menu`;
CREATE TABLE `tabel_menu` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nama_menu` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `kategori` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `harga` int NOT NULL,
  `deskripsi` text COLLATE utf8mb4_unicode_ci,
  `foto` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tag` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tersedia` tinyint(1) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=264 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data untuk tabel `tabel_menu` (263 baris)
INSERT INTO `tabel_menu` (`id`, `nama_menu`, `kategori`, `harga`, `deskripsi`, `foto`, `tag`, `tersedia`, `created_at`) VALUES
('1', 'Nasi Bakar Cumi Cabe Ijo', 'Makanan Utama', '28000', 'Nasi gurih daun pisang bakar dengan cumi asin cabe ijo khas Tyas.', 'assets/menu/wiki_nasi_bakar.jpg', 'Best Seller', '1', '2026-09-23 08:08:24'),
('2', 'Nasi Bakar Ayam Suwir Kemangi', 'Makanan Utama', '26000', 'Ayam suwir bumbu rica kemangi gurih harum dibakar sempurna.', 'assets/menu/wiki_nasi_bakar.jpg', 'Favorit', '1', '2026-09-23 08:08:24'),
('3', 'Nasi Bakar Tongkol Suwir Pedas', 'Makanan Utama', '25000', 'Tongkol asap suwir berbumbu sambal balado rempah wangi.', 'assets/menu/wiki_nasi_bakar.jpg', 'Pedas', '1', '2026-09-23 08:08:24'),
('4', 'Nasi Bakar Jamur Merang Gurih', 'Makanan Utama', '22000', 'Pilihan sehat jamur tiram dan merang tumis bumbu kuning.', 'assets/menu/wiki_nasi_bakar.jpg', 'Sehat', '1', '2026-09-23 08:08:24'),
('5', 'Nasi Bakar Teri Medan Petai', 'Makanan Utama', '27000', 'Teri medan renyah berpadu irisan petai segar daun kemangi.', 'assets/menu/wiki_nasi_bakar.jpg', 'Rekomendasi', '1', '2026-09-23 08:08:24'),
('6', 'Nasi Goreng Spesial Dapur Tyas', 'Makanan Utama', '28000', 'Nasi goreng racikan bumbu rahasia dengan telur, ayam suwir & sosis.', 'assets/menu/wiki_nasi_goreng.jpg', 'Favorit', '1', '2026-09-23 08:08:24'),
('7', 'Nasi Goreng Seafood XO', 'Makanan Utama', '34000', 'Nasi goreng harum saus XO dengan udang segar dan cumi empuk.', 'assets/menu/wiki_nasi_goreng.jpg', 'Chef Pick', '1', '2026-09-23 08:08:24'),
('8', 'Nasi Goreng Kebuli Kambing', 'Makanan Utama', '38000', 'Kaya rempah kapulaga, cengkeh dengan potongan daging kambing empuk.', 'assets/menu/wiki_nasi_kebuli.jpg', 'Spesial', '1', '2026-09-23 08:08:24'),
('9', 'Nasi Goreng Cabe Ijo Teri', 'Makanan Utama', '27000', 'Pedas gurih cabai hijau tumbuk dengan taburan teri renyah.', 'assets/menu/wiki_nasi_goreng.jpg', 'Pedas', '1', '2026-09-23 08:08:24'),
('10', 'Nasi Goreng Gila Pedas Manis', 'Makanan Utama', '29000', 'Topping melimpah sosis, bakso, telur orek dan tumisan sayur.', 'assets/menu/wiki_nasi_goreng.jpg', 'Favorit', '1', '2026-09-23 08:08:24'),
('11', 'Nasi Goreng Kampung Terasi', 'Makanan Utama', '25000', 'Aroma terasi bakar tradisional dengan telur ceplok dan kerupuk.', 'assets/menu/wiki_nasi_goreng.jpg', 'Klasik', '1', '2026-09-23 08:08:24'),
('12', 'Nasi Liwet Komplit Solo', 'Makanan Utama', '32000', 'Nasi gurih santan, sayur labu siam, suwir ayam areh dan telur pindang.', 'assets/menu/wiki_nasi_liwet.jpg', 'Tradisional', '1', '2026-09-23 08:08:24'),
('13', 'Nasi Uduk Betawi Komplit', 'Makanan Utama', '30000', 'Nasi gurih wangi dengan semur tahu, bihun goreng, tempe orek & ayam.', 'assets/menu/wiki_nasi_uduk.jpg', 'Sarapan & Makan', '1', '2026-09-23 08:08:24'),
('14', 'Nasi Kuning Gurih Tyas', 'Makanan Utama', '28000', 'Kuning alami kunyit dengan abon sapi, telur dadar iris & perkedel.', 'assets/menu/wiki_nasi_kuning.jpg', 'Gurih', '1', '2026-09-23 08:08:24'),
('15', 'Nasi Timbel Parahyangan', 'Makanan Utama', '35000', 'Nasi timbel daun pisang, ayam goreng lengkuas, sayur asem & sambal terasi.', 'assets/menu/wiki_nasi_timbel.jpg', 'Komplit', '1', '2026-09-23 08:08:24'),
('16', 'Nasi Gudeg Komplit Jogja', 'Makanan Utama', '33000', 'Nangka muda manis gurih, krecek pedas, ayam opor dan telur bacem.', 'assets/menu/wiki_gudeg.jpg', 'Tradisional', '1', '2026-09-23 08:08:24'),
('17', 'Nasi Padang Rendang Daging', 'Makanan Utama', '36000', 'Daging rendang empuk bumbu rempah hitam, daun singkong dan gulai kapau.', 'assets/menu/wiki_nasi_padang.jpg', 'Best Seller', '1', '2026-09-23 08:08:24'),
('18', 'Nasi Campur Bali Halal', 'Makanan Utama', '35000', 'Ayam betutu suwir, sate lilit ayam, jukut urap dan sambal matah.', 'assets/menu/wiki_nasi_campur.jpg', 'Khas Bali', '1', '2026-09-23 08:08:24'),
('19', 'Nasi Rawon Daging Sapi Surabaya', 'Makanan Utama', '36000', 'Kuah kluwek hitam gurih dengan daging sapi sengkel empuk & sambal tauge.', 'assets/menu/wiki_rawon.jpg', 'Kuah Hitam', '1', '2026-09-23 08:08:24'),
('20', 'Nasi Tutug Oncom Tasik', 'Makanan Utama', '27000', 'Nasi tumbuk oncom kencur harum disajikan dengan ayam goreng & lalap.', 'assets/menu/photo-1512058564366-18510be2db19.jpg', 'Khas Sunda', '1', '2026-09-23 08:08:24');
INSERT INTO `tabel_menu` (`id`, `nama_menu`, `kategori`, `harga`, `deskripsi`, `foto`, `tag`, `tersedia`, `created_at`) VALUES
('21', 'Ayam Bakar Madu Spesial Tyas', 'Ayam & Unggas', '29000', 'Ayam bakar legit berbalut madu hutan murni dengan olesan kecap rempah.', 'assets/menu/wiki_ayam_bakar.jpg', 'Best Seller', '1', '2026-09-23 08:08:24'),
('22', 'Ayam Goreng Lengkuas Gurih', 'Ayam & Unggas', '28000', 'Ayam ungkep bumbu kuning berlimpah taburan serundeng lengkuas renyah.', 'assets/menu/wiki_ayam_goreng.jpg', 'Favorit', '1', '2026-09-23 08:08:24'),
('23', 'Ayam Bakar Taliwang Pedas', 'Ayam & Unggas', '31000', 'Bumbu cabai merah bakar pedas khas Lombok dengan perasan jeruk limau.', 'assets/menu/wiki_ayam_bakar.jpg', 'Pedas', '1', '2026-09-23 08:08:24'),
('24', 'Ayam Betutu Kuah Khas Gilimanuk', 'Ayam & Unggas', '32000', 'Aroma bumbu genep Bali melimpah kuah rempah hangat berkhasiat.', 'assets/menu/wiki_ayam_betutu.jpg', 'Rempah', '1', '2026-09-23 08:08:24'),
('25', 'Ayam Kalasan Manis Gurih', 'Ayam & Unggas', '28000', 'Ayam ungkep air kelapa gurih manis legit digoreng renyah.', 'assets/menu/wiki_ayam_goreng.jpg', 'Klasik', '1', '2026-09-23 08:08:24'),
('26', 'Ayam Rica-Rica Pedas Kemangi', 'Ayam & Unggas', '29000', 'Potongan ayam empuk berbalut cabai rawit pedas dan daun kemangi segar.', 'assets/menu/wiki_rica-rica.jpg', 'Pedas Mantap', '1', '2026-09-23 08:08:24'),
('27', 'Ayam Woku Belanga Manado', 'Ayam & Unggas', '30000', 'Kuah kuning segar aroma daun pandan, kunyit, daun jeruk dan kemangi.', 'assets/menu/wiki_woku.jpg', 'Khas Manado', '1', '2026-09-23 08:08:24'),
('28', 'Ayam Tangkap Khas Aceh', 'Ayam & Unggas', '32000', 'Ayam potong renyah dibalut tumpukan daun koja dan daun pandan renyah.', 'assets/menu/wiki_ayam_tangkap.jpg', 'Unik', '1', '2026-09-23 08:08:24'),
('29', 'Ayam Goreng Mentega Gurih Manis', 'Ayam & Unggas', '30000', 'Ayam renyah berlumur saus mentega gurih, kecap inggris dan bawang bombay.', 'assets/menu/wiki_ayam_goreng.jpg', 'Anak & Dewasa', '1', '2026-09-23 08:08:24'),
('30', 'Ayam Kuluyuk Asam Manis', 'Ayam & Unggas', '30000', 'Fillet ayam krispi berbalut saus asam manis dengan irisan nanas segar.', 'assets/menu/wiki_ayam_goreng.jpg', 'Segar', '1', '2026-09-23 08:08:24'),
('31', 'Bebek Goreng Kremes Renyah', 'Ayam & Unggas', '36000', 'Bebek muda empuk tidak amis dengan taburan kremesan gurih asin.', 'assets/menu/wiki_bebek_goreng.jpg', 'Best Seller', '1', '2026-09-23 08:08:24'),
('32', 'Bebek Bakar Bumbu Rujak', 'Ayam & Unggas', '37000', 'Bebek bakar saus santan cabai bumbu rujak pedas legit gurih.', 'assets/menu/wiki_bebek_goreng.jpg', 'Favorit', '1', '2026-09-23 08:08:24'),
('33', 'Bebek Sinjay Madura Sambal Mangga', 'Ayam & Unggas', '38000', 'Bebek goreng renyah bumbu kuning dengan sambal pencit mangga muda.', 'assets/menu/wiki_bebek_goreng.jpg', 'Pedas Segar', '1', '2026-09-23 08:08:24'),
('34', 'Bebek Betutu Panggang', 'Ayam & Unggas', '39000', 'Bebek panggang empuk berbalut rempah genep khas Gianyar.', 'assets/menu/photo-1625944230945-1b7dd3b949ab.jpg', 'Spesial', '1', '2026-09-23 08:08:24'),
('35', 'Ayam Geprek Sambal Bawang Original', 'Geprek & Sambal', '24000', 'Ayam crispy jumbo digeprek ulekan cabai rawit bawang panas mendidih.', 'assets/menu/wiki_ayam_geprek.jpg', 'Pedas Pol', '1', '2026-09-23 08:08:24'),
('36', 'Ayam Geprek Sambal Matah Kecombrang', 'Geprek & Sambal', '27000', 'Ayam fillet crispy sambal matah harum bunga kecombrang segar.', 'assets/menu/wiki_ayam_geprek.jpg', 'Best Seller', '1', '2026-09-23 08:08:24'),
('37', 'Ayam Geprek Saus Keju Melted', 'Geprek & Sambal', '29000', 'Paduan ayam geprek pedas gurih disiram keju leleh creamy lumer.', 'assets/menu/wiki_ayam_geprek.jpg', 'Favorit', '1', '2026-09-23 08:08:24'),
('38', 'Ceker Mercon Dapur Tyas', 'Geprek & Sambal', '22000', 'Ceker ayam tanpa tulang empuk bumbu cabai rawit pedas nagih.', 'assets/menu/wiki_nasi.jpg', 'Best Seller', '1', '2026-09-23 08:08:24'),
('39', 'Sayap Ayam Mercon Lv 1-5', 'Geprek & Sambal', '25000', 'Sayap ayam crispy berselimut cabai merah membakar lidah.', 'assets/menu/wiki_nasi.jpg', 'Tantangan', '1', '2026-09-23 08:08:24'),
('40', 'Kulit Ayam Krispi Sambal Ijo', 'Geprek & Sambal', '20000', 'Kulit ayam goreng garing kriuk dicocol sambal ijo padang harum.', 'assets/menu/wiki_nasi.jpg', 'Kriuk', '1', '2026-09-23 08:08:24');
INSERT INTO `tabel_menu` (`id`, `nama_menu`, `kategori`, `harga`, `deskripsi`, `foto`, `tag`, `tersedia`, `created_at`) VALUES
('41', 'Paru Sapi Balado Renyah', 'Geprek & Sambal', '32000', 'Irisan paru sapi tipis renyah digoreng gurih berlumur sambal balado.', 'assets/menu/wiki_nasi.jpg', 'Favorit', '1', '2026-09-23 08:08:24'),
('42', 'Iga Sapi Penyet Sambal Korek', 'Geprek & Sambal', '45000', 'Iga sapi bakar empuk dipenyet dengan ulekan cabai korek panas.', 'assets/menu/wiki_nasi.jpg', 'Premium', '1', '2026-09-23 08:08:24'),
('43', 'Bebek Penyet Sambal Terasi Dadak', 'Geprek & Sambal', '37000', 'Bebek goreng empuk dipenyet di cobek tanah liat sambal terasi segar.', 'assets/menu/wiki_nasi.jpg', 'Tradisional', '1', '2026-09-23 08:08:24'),
('44', 'Tempe & Tahu Penyet Sambal Kencur', 'Geprek & Sambal', '16000', 'Tahu tempe goreng gurih dengan sambal kencur harum menggugah selera.', 'assets/menu/wiki_nasi.jpg', 'Hemat', '1', '2026-09-23 08:08:24'),
('45', 'Udang Bakar Madu Jimbaran', 'Seafood', '38000', 'Udang windu segar dibakar saus manis pedas khas pantai Jimbaran Bali.', 'assets/menu/photo-1599084993091-1cb5c0721cc6.jpg', 'Best Seller', '1', '2026-09-23 08:08:24'),
('46', 'Udang Goreng Saus Telur Asin', 'Seafood', '40000', 'Udang krispi berbalut pasta kuning telur asin creamy dan daun kari.', 'assets/menu/photo-1559742811-822873691df8.jpg', 'Favorit', '1', '2026-09-23 08:08:24'),
('47', 'Cumi Goreng Tepung Calamari Crispy', 'Seafood', '34000', 'Cincin cumi segar tepung berbumbu gurih renyah dengan saus tartar pedas.', 'assets/menu/photo-1534422298391-e4f8c172dddb.jpg', 'Camilan & Lauk', '1', '2026-09-23 08:08:24'),
('48', 'Cumi Bakar Saus Padang Pedas', 'Seafood', '36000', 'Cumi utuh bakar saus padang kental bumbu rempah cabai merah.', 'assets/menu/wiki_cumi-cumi.jpg', 'Pedas', '1', '2026-09-23 08:08:24'),
('49', 'Gurame Terbang Saus Asam Manis', 'Seafood', '45000', 'Ikan gurame segar fillet goreng terbang mekar dengan siraman asam manis.', 'assets/menu/wiki_ikan_bakar.jpg', 'Favorit Keluarga', '1', '2026-09-23 08:08:24'),
('50', 'Gurame Bakar Bumbu Cobek Sunda', 'Seafood', '46000', 'Ikan gurame bakar olesan kecap disiram sambal cobek jahe kencur hangat.', 'assets/menu/wiki_ikan_bakar.jpg', 'Khas Sunda', '1', '2026-09-23 08:08:24'),
('51', 'Kerang Hijau Saus Tiram Gurih', 'Seafood', '28000', 'Kerang hijau pilihan ditumis saus tiram, jahe segar dan daun bawang.', 'assets/menu/wiki_ikan_bakar.jpg', 'Gurih', '1', '2026-09-23 08:08:24'),
('52', 'Kerang Dara Rebus Sambal Nanas', 'Seafood', '26000', 'Kerang dara segar rebus daun salam disajikan cocolan saus nanas pedas.', 'assets/menu/wiki_ikan_bakar.jpg', 'Segar', '1', '2026-09-23 08:08:24'),
('53', 'Kakap Merah Kuah Asam Pedas', 'Seafood', '42000', 'Fillet kakap merah kuah asam segar tomat hijau, belimbing wuluh & kemangi.', 'assets/menu/wiki_ikan_bakar.jpg', 'Segar', '1', '2026-09-23 08:08:24'),
('54', 'Kepiting Saus Lada Hitam (Portion)', 'Seafood', '55000', 'Daging kepiting segar tumis lada hitam pedas aromatik menggoda.', 'assets/menu/wiki_crab_dish.jpg', 'Chef Pick', '1', '2026-09-23 08:08:24'),
('55', 'Mie Goreng Spesial Dapur Tyas', 'Mie & Pasta', '26000', 'Mie telur kenyal digoreng wok panas dengan sayur, bakso dan telur.', 'assets/menu/wiki_mie_goreng.jpg', 'Best Seller', '1', '2026-09-23 08:08:24'),
('56', 'Mie Pedas Mercon Tyas Lv 1-3', 'Mie & Pasta', '22000', 'Mie pedas kenyal taburan ayam cincang dan pangsit goreng renyah.', 'assets/menu/wiki_mie_goreng.jpg', 'Favorit Anak Muda', '1', '2026-09-23 08:08:24'),
('57', 'Mie Ayam Jamur Pangsit Rebus', 'Mie & Pasta', '24000', 'Mie keriting kenyal dengan tumisan ayam jamur manis dan kuah kaldu.', 'assets/menu/wiki_mie_ayam.jpg', 'Favorit', '1', '2026-09-23 08:08:24'),
('58', 'Mie Kocok Kaki Sapi Bandung', 'Mie & Pasta', '32000', 'Mie kuning, tauge segar, kikil sapi empuk dalam kuah kaldu kental gurih.', 'assets/menu/wiki_mie_kocok.jpg', 'Khas Bandung', '1', '2026-09-23 08:08:24'),
('59', 'Mie Godhog Jawa Spesial', 'Mie & Pasta', '27000', 'Mie kuah kaldu ayam kampung kental gurih dengan telur orak arik & kol.', 'assets/menu/wiki_mie_goreng.jpg', 'Tradisional', '1', '2026-09-23 08:08:24'),
('60', 'Mie Celor Khas Palembang', 'Mie & Pasta', '29000', 'Mie tebal kuah kaldu udang dan santan kental dengan irisan telur rebus.', 'assets/menu/wiki_mie_celor.jpg', 'Khas Palembang', '1', '2026-09-23 08:08:24');
INSERT INTO `tabel_menu` (`id`, `nama_menu`, `kategori`, `harga`, `deskripsi`, `foto`, `tag`, `tersedia`, `created_at`) VALUES
('61', 'Kwetiau Goreng Seafood Penang Style', 'Mie & Pasta', '33000', 'Kwetiau beras kenyal aroma smokey wok hei dengan udang dan telur bebek.', 'assets/menu/wiki_kwetiau_goreng.jpg', 'Chef Pick', '1', '2026-09-23 08:08:24'),
('62', 'Kwetiau Siram Sapi Kuah Kental', 'Mie & Pasta', '35000', 'Kwetiau goreng disiram kuah telur kental dan irisan daging sapi empuk.', 'assets/menu/wiki_char_kway_teow.jpg', 'Hangat', '1', '2026-09-23 08:08:24'),
('63', 'Bihun Goreng Kampung Sayur Mayur', 'Mie & Pasta', '24000', 'Bihun beras lembut digoreng kecap gurih dengan sayuran segar & bakso.', 'assets/menu/wiki_bihun_goreng.jpg', 'Hemat', '1', '2026-09-23 08:08:24'),
('64', 'Bakso Urat Sapi Jumbo Tyas', 'Mie & Pasta', '27000', 'Bakso urat sapi asli kenyal berdaging dengan kuah kaldu sumsum gurih.', 'assets/menu/wiki_bakso.jpg', 'Best Seller', '1', '2026-09-23 08:08:24'),
('65', 'Bakso Telur Asin Melted', 'Mie & Pasta', '28000', 'Bakso sapi lembut berisikan kuning telur asin gurih yang meledak di lidah.', 'assets/menu/wiki_bakso.jpg', 'Unik', '1', '2026-09-23 08:08:24'),
('66', 'Bakso Cuanki Bandung Komplit', 'Mie & Pasta', '25000', 'Bakso halus, siomay kering, tahu bakso dan batagor kuah gurih wangi.', 'assets/menu/wiki_bakso.jpg', 'Khas Bandung', '1', '2026-09-23 08:08:24'),
('67', 'Soto Ayam Kampung Segar Tyas', 'Sup & Soto', '25000', 'Kuah kaldu kuning rempah bening dengan suwir ayam kampung dan koya.', 'assets/menu/wiki_soto_ayam.jpg', 'Best Seller', '1', '2026-09-23 08:08:24'),
('68', 'Soto Betawi Daging Sapi Santan', 'Sup & Soto', '37000', 'Kuah santan susu creamy wangi minyak samin dengan potongan daging empuk.', 'assets/menu/wiki_soto_betawi.jpg', 'Gurih Creamy', '1', '2026-09-23 08:08:24'),
('69', 'Soto Kudus Mangkok Kerbau/Ayam', 'Sup & Soto', '26000', 'Soto khas mangkok kecil bumbu rempah bawang putih goreng wangi.', 'assets/menu/wiki_soto_kudus.jpg', 'Tradisional', '1', '2026-09-23 08:08:24'),
('70', 'Soto Lamongan Koya Melimpah', 'Sup & Soto', '26000', 'Soto kuah kuning gurih dengan taburan bubuk koya kerupuk udang gurih.', 'assets/menu/wiki_soto_lamongan.jpg', 'Favorit', '1', '2026-09-23 08:08:24'),
('71', 'Sop Buntut Sapi Kuah Bening', 'Sup & Soto', '52000', 'Potongan buntut sapi impor empuk kuah kaldu rempah pala dan cengkeh.', 'assets/menu/photo-1588168333986-5078d3ae3976.jpg', 'Premium', '1', '2026-09-23 08:08:24'),
('72', 'Sop Buntut Bakar Madu Tyas', 'Sup & Soto', '55000', 'Buntut bakar karamelisasi madu disajikan kuah kaldu hangat terpisah.', 'assets/menu/photo-1529692236671-f1f6cf9683ba.jpg', 'Chef Pick', '1', '2026-09-23 08:08:24'),
('73', 'Sop Iga Sapi Asam Pedas', 'Sup & Soto', '46000', 'Iga sapi empuk kuah kaldu segar dengan potongan tomat hijau dan cabai.', 'assets/menu/photo-1544025162-d76694265947.jpg', 'Segar', '1', '2026-09-23 08:08:24'),
('74', 'Sayur Asem Parahyangan Gurih Segar', 'Sup & Soto', '18000', 'Kuah asam jawa segar dengan kacang tanah, jagung manis dan labu siam.', 'assets/menu/wiki_sayur_asem.jpg', 'Segar', '1', '2026-09-23 08:08:24'),
('75', 'Tongseng Kambing Rempah Kuah Santan', 'Sup & Soto', '42000', 'Daging kambing empuk tumis kol dan tomat kuah gulai rempah manis pedas.', 'assets/menu/wiki_soto.jpg', 'Kaya Rempah', '1', '2026-09-23 08:08:24'),
('76', 'Gulai Kepala Kakap Merah Padang', 'Sup & Soto', '48000', 'Kepala kakap segar berkuah gulai santan kunyit kuning pedas menggoda.', 'assets/menu/wiki_soto.jpg', 'Padang Style', '1', '2026-09-23 08:08:24'),
('77', 'Dimsum Ayam Udang Homemade (4 pcs)', 'Snack & Dimsum', '18000', 'Daging ayam dan udang cincang juicy hangat disajikan saus chili oil.', 'assets/menu/wiki_shumai.jpg', 'Best Seller', '1', '2026-09-23 08:08:24'),
('78', 'Dimsum Mozzarella Melted (4 pcs)', 'Snack & Dimsum', '21000', 'Dimsum gurih dengan topping lelehan keju mozzarella lembut.', 'assets/menu/wiki_shumai.jpg', 'Favorit', '1', '2026-09-23 08:08:24'),
('79', 'Dimsum Mentai Panggang (4 pcs)', 'Snack & Dimsum', '23000', 'Dimsum kukus disiram saus mentai gurih creamy lalu dibakar torch.', 'assets/menu/wiki_shumai.jpg', 'Viral', '1', '2026-09-23 08:08:24'),
('80', 'Pangsit Goreng Renyah Tyas (5 pcs)', 'Snack & Dimsum', '16000', 'Kulit renyah keemasan isi adonan ayam udang saus asam manis pedas.', 'assets/menu/wiki_wonton.jpg', 'Best Seller', '1', '2026-09-23 08:08:24');
INSERT INTO `tabel_menu` (`id`, `nama_menu`, `kategori`, `harga`, `deskripsi`, `foto`, `tag`, `tersedia`, `created_at`) VALUES
('81', 'Batagor Ikan Tenggiri Saus Kacang', 'Snack & Dimsum', '22000', 'Tahu bakso ikan tenggiri goreng garing disiram saus kacang kental legit.', 'assets/menu/wiki_batagor.jpg', 'Khas Bandung', '1', '2026-09-23 08:08:24'),
('82', 'Siomay Bandung Kukus Bumbu Kacang', 'Snack & Dimsum', '22000', 'Siomay ikan tenggiri, kol, pare, kentang dan telur saus kacang gurih.', 'assets/menu/wiki_siomay.jpg', 'Tradisional', '1', '2026-09-23 08:08:24'),
('83', 'Tempe Mendoan Purwokerto (4 pcs)', 'Snack & Dimsum', '15000', 'Tempe kedelai tipis berbalut tepung daun bawang dengan cocolan kecap rawit.', 'assets/menu/wiki_mendoan.jpg', 'Favorit', '1', '2026-09-23 08:08:24'),
('84', 'Tahu Walik Krispi Sambal Petis', 'Snack & Dimsum', '17000', 'Tahu pong dibalik isi adonan daging ayam giling kenyal gurih renyah.', 'assets/menu/wiki_tahu_goreng.jpg', 'Kriuk', '1', '2026-09-23 08:08:24'),
('85', 'Cireng Krispi Bumbu Rujak Pedas', 'Snack & Dimsum', '15000', 'Aci goreng garing di luar kenyal di dalam dicocol saus gula aren asam pedas.', 'assets/menu/wiki_cireng.jpg', 'Nagih', '1', '2026-09-23 08:08:24'),
('86', 'Pisang Goreng Wijen Madu', 'Snack & Dimsum', '16000', 'Pisang raja manis legit digoreng tepung wijen renyah karamel madu.', 'assets/menu/wiki_pisang_goreng.jpg', 'Manis', '1', '2026-09-23 08:08:24'),
('87', 'Roti Bakar Cokelat Keju Susu', 'Snack & Dimsum', '18000', 'Roti tebal bakar empuk bertabur meses cokelat, keju cheddar parut & kental manis.', 'assets/menu/wiki_roti_bakar.jpg', 'Favorit', '1', '2026-09-23 08:08:24'),
('88', 'Singkong Goreng Keju Merekah', 'Snack & Dimsum', '15000', 'Singkong empuk pulen digoreng merekah ditaburi keju gurih melimpah.', 'assets/menu/wiki_gorengan.jpg', 'Tradisional', '1', '2026-09-23 08:08:24'),
('89', 'Salad Buah Premium Dapur Tyas (Reguler)', 'Salad & Sehat', '25000', 'Potongan apel fuji, melon, anggur, pir madu saus yoghurt creamy parutan keju.', 'assets/menu/wiki_fruit_salad.jpg', 'Best Seller', '1', '2026-09-23 08:08:24'),
('90', 'Salad Buah Jumbo Family Share', 'Salad & Sehat', '45000', 'Porsi besar aneka buah premium dengan saus keju manis segar melimpah.', 'assets/menu/wiki_fruit_salad.jpg', 'Favorit', '1', '2026-09-23 08:08:24'),
('91', 'Gado-Gado Siram Sayur Segar', 'Salad & Sehat', '22000', 'Bayam, tauge, kacang panjang rebus, tahu tempe telur siraman bumbu kacang.', 'assets/menu/wiki_gado-gado.jpg', 'Sehat', '1', '2026-09-23 08:08:24'),
('92', 'Karedok Sayur Mentah Sunda', 'Salad & Sehat', '19000', 'Sayuran mentah segar renyah diaduk dengan ulekan bumbu kacang kencur wangi.', 'assets/menu/wiki_karedok.jpg', 'Khas Sunda', '1', '2026-09-23 08:08:24'),
('93', 'Pecel Madiun Bumbu Pedas Gurih', 'Salad & Sehat', '20000', 'Aneka daun hijau rebus disiram saus pecel kacang tanah sangrai harum.', 'assets/menu/wiki_pecel.jpg', 'Tradisional', '1', '2026-09-23 08:08:24'),
('94', 'Es Teh Manis Melati Jumbo', 'Minuman', '6000', 'Seduhan daun teh melati wangi khas Dapur Tyas segar melegakan tenggorokan.', 'assets/menu/wiki_iced_tea.jpg', 'Segar', '1', '2026-09-23 08:08:24'),
('95', 'Es Teh Tarik Creamy Khas Melayu', 'Minuman', '12000', 'Teh hitam wangi dipadu susu kental manis ditarik hingga berbusa lembut.', 'assets/menu/wiki_iced_tea.jpg', 'Favorit', '1', '2026-09-23 08:08:24'),
('96', 'Es Jeruk Peras Murni', 'Minuman', '10000', 'Perasan jeruk segar asli tanpa pemanis buatan dingin menyejukkan.', 'assets/menu/wiki_orange_juice.jpg', 'Alami', '1', '2026-09-23 08:08:24'),
('97', 'Es Cincau Hijau Gula Aren Susu', 'Minuman', '12000', 'Cincau alami hijau kenyal dingin dengan kuah susu evaporasi & aren murni.', 'assets/menu/wiki_grass_jelly.jpg', 'Best Seller', '1', '2026-09-23 08:08:24'),
('98', 'Es Cendol Dawet Ayu Nangka', 'Minuman', '14000', 'Dawet pandan suji harum, santan gurih, gula aren cair dan potongan nangka.', 'assets/menu/wiki_cendol.jpg', 'Tradisional', '1', '2026-09-23 08:08:24'),
('99', 'Es Kelapa Muda Jeruk Nipis', 'Minuman', '15000', 'Air dan daging kelapa muda murni dengan sentuhan tetesan jeruk nipis segar.', 'assets/menu/wiki_coconut_water.jpg', 'Alami', '1', '2026-09-23 08:08:24'),
('100', 'Es Teler Komplit Alpukat Nangka', 'Minuman', '18000', 'Daging kelapa muda, alpukat mentega, potongan nangka wangi susu kental manis.', 'assets/menu/wiki_es_teler.jpg', 'Favorit', '1', '2026-09-23 08:08:24');
INSERT INTO `tabel_menu` (`id`, `nama_menu`, `kategori`, `harga`, `deskripsi`, `foto`, `tag`, `tersedia`, `created_at`) VALUES
('101', 'Es Campur Pelangi Dapur Tyas', 'Minuman', '16000', 'Cincau hitam, tape singkong, kolang kaling, agar-agar sirup cocopandan merah.', 'assets/menu/wiki_es_campur.jpg', 'Segar', '1', '2026-09-23 08:08:24'),
('102', 'Es Lemon Tea Sereh Madu', 'Minuman', '14000', 'Teh wangi dengan irisan lemon segar, batang serai geprek dan madu alami.', 'assets/menu/wiki_iced_tea.jpg', 'Sehat', '1', '2026-09-23 08:08:24'),
('103', 'Jus Alpukat Kocok Cokelat Susu', 'Minuman', '16000', 'Alpukat mentega lembut diblender kental dengan lelehan saus cokelat manis.', 'assets/menu/photo-1579954115545-a95591f28bfc.jpg', 'Kental', '1', '2026-09-23 08:08:24'),
('104', 'Jus Mangga Gedong Gincu Segar', 'Minuman', '15000', 'Daging mangga matang pohon segar manis alami kaya vitamin C.', 'assets/menu/wiki_mango_juice.jpg', 'Vitamin C', '1', '2026-09-23 08:08:24'),
('105', 'Jus Buah Naga Merah Segar', 'Minuman', '14000', 'Buah naga merah segar diblender lembut dengan perasan jeruk nipis.', 'assets/menu/wiki_es_teh.jpg', 'Detox', '1', '2026-09-23 08:08:24'),
('106', 'Wedang Jahe Sereh Hangat', 'Minuman', '11000', 'Rebusan jahe merah bakar, batang serai dan gula merah menghangatkan tubuh.', 'assets/menu/wiki_wedang_jahe.jpg', 'Hangat', '1', '2026-09-23 08:08:24'),
('107', 'Wedang Ronde Jahe Isi Kacang', 'Minuman', '16000', 'Bola ketan kenyal isi pasta kacang manis dalam kuah jahe rempah hangat.', 'assets/menu/wiki_es_teh.jpg', 'Hangat', '1', '2026-09-23 08:08:24'),
('108', 'Kopi Susu Gula Aren Tyas', 'Coffee & Tea', '16000', 'Espresso arabika blend dengan susu creamy dan legitnya gula aren organik.', 'assets/menu/wiki_kopi_susu.jpg', 'Best Seller', '1', '2026-09-23 08:08:24'),
('109', 'Kopi Hitam Tubruk Arabika Gayo', 'Coffee & Tea', '12000', 'Biji kopi arabika gayo aceh seduh tubruk aroma fruity dan cokelat lembut.', 'assets/menu/wiki_kopi.jpg', 'Pecinta Kopi', '1', '2026-09-23 08:08:24'),
('110', 'Americano Iced Double Shot', 'Coffee & Tea', '15000', 'Espresso ganda dingin segar beraroma kopi pekat tanpa ampas.', 'assets/menu/wiki_caff___americano.jpg', 'Segar', '1', '2026-09-23 08:08:24'),
('111', 'Caramel Macchiato Iced', 'Coffee & Tea', '22000', 'Perpaduan espresso, susu vanilla dingin dan siraman karamel keemasan.', 'assets/menu/wiki_kopi.jpg', 'Favorit', '1', '2026-09-23 08:08:24'),
('112', 'Matcha Green Tea Latte Creamy', 'Coffee & Tea', '20000', 'Bubuk matcha murni Jepang dikocok susu segar berbusa lembut.', 'assets/menu/wiki_latte.jpg', 'Non Kopi', '1', '2026-09-23 08:08:24'),
('113', 'Chocolate Lava Iced Latte', 'Coffee & Tea', '19000', 'Cokelat dark pekat berpadu susu segar dingin manis gurih memanjakan lidah.', 'assets/menu/wiki_latte.jpg', 'Favorit', '1', '2026-09-23 08:08:24'),
('114', 'Rendang Paru Sapi Padang', 'Ayam & Unggas', '34000', 'Paru sapi empuk dimasak bumbu rendang minang gurih berempah.', 'assets/menu/wiki_rendang.jpg', 'Spesial', '1', '2026-09-23 08:08:24'),
('115', 'Dendeng Balado Basah', 'Ayam & Unggas', '38000', 'Daging sapi iris tipis empuk disiram ulekan sambal balado merah segar.', 'assets/menu/wiki_ayam_goreng.jpg', 'Favorit', '1', '2026-09-23 08:08:24'),
('116', 'Dendeng Batokok Renyah', 'Ayam & Unggas', '37000', 'Dendeng sapi goreng garing ditumbuk dengan taburan cabai hijau limau.', 'assets/menu/wiki_ayam_goreng.jpg', 'Khas Padang', '1', '2026-09-23 08:08:24'),
('117', 'Gulai Cincang Sapi Rempah', 'Ayam & Unggas', '35000', 'Daging sapi cincang kuah santan gurih kental beraroma kapulaga cengkeh.', 'assets/menu/wiki_ayam_goreng.jpg', 'Gurih', '1', '2026-09-23 08:08:24'),
('118', 'Sapi Lada Hitam Wok', 'Ayam & Unggas', '42000', 'Irisan daging sapi empuk tumis saus lada hitam wangi paprika bombay.', 'assets/menu/wiki_ayam_goreng.jpg', 'Best Seller', '1', '2026-09-23 08:08:24'),
('119', 'Bistik Sapi Jawa Klasik', 'Ayam & Unggas', '39000', 'Daging sapi empuk kuah semur manis gurih bumbu pala disajikan selada.', 'assets/menu/wiki_ayam_goreng.jpg', 'Tradisional', '1', '2026-09-23 08:08:24'),
('120', 'Empal Gepuk Serundeng', 'Ayam & Unggas', '33000', 'Daging sapi ungkep empuk manis legit dengan taburan serundeng kelapa.', 'assets/menu/wiki_ayam_goreng.jpg', 'Favorit', '1', '2026-09-23 08:08:24');
INSERT INTO `tabel_menu` (`id`, `nama_menu`, `kategori`, `harga`, `deskripsi`, `foto`, `tag`, `tersedia`, `created_at`) VALUES
('121', 'Semur Daging Sapi Betawi', 'Ayam & Unggas', '35000', 'Daging sapi empuk kuah kecap rempah pala cengkeh kentang lembut.', 'assets/menu/wiki_ayam_goreng.jpg', 'Klasik', '1', '2026-09-23 08:08:24'),
('122', 'Rawon Iga Sapi Kuah Kluwek', 'Ayam & Unggas', '46000', 'Iga sapi empuk kuah kluwek hitam gurih mantap khas Jawa Timur.', 'assets/menu/wiki_rawon.jpg', 'Spesial', '1', '2026-09-23 08:08:24'),
('123', 'Sop Sengkel Sapi Bening', 'Ayam & Unggas', '40000', 'Potongan sengkel sapi empuk urat kenyal kuah rempah bening segar.', 'assets/menu/wiki_ayam_goreng.jpg', 'Hangat', '1', '2026-09-23 08:08:24'),
('124', 'Iga Bakar Saus Barbeque', 'Ayam & Unggas', '48000', 'Iga sapi bakar karamel saus BBQ gurih smokey disajikan kentang.', 'assets/menu/wiki_ayam_goreng.jpg', 'Chef Pick', '1', '2026-09-23 08:08:24'),
('125', 'Tongseng Sapi Cabai Rawit', 'Ayam & Unggas', '38000', 'Daging sapi empuk tumis kol tomat kuah gulai rempah manis pedas.', 'assets/menu/wiki_ayam_goreng.jpg', 'Pedas Gurih', '1', '2026-09-23 08:08:24'),
('126', 'Ayam Bakar Bumbu Rujak', 'Ayam & Unggas', '29000', 'Ayam bakar saus santan cabai bumbu rujak pedas legit gurih.', 'assets/menu/wiki_ayam_bakar.jpg', 'Favorit', '1', '2026-09-23 08:08:24'),
('127', 'Ayam Bakar Kecap Pedas Limau', 'Ayam & Unggas', '28000', 'Ayam bakar olesan kecap manis cabai rawit dengan perasan jeruk limau.', 'assets/menu/wiki_ayam_bakar.jpg', 'Segar', '1', '2026-09-23 08:08:24'),
('128', 'Ayam Pop Khas Bukittinggi', 'Ayam & Unggas', '29000', 'Ayam kampung rebus bumbu bawang disajikan saus tomat cabai merah.', 'assets/menu/wiki_ayam_pop.jpg', 'Khas Padang', '1', '2026-09-23 08:08:24'),
('129', 'Ayam Goreng Bumbu Kuning', 'Ayam & Unggas', '26000', 'Ayam ungkep rempah kunyit jahe lengkuas digoreng gurih harum.', 'assets/menu/wiki_ayam_goreng.jpg', 'Gurih', '1', '2026-09-23 08:08:24'),
('130', 'Ayam Goreng Saus Telur Asin', 'Ayam & Unggas', '32000', 'Ayam krispi renyah berlumur saus kuning telur asin creamy daun kari.', 'assets/menu/wiki_ayam_goreng.jpg', 'Best Seller', '1', '2026-09-23 08:08:24'),
('131', 'Ayam Geprek Sambal Korek Pedas', 'Geprek & Sambal', '25000', 'Ayam goreng tepung renyah diulek bersama cabai rawit korek panas.', 'assets/menu/wiki_ayam_geprek.jpg', 'Pedas Pol', '1', '2026-09-23 08:08:24'),
('132', 'Ayam Geprek Sambal Terasi Bakar', 'Geprek & Sambal', '26000', 'Sensasi aroma terasi bakar khas pada ulekan ayam geprek krispi.', 'assets/menu/wiki_ayam_geprek.jpg', 'Tradisional', '1', '2026-09-23 08:08:24'),
('133', 'Ayam Bakar Padang Gulai', 'Ayam & Unggas', '29000', 'Ayam bakar bumbu gulai merah rempah padang wangi gurih pekat.', 'assets/menu/wiki_ayam_bakar.jpg', 'Khas Padang', '1', '2026-09-23 08:08:24'),
('134', 'Ayam Masak Habang Banjar', 'Ayam & Unggas', '30000', 'Ayam empuk bumbu cabai kering merah manis gurih khas Banjarmasin.', 'assets/menu/wiki_ayam_goreng.jpg', 'Khas Banjar', '1', '2026-09-23 08:08:24'),
('135', 'Ayam Panggang Klaten Manis Gurih', 'Ayam & Unggas', '31000', 'Ayam panggang bumbu areh santan kental manis gurih meresap tulang.', 'assets/menu/wiki_ayam_goreng.jpg', 'Tradisional', '1', '2026-09-23 08:08:24'),
('136', 'Sate Ayam Madura Saus Kacang (10 tusuk)', 'Ayam & Unggas', '32000', 'Potongan daging ayam empuk bakar bumbu kecap kacang kental bawang.', 'assets/menu/wiki_sate_ayam.jpg', 'Best Seller', '1', '2026-09-23 08:08:24'),
('137', 'Sate Taichan Daging Paha (10 tusuk)', 'Ayam & Unggas', '30000', 'Sate ayam bakar polos gurih asin disajikan sambal rawit pedas jeruk.', 'assets/menu/wiki_ayam_goreng.jpg', 'Favorit Anak Muda', '1', '2026-09-23 08:08:24'),
('138', 'Sate Lilit Ayam Khas Bali (5 tusuk)', 'Ayam & Unggas', '27000', 'Cincangan daging ayam rempah bumbu genep lilit batang serai harum.', 'assets/menu/wiki_sate_lilit.jpg', 'Khas Bali', '1', '2026-09-23 08:08:24'),
('139', 'Ayam Suwir Bali Pedas Harum', 'Ayam & Unggas', '28000', 'Suwiran ayam bumbu genep cabai merah harum daun jeruk Bali.', 'assets/menu/wiki_ayam_goreng.jpg', 'Pedas Harum', '1', '2026-09-23 08:08:24'),
('140', 'Ayam Bakar Solo Manis Gurih', 'Ayam & Unggas', '28000', 'Ayam bakar ungkep kecap manis bawang merah Solo meresap sempurna.', 'assets/menu/wiki_ayam_bakar.jpg', 'Manis Gurih', '1', '2026-09-23 08:08:24');
INSERT INTO `tabel_menu` (`id`, `nama_menu`, `kategori`, `harga`, `deskripsi`, `foto`, `tag`, `tersedia`, `created_at`) VALUES
('141', 'Ayam Krispi Saus Keju Pedas', 'Ayam & Unggas', '29000', 'Ayam goreng tepung garing disiram saus keju cheddar pedas gurih.', 'assets/menu/wiki_ayam_goreng.jpg', 'Favorit', '1', '2026-09-23 08:08:24'),
('142', 'Udang Saus Padang Pedas Manis', 'Seafood', '39000', 'Udang segar tumis saus padang kental bumbu cabai telur kocok.', 'assets/menu/wiki_ikan_bakar.jpg', 'Best Seller', '1', '2026-09-23 08:08:24'),
('143', 'Udang Asam Manis Nanas', 'Seafood', '37000', 'Udang goreng saus tomat asam manis dengan potongan nanas segar paprika.', 'assets/menu/wiki_ikan_bakar.jpg', 'Segar', '1', '2026-09-23 08:08:24'),
('144', 'Udang Rebus Daun Salam', 'Seafood', '33000', 'Udang laut segar rebus jahe daun salam cocol saus tiram jahe.', 'assets/menu/wiki_ikan_bakar.jpg', 'Alami', '1', '2026-09-23 08:08:24'),
('145', 'Udang Bakar Saus Rica', 'Seafood', '38000', 'Udang bakar bumbu rica-rica pedas cabai rawit tomat merah harum.', 'assets/menu/photo-1559742811-822873691df8.jpg', 'Pedas', '1', '2026-09-23 08:08:24'),
('146', 'Cumi Saus Tiram Gurih', 'Seafood', '35000', 'Cumi segar ditumis saus tiram bawang bombay dan jahe harum.', 'assets/menu/wiki_ikan_bakar.jpg', 'Favorit', '1', '2026-09-23 08:08:24'),
('147', 'Cumi Bakar Kecap Bawang', 'Seafood', '36000', 'Cumi bakar empuk olesan kecap manis taburan irisan bawang cabai.', 'assets/menu/wiki_cumi-cumi.jpg', 'Gurih', '1', '2026-09-23 08:08:24'),
('148', 'Cumi Asin Cabe Ijo Pete', 'Seafood', '32000', 'Cumi asin empuk ditumis cabai hijau tomat hijau dan irisan petai segar.', 'assets/menu/wiki_ikan_bakar.jpg', 'Best Seller', '1', '2026-09-23 08:08:24'),
('149', 'Gurame Saus Padang', 'Seafood', '46000', 'Ikan gurame goreng mekar disiram saus padang pedas kental gurih.', 'assets/menu/wiki_ikan_bakar.jpg', 'Favorit Keluarga', '1', '2026-09-23 08:08:24'),
('150', 'Gurame Bakar Kecap Pedas', 'Seafood', '45000', 'Ikan gurame bakar olesan mentega kecap manis pedas limau wangi.', 'assets/menu/wiki_ikan_bakar.jpg', 'Klasik', '1', '2026-09-23 08:08:24'),
('151', 'Kakap Bakar Bumbu Jimbaran', 'Seafood', '44000', 'Fillet kakap bakar saus tomat cabai bawang khas pantai Bali.', 'assets/menu/wiki_ikan_bakar.jpg', 'Spesial', '1', '2026-09-23 08:08:24'),
('152', 'Ikan Kembung Bakar Sambal Dabu-Dabu', 'Seafood', '26000', 'Ikan kembung bakar gurih disiram sambal dabu-dabu segar Manado.', 'assets/menu/wiki_ikan_bakar.jpg', 'Segar', '1', '2026-09-23 08:08:24'),
('153', 'Ikan Nila Goreng Sambal Terasi', 'Seafood', '25000', 'Ikan nila segar goreng garing disajikan lalapan segar sambal terasi.', 'assets/menu/wiki_ikan_bakar.jpg', 'Favorit', '1', '2026-09-23 08:08:24'),
('154', 'Ikan Lele Crispy Sambal Bawang', 'Seafood', '20000', 'Lele goreng tepung krispi kriuk dicocol sambal bawang pedas nampol.', 'assets/menu/wiki_ikan_bakar.jpg', 'Hemat', '1', '2026-09-23 08:08:24'),
('155', 'Ikan Bawal Bakar Bumbu Kuning', 'Seafood', '38000', 'Ikan bawal laut bakar bumbu rempah kuning kunyit jahe kemiri gurih.', 'assets/menu/wiki_ikan_bakar.jpg', 'Gurih', '1', '2026-09-23 08:08:24'),
('156', 'Kepiting Saus Padang Super Pedas', 'Seafood', '58000', 'Kepiting segar bumbu saus padang telur kental membakar selera makan.', 'assets/menu/photo-1565557623262-b51c2513a641.jpg', 'Chef Pick', '1', '2026-09-23 08:08:24'),
('157', 'Mie Kuah Kari Ayam Pedas', 'Mie & Pasta', '25000', 'Mie kuah kaldu kari santan gurih dengan potongan daging ayam empuk.', 'assets/menu/wiki_mie_kuah.jpg', 'Gurih', '1', '2026-09-23 08:08:24'),
('158', 'Mie Goreng Seafood Pedas Level 1-5', 'Mie & Pasta', '32000', 'Mie goreng wok hei dengan udang cumi bakso dan tingkat kepedasan.', 'assets/menu/wiki_mie_goreng.jpg', 'Tantangan', '1', '2026-09-23 08:08:24'),
('159', 'Mie Aceh Goreng Daging Sapi', 'Mie & Pasta', '33000', 'Mie kuning tebal bumbu rempah kari pekat khas Aceh potongan daging.', 'assets/menu/wiki_mie_aceh.jpg', 'Khas Aceh', '1', '2026-09-23 08:08:24'),
('160', 'Mie Aceh Kuah Kental Kari', 'Mie & Pasta', '33000', 'Mie aceh kuah nyemek kaya rempah kapulaga dengan emping acar bawang.', 'assets/menu/wiki_mie_aceh.jpg', 'Khas Aceh', '1', '2026-09-23 08:08:24');
INSERT INTO `tabel_menu` (`id`, `nama_menu`, `kategori`, `harga`, `deskripsi`, `foto`, `tag`, `tersedia`, `created_at`) VALUES
('161', 'Kwetiau Goreng Ayam Sosis', 'Mie & Pasta', '27000', 'Kwetiau kenyal digoreng telur ayam sosis dan sayuran segar sawi tauge.', 'assets/menu/wiki_kwetiau_goreng.jpg', 'Favorit', '1', '2026-09-23 08:08:24'),
('162', 'Kwetiau Kuah Telur Kental', 'Mie & Pasta', '28000', 'Kwetiau beras kuah kaldu ayam telur kocok hangat kental lezat.', 'assets/menu/wiki_mie_goreng.jpg', 'Hangat', '1', '2026-09-23 08:08:24'),
('163', 'Bihun Kuah Bebek Jahe Hangat', 'Mie & Pasta', '34000', 'Bihun lembut kuah kaldu bebek rempah jahe menghangatkan tubuh.', 'assets/menu/wiki_mie_goreng.jpg', 'Spesial', '1', '2026-09-23 08:08:24'),
('164', 'Bihun Goreng Seafood Spesial', 'Mie & Pasta', '31000', 'Bihun goreng kecap harum dengan cumi udang telur dan sayuran.', 'assets/menu/wiki_bihun_goreng.jpg', 'Favorit', '1', '2026-09-23 08:08:24'),
('165', 'Mie Ayam Bakso Urat Komplit', 'Mie & Pasta', '28000', 'Mie ayam jamur disajikan bakso urat besar pangsit kuah dan sawi.', 'assets/menu/wiki_mie_ayam.jpg', 'Komplit', '1', '2026-09-23 08:08:24'),
('166', 'Mie Ayam Ceker Mercon Pedas', 'Mie & Pasta', '26000', 'Mie ayam kenyal topping ceker mercon pedas gurih nagih.', 'assets/menu/wiki_mie_ayam.jpg', 'Best Seller', '1', '2026-09-23 08:08:24'),
('167', 'Bakso Aci Garut Pedas Segar', 'Mie & Pasta', '20000', 'Bakso aci kenyal isi ayam kuah jeruk limau pilus cikur dan cuanki lidah.', 'assets/menu/wiki_bakso.jpg', 'Segar', '1', '2026-09-23 08:08:24'),
('168', 'Bakso Goreng Renyah Tyas (5 pcs)', 'Mie & Pasta', '18000', 'Bakso goreng adonan ayam udang renyah kopong dicocol saus pedas.', 'assets/menu/wiki_bakso.jpg', 'Snack & Lauk', '1', '2026-09-23 08:08:24'),
('169', 'Seblak Ceker Komplit Pedas', 'Mie & Pasta', '22000', 'Kerupuk basah kuah kencur pedas dengan ceker empuk sosis bakso telur.', 'assets/menu/wiki_mie_goreng.jpg', 'Pedas Nampol', '1', '2026-09-23 08:08:24'),
('170', 'Seblak Seafood Makaroni Telur', 'Mie & Pasta', '24000', 'Seblak kuah cabai kencur dengan udang cumi makaroni dan telur kocok.', 'assets/menu/wiki_mie_goreng.jpg', 'Pedas', '1', '2026-09-23 08:08:24'),
('171', 'Pangsit Kuah Kaldu Daging Ayam', 'Mie & Pasta', '18000', 'Pangsit rebus isi ayam udang kuah kaldu ayam jernih bertabur daun bawang.', 'assets/menu/wiki_mie_goreng.jpg', 'Hangat', '1', '2026-09-23 08:08:24'),
('172', 'Tumis Kangkung Belacan Terasi', 'Makanan Utama', '17000', 'Kangkung hijau segar ditumis bumbu terasi bakar dan cabai merah harum.', 'assets/menu/wiki_nasi.jpg', 'Favorit', '1', '2026-09-23 08:08:24'),
('173', 'Tumis Kangkung Bawang Putih', 'Makanan Utama', '16000', 'Kangkung renyah ditumis bawang putih cincang gurih saus tiram.', 'assets/menu/wiki_nasi.jpg', 'Sehat', '1', '2026-09-23 08:08:24'),
('174', 'Tumis Tauge Ikan Asin Jambal', 'Makanan Utama', '18000', 'Tauge segar renyah ditumis potongan ikan asin jambal roti gurih asin.', 'assets/menu/wiki_nasi.jpg', 'Gurih', '1', '2026-09-23 08:08:24'),
('175', 'Capcay Kuah Kental Seafood', 'Makanan Utama', '28000', 'Aneka sayuran brokoli wortel jamur kembang kol udang kuah telur.', 'assets/menu/wiki_nasi.jpg', 'Sehat', '1', '2026-09-23 08:08:24'),
('176', 'Capcay Goreng Ayam Bakso', 'Makanan Utama', '26000', 'Sayuran segar ditumis kecap saus tiram potongan ayam bakso sosis.', 'assets/menu/wiki_bakso.jpg', 'Favorit', '1', '2026-09-23 08:08:24'),
('177', 'Tumis Buncis Daging Cincang', 'Makanan Utama', '22000', 'Baby buncis renyah ditumis daging sapi cincang bumbu bawang putih.', 'assets/menu/wiki_nasi.jpg', 'Rekomendasi', '1', '2026-09-23 08:08:24'),
('178', 'Sayur Lodeh Labu Siam Gurih', 'Makanan Utama', '16000', 'Sayur lodeh kuah santan gurih labu siam kacang panjang melinjo tempe.', 'assets/menu/wiki_sayur_lodeh.jpg', 'Tradisional', '1', '2026-09-23 08:08:24'),
('179', 'Urap Sayur Bumbu Kelapa Pedas', 'Makanan Utama', '17000', 'Aneka daun rebus dicampur parutan kelapa muda sangrai bumbu kencur.', 'assets/menu/wiki_nasi.jpg', 'Tradisional', '1', '2026-09-23 08:08:24'),
('180', 'Terong Balado Sambal Merah', 'Makanan Utama', '16000', 'Terong ungu goreng lembut disiram sambal balado cabai merah tomat.', 'assets/menu/wiki_nasi.jpg', 'Favorit', '1', '2026-09-23 08:08:24');
INSERT INTO `tabel_menu` (`id`, `nama_menu`, `kategori`, `harga`, `deskripsi`, `foto`, `tag`, `tersedia`, `created_at`) VALUES
('181', 'Gulai Daun Singkong Teri Padang', 'Makanan Utama', '18000', 'Daun singkong muda kuah gulai santan kunyit kuning dengan teri asin.', 'assets/menu/wiki_nasi.jpg', 'Khas Padang', '1', '2026-09-23 08:08:24'),
('182', 'Tumis Pare Udang Tidak Pahit', 'Makanan Utama', '20000', 'Pare segar diolah khusus tidak pahit ditumis udang dan cabai rawit.', 'assets/menu/wiki_nasi.jpg', 'Unik', '1', '2026-09-23 08:08:24'),
('183', 'Sambal Goreng Kentang Ati Ampela', 'Makanan Utama', '24000', 'Kentang dadu ati ampela bumbu balado santan manis pedas gurih.', 'assets/menu/wiki_nasi.jpg', 'Tradisional', '1', '2026-09-23 08:08:24'),
('184', 'Tahu Cabe Garam Crispy', 'Snack & Dimsum', '17000', 'Tahu sutra krispi ditumis bawang putih cincang cabai rawit garam gurih.', 'assets/menu/wiki_gorengan.jpg', 'Best Seller', '1', '2026-09-23 08:08:24'),
('185', 'Jamur Enoki Crispy Pedas', 'Snack & Dimsum', '18000', 'Jamur enoki goreng tepung renyah taburan bumbu cabai gurih nagih.', 'assets/menu/wiki_gorengan.jpg', 'Kriuk', '1', '2026-09-23 08:08:24'),
('186', 'Kentang Goreng French Fries Saus Keju', 'Snack & Dimsum', '18000', 'Kentang goreng impor renyah keemasan disiram saus keju cheddar gurih.', 'assets/menu/wiki_french_fries.jpg', 'Favorit', '1', '2026-09-23 08:08:24'),
('187', 'Sosis Bakar Jumbo Saus BBQ', 'Snack & Dimsum', '18000', 'Sosis sapi bratwurst bakar saus barbeque gurih dan mayonaise.', 'assets/menu/wiki_gorengan.jpg', 'Anak & Dewasa', '1', '2026-09-23 08:08:24'),
('188', 'Otak-Otak Ikan Tenggiri Bakar', 'Snack & Dimsum', '20000', 'Otak-otak ikan tenggiri daun pisang bakar cocol saus kacang gurih.', 'assets/menu/wiki_otak-otak.jpg', 'Tradisional', '1', '2026-09-23 08:08:24'),
('189', 'Lumpia Goreng Semarang (2 pcs)', 'Snack & Dimsum', '16000', 'Kulit lumpia renyah isi rebung manis ayam telur disajikan saus kental.', 'assets/menu/wiki_lumpia_semarang.jpg', 'Khas Semarang', '1', '2026-09-23 08:08:24'),
('190', 'Risoles Mayo Daging Asap Keju', 'Snack & Dimsum', '17000', 'Risoles kulit lembut isi smoked beef telur keju saus mayonaise lumer.', 'assets/menu/wiki_rissole.jpg', 'Best Seller', '1', '2026-09-23 08:08:24'),
('191', 'Kroket Kentang Daging Sapi', 'Snack & Dimsum', '16000', 'Adonan kentang halus isi daging sapi wortel gurih digoreng panir emas.', 'assets/menu/wiki_gorengan.jpg', 'Klasik', '1', '2026-09-23 08:08:24'),
('192', 'Tahu Gejrot Khas Cirebon Pedas', 'Snack & Dimsum', '14000', 'Tahu pong goreng disiram kuah gula merah asam jawa bawang cabai rawit.', 'assets/menu/wiki_tahu_gejrot.jpg', 'Pedas Segar', '1', '2026-09-23 08:08:24'),
('193', 'Martabak Telur Daging Sapi (Mini)', 'Snack & Dimsum', '22000', 'Kulit martabak renyah isi telur bebek daging cincang dan daun bawang.', 'assets/menu/wiki_gorengan.jpg', 'Favorit', '1', '2026-09-23 08:08:24'),
('194', 'Bakwan Jagung Manis Renyah (3 pcs)', 'Snack & Dimsum', '14000', 'Jagung manis pipil adonan rempah digoreng garing renyah dicocol rawit.', 'assets/menu/wiki_gorengan.jpg', 'Gurih Manis', '1', '2026-09-23 08:08:24'),
('195', 'Perkedel Kentang Ayam Gurih (3 pcs)', 'Snack & Dimsum', '14000', 'Kentang tumbuk lembut berbalut telur dan daun seledri daging ayam.', 'assets/menu/wiki_gorengan.jpg', 'Klasik', '1', '2026-09-23 08:08:24'),
('196', 'Puding Mangga Saus Vanilla', 'Dessert & Manis', '15000', 'Puding buah mangga manis lembut disiram saus vla susu vanilla dingin.', 'assets/menu/wiki_pudding.jpg', 'Segar', '1', '2026-09-23 08:08:24'),
('197', 'Puding Cokelat Belgia Vla Susu', 'Dessert & Manis', '16000', 'Puding cokelat pekat lumer disiram saus vla susu kental manis creamy.', 'assets/menu/wiki_pudding.jpg', 'Favorit', '1', '2026-09-23 08:08:24'),
('198', 'Banana Split Ice Cream Tyas', 'Dessert & Manis', '24000', 'Pisang segar dengan 3 scoop es krim cokelat vanilla stroberi saus cokelat.', 'assets/menu/wiki_banana_split.jpg', 'Spesial', '1', '2026-09-23 08:08:24'),
('199', 'Pancake Karamel Madu Pisang', 'Dessert & Manis', '20000', 'Pancake lembut hangat topping irisan pisang siraman madu karamel emas.', 'assets/menu/wiki_pancake.jpg', 'Manis', '1', '2026-09-23 08:08:24'),
('200', 'Waffle Saus Cokelat Keju', 'Dessert & Manis', '21000', 'Waffle renyah di luar lembut di dalam taburan keju parut saus cokelat.', 'assets/menu/wiki_waffle.jpg', 'Favorit', '1', '2026-09-23 08:08:24');
INSERT INTO `tabel_menu` (`id`, `nama_menu`, `kategori`, `harga`, `deskripsi`, `foto`, `tag`, `tersedia`, `created_at`) VALUES
('201', 'Klapertart Kelapa Muda Panggang', 'Dessert & Manis', '20000', 'Kue khas Manado daging kelapa muda kismis kenari bubuk kayu manis wangi.', 'assets/menu/wiki_pudding.jpg', 'Khas Manado', '1', '2026-09-23 08:08:24'),
('202', 'Kolak Pisang Ubi Gula Aren', 'Dessert & Manis', '15000', 'Pisang kepok ubi manis kuah santan gula aren murni daun pandan harum.', 'assets/menu/wiki_pudding.jpg', 'Tradisional', '1', '2026-09-23 08:08:24'),
('203', 'Bubur Sumsum Kuah Kinca Gula Jawa', 'Dessert & Manis', '14000', 'Bubur tepung beras santan lembut gurih disiram kuah gula jawa wangi.', 'assets/menu/wiki_pudding.jpg', 'Tradisional', '1', '2026-09-23 08:08:24'),
('204', 'Es Krim Goreng Vanilla Renyah', 'Dessert & Manis', '18000', 'Roti goreng renyah panas dengan es krim vanilla beku di dalamnya.', 'assets/menu/wiki_pudding.jpg', 'Unik', '1', '2026-09-23 08:08:24'),
('205', 'Roti Bakar Keju Mozzarella Tarik', 'Dessert & Manis', '20000', 'Roti bakar mentega dengan keju mozzarella melar gurih lumer.', 'assets/menu/wiki_roti_bakar.jpg', 'Viral', '1', '2026-09-23 08:08:24'),
('206', 'Churros Gula Kayu Manis Cokelat', 'Dessert & Manis', '18000', 'Kue churros spanyol renyah taburan gula kayu manis dicocol dark choco.', 'assets/menu/wiki_pudding.jpg', 'Camilan', '1', '2026-09-23 08:08:24'),
('207', 'Singkong Karamel Manis Legit', 'Dessert & Manis', '16000', 'Singkong rebus empuk dimasak karamel gula aren wangi wijen panggang.', 'assets/menu/wiki_pudding.jpg', 'Tradisional', '1', '2026-09-23 08:08:24'),
('208', 'Jus Sirsak Madu Segar', 'Minuman', '15000', 'Daging buah sirsak segar manis asam alami diblender madu murni.', 'assets/menu/wiki_es_teh.jpg', 'Segar', '1', '2026-09-23 08:08:24'),
('209', 'Jus Jambu Biji Merah Segar', 'Minuman', '14000', 'Jambu biji merah kaya vitamin C diblender halus disaring segar.', 'assets/menu/wiki_es_teh.jpg', 'Sehat', '1', '2026-09-23 08:08:24'),
('210', 'Jus Tomat Madu Segar', 'Minuman', '13000', 'Tomat merah segar manis diblender madu alami menyehatkan kulit.', 'assets/menu/wiki_es_teh.jpg', 'Sehat', '1', '2026-09-23 08:08:24'),
('211', 'Jus Wortel Jeruk Sehat', 'Minuman', '15000', 'Kombinasi sari wortel manis dan perasan jeruk segar kaya vitamin A.', 'assets/menu/wiki_es_teh.jpg', 'Detox', '1', '2026-09-23 08:08:24'),
('212', 'Es Timun Serut Jeruk Nipis', 'Minuman', '12000', 'Serutan mentimun segar dingin dengan air gula asli dan perasan jeruk nipis.', 'assets/menu/wiki_es_teh.jpg', 'Segar Dingin', '1', '2026-09-23 08:08:24'),
('213', 'Es Lidah Buaya Selasih Dingin', 'Minuman', '14000', 'Daging lidah buaya kenyal dingin biji selasih sirup pandan wangi.', 'assets/menu/wiki_es_teh.jpg', 'Pereda Panas', '1', '2026-09-23 08:08:24'),
('214', 'Es Blewah Serut Gula Aren', 'Minuman', '12000', 'Blewah serut aroma khas manis legit gula aren cair es batu segar.', 'assets/menu/wiki_es_teh.jpg', 'Tradisional', '1', '2026-09-23 08:08:24'),
('215', 'Es Kopi Susu Pandan Wangi', 'Coffee & Tea', '18000', 'Espresso arabika susu segar dengan sirup pandan alami harum manis legit.', 'assets/menu/wiki_kopi_susu.jpg', 'Favorit', '1', '2026-09-23 08:08:24'),
('216', 'Es Kopi Susu Regal Crumble', 'Coffee & Tea', '20000', 'Kopi susu gula aren dengan taburan biskuit marie regal renyah gurih.', 'assets/menu/wiki_kopi_susu.jpg', 'Best Seller', '1', '2026-09-23 08:08:24'),
('217', 'Hazelnut Latte Iced Creamy', 'Coffee & Tea', '22000', 'Espresso blend susu dingin berpadu sirup hazelnut kacang panggang wangi.', 'assets/menu/wiki_latte.jpg', 'Kopi Modern', '1', '2026-09-23 08:08:24'),
('218', 'Vanilla Latte Iced Dingin', 'Coffee & Tea', '21000', 'Espresso susu segar berpadu manis lembut sirup vanilla madagaskar.', 'assets/menu/wiki_latte.jpg', 'Favorit', '1', '2026-09-23 08:08:24'),
('219', 'Affogato Espresso Vanilla', 'Coffee & Tea', '18000', 'Satu scoop es krim vanilla dingin disiram satu shot espresso panas pekat.', 'assets/menu/wiki_espresso.jpg', 'Pecinta Kopi', '1', '2026-09-23 08:08:24'),
('220', 'Teh Telur Khas Padang Berbusa', 'Coffee & Tea', '16000', 'Kocokan kuning telur bebek teh hitam pekat susu kental manis berbusa.', 'assets/menu/wiki_kopi.jpg', 'Khas Padang', '1', '2026-09-23 08:08:24');
INSERT INTO `tabel_menu` (`id`, `nama_menu`, `kategori`, `harga`, `deskripsi`, `foto`, `tag`, `tersedia`, `created_at`) VALUES
('221', 'Bandrek Susu Jahe Rempah', 'Coffee & Tea', '14000', 'Rebusan jahe merah gula aren kayu manis cengkeh ditambah susu kental manis.', 'assets/menu/wiki_kopi.jpg', 'Hangat', '1', '2026-09-23 08:08:24'),
('222', 'Sekoteng Hangat Komplit', 'Minuman', '15000', 'Kuah jahe hangat dengan kacang tanah sangrai pacar cina roti kolang kaling.', 'assets/menu/wiki_sekoteng.jpg', 'Hangat', '1', '2026-09-23 08:08:24'),
('223', 'Susu Jahe Merah Madu Murni', 'Minuman', '14000', 'Susu sapi murni hangat dipadu ekstrak jahe merah geprek madu hutan.', 'assets/menu/wiki_es_teh.jpg', 'Sehat', '1', '2026-09-23 08:08:24'),
('224', 'Es Doger Khas Bandung Komplit', 'Minuman', '17000', 'Es serut santan merah muda ketan hitam tape singkong kelapa muda roti.', 'assets/menu/wiki_es_teh.jpg', 'Khas Bandung', '1', '2026-09-23 08:08:24'),
('225', 'Es Podeng Manten Nostalgia', 'Minuman', '16000', 'Es puter santan tradisional agar-agar alpukat roti taburan meses cokelat.', 'assets/menu/wiki_es_teh.jpg', 'Tradisional', '1', '2026-09-23 08:08:24'),
('226', 'Air Kelapa Murni Utuh Batok', 'Minuman', '18000', 'Kelapa muda utuh batok segar alami langsung disajikan dingin.', 'assets/menu/wiki_es_teh.jpg', 'Alami', '1', '2026-09-23 08:08:24'),
('227', 'Lemonade Squash Mint Soda', 'Minuman', '16000', 'Air soda dingin segar perasan lemon asli dan remasan daun mint segar.', 'assets/menu/wiki_es_teh.jpg', 'Segar Dingin', '1', '2026-09-23 08:08:24'),
('228', 'Sambal Bawang Ulek Meledak', 'Geprek & Sambal', '6000', 'Ulekan cabai rawit merah dan bawang putih segar disiram minyak panas.', 'assets/menu/wiki_nasi.jpg', 'Pedas Pol', '1', '2026-09-23 08:08:24'),
('229', 'Sambal Terasi Bakar Limau', 'Geprek & Sambal', '6000', 'Terasi udang bakar harum diulek cabai tomat dengan perasan jeruk limau.', 'assets/menu/wiki_nasi.jpg', 'Tradisional', '1', '2026-09-23 08:08:24'),
('230', 'Sambal Ijo Teri Asin', 'Geprek & Sambal', '7000', 'Cabai hijau keriting kukus ditumis dengan taburan teri asin renyah.', 'assets/menu/wiki_nasi.jpg', 'Favorit', '1', '2026-09-23 08:08:24'),
('231', 'Sambal Matah Bali Kecombrang', 'Geprek & Sambal', '7000', 'Irisan bawang merah cabai rawit serai minyak kelapa bunga kecombrang.', 'assets/menu/wiki_nasi.jpg', 'Khas Bali', '1', '2026-09-23 08:08:24'),
('232', 'Sambal Dabu-Dabu Segar Manado', 'Geprek & Sambal', '6000', 'Potongan tomat merah hijau cabai rawit bawang merah perasan jeruk nipis.', 'assets/menu/wiki_nasi.jpg', 'Segar', '1', '2026-09-23 08:08:24'),
('233', 'Sambal Korek Bawang Panas', 'Geprek & Sambal', '6000', 'Cabai rawit merah bawang putih digeprek kasar disiram minyak panas gurih.', 'assets/menu/wiki_nasi.jpg', 'Pedas Nampol', '1', '2026-09-23 08:08:24'),
('234', 'Sambal Mangga Muda Segar', 'Geprek & Sambal', '7000', 'Irisan mangga muda asam segar diulek bersama cabai merah gula terasi.', 'assets/menu/wiki_nasi.jpg', 'Pedas Asam', '1', '2026-09-23 08:08:24'),
('235', 'Sambal Tempe Semangit Pedas', 'Geprek & Sambal', '7000', 'Tempe semangit bakar gurih diulek bumbu cabai kencur bawang harum.', 'assets/menu/wiki_nasi.jpg', 'Tradisional', '1', '2026-09-23 08:08:24'),
('236', 'Sambal Petis Udang Madura', 'Geprek & Sambal', '7000', 'Petis udang hitam gurih manis khas Madura dengan ulekan cabai rawit.', 'assets/menu/wiki_nasi.jpg', 'Khas Madura', '1', '2026-09-23 08:08:24'),
('237', 'Kerupuk Kaleng Putih Gurih (2 pcs)', 'Snack & Dimsum', '4000', 'Kerupuk mawar putih bulat renyah gurih teman makan segala hidangan.', 'assets/menu/wiki_gorengan.jpg', 'Pelengkap', '1', '2026-09-23 08:08:24'),
('238', 'Kerupuk Udang Renyah Gurih', 'Snack & Dimsum', '6000', 'Kerupuk sari udang asli renyah gurih keemasan pelengkap hidangan resto.', 'assets/menu/wiki_gorengan.jpg', 'Pelengkap', '1', '2026-09-23 08:08:24'),
('239', 'Emping Melinjo Renyah Asin', 'Snack & Dimsum', '7000', 'Emping melinjo goreng garing dengan taburan garam gurih sedikit pahit nikmat.', 'assets/menu/wiki_gorengan.jpg', 'Pelengkap', '1', '2026-09-23 08:08:24'),
('240', 'Tahu Goreng Gurih Bumbu Kuning (4 pcs)', 'Snack & Dimsum', '10000', 'Tahu kuning gurih ungkep bawang ketumbar digoreng garing di luar lembut.', 'assets/menu/wiki_gorengan.jpg', 'Pelengkap', '1', '2026-09-23 08:08:24');
INSERT INTO `tabel_menu` (`id`, `nama_menu`, `kategori`, `harga`, `deskripsi`, `foto`, `tag`, `tersedia`, `created_at`) VALUES
('241', 'Tempe Goreng Ketumbar Gurih (4 pcs)', 'Snack & Dimsum', '10000', 'Tempe kedelai bumbu ketumbar bawang putih digoreng renyah harum.', 'assets/menu/wiki_gorengan.jpg', 'Pelengkap', '1', '2026-09-23 08:08:24'),
('242', 'Telur Dadar Barendo Crispy', 'Makanan Utama', '12000', 'Telur bebek kocok bumbu rempah digoreng mengembang mekar renyah keriting.', 'assets/menu/wiki_nasi.jpg', 'Best Seller', '1', '2026-09-23 08:08:24'),
('243', 'Telur Ceplok Mata Sapi', 'Makanan Utama', '6000', 'Telur ayam ceplok kuning setengah matang atau matang sesuai selera.', 'assets/menu/wiki_nasi.jpg', 'Pelengkap', '1', '2026-09-23 08:08:24'),
('244', 'Telur Asin Brebes Masir Gurih', 'Makanan Utama', '8000', 'Telur bebek asin asli Brebes kuning telur berminyak masir gurih legit.', 'assets/menu/wiki_nasi.jpg', 'Favorit', '1', '2026-09-23 08:08:24'),
('245', 'Kol Goreng Gurih Crispy', 'Makanan Utama', '8000', 'Kol segar digoreng renyah garing manis gurih pendamping ayam penyet.', 'assets/menu/wiki_nasi.jpg', 'Favorit', '1', '2026-09-23 08:08:24'),
('246', 'Pete Bakar Harum', 'Makanan Utama', '12000', 'Satu papan petai segar dibakar di atas arang harum legit menggugah selera.', 'assets/menu/wiki_nasi.jpg', 'Tradisional', '1', '2026-09-23 08:08:24'),
('247', 'Terong Goreng Sambal Bawang', 'Makanan Utama', '12000', 'Irisan terong ungu digoreng gurih disajikan ulekan sambal bawang pedas.', 'assets/menu/wiki_nasi.jpg', 'Favorit', '1', '2026-09-23 08:08:24'),
('248', 'Smoothie Mangga Buah Naga', 'Minuman', '18000', 'Lapisan smoothie mangga kental kuning dan buah naga merah segar alami.', 'assets/menu/wiki_es_teh.jpg', 'Sehat', '1', '2026-09-23 08:08:24'),
('249', 'Smoothie Strawberry Banana Berry', 'Minuman', '19000', 'Stroberi segar dan pisang manis diblender yoghurt kental kaya serat.', 'assets/menu/wiki_es_teh.jpg', 'Sehat', '1', '2026-09-23 08:08:24'),
('250', 'Avocado Coffee Float Tyas', 'Coffee & Tea', '23000', 'Jus alpukat mentega kental disiram espresso arabika satu scoop es krim.', 'assets/menu/wiki_kopi.jpg', 'Chef Pick', '1', '2026-09-23 08:08:24'),
('251', 'Matcha Espresso Fusion Cold', 'Coffee & Tea', '22000', 'Gradasi hijau matcha Jepang susu dingin dan espresso arabika pekat.', 'assets/menu/wiki_espresso.jpg', 'Kopi Modern', '1', '2026-09-23 08:08:24'),
('252', 'Ice Cream Gelato Cokelat Vanilla', 'Dessert & Manis', '16000', 'Dua scoop gelato lembut rasa cokelat pekat dan vanilla creamy.', 'assets/menu/wiki_pudding.jpg', 'Manis', '1', '2026-09-23 08:08:24'),
('253', 'Kelapa Kopyor Es Sirup Cocopandan', 'Minuman', '20000', 'Daging kelapa kopyor lembut berpadu es sirup cocopandan manis wangi.', 'assets/menu/wiki_es_teh.jpg', 'Spesial', '1', '2026-09-23 08:08:24'),
('254', 'Paket Hemat 1: Nasi + Ayam Geprek + Es Teh', 'Paket Hemat', '25000', 'Nasi hangat, ayam geprek sambal bawang pedas dan es teh melati jumbo.', 'assets/menu/wiki_ayam_geprek.jpg', 'Paket Hemat', '1', '2026-09-23 08:08:24'),
('255', 'Paket Hemat 2: Nasi + Ayam Bakar Madu + Sayur Asem + Es Teh', 'Paket Hemat', '32000', 'Nasi putih, ayam bakar madu legit, sayur asem segar dan es teh manis.', 'assets/menu/wiki_ayam_bakar.jpg', 'Best Seller', '1', '2026-09-23 08:08:24'),
('256', 'Paket Hemat 3: Nasi Bakar Cumi + Tahu Tempe + Teh Manis', 'Paket Hemat', '31000', 'Nasi bakar cumi cabe ijo daun pisang, tahu tempe goreng dan teh manis.', 'assets/menu/wiki_nasi_bakar.jpg', 'Favorit', '1', '2026-09-23 08:08:24'),
('257', 'Paket Hemat 4: Mie Ayam Jamur + Pangsit Goreng + Es Jeruk', 'Paket Hemat', '28000', 'Mie ayam jamur kenyal, 2 pangsit goreng renyah dan es jeruk peras murni.', 'assets/menu/wiki_mie_ayam.jpg', 'Favorit', '1', '2026-09-23 08:08:24'),
('258', 'Paket Hemat 5: Nasi Goreng Spesial + Telur Ceplok + Es Cincau', 'Paket Hemat', '30000', 'Nasi goreng spesial ayam sosis, telur ceplok dan es cincau gula aren.', 'assets/menu/wiki_nasi_goreng.jpg', 'Kenyang', '1', '2026-09-23 08:08:24'),
('259', 'Paket Kenyang 6: Nasi Timbel Komplit + Ayam Lengkuas + Es Teh Manis', 'Paket Hemat', '37000', 'Nasi timbel daun, ayam lengkuas serundeng, tahu tempe, lalap sambal, es teh.', 'assets/menu/wiki_nasi_timbel.jpg', 'Komplit', '1', '2026-09-23 08:08:24'),
('260', 'Paket Seafood 7: Nasi + Cumi Goreng Tepung + Sambal Bawang + Es Lemon Tea', 'Paket Hemat', '36000', 'Nasi putih, calamari cumi krispi saus tartar sambal bawang dan es lemon tea.', 'assets/menu/photo-1544145945-f90425340c7e.jpg', 'Seafood Hemat', '1', '2026-09-23 08:08:24');
INSERT INTO `tabel_menu` (`id`, `nama_menu`, `kategori`, `harga`, `deskripsi`, `foto`, `tag`, `tersedia`, `created_at`) VALUES
('261', 'Paket Seafood 8: Nasi + Udang Bakar Madu + Lalapan + Es Kelapa Muda', 'Paket Hemat', '39000', 'Nasi putih, udang bakar jimbaran, lalapan segar dan es kelapa muda asli.', 'assets/menu/photo-1517256064527-09c73fc73e38.jpg', 'Seafood Nikmat', '1', '2026-09-23 08:08:24'),
('262', 'Paket Ngemil Santai: Dimsum (4 pcs) + Pangsit Goreng (3 pcs) + Kopi Susu', 'Paket Hemat', '35000', 'Dimsum ayam udang, pangsit goreng renyah dan kopi susu gula aren Tyas.', 'assets/menu/wiki_shumai.jpg', 'Ngemil Santai', '1', '2026-09-23 08:08:24'),
('263', 'Paket Keluarga Tyas (4 Orang): 1 Gurame + 2 Ayam + 4 Nasi + Kangkung + 4 Teh', 'Paket Hemat', '135000', 'Porsi besar 4 orang: 1 Gurame terbang asam manis, 2 ayam bakar madu, 4 nasi, cah kangkung, 4 es teh.', 'assets/menu/wiki_nasi.jpg', 'Family Feast', '1', '2026-09-23 08:08:24');

-- --------------------------------------------------------
-- Struktur Tabel `tabel_pesanan`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `tabel_pesanan`;
CREATE TABLE `tabel_pesanan` (
  `id` int NOT NULL AUTO_INCREMENT,
  `kode_pesanan` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nomor_meja` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama_pelanggan` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT 'Pelanggan',
  `tipe_pesanan` enum('dine_in','take_away') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'dine_in',
  `catatan` text COLLATE utf8mb4_unicode_ci,
  `subtotal` int NOT NULL DEFAULT '0',
  `pajak` int NOT NULL DEFAULT '0',
  `total_bayar` int NOT NULL DEFAULT '0',
  `uang_diterima` int DEFAULT '0',
  `uang_kembali` int DEFAULT '0',
  `status_pesanan` enum('menunggu_kasir','dimasak_dapur','siap_saji','selesai','batal') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'menunggu_kasir',
  `status_bayar` enum('belum_bayar','lunas') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'belum_bayar',
  `metode_bayar` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT 'Kasir',
  `minta_struk` tinyint(1) DEFAULT '0',
  `diterima_kasir_oleh` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `peringatan_keluar` tinyint(1) DEFAULT '0',
  `waktu_pesan` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `waktu_update` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `kode_pesanan` (`kode_pesanan`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Struktur Tabel `tabel_detail_pesanan`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `tabel_detail_pesanan`;
CREATE TABLE `tabel_detail_pesanan` (
  `id` int NOT NULL AUTO_INCREMENT,
  `pesanan_id` int NOT NULL,
  `menu_id` int NOT NULL,
  `nama_menu` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `harga_satuan` int NOT NULL,
  `jumlah` int NOT NULL,
  `subtotal` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `pesanan_id` (`pesanan_id`),
  CONSTRAINT `tabel_detail_pesanan_ibfk_1` FOREIGN KEY (`pesanan_id`) REFERENCES `tabel_pesanan` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

SET FOREIGN_KEY_CHECKS = 1;
