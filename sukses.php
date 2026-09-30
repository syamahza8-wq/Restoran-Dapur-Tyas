<?php
/**
 * Halaman Sukses & Lacak Pesanan Pelanggan
 * Dapur Tyas by Techno Garage
 */

require_once __DIR__ . '/config.php';

$kode = $_GET['kode'] ?? '';
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

// Buat teks ringkasan untuk Barcode Bukti Pesanan
$summaryList = [];
foreach ($detailItems as $it) {
    $summaryList[] = "{$it['jumlah']}x {$it['nama_menu']}";
}
$tipeLabel = ($pesanan['tipe_pesanan'] ?? '') === 'take_away' ? 'BAWA PULANG (TAKE AWAY)' : 'MAKAN DI TEMPAT (DINE IN)';

$orderDataText = "DAPUR TYAS | " . ($pesanan['kode_pesanan'] ?? '') . "\n"
               . "MEJA: " . ($pesanan['nomor_meja'] ?? '') . "\n"
               . "TIPE: " . $tipeLabel . "\n"
               . "PEMESAN: " . ($pesanan['nama_pelanggan'] ?? '') . "\n"
               . "TOTAL: " . formatRupiah($pesanan['total_bayar'] ?? 0) . "\n"
               . "METODE: " . strtoupper($pesanan['metode_bayar'] ?? 'KASIR') . "\n"
               . "PESANAN:\n" . implode("\n", $summaryList);

// Deteksi status lunas & metode pembayaran
$isLunas = ($pesanan && ($pesanan['status_bayar'] ?? '') === 'lunas');
$isCash = ($pesanan && ($pesanan['metode_bayar'] ?? '') !== 'ewallet');
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
  <title>Status Pesanan #<?= htmlspecialchars($kode) ?> - Dapur Tyas</title>
  
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800;900&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <link rel="stylesheet" href="assets/css/style.css">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>

  <style>
    .barcode-box {
      border: 2px dashed var(--primary);
      border-radius: var(--radius-md);
      background: #fafbfa;
      padding: 16px;
      text-align: center;
    }
    .payment-instruction-box {
      background: #FDF9F0;
      border: 1px solid #F1D4A7;
      border-radius: var(--radius-md);
      padding: 14px;
    }
    .lock-banner {
      background: #FEF2F2;
      border: 1.5px solid #FCA5A5;
      border-radius: 16px;
      padding: 14px;
    }
    .unlocked-banner {
      background: #ECFDF5;
      border: 1.5px solid #A7F3D0;
      border-radius: 16px;
      padding: 14px;
    }
  </style>
