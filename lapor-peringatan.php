<?php
/**
 * API Lapor Peringatan Keluar Paksa Pelanggan (Anti-Kabur / Belum Bayar)
 * Dapur Tyas by Techno Garage
 */

header('Content-Type: application/json');
require_once __DIR__ . '/config.php';

$raw = file_get_contents('php://input');
$data = json_decode($raw, true);

$kode = trim($data['kode'] ?? ($_POST['kode'] ?? ''));

if (empty($kode)) {
    echo json_encode(['status' => 'error', 'message' => 'Kode pesanan kosong']);
    exit;
}

try {
    $stmt = $pdo->prepare("SELECT id, nomor_meja, total_bayar, status_bayar FROM tabel_pesanan WHERE kode_pesanan = ?");
    $stmt->execute([$kode]);
    $pesanan = $stmt->fetch();

    if ($pesanan && $pesanan['status_bayar'] !== 'lunas') {
        // Tandai peringatan keluar = 1 agar kasir berbunyi alarm peringatan
        $stmtUp = $pdo->prepare("UPDATE tabel_pesanan SET peringatan_keluar = 1 WHERE id = ?");
        $stmtUp->execute([$pesanan['id']]);

        echo json_encode(['status' => 'success', 'message' => 'Peringatan terkirim ke POS kasir']);
    } else {
        echo json_encode(['status' => 'ignored', 'message' => 'Sudah lunas atau tidak ditemukan']);
    }
} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
