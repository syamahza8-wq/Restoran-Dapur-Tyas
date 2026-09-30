<?php
/**
 * Cetak Struk Pesanan Pelanggan (Thermal Receipt Format)
 * Dapur Tyas by Techno Garage
 * Format Kertas: Thermal Printer 58mm / 80mm & Mobile Screen
 */

require_once __DIR__ . '/config.php';

$kode = trim($_GET['kode'] ?? '');
$autoprint = isset($_GET['autoprint']) && $_GET['autoprint'] == '1';

$pesanan = null;
$detailItems = [];

if (!empty($kode)) {
    $stmt = $pdo->prepare("SELECT * FROM tabel_pesanan WHERE kode_pesanan = ?");
    $stmt->execute([$kode]);
    $pesanan = $stmt->fetch();

    if ($pesanan) {
        $stmtItems = $pdo->prepare("SELECT * FROM tabel_detail_pesanan WHERE pesanan_id = ?");
        $stmtItems->execute([$pesanan['id']]);
        $detailItems = $stmtItems->fetchAll();
    }
}

if (!$pesanan) {
    die("<h3>Pesanan tidak ditemukan atau kode tidak valid.</h3><a href='index.php'>Kembali ke Beranda</a>");
}

$subtotal = 0;
foreach ($detailItems as $it) {
    $subtotal += ($it['harga_satuan'] * $it['jumlah']);
}
$ppn = (int)($pesanan['total_bayar'] - $subtotal);
if ($ppn < 0) $ppn = 0;

$isLunas = ($pesanan['status_bayar'] === 'lunas');
$tipeLabel = ($pesanan['tipe_pesanan'] ?? '') === 'take_away' ? 'TAKE AWAY (BUNGKUS)' : 'DINE IN (MAKAN DI TEMPAT)';
$waktuPesan = !empty($pesanan['waktu_pesan']) ? date('d/m/Y H:i', strtotime($pesanan['waktu_pesan'])) : date('d/m/Y H:i');
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
  <title>Struk Pesanan #<?= htmlspecialchars($pesanan['kode_pesanan']) ?> - Dapur Tyas</title>
  
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com">
  <link href="https://fonts.googleapis.com/css2?family=Courier+Prime:ital,wght@0,400;0,700;1,400&family=Plus+Jakarta+Sans:wght@500;700;800&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>

  <style>
    :root {
      --primary-color: #0F5132;
    }

    body {
      background-color: #F1F5F9;
      font-family: 'Plus Jakarta Sans', sans-serif;
      margin: 0;
      padding: 20px 10px;
    }

    .receipt-container {
      max-width: 380px;
      margin: 0 auto;
      background: #FFFFFF;
      padding: 24px 20px;
      border-radius: 12px;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
      font-family: 'Courier Prime', Courier, monospace;
      color: #000000;
      line-height: 1.35;
      font-size: 13px;
    }

    .receipt-divider {
      border-top: 1px dashed #555;
      margin: 10px 0;
    }

    .receipt-divider-double {
      border-top: 2px dashed #000;
      margin: 10px 0;
    }

    .receipt-header {
      text-align: center;
      margin-bottom: 8px;
    }

    .receipt-header h4 {
      font-size: 18px;
      font-weight: 800;
      margin: 0;
      letter-spacing: 0.5px;
    }

    .receipt-header p {
      margin: 2px 0;
      font-size: 11px;
      color: #333;
    }

    .receipt-row {
      display: flex;
      justify-content: space-between;
      margin-bottom: 3px;
    }

    .receipt-status-badge {
      display: inline-block;
      padding: 3px 12px;
      border-radius: 4px;
      font-weight: 800;
      font-size: 12px;
      letter-spacing: 1px;
      text-align: center;
      margin: 4px 0;
    }

    .status-lunas {
      border: 1.5px solid #000;
      background: #E8F5E9;
      color: #000;
    }

    .status-belum-lunas {
      border: 1.5px dashed #000;
      background: #FFF3E0;
      color: #000;
    }

    .action-bar {
      max-width: 380px;
      margin: 0 auto 16px auto;
      display: flex;
      gap: 8px;
    }

    @media print {
      body {
        background-color: #FFFFFF !important;
        padding: 0 !important;
        margin: 0 !important;
      }

      .no-print {
        display: none !important;
      }

      .receipt-container {
        max-width: 100% !important;
        width: 100% !important;
        box-shadow: none !important;
        border-radius: 0 !important;
        padding: 5px !important;
        margin: 0 !important;
        font-size: 12px !important;
      }

      @page {
        margin: 0;
        size: auto;
      }
    }
  </style>
