<?php
/**
 * Router Utama Dapur Tyas by Techno Garage
 * - Jika diakses lewat HP / Mobile:
 *   * Jika ada parameter meja -> Ke menu.php?meja=XX
 *   * Jika belum ada meja -> Ke pilih-meja.php
 * - Jika diakses lewat Komputer / Kasir -> Arahkan ke POS Kasir / Login Staf (pos.php)
 */

require_once __DIR__ . '/config.php';

if (isMobileDevice()) {
    if (!empty($_GET['meja'])) {
        $meja = trim($_GET['meja']);
        header("Location: menu.php?meja=" . urlencode($meja));
    } else {
        header("Location: pilih-meja.php");
    }
    exit;
} else {
    if (!empty($_SESSION['staf_id'])) {
        header("Location: pos.php");
    } else {
        header("Location: login.php");
    }
    exit;
}
?>
