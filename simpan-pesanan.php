<?php
/**
 * API Backend untuk menyimpan pesanan baru ke MySQL
 * Dapur Tyas by Techno Garage
 */

header('Content-Type: application/json');
require_once __DIR__ . '/config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status' => 'error', 'message' => 'Hanya menerima request POST']);
    exit;
}

$raw = file_get_contents('php://input');
$data = json_decode($raw, true);

if (!$data || empty($data['items']) || empty($data['nomor_meja'])) {
    echo json_encode(['status' => 'error', 'message' => 'Data pesanan atau nomor meja tidak lengkap']);
    exit;
}

$nomorMeja = htmlspecialchars(trim($data['nomor_meja']));
$namaPelanggan = htmlspecialchars(trim($data['nama_pelanggan'] ?? "Pelanggan Meja {$nomorMeja}"));
$tipePesanan = in_array($data['tipe_pesanan'] ?? '', ['dine_in', 'take_away']) ? $data['tipe_pesanan'] : 'dine_in';
$catatan = htmlspecialchars(trim($data['catatan'] ?? ''));
$metodeBayar = htmlspecialchars(trim($data['metode_bayar'] ?? 'kasir'));
$mintaStruk = !empty($data['minta_struk']) ? 1 : 0;
$items = $data['items'];

// Hitung subtotal dan total
$subtotal = 0;
foreach ($items as $it) {
    $subtotal += ($it['harga'] * $it['qty']);
}
$pajak = (int) round($subtotal * 0.10);
$totalBayar = $subtotal + $pajak;

// Standarkan kode meja untuk kode pesanan (misal 01, 02, VIP-1)
$cleanMejaTag = preg_replace('/[^A-Za-z0-9]/', '', $nomorMeja);
if (strlen($cleanMejaTag) === 1) {
    $cleanMejaTag = '0' . $cleanMejaTag;
}

// Buat Kode Pesanan Unik per Meja & Waktu (Contoh: TYAS-M01-260922-8492)
$kodePesanan = 'TYAS-M' . $cleanMejaTag . '-' . date('ymd') . '-' . rand(1000, 9999);

try {
    $pdo->beginTransaction();

    // 1. Simpan Header Pesanan (status_bayar: jika ewallet tetap menunggu verifikasi/konfirmasi kasir, metode_bayar tersimpan, minta_struk tersimpan)
    $stmt = $pdo->prepare("
        INSERT INTO tabel_pesanan 
        (kode_pesanan, nomor_meja, nama_pelanggan, tipe_pesanan, catatan, subtotal, pajak, total_bayar, status_pesanan, status_bayar, metode_bayar, minta_struk)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'menunggu_kasir', 'belum_bayar', ?, ?)
    ");
    $stmt->execute([$kodePesanan, $nomorMeja, $namaPelanggan, $tipePesanan, $catatan, $subtotal, $pajak, $totalBayar, $metodeBayar, $mintaStruk]);
    $pesananId = $pdo->lastInsertId();

    // 2. Simpan Detail Items
    $stmtDetail = $pdo->prepare("
        INSERT INTO tabel_detail_pesanan 
        (pesanan_id, menu_id, nama_menu, harga_satuan, jumlah, subtotal)
        VALUES (?, ?, ?, ?, ?, ?)
    ");

    foreach ($items as $it) {
        $itemSubtotal = $it['harga'] * $it['qty'];
        $stmtDetail->execute([
            $pesananId,
            $it['menu_id'],
            $it['nama_menu'],
            $it['harga'],
            $it['qty'],
            $itemSubtotal
        ]);
    }

    $pdo->commit();

    echo json_encode([
        'status' => 'success',
        'message' => 'Pesanan berhasil dikirim ke Kasir Dapur Tyas',
        'kode_pesanan' => $kodePesanan,
        'pesanan_id' => $pesananId
    ]);

} catch (Exception $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    echo json_encode([
        'status' => 'error',
        'message' => 'Gagal menyimpan pesanan: ' . $e->getMessage()
    ]);
}
?>
