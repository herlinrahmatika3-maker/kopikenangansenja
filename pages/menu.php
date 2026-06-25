<?php
require_once __DIR__ . '/../config/constants.php';
require_once __DIR__ . '/../includes/admin_check.php';
require_once __DIR__ . '/../config/koneksi.php';
require_once __DIR__ . '/../includes/produk_setup.php';

$ulasan = [];
$menuOptions = [];
$menuQuery = mysqli_query($con, "SELECT nama FROM produk ORDER BY nama ASC");
if ($menuQuery) {
    while ($row = mysqli_fetch_assoc($menuQuery)) {
        $menuOptions[] = $row['nama'];
    }
}
$ulasanQuery = mysqli_query($con, "SELECT * FROM ulasan ORDER BY created_at DESC");
if (!$ulasanQuery) {
    $ulasanQuery = mysqli_query($con, "SELECT * FROM ulasan ORDER BY id DESC");
}
if ($ulasanQuery) {
    while ($row = mysqli_fetch_assoc($ulasanQuery)) {
        $ulasan[] = $row;
    }
}
$ratingCount = count($ulasan);
$ratingTotal = 0;
foreach ($ulasan as $u) { $ratingTotal += $u['rating']; }
$avgRating = $ratingCount ? number_format($ratingTotal / $ratingCount, 1) : '0.0';
$pesan = isset($_GET['pesan']) ? $_GET['pesan'] : '';
?>
<!doctype html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Ulasan - Kopi Kenangan Senja</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
  <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/style.css">
  <style>
    .ulasan-card { border-left: 4px solid #c8842a; transition: transform 0.2s; }
    .ulasan-card:hover { transform: translateY(-3px); box-shadow: 0 8px 25px rgba(200,132,42,0.15); }
    .form-card { border-left: 4px solid #c8842a; }
    .form-card .card-header { background:#fff7eb; }
    .form-card label { font-weight: 600; }
    .rating-chip { font-size: 0.92rem; }
  </style>
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

  <nav class="main-header navbar navbar-expand navbar-dark" style="background:linear-gradient(90deg,#3d1c00,#7a3b10);">
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#"><i class="fas fa-bars"></i></a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <span class="nav-link text-warning" style="font-weight:700;">
          <i class="fas fa-mug-hot" style="margin-right:6px;"></i>Kopi Kenangan Senja
        </span>
      </li>
    </ul>
    <ul class="navbar-nav ml-auto">
      <li class="nav-item">
        <span class="nav-link text-light">
          <i class="fas fa-user-circle" style="margin-right:4px;"></i>
          Halo, <strong><?php echo $_SESSION['username']; ?></strong>
        </span>
      </li>
      <li class="nav-item">
        <a class="nav-link text-warning" href="<?php echo BASE_URL; ?>/actions/logout.php" onclick="return konfirmasiLogout()">
          <i class="fas fa-sign-out-alt" style="margin-right:4px;"></i>Logout
        </a>
      </li>
    </ul>
  </nav>

  <aside class="main-sidebar sidebar-dark-primary elevation-4" style="background:#2d1200;">
    <a href="<?php echo BASE_URL; ?>/pages/tentang.php" class="brand-link text-center" style="border-bottom:1px solid #5a2d00;">
      <i class="fas fa-mug-hot fa-lg" style="color:#c8842a;"></i>
      <span class="brand-text" style="color:#f0c070;font-weight:700;">Kopi Kenangan Senja</span>
    </a>
    <div class="sidebar">
      <div class="user-panel mt-3 pb-3 mb-3 d-flex align-items-center" style="border-bottom:1px solid #5a2d00;">
        <div class="image">
          <i class="fas fa-user-circle fa-2x" style="color:#c8842a;margin-left:8px;"></i>
        </div>
        <div class="info">
          <a href="#" class="d-block" style="color:#f0c070;"><?php echo $_SESSION['username']; ?></a>
          <small style="color:#a08050;">Administrator</small>
        </div>
      </div>
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu">
          <li class="nav-item">
            <a href="<?php echo BASE_URL; ?>/pages/tentang.php" class="nav-link">
              <i class="nav-icon fas fa-info-circle"></i><p>Tentang Kami</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="<?php echo BASE_URL; ?>/pages/data.php" class="nav-link">
              <i class="nav-icon fas fa-box"></i><p>Data Produk</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="<?php echo BASE_URL; ?>/pages/users.php" class="nav-link">
              <i class="nav-icon fas fa-users"></i><p>Users</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="<?php echo BASE_URL; ?>/pages/menu.php" class="nav-link active" style="background:#c8842a;">
              <i class="nav-icon fas fa-star"></i><p>Ulasan</p>
            </a>
          </li>
          <li class="nav-item mt-3">
            <a href="<?php echo BASE_URL; ?>/actions/logout.php" class="nav-link" onclick="return konfirmasiLogout()" style="color:#ff9966;">
              <i class="nav-icon fas fa-sign-out-alt"></i><p>Logout</p>
            </a>
          </li>
        </ul>
      </nav>
    </div>
  </aside>

  <div class="content-wrapper">
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0" style="color:#3d1c00;">
              <i class="fas fa-star" style="color:#c8842a;margin-right:8px;"></i>Ulasan Pelanggan
            </h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="<?php echo BASE_URL; ?>/pages/tentang.php">Home</a></li>
              <li class="breadcrumb-item active">Ulasan</li>
            </ol>
          </div>
        </div>
      </div>
    </div>

    <section class="content">
      <div class="container-fluid">

        <div class="row mb-3">
          <div class="col-md-4 col-sm-6 mb-3">
            <div class="info-box" style="border-left:4px solid #c8842a;">
              <span class="info-box-icon" style="background:#c8842a;color:#fff;"><i class="fas fa-comments"></i></span>
              <div class="info-box-content">
                <span class="info-box-text">Total Ulasan</span>
                <span class="info-box-number"><?php echo $ratingCount; ?></span>
              </div>
            </div>
          </div>
          <div class="col-md-4 col-sm-6 mb-3">
            <div class="info-box" style="border-left:4px solid #f0a500;">
              <span class="info-box-icon" style="background:#f0a500;color:#fff;"><i class="fas fa-star"></i></span>
              <div class="info-box-content">
                <span class="info-box-text">Rata-rata Rating</span>
                <span class="info-box-number"><?php echo $avgRating; ?> / 5</span>
              </div>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-lg-5 mb-4">
            <div class="card card-outline form-card">
              <div class="card-header">
                <h5 class="card-title mb-0"><i class="fas fa-pen" style="color:#c8842a;margin-right:8px;"></i>Tulis Ulasan Baru</h5>
              </div>
              <div class="card-body">
                <form method="POST" action="<?php echo BASE_URL; ?>/actions/proses_ulasan.php">
                  <input type="hidden" name="return" value="menu.php">

                  <div class="form-group mb-3">
                    <label>Nama Anda</label>
                    <input type="text" name="nama" class="form-control" value="<?php echo htmlspecialchars($_SESSION['nama'] ?? $_SESSION['username']); ?>" required>
                  </div>

                  <div class="form-group mb-3">
                    <label>Menu Favorit</label>
                    <?php if (!empty($menuOptions)): ?>
                      <select name="menu" class="form-control" required>
                        <option value="">Pilih menu favorit</option>
                        <?php foreach ($menuOptions as $menuOption): ?>
                          <option value="<?php echo htmlspecialchars($menuOption); ?>"><?php echo htmlspecialchars($menuOption); ?></option>
                        <?php endforeach; ?>
                      </select>
                    <?php else: ?>
                      <input type="text" name="menu" class="form-control" placeholder="Contoh: Cappuccino" required>
                    <?php endif; ?>
                  </div>

                  <div class="form-group mb-3">
                    <label>Rating</label>
                    <select name="rating" class="form-control" required>
                      <option value="">Pilih Rating</option>
                      <option value="1">⭐ 1</option>
                      <option value="2">⭐⭐ 2</option>
                      <option value="3">⭐⭐⭐ 3</option>
                      <option value="4">⭐⭐⭐⭐ 4</option>
                      <option value="5">⭐⭐⭐⭐⭐ 5</option>
                    </select>
                  </div>

                  <div class="form-group mb-4">
                    <label>Komentar</label>
                    <textarea name="komentar" rows="4" class="form-control" placeholder="Tulis komentar Anda..." required></textarea>
                  </div>

                  <button type="submit" class="btn btn-warning w-100"><i class="fas fa-paper-plane"></i> Kirim Ulasan</button>
                </form>
              </div>
            </div>
          </div>

          <div class="col-lg-7 mb-4">
            <div class="row">
              <?php if (empty($ulasan)): ?>
                <div class="col-12">
                  <div class="card card-outline">
                    <div class="card-body text-center text-muted">
                      Tidak ada ulasan saat ini.
                    </div>
                  </div>
                </div>
              <?php endif; ?>

              <?php foreach ($ulasan as $u): ?>
              <div class="col-md-6 mb-3">
                <div class="card ulasan-card">
                  <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                      <div>
                        <h6 class="mb-1" style="color:#3d1c00;font-weight:700;"><?php echo htmlspecialchars($u['nama'] ?? $u['nama_pengguna'] ?? 'Anonim'); ?></h6>
                        <small class="text-muted"><i class="fas fa-mug-hot" style="margin-right:6px;"></i><?php echo htmlspecialchars($u['menu'] ?? ($u['id_produk'] ? 'Produk #' . $u['id_produk'] : 'Menu')); ?></small>
                      </div>
                      <div>
                        <?php for ($s = 1; $s <= 5; $s++): ?>
                          <i class="fas fa-star" style="color:<?php echo $s <= $u['rating'] ? '#f0a500' : '#ddd'; ?>;"></i>
                        <?php endfor; ?>
                      </div>
                    </div>
                    <p class="text-muted" style="font-size:14px; line-height:1.6;"><?php echo htmlspecialchars($u['komentar']); ?></p>
                    <div class="d-flex justify-content-between align-items-center mt-3">
                      <div class="text-secondary" style="font-size:12px;"><?php echo date('d M Y', strtotime($u['created_at'])); ?></div>
                      <form method="POST" action="<?php echo BASE_URL; ?>/actions/proses_ulasan.php" onsubmit="return confirm('Hapus ulasan ini?');">
                        <input type="hidden" name="action" value="delete">
                        <input type="hidden" name="id" value="<?php echo (int) $u['id']; ?>">
                        <input type="hidden" name="return" value="menu.php">
                        <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                      </form>
                    </div>
                  </div>
                </div>
              </div>
              <?php endforeach; ?>
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
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
  function konfirmasiLogout() {
    Swal.fire({
      title: 'Konfirmasi Logout',
      text: 'Apakah Anda yakin ingin keluar?',
      icon: 'question',
      showCancelButton: true,
      confirmButtonColor: '#c8842a',
      cancelButtonColor: '#6c757d',
      confirmButtonText: 'Ya, Logout',
      cancelButtonText: 'Batal'
    }).then(function(result) {
      if (result.isConfirmed) { window.location.href = '<?php echo BASE_URL; ?>/actions/logout.php'; }
    });
    return false;
  }

  var pesan = '<?php echo $pesan; ?>';
  if (pesan === 'ulasan') {
    Swal.fire({ icon: 'success', title: 'Berhasil', text: 'Ulasan berhasil dikirim.', confirmButtonColor: '#c8842a' });
  }
  if (pesan === 'deleted') {
    Swal.fire({ icon: 'success', title: 'Berhasil', text: 'Ulasan berhasil dihapus.', confirmButtonColor: '#c8842a' });
  }
</script>
</body>
</html>
