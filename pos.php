<?php
/**
 * POS Kasir & Admin Dashboard (Desktop Only)
 * Dapur Tyas by Techno Garage
 */

require_once __DIR__ . '/config.php';

// 1. Proteksi Deteksi Perangkat Mobile
if (isMobileDevice()) {
    header("Location: akses-ditolak.php");
    exit;
}

// 2. Proteksi Wajib Login Staf
if (empty($_SESSION['staf_id'])) {
    header("Location: login.php");
    exit;
}

// Ambil data staf login
$stafNama = $_SESSION['staf_nama'];
$stafRole = $_SESSION['staf_role']; // 'admin' atau 'kasir'
$currentTab = $_GET['tab'] ?? ($stafRole === 'admin' ? 'admin' : 'pos');

// Pesan feedback operasi admin
$pesanSukses = '';
$pesanError = '';

// Handle Operasi Admin: Tambah Meja & Hapus Meja
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    if ($stafRole !== 'admin') {
        $pesanError = 'Akses ditolak: Hanya Admin yang berhak mengelola meja!';
    } else {
        $action = $_POST['action'];

        if ($action === 'tambah_meja') {
            $nomorMeja = trim($_POST['nomor_meja'] ?? '');
            $namaArea  = trim($_POST['nama_area'] ?? 'Utama');
            $kapasitas = (int)($_POST['kapasitas'] ?? 4);

            if (!empty($nomorMeja)) {
                // Standarkan nomor meja misalnya 1 digit jadi 2 digit jika angka
                if (is_numeric($nomorMeja) && strlen($nomorMeja) === 1) {
                    $nomorMeja = str_pad($nomorMeja, 2, '0', STR_PAD_LEFT);
                }

                try {
                    $stmt = $pdo->prepare("INSERT INTO tabel_meja (nomor_meja, nama_area, kapasitas, status) VALUES (?, ?, ?, 'tersedia')");
                    $stmt->execute([$nomorMeja, $namaArea, $kapasitas]);
                    $pesanSukses = "Berhasil menambah Meja {$nomorMeja} ({$namaArea})!";
                } catch (PDOException $e) {
                    if ($e->getCode() == 23000) {
                        $pesanError = "Gagal: Meja {$nomorMeja} sudah terdaftar!";
                    } else {
                        $pesanError = "Terjadi kesalahan: " . $e->getMessage();
                    }
                }
            } else {
                $pesanError = 'Nomor meja tidak boleh kosong!';
            }
        } elseif ($action === 'hapus_meja') {
            $mejaId = (int)($_POST['meja_id'] ?? 0);
            if ($mejaId > 0) {
                try {
                    $stmt = $pdo->prepare("DELETE FROM tabel_meja WHERE id = ?");
                    $stmt->execute([$mejaId]);
                    $pesanSukses = "Meja berhasil dihapus!";
                } catch (PDOException $e) {
                    $pesanError = "Gagal menghapus meja: " . $e->getMessage();
                }
            }
        } elseif ($action === 'update_status_meja') {
            $mejaId = (int)($_POST['meja_id'] ?? 0);
            $statusBaru = $_POST['status'] ?? 'tersedia';
            if ($mejaId > 0 && in_array($statusBaru, ['tersedia', 'terisi', 'reservasi'])) {
                try {
                    $stmt = $pdo->prepare("UPDATE tabel_meja SET status = ? WHERE id = ?");
                    $stmt->execute([$statusBaru, $mejaId]);
                    $pesanSukses = "Status meja berhasil diperbarui!";
                } catch (PDOException $e) {
                    $pesanError = "Gagal mengubah status: " . $e->getMessage();
                }
            }
        } elseif ($action === 'tambah_menu') {
            $namaMenu = trim($_POST['nama_menu'] ?? '');
            $kategori = trim($_POST['kategori'] ?? 'Makanan');
            $harga    = (int)($_POST['harga'] ?? 0);
            $deskripsi= trim($_POST['deskripsi'] ?? '');
            $foto     = trim($_POST['foto'] ?? 'tyas.png');
            $tag      = trim($_POST['tag'] ?? '');

            if (!empty($namaMenu) && $harga > 0) {
                if (empty($foto)) $foto = 'tyas.png';
                try {
                    $stmt = $pdo->prepare("INSERT INTO tabel_menu (nama_menu, kategori, harga, deskripsi, foto, tag, tersedia) VALUES (?, ?, ?, ?, ?, ?, 1)");
                    $stmt->execute([$namaMenu, $kategori, $harga, $deskripsi, $foto, $tag]);
                    $pesanSukses = "Menu '{$namaMenu}' berhasil ditambahkan ke katalog!";
                } catch (PDOException $e) {
                    $pesanError = "Gagal menambah menu: " . $e->getMessage();
                }
            } else {
                $pesanError = 'Nama menu dan harga valid wajib diisi!';
            }
        } elseif ($action === 'toggle_menu') {
            $menuId = (int)($_POST['menu_id'] ?? 0);
            $tersedia = (int)($_POST['tersedia'] ?? 1);
            if ($menuId > 0) {
                try {
                    $stmt = $pdo->prepare("UPDATE tabel_menu SET tersedia = ? WHERE id = ?");
                    $stmt->execute([$tersedia, $menuId]);
                    $pesanSukses = "Status ketersediaan menu berhasil diubah!";
                } catch (PDOException $e) {
                    $pesanError = "Gagal mengubah status menu: " . $e->getMessage();
                }
            }
        } elseif ($action === 'hapus_menu') {
            $menuId = (int)($_POST['menu_id'] ?? 0);
            if ($menuId > 0) {
                try {
                    $stmt = $pdo->prepare("DELETE FROM tabel_menu WHERE id = ?");
                    $stmt->execute([$menuId]);
                    $pesanSukses = "Menu berhasil dihapus dari daftar!";
                } catch (PDOException $e) {
                    $pesanError = "Gagal menghapus menu: " . $e->getMessage();
                }
            }
        }
    }
}

// Ambil data Meja dari tabel_meja
try {
    $daftarMeja = $pdo->query("SELECT * FROM tabel_meja ORDER BY LPAD(nomor_meja, 10, '0') ASC, nomor_meja ASC")->fetchAll();
} catch (PDOException $e) {
    $daftarMeja = [];
}

// Ambil data Menu untuk dikelola Admin
try {
    $daftarMenuAdmin = $pdo->query("SELECT * FROM tabel_menu ORDER BY kategori ASC, nama_menu ASC")->fetchAll();
} catch (PDOException $e) {
    $daftarMenuAdmin = [];
}

