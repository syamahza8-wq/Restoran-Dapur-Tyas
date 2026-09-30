<?php
/**
 * Cetak QR Code Restoran Dapur Tyas
 * Dapur Tyas by Techno Garage
 * Fitur: 1 Barcode Utama Resto (Scan -> Pilih Meja -> Pilih Menu) & Opsi QR Per Meja
 */

require_once __DIR__ . '/config.php';
$localIP = getLocalServerIP();

// Ambil daftar meja langsung dari tabel_meja di database
try {
    $stmt = $pdo->query("SELECT * FROM tabel_meja ORDER BY LPAD(nomor_meja, 10, '0') ASC, nomor_meja ASC");
    $daftarMeja = $stmt->fetchAll();
} catch (PDOException $e) {
    $daftarMeja = [];
}

// Fallback jika belum ada meja di database
if (empty($daftarMeja)) {
    for ($i = 1; $i <= 12; $i++) {
        $daftarMeja[] = [
            'id' => $i,
            'nomor_meja' => str_pad($i, 2, '0', STR_PAD_LEFT),
            'nama_area' => ($i <= 6) ? 'Indoor AC' : 'Outdoor Garden',
            'kapasitas' => 4
        ];
    }
}

// URL Master Barcode (1 Barcode Utama untuk Semua Pelanggan)
$masterQrUrl = "http://{$localIP}/Dapur-Tyas/pilih-meja.php";
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Cetak Barcode QR Code - Dapur Tyas</title>
  
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800;900&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <link rel="stylesheet" href="assets/css/style.css">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>

  <style>
    :root {
      --primary-color: #0F5132;
      --accent-flame: #F97316;
      --accent-orange: #EA580C;
    }

    body {
      background-color: #F8FAFC;
      font-family: 'Plus Jakarta Sans', sans-serif;
    }

    /* Master Stand Design (1 Barcode) */
    .master-qr-stand {
      background: #FFFFFF;
      border: 3px solid #0F5132;
      border-radius: 28px;
      padding: 36px 28px;
      text-align: center;
      box-shadow: 0 16px 40px rgba(15, 23, 42, 0.1);
      position: relative;
      max-width: 480px;
      margin: 0 auto;
    }

    .master-qr-stand::before {
      content: '';
      position: absolute;
      top: -6px;
      left: 20%;
      right: 20%;
      height: 6px;
      background: linear-gradient(90deg, var(--accent-flame), var(--accent-orange));
      border-radius: 999px;
    }

    .qr-box-inner {
      background: #FFFFFF;
      padding: 16px;
      border-radius: 20px;
      display: inline-block;
      box-shadow: 0 4px 20px rgba(0,0,0,0.06);
      border: 2px solid #E2E8F0;
    }

    .step-card {
      background: #F8FAFC;
      border: 1px solid #E2E8F0;
      border-radius: 14px;
      padding: 10px 12px;
      display: flex;
      align-items: center;
      gap: 10px;
      text-align: left;
    }

    .step-badge {
      width: 28px;
      height: 28px;
      border-radius: 50%;
      background: var(--primary-color);
      color: #FFFFFF;
      font-weight: 800;
      font-size: 0.8rem;
      display: flex;
      align-items: center;
      justify-content: center;
      flex-shrink: 0;
    }

    /* Individual Table Stand */
    .qr-stand {
      border: 2px dashed #0F5132;
      border-radius: 18px;
      background: #FFFFFF;
      padding: 20px;
      text-align: center;
      box-shadow: 0 4px 12px rgba(15, 23, 42, 0.05);
    }

    .nav-pills-custom .nav-link {
      color: #475569;
      font-weight: 700;
      border-radius: 999px;
      padding: 8px 20px;
      border: 1px solid transparent;
      transition: all 0.2s ease;
    }

    .nav-pills-custom .nav-link.active {
      background-color: #0F5132;
      color: #FFFFFF;
      box-shadow: 0 4px 12px rgba(15, 81, 50, 0.25);
    }

    @media print {
      .no-print { display: none !important; }
      body { background: #FFFFFF !important; padding: 0 !important; }
      .container { max-width: 100% !important; padding: 0 !important; }
      .master-qr-stand { border: 3px solid #000 !important; box-shadow: none !important; page-break-inside: avoid; margin: 20px auto !important; max-width: 90% !important; }
      .qr-stand { border: 2px solid #000 !important; box-shadow: none !important; page-break-inside: avoid; margin-bottom: 30px; }
      .tab-pane:not(.active) { display: none !important; }
    }
  </style>
</head>
<body>

  <!-- Top Navbar -->
  <nav class="navbar navbar-custom px-3 py-2 no-print">
    <div class="container-fluid d-flex align-items-center justify-content-between">
      <div class="d-flex align-items-center gap-2">
        <div class="position-relative">
          <img src="tyas.png" alt="Logo" class="rounded-circle" style="width: 44px; height: 44px; object-fit: cover; border: 2px solid var(--primary-color); background: #fff;">
          <img src="techno garage.jpg" alt="Partner" class="rounded-circle position-absolute bottom-0 end-0 border border-white" style="width: 18px; height: 18px; object-fit: cover; transform: translate(20%, 20%);">
        </div>
        <span class="fw-bold fs-5 text-main">DAPUR TYAS</span>
        <span class="brand-badge ms-1">Techno Garage</span>
      </div>
      <div class="d-flex gap-2">
        <a href="pos.php" class="btn btn-sm btn-outline-custom"><i class="bi bi-laptop"></i> Panel Staf POS</a>
        <button onclick="window.print()" class="btn btn-sm btn-primary-custom"><i class="bi bi-printer-fill"></i> Cetak Barcode</button>
      </div>
    </div>
  </nav>

  <div class="container py-4" style="max-width: 960px;">
    
    <!-- Header Controls (No Print) -->
    <div class="card-custom p-4 mb-4 no-print bg-white">
      <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
        <div>
          <h4 class="fw-bold mb-1"><i class="bi bi-qr-code-scan text-success me-2"></i>Cetak QR Barcode Restoran</h4>
          <p class="text-muted small mb-0">
            Cukup 1 barcode untuk seluruh restoran! Pelanggan scan, pilih meja, lalu memilih menu dengan praktis & rapi.
          </p>
        </div>
        <div class="d-flex align-items-center gap-2">
          <button onclick="window.print()" class="btn btn-primary-custom text-nowrap"><i class="bi bi-printer-fill me-1"></i> Cetak Sekarang</button>
        </div>
      </div>

      <!-- Nav Tabs Pilihan Mode Cetak -->
      <ul class="nav nav-pills nav-pills-custom mt-3 gap-2 border-top pt-3" id="barcodeTab" role="tablist">
        <li class="nav-item" role="presentation">
          <button class="nav-link active" id="single-tab" data-bs-toggle="pill" data-bs-target="#single-pane" type="button" role="tab">
            <i class="bi bi-star-fill text-warning me-1"></i> 1 Barcode Utama Resto (Rekomendasi)
          </button>
        </li>
        <li class="nav-item" role="presentation">
          <button class="nav-link" id="multi-tab" data-bs-toggle="pill" data-bs-target="#multi-pane" type="button" role="tab">
            <i class="bi bi-grid-fill me-1"></i> Barcode Per Meja (<?= count($daftarMeja) ?> Meja)
          </button>
        </li>
      </ul>

      <div class="mt-3 p-3 bg-light rounded-3 border d-flex align-items-center justify-content-between flex-wrap gap-2">
        <div class="small">
          <i class="bi bi-wifi text-success me-1"></i> <strong>Wi-Fi Restoran:</strong> Terhubung di IP <code><?= htmlspecialchars($localIP) ?></code>
          <span class="mx-2 text-muted">|</span>
          <i class="bi bi-link-45deg text-primary me-1"></i> Target: <span class="text-success fw-bold">pilih-meja.php</span>
        </div>
        <a href="pilih-meja.php" target="_blank" class="badge bg-success-subtle text-success border border-success-subtle px-3 py-2 text-decoration-none">
          <i class="bi bi-box-arrow-up-right me-1"></i> Buka Tampilan Pelanggan
        </a>
      </div>
    </div>

    <!-- Tab Contents -->
    <div class="tab-content" id="barcodeTabContent">
      
      <!-- TAB 1: MASTER 1 BARCODE RESTO (UTAMA) -->
      <div class="tab-pane fade show active" id="single-pane" role="tabpanel">
        
        <div class="master-qr-stand">
          <!-- Standee Header -->
          <div class="d-flex align-items-center justify-content-center gap-2 mb-2">
            <img src="tyas.png" alt="Logo" class="rounded-circle" style="width: 52px; height: 52px; object-fit: cover; border: 2.5px solid #0F5132; background: #fff;">
            <div class="text-start">
              <h4 class="fw-bold text-dark mb-0 lh-1">DAPUR TYAS</h4>
              <span class="text-muted small fw-bold" style="font-size: 0.72rem; letter-spacing: 0.5px;">TECHNO GARAGE</span>
            </div>
          </div>

          <div class="badge bg-dark text-white px-3 py-1 rounded-pill my-2" style="font-size: 0.75rem; letter-spacing: 0.5px;">
            <i class="bi bi-phone-fill me-1 text-warning"></i> SMART SELF-ORDER
          </div>

          <h5 class="fw-bold text-dark mb-1" style="letter-spacing: -0.3px;">SCAN DI SINI UNTUK PESAN</h5>
          <p class="text-muted small mb-3" style="font-size: 0.78rem;">Buka kamera HP Anda & arahkan ke kode barcode di bawah</p>

          <!-- Master QR Image Container -->
          <div class="qr-box-inner mb-3">
            <div id="masterQrcode"></div>
          </div>

          <!-- 3 Steps Instructions -->
          <div class="d-flex flex-column gap-2 text-start my-3" style="font-size: 0.8rem;">
            <div class="step-card">
              <span class="step-badge">1</span>
              <div>
                <strong>Scan Barcode</strong>
                <div class="text-muted" style="font-size: 0.72rem;">Buka aplikasi kamera atau QR Scanner di HP</div>
              </div>
            </div>
            <div class="step-card">
              <span class="step-badge">2</span>
              <div>
                <strong>Pilih Nomor Meja</strong>
                <div class="text-muted" style="font-size: 0.72rem;">Tentukan meja tempat Anda duduk</div>
              </div>
            </div>
            <div class="step-card">
              <span class="step-badge">3</span>
              <div>
                <strong>Pilih Menu & Pesan</strong>
                <div class="text-muted" style="font-size: 0.72rem;">Pilih hidangan favorit dan nikmati layanan kami</div>
              </div>
            </div>
          </div>

          <!-- Bottom Footer URL -->
          <div class="border-top pt-3 mt-2">
            <div class="text-muted text-break small" style="font-size: 0.72rem;"><?= htmlspecialchars($masterQrUrl) ?></div>
            <div class="no-print mt-2">
              <a href="<?= htmlspecialchars($masterQrUrl) ?>" target="_blank" class="btn btn-sm btn-outline-custom w-100">
                <i class="bi bi-box-arrow-up-right me-1"></i> Buka Halaman Pemilihan Meja
              </a>
            </div>
          </div>
        </div>

      </div>

      <!-- TAB 2: BARCODE PER MEJA (OPSIONAL) -->
      <div class="tab-pane fade" id="multi-pane" role="tabpanel">
        <div class="row g-4" id="qrContainerMeja"></div>
      </div>

    </div>

  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    const masterUrl = <?= json_encode($masterQrUrl) ?>;
    const baseServerUrl = "http://<?= htmlspecialchars($localIP) ?>/Dapur-Tyas/menu.php";
    const tables = <?= json_encode($daftarMeja) ?>;

    // 1. Generate Master Resto QR Code (1 Barcode)
    function generateMasterQR() {
      const masterContainer = document.getElementById('masterQrcode');
      if (masterContainer) {
        masterContainer.innerHTML = '';
        new QRCode(masterContainer, {
          text: masterUrl,
          width: 190,
          height: 190,
          colorDark: "#0F5132",
          colorLight: "#ffffff",
          correctLevel: QRCode.CorrectLevel.H
        });
      }
    }

    // 2. Generate Individual Table QRs (Opsional)
    function generateTableQRCodes() {
      const container = document.getElementById('qrContainerMeja');
      if (!container) return;
      container.innerHTML = '';

      tables.forEach((tbl, index) => {
        const tableCode = tbl.nomor_meja;
        const area = tbl.nama_area || 'Area Utama';
        const tableUrl = `${baseServerUrl}?meja=${encodeURIComponent(tableCode)}`;
        const col = document.createElement('div');
        col.className = 'col-12 col-sm-6 col-md-4 col-lg-3';

        col.innerHTML = `
          <div class="qr-stand h-100 d-flex flex-column justify-content-between">
            <div>
              <div class="d-flex align-items-center justify-content-center gap-2 mb-2">
                <img src="tyas.png" alt="Logo" style="width: 32px; height: 32px; border-radius: 50%; object-fit: cover; border: 1.5px solid var(--primary-color); background: #fff;">
                <span class="fw-bold small">DAPUR TYAS</span>
              </div>
              <div class="badge bg-dark fs-6 px-3 py-1 mb-1">MEJA ${tableCode}</div>
              <div class="text-muted small mb-2" style="font-size: 0.72rem;">${area}</div>
              <div class="text-muted" style="font-size: 0.75rem;">Scan untuk Pesan Makanan & Minuman</div>
            </div>

            <div class="d-flex justify-content-center my-3 p-2 bg-white rounded-3 shadow-xs">
              <div id="qrcode-table-${index}"></div>
            </div>

            <div>
              <div class="text-success fw-bold small mb-1"><i class="bi bi-camera-fill"></i> Buka Kamera & Scan</div>
              <div class="text-muted text-break small mb-2" style="font-size: 0.68rem;">${tableUrl}</div>
              <div class="no-print">
                <a href="${tableUrl}" target="_blank" class="btn btn-sm btn-outline-custom w-100 small" style="font-size: 0.78rem;">
                  <i class="bi bi-box-arrow-up-right"></i> Buka Meja ${tableCode}
                </a>
              </div>
            </div>
          </div>
        `;

        container.appendChild(col);

        setTimeout(() => {
          new QRCode(document.getElementById(`qrcode-table-${index}`), {
            text: tableUrl,
            width: 140,
            height: 140,
            colorDark: "#1A2B22",
            colorLight: "#ffffff",
            correctLevel: QRCode.CorrectLevel.M
          });
        }, 40);
      });
    }

    window.addEventListener('DOMContentLoaded', () => {
      generateMasterQR();
      generateTableQRCodes();
    });
  </script>
</body>
</html>
