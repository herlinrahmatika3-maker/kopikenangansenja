<?php
require_once __DIR__ . '/../config/constants.php';
require_once __DIR__ . '/../includes/admin_check.php';
require_once __DIR__ . '/../config/koneksi.php';
require_once __DIR__ . '/../includes/produk_setup.php';

$pesan = isset($_GET['pesan']) ? $_GET['pesan'] : '';
$produkQuery = mysqli_query($con, "SELECT * FROM produk ORDER BY id DESC");
$produk = [];
while ($row = mysqli_fetch_assoc($produkQuery)) {
    $produk[] = $row;
}
?>
<!doctype html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Data Produk - Kopi Kenangan Senja</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
  <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/style.css">
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
            <a href="<?php echo BASE_URL; ?>/pages/data.php" class="nav-link active" style="background:#c8842a;">
              <i class="nav-icon fas fa-box"></i><p>Data Produk</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="<?php echo BASE_URL; ?>/pages/users.php" class="nav-link">
              <i class="nav-icon fas fa-users"></i><p>Users</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="<?php echo BASE_URL; ?>/pages/menu.php" class="nav-link">
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
              <i class="fas fa-box" style="color:#c8842a;margin-right:8px;"></i>Data Produk
            </h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="<?php echo BASE_URL; ?>/pages/tentang.php">Home</a></li>
              <li class="breadcrumb-item active">Data Produk</li>
            </ol>
          </div>
        </div>
      </div>
    </div>

    <section class="content">
      <div class="container-fluid">

        <div class="row mb-3">
          <div class="col-md-3 col-6">
            <div class="info-box" style="border-left:4px solid #c8842a;">
              <span class="info-box-icon" style="background:#c8842a;color:#fff;"><i class="fas fa-box"></i></span>
              <div class="info-box-content">
                <span class="info-box-text">Total Produk</span>
                <span class="info-box-number"><?php echo count($produk); ?></span>
              </div>
            </div>
          </div>
          <div class="col-md-3 col-6">
            <div class="info-box" style="border-left:4px solid #3d1c00;">
              <span class="info-box-icon" style="background:#3d1c00;color:#fff;"><i class="fas fa-boxes"></i></span>
              <div class="info-box-content">
                <span class="info-box-text">Total Stok</span>
                <span class="info-box-number">
                  <?php
                    $total_stok = 0;
                    foreach ($produk as $p) { $total_stok += $p['stok']; }
                    echo $total_stok;
                  ?>
                </span>
              </div>
            </div>
          </div>
        </div>

        <div class="row mb-3">
          <div class="col-12 text-right">
            <a href="<?php echo BASE_URL; ?>/pages/produk_form.php" class="btn btn-warning">
              <i class="fas fa-plus"></i> Tambah Produk
            </a>
          </div>
        </div>

        <div class="card card-outline" style="border-top:4px solid #c8842a;">
          <div class="card-header">
            <h5 class="card-title mb-0">
              <i class="fas fa-list" style="color:#c8842a;margin-right:8px;"></i>Daftar Menu Kopi
            </h5>
          </div>
          <div class="card-body p-0">
            <div class="table-responsive">
              <table class="table table-hover mb-0">
                <thead style="background:#3d1c00;color:#fff;">
                  <tr>
                    <th class="text-center" width="50">No</th>
                    <th>Foto</th>
                    <th>Nama Produk</th>
                    <th>Deskripsi</th>
                    <th class="text-center">Harga</th>
                    <th class="text-center">Stok</th>
                    <th class="text-center">Status</th>
                    <th class="text-center">Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  $no = 1;
                  foreach ($produk as $p):
                  ?>
                  <tr>
                    <td class="text-center align-middle"><?php echo $no++; ?></td>
                    <td class="align-middle">
                      <?php if (!empty($p['foto']) && file_exists(__DIR__ . '/../uploads/produk/' . $p['foto'])): ?>
                        <img src="<?php echo BASE_URL; ?>/uploads/produk/<?php echo htmlspecialchars($p['foto']); ?>" style="width:52px;height:52px;object-fit:cover;border-radius:8px;border:2px solid #e9d8c0;" alt="">
                      <?php else: ?>
                        <div style="width:52px;height:52px;border-radius:8px;background:<?php echo $p['warna']; ?>22;display:flex;align-items:center;justify-content:center;">
                          <i class="<?php echo $p['icon']; ?>" style="color:<?php echo $p['warna']; ?>;font-size:20px;"></i>
                        </div>
                      <?php endif; ?>
                    </td>
                    <td class="align-middle">
                      <strong><?php echo $p['nama']; ?></strong>
                    </td>
                    <td class="align-middle text-muted" style="font-size:13px;"><?php echo $p['deskripsi']; ?></td>
                    <td class="text-center align-middle">
                      <span class="badge" style="background:#c8842a;color:#fff;font-size:13px;">
                        Rp <?php echo number_format($p['harga'], 0, ',', '.'); ?>
                      </span>
                    </td>
                    <td class="text-center align-middle" style="font-weight:bold;"><?php echo $p['stok']; ?></td>
                    <td class="text-center align-middle">
                      <?php if ($p['stok'] > 10): ?>
                        <span class="badge badge-success">Tersedia</span>
                      <?php else: ?>
                        <span class="badge badge-warning">Hampir Habis</span>
                      <?php endif; ?>
                    </td>
                    <td class="text-center align-middle">
                      <a href="<?php echo BASE_URL; ?>/pages/produk_form.php?id=<?php echo $p['id']; ?>" class="btn btn-sm btn-info">Edit</a>
                      <a href="<?php echo BASE_URL; ?>/actions/proses_produk.php?hapus=<?php echo $p['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Hapus produk ini?')">Hapus</a>
                    </td>
                  </tr>
                  <?php endforeach; ?>
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

  const pesan = '<?php echo $pesan; ?>';
  if (pesan === 'tambah') {
    Swal.fire({ icon: 'success', title: 'Berhasil', text: 'Produk berhasil ditambahkan.', confirmButtonColor: '#c8842a' });
  }
  if (pesan === 'edit') {
    Swal.fire({ icon: 'success', title: 'Berhasil', text: 'Produk berhasil diperbarui.', confirmButtonColor: '#c8842a' });
  }
  if (pesan === 'hapus') {
    Swal.fire({ icon: 'success', title: 'Berhasil', text: 'Produk berhasil dihapus.', confirmButtonColor: '#c8842a' });
  }
</script>
</body>
</html>