// Ambil semua pesanan dari MySQL
$stmt = $pdo->query("
    SELECT p.*, GROUP_CONCAT(CONCAT(d.jumlah, 'x ', d.nama_menu) SEPARATOR ', ') as rincian_menu
    FROM tabel_pesanan p
    LEFT JOIN tabel_detail_pesanan d ON p.id = d.pesanan_id
    GROUP BY p.id
    ORDER BY p.id DESC
");
$orders = $stmt->fetchAll();

// Hitung metrik
$waiting = array_filter($orders, fn($o) => $o['status_pesanan'] === 'menunggu_kasir');
$cooking = array_filter($orders, fn($o) => $o['status_pesanan'] === 'dimasak_dapur');
$ready   = array_filter($orders, fn($o) => $o['status_pesanan'] === 'siap_saji');
$done    = array_filter($orders, fn($o) => $o['status_pesanan'] === 'selesai');

$totalOmzet = array_reduce($done, fn($carry, $item) => $carry + $item['total_bayar'], 0);
$maxOrderId = !empty($orders) ? max(array_column($orders, 'id')) : 0;
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= $stafRole === 'admin' ? 'Admin Panel & POS' : 'POS Kasir' ?> - Dapur Tyas</title>
  
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <link rel="stylesheet" href="assets/css/style.css">
  <script src="https://unpkg.com/html5-qrcode"></script>

  <style>
    .nav-tab-custom {
      display: flex;
      gap: 8px;
      border-bottom: 2px solid var(--border-color);
      margin-bottom: 20px;
    }
    .nav-tab-btn {
      padding: 10px 20px;
      font-weight: 700;
      font-size: 0.92rem;
      border: none;
      background: transparent;
      color: var(--text-muted);
      border-bottom: 3px solid transparent;
      transition: all 0.2s ease;
      text-decoration: none;
      display: inline-flex;
      align-items: center;
      gap: 8px;
    }
    .nav-tab-btn:hover {
      color: var(--primary);
    }
    .nav-tab-btn.active {
      color: var(--primary);
      border-bottom-color: var(--primary);
      background: rgba(116, 140, 105, 0.08);
      border-radius: 8px 8px 0 0;
    }
    .table-box-card {
      border: 1px solid var(--border-color);
      border-radius: var(--radius-md);
      transition: all 0.2s ease;
      background: #fff;
    }
    .table-box-card:hover {
      border-color: var(--primary);
      box-shadow: var(--shadow-sm);
    }
    .role-badge-admin {
      background: #4F46E5;
      color: #fff;
      font-weight: 700;
      padding: 3px 10px;
      border-radius: var(--radius-full);
      font-size: 0.72rem;
    }
    .role-badge-kasir {
      background: #D9A05B;
      color: #1A2B22;
      font-weight: 700;
      padding: 3px 10px;
      border-radius: var(--radius-full);
      font-size: 0.72rem;
    }
  </style>

  <script>
    // JS Device Guard
    if (/Mobi|Android|iPhone|iPad|iPod/i.test(navigator.userAgent) || window.innerWidth <= 768) {
      window.location.href = "akses-ditolak.php";
    }
  </script>
</head>
<body style="background-color: var(--bg-primary);">

  <!-- Navbar Staf Desktop -->
  <nav class="navbar navbar-custom px-4 py-2 sticky-top">
    <div class="container-fluid d-flex align-items-center justify-content-between">
      
      <div class="d-flex align-items-center gap-3">
        <div class="position-relative">
          <img src="tyas.png" alt="Logo Dapur Tyas" class="rounded-circle" style="width: 44px; height: 44px; object-fit: cover; border: 2px solid var(--primary); background: #fff;">
          <img src="techno garage.jpg" alt="Techno Garage" class="rounded-circle position-absolute bottom-0 end-0 border border-white" style="width: 18px; height: 18px; object-fit: cover; transform: translate(20%, 20%);">
        </div>
        <div>
          <div class="d-flex align-items-center gap-2">
            <span class="fw-bold fs-5 text-main">DAPUR TYAS</span>
            <span class="<?= $stafRole === 'admin' ? 'role-badge-admin' : 'role-badge-kasir' ?>">
              <i class="bi <?= $stafRole === 'admin' ? 'bi-shield-check' : 'bi-laptop' ?>"></i> 
              <?= strtoupper($stafRole) ?>
            </span>
          </div>
          <div class="small text-muted" style="font-size: 0.72rem;">Sistem Manajemen Kasir & Resto by Techno Garage</div>
        </div>
      </div>

      <div class="d-flex align-items-center gap-3">
        <!-- Live Audio Alert Toggle (Khusus Kasir / Monitor) -->
        <button id="btnEnableAudio" class="btn btn-sm btn-outline-success" onclick="enableAudioAlert()">
          <i class="bi bi-volume-up-fill"></i> <span id="audioStatusText">Suara Notif Aktif</span>
        </button>

        <div class="text-end">
          <div class="fw-bold text-main"><?= htmlspecialchars($stafNama) ?></div>
          <div class="small text-muted text-uppercase"><?= htmlspecialchars($stafRole) ?></div>
        </div>

        <!-- Live Wi-Fi IP Indicator -->
        <span class="badge bg-light text-dark border d-none d-lg-inline-flex align-items-center gap-1 py-2 px-3" title="IP Wi-Fi Laptop yang digunakan untuk QR Meja & Menu">
          <i class="bi bi-wifi text-success"></i>
          <span>IP Wi-Fi: <strong><?= htmlspecialchars(getLocalServerIP()) ?></strong></span>
        </span>

        <a href="barcode.php" target="_blank" class="btn btn-sm btn-outline-custom">
          <i class="bi bi-qr-code"></i> Cetak QR Meja
        </a>

        <a href="logout.php" class="btn btn-sm btn-outline-danger">
          <i class="bi bi-box-arrow-right"></i> Logout
        </a>
      </div>

    </div>
  </nav>

  <!-- Content Container -->
  <div class="container-fluid px-4 py-3">

    <!-- Notifikasi Sukses / Error -->
    <?php if (!empty($pesanSukses)): ?>
      <div class="alert alert-success alert-dismissible fade show py-2 px-3 small d-flex align-items-center gap-2 mb-3" role="alert">
        <i class="bi bi-check-circle-fill fs-5"></i>
        <div><?= htmlspecialchars($pesanSukses) ?></div>
        <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
      </div>
    <?php endif; ?>

    <?php if (!empty($pesanError)): ?>
      <div class="alert alert-danger alert-dismissible fade show py-2 px-3 small d-flex align-items-center gap-2 mb-3" role="alert">
        <i class="bi bi-exclamation-triangle-fill fs-5"></i>
        <div><?= htmlspecialchars($pesanError) ?></div>
        <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
      </div>
    <?php endif; ?>

    <!-- Navigation Tab Pembeda Kasir vs Admin -->
    <div class="nav-tab-custom">
      <a href="pos.php?tab=pos" class="nav-tab-btn <?= $currentTab === 'pos' ? 'active' : '' ?>">
        <i class="bi bi-receipt"></i>
        <span>Antrean Kasir</span>
        <?php if (count($waiting) > 0): ?>
          <span class="badge rounded-pill bg-danger"><?= count($waiting) ?></span>
        <?php endif; ?>
      </a>

      <?php if ($stafRole === 'admin'): ?>
        <a href="pos.php?tab=admin" class="nav-tab-btn <?= $currentTab === 'admin' ? 'active' : '' ?>">
          <i class="bi bi-grid-1x2-fill"></i>
          <span>Kelola Meja Resto (Admin)</span>
          <span class="badge rounded-pill bg-primary"><?= count($daftarMeja) ?> Meja</span>
        </a>
        <a href="pos.php?tab=menu" class="nav-tab-btn <?= $currentTab === 'menu' ? 'active' : '' ?>">
          <i class="bi bi-egg-fried"></i>
          <span>Kelola Menu Resto (Admin)</span>
          <span class="badge rounded-pill bg-success"><?= count($daftarMenuAdmin) ?> Menu</span>
        </a>
      <?php endif; ?>
    </div>

    <?php if ($currentTab === 'admin' && $stafRole === 'admin'): ?>
      <!-- ================= TAB ADMIN: KELOLA MEJA ================= -->
      <div class="row g-4">
        <!-- Form Tambah Meja Baru -->
        <div class="col-12 col-lg-4">
          <div class="card-custom bg-white p-4 shadow-sm h-100">
            <div class="d-flex align-items-center gap-2 mb-3">
              <div class="p-2 bg-primary-subtle text-primary rounded-circle">
                <i class="bi bi-plus-circle-fill fs-5"></i>
              </div>
              <div>
                <h6 class="fw-bold mb-0 text-main">Tambah Meja Resto</h6>
                <small class="text-muted">Buat meja baru untuk pemesanan barcode QR</small>
              </div>
            </div>

            <form method="POST" action="pos.php?tab=admin">
              <input type="hidden" name="action" value="tambah_meja">

              <div class="mb-3">
                <label class="form-label small fw-bold text-main">Nomor / Kode Meja:</label>
                <div class="input-group">
                  <span class="input-group-text bg-light text-muted">MEJA</span>
                  <input type="text" name="nomor_meja" class="form-control" placeholder="Contoh: 13, 14, VIP-1" required>
                </div>
                <div class="text-muted small mt-1" style="font-size: 0.72rem;">*Bisa angka (misal 13) atau teks khusus (VIP-01).</div>
              </div>

              <div class="mb-3">
                <label class="form-label small fw-bold text-main">Area / Ruangan:</label>
                <select name="nama_area" class="form-select">
                  <option value="Indoor AC">Indoor AC (Dalam Ruangan)</option>
                  <option value="Outdoor Garden">Outdoor Garden (Taman)</option>
                  <option value="Lantai 2 Rooftop">Lantai 2 Rooftop</option>
                  <option value="Area VIP">Area VIP</option>
                  <option value="Area Utama">Area Utama</option>
                </select>
              </div>

              <div class="mb-4">
                <label class="form-label small fw-bold text-main">Kapasitas Kursi:</label>
                <div class="input-group">
                  <input type="number" name="kapasitas" class="form-control" value="4" min="1" max="20">
                  <span class="input-group-text bg-light text-muted">Orang</span>
                </div>
              </div>

              <button type="submit" class="btn btn-primary-custom w-100 py-2 shadow-xs fw-bold">
                <i class="bi bi-plus-lg"></i> Simpan & Buat Meja Baru
              </button>
            </form>

            <div class="border-top mt-4 pt-3 text-muted small">
              <i class="bi bi-info-circle text-primary me-1"></i>
              Setelah meja ditambahkan, QR Barcode meja tersebut akan otomatis tersedia di menu <strong>Cetak QR Meja</strong> untuk di-print atau ditaruh di meja resto.
            </div>
          </div>
        </div>

        <!-- Daftar Meja Resto Aktif -->
        <div class="col-12 col-lg-8">
          <div class="card-custom bg-white p-4 shadow-sm">
            <div class="d-flex justify-content-between align-items-center mb-3">
              <div>
                <h6 class="fw-bold mb-0 text-main">
                  <i class="bi bi-table text-success me-2"></i>Daftar Meja Terdaftar
                </h6>
                <small class="text-muted">Total: <?= count($daftarMeja) ?> meja siap melayani pelanggan</small>
              </div>
              <a href="barcode.php" target="_blank" class="btn btn-sm btn-outline-custom">
                <i class="bi bi-printer-fill"></i> Buka Layar Cetak QR
              </a>
            </div>

            <div class="row g-3">
              <?php if (empty($daftarMeja)): ?>
                <div class="col-12 text-center py-5 text-muted">
                  <i class="bi bi-slash-circle fs-2"></i>
                  <p class="mt-2 mb-0">Belum ada meja. Silakan tambahkan meja di form sebelah kiri.</p>
                </div>
              <?php else: ?>
                <?php foreach ($daftarMeja as $meja): ?>
                  <div class="col-12 col-sm-6 col-md-4">
                    <div class="table-box-card p-3 d-flex flex-column justify-content-between h-100">
                      <div>
                        <div class="d-flex justify-content-between align-items-center mb-2">
                          <span class="badge bg-dark fs-6 px-3 py-1">MEJA <?= htmlspecialchars($meja['nomor_meja']) ?></span>
                          <span class="badge <?= $meja['status'] === 'tersedia' ? 'bg-success' : ($meja['status'] === 'terisi' ? 'bg-warning text-dark' : 'bg-info') ?>">
                            <?= ucfirst($meja['status']) ?>
                          </span>
                        </div>
                        <div class="small text-muted mb-1">
                          <i class="bi bi-geo-alt-fill text-danger me-1"></i> Area: <strong><?= htmlspecialchars($meja['nama_area']) ?></strong>
                        </div>
                        <div class="small text-muted mb-3">
                          <i class="bi bi-people-fill text-primary me-1"></i> Kapasitas: <strong><?= htmlspecialchars($meja['kapasitas']) ?> Orang</strong>
                        </div>
                      </div>

                      <div class="border-top pt-2 d-flex justify-content-between align-items-center gap-2">
                        <!-- Link Cek Menu Pelanggan -->
                        <a href="menu.php?meja=<?= urlencode($meja['nomor_meja']) ?>" target="_blank" class="btn btn-sm btn-light border small text-muted" title="Buka Tampilan Menu Meja Ini">
                          <i class="bi bi-eye"></i> Menu
                        </a>

                        <!-- Form Hapus Meja -->
                        <form method="POST" action="pos.php?tab=admin" onsubmit="return confirm('Apakah Anda yakin ingin menghapus Meja <?= htmlspecialchars($meja['nomor_meja']) ?>?');">
                          <input type="hidden" name="action" value="hapus_meja">
                          <input type="hidden" name="meja_id" value="<?= $meja['id'] ?>">
                          <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus Meja">
                            <i class="bi bi-trash3"></i> Hapus
                          </button>
                        </form>
                      </div>
                    </div>
                  </div>
                <?php endforeach; ?>
              <?php endif; ?>
            </div>
          </div>
        </div>
      </div>

    <?php elseif ($currentTab === 'menu' && $stafRole === 'admin'): ?>
      <!-- ================= TAB ADMIN: KELOLA MENU ================= -->
      <div class="row g-4">
        <!-- Form Tambah Menu Baru -->
        <div class="col-12 col-lg-4">
          <div class="card-custom bg-white p-4 shadow-sm h-100">
            <div class="d-flex align-items-center gap-2 mb-3">
              <div class="p-2 bg-success-subtle text-success rounded-circle">
                <i class="bi bi-plus-circle-fill fs-5"></i>
              </div>
              <div>
                <h6 class="fw-bold mb-0 text-main">Tambah Menu Resto</h6>
                <small class="text-muted">Tambahkan menu makanan/minuman baru</small>
              </div>
            </div>

            <form method="POST" action="pos.php?tab=menu">
              <input type="hidden" name="action" value="tambah_menu">

              <div class="mb-3">
                <label class="form-label small fw-bold text-main">Nama Menu:</label>
                <input type="text" name="nama_menu" class="form-control" placeholder="Contoh: Ayam Bakar Madu Spesial" required>
              </div>

              <div class="mb-3">
                <label class="form-label small fw-bold text-main">Kategori:</label>
                <select name="kategori" class="form-select">
                  <option value="Makanan">Makanan</option>
                  <option value="Minuman">Minuman</option>
                  <option value="Camilan">Camilan</option>
                  <option value="Dessert">Dessert</option>
                  <option value="Paket Hemat">Paket Hemat</option>
                </select>
              </div>

              <div class="mb-3">
                <label class="form-label small fw-bold text-main">Harga (Rp):</label>
                <div class="input-group">
                  <span class="input-group-text bg-light text-muted">Rp</span>
                  <input type="number" name="harga" class="form-control" placeholder="25000" min="1000" step="500" required>
                </div>
              </div>

              <div class="mb-3">
                <label class="form-label small fw-bold text-main">Deskripsi Menu:</label>
                <textarea name="deskripsi" class="form-control" rows="2" placeholder="Penjelasan singkat rasa, bumbu, atau porsi"></textarea>
              </div>

              <div class="mb-3">
                <label class="form-label small fw-bold text-main">URL / File Foto Menu:</label>
                <input type="text" name="foto" class="form-control" value="tyas.png" placeholder="tyas.png atau URL gambar online">
                <small class="text-muted" style="font-size:0.72rem;">Default: tyas.png atau link https://...</small>
              </div>

              <div class="mb-4">
                <label class="form-label small fw-bold text-main">Tag Khusus (Opsional):</label>
                <input type="text" name="tag" class="form-control" placeholder="Contoh: Best Seller, Favorit, Pedas">
              </div>

              <button type="submit" class="btn btn-primary-custom w-100 py-2 shadow-xs fw-bold">
                <i class="bi bi-plus-lg"></i> Simpan & Terbitkan Menu
              </button>
            </form>
          </div>
        </div>

        <!-- Daftar Menu Resto -->
        <div class="col-12 col-lg-8">
          <div class="card-custom bg-white p-4 shadow-sm">
            <div class="d-flex justify-content-between align-items-center mb-3">
              <div>
                <h6 class="fw-bold mb-0 text-main">
                  <i class="bi bi-journals text-success me-2"></i>Daftar Menu Restoran
                </h6>
                <small class="text-muted">Total: <?= count($daftarMenuAdmin) ?> menu terdaftar di katalog</small>
              </div>
              <a href="menu.php" target="_blank" class="btn btn-sm btn-outline-custom">
                <i class="bi bi-phone"></i> Tinjau Menu Pelanggan
              </a>
            </div>

            <div class="table-responsive">
              <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                  <tr class="small text-muted">
                    <th>Foto</th>
                    <th>Nama Menu</th>
                    <th>Kategori</th>
                    <th>Harga</th>
                    <th>Status</th>
                    <th class="text-end">Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  <?php if (empty($daftarMenuAdmin)): ?>
                    <tr>
                      <td colspan="6" class="text-center py-4 text-muted">Belum ada menu yang didaftarkan.</td>
                    </tr>
                  <?php else: ?>
                    <?php foreach ($daftarMenuAdmin as $m): ?>
                      <tr>
                        <td style="width: 50px;">
                          <img src="<?= htmlspecialchars($m['foto']) ?>" alt="" onerror="this.src='tyas.png'" class="rounded-2 shadow-2xs" style="width: 44px; height: 44px; object-fit: cover;">
                        </td>
                        <td>
                          <div class="fw-bold text-main"><?= htmlspecialchars($m['nama_menu']) ?></div>
                          <?php if (!empty($m['tag'])): ?>
                            <span class="badge bg-warning text-dark" style="font-size: 0.68rem;"><?= htmlspecialchars($m['tag']) ?></span>
                          <?php endif; ?>
                          <div class="text-muted small text-truncate" style="max-width: 200px;"><?= htmlspecialchars($m['deskripsi'] ?? '') ?></div>
                        </td>
                        <td>
                          <span class="badge bg-light text-dark border"><?= htmlspecialchars($m['kategori']) ?></span>
                        </td>
                        <td class="fw-bold text-success">
                          <?= formatRupiah($m['harga']) ?>
                        </td>
                        <td>
                          <form method="POST" action="pos.php?tab=menu" class="d-inline">
                            <input type="hidden" name="action" value="toggle_menu">
                            <input type="hidden" name="menu_id" value="<?= $m['id'] ?>">
                            <input type="hidden" name="tersedia" value="<?= $m['tersedia'] ? 0 : 1 ?>">
                            <button type="submit" class="badge border-0 <?= $m['tersedia'] ? 'bg-success' : 'bg-secondary' ?>" title="Klik untuk ubah ketersediaan">
                              <?= $m['tersedia'] ? 'Tersedia' : 'Habis' ?>
                            </button>
                          </form>
                        </td>
                        <td class="text-end">
                          <form method="POST" action="pos.php?tab=menu" class="d-inline" onsubmit="return confirm('Hapus menu <?= htmlspecialchars(addslashes($m['nama_menu'])) ?>?');">
                            <input type="hidden" name="action" value="hapus_menu">
                            <input type="hidden" name="menu_id" value="<?= $m['id'] ?>">
                            <button type="submit" class="btn btn-sm btn-outline-danger p-1 px-2" title="Hapus Menu">
                              <i class="bi bi-trash3"></i>
                            </button>
                          </form>
                        </td>
                      </tr>
                    <?php endforeach; ?>
                  <?php endif; ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>

    <?php else: ?>
      <!-- ================= TAB KASIR: POS & ANTREAN PESANAN ================= -->

      <!-- Container Peringatan Darurat (Anti-Keluar Paksa) -->
      <div id="securityAlertArea"></div>

      <!-- KPI Summary -->
      <div class="row g-3 mb-4">
        <div class="col-md-3">
          <div class="card-custom p-3 bg-white">
            <div class="text-muted small">Pesanan Baru (Barcode)</div>
            <div class="fw-bold fs-3 text-warning" id="countWaiting"><?= count($waiting) ?></div>
            <div class="small text-muted">Menunggu Konfirmasi Kasir</div>
          </div>
        </div>

        <div class="col-md-3">
          <div class="card-custom p-3 bg-white">
            <div class="text-muted small">Sedang Dimasak Dapur</div>
            <div class="fw-bold fs-3 text-primary"><?= count($cooking) ?></div>
            <div class="small text-muted">Koki Sedang Menyiapkan</div>
          </div>
        </div>

        <div class="col-md-3">
          <div class="card-custom p-3 bg-white">
            <div class="text-muted small">Siap Saji / Diantar</div>
            <div class="fw-bold fs-3 text-success"><?= count($ready) ?></div>
            <div class="small text-muted">Siap Diantar ke Meja</div>
          </div>
        </div>

        <div class="col-md-3">
          <div class="card-custom p-3 bg-white">
            <div class="text-muted small">Total Omzet Transaksi Selesai</div>
            <div class="fw-bold fs-4 text-main"><?= formatRupiah($totalOmzet) ?></div>
            <div class="small text-muted">Lunas</div>
          </div>
        </div>
      </div>

      <!-- Orders Grid Header -->
      <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
        <h5 class="fw-bold text-main mb-0">
          <i class="bi bi-inbox-fill text-success me-2"></i>Antrean Pesanan Masuk Pelanggan
        </h5>
        <div class="d-flex align-items-center gap-2">
          <!-- Tombol Scan Barcode Pesanan Pelanggan -->
          <button class="btn btn-sm btn-primary-custom shadow-xs" onclick="openScannerModal()">
            <i class="bi bi-upc-scan"></i> <strong>Scan Barcode Pelanggan</strong>
          </button>
          <span class="badge bg-light text-muted border d-none d-md-inline" id="pollingBadge"><i class="bi bi-dot text-success fs-5"></i> Live Polling</span>
          <button class="btn btn-sm btn-outline-secondary" onclick="location.reload()">
            <i class="bi bi-arrow-clockwise"></i> Segarkan Data
          </button>
        </div>
      </div>

      <!-- Grid Pesanan Masuk -->
      <div class="row g-3">
        <?php if (empty($orders)): ?>
          <div class="col-12 text-center py-5 bg-white card-custom">
            <i class="bi bi-cup-hot text-muted fs-1"></i>
            <h6 class="fw-bold mt-2">Belum ada pesanan dari pelanggan</h6>
            <p class="text-muted small">Saat pelanggan scan barcode meja dan menekan pesan, tiket otomatis masuk ke kasir dengan notifikasi suara.</p>
          </div>
        <?php else: ?>
          <?php foreach ($orders as $ord): ?>
            <div class="col-12 col-md-6 col-lg-4">
              <div class="card-custom bg-white p-3 d-flex flex-column justify-content-between h-100" style="border-left: 6px solid <?= $ord['status_pesanan'] === 'menunggu_kasir' ? '#D9A05B' : ($ord['status_pesanan'] === 'dimasak_dapur' ? '#0D6EFD' : '#3E8E41') ?>;">
                <div>
                  <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="badge bg-dark fs-6 px-3 py-1">MEJA <?= htmlspecialchars($ord['nomor_meja']) ?></span>
                    <span class="badge <?= $ord['status_pesanan'] === 'menunggu_kasir' ? 'bg-warning text-dark' : ($ord['status_pesanan'] === 'dimasak_dapur' ? 'bg-primary' : 'bg-success') ?>">
                      <?= htmlspecialchars($ord['status_pesanan']) ?>
                    </span>
                  </div>

                  <div class="d-flex justify-content-between small text-muted mb-2">
                    <strong>#<?= htmlspecialchars($ord['kode_pesanan']) ?></strong>
                    <span><?= date('H:i', strtotime($ord['waktu_pesan'])) ?> WIB</span>
                  </div>

                  <div class="small mb-1 d-flex align-items-center justify-content-between">
                    <div><i class="bi bi-person-fill text-muted"></i> <strong><?= htmlspecialchars($ord['nama_pelanggan']) ?></strong></div>
                    <div>
                      <span class="badge <?= ($ord['tipe_pesanan'] ?? '') === 'take_away' ? 'bg-info text-dark' : 'bg-success-subtle text-success border border-success' ?>" style="font-size: 0.72rem;">
                        <i class="bi <?= ($ord['tipe_pesanan'] ?? '') === 'take_away' ? 'bi-bag-check-fill' : 'bi-shop' ?>"></i>
                        <?= ($ord['tipe_pesanan'] ?? '') === 'take_away' ? 'Bawa Pulang' : 'Makan di Tempat' ?>
                      </span>
                    </div>
                  </div>

                  <!-- Metode Pembayaran Badge & Permintaan Struk -->
                  <div class="mb-2 d-flex flex-wrap gap-1 align-items-center">
                    <span class="badge <?= ($ord['metode_bayar'] ?? '') === 'ewallet' ? 'bg-info-subtle text-info-emphasis border border-info' : 'bg-warning-subtle text-warning-emphasis border border-warning' ?>">
                      <i class="bi <?= ($ord['metode_bayar'] ?? '') === 'ewallet' ? 'bi-qr-code' : 'bi-shop' ?>"></i>
                      <?= ($ord['metode_bayar'] ?? '') === 'ewallet' ? 'E-Wallet' : 'Tunai / Kasir' ?>
                    </span>
                    <span class="badge <?= $ord['status_bayar'] === 'lunas' ? 'bg-success' : 'bg-secondary' ?>">
                      <?= strtoupper($ord['status_bayar']) ?>
                    </span>
                    <?php if (!empty($ord['minta_struk'])): ?>
                      <span class="badge bg-warning text-dark border border-warning fw-bold animate__animated animate__pulse animate__infinite" title="Pelanggan meminta struk fisik">
                        <i class="bi bi-printer-fill me-1"></i> MINTA STRUK
                      </span>
                    <?php else: ?>
                      <span class="badge bg-light text-muted border" title="Pelanggan memilih e-struk (Paperless)">
                        <i class="bi bi-phone me-1"></i> Paperless
                      </span>
                    <?php endif; ?>
                  </div>

                  <!-- Rincian Menu -->
                  <div class="bg-light p-2 rounded-2 mb-3 small">
                    <?= htmlspecialchars($ord['rincian_menu']) ?>
                    <?php if (!empty($ord['catatan'])): ?>
                      <div class="mt-1 text-danger"><em>*<?= htmlspecialchars($ord['catatan']) ?></em></div>
                    <?php endif; ?>
                  </div>

                  <div class="d-flex justify-content-between align-items-center mb-3">
                    <span class="fw-bold">Total:</span>
                    <span class="fw-bold fs-5 text-success"><?= formatRupiah($ord['total_bayar']) ?></span>
                  </div>
                </div>

                <!-- Action Kasir: Langsung Kirim ke Dapur & Fleksibilitas Bayar Kasir -->
                <div class="border-top pt-2 d-flex flex-column gap-2">
                  <?php if ($ord['status_pesanan'] === 'menunggu_kasir'): ?>
                    <button class="btn btn-primary-custom w-100 py-2 shadow-xs fw-bold" onclick="confirmOrderOk(<?= $ord['id'] ?>, '<?= htmlspecialchars($ord['kode_pesanan']) ?>', '<?= htmlspecialchars($ord['nomor_meja']) ?>')">
                      <i class="bi bi-fire me-1"></i> <strong>OK - Langsung Kirim ke Dapur</strong>
                    </button>
                    <?php if ($ord['status_bayar'] !== 'lunas'): ?>
                      <button class="btn btn-outline-success btn-sm w-100 py-1 fw-bold" onclick='openCashierPaymentModal(<?= htmlspecialchars(json_encode([
                        "id" => (int)$ord["id"],
                        "kode_pesanan" => $ord["kode_pesanan"],
                        "nomor_meja" => $ord["nomor_meja"],
                        "nama_pelanggan" => $ord["nama_pelanggan"],
                        "total_bayar" => (int)$ord["total_bayar"],
                        "total_bayar_rp" => formatRupiah($ord["total_bayar"]),
                        "metode_bayar" => $ord["metode_bayar"]
                      ])) ?>)'>
                        <i class="bi bi-cash-coin me-1"></i> Terima Bayar Sekarang (Cash/E-Wallet)
                      </button>
                    <?php endif; ?>
                  <?php elseif ($ord['status_pesanan'] === 'dimasak_dapur'): ?>
                    <button class="btn btn-outline-primary w-100 fw-bold" onclick="updateStatus(<?= $ord['id'] ?>, 'siap_saji')">
                      <i class="bi bi-bell-fill me-1"></i> Tandai Siap Saji / Diantar ke Meja
                    </button>
                    <?php if ($ord['status_bayar'] !== 'lunas'): ?>
                      <button class="btn btn-outline-success btn-sm w-100 py-1" onclick='openCashierPaymentModal(<?= htmlspecialchars(json_encode([
                        "id" => (int)$ord["id"],
                        "kode_pesanan" => $ord["kode_pesanan"],
                        "nomor_meja" => $ord["nomor_meja"],
                        "nama_pelanggan" => $ord["nama_pelanggan"],
                        "total_bayar" => (int)$ord["total_bayar"],
                        "total_bayar_rp" => formatRupiah($ord["total_bayar"]),
                        "metode_bayar" => $ord["metode_bayar"]
                      ])) ?>)'>
                        <i class="bi bi-cash-coin me-1"></i> Bayar Kasir
                      </button>
                    <?php endif; ?>
                  <?php elseif ($ord['status_pesanan'] === 'siap_saji'): ?>
                    <button class="btn btn-success w-100 fw-bold" onclick="updateStatus(<?= $ord['id'] ?>, 'selesai')">
                      <i class="bi bi-check2-circle me-1"></i> Selesaikan Transaksi Meja
                    </button>
                    <?php if ($ord['status_bayar'] !== 'lunas'): ?>
                      <button class="btn btn-outline-success btn-sm w-100 py-1" onclick='openCashierPaymentModal(<?= htmlspecialchars(json_encode([
                        "id" => (int)$ord["id"],
                        "kode_pesanan" => $ord["kode_pesanan"],
                        "nomor_meja" => $ord["nomor_meja"],
                        "nama_pelanggan" => $ord["nama_pelanggan"],
                        "total_bayar" => (int)$ord["total_bayar"],
                        "total_bayar_rp" => formatRupiah($ord["total_bayar"]),
                        "metode_bayar" => $ord["metode_bayar"]
                      ])) ?>)'>
                        <i class="bi bi-cash-coin me-1"></i> Bayar Kasir
                      </button>
                    <?php endif; ?>
                  <?php endif; ?>
                  
                  <a href="cetak-struk.php?kode=<?= urlencode($ord['kode_pesanan']) ?>" target="_blank" class="btn btn-outline-secondary btn-sm w-100 py-1" style="font-size:0.75rem;">
                    <i class="bi bi-printer me-1"></i> Cetak Struk (Thermal)
                  </a>
                </div>

              </div>
            </div>
          <?php endforeach; ?>
        <?php endif; ?>
      </div>

    <?php endif; ?>

  </div>

  <!-- Modal Scan Barcode Pesanan (Webcam & Input Barcode Gun) -->
  <div class="modal fade" id="scannerModal" tabindex="-1" aria-labelledby="scannerModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
        <div class="modal-header bg-dark text-white border-0 py-3">
          <div class="d-flex align-items-center gap-2">
            <i class="bi bi-upc-scan fs-4 text-warning"></i>
            <div>
              <h6 class="modal-title fw-bold mb-0" id="scannerModalLabel">Alat Scan Pesanan Bayar di Kasir</h6>
              <small class="text-white-50" style="font-size: 0.72rem;">Scan QR di layar HP pelanggan atau input kode</small>
            </div>
          </div>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close" onclick="stopQrScanner()"></button>
        </div>

        <div class="modal-body p-4">
          <!-- Input Cepat Manual / Barcode Scanner Gun -->
          <div class="mb-3">
            <label class="form-label small fw-bold text-main">
              <i class="bi bi-keyboard text-primary me-1"></i> Scan Gun Barcode / Ketik Kode Pesanan:
            </label>
            <div class="input-group">
              <span class="input-group-text bg-light text-muted">#</span>
              <input type="text" id="manualScanInput" class="form-control" placeholder="Contoh: TYAS-12345" autofocus onkeydown="if(event.key==='Enter') doSearchOrder(this.value)">
              <button class="btn btn-primary-custom" type="button" onclick="doSearchOrder(document.getElementById('manualScanInput').value)">
                <i class="bi bi-search"></i> Cari
              </button>
            </div>
            <small class="text-muted" style="font-size:0.72rem;">*Bisa ditembakkan langsung menggunakan Barcode Scanner USB / nirkabel.</small>
          </div>

          <div class="d-flex align-items-center my-3 text-muted">
            <hr class="flex-grow-1 my-0">
            <span class="px-2 small fw-bold text-uppercase" style="font-size: 0.7rem;">ATAU MENGGUNAKAN KAMERA KASIR</span>
            <hr class="flex-grow-1 my-0">
          </div>

          <!-- Kamera Scanner Viewport -->
          <div class="text-center">
            <div id="reader" style="width: 100%; min-height: 240px; background: #f8faf9; border-radius: 12px; overflow: hidden;" class="border"></div>
            <div class="mt-2 text-muted small" id="scanCameraStatus">
              <i class="bi bi-camera-fill text-success me-1"></i> Arahkan kamera ke Barcode / QR pesanan pelanggan.
            </div>
          </div>
        </div>

        <div class="modal-footer bg-light border-0 py-2">
          <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-dismiss="modal" onclick="stopQrScanner()">Tutup Scanner</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal Detail & Konfirmasi Pesanan Hasil Scan -->
  <div class="modal fade" id="orderResultModal" tabindex="-1" aria-labelledby="orderResultModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
        <div class="modal-header bg-success text-white border-0 py-3">
          <div class="d-flex align-items-center gap-2">
            <i class="bi bi-check-circle-fill fs-4 text-warning"></i>
            <div>
              <h6 class="modal-title fw-bold mb-0" id="orderResultModalLabel">Pesanan Ditemukan!</h6>
              <small class="text-white-50" id="scanModalOrderCode">#TYAS-XXXXX</small>
            </div>
          </div>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <div class="modal-body p-4" id="scanResultBody">
          <!-- Diisi otomatis melalui JS -->
        </div>

        <div class="modal-footer bg-light border-0 py-2 d-flex justify-content-between">
          <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
          <button type="button" class="btn btn-primary-custom px-4 fw-bold shadow-sm" id="btnScanConfirmPayment">
            <i class="bi bi-cash-stack me-1"></i> <strong>OK - Terima & Bayar Lunas</strong>
          </button>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal Konfirmasi Pembayaran Kasir (Cash / E-Wallet dengan Hitung Kembalian) -->
  <div class="modal fade" id="cashierPaymentModal" tabindex="-1" aria-labelledby="cashierPaymentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
        <div class="modal-header bg-dark text-white border-0 py-3">
          <div class="d-flex align-items-center gap-2">
            <div class="p-2 bg-success rounded-circle text-white d-flex align-items-center justify-content-center" style="width:38px;height:38px;">
              <i class="bi bi-cash-coin fs-5"></i>
            </div>
            <div>
              <h6 class="modal-title fw-bold mb-0" id="cashierPaymentModalLabel">Konfirmasi Pembayaran Kasir</h6>
              <small class="text-white-50" id="payModalSubtitle">MEJA -- | #TYAS-XXXX</small>
            </div>
          </div>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <div class="modal-body p-4">
          <input type="hidden" id="payOrderId" value="">
          <input type="hidden" id="payOrderTotal" value="0">

          <!-- Info Singkat Tagihan -->
          <div class="p-3 bg-light rounded-3 border mb-3">
            <div class="d-flex justify-content-between text-muted small mb-1">
              <span>Pelanggan: <strong id="payCustomerName" class="text-main">-</strong></span>
              <span>Meja: <span id="payTableBadge" class="badge bg-dark">01</span></span>
            </div>
            <div class="d-flex justify-content-between align-items-center pt-2 border-top">
              <span class="fw-bold text-main">Total Tagihan:</span>
              <span class="fw-bold fs-4 text-success" id="payTotalText">Rp 0</span>
            </div>
          </div>

          <!-- Pilihan Metode Bayar di Kasir -->
          <label class="form-label small fw-bold text-main mb-2">Pilih Cara Pembayaran:</label>
          <div class="btn-group w-100 mb-3" role="group">
            <input type="radio" class="btn-check" name="paymentTypeRadio" id="payTypeCash" value="kasir" checked onchange="switchPaymentType('cash')">
            <label class="btn btn-outline-success fw-bold" for="payTypeCash">
              <i class="bi bi-cash-stack me-1"></i> Tunai / Cash (Kasir)
            </label>

            <input type="radio" class="btn-check" name="paymentTypeRadio" id="payTypeEwallet" value="ewallet" onchange="switchPaymentType('ewallet')">
            <label class="btn btn-outline-primary fw-bold" for="payTypeEwallet">
              <i class="bi bi-qr-code me-1"></i> E-Wallet / QRIS
            </label>
          </div>

          <!-- Panel Form Tunai (Cash) -->
          <div id="cashPaymentPanel">
            <div class="mb-3">
              <label class="form-label small fw-bold text-main">Uang Tunai Diterima dari Pelanggan:</label>
              <div class="input-group input-group-lg">
                <span class="input-group-text bg-light fw-bold text-muted">Rp</span>
                <input type="number" id="cashInputReceived" class="form-control fw-bold fs-4 text-success" placeholder="0" min="0" step="1000" oninput="calculateChange()">
              </div>
              <div class="d-flex gap-1 flex-wrap mt-2" id="quickCashButtons">
                <!-- Tombol Cepat Uang disuntikkan via JS -->
              </div>
            </div>

            <!-- Kotak Hasil Kembalian -->
            <div class="p-3 rounded-3 border mb-2 text-center" id="changeBox" style="background: #fafbfa;">
              <div class="small text-muted mb-1">Uang Kembalian Pelanggan:</div>
              <div class="fw-bold fs-3 text-secondary" id="changeAmountText">Rp 0</div>
              <div class="small fw-bold text-danger d-none mt-1" id="changeWarningText">
                <i class="bi bi-exclamation-circle-fill"></i> Uang pembayaran yang diterima masih kurang!
              </div>
            </div>
          </div>

          <!-- Panel Form E-Wallet -->
          <div id="ewalletPaymentPanel" class="d-none">
            <div class="alert alert-info py-2 px-3 small rounded-3 mb-2">
              <i class="bi bi-info-circle-fill me-1"></i>
              Pastikan notifikasi/saldo E-Wallet restoran telah bertambah sebelum mengonfirmasi lunas.
            </div>
            <div class="p-3 bg-light rounded-3 border text-center">
              <div class="small text-muted">Total Pembayaran E-Wallet:</div>
              <div class="fw-bold fs-4 text-primary" id="ewalletTotalText">Rp 0</div>
              <span class="badge bg-success-subtle text-success border border-success mt-2">
                <i class="bi bi-check2"></i> Pembayaran Pas (Tanpa Kembalian)
              </span>
            </div>
          </div>

        </div>

        <div class="modal-footer bg-light border-0 py-2 d-flex justify-content-between">
          <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
          <button type="button" class="btn btn-success px-4 fw-bold shadow-sm" id="btnSubmitPayment" onclick="submitCashierPayment()">
            <i class="bi bi-check-circle-fill me-1"></i> <strong>Konfirmasi Lunas & Kirim ke Dapur</strong>
          </button>
        </div>
      </div>
    </div>
  </div>

  <!-- Toast Notifikasi Pesanan Baru -->
  <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 9999;">
    <div id="liveOrderToast" class="toast align-items-center text-bg-warning border-0 shadow-lg" role="alert" aria-live="assertive" aria-atomic="true">
      <div class="d-flex">
        <div class="toast-body fs-6 fw-bold">
          <i class="bi bi-bell-fill text-danger me-2"></i>
          <span id="toastMessage">Pesanan Baru Masuk!</span>
        </div>
        <button type="button" class="btn-close me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

  <script>
    let lastSeenId = <?= (int)$maxOrderId ?>;
    let audioContext = null;

    // Web Audio Sound Generator (Bip Notifikasi Pesanan Masuk tanpa file eksternal)
    function playNotificationSound() {
      try {
        const ctx = new (window.AudioContext || window.webkitAudioContext)();
        const osc = ctx.createOscillator();
        const gain = ctx.createGain();
        osc.connect(gain);
        gain.connect(ctx.destination);

        // Sound Melody Bell (Ding Dong Resto)
        osc.type = 'sine';
        osc.frequency.setValueAtTime(587.33, ctx.currentTime); // D5
        osc.frequency.setValueAtTime(880, ctx.currentTime + 0.15); // A5
        gain.gain.setValueAtTime(0.3, ctx.currentTime);
        gain.gain.exponentialRampToValueAtTime(0.01, ctx.currentTime + 0.6);

        osc.start(ctx.currentTime);
        osc.stop(ctx.currentTime + 0.6);
      } catch (e) {
        console.log('Audio notification error:', e);
      }
    }

    function enableAudioAlert() {
      playNotificationSound();
      document.getElementById('audioStatusText').innerText = 'Suara Berfungsi';
    }

    // Kasir Menekan tombol OK: Konfirmasi Pesanan & Teruskan ke Dapur & Bayar Lunas
    async function confirmOrderOk(id, kode, meja) {
      if (!confirm(`Konfirmasi OK untuk Pesanan ${kode} di MEJA ${meja}?\nPesanan akan otomatis tersimpan sebagai diterima kasir dan diteruskan ke bagian dapur.`)) {
        return;
      }
      await updateStatus(id, 'dimasak_dapur');
    }

    async function updateStatus(id, status) {
      try {
        const res = await fetch('update-pesanan.php', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ id, status_pesanan: status })
        });
        const d = await res.json();
        if (d.status === 'success') {
          location.reload();
        } else {
          alert(d.message);
        }
      } catch (e) {
        alert('Gagal menghubungi server.');
      }
    }

    // ================= SUARA SIRINE DARURAT KASIR (Anti-Keluar Paksa) =================
    let sirenCtx = null;
    let sirenInterval = null;
    let isSirenPlaying = false;

    function playSirenSound() {
      if (isSirenPlaying) return;
      isSirenPlaying = true;
      try {
        if (!sirenCtx) sirenCtx = new (window.AudioContext || window.webkitAudioContext)();
        if (sirenCtx.state === 'suspended') sirenCtx.resume();

        let toggle = false;
        sirenInterval = setInterval(() => {
          if (!isSirenPlaying) return;
          try {
            const osc = sirenCtx.createOscillator();
            const gain = sirenCtx.createGain();
            osc.type = 'sawtooth';
            const freq = toggle ? 940 : 660;
            osc.frequency.setValueAtTime(freq, sirenCtx.currentTime);
            osc.frequency.linearRampToValueAtTime(toggle ? 1160 : 860, sirenCtx.currentTime + 0.22);
            gain.gain.setValueAtTime(0.35, sirenCtx.currentTime);
            gain.gain.exponentialRampToValueAtTime(0.01, sirenCtx.currentTime + 0.32);

            osc.connect(gain);
            gain.connect(sirenCtx.destination);
            osc.start(sirenCtx.currentTime);
            osc.stop(sirenCtx.currentTime + 0.32);
            toggle = !toggle;
          } catch (e) {}
        }, 340);
      } catch (e) {
        console.log('Siren sound error:', e);
      }
    }

    function stopSirenSound() {
      isSirenPlaying = false;
      if (sirenInterval) {
        clearInterval(sirenInterval);
        sirenInterval = null;
      }
    }

    function handleSecurityAlerts(alerts) {
      playSirenSound();
      const area = document.getElementById('securityAlertArea');
      if (!area) return;

      const items = alerts.map(a => `
        <div class="alert alert-danger border-2 border-danger shadow-lg p-3 rounded-4 mb-3 d-flex flex-wrap align-items-center justify-content-between gap-3 animate__animated animate__pulse animate__infinite" style="background: #FFF0F0;">
          <div class="d-flex align-items-center gap-3">
            <div class="p-2 bg-danger text-white rounded-circle shadow-sm flex-shrink-0" style="width: 44px; height: 44px; display: flex; align-items: center; justify-content: center;">
              <i class="bi bi-exclamation-triangle-fill fs-4"></i>
            </div>
            <div>
              <div class="fw-bold fs-6 text-danger"><i class="bi bi-shield-fill-exclamation me-1"></i> PERINGATAN DARURAT: PELANGGAN MENCOBA KELUAR SEBELUM LUNAS!</div>
              <div class="small text-dark mt-1">
                <span class="badge bg-dark fs-6 px-2 py-1 me-1">MEJA ${a.nomor_meja}</span>
                Kode: <strong>#${a.kode_pesanan}</strong> | Pemesan: <strong>${a.nama_pelanggan || 'Pelanggan'}</strong> | Tagihan: <strong class="text-danger fs-6">${a.total_bayar_rp}</strong>
                <span class="badge bg-warning text-dark ms-1">${(a.metode_bayar === 'ewallet') ? 'Pilih E-Wallet (Belum Lunas)' : 'Kasir'}</span>
              </div>
            </div>
          </div>
          <div class="d-flex gap-2">
            <button class="btn btn-success fw-bold px-3 shadow-sm" onclick='openCashierPaymentModal(${JSON.stringify(a)})'>
              <i class="bi bi-cash-stack me-1"></i> <strong>Konfirmasi Bayar Cash / Lunas</strong>
            </button>
            <button class="btn btn-outline-danger fw-bold px-3" onclick="resetSecurityAlert(${a.id})">
              <i class="bi bi-bell-slash me-1"></i> Matikan Suara Alarm
            </button>
          </div>
        </div>
      `).join('');

      area.innerHTML = items;
    }

    function clearSecurityAlertUI() {
      stopSirenSound();
      const area = document.getElementById('securityAlertArea');
      if (area) area.innerHTML = '';
    }

    async function resetSecurityAlert(id) {
      stopSirenSound();
      try {
        await fetch('update-pesanan.php', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ id: id || 0, action: 'reset_peringatan' })
        });
        const area = document.getElementById('securityAlertArea');
        if (area) area.innerHTML = '';
      } catch (e) {}
    }

    // ================= MODAL KONFIRMASI PEMBAYARAN KASIR (Cash / E-Wallet) =================
    let cashierPaymentModalObj = null;

    function formatRupiahJs(num) {
      return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0 }).format(num);
    }

    function openCashierPaymentModal(order) {
      stopSirenSound();
      if (!cashierPaymentModalObj) {
        cashierPaymentModalObj = new bootstrap.Modal(document.getElementById('cashierPaymentModal'));
      }

      document.getElementById('payOrderId').value = order.id;
      const total = parseInt(order.total_bayar) || 0;
      document.getElementById('payOrderTotal').value = total;

      document.getElementById('payModalSubtitle').innerText = `MEJA ${order.nomor_meja} | #${order.kode_pesanan}`;
      document.getElementById('payCustomerName').innerText = order.nama_pelanggan || 'Pelanggan';
      document.getElementById('payTableBadge').innerText = order.nomor_meja;
      document.getElementById('payTotalText').innerText = formatRupiahJs(total);
      document.getElementById('ewalletTotalText').innerText = formatRupiahJs(total);

      // Default: Tunai / Cash (membantu skenario pelanggan pilih e-wallet tapi bayar cash)
      document.getElementById('payTypeCash').checked = true;
      switchPaymentType('cash');

      // Generate Tombol Cepat Uang
      const quickBtns = document.getElementById('quickCashButtons');
      quickBtns.innerHTML = '';

      const roundedTotal = Math.ceil(total / 10000) * 10000;
      const nominalList = [total];
      if (roundedTotal > total && !nominalList.includes(roundedTotal)) nominalList.push(roundedTotal);
      [20000, 50000, 100000].forEach(n => {
        if (n >= total && !nominalList.includes(n)) nominalList.push(n);
      });

      nominalList.forEach((nom) => {
        const btn = document.createElement('button');
        btn.type = 'button';
        btn.className = 'btn btn-sm btn-outline-secondary py-1 px-2';
        btn.innerHTML = (nom === total) ? `<strong>Uang Pas (${formatRupiahJs(nom)})</strong>` : formatRupiahJs(nom);
        btn.onclick = () => {
          document.getElementById('cashInputReceived').value = nom;
          calculateChange();
        };
        quickBtns.appendChild(btn);
      });

      // Default input uang diterima: kosong agar kasir mengisi, atau set default uang pas
      const cashInput = document.getElementById('cashInputReceived');
      cashInput.value = '';
      calculateChange();

      cashierPaymentModalObj.show();
      setTimeout(() => cashInput.focus(), 500);
    }

    function switchPaymentType(type) {
      const cashPanel = document.getElementById('cashPaymentPanel');
      const ewalletPanel = document.getElementById('ewalletPaymentPanel');
      const btnSubmit = document.getElementById('btnSubmitPayment');

      if (type === 'ewallet') {
        cashPanel.classList.add('d-none');
        ewalletPanel.classList.remove('d-none');
        btnSubmit.disabled = false;
        btnSubmit.innerHTML = `<i class="bi bi-check-circle-fill me-1"></i> <strong>Konfirmasi E-Wallet Lunas & Kirim ke Dapur</strong>`;
      } else {
        cashPanel.classList.remove('d-none');
        ewalletPanel.classList.add('d-none');
        btnSubmit.innerHTML = `<i class="bi bi-check-circle-fill me-1"></i> <strong>Konfirmasi Cash Lunas & Kirim ke Dapur</strong>`;
        calculateChange();
      }
    }

    function calculateChange() {
      const total = parseInt(document.getElementById('payOrderTotal').value) || 0;
      const cashInput = document.getElementById('cashInputReceived');
      const received = parseInt(cashInput.value) || 0;
      const changeText = document.getElementById('changeAmountText');
      const warningText = document.getElementById('changeWarningText');
      const btnSubmit = document.getElementById('btnSubmitPayment');

      if (received === 0) {
        changeText.innerText = 'Rp 0';
        changeText.className = 'fw-bold fs-3 text-secondary';
        warningText.classList.add('d-none');
        btnSubmit.disabled = false;
        return;
      }

      const diff = received - total;
      if (diff < 0) {
        changeText.innerText = '- ' + formatRupiahJs(Math.abs(diff));
        changeText.className = 'fw-bold fs-3 text-danger';
        warningText.classList.remove('d-none');
        warningText.innerHTML = `<i class="bi bi-exclamation-circle-fill"></i> Uang pembayaran yang diterima masih kurang ${formatRupiahJs(Math.abs(diff))}!`;
        btnSubmit.disabled = true;
      } else {
        changeText.innerText = formatRupiahJs(diff);
        changeText.className = 'fw-bold fs-3 text-success';
        warningText.classList.add('d-none');
        btnSubmit.disabled = false;
      }
    }

    async function submitCashierPayment() {
      const orderId = parseInt(document.getElementById('payOrderId').value);
      const total = parseInt(document.getElementById('payOrderTotal').value) || 0;
      const isEwallet = document.getElementById('payTypeEwallet').checked;
      const btnSubmit = document.getElementById('btnSubmitPayment');

      let received = total;
      let change = 0;
      let metode = 'kasir';

      if (!isEwallet) {
        received = parseInt(document.getElementById('cashInputReceived').value) || 0;
        if (received < total) {
          alert(`Uang tunai yang diterima masih kurang! Total tagihan adalah ${formatRupiahJs(total)}.`);
          return;
        }
        change = received - total;
        metode = 'kasir'; // Dibayar secara tunai / cash di kasir
      } else {
        received = total;
        change = 0;
        metode = 'ewallet';
      }

      btnSubmit.disabled = true;
      btnSubmit.innerHTML = `<span class="spinner-border spinner-border-sm me-2"></span> Menyimpan Data...`;

      try {
        const res = await fetch('update-pesanan.php', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({
            id: orderId,
            status_pesanan: 'dimasak_dapur',
            status_bayar: 'lunas',
            metode_bayar: metode,
            uang_diterima: received,
            uang_kembali: change
          })
        });
        const d = await res.json();

        if (d.status === 'success') {
          stopSirenSound();
          if (cashierPaymentModalObj) cashierPaymentModalObj.hide();
          alert(`Pembayaran Berhasil Dikonfirmasi Lunas!\nMetode: ${metode === 'kasir' ? 'Tunai / Cash' : 'E-Wallet'}\nTotal: ${formatRupiahJs(total)}\nUang Diterima: ${formatRupiahJs(received)}\nKembalian: ${formatRupiahJs(change)}\n\nPesanan telah diteruskan ke bagian dapur.`);
          location.reload();
        } else {
          alert('Gagal: ' + d.message);
          btnSubmit.disabled = false;
          btnSubmit.innerHTML = `<i class="bi bi-check-circle-fill me-1"></i> <strong>Konfirmasi Lunas & Kirim ke Dapur</strong>`;
        }
      } catch (e) {
        alert('Gagal menghubungi server untuk menyimpan transaksi.');
        btnSubmit.disabled = false;
        btnSubmit.innerHTML = `<i class="bi bi-check-circle-fill me-1"></i> <strong>Konfirmasi Lunas & Kirim ke Dapur</strong>`;
      }
    }

    // Polling Pesanan Baru & Peringatan Keamanan Setiap 4 Detik (Khusus tab kasir)
    <?php if ($currentTab === 'pos'): ?>
    async function checkNewOrders() {
      try {
        const res = await fetch(`cek-pesanan-baru.php?last_id=${lastSeenId}`);
        const data = await res.json();

        if (data.status === 'success') {
          // 1. Cek Peringatan Keamanan (Pelanggan Belum Bayar Mencoba Keluar Paksa)
          if (data.has_security_alert && data.security_alerts.length > 0) {
            handleSecurityAlerts(data.security_alerts);
          } else {
            clearSecurityAlertUI();
          }

          // 2. Cek Pesanan Baru Masuk
          if (data.has_new) {
            playNotificationSound();
            const firstNew = data.orders[0];
            const msg = `Pesanan Baru dari MEJA ${firstNew.nomor_meja} (${data.orders.length} pesanan)!`;
            
            document.getElementById('toastMessage').innerText = msg;
            const toastEl = document.getElementById('liveOrderToast');
            const toast = new bootstrap.Toast(toastEl, { delay: 4000 });
            toast.show();

            // Refresh halaman agar tiket terbaru muncul seketika di kasir
            setTimeout(() => {
              location.reload();
            }, 1200);
          }
          if (data.max_id > lastSeenId) {
            lastSeenId = data.max_id;
          }
        }
      } catch (err) {
        // Silently handle polling disconnect
      }
    }

    setInterval(checkNewOrders, 4000);

    // ================= SCANNER BARCODE PESANAN (KASIR) =================
    let html5QrScanner = null;
    let scannerModalObj = null;
    let orderResultModalObj = null;
    let currentScannedOrder = null;

    function openScannerModal() {
      if (!scannerModalObj) {
        scannerModalObj = new bootstrap.Modal(document.getElementById('scannerModal'));
      }
      scannerModalObj.show();

      const input = document.getElementById('manualScanInput');
      input.value = '';
      setTimeout(() => input.focus(), 500);

      startQrScanner();
    }

    function startQrScanner() {
      if (typeof Html5Qrcode === 'undefined') return;

      if (!html5QrScanner) {
        html5QrScanner = new Html5Qrcode("reader");
      }

      html5QrScanner.start(
        { facingMode: "environment" },
        {
          fps: 10,
          qrbox: { width: 220, height: 220 }
        },
        (decodedText) => {
          // Sukses scan QR
          playNotificationSound();
          stopQrScanner();
          if (scannerModalObj) scannerModalObj.hide();
          doSearchOrder(decodedText);
        },
        (errorMessage) => {
          // Scan frame gagal (abaikan log frame biasa)
        }
      ).catch(err => {
        document.getElementById('scanCameraStatus').innerHTML = `
          <span class="text-danger small"><i class="bi bi-exclamation-circle"></i> Kamera tidak aktif/diizinkan. Anda tetap bisa scan dengan Barcode Gun USB atau ketik kode di atas.</span>
        `;
      });
    }

    function stopQrScanner() {
      if (html5QrScanner) {
        html5QrScanner.stop().then(() => {
          html5QrScanner.clear();
        }).catch(err => {});
      }
    }

    // Cari Pesanan melalui API cari-pesanan.php
    async function doSearchOrder(rawCode) {
      if (!rawCode || !rawCode.trim()) return;
      const cleanCode = rawCode.trim();

      try {
        const res = await fetch(`cari-pesanan.php?kode=${encodeURIComponent(cleanCode)}`);
        const data = await res.json();

        if (data.status === 'success') {
          currentScannedOrder = data.order;
          renderScannedOrder(data.order);
        } else {
          alert('Pesanan tidak ditemukan: ' + data.message);
        }
      } catch (e) {
        alert('Gagal menghubungi server untuk mencari pesanan.');
      }
    }

    function renderScannedOrder(order) {
      document.getElementById('scanModalOrderCode').innerText = `#${order.kode_pesanan} (MEJA ${order.nomor_meja})`;

      let itemsHtml = order.items.map(it => `
        <div class="d-flex justify-content-between align-items-center py-1 border-bottom small">
          <div>
            <strong>${it.jumlah}x</strong> ${it.nama_menu}
            <div class="text-muted" style="font-size:0.7rem;">@ ${it.harga_satuan_rp}</div>
          </div>
          <span class="fw-bold">${it.subtotal_rp}</span>
        </div>
      `).join('');

      let statusBadge = '';
      if (order.status_pesanan === 'menunggu_kasir') {
        statusBadge = '<span class="badge bg-warning text-dark">Menunggu Kasir</span>';
      } else if (order.status_pesanan === 'dimasak_dapur') {
        statusBadge = '<span class="badge bg-primary">Sedang Dimasak</span>';
      } else if (order.status_pesanan === 'siap_saji') {
        statusBadge = '<span class="badge bg-success">Siap Saji</span>';
      } else {
        statusBadge = `<span class="badge bg-secondary">${order.status_pesanan}</span>`;
      }

      const isLunas = order.status_bayar === 'lunas';
      let alreadyPaidAlert = '';
      if (isLunas) {
        alreadyPaidAlert = `
          <div class="alert alert-danger d-flex align-items-center gap-2 py-2 px-3 mb-3 border-0 rounded-3 shadow-xs">
            <i class="bi bi-exclamation-triangle-fill fs-4 text-danger flex-shrink-0"></i>
            <div class="small">
              <strong>PERINGATAN: PESANAN INI SUDAH LUNAS / SELESAI!</strong><br>
              Nomor tiket ini sudah pernah diproses sebelumnya. Jangan menerima pembayaran ganda.
            </div>
          </div>
        `;
      }

      document.getElementById('scanResultBody').innerHTML = `
        ${alreadyPaidAlert}
        <div class="d-flex justify-content-between align-items-center mb-3">
          <div>
            <span class="badge bg-dark fs-6 px-3 py-1">MEJA ${order.nomor_meja}</span>
            <div class="small text-muted mt-1">Pemesan: <strong>${order.nama_pelanggan}</strong></div>
          </div>
          <div class="text-end">
            ${statusBadge}
            <div class="small fw-bold ${isLunas ? 'text-success' : 'text-danger'} mt-1 text-uppercase">${order.status_bayar}</div>
          </div>
        </div>

        ${order.catatan ? `<div class="p-2 mb-3 bg-light rounded-2 small text-muted"><i class="bi bi-info-circle text-primary me-1"></i> Catatan: ${order.catatan}</div>` : ''}

        <label class="form-label small fw-bold text-muted text-uppercase mb-1">Rincian Menu Pelanggan:</label>
        <div class="mb-3" style="max-height: 200px; overflow-y: auto;">
          ${itemsHtml}
        </div>

        <div class="d-flex justify-content-between align-items-center pt-2 border-top">
          <span class="fw-bold text-main">Total Tagihan:</span>
          <span class="fw-bold fs-4 text-success">${order.total_bayar_rp}</span>
        </div>
      `;

      const btnConfirm = document.getElementById('btnScanConfirmPayment');
      if (isLunas) {
        btnConfirm.className = "btn btn-secondary px-4 fw-bold shadow-sm";
        btnConfirm.innerHTML = `<i class="bi bi-check2-all me-1"></i> Sudah Lunas (Tutup)`;
        btnConfirm.onclick = () => {
          if (orderResultModalObj) orderResultModalObj.hide();
        };
      } else {
        btnConfirm.className = "btn btn-success px-4 fw-bold shadow-sm";
        btnConfirm.innerHTML = `<i class="bi bi-cash-coin me-1"></i> <strong>Proses Bayar (Cash / E-Wallet)</strong>`;
        btnConfirm.onclick = () => {
          if (orderResultModalObj) orderResultModalObj.hide();
          openCashierPaymentModal({
            id: order.id,
            kode_pesanan: order.kode_pesanan,
            nomor_meja: order.nomor_meja,
            nama_pelanggan: order.nama_pelanggan,
            total_bayar: order.total_bayar,
            total_bayar_rp: order.total_bayar_rp,
            metode_bayar: order.metode_bayar
          });
        };
      }

      if (!orderResultModalObj) {
        orderResultModalObj = new bootstrap.Modal(document.getElementById('orderResultModal'));
      }
      orderResultModalObj.show();
    }
    <?php endif; ?>
  </script>
</body>
</html>
