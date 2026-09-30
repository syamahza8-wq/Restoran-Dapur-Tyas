<?php
/**
 * Halaman Pemilihan Meja Pelanggan
 * Dapur Tyas by Techno Garage
 * Alur: Scan 1 Barcode Utama Resto -> Pilih Meja -> Pilih Menu
 */

require_once __DIR__ . '/config.php';

// Ambil daftar meja dari database
try {
    $stmt = $pdo->query("SELECT * FROM tabel_meja ORDER BY LPAD(nomor_meja, 10, '0') ASC, nomor_meja ASC");
    $daftarMeja = $stmt->fetchAll();
} catch (PDOException $e) {
    $daftarMeja = [];
}

// Fallback jika belum ada meja di database
if (empty($daftarMeja)) {
    for ($i = 1; $i <= 12; $i++) {
        $nomor = str_pad($i, 2, '0', STR_PAD_LEFT);
        $daftarMeja[] = [
            'id' => $i,
            'nomor_meja' => $nomor,
            'nama_area' => ($i <= 6) ? 'Indoor AC' : 'Outdoor Garden',
            'kapasitas' => 4,
            'status' => 'tersedia'
        ];
    }
}

// Cek meja yang sedang aktif dipesan (belum selesai)
$mejaTerisi = [];
try {
    $stmtAktif = $pdo->query("SELECT DISTINCT nomor_meja FROM tabel_pesanan WHERE status_pesanan NOT IN ('selesai', 'dibatalkan')");
    $mejaTerisi = $stmtAktif->fetchAll(PDO::FETCH_COLUMN);
} catch (PDOException $e) {}

// Dapatkan daftar area unik untuk filter tab
$areaList = array_values(array_unique(array_column($daftarMeja, 'nama_area')));
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
  <meta name="theme-color" content="#FFFFFF">
  <title>Pilih Nomor Meja - Dapur Tyas</title>
  
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <link rel="stylesheet" href="assets/css/style.css">

  <style>
    :root {
      --primary-color: #0F5132;
      --accent-flame: #F97316;
      --accent-orange: #EA580C;
      --bg-body: #F8FAFC;
    }

    body {
      background-color: var(--bg-body);
      font-family: 'Plus Jakarta Sans', sans-serif;
      color: #0F172A;
      min-height: 100vh;
      display: flex;
      flex-direction: column;
    }

    /* Background ambient glow */
    body::before {
      content: '';
      position: fixed;
      top: -10vw;
      left: -10vw;
      width: 50vw;
      height: 50vw;
      background: radial-gradient(circle, rgba(16, 185, 129, 0.09) 0%, transparent 70%);
      filter: blur(50px);
      z-index: -1;
      pointer-events: none;
    }

    .header-hero {
      background: #FFFFFF;
      border-bottom: 1px solid #E2E8F0;
      padding: 24px 16px 18px 16px;
      text-align: center;
      box-shadow: 0 2px 12px rgba(0, 0, 0, 0.03);
    }

    .table-selection-card {
      background: #FFFFFF;
      border: 1.5px solid #E2E8F0;
      border-radius: 20px;
      padding: 16px;
      transition: all 0.25s cubic-bezier(0.34, 1.56, 0.64, 1);
      cursor: pointer;
      position: relative;
      overflow: hidden;
      display: flex;
      flex-direction: column;
      justify-content: space-between;
      height: 100%;
      box-shadow: 0 3px 10px rgba(15, 23, 42, 0.04);
    }

    .table-selection-card:hover, .table-selection-card:active {
      transform: translateY(-3px) scale(1.02);
      border-color: var(--accent-orange);
      box-shadow: 0 10px 25px -5px rgba(234, 88, 12, 0.2);
    }

    .table-badge-number {
      font-size: 1.35rem;
      font-weight: 900;
      color: #0F172A;
      letter-spacing: -0.5px;
      line-height: 1.1;
    }

    .status-pill {
      font-size: 0.72rem;
      font-weight: 700;
      padding: 3px 10px;
      border-radius: 999px;
      display: inline-flex;
      align-items: center;
      gap: 5px;
    }

    .status-tersedia {
      background: #ECFDF5;
      color: #065F46;
      border: 1px solid #A7F3D0;
    }

    .status-terisi {
      background: #FFFBEB;
      color: #B45309;
      border: 1px solid #FDE68A;
    }

    .btn-pilih-meja {
      background: linear-gradient(135deg, var(--accent-flame), var(--accent-orange));
      color: #FFFFFF;
      border: none;
      font-weight: 800;
      font-size: 0.85rem;
      border-radius: 12px;
      padding: 9px 14px;
      width: 100%;
      margin-top: 12px;
      transition: all 0.2s ease;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 6px;
      box-shadow: 0 4px 12px rgba(234, 88, 12, 0.25);
    }

    .btn-pilih-meja:hover {
      color: #FFFFFF;
      opacity: 0.95;
    }

    .btn-pilih-meja:active {
      transform: scale(0.96);
    }

    .area-filter-btn {
      background: #FFFFFF;
      border: 1px solid #CBD5E1;
      color: #475569;
      font-size: 0.8rem;
      font-weight: 700;
      padding: 6px 16px;
      border-radius: 999px;
      white-space: nowrap;
      cursor: pointer;
      transition: all 0.2s ease;
    }

    .area-filter-btn.active, .area-filter-btn:hover {
      background: #0F5132;
      color: #FFFFFF;
      border-color: #0F5132;
    }

    .pulse-dot {
      width: 8px;
      height: 8px;
      border-radius: 50%;
      background-color: currentColor;
    }
  </style>
