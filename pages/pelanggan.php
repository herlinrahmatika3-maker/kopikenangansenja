<?php
require_once __DIR__ . '/../config/constants.php';
require_once __DIR__ . '/../includes/customer_check.php';
require_once __DIR__ . '/../config/koneksi.php';
require_once __DIR__ . '/../includes/produk_setup.php';

$cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
$cartCount = 0;
foreach ($cart as $qty) { $cartCount += $qty; }

$produkCount = 0;
$produkQuery = mysqli_query($con, "SELECT COUNT(*) AS total FROM produk");
if ($produkQuery) {
    $row = mysqli_fetch_assoc($produkQuery);
    $produkCount = (int) $row['total'];
}

$reviewCount = 0;
$reviewQuery = mysqli_query($con, "SELECT COUNT(*) AS total FROM ulasan");
if ($reviewQuery) {
    $row = mysqli_fetch_assoc($reviewQuery);
    $reviewCount = (int) $row['total'];
}
?>
<!doctype html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Pelanggan - Kopi Kenangan Senja</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
  <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/style.css">
  <style>
    .info-box-icon { background: #3d1c00 !important; }
    .btn-action { min-width: 180px; }
  </style>
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">
  <nav class="main-header navbar navbar-expand navbar-dark" style="background:linear-gradient(90deg,#3d1c00,#7a3b10);">
    <ul class="navbar-nav">
      <li class="nav-item d-none d-sm-inline-block">
        <a href="<?php echo BASE_URL; ?>/pages/pelanggan.php" class="nav-link"><i class="fas fa-home" style="margin-right:6px;"></i>Dashboard</a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="<?php echo BASE_URL; ?>/pages/store.php" class="nav-link"><i class="fas fa-store" style="margin-right:6px;"></i>Belanja</a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="<?php echo BASE_URL; ?>/pages/keranjang.php" class="nav-link"><i class="fas fa-shopping-cart" style="margin-right:6px;"></i>Keranjang (<?php echo $cartCount; ?>)</a>
      </li>
    </ul>
    <ul class="navbar-nav ml-auto">
      <li class="nav-item">
        <span class="nav-link text-light"><i class="fas fa-user-circle" style="margin-right:4px;"></i><?php echo htmlspecialchars($_SESSION['nama'] ?? $_SESSION['username']); ?></span>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="<?php echo BASE_URL; ?>/actions/logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
      </li>
    </ul>
  </nav>

  <div class="content-wrapper">
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0" style="color:#3d1c00;"><i class="fas fa-user-friends" style="color:#c8842a;margin-right:8px;"></i>Dashboard Pelanggan</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item active">Dashboard</li>
            </ol>
          </div>
        </div>
      </div>
    </section>

    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-lg-4 col-md-6 mb-3">
            <div class="info-box">
              <span class="info-box-icon"><i class="fas fa-coffee"></i></span>
              <div class="info-box-content">
                <span class="info-box-text">Menu Tersedia</span>
                <span class="info-box-number"><?php echo $produkCount; ?></span>
              </div>
            </div>
          </div>
          <div class="col-lg-4 col-md-6 mb-3">
            <div class="info-box">
              <span class="info-box-icon"><i class="fas fa-shopping-cart"></i></span>
              <div class="info-box-content">
                <span class="info-box-text">Isi Keranjang</span>
                <span class="info-box-number"><?php echo $cartCount; ?></span>
              </div>
            </div>
          </div>
          <div class="col-lg-4 col-md-6 mb-3">
            <div class="info-box">
              <span class="info-box-icon"><i class="fas fa-star"></i></span>
              <div class="info-box-content">
                <span class="info-box-text">Ulasan Tersedia</span>
                <span class="info-box-number"><?php echo $reviewCount; ?></span>
              </div>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-lg-4 col-md-6 mb-3">
            <div class="card card-outline card-success">
              <div class="card-body text-center">
                <h5 class="mb-3">Mulai Belanja</h5>
                <p class="text-muted">Lihat semua menu kopi dan tambahkan ke keranjang.</p>
                <a href="<?php echo BASE_URL; ?>/pages/store.php" class="btn btn-success btn-action">Buka Toko</a>
              </div>
            </div>
          </div>
          <div class="col-lg-4 col-md-6 mb-3">
            <div class="card card-outline card-warning">
              <div class="card-body text-center">
                <h5 class="mb-3">Beri Ulasan</h5>
                <p class="text-muted">Bagikan pendapatmu tentang menu favorit.</p>
                <a href="<?php echo BASE_URL; ?>/pages/store.php#review-form" class="btn btn-warning btn-action">Tulis Ulasan</a>
              </div>
            </div>
          </div>
          <div class="col-lg-4 col-md-6 mb-3">
            <div class="card card-outline card-info">
              <div class="card-body text-center">
                <h5 class="mb-3">Periksa Keranjang</h5>
                <p class="text-muted">Lihat dan checkout pesananmu.</p>
                <a href="<?php echo BASE_URL; ?>/pages/keranjang.php" class="btn btn-info btn-action">Lihat Keranjang</a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>

  <footer class="main-footer text-center" style="background:#2d1200;color:#a08050;border-top:1px solid #5a2d00;">
    <strong>&copy; 2026 Kopi Kenangan Senja</strong> &mdash; All rights reserved.
  </footer>
</div>

<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.4/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>
</body>
</html>