</head>
<body>

  <!-- Floating Action Buttons (Hidden when printing) -->
  <div class="action-bar no-print">
    <a href="sukses.php?kode=<?= urlencode($pesanan['kode_pesanan']) ?>" class="btn btn-outline-secondary btn-sm rounded-pill flex-grow-1">
      <i class="bi bi-arrow-left me-1"></i> Status Pesanan
    </a>
    <button onclick="window.print()" class="btn btn-dark btn-sm rounded-pill px-3 fw-bold flex-grow-1 shadow-sm">
      <i class="bi bi-printer-fill me-1"></i> Cetak Struk (Print)
    </button>
  </div>

  <!-- Thermal Receipt Structure -->
  <div class="receipt-container" id="printableReceipt">
    
    <!-- Header -->
    <div class="receipt-header">
      <div class="d-flex align-items-center justify-content-center gap-2 mb-1">
        <img src="tyas.png" alt="Logo" style="width: 34px; height: 34px; border-radius: 50%; object-fit: cover; border: 1px solid #000;">
        <span style="font-size: 16px; font-weight: 800;">DAPUR TYAS</span>
      </div>
      <p>Techno Garage Smart Resto</p>
      <p>Jaringan Wi-Fi Self-Order Lokal</p>
      <p>Telp / WA: 0812-3456-7890</p>
    </div>

    <div class="receipt-divider-double"></div>

    <!-- Status Pembayaran Struk -->
    <div class="text-center my-1">
      <?php if ($isLunas): ?>
        <div class="receipt-status-badge status-lunas">*** BUKTI PEMBAYARAN LUNAS ***</div>
      <?php else: ?>
        <div class="receipt-status-badge status-belum-lunas">*** TIKET PESANAN / BELUM LUNAS ***</div>
      <?php endif; ?>
    </div>

    <div class="receipt-divider"></div>

    <!-- Metadata Transaksi -->
    <div class="receipt-row">
      <span>No. Tiket</span>
      <strong>#<?= htmlspecialchars($pesanan['kode_pesanan']) ?></strong>
    </div>
    <div class="receipt-row">
      <span>Tanggal</span>
      <span><?= $waktuPesan ?></span>
    </div>
    <div class="receipt-row">
      <span>Nomor Meja</span>
      <strong>MEJA <?= htmlspecialchars($pesanan['nomor_meja']) ?></strong>
    </div>
    <div class="receipt-row">
      <span>Pemesan</span>
      <span><?= htmlspecialchars($pesanan['nama_pelanggan'] ?: 'Pelanggan') ?></span>
    </div>
    <div class="receipt-row">
      <span>Layanan</span>
      <span><?= $tipeLabel ?></span>
    </div>
    <div class="receipt-row">
      <span>Kasir/Staf</span>
      <span><?= htmlspecialchars($pesanan['diterima_kasir_oleh'] ?: 'Self-Order System') ?></span>
    </div>

    <div class="receipt-divider"></div>

    <!-- Daftar Item Menu -->
    <div class="mb-2">
      <?php foreach ($detailItems as $item): ?>
        <div class="receipt-row" style="margin-bottom: 2px;">
          <span style="flex: 1; padding-right: 6px;">
            <?= $item['jumlah'] ?>x <?= htmlspecialchars($item['nama_menu']) ?>
          </span>
          <span class="text-end fw-bold" style="white-space: nowrap;">
            <?= formatRupiah($item['subtotal']) ?>
          </span>
        </div>
        <div style="font-size: 11px; color: #444; margin-left: 18px; margin-bottom: 4px;">
          @ <?= formatRupiah($item['harga_satuan']) ?>
          <?php if (!empty($item['catatan'])): ?>
            <span style="font-style: italic;">(Catatan: <?= htmlspecialchars($item['catatan']) ?>)</span>
          <?php endif; ?>
        </div>
      <?php endforeach; ?>
    </div>

    <div class="receipt-divider"></div>

    <!-- Ringkasan Total Tagihan -->
    <div class="receipt-row">
      <span>Subtotal Menu</span>
      <span><?= formatRupiah($subtotal) ?></span>
    </div>
    <div class="receipt-row">
      <span>PPN Resto (10%)</span>
      <span><?= formatRupiah($ppn) ?></span>
    </div>
    
    <div class="receipt-divider-double"></div>

    <div class="receipt-row" style="font-size: 15px; font-weight: 800;">
      <span>TOTAL BAYAR</span>
      <span><?= formatRupiah($pesanan['total_bayar']) ?></span>
    </div>

    <div class="receipt-divider"></div>

    <div class="receipt-row">
      <span>Metode Bayar</span>
      <strong><?= ($pesanan['metode_bayar'] === 'ewallet') ? 'QRIS / E-WALLET' : 'TUNAI / CASH' ?></strong>
    </div>

    <?php if ($isLunas && ($pesanan['uang_diterima'] ?? 0) > 0): ?>
      <div class="receipt-row">
        <span>Uang Diterima</span>
        <span><?= formatRupiah($pesanan['uang_diterima']) ?></span>
      </div>
      <div class="receipt-row">
        <span>Kembalian</span>
        <strong><?= formatRupiah($pesanan['uang_kembali']) ?></strong>
      </div>
    <?php endif; ?>

    <div class="receipt-divider"></div>

    <!-- QR Code Struk untuk Verifikasi -->
    <div class="text-center my-3">
      <div class="d-inline-block p-1 bg-white border border-dark rounded">
        <div id="receiptQr"></div>
      </div>
      <div class="mt-1" style="font-size: 10px; letter-spacing: 0.5px;">#<?= htmlspecialchars($pesanan['kode_pesanan']) ?></div>
    </div>

    <!-- Footer Struk -->
    <div class="text-center mt-2" style="font-size: 11px;">
      <p class="mb-1 fw-bold">~ TERIMA KASIH ATAS KUNJUNGAN ANDA ~</p>
      <p class="mb-1">Selamat Menikmati Hidangan Kami</p>
      <p class="mb-0 text-muted" style="font-size: 9px;">Dapur Tyas &bull; Powered by Techno Garage</p>
    </div>

  </div>

  <script>
    window.addEventListener('DOMContentLoaded', () => {
      const orderCode = <?= json_encode($pesanan['kode_pesanan']) ?>;
      
      // Render QR Code struk
      const qrEl = document.getElementById('receiptQr');
      if (qrEl) {
        new QRCode(qrEl, {
          text: "DAPURTYAS:" + orderCode,
          width: 88,
          height: 88,
          colorDark: "#000000",
          colorLight: "#ffffff",
          correctLevel: QRCode.CorrectLevel.M
        });
      }

      <?php if ($autoprint): ?>
        setTimeout(() => {
          window.print();
        }, 500);
      <?php endif; ?>
    });
  </script>
</body>
</html>
