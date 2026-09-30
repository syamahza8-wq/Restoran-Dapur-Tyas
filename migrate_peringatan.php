<?php
require_once __DIR__ . '/config.php';

try {
    // Cek apakah kolom peringatan_keluar sudah ada
    $check = $pdo->query("SHOW COLUMNS FROM tabel_pesanan LIKE 'peringatan_keluar'")->fetch();
    if (!$check) {
        $pdo->exec("ALTER TABLE tabel_pesanan ADD COLUMN peringatan_keluar TINYINT(1) DEFAULT 0 AFTER diterima_kasir_oleh");
        echo "Kolom peringatan_keluar berhasil ditambahkan!\n";
    } else {
        echo "Kolom peringatan_keluar sudah ada.\n";
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
