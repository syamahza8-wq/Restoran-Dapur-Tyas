<?php
require_once __DIR__ . '/config.php';

try {
    // 1. Kolom peringatan_keluar
    $col1 = $pdo->query("SHOW COLUMNS FROM tabel_pesanan LIKE 'peringatan_keluar'")->fetch();
    if (!$col1) {
        $pdo->exec("ALTER TABLE tabel_pesanan ADD COLUMN peringatan_keluar TINYINT(1) DEFAULT 0 AFTER diterima_kasir_oleh");
        echo "Kolom peringatan_keluar ditambahkan.\n";
    }

    // 2. Kolom uang_diterima
    $col2 = $pdo->query("SHOW COLUMNS FROM tabel_pesanan LIKE 'uang_diterima'")->fetch();
    if (!$col2) {
        $pdo->exec("ALTER TABLE tabel_pesanan ADD COLUMN uang_diterima INT DEFAULT 0 AFTER total_bayar");
        echo "Kolom uang_diterima ditambahkan.\n";
    }

    // 3. Kolom uang_kembali
    $col3 = $pdo->query("SHOW COLUMNS FROM tabel_pesanan LIKE 'uang_kembali'")->fetch();
    if (!$col3) {
        $pdo->exec("ALTER TABLE tabel_pesanan ADD COLUMN uang_kembali INT DEFAULT 0 AFTER uang_diterima");
        echo "Kolom uang_kembali ditambahkan.\n";
    }

    echo "Migrasi database selesai sukses!\n";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
