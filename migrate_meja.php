<?php
require_once __DIR__ . '/config.php';

try {
    // Buat tabel_meja jika belum ada
    $pdo->exec("CREATE TABLE IF NOT EXISTS `tabel_meja` (
        `id` INT AUTO_INCREMENT PRIMARY KEY,
        `nomor_meja` VARCHAR(20) NOT NULL UNIQUE,
        `nama_area` VARCHAR(50) DEFAULT 'Utama',
        `kapasitas` INT DEFAULT 4,
        `status` ENUM('tersedia', 'terisi', 'reservasi') DEFAULT 'tersedia',
        `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");

    // Cek apakah tabel masih kosong, jika ya buat default meja 01 sampai 12
    $count = $pdo->query("SELECT COUNT(*) FROM tabel_meja")->fetchColumn();
    if ($count == 0) {
        $stmt = $pdo->prepare("INSERT INTO tabel_meja (nomor_meja, nama_area, kapasitas, status) VALUES (?, ?, ?, 'tersedia')");
        for ($i = 1; $i <= 12; $i++) {
            $nomor = str_pad($i, 2, '0', STR_PAD_LEFT);
            $area = ($i <= 6) ? 'Indoor AC' : 'Outdoor Garden';
            $stmt->execute([$nomor, $area, 4]);
        }
        echo "Tabel meja berhasil dibuat dan diisi default 12 meja!\n";
    } else {
        echo "Tabel meja sudah ada dengan {$count} meja.\n";
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
