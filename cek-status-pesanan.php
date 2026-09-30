<?php
/**
 * API Cek Status Pesanan Khusus Pelanggan (Polling Real-Time Status & Pembayaran)
 * Dapur Tyas by Techno Garage
 */

header('Content-Type: application/json');
require_once __DIR__ . '/config.php';

$kode = trim($_GET['kode'] ?? '');
if (empty($kode)) {
    echo json_encode(['status' => 'error', 'message' => 'Kode kosong']);
    exit;
}

try {
    $stmt = $pdo->prepare("SELECT id, kode_pesanan, nomor_meja, total_bayar, status_pesanan, status_bayar, metode_bayar, uang_diterima, uang_kembali, waktu_update FROM tabel_pesanan WHERE kode_pesanan = ?");
    $stmt->execute([$kode]);
    $p = $stmt->fetch();

    if (!$p) {
        echo json_encode(['status' => 'error', 'message' => 'Pesanan tidak ditemukan']);
        exit;
    }

    echo json_encode([
        'status' => 'success',
        'is_lunas' => ($p['status_bayar'] === 'lunas'),
        'status_pesanan' => $p['status_pesanan'],
        'status_bayar' => $p['status_bayar'],
        'metode_bayar' => $p['metode_bayar'],
        'total_bayar' => (int)$p['total_bayar'],
        'uang_diterima' => (int)($p['uang_diterima'] ?? 0),
        'uang_kembali' => (int)($p['uang_kembali'] ?? 0),
        'total_bayar_rp' => formatRupiah($p['total_bayar']),
        'uang_diterima_rp' => formatRupiah($p['uang_diterima'] ?? 0),
        'uang_kembali_rp' => formatRupiah($p['uang_kembali'] ?? 0),
    ]);
} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
