<?php
/**
 * Menu Pelanggan Mobile-First Modern Dark Glassmorphism
 * Dapur Tyas by Techno Garage
 */

require_once __DIR__ . '/config.php';

// 1. Otomatisasi Nomor Meja melalui parameter GET (Prioritas Utama URL Barcode Meja)
if (isset($_GET['meja']) && trim($_GET['meja']) !== '') {
    $mejaParam = trim($_GET['meja']);
    if (is_numeric($mejaParam) && strlen($mejaParam) === 1) {
        $mejaParam = str_pad($mejaParam, 2, '0', STR_PAD_LEFT);
    }
    $_SESSION['nomor_meja'] = htmlspecialchars($mejaParam);
}

if (empty($_SESSION['nomor_meja'])) {
    header("Location: pilih-meja.php");
    exit;
}

$mejaAktif = (string)$_SESSION['nomor_meja'];

// 1.1 Kunci Pesanan: Jika ada tagihan aktif yang belum dibayar lunas di meja ini, arahkan ke halaman pembayaran (sukses.php)
try {
    $stmtUnpaid = $pdo->prepare("SELECT kode_pesanan FROM tabel_pesanan WHERE nomor_meja = ? AND status_bayar != 'lunas' AND status_pesanan NOT IN ('selesai', 'dibatalkan') AND waktu_pesan >= NOW() - INTERVAL 4 HOUR ORDER BY id DESC LIMIT 1");
    $stmtUnpaid->execute([$mejaAktif]);
    $unpaidOrder = $stmtUnpaid->fetch();
    if ($unpaidOrder && empty($_GET['force'])) {
        header("Location: sukses.php?kode=" . urlencode($unpaidOrder['kode_pesanan']));
        exit;
    }
} catch (PDOException $e) {}

// 2. Ambil data menu dari tabel_menu MySQL
try {
    $stmt = $pdo->query("SELECT * FROM tabel_menu WHERE tersedia = 1 ORDER BY kategori ASC, id ASC");
    $daftarMenu = $stmt->fetchAll();
} catch (PDOException $e) {
    $daftarMenu = [];
}

// Ambil kategori unik untuk filter
$kategoriList = array_values(array_unique(array_column($daftarMenu, 'kategori')));

