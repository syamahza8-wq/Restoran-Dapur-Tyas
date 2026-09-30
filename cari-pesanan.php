<?php
/**
 * API Cari Pesanan Berdasarkan Barcode / Kode Pesanan
 * Dapur Tyas by Techno Garage
 */

header('Content-Type: application/json');
require_once __DIR__ . '/config.php';

if (empty($_SESSION['staf_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Sesi login telah berakhir']);
    exit;
}

$kode = trim($_GET['kode'] ?? '');
if (empty($kode)) {
    echo json_encode(['status' => 'error', 'message' => 'Kode pesanan tidak boleh kosong']);
    exit;
}

// Bersihkan karakter awalan/akhiran yang tidak perlu
$kode = trim(str_replace('#', '', $kode));

// Ekstrak kode pesanan jika ada format URL atau teks panjang (misal TYAS-M01-260922-1234 atau TYAS-260922-123)
if (preg_match('/(TYAS-[A-Z0-9\-]+)/i', $kode, $matches)) {
    $kode = strtoupper(rtrim($matches[1], '-'));
}

try {
    // 1. Prioritaskan cari pesanan yang belum bayar / menunggu kasir jika ada query
    $stmt = $pdo->prepare("SELECT * FROM tabel_pesanan WHERE kode_pesanan = ? ORDER BY (status_bayar = 'belum_bayar') DESC, id DESC LIMIT 1");
    $stmt->execute([$kode]);
    $pesanan = $stmt->fetch();

    if (!$pesanan) {
        // Coba cari LIKE jika ada awalan atau akhiran spasi
        $stmtLike = $pdo->prepare("SELECT * FROM tabel_pesanan WHERE kode_pesanan LIKE ? ORDER BY (status_bayar = 'belum_bayar') DESC, id DESC LIMIT 1");
        $stmtLike->execute(['%' . $kode . '%']);
        $pesanan = $stmtLike->fetch();
    }

    if (!$pesanan) {
        echo json_encode([
            'status' => 'error', 
            'message' => "Pesanan dengan kode {$kode} tidak ditemukan di sistem."
        ]);
        exit;
    }

    // Ambil detail rincian menu
    $stmtItems = $pdo->prepare("SELECT * FROM tabel_detail_pesanan WHERE pesanan_id = ?");
    $stmtItems->execute([$pesanan['id']]);
    $items = $stmtItems->fetchAll();

    echo json_encode([
        'status' => 'success',
        'order' => [
            'id' => $pesanan['id'],
            'kode_pesanan' => $pesanan['kode_pesanan'],
            'nomor_meja' => $pesanan['nomor_meja'],
            'nama_pelanggan' => $pesanan['nama_pelanggan'],
            'catatan' => $pesanan['catatan'],
            'subtotal' => (int)$pesanan['subtotal'],
            'pajak' => (int)$pesanan['pajak'],
            'total_bayar' => (int)$pesanan['total_bayar'],
            'total_bayar_rp' => formatRupiah($pesanan['total_bayar']),
            'uang_diterima' => (int)($pesanan['uang_diterima'] ?? 0),
            'uang_kembali' => (int)($pesanan['uang_kembali'] ?? 0),
            'status_pesanan' => $pesanan['status_pesanan'],
            'status_bayar' => $pesanan['status_bayar'],
            'metode_bayar' => $pesanan['metode_bayar'],
            'waktu_pesan' => date('d/m/Y H:i', strtotime($pesanan['waktu_pesan'])),
            'items' => array_map(function($it) {
                return [
                    'nama_menu' => $it['nama_menu'],
                    'jumlah' => (int)$it['jumlah'],
                    'harga_satuan' => (int)$it['harga_satuan'],
                    'subtotal' => (int)$it['subtotal'],
                    'harga_satuan_rp' => formatRupiah($it['harga_satuan']),
                    'subtotal_rp' => formatRupiah($it['subtotal'])
                ];
            }, $items)
        ]
    ]);
} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
