<?php
require_once __DIR__ . '/../config/constants.php';
require_once __DIR__ . '/../includes/admin_check.php';
require_once __DIR__ . '/../config/koneksi.php';
require_once __DIR__ . '/../includes/produk_setup.php';

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$mode = 'tambah';
$produk = [
    'nama' => '',
    'harga' => '',
    'stok' => '',
    'deskripsi' => '',
    'icon' => 'fas fa-mug-hot',
    'warna' => '#a0601a',
    'foto' => '',
];

if ($id > 0) {
    $query = mysqli_query($con, "SELECT * FROM produk WHERE id = $id LIMIT 1");
    if ($row = mysqli_fetch_assoc($query)) {
        $produk = $row;
        $mode = 'edit';
    } else {
        header('Location: data.php');
        exit;
    }
}

$pageTitle = $mode === 'edit' ? 'Edit Produk' : 'Tambah Produk Baru';
$buttonLabel = $mode === 'edit' ? 'Simpan Perubahan' : 'Tambah Produk';
?>
<!doctype html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title><?php echo $pageTitle; ?> - Kopi Kenangan Senja</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
  <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/style.css">
  <style>
    .foto-preview-box {
      width: 160px; height: 160px;
      border: 2px dashed #c8842a;
      border-radius: 12px;
      display: flex; align-items: center; justify-content: center;
      cursor: pointer; overflow: hidden; background: #fdf6ee;
      transition: border-color .2s;
    }
    .foto-preview-box:hover { border-color: #3d1c00; }
    .foto-preview-box img { width: 100%; height: 100%; object-fit: cover; }
    .foto-placeholder { text-align: center; color: #c8842a; }
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
          <li class="nav-item"><a href="<?php echo BASE_URL; ?>/pages/tentang.php" class="nav-link"><i class="nav-icon fas fa-info-circle"></i><p>Tentang Kami</p></a></li>
          <li class="nav-item"><a href="<?php echo BASE_URL; ?>/pages/data.php" class="nav-link active"><i class="nav-icon fas fa-box"></i><p>Data Produk</p></a></li>
          <li class="nav-item"><a href="<?php echo BASE_URL; ?>/pages/users.php" class="nav-link"><i class="nav-icon fas fa-users"></i><p>Users</p></a></li>
          <li class="nav-item"><a href="<?php echo BASE_URL; ?>/pages/menu.php" class="nav-link"><i class="nav-icon fas fa-star"></i><p>Ulasan</p></a></li>
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
            <h1 class="m-0" style="color:#3d1c00;">
              <i class="fas fa-box" style="color:#c8842a;margin-right:8px;"></i><?php echo $pageTitle; ?>
            </h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="<?php echo BASE_URL; ?>/pages/tentang.php">Home</a></li>
              <li class="breadcrumb-item"><a href="<?php echo BASE_URL; ?>/pages/data.php">Data Produk</a></li>
              <li class="breadcrumb-item active"><?php echo $pageTitle; ?></li>
            </ol>
          </div>
        </div>
      </div>
    </div>

    <section class="content">
      <div class="container-fluid">
        <div class="card card-outline" style="border-top:4px solid #c8842a;">
          <div class="card-header">
            <h5 class="card-title mb-0"><i class="fas fa-edit" style="color:#c8842a;margin-right:8px;"></i><?php echo $pageTitle; ?></h5>
          </div>
          <div class="card-body">
            <form action="<?php echo BASE_URL; ?>/actions/proses_produk.php" method="POST" enctype="multipart/form-data">
              <input type="hidden" name="action" value="<?php echo $mode; ?>">
              <?php if ($mode === 'edit'): ?>
                <input type="hidden" name="id" value="<?php echo $produk['id']; ?>">
                <input type="hidden" name="foto_lama" value="<?php echo htmlspecialchars($produk['foto'] ?? ''); ?>">
              <?php endif; ?>

              <!-- Foto Produk -->
              <div class="form-group mb-3">
                <label><i class="fas fa-image" style="color:#c8842a;margin-right:6px;"></i>Foto Produk</label>
                <div class="d-flex align-items-start">
                  <div>
                    <div class="foto-preview-box" id="fotoPreviewBox" onclick="document.getElementById('fotoInput').click()">
                      <?php if (!empty($produk['foto']) && file_exists(__DIR__ . '/../uploads/produk/' . $produk['foto'])): ?>
                        <img src="<?php echo BASE_URL; ?>/uploads/produk/<?php echo htmlspecialchars($produk['foto']); ?>" id="fotoPreview" alt="Foto Produk">
                      <?php else: ?>
                        <div class="foto-placeholder" id="fotoPlaceholder">
                          <i class="fas fa-camera fa-2x mb-1"></i><br>
                          <small>Klik untuk upload foto</small>
                        </div>
                        <img src="" id="fotoPreview" alt="" style="display:none;">
                      <?php endif; ?>
                    </div>
                    <input type="file" name="foto" id="fotoInput" accept="image/*" style="display:none;" onchange="previewFoto(this)">
                    <small class="text-muted d-block mt-1">Format: JPG, PNG, GIF. Maks 2MB.</small>
                    <?php if (!empty($produk['foto'])): ?>
                      <div class="form-check mt-1">
                        <input type="checkbox" name="hapus_foto" value="1" class="form-check-input" id="hapusFoto">
                        <label class="form-check-label text-danger" for="hapusFoto">Hapus foto</label>
                      </div>
                    <?php endif; ?>
                  </div>
                </div>
              </div>

              <div class="form-group mb-3">
                <label>Nama Produk</label>
                <input type="text" name="nama" class="form-control" value="<?php echo htmlspecialchars($produk['nama']); ?>" required>
              </div>

              <div class="row">
                <div class="col-md-4 mb-3">
                  <label>Harga (Rp)</label>
                  <input type="number" name="harga" class="form-control" value="<?php echo htmlspecialchars($produk['harga']); ?>" min="0" required>
                </div>
                <div class="col-md-4 mb-3">
                  <label>Stok</label>
                  <input type="number" name="stok" class="form-control" value="<?php echo htmlspecialchars($produk['stok']); ?>" min="0" required>
                </div>
                <div class="col-md-4 mb-3">
                  <label>Warna Ikon</label>
                  <input type="color" name="warna" class="form-control" value="<?php echo htmlspecialchars($produk['warna']); ?>" required>
                </div>
              </div>

              <div class="form-group mb-3">
                <label>Ikon</label>
                <input type="text" name="icon" class="form-control" value="<?php echo htmlspecialchars($produk['icon']); ?>" placeholder="Contoh: fas fa-coffee" required>
                <small class="form-text text-muted">Gunakan kelas FontAwesome.</small>
              </div>

              <div class="form-group mb-4">
                <label>Deskripsi</label>
                <textarea name="deskripsi" class="form-control" rows="4" required><?php echo htmlspecialchars($produk['deskripsi']); ?></textarea>
              </div>

              <button type="submit" class="btn btn-warning"><i class="fas fa-save"></i> <?php echo $buttonLabel; ?></button>
              <a href="<?php echo BASE_URL; ?>/pages/data.php" class="btn btn-secondary ml-2">Batal</a>
            </form>
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
  function previewFoto(input) {
    if (input.files && input.files[0]) {
      var reader = new FileReader();
      reader.onload = function(e) {
        document.getElementById('fotoPreview').src = e.target.result;
        document.getElementById('fotoPreview').style.display = 'block';
        var ph = document.getElementById('fotoPlaceholder');
        if (ph) ph.style.display = 'none';
      };
      reader.readAsDataURL(input.files[0]);
    }
  }
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