// Pisahkan menu trending / chef's pick untuk carousel horizontal organik
$trendingMenu = array_slice(array_filter($daftarMenu, function($m) {
    return !empty($m['tag']) && in_array($m['tag'], ['Best Seller', 'Favorit', 'Chef Pick', 'Pedas']);
}), 0, 8);
if (empty($trendingMenu)) {
    $trendingMenu = array_slice($daftarMenu, 0, 6);
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover">
  <meta name="theme-color" content="#FFFFFF">
  <title>Dapur Tyas by Techno Garage - Meja <?= $mejaAktif ?></title>
  
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

  <style>
    :root {
      --bg-body: #F8FAFC;
      --bg-surface: #FFFFFF;
      --bg-card: #FFFFFF;
      --bg-card-subtle: #F1F5F9;
      --border-subtle: #E2E8F0;
      --border-active: #10B981;
      
      --accent-flame: #F97316;
      --accent-orange: #EA580C;
      --accent-emerald: #10B981;
      --accent-neon: #059669;
      --accent-amber: #D97706;
      
      --text-main: #0F172A;
      --text-soft: #334155;
      --text-muted: #64748B;
      --text-dim: #94A3B8;
      
      --radius-organic: 22px 8px 22px 8px;
      --radius-organic-alt: 8px 22px 8px 22px;
      --radius-pill: 999px;
      --radius-sheet: 28px 28px 0 0;
      
      --shadow-sm: 0 2px 8px rgba(15, 23, 42, 0.04);
      --shadow-md: 0 4px 16px -2px rgba(15, 23, 42, 0.08);
      --shadow-lg: 0 12px 30px -4px rgba(15, 23, 42, 0.12);
      --shadow-flame: 0 8px 20px -4px rgba(234, 88, 12, 0.35);
    }

    * {
      box-sizing: border-box;
      -webkit-tap-highlight-color: transparent;
    }

    html {
      overflow-x: hidden;
      max-width: 100vw;
    }

    body {
      background-color: var(--bg-body);
      color: var(--text-main);
      font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif;
      overflow-x: hidden;
      max-width: 100vw;
      margin: 0;
      padding-bottom: 120px;
      position: relative;
      min-height: 100vh;
    }

    /* Ambient Subtle Clean Glow Background */
    body::before {
      content: '';
      position: fixed;
      top: -15vw;
      left: -15vw;
      width: 70vw;
      height: 70vw;
      background: radial-gradient(circle, rgba(16, 185, 129, 0.08) 0%, transparent 70%);
      filter: blur(60px);
      z-index: -2;
      pointer-events: none;
    }

    body::after {
      content: '';
      position: fixed;
      bottom: 0;
      right: -15vw;
      width: 70vw;
      height: 70vw;
      background: radial-gradient(circle, rgba(249, 115, 22, 0.07) 0%, transparent 70%);
      filter: blur(70px);
      z-index: -2;
      pointer-events: none;
    }

    /* Top Sticky App Pill Bar (Clean White) */
    .top-pill-bar {
      position: sticky;
      top: 0;
      z-index: 100;
      padding: 12px 16px;
      background: rgba(255, 255, 255, 0.92);
      backdrop-filter: blur(20px);
      -webkit-backdrop-filter: blur(20px);
      border-bottom: 1px solid var(--border-subtle);
      box-shadow: 0 2px 10px rgba(0,0,0,0.03);
    }

    .brand-chip {
      background: #FFFFFF;
      border: 1px solid var(--border-subtle);
      border-radius: var(--radius-pill);
      padding: 5px 14px 5px 6px;
      display: inline-flex;
      align-items: center;
      gap: 8px;
      box-shadow: var(--shadow-sm);
    }

    .table-pulse-pill {
      background: linear-gradient(135deg, rgba(16, 185, 129, 0.12), rgba(249, 115, 22, 0.12));
      border: 1.5px solid rgba(16, 185, 129, 0.4);
      border-radius: var(--radius-pill);
      padding: 6px 14px;
      font-size: 0.78rem;
      font-weight: 800;
      letter-spacing: 0.3px;
      display: inline-flex;
      align-items: center;
      gap: 6px;
      color: #065F46;
      box-shadow: 0 2px 8px rgba(16, 185, 129, 0.15);
    }

    .table-pulse-dot {
      width: 7px;
      height: 7px;
      border-radius: 50%;
      background: var(--accent-emerald);
      box-shadow: 0 0 6px var(--accent-emerald);
      animation: pulseDot 2s infinite;
    }

    @keyframes pulseDot {
      0% { transform: scale(0.9); opacity: 0.7; }
      50% { transform: scale(1.3); opacity: 1; }
      100% { transform: scale(0.9); opacity: 0.7; }
    }

    /* Live Search Input Box (Clean White) */
    .search-glass-container {
      position: relative;
      margin: 12px 16px 6px 16px;
    }

    .search-glass-input {
      width: 100%;
      background: #FFFFFF;
      border: 1.5px solid var(--border-subtle);
      border-radius: 20px;
      padding: 12px 42px 12px 42px;
      color: var(--text-main);
      font-size: 0.88rem;
      font-weight: 500;
      box-shadow: var(--shadow-sm);
      transition: all 0.2s ease;
    }

    .search-glass-input:focus {
      outline: none;
      border-color: var(--accent-orange);
      background: #FFFFFF;
      box-shadow: 0 0 0 3px rgba(234, 88, 12, 0.15);
    }

    .search-glass-input::placeholder {
      color: #94A3B8;
    }

    .search-icon-left {
      position: absolute;
      left: 15px;
      top: 50%;
      transform: translateY(-50%);
      color: #64748B;
      font-size: 1rem;
      pointer-events: none;
    }

    .search-clear-btn {
      position: absolute;
      right: 12px;
      top: 50%;
      transform: translateY(-50%);
      background: #F1F5F9;
      border: none;
      width: 24px;
      height: 24px;
      border-radius: 50%;
      color: #64748B;
      display: none;
      align-items: center;
      justify-content: center;
      font-size: 0.75rem;
      cursor: pointer;
    }

    /* Quick Filter Keyword Pills under search */
    .quick-search-tags {
      display: flex;
      gap: 6px;
      overflow-x: auto;
      padding: 0 16px 10px 16px;
      scrollbar-width: none;
      -ms-overflow-style: none;
    }
    .quick-search-tags::-webkit-scrollbar { display: none; }

    .tag-pill-btn {
      background: #FFFFFF;
      border: 1px solid var(--border-subtle);
      color: var(--text-soft);
      font-size: 0.72rem;
      font-weight: 600;
      padding: 5px 12px;
      border-radius: var(--radius-pill);
      white-space: nowrap;
      cursor: pointer;
      box-shadow: var(--shadow-sm);
      transition: all 0.2s ease;
    }
    .tag-pill-btn:hover, .tag-pill-btn.active {
      background: #FFF7ED;
      border-color: #FDBA74;
      color: #C2410C;
    }

    /* Category Bottom Sheet Grid */
    .category-modal-grid {
      display: grid;
      grid-template-columns: repeat(2, 1fr);
      gap: 10px;
      margin-top: 10px;
    }
    .cat-modal-card {
      background: #F8FAFC;
      border: 1px solid var(--border-subtle);
      border-radius: 16px;
      padding: 14px;
      cursor: pointer;
      display: flex;
      align-items: center;
      justify-content: space-between;
      transition: all 0.2s ease;
      color: var(--text-main);
    }
    .cat-modal-card:active, .cat-modal-card.active {
      background: #FFF7ED;
      border-color: var(--accent-orange);
      transform: scale(0.98);
    }

    /* Organic Category Swipe Strip (White Theme) */
    .category-swipe-wrapper {
      display: flex;
      gap: 8px;
      overflow-x: auto;
      padding: 6px 16px 14px 16px;
      scrollbar-width: none;
      -ms-overflow-style: none;
      scroll-snap-type: x mandatory;
    }

    .category-swipe-wrapper::-webkit-scrollbar {
      display: none;
    }

    .cat-chip {
      scroll-snap-align: start;
      white-space: nowrap;
      padding: 8px 16px;
      border-radius: var(--radius-pill);
      background: #FFFFFF;
      border: 1.5px solid var(--border-subtle);
      color: var(--text-soft);
      font-size: 0.8rem;
      font-weight: 700;
      cursor: pointer;
      display: inline-flex;
      align-items: center;
      gap: 6px;
      box-shadow: var(--shadow-sm);
      transition: all 0.2s ease;
      flex-shrink: 0;
    }

    .cat-chip.active {
      background: linear-gradient(135deg, var(--accent-flame), var(--accent-orange));
      color: #fff;
      border-color: transparent;
      box-shadow: var(--shadow-flame);
      transform: scale(1.03);
    }

    .cat-chip:active {
      transform: scale(0.95);
    }

    /* Horizontal Trending Chef's Pick (Organic Squircle Carousel) */
    .section-title {
      font-size: 1.05rem;
      font-weight: 900;
      letter-spacing: -0.3px;
      padding: 0 16px;
      margin-bottom: 10px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      color: var(--text-main);
    }

    .trending-scroll-container {
      display: flex;
      gap: 14px;
      overflow-x: auto;
      padding: 4px 16px 16px 16px;
      scrollbar-width: none;
    }

    .trending-scroll-container::-webkit-scrollbar {
      display: none;
    }

    .trending-card {
      flex: 0 0 220px;
      border-radius: 24px 8px 24px 8px;
      background: #FFFFFF;
      border: 1px solid var(--border-subtle);
      box-shadow: var(--shadow-md);
      overflow: hidden;
      position: relative;
      transition: transform 0.25s ease;
      cursor: pointer;
    }

    .trending-card:active {
      transform: scale(0.98);
    }

    .trending-img-box {
      width: 100%;
      height: 125px;
      position: relative;
      overflow: hidden;
      background: #F1F5F9;
    }

    .trending-img-box img {
      width: 100%;
      height: 100%;
      object-fit: cover;
      transition: transform 0.4s ease;
    }

    .trending-card:active .trending-img-box img {
      transform: scale(1.08);
    }

    .organic-badge {
      position: absolute;
      top: 10px;
      left: 10px;
      background: rgba(15, 23, 42, 0.78);
      backdrop-filter: blur(8px);
      border-radius: 8px 18px 8px 18px;
      padding: 3px 9px;
      font-size: 0.68rem;
      font-weight: 800;
      color: #FFD54F;
      display: inline-flex;
      align-items: center;
      gap: 4px;
    }

    /* Asymmetric Organic Menu Grid (2 Columns Mobile First - White Clean Theme) */
    .menu-grid-mobile {
      display: grid;
      grid-template-columns: repeat(2, 1fr);
      gap: 12px;
      padding: 4px 16px 20px 16px;
    }

    @media (min-width: 768px) {
      .menu-grid-mobile {
        grid-template-columns: repeat(3, 1fr);
        gap: 16px;
        max-width: 960px;
        margin: 0 auto;
      }
    }

    @media (min-width: 992px) {
      .menu-grid-mobile {
        grid-template-columns: repeat(4, 1fr);
      }
    }

    .menu-card {
      background: #FFFFFF;
      border: 1px solid var(--border-subtle);
      border-radius: var(--radius-organic);
      box-shadow: var(--shadow-sm);
      overflow: hidden;
      display: flex;
      flex-direction: column;
      justify-content: space-between;
      position: relative;
      transition: all 0.2s ease;
      cursor: pointer;
      min-width: 0;
      box-sizing: border-box;
    }

    .menu-card:nth-child(even) {
      border-radius: var(--radius-organic-alt);
    }

    .menu-card:hover {
      box-shadow: var(--shadow-md);
      border-color: #CBD5E1;
    }

    .menu-card:active {
      transform: scale(0.98);
      border-color: var(--accent-orange);
    }

    .menu-img-wrap {
      width: 100%;
      height: 120px;
      position: relative;
      overflow: hidden;
      background: #F1F5F9;
      flex-shrink: 0;
    }

    .menu-img-wrap img {
      width: 100%;
      height: 100%;
      object-fit: cover;
      transition: transform 0.4s ease;
    }

    .menu-card:active .menu-img-wrap img {
      transform: scale(1.08);
    }

    .menu-tag-pill {
      position: absolute;
      top: 6px;
      left: 6px;
      background: rgba(255, 255, 255, 0.95);
      backdrop-filter: blur(8px);
      border-radius: var(--radius-pill);
      padding: 2px 7px;
      font-size: 0.62rem;
      font-weight: 800;
      color: var(--accent-orange);
      border: 1px solid rgba(234, 88, 12, 0.25);
      box-shadow: 0 2px 6px rgba(0,0,0,0.06);
      max-width: 85%;
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
    }

    .menu-card-body {
      padding: 9px 9px 11px 9px;
      display: flex;
      flex-direction: column;
      flex-grow: 1;
      justify-content: space-between;
      min-width: 0;
      box-sizing: border-box;
    }

    .menu-title {
      font-size: 0.82rem;
      font-weight: 800;
      line-height: 1.25;
      color: var(--text-main);
      display: -webkit-box;
      -webkit-line-clamp: 2;
      -webkit-box-orient: vertical;
      overflow: hidden;
      word-break: break-word;
      margin-bottom: 3px;
    }

    .menu-desc {
      font-size: 0.68rem;
      color: var(--text-muted);
      display: -webkit-box;
      -webkit-line-clamp: 2;
      -webkit-box-orient: vertical;
      overflow: hidden;
      line-height: 1.25;
      margin-bottom: 6px;
    }

    .menu-price-row {
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 4px;
      margin-top: auto;
      padding-top: 4px;
      min-width: 0;
      width: 100%;
      box-sizing: border-box;
    }

    .price-text {
      font-size: 0.82rem;
      font-weight: 900;
      color: #059669;
      letter-spacing: -0.2px;
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
      flex-shrink: 1;
      min-width: 0;
    }

    /* Spring Add Button */
    .btn-add-spring {
      width: 28px;
      height: 28px;
      min-width: 28px;
      border-radius: 50%;
      background: linear-gradient(135deg, var(--accent-flame), var(--accent-orange));
      color: #fff;
      border: none;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 0.95rem;
      cursor: pointer;
      box-shadow: 0 3px 10px rgba(234, 88, 12, 0.35);
      transition: transform 0.2s cubic-bezier(0.34, 1.56, 0.64, 1);
      flex-shrink: 0;
    }

    .btn-add-spring:active {
      transform: scale(0.85);
    }

    /* In-Card Quantity Pill (Clean Green & White) */
    .card-qty-ctrl {
      display: none;
      align-items: center;
      gap: 3px;
      background: #ECFDF5;
      border: 1px solid #A7F3D0;
      border-radius: var(--radius-pill);
      padding: 2px 4px;
      flex-shrink: 0;
      box-sizing: border-box;
    }

    .card-qty-ctrl.show {
      display: inline-flex;
    }

    .card-qty-btn {
      width: 20px;
      height: 20px;
      min-width: 20px;
      border-radius: 50%;
      background: var(--accent-emerald);
      color: #fff;
      border: none;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 0.75rem;
      font-weight: 800;
      padding: 0;
      line-height: 1;
      cursor: pointer;
      transition: transform 0.15s ease;
    }

    .card-qty-btn:active {
      transform: scale(0.85);
    }

    .card-qty-num {
      font-size: 0.75rem;
      font-weight: 800;
      color: #065F46;
      min-width: 14px;
      text-align: center;
      line-height: 1;
    }

    /* ================= FLOATING DOCK NAVIGATION (WHITE MODERN STYLE) ================= */
    .floating-dock-nav {
      position: fixed;
      bottom: 16px;
      left: 16px;
      right: 16px;
      max-width: 480px;
      margin: 0 auto;
      z-index: 1050;
      background: rgba(255, 255, 255, 0.94);
      backdrop-filter: blur(28px);
      -webkit-backdrop-filter: blur(28px);
      border: 1.5px solid rgba(226, 232, 240, 0.9);
      border-radius: 28px;
      padding: 8px 12px;
      box-shadow: 0 16px 40px rgba(15, 23, 42, 0.15);
      display: flex;
      align-items: center;
      justify-content: space-between;
    }

    .dock-btn {
      background: transparent;
      border: none;
      color: #64748B;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      font-size: 0.65rem;
      font-weight: 700;
      gap: 2px;
      padding: 6px 8px;
      border-radius: 16px;
      cursor: pointer;
      transition: all 0.2s ease;
      text-decoration: none;
      flex-shrink: 0;
    }

    .dock-btn i {
      font-size: 1.15rem;
      line-height: 1;
    }

    .dock-btn.active, .dock-btn:hover {
      color: #065F46;
      background: #ECFDF5;
    }

    /* Floating Capsule Cart Action in Dock */
    .dock-cart-pill {
      background: linear-gradient(135deg, var(--accent-flame), var(--accent-orange));
      color: #fff;
      border-radius: var(--radius-pill);
      padding: 7px 14px;
      display: flex;
      align-items: center;
      gap: 6px;
      font-weight: 800;
      font-size: 0.78rem;
      border: none;
      box-shadow: var(--shadow-flame);
      cursor: pointer;
      transition: transform 0.2s cubic-bezier(0.34, 1.56, 0.64, 1);
      white-space: nowrap;
      flex-shrink: 0;
    }

    .dock-cart-pill:active {
      transform: scale(0.94);
    }

    .cart-badge-counter {
      background: #FFFFFF;
      color: #C2410C;
      font-size: 0.7rem;
      font-weight: 900;
      padding: 1px 6px;
      border-radius: var(--radius-pill);
      box-shadow: 0 2px 6px rgba(0,0,0,0.15);
    }

    /* ================= BOTTOM SHEET MODAL (CLEAN WHITE) ================= */
    .glass-bottom-sheet .modal-dialog {
      margin: 0;
      position: fixed;
      bottom: 0;
      left: 0;
      right: 0;
      max-width: 540px;
      margin-left: auto;
      margin-right: auto;
      width: 100%;
      pointer-events: none;
    }

    .glass-bottom-sheet .modal-content {
      pointer-events: auto;
      background: #FFFFFF;
      border: 1px solid var(--border-subtle);
      border-bottom: none;
      border-radius: 28px 28px 0 0;
      color: var(--text-main);
      padding: 10px 16px 20px 16px;
      max-height: 85vh;
      display: flex;
      flex-direction: column;
      box-shadow: 0 -10px 40px rgba(15, 23, 42, 0.15);
    }

    .sheet-handle {
      width: 44px;
      height: 5px;
      background: #CBD5E1;
      border-radius: var(--radius-pill);
      margin: 4px auto 12px auto;
      flex-shrink: 0;
    }

    .sheet-header-box {
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding-bottom: 10px;
      border-bottom: 1px solid var(--border-subtle);
      margin-bottom: 12px;
      flex-shrink: 0;
    }

    .sheet-body-scroll {
      overflow-y: auto;
      -webkit-overflow-scrolling: touch;
      flex-grow: 1;
      padding-right: 4px;
      margin-bottom: 10px;
    }

    .sheet-footer-fixed {
      flex-shrink: 0;
      padding-top: 10px;
      border-top: 1px solid var(--border-subtle);
    }

    /* Clear, Bold Close Button (Tanda Silang Mantap) */
    .btn-sheet-close {
      width: 34px;
      height: 34px;
      border-radius: 50%;
      background: #F1F5F9;
      border: 1px solid var(--border-subtle);
      color: #64748B;
      display: flex;
      align-items: center;
      justify-content: center;
      cursor: pointer;
      font-size: 1rem;
      transition: all 0.2s ease;
      flex-shrink: 0;
    }
    .btn-sheet-close:hover, .btn-sheet-close:active {
      background: #FEE2E2;
      border-color: #FCA5A5;
      color: #DC2626;
      transform: scale(0.92);
    }

    /* Cart item delete badge */
    .btn-item-delete {
      width: 26px;
      height: 26px;
      border-radius: 50%;
      background: #FEE2E2;
      border: 1px solid #FECACA;
      color: #DC2626;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 0.8rem;
      cursor: pointer;
      transition: all 0.2s ease;
    }
    .btn-item-delete:active {
      background: #DC2626;
      color: #fff;
      transform: scale(0.9);
    }

    .payment-select-card {
      background: #F8FAFC;
      border: 1.5px solid var(--border-subtle);
      border-radius: 18px;
      padding: 12px;
      cursor: pointer;
      transition: all 0.2s ease;
      display: flex;
      align-items: center;
      gap: 12px;
      color: var(--text-main);
    }

    .payment-select-card.selected {
      border-color: var(--accent-emerald);
      background: #ECFDF5;
      box-shadow: 0 2px 10px rgba(16, 185, 129, 0.12);
    }

    /* Shimmer submit button */
    .btn-submit-glow {
      background: linear-gradient(135deg, var(--accent-flame) 0%, var(--accent-orange) 100%);
      color: #fff;
      font-weight: 800;
      border: none;
      border-radius: 20px;
      padding: 14px 20px;
      width: 100%;
      font-size: 1rem;
      letter-spacing: 0.3px;
      box-shadow: var(--shadow-flame);
      position: relative;
      overflow: hidden;
      transition: transform 0.2s ease;
    }

    .btn-submit-glow:active {
      transform: scale(0.97);
    }

    .btn-submit-glow:disabled {
      opacity: 0.45;
      cursor: not-allowed;
      pointer-events: none;
      box-shadow: none;
      background: #E2E8F0 !important;
      color: #94A3B8 !important;
    }

    .btn-submit-glow:disabled::after {
      display: none;
    }

    .btn-submit-glow::after {
      content: '';
      position: absolute;
      top: -50%;
      left: -60%;
      width: 40%;
      height: 200%;
      background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.35), transparent);
      transform: rotate(25deg);
      animation: shimmerBtn 3.5s infinite;
    }

    @keyframes shimmerBtn {
      0% { left: -60%; }
      50% { left: 140%; }
      100% { left: 140%; }
    }
  </style>
