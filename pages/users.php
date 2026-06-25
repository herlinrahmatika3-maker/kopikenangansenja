<?php
require_once __DIR__ . '/../config/constants.php';
require_once __DIR__ . '/../includes/admin_check.php';
require_once __DIR__ . '/../config/koneksi.php';
require_once __DIR__ . '/../includes/produk_setup.php';

$users = [];
$userQuery = mysqli_query($con, "SELECT username, nama, role FROM users ORDER BY role ASC, username ASC");
if ($userQuery) {
    while ($row = mysqli_fetch_assoc($userQuery)) {
        $users[] = $row;
    }
}

$totalUsers = count($users);
$totalAdmin = 0;
$totalCustomer = 0;
foreach ($users as $user) {
    if ($user['role'] === 'admin') {
        $totalAdmin++;
    } else {
        $totalCustomer++;
    }
}
?>
<!doctype html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Users - Kopi Kenangan Senja</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
  <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/style.css">
  <style>
    .info-box-icon { background: #3d1c00 !important; }
    .user-role-badge { font-size: 0.82rem; padding: 5px 10px; border-radius: 999px; }
    .badge-admin { background: #c8842a; color: #fff; }
    .badge-pelanggan { background: #3d1c00; color: #fff; }
    .user-table th, .user-table td { vertical-align: middle; }
    .user-count-card { border-top: 4px solid #c8842a; }
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
        <span class="nav-link text-warning" style="font-weight:700;"><i class="fas fa-mug-hot" style="margin-right:6px;"></i>Kopi Kenangan Senja</span>
      </li>
    </ul>
    <ul class="navbar-nav ml-auto">
      <li class="nav-item">
        <span class="nav-link text-light"><i class="fas fa-user-circle" style="margin-right:4px;"></i><strong><?php echo $_SESSION['username']; ?></strong></span>
      </li>
      <li class="nav-item">
        <a class="nav-link text-warning" href="<?php echo BASE_URL; ?>/actions/logout.php" onclick="return konfirmasiLogout()"><i class="fas fa-sign-out-alt" style="margin-right:4px;"></i>Logout</a>
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
        <div class="image"><i class="fas fa-user-circle fa-2x" style="color:#c8842a;margin-left:8px;"></i></div>
        <div class="info">
          <a href="#" class="d-block" style="color:#f0c070;"><?php echo $_SESSION['username']; ?></a>
          <small style="color:#a08050;">Administrator</small>
        </div>
      </div>
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu">
          <li class="nav-item"><a href="<?php echo BASE_URL; ?>/pages/tentang.php" class="nav-link"><i class="nav-icon fas fa-info-circle"></i><p>Tentang Kami</p></a></li>
          <li class="nav-item"><a href="<?php echo BASE_URL; ?>/pages/data.php" class="nav-link"><i class="nav-icon fas fa-box"></i><p>Data Produk</p></a></li>
          <li class="nav-item"><a href="<?php echo BASE_URL; ?>/pages/menu.php" class="nav-link"><i class="nav-icon fas fa-star"></i><p>Ulasan</p></a></li>
          <li class="nav-item"><a href="<?php echo BASE_URL; ?>/pages/users.php" class="nav-link active" style="background:#c8842a;"><i class="nav-icon fas fa-users"></i><p>Users</p></a></li>
          <li class="nav-item mt-3"><a href="<?php echo BASE_URL; ?>/actions/logout.php" class="nav-link" onclick="return konfirmasiLogout()" style="color:#ff9966;"><i class="nav-icon fas fa-sign-out-alt"></i><p>Logout</p></a></li>
        </ul>
      </nav>
    </div>
  </aside>

  <div class="content-wrapper">
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0" style="color:#3d1c00;"><i class="fas fa-users" style="color:#c8842a;margin-right:8px;"></i>Users</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right"><li class="breadcrumb-item"><a href="<?php echo BASE_URL; ?>/pages/tentang.php">Home</a></li><li class="breadcrumb-item active">Users</li></ol>
          </div>
        </div>
      </div>
    </div>

    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-4 mb-3">
            <div class="info-box user-count-card">
              <span class="info-box-icon bg-success"><i class="fas fa-user"></i></span>
              <div class="info-box-content"><span class="info-box-text">Total Users</span><span class="info-box-number"><?php echo $totalUsers; ?></span></div>
            </div>
          </div>
          <div class="col-md-4 mb-3">
            <div class="info-box user-count-card">
              <span class="info-box-icon bg-warning"><i class="fas fa-user-shield"></i></span>
              <div class="info-box-content"><span class="info-box-text">Admin</span><span class="info-box-number"><?php echo $totalAdmin; ?></span></div>
            </div>
          </div>
          <div class="col-md-4 mb-3">
            <div class="info-box user-count-card">
              <span class="info-box-icon bg-info"><i class="fas fa-users"></i></span>
              <div class="info-box-content"><span class="info-box-text">Pelanggan</span><span class="info-box-number"><?php echo $totalCustomer; ?></span></div>
            </div>
          </div>
        </div>

        <div class="card card-outline">
          <div class="card-header" style="background:#fff7eb;">
            <h5 class="card-title mb-0"><i class="fas fa-table" style="color:#3d1c00;margin-right:8px;"></i>Daftar Users</h5>
          </div>
          <div class="card-body p-0">
            <div class="table-responsive">
              <table class="table table-hover user-table mb-0">
                <thead style="background:#3d1c00;color:#fff;">
                  <tr>
                    <th style="width:60px;">No</th>
                    <th>Username</th>
                    <th>Nama</th>
                    <th>Role</th>
                  </tr>
                </thead>
                <tbody>
                  <?php if (empty($users)): ?>
                    <tr><td colspan="4" class="text-center text-muted">Belum ada user terdaftar.</td></tr>
                  <?php else: ?>
                    <?php $no = 1; foreach ($users as $user): ?>
                      <tr>
                        <td><?php echo $no++; ?></td>
                        <td><?php echo htmlspecialchars($user['username']); ?></td>
                        <td><?php echo htmlspecialchars($user['nama']); ?></td>
                        <td>
                          <span class="user-role-badge <?php echo $user['role'] === 'admin' ? 'badge-admin' : 'badge-pelanggan'; ?>">
                            <?php echo htmlspecialchars(ucfirst($user['role'])); ?>
                          </span>
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
</script>
</body>
</html>
