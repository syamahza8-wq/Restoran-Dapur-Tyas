<?php
/**
 * Database Connection & Global Configuration
 * Dapur Tyas by Techno Garage
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Konfigurasi Database MySQL Laragon
$db_host = '127.0.0.1';
$db_user = 'root';
$db_pass = '';
$db_name = 'dapur_tyas';

try {
    $pdo = new PDO("mysql:host={$db_host};dbname={$db_name};charset=utf8mb4", $db_user, $db_pass, [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ]);
} catch (PDOException $e) {
    die("Koneksi Database Gagal: " . htmlspecialchars($e->getMessage()));
}

/**
 * Deteksi Perangkat (User-Agent Sniffer)
 * Mengembalikan true jika diakses via Smartphone / Mobile device (Android, iPhone, iPad, Windows Phone dsb)
 */
function isMobileDevice() {
    if (empty($_SERVER['HTTP_USER_AGENT'])) {
        return false;
    }
    $userAgent = strtolower($_SERVER['HTTP_USER_AGENT']);
    $mobileKeywords = [
        'mobile', 'android', 'iphone', 'ipad', 'ipod', 'blackberry',
        'opera mini', 'windows phone', 'iemobile', 'wpdesktop', 'webos'
    ];
    foreach ($mobileKeywords as $keyword) {
        if (strpos($userAgent, $keyword) !== false) {
            return true;
        }
    }
    return false;
}

/**
 * Format Angka ke Rupiah
 */
function formatRupiah($angka) {
    return 'Rp ' . number_format($angka, 0, ',', '.');
}

/**
 * Deteksi Dinamis Alamat IP Wi-Fi / LAN Laptop Server
 * Menyesuaikan seketika saat laptop berpindah jaringan Wi-Fi (bahkan tanpa restart Laragon)
 */
function getLocalServerIP() {
    // 1. Jika request datang dari browser client ke IP host tertentu, gunakan host itu
    if (!empty($_SERVER['HTTP_HOST'])) {
        $host = $_SERVER['HTTP_HOST'];
        $parts = explode(':', $host);
        $hostIp = $parts[0];
        if ($hostIp !== 'localhost' && $hostIp !== '127.0.0.1' && filter_var($hostIp, FILTER_VALIDATE_IP)) {
            return $hostIp;
        }
    }

    if (!empty($_SERVER['SERVER_ADDR']) && $_SERVER['SERVER_ADDR'] !== '127.0.0.1' && $_SERVER['SERVER_ADDR'] !== '::1') {
        return $_SERVER['SERVER_ADDR'];
    }

    // 2. Cari interface Wi-Fi aktif di Windows via PowerShell
    if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
        $cmdWifi = 'powershell -NoProfile -Command "Get-NetIPAddress -AddressFamily IPv4 | Where-Object { ($_.InterfaceAlias -like \'*Wi-Fi*\' -or $_.InterfaceAlias -like \'*Wireless*\' -or $_.InterfaceAlias -like \'*WLAN*\') -and $_.IPAddress -notlike \'169.254*\' -and $_.IPAddress -ne \'127.0.0.1\' } | Select-Object -ExpandProperty IPAddress"';
        $wifiOutput = @shell_exec($cmdWifi);
        if ($wifiOutput) {
            $lines = array_filter(array_map('trim', explode("\n", $wifiOutput)));
            foreach ($lines as $line) {
                if (filter_var($line, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
                    return $line;
                }
            }
        }

        // Coba interface jaringan LAN non-loopback lainnya
        $cmdLan = 'powershell -NoProfile -Command "Get-NetIPAddress -AddressFamily IPv4 | Where-Object { $_.InterfaceAlias -notmatch \'Loopback|vEthernet|VirtualBox|VMware\' -and $_.IPAddress -notlike \'169.254*\' -and $_.IPAddress -ne \'127.0.0.1\' } | Select-Object -ExpandProperty IPAddress"';
        $lanOutput = @shell_exec($cmdLan);
        if ($lanOutput) {
            $lines = array_filter(array_map('trim', explode("\n", $lanOutput)));
            foreach ($lines as $line) {
                if (filter_var($line, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
                    return $line;
                }
            }
        }
    }

    // 3. Fallback host name
    $hostName = gethostname();
    $ip = gethostbyname($hostName);
    if ($ip && $ip !== '127.0.0.1') {
        return $ip;
    }

    return '127.0.0.1';
}
?>