</head>
<body>

  <nav class="navbar navbar-custom px-3 py-2 sticky-top">
    <div class="container-fluid d-flex align-items-center justify-content-between flex-nowrap">
      <div class="d-flex align-items-center gap-2">
        <div class="position-relative">
          <img src="tyas.png" alt="Logo" class="rounded-circle shadow-sm" style="width: 40px; height: 40px; object-fit: cover; border: 2px solid var(--primary); background: #fff;">
          <img src="techno garage.jpg" alt="Partner" class="rounded-circle position-absolute bottom-0 end-0 border border-white" style="width: 16px; height: 16px; object-fit: cover; transform: translate(20%, 20%);">
        </div>
        <div>
          <span class="fw-bold text-main">DAPUR TYAS</span>
          <div class="text-muted" style="font-size: 0.68rem;">Techno Garage</div>
        </div>
      </div>

      <!-- Tombol Navbar: Hanya aktif jika status sudah LUNAS -->
      <?php if ($isLunas): ?>
        <a href="menu.php?meja=<?= urlencode($pesanan['nomor_meja'] ?? '01') ?>" class="btn btn-sm btn-outline-custom">
          <i class="bi bi-plus-circle me-1"></i> Tambah Menu
        </a>
      <?php else: ?>
        <span class="badge bg-danger-subtle text-danger border border-danger-subtle py-1 px-2 small">
          <i class="bi bi-lock-fill me-1"></i> Menu Terkunci
        </span>
      <?php endif; ?>
    </div>
  </nav>

  <div class="container py-3" style="max-width: 580px;">
    <?php if (!$pesanan): ?>
      <div class="card-custom bg-white p-4 text-center">
        <i class="bi bi-exclamation-circle text-warning fs-1"></i>
        <h5 class="fw-bold mt-2">Pesanan Tidak Ditemukan</h5>
        <a href="pilih-meja.php" class="btn btn-primary-custom mt-3">Pilih Nomor Meja</a>
      </div>
    <?php else: ?>
      <div class="card-custom bg-white p-3 p-sm-4 shadow-sm">
        
        <!-- Header Status -->
        <div class="text-center mb-3">
          <?php if ($isLunas): ?>
            <div class="d-inline-flex p-3 rounded-circle bg-success text-white mb-2 shadow-sm animate__animated animate__zoomIn" style="width: 76px; height: 76px; align-items: center; justify-content: center;">
              <i class="bi bi-check-lg" style="font-size: 2.8rem; font-weight: bold;"></i>
            </div>
            <h4 class="fw-bold text-success mb-1">Pembayaran Berhasil!</h4>
            <div class="small text-muted mb-2">Terima kasih, pembayaran Anda telah diverifikasi oleh kasir. Silakan nikmati hidangan Anda.</div>
          <?php elseif ($pesanan['status_pesanan'] === 'dimasak_dapur' || $pesanan['status_pesanan'] === 'siap_saji'): ?>
            <div class="d-inline-flex p-3 rounded-circle bg-primary-subtle text-primary mb-2 shadow-xs" style="width: 72px; height: 72px; align-items: center; justify-content: center;">
              <i class="bi bi-fire fs-1 text-primary"></i>
            </div>
            <h4 class="fw-bold text-primary mb-1">Pesanan Sedang Disiapkan!</h4>
            <div class="small text-muted mb-2">
              <?= $isCash ? 'Koki sedang menyiapkan hidangan. Silakan bayar tagihan tunai di Kasir agar menu pesan terbuka kembali.' : 'Koki sedang menyiapkan hidangan Anda.' ?>
            </div>
          <?php else: ?>
            <div class="d-inline-flex p-3 rounded-circle bg-warning-subtle text-warning mb-2 shadow-xs" style="width: 68px; height: 68px; align-items: center; justify-content: center;">
              <i class="bi <?= $isCash ? 'bi-cash-coin' : 'bi-hourglass-split' ?> fs-2"></i>
            </div>
            <h4 class="fw-bold text-main mb-1">Menunggu Pembayaran <?= $isCash ? 'Tunai (Cash)' : 'E-Wallet' ?></h4>
            <div class="small text-danger fw-bold mb-2">
              <i class="bi bi-lock-fill"></i> Menu pesan terkunci sampai pembayaran diselesaikan
            </div>
          <?php endif; ?>
          <div class="badge bg-dark fs-6 px-3 py-1">MEJA <?= htmlspecialchars($pesanan['nomor_meja']) ?></div>
        </div>

        <!-- Info Singkat Pesanan -->
        <div class="border-top border-bottom py-2 my-2 small text-muted">
          <div class="d-flex justify-content-between">
            <span>Kode Tiket:</span>
            <strong class="text-main">#<?= htmlspecialchars($pesanan['kode_pesanan']) ?></strong>
          </div>
          <div class="d-flex justify-content-between mt-1">
            <span>Tipe Pesanan:</span>
            <span class="badge <?= ($pesanan['tipe_pesanan'] ?? '') === 'take_away' ? 'bg-info text-dark' : 'bg-success-subtle text-success border border-success' ?>">
              <i class="bi <?= ($pesanan['tipe_pesanan'] ?? '') === 'take_away' ? 'bi-bag-check-fill' : 'bi-shop' ?> me-1"></i>
              <?= ($pesanan['tipe_pesanan'] ?? '') === 'take_away' ? 'Bawa Pulang (Take Away)' : 'Makan di Tempat (Dine In)' ?>
            </span>
          </div>
          <div class="d-flex justify-content-between mt-1">
            <span>Metode Bayar:</span>
            <span class="badge <?= ($pesanan['metode_bayar'] ?? '') === 'ewallet' ? 'bg-success' : 'bg-warning text-dark' ?>">
              <i class="bi <?= ($pesanan['metode_bayar'] ?? '') === 'ewallet' ? 'bi-qr-code' : 'bi-cash-stack' ?> me-1"></i>
              <?= ($pesanan['metode_bayar'] ?? '') === 'ewallet' ? 'E-Wallet / QRIS' : 'Tunai / Kasir' ?>
            </span>
          </div>
          <div class="d-flex justify-content-between mt-1">
            <span>Status Pesanan:</span>
            <?php if ($pesanan['status_pesanan'] === 'menunggu_kasir'): ?>
              <span class="badge bg-warning text-dark">Menunggu Konfirmasi Kasir</span>
            <?php elseif ($pesanan['status_pesanan'] === 'dimasak_dapur'): ?>
              <span class="badge bg-primary"><i class="bi bi-fire me-1"></i> Sedang Dimasak Dapur</span>
            <?php elseif ($pesanan['status_pesanan'] === 'siap_saji'): ?>
              <span class="badge bg-info text-dark"><i class="bi bi-bell-fill me-1"></i> Siap Disajikan</span>
            <?php elseif ($pesanan['status_pesanan'] === 'selesai'): ?>
              <span class="badge bg-success"><i class="bi bi-check2-all me-1"></i> Selesai</span>
            <?php else: ?>
              <span class="badge bg-secondary"><?= htmlspecialchars($pesanan['status_pesanan']) ?></span>
            <?php endif; ?>
          </div>
          <div class="d-flex justify-content-between mt-1">
            <span>Status Pembayaran:</span>
            <strong class="<?= $isLunas ? 'text-success' : 'text-danger' ?>">
              <?php if ($isLunas): ?>
                <i class="bi bi-patch-check-fill text-success"></i> LUNAS (TERVERIFIKASI)
              <?php else: ?>
                <i class="bi bi-x-circle-fill text-danger"></i> BELUM LUNAS
              <?php endif; ?>
            </strong>
          </div>
        </div>

        <!-- Rincian Pembayaran Tunai (Jika sudah lunas) -->
        <?php if ($isLunas && ($pesanan['uang_diterima'] ?? 0) > 0): ?>
          <div class="p-3 bg-light rounded-3 border my-3">
            <div class="small fw-bold text-main mb-2"><i class="bi bi-receipt-cutoff text-success me-1"></i> Bukti Transaksi Kasir:</div>
            <div class="d-flex justify-content-between small text-muted mb-1">
              <span>Total Tagihan:</span>
              <span class="fw-bold text-main"><?= formatRupiah($pesanan['total_bayar']) ?></span>
            </div>
            <div class="d-flex justify-content-between small text-muted mb-1">
              <span>Uang Diterima:</span>
              <span class="fw-bold text-main"><?= formatRupiah($pesanan['uang_diterima']) ?></span>
            </div>
            <div class="d-flex justify-content-between small pt-1 border-top">
              <span class="fw-bold text-success">Uang Kembalian:</span>
              <span class="fw-bold text-success fs-6"><?= formatRupiah($pesanan['uang_kembali']) ?></span>
            </div>
          </div>
        <?php endif; ?>

        <!-- INSTRUKSI PEMBAYARAN KASIR ATAU E-WALLET (Hanya jika belum lunas) -->
        <?php if (!$isLunas): ?>
          <?php if (($pesanan['metode_bayar'] ?? '') === 'ewallet'): ?>
            <!-- Pembayaran E-Wallet: Tampilkan QRIS Code Pembayaran -->
            <div class="payment-instruction-box my-3 text-center">
              <h6 class="fw-bold text-main mb-1"><i class="bi bi-qr-code text-success me-1"></i> Kode Pembayaran E-Wallet / QRIS</h6>
              <p class="small text-muted mb-2">Scan kode QR di bawah menggunakan GoPay, OVO, DANA, BCA, atau Mobile Banking:</p>
              
              <div class="d-flex justify-content-center p-2 bg-white rounded-3 border d-inline-block mx-auto mb-2 shadow-xs">
                <div id="ewalletQr"></div>
              </div>

              <div class="fw-bold fs-5 text-success mb-1"><?= formatRupiah($pesanan['total_bayar']) ?></div>
              <div class="small text-muted mb-2">Kode Pembayaran: <strong><?= htmlspecialchars($pesanan['kode_pesanan']) ?></strong></div>
              <div class="alert alert-warning py-2 px-3 small mb-0 border-0 rounded-3 text-start">
                <i class="bi bi-info-circle-fill text-warning me-1"></i> Jika belum masuk, Anda juga dapat langsung membayar uang tunai (cash) di Meja Kasir.
              </div>
            </div>
          <?php else: ?>
            <!-- Pembayaran Langsung di Kasir (Cash): Tampilkan Barcode Tiket Pesanan -->
            <div class="barcode-box my-3">
              <h6 class="fw-bold text-main mb-1"><i class="bi bi-upc-scan text-primary me-1"></i> Barcode Tiket Pembayaran Kasir</h6>
              <p class="small text-muted mb-2">Tunjukkan tiket ini dan bayar sejumlah tagihan ke Meja Kasir:</p>
              
              <div class="d-flex justify-content-center p-2 bg-white rounded-3 border d-inline-block mx-auto mb-2 shadow-xs">
                <div id="orderBarcode"></div>
              </div>
              
              <div class="small fw-bold text-main mb-1">#<?= htmlspecialchars($pesanan['kode_pesanan']) ?></div>
              <div class="alert alert-warning py-2 px-3 small mt-2 mb-0 border-0 rounded-3 text-start">
                <i class="bi bi-shield-lock-fill text-danger me-1"></i> <strong>Pemberitahuan:</strong> Menu pesan tambahan dan akses keluar akan terbuka otomatis setelah Kasir menerima uang Anda dan memverifikasi pembayaran di sistem POS.
              </div>
            </div>
          <?php endif; ?>
        <?php endif; ?>

        <!-- Detail Item Pesanan -->
        <h6 class="fw-bold small text-main mb-2 mt-3"><i class="bi bi-card-checklist text-primary me-1"></i> Rincian Pesanan:</h6>
        <div class="list-group list-group-flush mb-3">
          <?php foreach ($detailItems as $item): ?>
            <div class="list-group-item d-flex justify-content-between align-items-center px-0 py-2 small">
              <div>
                <span class="fw-bold"><?= $item['jumlah'] ?>x</span> <?= htmlspecialchars($item['nama_menu']) ?>
                <div class="text-muted" style="font-size: 0.72rem;">@ <?= formatRupiah($item['harga_satuan']) ?></div>
              </div>
              <span class="fw-bold"><?= formatRupiah($item['subtotal']) ?></span>
            </div>
          <?php endforeach; ?>
        </div>

        <div class="d-flex justify-content-between fw-bold fs-5 border-top pt-2 text-main">
          <span>Total Tagihan:</span>
          <span class="text-success"><?= formatRupiah($pesanan['total_bayar']) ?></span>
        </div>

        <!-- Pilihan / Pertanyaan Struk Pelanggan -->
        <div class="card p-3 my-3 border rounded-3 bg-light text-start" id="strukPreferenceBox">
          <div class="d-flex align-items-center justify-content-between mb-2">
            <span class="small fw-bold text-main">
              <i class="bi bi-receipt-cutoff text-warning me-1"></i> Struk Pesanan Fisik
            </span>
            <span id="badgeStrukStatus" class="badge <?= !empty($pesanan['minta_struk']) ? 'bg-success' : 'bg-secondary' ?>">
              <?= !empty($pesanan['minta_struk']) ? 'Diminta ke Kasir' : 'E-Struk di HP' ?>
            </span>
          </div>

          <p class="small text-muted mb-2" id="textStrukDesc" style="font-size: 0.78rem;">
            <?= !empty($pesanan['minta_struk']) 
                ? 'Anda memilih <strong>Ya, minta struk fisik</strong>. Kasir akan mencetak struk untuk Anda.' 
                : 'Anda memilih <strong>Tidak perlu struk fisik (Paperless)</strong>. Rincian pesanan tersimpan di HP Anda.' ?>
          </p>

          <div class="d-flex gap-2">
            <button type="button" class="btn btn-sm <?= !empty($pesanan['minta_struk']) ? 'btn-outline-secondary' : 'btn-outline-success' ?> flex-grow-1" id="btnToggleStruk" onclick="toggleMintaStruk()">
              <i class="bi <?= !empty($pesanan['minta_struk']) ? 'bi-x-circle' : 'bi-printer' ?> me-1"></i>
              <span id="labelToggleStruk"><?= !empty($pesanan['minta_struk']) ? 'Batalkan Permintaan Struk' : 'Minta Struk Fisik ke Kasir' ?></span>
            </button>
          </div>
        </div>

        <!-- ================= TOMBOL AKSI SETELAH BAYAR / SEBELUM BAYAR ================= -->
        <div class="mt-4">
          <?php if ($isLunas): ?>
            <!-- JIKA SUDAH BAYAR LUNAS: BISA KEMBALI KE MENU DAN KELUAR -->
            <div class="unlocked-banner mb-3 text-center">
              <div class="fw-bold text-success small mb-1"><i class="bi bi-unlock-fill me-1"></i> Pembayaran Selesai & Terverifikasi</div>
              <div class="text-muted small">Anda sekarang dapat memesan menu tambahan atau menyelesaikan kunjungan.</div>
            </div>

            <div class="d-flex flex-column gap-2">
              <a href="menu.php?meja=<?= urlencode($pesanan['nomor_meja']) ?>" class="btn btn-primary-custom w-100 py-2 fw-bold shadow-xs">
                <i class="bi bi-plus-circle me-1"></i> Kembali ke Menu / Pesan Tambahan
              </a>
              <button type="button" class="btn btn-outline-secondary w-100 py-2 fw-bold" onclick="selesaikanDanKeluar()">
                <i class="bi bi-box-arrow-right me-1"></i> Selesai & Keluar
              </button>
            </div>
          <?php else: ?>
            <!-- JIKA BELUM BAYAR: TERKUNCI & TIDAK BISA KEMBALI KE MENU / KELUAR -->
            <div class="lock-banner mb-3 text-center">
              <div class="fw-bold text-danger small mb-1"><i class="bi bi-lock-fill me-1"></i> Navigasi & Menu Terkunci</div>
              <div class="text-muted small">
                Harap bayar sebesar <strong><?= formatRupiah($pesanan['total_bayar']) ?></strong> <?= $isCash ? 'di Meja Kasir' : 'melalui QRIS' ?> terlebih dahulu untuk membuka menu pesan dan keluar.
              </div>
            </div>

            <div class="d-flex flex-column gap-2">
              <button class="btn btn-outline-custom w-100 py-2" onclick="location.reload()">
                <i class="bi bi-arrow-clockwise me-1"></i> Periksa Status Pembayaran Kasir
              </button>
            </div>
          <?php endif; ?>
        </div>

        <div class="mt-3 text-center text-muted" style="font-size: 0.75rem;">
          <i class="bi bi-info-circle-fill text-warning me-1"></i>
          Dapur Tyas by Techno Garage &bull; Smart Local POS
        </div>

      </div>
    <?php endif; ?>
  </div>

  <!-- Modal Peringatan Belum Bayar (Anti-Keluar Paksa) -->
  <div class="modal fade" id="unpaidExitWarningModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content border-0 shadow-lg rounded-4 text-center p-4">
        <div class="d-inline-flex p-3 rounded-circle bg-danger text-white mx-auto mb-3 shadow-sm" style="width: 72px; height: 72px; align-items: center; justify-content: center;">
          <i class="bi bi-shield-lock-fill" style="font-size: 2.4rem;"></i>
        </div>
        <h5 class="fw-bold text-danger mb-2">PESANAN BELUM LUNAS!</h5>
        <p class="small text-muted mb-3">
          Halaman ini tidak dapat ditutup dan Anda belum dapat kembali ke menu pesan sebelum pembayaran diselesaikan. Sinyal peringatan telah dikirimkan ke POS Kasir.
        </p>
        <div class="p-3 bg-light rounded-3 text-start small border mb-3">
          <div class="d-flex justify-content-between mb-1">
            <span>Kode Pesanan:</span>
            <strong>#<?= htmlspecialchars($pesanan['kode_pesanan'] ?? '') ?></strong>
          </div>
          <div class="d-flex justify-content-between mb-1">
            <span>Meja:</span>
            <strong>MEJA <?= htmlspecialchars($pesanan['nomor_meja'] ?? '') ?></strong>
          </div>
          <div class="d-flex justify-content-between pt-1 border-top">
            <span class="fw-bold">Total Tagihan:</span>
            <strong class="text-success"><?= formatRupiah($pesanan['total_bayar'] ?? 0) ?></strong>
          </div>
        </div>
        <button type="button" class="btn btn-primary-custom w-100 fw-bold py-2 shadow-xs" data-bs-dismiss="modal">
          <i class="bi bi-arrow-return-left me-1"></i> Kembali & Selesaikan Pembayaran
        </button>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

  <script>
    window.addEventListener('DOMContentLoaded', () => {
      const orderCode = <?= json_encode($pesanan['kode_pesanan'] ?? '') ?>;
      const orderTotal = <?= (int)($pesanan['total_bayar'] ?? 0) ?>;
      let isOrderLunas = <?= $isLunas ? 'true' : 'false' ?>;
      const tableNumber = <?= json_encode($pesanan['nomor_meja'] ?? '01') ?>;

      // 1. Jika E-Wallet: Buat QRIS Pembayaran
      const ewalletContainer = document.getElementById('ewalletQr');
      if (ewalletContainer) {
        const qrisString = "00020101021226600016ID.CO.DAPURTYAS.WWW01189360000000000000000215" + orderCode + "520458125303360540" + orderTotal + "5802ID5910DAPURTYAS6007BANDUNG6304ABCD";
        new QRCode(ewalletContainer, {
          text: qrisString,
          width: 160,
          height: 160,
          colorDark: "#1A2B22",
          colorLight: "#ffffff",
          correctLevel: QRCode.CorrectLevel.M
        });
      }

      // 2. Jika Bayar Kasir: Buat Barcode / QR Kode Tiket Pesanan untuk Kasir
      const barcodeContainer = document.getElementById('orderBarcode');
      if (barcodeContainer) {
        new QRCode(barcodeContainer, {
          text: orderCode,
          width: 160,
          height: 160,
          colorDark: "#1A2B22",
          colorLight: "#ffffff",
          correctLevel: QRCode.CorrectLevel.M
        });
      }

      // ================= AUDIO & EXIT WARNING (Hanya jika belum lunas) =================
      let audioCtx = null;
      let unpaidModalObj = null;

      const unlockAudio = () => {
        try {
          if (!audioCtx) audioCtx = new (window.AudioContext || window.webkitAudioContext)();
          if (audioCtx.state === 'suspended') audioCtx.resume();
        } catch (e) {}
        window.removeEventListener('touchstart', unlockAudio);
        window.removeEventListener('click', unlockAudio);
      };
      window.addEventListener('touchstart', unlockAudio, { passive: true });
      window.addEventListener('click', unlockAudio, { passive: true });

      function playPhoneAlarm() {
        try {
          if (!audioCtx) audioCtx = new (window.AudioContext || window.webkitAudioContext)();
          if (audioCtx.state === 'suspended') audioCtx.resume();

          for (let i = 0; i < 3; i++) {
            const osc = audioCtx.createOscillator();
            const gain = audioCtx.createGain();
            osc.type = 'sawtooth';
            const t = audioCtx.currentTime + (i * 0.22);
            osc.frequency.setValueAtTime(880, t);
            osc.frequency.linearRampToValueAtTime(1450, t + 0.1);
            osc.frequency.linearRampToValueAtTime(880, t + 0.18);

            gain.gain.setValueAtTime(0.5, t);
            gain.gain.exponentialRampToValueAtTime(0.01, t + 0.2);

            osc.connect(gain);
            gain.connect(audioCtx.destination);
            osc.start(t);
            osc.stop(t + 0.2);
          }
        } catch (e) {}
      }

      function triggerSecurityWarning() {
        if (isOrderLunas) return;
        playPhoneAlarm();
        
        fetch('lapor-peringatan.php', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ kode: orderCode })
        }).catch(err => {});

        const modalEl = document.getElementById('unpaidExitWarningModal');
        if (modalEl) {
          if (!unpaidModalObj) unpaidModalObj = new bootstrap.Modal(modalEl);
          unpaidModalObj.show();
        } else {
          alert("PERINGATAN: Pesanan Anda belum dibayar lunas!\nHarap selesaikan pembayaran tunai di Kasir atau E-Wallet.");
        }
      }

      // Kunci Tombol Back Browser jika belum lunas
      if (!isOrderLunas) {
        history.pushState(null, null, location.href);
        window.addEventListener('popstate', (e) => {
          if (!isOrderLunas) {
            history.pushState(null, null, location.href);
            triggerSecurityWarning();
          }
        });

        window.addEventListener('beforeunload', (e) => {
          if (!isOrderLunas) {
            const data = JSON.stringify({ kode: orderCode });
            if (navigator.sendBeacon) {
              navigator.sendBeacon('lapor-peringatan.php', data);
            }
            const msg = "Pesanan belum lunas! Mohon selesaikan pembayaran.";
            e.preventDefault();
            e.returnValue = msg;
            return msg;
          }
        });

        document.addEventListener('visibilitychange', () => {
          if (document.hidden && !isOrderLunas) {
            fetch('lapor-peringatan.php', {
              method: 'POST',
              headers: { 'Content-Type': 'application/json' },
              body: JSON.stringify({ kode: orderCode })
            }).catch(err => {});
          }
        });
      }

      // ================= REAL-TIME POLLING STATUS PEMBAYARAN KASIR =================
      const pollInterval = setInterval(async () => {
        if (isOrderLunas) {
          clearInterval(pollInterval);
          return;
        }

        try {
          const res = await fetch(`cek-status-pesanan.php?kode=${encodeURIComponent(orderCode)}`);
          const data = await res.json();

          if (data.status === 'success' && data.is_lunas) {
            isOrderLunas = true;
            clearInterval(pollInterval);

            // Bunyikan nada sukses (Ting!)
            try {
              const ctx = new (window.AudioContext || window.webkitAudioContext)();
              const osc = ctx.createOscillator();
              const gain = ctx.createGain();
              osc.type = 'sine';
              osc.frequency.setValueAtTime(523.25, ctx.currentTime);
              osc.frequency.setValueAtTime(659.25, ctx.currentTime + 0.15);
              osc.frequency.setValueAtTime(783.99, ctx.currentTime + 0.3);
              gain.gain.setValueAtTime(0.3, ctx.currentTime);
              gain.gain.exponentialRampToValueAtTime(0.01, ctx.currentTime + 0.8);
              osc.connect(gain);
              gain.connect(ctx.destination);
              osc.start(ctx.currentTime);
              osc.stop(ctx.currentTime + 0.8);
            } catch (e) {}

            // Reload otomatis membuka semua tombol menu dan keluar
            setTimeout(() => {
              location.reload();
            }, 600);
          }
        } catch (e) {}
      }, 2500);
    });

    // ================= UBAH PREFERENSI MINTA STRUK KE KASIR =================
    let currentMintaStruk = <?= !empty($pesanan['minta_struk']) ? 1 : 0 ?>;

    async function toggleMintaStruk() {
      const newStatus = currentMintaStruk ? 0 : 1;
      const btn = document.getElementById('btnToggleStruk');
      if (btn) btn.disabled = true;

      try {
        const res = await fetch('update-struk.php', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({
            kode: <?= json_encode($pesanan['kode_pesanan']) ?>,
            minta_struk: newStatus
          })
        });
        const data = await res.json();
        if (data.status === 'success') {
          currentMintaStruk = newStatus;
          const badge = document.getElementById('badgeStrukStatus');
          const desc = document.getElementById('textStrukDesc');
          const label = document.getElementById('labelToggleStruk');
          const icon = btn.querySelector('i');

          if (currentMintaStruk) {
            badge.className = 'badge bg-success';
            badge.innerText = 'Diminta ke Kasir';
            desc.innerHTML = 'Anda memilih <strong>Ya, minta struk fisik</strong>. Kasir akan mencetak struk untuk Anda.';
            btn.className = 'btn btn-sm btn-outline-secondary flex-grow-1';
            icon.className = 'bi bi-x-circle me-1';
            label.innerText = 'Batalkan Permintaan Struk';
          } else {
            badge.className = 'badge bg-secondary';
            badge.innerText = 'E-Struk di HP';
            desc.innerHTML = 'Anda memilih <strong>Tidak perlu struk fisik (Paperless)</strong>. Rincian pesanan tersimpan di HP Anda.';
            btn.className = 'btn btn-sm btn-outline-success flex-grow-1';
            icon.className = 'bi bi-printer me-1';
            label.innerText = 'Minta Struk Fisik ke Kasir';
          }
        }
      } catch (e) {
        alert('Gagal memperbarui permintaan struk.');
      } finally {
        if (btn) btn.disabled = false;
      }
    }

    // Fungsi Selesai & Keluar Bersih
    function selesaikanDanKeluar() {
      const tableNumber = <?= json_encode($pesanan['nomor_meja'] ?? '01') ?>;
      try {
        sessionStorage.removeItem('dapur_tyas_cart_' + tableNumber);
        localStorage.removeItem('dapur_tyas_last_order_' + tableNumber);
        sessionStorage.removeItem('dapur_tyas_last_order');
      } catch(e) {}

      alert("Terima kasih atas kunjungan Anda di Dapur Tyas! Semoga harimu menyenangkan. 😊");
      window.location.href = "pilih-meja.php";
    }
  </script>
</body>
</html>
