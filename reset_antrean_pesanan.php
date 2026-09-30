<?php
/**
 * Script Reset / Hapus Semua Antrean Pesanan & Omzet Dapur Tyas
 */
require_once __DIR__ . '/config.php';

try {
    $pdo->exec("SET FOREIGN_KEY_CHECKS = 0;");
    $pdo->exec("TRUNCATE TABLE tabel_detail_pesanan;");
    $pdo->exec("TRUNCATE TABLE tabel_pesanan;");
    $pdo->exec("SET FOREIGN_KEY_CHECKS = 1;");

    echo "BERHASIL: Semua data antrean pesanan berhasil dihapus dan total omzet transaksi lunas telah kembali ke Rp 0!\n";
} catch (PDOException $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}