</head>
<body>

  <!-- Top Pill Sticky App Bar -->
  <header class="top-pill-bar">
    <div class="d-flex align-items-center justify-content-between">
      
      <!-- Brand Pill -->
      <div class="brand-chip">
        <div class="position-relative" style="width: 32px; height: 32px;">
          <img src="tyas.png" alt="Logo" class="rounded-circle" style="width: 100%; height: 100%; object-fit: cover; border: 1.5px solid var(--accent-emerald);">
          <img src="techno garage.jpg" alt="Partner" class="rounded-circle position-absolute bottom-0 end-0 border border-white" style="width: 13px; height: 13px; object-fit: cover; transform: translate(15%, 15%);">
        </div>
        <div>
          <div class="fw-bold text-dark lh-1" style="font-size: 0.82rem; letter-spacing: -0.2px;">DAPUR TYAS</div>
          <div class="text-muted" style="font-size: 0.6rem;">Techno Garage</div>
        </div>
      </div>

      <!-- Live Table Pill (Klik untuk ganti nomor meja) -->
      <a href="pilih-meja.php" class="table-pulse-pill text-decoration-none" title="Klik untuk ganti nomor meja">
        <span class="table-pulse-dot"></span>
        <span>MEJA <?= htmlspecialchars($mejaAktif) ?></span>
        <i class="bi bi-chevron-down small opacity-75 ms-1" style="font-size: 0.7rem;"></i>
      </a>

    </div>
  </header>

  <!-- Live Instant Search Bar -->
  <div class="search-glass-container">
    <i class="bi bi-search search-icon-left"></i>
    <input type="text" id="searchInput" class="search-glass-input" placeholder="Cari 260+ menu lezat Dapur Tyas..." autocomplete="off">
    <button type="button" id="searchClearBtn" class="search-clear-btn" onclick="clearSearch()">
      <i class="bi bi-x-lg"></i>
    </button>
  </div>

  <!-- Quick Search Tag Chips -->
  <div class="quick-search-tags">
    <button type="button" class="tag-pill-btn" onclick="quickSearch('Ayam')">🍗 Ayam</button>
    <button type="button" class="tag-pill-btn" onclick="quickSearch('Bebek')">🦆 Bebek</button>
    <button type="button" class="tag-pill-btn" onclick="quickSearch('Seafood')">🦐 Seafood</button>
    <button type="button" class="tag-pill-btn" onclick="quickSearch('Mie')">🍜 Mie</button>
    <button type="button" class="tag-pill-btn" onclick="quickSearch('Nasi')">🍚 Nasi</button>
    <button type="button" class="tag-pill-btn" onclick="quickSearch('Pedas')">🌶️ Pedas</button>
    <button type="button" class="tag-pill-btn" onclick="quickSearch('Goreng')">🍳 Goreng</button>
    <button type="button" class="tag-pill-btn" onclick="quickSearch('Bakar')">🔥 Bakar</button>
    <button type="button" class="tag-pill-btn" onclick="quickSearch('Kopi')">☕ Kopi</button>
    <button type="button" class="tag-pill-btn" onclick="quickSearch('Es')">🧊 Es Segar</button>
  </div>

  <!-- Organic Swipeable Category Strip -->
  <div class="category-swipe-wrapper" id="categoryScroll">
    <button type="button" class="cat-chip active" data-cat="all" onclick="filterCategory('all', this)">
      <i class="bi bi-grid-fill"></i> Semua (<?= count($daftarMenu) ?>)
    </button>
    <?php foreach ($kategoriList as $kat): ?>
      <?php 
        $countThis = count(array_filter($daftarMenu, fn($m) => $m['kategori'] === $kat));
      ?>
      <button type="button" class="cat-chip" data-cat="<?= htmlspecialchars($kat) ?>" onclick="filterCategory('<?= htmlspecialchars(addslashes($kat)) ?>', this)">
        <?= htmlspecialchars($kat) ?> <span style="opacity: 0.65; font-size: 0.72rem;">(<?= $countThis ?>)</span>
      </button>
    <?php endforeach; ?>
  </div>

  <!-- Trending / Chef's Recommendation Organic Carousel -->
  <div id="trendingSection" class="mt-2">
    <div class="section-title">
      <span class="text-dark"><i class="bi bi-fire text-danger me-1"></i> Rekomendasi Koki</span>
      <span class="text-muted" style="font-size: 0.75rem;">Geser &rarr;</span>
    </div>

    <div class="trending-scroll-container">
      <?php foreach ($trendingMenu as $trend): ?>
        <div class="trending-card" onclick="openMenuDetail(<?= htmlspecialchars(json_encode($trend)) ?>)">
          <div class="trending-img-box">
            <img src="<?= htmlspecialchars($trend['foto']) ?>" alt="" loading="lazy" onerror="this.src='tyas.png'">
            <div class="organic-badge">
              <i class="bi bi-stars"></i> <?= htmlspecialchars($trend['tag'] ?: 'Pilihan Koki') ?>
            </div>
          </div>
          <div class="p-2">
            <div class="fw-bold text-truncate text-dark" style="font-size: 0.85rem;"><?= htmlspecialchars($trend['nama_menu']) ?></div>
            <div class="d-flex justify-content-between align-items-center mt-1">
              <span class="fw-bold text-success" style="font-size: 0.9rem;"><?= formatRupiah($trend['harga']) ?></span>
              <button class="btn-add-spring" style="width: 28px; height: 28px; font-size: 0.9rem;" onclick="event.stopPropagation(); addToCartDirect(<?= htmlspecialchars(json_encode($trend)) ?>)">
                <i class="bi bi-plus-lg"></i>
              </button>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>

  <!-- Main Menu Grid (2-Column Mobile First) -->
  <div class="section-title mt-3">
    <span class="text-dark" id="gridTitleText">
      <i class="bi bi-collection-fill text-warning me-1"></i> Katalog Hidangan
    </span>
    <span class="text-muted" id="menuCountText" style="font-size: 0.75rem;"><?= count($daftarMenu) ?> menu</span>
  </div>

  <div class="menu-grid-mobile" id="menuContainer">
    <?php foreach ($daftarMenu as $item): ?>
      <div class="menu-card" data-id="<?= $item['id'] ?>" data-name="<?= strtolower(htmlspecialchars($item['nama_menu'])) ?>" data-cat="<?= htmlspecialchars($item['kategori']) ?>" onclick="openMenuDetail(<?= htmlspecialchars(json_encode($item)) ?>)">
        <div class="menu-img-wrap">
          <img src="<?= htmlspecialchars($item['foto']) ?>" alt="" loading="lazy" onerror="this.src='tyas.png'">
          <?php if (!empty($item['tag'])): ?>
            <div class="menu-tag-pill"><?= htmlspecialchars($item['tag']) ?></div>
          <?php endif; ?>
        </div>

        <div class="menu-card-body">
          <div>
            <div class="menu-title"><?= htmlspecialchars($item['nama_menu']) ?></div>
            <div class="menu-desc"><?= htmlspecialchars($item['deskripsi']) ?></div>
          </div>

          <div class="menu-price-row">
            <span class="price-text"><?= formatRupiah($item['harga']) ?></span>
            
            <!-- Quick Add Button -->
            <button type="button" class="btn-add-spring" id="addBtn-<?= $item['id'] ?>" onclick="event.stopPropagation(); addToCartDirect(<?= htmlspecialchars(json_encode($item)) ?>)">
              <i class="bi bi-plus-lg"></i>
            </button>

            <!-- Quantity Control (Muncul jika ada di keranjang) -->
            <div class="card-qty-ctrl" id="qtyCtrl-<?= $item['id'] ?>" onclick="event.stopPropagation();">
              <button type="button" class="card-qty-btn" onclick="updateQtyDirect(<?= $item['id'] ?>, -1)">-</button>
              <span class="card-qty-num" id="qtyVal-<?= $item['id'] ?>">1</span>
              <button type="button" class="card-qty-btn" onclick="updateQtyDirect(<?= $item['id'] ?>, 1)">+</button>
            </div>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
  </div>

  <!-- Empty State jika pencarian tidak ditemukan -->
  <div id="emptySearchNotice" class="text-center py-5 d-none">
    <div class="p-3 rounded-circle d-inline-block mx-auto mb-2" style="background: rgba(249, 115, 22, 0.1);">
      <i class="bi bi-search text-warning fs-1"></i>
    </div>
    <h6 class="fw-bold text-dark">Menu tidak ditemukan</h6>
    <p class="text-muted small mb-3">Coba gunakan kata kunci lain atau pilih kategori di atas.</p>
    <button class="btn btn-sm btn-outline-secondary px-3 rounded-pill" onclick="clearSearch()">Lihat Semua Menu</button>
  </div>

  <!-- ================= FLOATING DOCK NAVIGATION ================= -->
  <nav class="floating-dock-nav">
    <button type="button" class="dock-btn active" id="dockBtnKatalog" onclick="goToKatalog()">
      <i class="bi bi-grid-1x2-fill"></i>
      <span>Katalog</span>
    </button>

    <button type="button" class="dock-btn" id="dockBtnKategori" onclick="openCategorySheet()">
      <i class="bi bi-compass-fill"></i>
      <span>Kategori</span>
    </button>

    <button type="button" class="dock-btn" onclick="focusSearch()">
      <i class="bi bi-search"></i>
      <span>Cari</span>
    </button>

    <!-- Dynamic Cart Capsule -->
    <button type="button" class="dock-cart-pill" id="dockCartButton" onclick="openCartModal()">
      <i class="bi bi-bag-check-fill"></i>
      <span id="dockCartCountBadge" class="cart-badge-counter">0</span>
      <span id="dockCartTotalText">Rp 0</span>
    </button>
  </nav>

  <!-- ================= MODAL PILIH KATEGORI (BOTTOM SHEET) ================= -->
  <div class="modal fade glass-bottom-sheet" id="categorySheetModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="sheet-handle"></div>
        <div class="sheet-header-box">
          <div>
            <h6 class="fw-bold text-dark mb-0" style="font-size: 0.95rem;">
              <i class="bi bi-compass-fill text-warning me-1"></i> Pilih Kategori Menu
            </h6>
            <span class="text-muted" style="font-size: 0.72rem;">Filter cepat hidangan Dapur Tyas</span>
          </div>
          <button type="button" class="btn-sheet-close" data-bs-dismiss="modal" aria-label="Tutup">
            <i class="bi bi-x-lg"></i>
          </button>
        </div>

        <div class="sheet-body-scroll">
          <div class="category-modal-grid">
            <div class="cat-modal-card active" data-cat="all" onclick="selectCategoryFromSheet('all')">
              <div>
                <div class="fw-bold" style="font-size: 0.88rem;">✨ Semua Menu</div>
                <div class="text-dim" style="font-size: 0.72rem;"><?= count($daftarMenu) ?> pilihan</div>
              </div>
              <i class="bi bi-chevron-right text-dim"></i>
            </div>

            <?php foreach ($kategoriList as $kat): ?>
              <?php 
                $countThis = count(array_filter($daftarMenu, fn($m) => $m['kategori'] === $kat));
                $iconKat = 'bi-tag-fill';
                if (stripos($kat, 'makanan') !== false) $iconKat = 'bi-egg-fried';
                elseif (stripos($kat, 'minuman') !== false) $iconKat = 'bi-cup-straw';
                elseif (stripos($kat, 'snack') !== false || stripos($kat, 'cemilan') !== false) $iconKat = 'bi-cake2-fill';
                elseif (stripos($kat, 'kopi') !== false) $iconKat = 'bi-cup-hot-fill';
                elseif (stripos($kat, 'mie') !== false) $iconKat = 'bi-fire';
              ?>
              <div class="cat-modal-card" data-cat="<?= htmlspecialchars($kat) ?>" onclick="selectCategoryFromSheet('<?= htmlspecialchars(addslashes($kat)) ?>')">
                <div>
                  <div class="fw-bold" style="font-size: 0.88rem;"><i class="bi <?= $iconKat ?> text-warning me-1"></i> <?= htmlspecialchars($kat) ?></div>
                  <div class="text-dim" style="font-size: 0.72rem;"><?= $countThis ?> menu</div>
                </div>
                <i class="bi bi-chevron-right text-dim"></i>
              </div>
            <?php endforeach; ?>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- ================= MODAL DETAIL MENU (BOTTOM SHEET) ================= -->
  <div class="modal fade glass-bottom-sheet" id="menuDetailModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="sheet-handle"></div>

        <div class="sheet-body-scroll">
          <div class="position-relative rounded-4 overflow-hidden mb-3" style="height: 180px; background: #F1F5F9;">
            <img id="detailImg" src="tyas.png" alt="" style="width: 100%; height: 100%; object-fit: cover;">
            <span id="detailTag" class="organic-badge" style="top: 12px; left: 12px;">Spesial</span>
            <button type="button" class="btn-sheet-close position-absolute top-0 end-0 m-2" data-bs-dismiss="modal" aria-label="Tutup">
              <i class="bi bi-x-lg"></i>
            </button>
          </div>

          <div class="d-flex justify-content-between align-items-start mb-2">
            <div>
              <h5 class="fw-bold text-dark mb-1" id="detailTitle">Nama Menu</h5>
              <span class="badge bg-light text-dark border" id="detailCat">Kategori</span>
            </div>
            <span class="fw-bold fs-4 text-success" id="detailPrice">Rp 0</span>
          </div>

          <p class="text-muted small mb-3" id="detailDesc">Deskripsi hidangan...</p>

          <!-- Catatan Tambahan (Opsional) -->
          <div class="mb-3">
            <label class="form-label small fw-bold text-dark mb-1"><i class="bi bi-pencil-square me-1"></i> Catatan Khusus untuk Koki (Opsional):</label>
            <input type="text" id="detailCustomNote" class="form-control bg-light text-dark border-secondary-subtle small" placeholder="Contoh: Tidak pedas / sedikit es / sambal dipisah">
          </div>
        </div>

        <!-- Quantity & Add to Cart Fixed Action -->
        <div class="sheet-footer-fixed">
          <div class="d-flex align-items-center justify-content-between gap-3">
            <div class="d-flex align-items-center gap-2 bg-light p-1 rounded-pill border">
              <button type="button" class="btn btn-sm btn-outline-secondary rounded-circle" style="width: 32px; height: 32px;" onclick="adjustDetailQty(-1)">-</button>
              <span class="fw-bold px-2 text-dark" id="detailQty">1</span>
              <button type="button" class="btn btn-sm btn-outline-secondary rounded-circle" style="width: 32px; height: 32px;" onclick="adjustDetailQty(1)">+</button>
            </div>

            <button type="button" class="btn-submit-glow py-2 flex-grow-1" id="detailAddBtn" onclick="confirmAddFromDetail()">
              <i class="bi bi-bag-plus me-1"></i> Masukkan Pesanan
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- ================= MODAL KERANJANG & CHECKOUT (BOTTOM SHEET) ================= -->
  <div class="modal fade glass-bottom-sheet" id="cartModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="sheet-handle"></div>

        <!-- Header Modal dengan Tanda Silang Mantap & Jelas -->
        <div class="sheet-header-box">
          <div>
            <h6 class="fw-bold text-dark mb-0" style="font-size: 0.95rem;">
              <i class="bi bi-receipt-cutoff text-warning me-1"></i> Pesanan Meja <?= htmlspecialchars($mejaAktif) ?>
            </h6>
            <span class="text-muted" style="font-size: 0.72rem;">Periksa daftar pesanan Anda</span>
          </div>
          <button type="button" class="btn-sheet-close" data-bs-dismiss="modal" aria-label="Tutup">
            <i class="bi bi-x-lg"></i>
          </button>
        </div>

        <!-- Area Scrollable (Daftar Menu & Opsi Bayar Tidak Akan Tembus/Keluar-Keluar) -->
        <div class="sheet-body-scroll">
          <!-- Cart Item List Container -->
          <div id="cartItemsList" class="mb-3">
            <!-- Diisi otomatis oleh Javascript -->
          </div>

          <!-- Bagian Rincian Pembayaran & Input (Hanya tampil jika ada menu) -->
          <div id="cartCheckoutDetails" style="display: none;">
            <!-- Input Nama Pelanggan -->
            <div class="mb-3">
              <label class="form-label small fw-bold text-dark mb-1"><i class="bi bi-person-fill text-warning me-1"></i> Nama Pemesan:</label>
              <input type="text" id="custNameInput" class="form-control bg-light text-dark border-secondary-subtle small" placeholder="Masukkan nama Anda (misal: Budi / Tyas)" value="">
            </div>

            <!-- Pilihan Tipe Pesanan: Makan di Tempat / Bawa Pulang -->
            <label class="form-label small fw-bold text-dark mb-1"><i class="bi bi-geo-alt-fill text-warning me-1"></i> Tipe Pesanan:</label>
            <div class="row g-2 mb-3" id="orderTypeSelector">
              <div class="col-6">
                <div class="payment-select-card selected text-center justify-content-center py-2 px-2" id="optTypeDineIn" onclick="selectOrderType('dine_in')">
                  <div class="d-flex flex-column align-items-center gap-1">
                    <i class="bi bi-shop fs-4 text-warning"></i>
                    <span class="fw-bold text-dark small">Makan di Tempat</span>
                    <span class="text-muted" style="font-size: 0.65rem;">(Dine In Meja <?= htmlspecialchars($mejaAktif) ?>)</span>
                  </div>
                </div>
              </div>
              <div class="col-6">
                <div class="payment-select-card text-center justify-content-center py-2 px-2" id="optTypeTakeAway" onclick="selectOrderType('take_away')">
                  <div class="d-flex flex-column align-items-center gap-1">
                    <i class="bi bi-bag-check fs-4 text-info"></i>
                    <span class="fw-bold text-dark small">Bawa Pulang</span>
                    <span class="text-muted" style="font-size: 0.65rem;">(Take Away / Bungkus)</span>
                  </div>
                </div>
              </div>
            </div>

            <!-- Pilihan Cara Pembayaran -->
            <label class="form-label small fw-bold text-dark mb-2"><i class="bi bi-wallet2 text-success me-1"></i> Pilihan Pembayaran:</label>
            <div class="d-flex flex-column gap-2 mb-3">
              
              <!-- Bayar Langsung di Kasir -->
              <div class="payment-select-card selected" id="optPayKasir" onclick="selectPayOption('kasir')">
                <div class="p-2 bg-warning-subtle text-warning rounded-circle">
                  <i class="bi bi-shop fs-5"></i>
                </div>
                <div class="flex-grow-1">
                  <div class="fw-bold text-dark small">Bayar Langsung di Kasir (Tunai / Cash)</div>
                  <div class="text-muted" style="font-size: 0.7rem;">Pesanan langsung diproses & diantar ke meja tanpa tunggu bayar.</div>
                </div>
                <i class="bi bi-check-circle-fill text-success fs-5 check-icon"></i>
              </div>

              <!-- Bayar E-Wallet / QRIS -->
              <div class="payment-select-card" id="optPayEwallet" onclick="selectPayOption('ewallet')">
                <div class="p-2 bg-success-subtle text-success rounded-circle">
                  <i class="bi bi-qr-code fs-5"></i>
                </div>
                <div class="flex-grow-1">
                  <div class="fw-bold text-dark small">E-Wallet / QRIS (GoPay, OVO, DANA, BCA)</div>
                  <div class="text-muted" style="font-size: 0.7rem;">Scan kode QR setelah pesanan dikirim.</div>
                </div>
                <i class="bi bi-circle text-muted fs-5 check-icon"></i>
              </div>

            </div>

            <!-- Pertanyaan: Apakah Anda Ingin Sekalian Struknya? -->
            <label class="form-label small fw-bold text-dark mb-1">
              <i class="bi bi-receipt text-warning me-1"></i> Apakah Anda ingin sekalian struknya?
            </label>
            <div class="row g-2 mb-3" id="receiptOptionSelector">
              <div class="col-6">
                <div class="payment-select-card text-center justify-content-center py-2 px-2" id="optStrukYa" onclick="selectReceiptOption(1)">
                  <div class="d-flex flex-column align-items-center gap-1">
                    <i class="bi bi-printer-fill fs-5 text-success"></i>
                    <span class="fw-bold text-dark small">Ya, Minta Struk</span>
                    <span class="text-muted" style="font-size: 0.65rem;">(Dicetak fisik oleh kasir)</span>
                  </div>
                </div>
              </div>
              <div class="col-6">
                <div class="payment-select-card selected text-center justify-content-center py-2 px-2" id="optStrukTidak" onclick="selectReceiptOption(0)">
                  <div class="d-flex flex-column align-items-center gap-1">
                    <i class="bi bi-phone fs-5 text-primary"></i>
                    <span class="fw-bold text-dark small">Tidak Perlu</span>
                    <span class="text-muted" style="font-size: 0.65rem;">(Paperless / e-struk di HP)</span>
                  </div>
                </div>
              </div>
            </div>

            <!-- Ringkasan Pembayaran -->
            <div class="p-2 rounded-3 bg-light border mb-2 small">
              <div class="d-flex justify-content-between text-muted mb-1">
                <span>Subtotal Menu:</span>
                <span id="cartSubtotalText" class="text-dark fw-bold">Rp 0</span>
              </div>
              <div class="d-flex justify-content-between text-muted mb-1">
                <span>PPN Resto (10%):</span>
                <span id="cartTaxText" class="text-dark fw-bold">Rp 0</span>
              </div>
              <div class="d-flex justify-content-between pt-1 border-top fw-bold">
                <span class="text-dark">Total Tagihan:</span>
                <span id="cartGrandTotalText" class="text-success fs-6">Rp 0</span>
              </div>
            </div>
          </div>
        </div>

        <!-- Fixed Footer Modal: Tombol Kirim Pesanan Selalu Terlihat dan Rapi di Bawah -->
        <div class="sheet-footer-fixed">
          <button type="button" class="btn-submit-glow" id="btnSubmitOrder" onclick="processSubmitOrder()" disabled>
            <i class="bi bi-send-fill me-1"></i> Kirim Pesanan ke Kasir & Dapur
          </button>
        </div>

      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

  <script>
    // Inisialisasi Keranjang Belanja Pelanggan
    let cart = {};
    const tableNumber = <?= json_encode($mejaAktif) ?>;
    let selectedPayMethod = 'kasir';
    let detailModalObj = null;
    let cartModalObj = null;
    let currentDetailItem = null;
    let currentDetailQty = 1;

    let categorySheetModalObj = null;

    // Load keranjang tersimpan dari sessionStorage jika ada
    try {
      const savedCart = sessionStorage.getItem('dapur_tyas_cart_' + tableNumber);
      if (savedCart) {
        cart = JSON.parse(savedCart);
      }
    } catch(e) {}

    window.addEventListener('DOMContentLoaded', () => {
      detailModalObj = new bootstrap.Modal(document.getElementById('menuDetailModal'));
      cartModalObj = new bootstrap.Modal(document.getElementById('cartModal'));
      categorySheetModalObj = new bootstrap.Modal(document.getElementById('categorySheetModal'));
      updateCartUI();
      checkActiveOrder();

      // Cek apakah ada nama pelanggan tersimpan
      const savedName = localStorage.getItem('dapur_tyas_cust_name');
      if (savedName) {
        document.getElementById('custNameInput').value = savedName;
      }

      // Input Search Listener
      const searchInput = document.getElementById('searchInput');
      const searchClear = document.getElementById('searchClearBtn');
      searchInput.addEventListener('input', () => {
        const val = searchInput.value.trim().toLowerCase();
        searchClear.style.display = val ? 'flex' : 'none';
        applyFilters();
      });
    });

    // Cek pesanan aktif di local / session storage
    function checkActiveOrder() {
      const lastCode = localStorage.getItem('dapur_tyas_last_order_' + tableNumber) || sessionStorage.getItem('dapur_tyas_last_order');
      if (lastCode) {
        const banner = document.getElementById('activeOrderBanner');
        const text = document.getElementById('activeOrderKodeText');
        const link = document.getElementById('activeOrderLink');
        if (banner && text && link) {
          text.innerText = 'Kode: ' + lastCode;
          link.href = 'sukses.php?kode=' + encodeURIComponent(lastCode);
          banner.style.display = 'flex';
        }
      }
    }

    function formatRupiah(number) {
      return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0 }).format(number);
    }

    function triggerHaptic() {
      if ('vibrate' in navigator) {
        navigator.vibrate(20);
      }
    }

    // ================= FILTER PENCARIAN & KATEGORI =================
    let currentCategory = 'all';

    function filterCategory(cat, btn) {
      triggerHaptic();
      currentCategory = cat;

      // Update chip aktif di swipe bar
      document.querySelectorAll('.cat-chip').forEach(c => {
        if (c.getAttribute('data-cat') === cat) {
          c.classList.add('active');
          c.scrollIntoView({ behavior: 'smooth', inline: 'center', block: 'nearest' });
        } else {
          c.classList.remove('active');
        }
      });

      // Update modal kartu jika dibuka
      document.querySelectorAll('.cat-modal-card').forEach(mc => {
        if (mc.getAttribute('data-cat') === cat) mc.classList.add('active');
        else mc.classList.remove('active');
      });

      applyFilters();
    }

    function openCategorySheet() {
      triggerHaptic();
      categorySheetModalObj.show();
    }

    function selectCategoryFromSheet(cat) {
      filterCategory(cat, null);
      categorySheetModalObj.hide();
      document.getElementById('menuContainer').scrollIntoView({ behavior: 'smooth', block: 'start' });
    }

    function goToKatalog() {
      triggerHaptic();
      window.scrollTo({ top: 0, behavior: 'smooth' });
      if (currentCategory !== 'all') {
        filterCategory('all', null);
      }
    }

    function quickSearch(keyword) {
      triggerHaptic();
      const inp = document.getElementById('searchInput');
      inp.value = keyword;
      document.getElementById('searchClearBtn').style.display = 'flex';
      applyFilters();
      inp.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }

    function applyFilters() {
      const query = document.getElementById('searchInput').value.trim().toLowerCase();
      const cards = document.querySelectorAll('.menu-card');
      const trendingSec = document.getElementById('trendingSection');
      const gridTitle = document.getElementById('gridTitleText');
      let visibleCount = 0;

      // Atur visibilitas Rekomendasi Koki: hanya tampil jika kategori "Semua" dan tidak sedang mencari keyword
      if (trendingSec) {
        if (currentCategory === 'all' && !query) {
          trendingSec.style.display = 'block';
        } else {
          trendingSec.style.display = 'none';
        }
      }

      // Update Judul Section Grid
      if (gridTitle) {
        if (query) {
          gridTitle.innerHTML = `<i class="bi bi-search text-warning me-1"></i> Hasil Pencarian: "${escapeHtml(query)}"`;
        } else if (currentCategory === 'all') {
          gridTitle.innerHTML = `<i class="bi bi-collection-fill text-warning me-1"></i> Katalog Semua Hidangan`;
        } else {
          gridTitle.innerHTML = `<i class="bi bi-compass-fill text-warning me-1"></i> Kategori: ${escapeHtml(currentCategory)}`;
        }
      }

      cards.forEach(card => {
        const name = card.getAttribute('data-name');
        const cat = card.getAttribute('data-cat');
        const matchQuery = !query || name.includes(query);
        const matchCat = (currentCategory === 'all') || (cat === currentCategory);

        if (matchQuery && matchCat) {
          card.style.display = 'flex';
          visibleCount++;
        } else {
          card.style.display = 'none';
        }
      });

      document.getElementById('menuCountText').innerText = visibleCount + ' menu';
      const emptyNotice = document.getElementById('emptySearchNotice');
      if (visibleCount === 0) {
        emptyNotice.classList.remove('d-none');
      } else {
        emptyNotice.classList.add('d-none');
      }
    }

    function escapeHtml(str) {
      return str.replace(/&/g, "&amp;").replace(/</g, "&lt;").replace(/>/g, "&gt;").replace(/"/g, "&quot;").replace(/'/g, "&#039;");
    }

    function focusSearch() {
      const inp = document.getElementById('searchInput');
      inp.focus();
      inp.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }

    function clearSearch() {
      const inp = document.getElementById('searchInput');
      inp.value = '';
      document.getElementById('searchClearBtn').style.display = 'none';
      applyFilters();
    }

    // ================= MODAL DETAIL MENU =================
    function openMenuDetail(item) {
      triggerHaptic();
      currentDetailItem = item;
      currentDetailQty = cart[item.id] ? cart[item.id].qty : 1;

      document.getElementById('detailImg').src = item.foto || 'tyas.png';
      document.getElementById('detailTitle').innerText = item.nama_menu;
      document.getElementById('detailCat').innerText = item.kategori;
      document.getElementById('detailDesc').innerText = item.deskripsi || 'Hidangan istimewa racikan bumbu khas Dapur Tyas.';
      document.getElementById('detailPrice').innerText = formatRupiah(item.harga);
      document.getElementById('detailQty').innerText = currentDetailQty;
      document.getElementById('detailTag').innerText = item.tag || 'Spesial';
      document.getElementById('detailCustomNote').value = cart[item.id] ? (cart[item.id].catatan || '') : '';

      detailModalObj.show();
    }

    function adjustDetailQty(delta) {
      triggerHaptic();
      currentDetailQty = Math.max(1, currentDetailQty + delta);
      document.getElementById('detailQty').innerText = currentDetailQty;
    }

    function confirmAddFromDetail() {
      if (!currentDetailItem) return;
      triggerHaptic();
      const note = document.getElementById('detailCustomNote').value.trim();
      cart[currentDetailItem.id] = {
        menu_id: currentDetailItem.id,
        nama_menu: currentDetailItem.nama_menu,
        harga: parseInt(currentDetailItem.harga),
        qty: currentDetailQty,
        catatan: note
      };
      saveCart();
      updateCartUI();
      detailModalObj.hide();
    }

    // ================= DIRECT QUICK ADD TO CART =================
    function addToCartDirect(item) {
      triggerHaptic();
      if (!cart[item.id]) {
        cart[item.id] = {
          menu_id: item.id,
          nama_menu: item.nama_menu,
          harga: parseInt(item.harga),
          qty: 1,
          catatan: ''
        };
      } else {
        cart[item.id].qty += 1;
      }
      saveCart();
      updateCartUI();
    }

    function updateQtyDirect(itemId, delta) {
      triggerHaptic();
      if (cart[itemId]) {
        cart[itemId].qty += delta;
        if (cart[itemId].qty <= 0) {
          delete cart[itemId];
        }
      }
      saveCart();
      updateCartUI();
    }

    function saveCart() {
      try {
        sessionStorage.setItem('dapur_tyas_cart_' + tableNumber, JSON.stringify(cart));
      } catch(e) {}
    }

    // ================= SYNC UI KERANJANG =================
    function updateCartUI() {
      let totalQty = 0;
      let subtotal = 0;

      // Update button & quantity pill pada setiap kartu menu
      document.querySelectorAll('.menu-card').forEach(card => {
        const id = card.getAttribute('data-id');
        const addBtn = document.getElementById('addBtn-' + id);
        const qtyCtrl = document.getElementById('qtyCtrl-' + id);
        const qtyVal = document.getElementById('qtyVal-' + id);

        if (cart[id] && cart[id].qty > 0) {
          if (addBtn) addBtn.style.display = 'none';
          if (qtyCtrl) qtyCtrl.classList.add('show');
          if (qtyVal) qtyVal.innerText = cart[id].qty;
        } else {
          if (addBtn) addBtn.style.display = 'flex';
          if (qtyCtrl) qtyCtrl.classList.remove('show');
        }
      });

      // Hitung total dan subtotal
      for (const id in cart) {
        totalQty += cart[id].qty;
        subtotal += (cart[id].harga * cart[id].qty);
      }

      const badge = document.getElementById('dockCartCountBadge');
      const totalTxt = document.getElementById('dockCartTotalText');
      badge.innerText = totalQty;
      totalTxt.innerText = formatRupiah(subtotal);

      // Render daftar item di modal keranjang
      renderCartItems(subtotal);
    }

    function renderCartItems(subtotal) {
      const container = document.getElementById('cartItemsList');
      const checkoutDetails = document.getElementById('cartCheckoutDetails');
      const btnSubmit = document.getElementById('btnSubmitOrder');
      const items = Object.values(cart);

      if (items.length === 0) {
        container.innerHTML = `
          <div class="text-center py-4 my-2">
            <div class="p-3 rounded-circle d-inline-block mx-auto mb-2" style="background: rgba(249, 115, 22, 0.1);">
              <i class="bi bi-bag-x text-warning fs-1"></i>
            </div>
            <h6 class="fw-bold text-dark mb-1">Pesanan Anda Belum Ada</h6>
            <p class="text-muted small mb-3">Keranjang masih kosong. Silakan pilih menu makanan atau minuman terlebih dahulu.</p>
            <button type="button" class="btn btn-sm btn-outline-warning rounded-pill px-4 py-2 fw-bold" data-bs-dismiss="modal">
              <i class="bi bi-plus-lg me-1"></i> Pilih Menu Sekarang
            </button>
          </div>
        `;
        if (checkoutDetails) checkoutDetails.style.display = 'none';
        if (btnSubmit) btnSubmit.disabled = true;
        return;
      }

      if (checkoutDetails) checkoutDetails.style.display = 'block';
      if (btnSubmit) btnSubmit.disabled = false;

      container.innerHTML = items.map(it => `
        <div class="d-flex align-items-center justify-content-between py-2 border-bottom border-light-subtle gap-2">
          <div style="flex-grow: 1; min-width: 0;">
            <div class="fw-bold text-dark small text-truncate">${it.nama_menu}</div>
            <div class="d-flex align-items-center gap-2">
              <span class="text-muted" style="font-size: 0.72rem;">@ ${formatRupiah(it.harga)}</span>
              <span class="fw-bold text-success" style="font-size: 0.78rem;">= ${formatRupiah(it.harga * it.qty)}</span>
            </div>
            ${it.catatan ? `<div class="text-danger small text-truncate" style="font-size: 0.68rem;">*${it.catatan}</div>` : ''}
          </div>
          <div class="d-flex align-items-center gap-2 flex-shrink-0">
            <div class="d-flex align-items-center gap-1 bg-light p-1 rounded-pill border">
              <button type="button" class="btn btn-sm btn-outline-secondary rounded-circle py-0 px-2" style="line-height:1.2;" onclick="updateQtyDirect(${it.menu_id}, -1)">-</button>
              <span class="fw-bold px-1 small text-dark">${it.qty}</span>
              <button type="button" class="btn btn-sm btn-outline-secondary rounded-circle py-0 px-2" style="line-height:1.2;" onclick="updateQtyDirect(${it.menu_id}, 1)">+</button>
            </div>
            <button type="button" class="btn-item-delete" onclick="deleteItemDirect(${it.menu_id})" title="Hapus menu ini">
              <i class="bi bi-x-lg"></i>
            </button>
          </div>
        </div>
      `).join('');

      const tax = Math.round(subtotal * 0.10);
      const grandTotal = subtotal + tax;
      document.getElementById('cartSubtotalText').innerText = formatRupiah(subtotal);
      document.getElementById('cartTaxText').innerText = formatRupiah(tax);
      document.getElementById('cartGrandTotalText').innerText = formatRupiah(grandTotal);
    }

    function deleteItemDirect(itemId) {
      triggerHaptic();
      if (cart[itemId]) {
        delete cart[itemId];
        saveCart();
        updateCartUI();
      }
    }

    function openCartModal() {
      triggerHaptic();
      cartModalObj.show();
    }

    function selectPayOption(method) {
      triggerHaptic();
      selectedPayMethod = method;
      const optKasir = document.getElementById('optPayKasir');
      const optEwallet = document.getElementById('optPayEwallet');

      if (method === 'kasir') {
        optKasir.classList.add('selected');
        optKasir.querySelector('.check-icon').className = 'bi bi-check-circle-fill text-success fs-5 check-icon';
        optEwallet.classList.remove('selected');
        optEwallet.querySelector('.check-icon').className = 'bi bi-circle text-muted fs-5 check-icon';
      } else {
        optEwallet.classList.add('selected');
        optEwallet.querySelector('.check-icon').className = 'bi bi-check-circle-fill text-success fs-5 check-icon';
        optKasir.classList.remove('selected');
        optKasir.querySelector('.check-icon').className = 'bi bi-circle text-muted fs-5 check-icon';
      }
    }

    // ================= PILIH TIPE PESANAN: DINE IN / TAKE AWAY =================
    let selectedOrderType = 'dine_in';

    function selectOrderType(type) {
      triggerHaptic();
      selectedOrderType = type;
      const optDineIn = document.getElementById('optTypeDineIn');
      const optTakeAway = document.getElementById('optTypeTakeAway');

      if (type === 'dine_in') {
        optDineIn.classList.add('selected');
        optTakeAway.classList.remove('selected');
      } else {
        optTakeAway.classList.add('selected');
        optDineIn.classList.remove('selected');
      }
    }

    // ================= PILIH OPSI STRUK FISIK KASIR =================
    let selectedReceiptOption = 0; // 0 = tidak perlu / paperless, 1 = minta struk fisik
    function selectReceiptOption(opt) {
      triggerHaptic();
      selectedReceiptOption = opt;
      const optYa = document.getElementById('optStrukYa');
      const optTidak = document.getElementById('optStrukTidak');
      if (opt === 1) {
        optYa.classList.add('selected');
        optTidak.classList.remove('selected');
      } else {
        optTidak.classList.add('selected');
        optYa.classList.remove('selected');
      }
    }

    // ================= PROSES PENGIRIMAN PESANAN KE BACKEND =================
    async function processSubmitOrder() {
      const items = Object.values(cart);
      if (items.length === 0) {
        alert('Keranjang belanja Anda masih kosong!');
        return;
      }

      const custName = document.getElementById('custNameInput').value.trim() || `Pelanggan Meja ${tableNumber}`;
      localStorage.setItem('dapur_tyas_cust_name', custName);

      const submitBtn = document.getElementById('btnSubmitOrder');
      submitBtn.disabled = true;
      submitBtn.innerHTML = `<span class="spinner-border spinner-border-sm me-2"></span> Mengirim ke Dapur...`;

      try {
        const response = await fetch('simpan-pesanan.php', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({
            nomor_meja: tableNumber,
            nama_pelanggan: custName,
            tipe_pesanan: selectedOrderType,
            metode_bayar: selectedPayMethod,
            minta_struk: selectedReceiptOption,
            items: items
          })
        });

        const data = await response.json();

        if (data.status === 'success') {
          // Bersihkan keranjang belanja
          cart = {};
          sessionStorage.removeItem('dapur_tyas_cart_' + tableNumber);
          localStorage.setItem('dapur_tyas_last_order_' + tableNumber, data.kode_pesanan);
          sessionStorage.setItem('dapur_tyas_last_order', data.kode_pesanan);
          updateCartUI();
          cartModalObj.hide();

          // Arahkan seketika ke halaman lacak status pesanan & pembayaran
          window.location.href = `sukses.php?kode=${encodeURIComponent(data.kode_pesanan)}`;
        } else {
          alert('Gagal: ' + data.message);
          submitBtn.disabled = false;
          submitBtn.innerHTML = `<i class="bi bi-send-fill me-1"></i> Kirim Pesanan ke Kasir & Dapur`;
        }
      } catch (err) {
        alert('Terjadi kesalahan jaringan saat mengirim pesanan. Silakan coba lagi.');
        submitBtn.disabled = false;
        submitBtn.innerHTML = `<i class="bi bi-send-fill me-1"></i> Kirim Pesanan ke Kasir & Dapur`;
      }
    }
  </script>
</body>
</html>
