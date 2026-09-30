<?php
require_once __DIR__ . '/config.php';

try {
    // Cek apakah kolom minta_struk sudah ada
    $check = $pdo->query("SHOW COLUMNS FROM `tabel_pesanan` LIKE 'minta_struk'")->fetch();
    if (!$check) {
        $pdo->exec("ALTER TABLE `tabel_pesanan` ADD COLUMN `minta_struk` TINYINT(1) DEFAULT 0 AFTER `metode_bayar`");
        echo "Kolom minta_struk berhasil ditambahkan ke tabel_pesanan.\n";
    } else {
        echo "Kolom minta_struk sudah ada.\n";
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
