<?php
/**
 * Downloader untuk menyimpan 83 foto kuliner asli ke lokal (assets/menu)
 * Agar website 100% cepat, offline-ready, dan tidak pernah gagal load gambar!
 */
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/seed_250_menu.php';

$dir = __DIR__ . '/assets/menu';
if (!is_dir($dir)) {
    mkdir($dir, 0777, true);
}

echo "Memulai download foto kuliner ke folder lokal (assets/menu)...\n";

$downloaded = 0;
$failed = 0;
$allPhotoIds = [];
foreach ($photoLibrary as $k => $list) {
    foreach ($list as $pid) {
        $allPhotoIds[$pid] = true;
    }
}

$context = stream_context_create([
    'http' => [
        'timeout' => 15,
        'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36'
    ]
]);

$ch = curl_init();
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 15);
curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)');

foreach (array_keys($allPhotoIds) as $idx => $photoId) {
    $targetFile = $dir . '/' . $photoId . '.jpg';
    
    if (file_exists($targetFile) && filesize($targetFile) > 1000) {
        $downloaded++;
        continue;
    }

    $url = "https://images.unsplash.com/{$photoId}?auto=format&fit=crop&w=500&h=380&q=80";
    curl_setopt($ch, CURLOPT_URL, $url);
    $data = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    if ($httpCode == 200 && strlen($data) > 1000) {
        file_put_contents($targetFile, $data);
        $downloaded++;
        echo "[$downloaded/" . count($allPhotoIds) . "] Berhasil: $photoId.jpg (" . round(strlen($data)/1024) . " KB)\n";
    } else {
        $failed++;
        echo "[$idx] GAGAL: $photoId (HTTP: $httpCode)\n";
    }
}
curl_close($ch);

echo "Selesai download: $downloaded berhasil, $failed gagal.\n";

// Sekarang update database tabel_menu agar menggunakan path lokal jika file lokal tersedia!
echo "Memperbarui tabel_menu di MySQL...\n";
$stmt = $pdo->query("SELECT id, nama_menu, kategori FROM tabel_menu");
$menus = $stmt->fetchAll();

$updateStmt = $pdo->prepare("UPDATE tabel_menu SET foto = ? WHERE id = ?");

$updatedCount = 0;
foreach ($menus as $idx => $m) {
    $namaLower = strtolower($m['nama_menu']);
    $katLower = strtolower($m['kategori']);
    
    $type = 'nasi';
    if (strpos($namaLower, 'kopi') !== false || strpos($namaLower, 'espresso') !== false || strpos($namaLower, 'latte') !== false || strpos($namaLower, 'macchiato') !== false || strpos($namaLower, 'americano') !== false) {
        $type = 'kopi';
    } elseif (strpos($namaLower, 'teh') !== false || strpos($namaLower, 'jus') !== false || strpos($namaLower, 'es ') !== false || strpos($namaLower, 'wedang') !== false || strpos($namaLower, 'kelapa') !== false || strpos($namaLower, 'smoothie') !== false || strpos($katLower, 'minuman') !== false) {
        $type = 'minum';
    } elseif (strpos($namaLower, 'mie') !== false || strpos($namaLower, 'kwetiau') !== false || strpos($namaLower, 'bihun') !== false || strpos($namaLower, 'bakso') !== false || strpos($namaLower, 'seblak') !== false) {
        $type = 'mie';
    } elseif (strpos($namaLower, 'udang') !== false || strpos($namaLower, 'cumi') !== false || strpos($namaLower, 'ikan') !== false || strpos($namaLower, 'gurame') !== false || strpos($namaLower, 'kakap') !== false || strpos($namaLower, 'kerang') !== false || strpos($namaLower, 'kepiting') !== false || strpos($katLower, 'seafood') !== false) {
        $type = 'seafood';
    } elseif (strpos($namaLower, 'bebek') !== false) {
        $type = 'bebek';
    } elseif (strpos($namaLower, 'sapi') !== false || strpos($namaLower, 'dendeng') !== false || strpos($namaLower, 'rendang') !== false || strpos($namaLower, 'iga') !== false || strpos($namaLower, 'buntut') !== false || strpos($namaLower, 'rawon') !== false) {
        $type = 'daging';
    } elseif (strpos($namaLower, 'geprek') !== false || strpos($namaLower, 'mercon') !== false || strpos($namaLower, 'sambal') !== false || strpos($namaLower, 'penyet') !== false || strpos($namaLower, 'balado') !== false || strpos($namaLower, 'pedas') !== false) {
        $type = 'pedas';
    } elseif (strpos($namaLower, 'soto') !== false || strpos($namaLower, 'sop') !== false || strpos($namaLower, 'kuah') !== false || strpos($namaLower, 'sayur asem') !== false || strpos($namaLower, 'gulai') !== false || strpos($namaLower, 'tongseng') !== false) {
        $type = 'kuah';
    } elseif (strpos($namaLower, 'dimsum') !== false || strpos($namaLower, 'pangsit') !== false || strpos($namaLower, 'batagor') !== false || strpos($namaLower, 'siomay') !== false || strpos($namaLower, 'mendoan') !== false || strpos($namaLower, 'cireng') !== false || strpos($namaLower, 'tahu') !== false || strpos($namaLower, 'tempe') !== false || strpos($namaLower, 'gorengan') !== false) {
        $type = 'snack';
    } elseif (strpos($namaLower, 'salad') !== false || strpos($namaLower, 'gado') !== false || strpos($namaLower, 'karedok') !== false || strpos($namaLower, 'pecel') !== false) {
        $type = 'salad';
    } elseif (strpos($namaLower, 'puding') !== false || strpos($namaLower, 'es krim') !== false || strpos($namaLower, 'ice cream') !== false || strpos($namaLower, 'waffle') !== false || strpos($namaLower, 'pancake') !== false || strpos($namaLower, 'roti') !== false || strpos($namaLower, 'pisang') !== false) {
        $type = 'dessert';
    } elseif (strpos($namaLower, 'ayam') !== false) {
        $type = 'ayam';
    }

    $list = $photoLibrary[$type] ?? $photoLibrary['nasi'];
    $photoId = $list[$idx % count($list)];
    $localPath = "assets/menu/{$photoId}.jpg";

    if (file_exists(__DIR__ . '/' . $localPath)) {
        $updateStmt->execute([$localPath, $m['id']]);
        $updatedCount++;
    }
}

echo "SUKSES: $updatedCount menu diupdate menggunakan path lokal assets/menu/\n";
