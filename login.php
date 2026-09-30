<?php
/**
 * Halaman Login Staf (Kasir & Admin)
 * Dapur Tyas by Techno Garage
 * 
 * KEAMANAN PERANGKAT:
 * Dilarang keras dibuka dari Smartphone/Mobile.
 * Jika diakses via HP, langsung di-redirect ke akses-ditolak.php.
 */

require_once __DIR__ . '/config.php';

// Proteksi Deteksi Perangkat Mobile (Server-side User Agent)
if (isMobileDevice()) {
    header("Location: akses-ditolak.php");
    exit;
}

$pesanError = '';

// Proses Login
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if (!empty($username) && !empty($password)) {
        $stmt = $pdo->prepare("SELECT * FROM tabel_staf WHERE username = ? LIMIT 1");
        $stmt->execute([$username]);
        $staf = $stmt->fetch();

        if ($staf && password_verify($password, $staf['password'])) {
            $_SESSION['staf_id'] = $staf['id'];
            $_SESSION['staf_user'] = $staf['username'];
            $_SESSION['staf_nama'] = $staf['nama_lengkap'];
            $_SESSION['staf_role'] = $staf['role'];

            // Redirect sesuai role
            if ($staf['role'] === 'admin') {
                header("Location: pos.php?tab=admin");
            } else {
                header("Location: pos.php");
            }
            exit;
        } else {
            $pesanError = 'Username atau Password salah!';
        }
    } else {
        $pesanError = 'Silakan isi username dan password.';
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login Staf - Dapur Tyas by Techno Garage</title>
  
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <link rel="stylesheet" href="assets/css/style.css">

  <script>
    // Proteksi Lapis Kedua (Client-side JavaScript Screen & Navigator Check)
    if (/Mobi|Android|iPhone|iPad|iPod/i.test(navigator.userAgent) || window.innerWidth <= 768) {
      window.location.href = "akses-ditolak.php";
    }
  </script>
</head>
<body class="d-flex align-items-center justify-content-center min-vh-100" style="background-color: var(--bg-primary);">

  <div class="card-custom bg-white p-4 p-md-5 shadow-lg" style="max-width: 440px; width: 100%;">
    
    <!-- Dual Logo Dapur Tyas & Techno Garage -->
    <div class="text-center mb-4">
      <div class="d-inline-block position-relative mb-2">
        <img src="tyas.png" alt="Logo Dapur Tyas" class="rounded-circle shadow-sm" style="width: 72px; height: 72px; object-fit: cover; border: 3px solid var(--primary); background: #fff;">
        <img src="techno garage.jpg" alt="Techno Garage" class="rounded-circle position-absolute bottom-0 end-0 border border-white shadow-sm" style="width: 26px; height: 26px; object-fit: cover; transform: translate(15%, 15%);">
      </div>
      <h3 class="fw-bold text-main mb-0" style="letter-spacing: -0.5px;">DAPUR TYAS</h3>
      <div class="small text-muted">by Techno Garage &bull; Portal Staf Desktop</div>
    </div>

    <?php if (!empty($pesanError)): ?>
      <div class="alert alert-danger py-2 small" role="alert">
        <i class="bi bi-exclamation-circle me-1"></i> <?= htmlspecialchars($pesanError) ?>
      </div>
    <?php endif; ?>

    <form method="POST" action="login.php">
      <div class="mb-3">
        <label class="form-label small fw-bold text-main">Username Staf</label>
        <div class="input-group">
          <span class="input-group-text bg-white"><i class="bi bi-person text-muted"></i></span>
          <input type="text" name="username" class="form-control" placeholder="admin / kasir" required autofocus>
        </div>
      </div>

      <div class="mb-4">
        <label class="form-label small fw-bold text-main">Password</label>
        <div class="input-group">
          <span class="input-group-text bg-white"><i class="bi bi-lock text-muted"></i></span>
          <input type="password" name="password" class="form-control" placeholder="Masukkan password" required>
        </div>
      </div>

      <button type="submit" class="btn btn-primary-custom w-100 py-2 fs-6 mb-3">
        <i class="bi bi-box-arrow-in-right"></i> Masuk ke Sistem
      </button>
    </form>

    <div class="border-top pt-3 text-center small text-muted">
      <div>Akun Uji Coba:</div>
      <div>Kasir: <code>kasir</code> | pass: <code>kasir123tyas</code></div>
      <div>Admin: <code>admin</code> | pass: <code>admin123tyas</code></div>
    </div>

  </div>

</body>
</html>
