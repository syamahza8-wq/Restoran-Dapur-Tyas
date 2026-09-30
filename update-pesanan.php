<?php
/**
 * Update Status Pesanan & Transaksi Kasir
 * Dapur Tyas by Techno Garage
 */

header('Content-Type: application/json');
require_once __DIR__ . '/config.php';

if (empty($_SESSION['staf_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Sesi login telah berakhir']);
    exit;
}

$raw = file_get_contents('php://input');
$data = json_decode($raw, true);

$pesananId = (int) ($data['id'] ?? 0);
$statusPesanan = $data['status_pesanan'] ?? null;
$statusBayar = $data['status_bayar'] ?? null;
$metodeBayar = $data['metode_bayar'] ?? null;
$uangDiterima = isset($data['uang_diterima']) ? (int)$data['uang_diterima'] : null;
$uangKembali = isset($data['uang_kembali']) ? (int)$data['uang_kembali'] : null;

$action = $data['action'] ?? '';

// Opsi mematikan / reset peringatan darurat dari kasir
if ($action === 'reset_peringatan') {
    if ($pesananId > 0) {
        $stmt = $pdo->prepare("UPDATE tabel_pesanan SET peringatan_keluar = 0 WHERE id = ?");
        $stmt->execute([$pesananId]);
    } else {
        $pdo->query("UPDATE tabel_pesanan SET peringatan_keluar = 0 WHERE peringatan_keluar = 1");
    }
    echo json_encode(['status' => 'success', 'message' => 'Peringatan berhasil dinonaktifkan']);
    exit;
}

if (!$pesananId) {
    echo json_encode(['status' => 'error', 'message' => 'ID pesanan tidak valid']);
    exit;
}

try {
    $updates = [];
    $params = [];

    if ($statusPesanan) {
        $updates[] = "status_pesanan = ?";
        $params[] = $statusPesanan;

        if ($statusPesanan === 'dimasak_dapur') {
            $updates[] = "diterima_kasir_oleh = ?";
            $params[] = $_SESSION['staf_nama'];
        } elseif ($statusPesanan === 'selesai') {
            $updates[] = "diterima_kasir_oleh = ?";
            $params[] = $_SESSION['staf_nama'];
            $updates[] = "status_bayar = 'lunas'";
            $updates[] = "peringatan_keluar = 0";
        }
    }

    if ($statusBayar) {
        $updates[] = "status_bayar = ?";
        $params[] = $statusBayar;
        if ($statusBayar === 'lunas') {
            $updates[] = "peringatan_keluar = 0";
        }
    }

    if ($metodeBayar) {
        $updates[] = "metode_bayar = ?";
        $params[] = $metodeBayar;
    }

    if ($uangDiterima !== null) {
        $updates[] = "uang_diterima = ?";
        $params[] = $uangDiterima;
    }

    if ($uangKembali !== null) {
        $updates[] = "uang_kembali = ?";
        $params[] = $uangKembali;
    }

    if (!empty($updates)) {
        $sql = "UPDATE tabel_pesanan SET " . implode(", ", $updates) . " WHERE id = ?";
        $params[] = $pesananId;
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
    }

    echo json_encode(['status' => 'success', 'message' => 'Pesanan berhasil diperbarui']);
} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
?>
