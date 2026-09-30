<?php
/**
 * API Backend untuk mengecek pesanan terbaru & audio notifikasi
 * Dapur Tyas by Techno Garage
 */

header('Content-Type: application/json');
require_once __DIR__ . '/config.php';

if (empty($_SESSION['staf_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
    exit;
}

$lastId = (int) ($_GET['last_id'] ?? 0);

try {
    // Cek pesanan baru dengan ID lebih besar dari lastId
    $stmt = $pdo->prepare("
        SELECT id, kode_pesanan, nomor_meja, nama_pelanggan, tipe_pesanan, total_bayar, metode_bayar, status_pesanan, waktu_pesan
        FROM tabel_pesanan
        WHERE id > ? AND status_pesanan = 'menunggu_kasir'
        ORDER BY id ASC
    ");
    $stmt->execute([$lastId]);
    $newOrders = $stmt->fetchAll();

    // Dapatkan max ID saat ini
    $maxStmt = $pdo->query("SELECT MAX(id) as max_id FROM tabel_pesanan");
    $maxRow = $maxStmt->fetch();
    $currentMaxId = (int) ($maxRow['max_id'] ?? 0);

    // Cek apakah ada pesanan yang mencoba keluar paksa saat belum lunas
    $stmtPeringatan = $pdo->query("
        SELECT id, kode_pesanan, nomor_meja, nama_pelanggan, total_bayar, metode_bayar
        FROM tabel_pesanan 
        WHERE peringatan_keluar = 1 AND status_bayar = 'belum_bayar'
        ORDER BY id DESC
    ");
    $unpaidAlerts = $stmtPeringatan->fetchAll();
    foreach ($unpaidAlerts as &$ua) {
        $ua['total_bayar_rp'] = formatRupiah($ua['total_bayar']);
    }
    unset($ua);

    echo json_encode([
        'status' => 'success',
        'has_new' => count($newOrders) > 0,
        'count' => count($newOrders),
        'orders' => $newOrders,
        'has_security_alert' => count($unpaidAlerts) > 0,
        'security_alerts' => $unpaidAlerts,
        'max_id' => $currentMaxId
    ]);
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