</head>
<body>

  <!-- Top Hero Header -->
  <div class="header-hero">
    <div class="d-flex align-items-center justify-content-center gap-2 mb-2">
      <div class="position-relative">
        <img src="tyas.png" alt="Logo" class="rounded-circle shadow-sm" style="width: 48px; height: 48px; object-fit: cover; border: 2px solid var(--primary-color); background: #fff;">
        <img src="techno garage.jpg" alt="Partner" class="rounded-circle position-absolute bottom-0 end-0 border border-white" style="width: 18px; height: 18px; object-fit: cover; transform: translate(15%, 15%);">
      </div>
      <div class="text-start">
        <h5 class="fw-bold text-dark mb-0 lh-1">DAPUR TYAS</h5>
        <span class="text-muted small" style="font-size: 0.72rem;">Techno Garage &bull; Smart Self-Order</span>
      </div>
    </div>

    <h4 class="fw-bold text-dark mt-3 mb-1" style="letter-spacing: -0.5px;">Selamat Datang! 👋</h4>
    <p class="text-muted small mb-0" style="max-width: 440px; margin: 0 auto;">
      Silakan tentukan <strong>Nomor Meja</strong> tempat Anda duduk untuk mulai memilih dan memesan menu hidangan.
    </p>
  </div>

  <div class="container py-3" style="max-width: 680px; flex-grow: 1;">

    <!-- Filter Tab Area Meja -->
    <div class="d-flex gap-2 overflow-x-auto pb-2 mb-3" style="scrollbar-width: none;">
      <button type="button" class="area-filter-btn active" onclick="filterArea('all', this)">
        <i class="bi bi-grid-fill me-1"></i> Semua Meja (<?= count($daftarMeja) ?>)
      </button>
      <?php foreach ($areaList as $area): ?>
        <?php 
          $countArea = count(array_filter($daftarMeja, fn($m) => $m['nama_area'] === $area));
          $iconArea = (stripos($area, 'outdoor') !== false) ? 'bi-tree-fill' : 'bi-snow';
        ?>
        <button type="button" class="area-filter-btn" onclick="filterArea('<?= htmlspecialchars(addslashes($area)) ?>', this)">
          <i class="bi <?= $iconArea ?> me-1"></i> <?= htmlspecialchars($area) ?> (<?= $countArea ?>)
        </button>
      <?php endforeach; ?>
    </div>

    <!-- Quick Search Meja -->
    <div class="mb-3 position-relative">
      <i class="bi bi-search position-absolute top-50 start-0 translate-middle-y ms-3 text-muted"></i>
      <input type="text" id="searchMejaInput" class="form-control form-control-lg rounded-pill ps-5 border-secondary-subtle small" placeholder="Cari nomor meja (misal: 01, 05, 10)..." style="font-size: 0.9rem;" autocomplete="off">
    </div>

    <!-- Grid Kartu Meja -->
    <div class="row g-3" id="mejaGridContainer">
      <?php foreach ($daftarMeja as $meja): ?>
        <?php 
          $nomor = $meja['nomor_meja'];
          $area = $meja['nama_area'] ?: 'Area Utama';
          $isTerisi = in_array($nomor, $mejaTerisi);
          $kapasitas = $meja['kapasitas'] ?? 4;
        ?>
        <div class="col-6 col-sm-4 col-md-4 meja-item-col" data-nomor="<?= htmlspecialchars($nomor) ?>" data-area="<?= htmlspecialchars($area) ?>">
          <div class="table-selection-card" onclick="pilihMeja('<?= htmlspecialchars($nomor) ?>')">
            <div>
              <div class="d-flex align-items-center justify-content-between mb-2">
                <span class="status-pill <?= $isTerisi ? 'status-terisi' : 'status-tersedia' ?>">
                  <span class="pulse-dot"></span>
                  <?= $isTerisi ? 'Terisi' : 'Tersedia' ?>
                </span>
                <span class="text-muted small" style="font-size: 0.72rem;">
                  <i class="bi bi-people-fill text-secondary"></i> <?= $kapasitas ?> Kursi
                </span>
              </div>

              <div class="text-center py-2">
                <div class="text-muted text-uppercase fw-bold" style="font-size: 0.68rem; letter-spacing: 1px;">Nomor Meja</div>
                <div class="table-badge-number">MEJA <?= htmlspecialchars($nomor) ?></div>
                <div class="text-muted small mt-1" style="font-size: 0.72rem;">
                  <i class="bi <?= (stripos($area, 'outdoor') !== false) ? 'bi-tree' : 'bi-snow' ?> me-1"></i>
                  <?= htmlspecialchars($area) ?>
                </div>
              </div>
            </div>

            <button type="button" class="btn-pilih-meja">
              <span>Buka Menu</span>
              <i class="bi bi-arrow-right"></i>
            </button>
          </div>
        </div>
      <?php endforeach; ?>
    </div>

    <!-- Empty search notice -->
    <div id="emptyMejaNotice" class="text-center py-5 d-none">
      <i class="bi bi-exclamation-circle text-warning fs-1"></i>
      <h6 class="fw-bold mt-2">Nomor meja tidak ditemukan</h6>
      <p class="text-muted small">Silakan periksa nomor meja yang Anda ketikkan.</p>
    </div>

  </div>

  <!-- Footer Info -->
  <footer class="text-center py-3 bg-white border-top text-muted small mt-auto">
    <div class="container">
      <div>Dapur Tyas &copy; <?= date('Y') ?> &bull; Powered by <strong>Techno Garage</strong></div>
      <div style="font-size: 0.7rem;" class="text-secondary mt-1">Sistem Pemesanan Mandiri Berbasis QR Barcode Lokal Wi-Fi</div>
    </div>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    let currentAreaFilter = 'all';

    function filterArea(area, btn) {
      currentAreaFilter = area;
      document.querySelectorAll('.area-filter-btn').forEach(b => b.classList.remove('active'));
      if (btn) btn.classList.add('active');
      applyMejaFilters();
    }

    const searchInput = document.getElementById('searchMejaInput');
    searchInput.addEventListener('input', applyMejaFilters);

    function applyMejaFilters() {
      const q = searchInput.value.trim().toLowerCase();
      const items = document.querySelectorAll('.meja-item-col');
      let visibleCount = 0;

      items.forEach(item => {
        const nomor = item.getAttribute('data-nomor').toLowerCase();
        const area = item.getAttribute('data-area').toLowerCase();

        const matchQuery = !q || nomor.includes(q) || ('meja ' + nomor).includes(q) || area.includes(q);
        const matchArea = (currentAreaFilter === 'all') || (item.getAttribute('data-area') === currentAreaFilter);

        if (matchQuery && matchArea) {
          item.style.display = 'block';
          visibleCount++;
        } else {
          item.style.display = 'none';
        }
      });

      const emptyNotice = document.getElementById('emptyMejaNotice');
      if (visibleCount === 0) {
        emptyNotice.classList.remove('d-none');
      } else {
        emptyNotice.classList.add('d-none');
      }
    }

    function pilihMeja(nomorMeja) {
      if ('vibrate' in navigator) {
        navigator.vibrate(25);
      }
      // Simpan nomor meja di localStorage
      localStorage.setItem('dapur_tyas_meja_terpilih', nomorMeja);
      // Arahkan ke menu.php dengan parameter meja
      window.location.href = `menu.php?meja=${encodeURIComponent(nomorMeja)}`;
    }
  </script>
</body>
</html>
