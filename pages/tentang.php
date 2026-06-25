<?php
require_once __DIR__ . '/../config/constants.php';
require_once __DIR__ . '/../includes/admin_check.php';
?>


<!doctype html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Tentang Kami - Kopi Kenangan Senja</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
  <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/style.css">
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

  <!-- Navbar -->
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

  <!-- Sidebar -->
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
          <!-- PPT Slide 6: Menggunakan variabel $_SESSION['username'] -->
          <a href="#" class="d-block" style="color:#f0c070;"><?php echo $_SESSION['username']; ?></a>
          <small style="color:#a08050;">Administrator</small>
        </div>
      </div>
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu">
          <li class="nav-item">
            <a href="<?php echo BASE_URL; ?>/pages/tentang.php" class="nav-link active" style="background:#c8842a;">
              <i class="nav-icon fas fa-info-circle"></i>
              <p>Tentang Kami</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="<?php echo BASE_URL; ?>/pages/data.php" class="nav-link">
              <i class="nav-icon fas fa-box"></i>
              <p>Data Produk</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="<?php echo BASE_URL; ?>/pages/users.php" class="nav-link">
              <i class="nav-icon fas fa-users"></i>
              <p>Users</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="<?php echo BASE_URL; ?>/pages/menu.php" class="nav-link">
              <i class="nav-icon fas fa-star"></i>
              <p>Ulasan</p>
            </a>
          </li>
          <li class="nav-item mt-3">
            <a href="<?php echo BASE_URL; ?>/actions/logout.php" class="nav-link" onclick="return konfirmasiLogout()" style="color:#ff9966;">
              <i class="nav-icon fas fa-sign-out-alt"></i>
              <p>Logout</p>
            </a>
          </li>
        </ul>
      </nav>
    </div>
  </aside>

  <!-- Content Wrapper -->
  <div class="content-wrapper">
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0" style="color:#3d1c00;">
              <i class="fas fa-info-circle" style="color:#c8842a;margin-right:8px;"></i>Tentang Kami
            </h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item active">Tentang Kami</li>
            </ol>
          </div>
        </div>
      </div>
    </div>

    <section class="content">
      <div class="container-fluid">

        <!-- Hero -->
        <div class="card card-outline mb-4" style="border-top:4px solid #c8842a;">
          <div class="card-body text-center py-5" style="background:linear-gradient(135deg,#fff8f0,#ffe8cc);border-radius:8px;">
            <i class="fas fa-mug-hot fa-5x mb-3" style="color:#c8842a;"></i>
            <h2 style="color:#3d1c00;font-weight:700;">Kopi Kenangan Senja</h2>
            <p class="text-muted" style="font-size:18px;">Setiap tegukan menyimpan cerita</p>
            <span class="badge" style="background:#c8842a;font-size:13px;letter-spacing:2px;padding:8px 18px;">
              100% ARABICA COFFEE
            </span>
          </div>
        </div>

        <div class="row">
          <div class="col-md-6">
            <div class="card card-outline" style="border-top:4px solid #c8842a;">
              <div class="card-header">
                <h5 class="card-title mb-0">
                  <i class="fas fa-heart" style="color:#c8842a;margin-right:8px;"></i>Kisah Kami
                </h5>
              </div>
              <div class="card-body">
                <p>Kopi Kenangan Senja lahir dari kecintaan mendalam terhadap cita rasa kopi autentik Nusantara. Didirikan pada tahun 2020, kami berkomitmen menghadirkan pengalaman ngopi yang tak terlupakan di setiap cangkir.</p>
                <p>Kami percaya bahwa secangkir kopi yang sempurna bukan hanya soal rasa, tetapi juga tentang momen, suasana, dan kenangan yang tercipta bersamanya.</p>
              </div>
            </div>
          </div>
          <div class="col-md-6">
            <div class="card card-outline" style="border-top:4px solid #c8842a;">
              <div class="card-header">
                <h5 class="card-title mb-0">
                  <i class="fas fa-bullseye" style="color:#c8842a;margin-right:8px;"></i>Visi &amp; Misi
                </h5>
              </div>
              <div class="card-body">
                <p><strong>Visi:</strong> Menjadi kedai kopi pilihan utama yang menghadirkan kehangatan dan kenangan indah bagi setiap pelanggan.</p>
                <p><strong>Misi:</strong></p>
                <ul>
                  <li>Menyajikan kopi berkualitas tinggi dari biji pilihan</li>
                  <li>Menciptakan suasana yang nyaman dan hangat</li>
                  <li>Memberikan pelayanan terbaik dengan sepenuh hati</li>
                </ul>
              </div>
            </div>
          </div>
        </div>

        <!-- Statistik -->
        <div class="row">
          <div class="col-lg-3 col-6">
            <div class="small-box" style="background:linear-gradient(135deg,#c8842a,#a0601a);color:#fff;">
              <div class="inner"><h3>4+</h3><p>Varian Menu</p></div>
              <div class="icon"><i class="fas fa-coffee"></i></div>
            </div>
          </div>
          <div class="col-lg-3 col-6">
            <div class="small-box" style="background:linear-gradient(135deg,#3d1c00,#7a3b10);color:#fff;">
              <div class="inner"><h3>100%</h3><p>Arabica Bean</p></div>
              <div class="icon"><i class="fas fa-leaf"></i></div>
            </div>
          </div>
          <div class="col-lg-3 col-6">
            <div class="small-box" style="background:linear-gradient(135deg,#c8842a,#3d1c00);color:#fff;">
              <div class="inner"><h3>500+</h3><p>Pelanggan Setia</p></div>
              <div class="icon"><i class="fas fa-users"></i></div>
            </div>
          </div>
          <div class="col-lg-3 col-6">
            <div class="small-box" style="background:linear-gradient(135deg,#7a3b10,#c8842a);color:#fff;">
              <div class="inner"><h3>5&#9733;</h3><p>Rating Kami</p></div>
              <div class="icon"><i class="fas fa-star"></i></div>
            </div>
          </div>
        </div>

      </div>
    </section>
  </div>

  <footer class="main-footer text-center" style="background:#2d1200;color:#a08050;border-top:1px solid #5a2d00;">
    <strong>&copy; 2026 Kopi Kenangan Senja</strong> &mdash; All rights reserved.
  </footer>

</div><!-- /.wrapper -->

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
      if (result.isConfirmed) {
        window.location.href = '<?php echo BASE_URL; ?>/actions/logout.php';
      }
    });
    return false;
  }

  var params = new URLSearchParams(window.location.search);
  if (params.get('login') === 'sukses') {
    Swal.fire({
      icon: 'success',
      title: 'Selamat Datang!',
      text: 'Anda berhasil masuk ke sistem.',
      confirmButtonColor: '#c8842a',
      timer: 2000,
      timerProgressBar: true,
      showConfirmButton: false
    });
    history.replaceState(null, '', 'tentang.php');
  }
</script>
</body>
</html>
