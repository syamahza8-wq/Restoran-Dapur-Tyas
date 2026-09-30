<?php
/**
 * Update Permintaan Struk dari Pelanggan
 * Dapur Tyas by Techno Garage
 */

header('Content-Type: application/json');
require_once __DIR__ . '/config.php';

$raw = file_get_contents('php://input');
$data = json_decode($raw, true);

$kode = trim($data['kode'] ?? '');
$mintaStruk = isset($data['minta_struk']) ? (int)$data['minta_struk'] : 0;

if (empty($kode)) {
    echo json_encode(['status' => 'error', 'message' => 'Kode pesanan kosong']);
    exit;
}

try {
    $stmt = $pdo->prepare("UPDATE tabel_pesanan SET minta_struk = ? WHERE kode_pesanan = ?");
    $stmt->execute([$mintaStruk, $kode]);

    echo json_encode([
        'status' => 'success',
        'minta_struk' => $mintaStruk,
        'message' => $mintaStruk ? 'Permintaan struk fisik telah disampaikan ke Kasir' : 'Diatur ke mode Paperless (E-Struk Digital)'
    ]);
} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
