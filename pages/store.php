<?php
require_once __DIR__ . '/../config/constants.php';
require_once __DIR__ . '/../includes/customer_check.php';
require_once __DIR__ . '/../config/koneksi.php';
require_once __DIR__ . '/../includes/produk_setup.php';

$cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
$cartCount = 0;
foreach ($cart as $qty) { $cartCount += $qty; }

$products = [];
$productQuery = mysqli_query($con, "SELECT * FROM produk ORDER BY id DESC");
while ($row = mysqli_fetch_assoc($productQuery)) {
    $products[] = $row;
}

$ulasan = [];
$ulasanQuery = mysqli_query($con, "SELECT * FROM ulasan ORDER BY created_at DESC LIMIT 4");
if (!$ulasanQuery) {
    $ulasanQuery = mysqli_query($con, "SELECT * FROM ulasan ORDER BY id DESC LIMIT 4");
}
if ($ulasanQuery) {
    while ($row = mysqli_fetch_assoc($ulasanQuery)) {
        $ulasan[] = $row;
    }
}

$pesan = isset($_GET['pesan']) ? $_GET['pesan'] : '';
?>
<!doctype html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Toko - Kopi Kenangan Senja</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
  <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/style.css">
  <style>
    .product-card { border-left: 4px solid #3d1c00; }
    .product-card:hover { transform: translateY(-2px); box-shadow: 0 10px 24px rgba(0,0,0,0.08); }
    .review-card { border-left: 4px solid #c8842a; }
    .top-nav .nav-link { color: #fff; }
    .product-img { width: 60px; height: 60px; object-fit: cover; border-radius: 8px; border: 2px solid #e9d8c0; }
    .product-icon-box { width:60px; height:60px; border-radius:8px; display:flex; align-items:center; justify-content:center; font-size:24px; }
  </style>
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">
  <nav class="main-header navbar navbar-expand navbar-dark" style="background:linear-gradient(90deg,#3d1c00,#7a3b10);">
    <ul class="navbar-nav">
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
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-3">
          <div class="col-sm-6">
            <h1 class="m-0" style="color:#3d1c00;"><i class="fas fa-store" style="color:#c8842a;margin-right:8px;"></i>Toko Pelanggan</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item active">Toko</li>
            </ol>
          </div>
        </div>
      </div>
    </div>

    <section class="content">
      <div class="container-fluid">
        <div class="row mb-3">
          <div class="col-lg-4 col-sm-6 mb-3">
            <div class="small-box" style="background:linear-gradient(135deg,#3d1c00,#7a3b10);color:#fff;">
              <div class="inner"><h3><?php echo count($products); ?></h3><p>Produk Tersedia</p></div>
              <div class="icon"><i class="fas fa-box-open"></i></div>
            </div>
          </div>
          <div class="col-lg-4 col-sm-6 mb-3">
            <div class="small-box" style="background:linear-gradient(135deg,#c8842a,#a0601a);color:#fff;">
              <div class="inner"><h3><?php echo $cartCount; ?></h3><p>Jumlah Barang di Keranjang</p></div>
              <div class="icon"><i class="fas fa-shopping-cart"></i></div>
            </div>
          </div>
          <div class="col-lg-4 col-sm-12 mb-3">
            <div class="small-box" style="background:linear-gradient(135deg,#f0a500,#f8c849);color:#fff;">
              <div class="inner"><h3><?php echo htmlspecialchars($_SESSION['nama'] ?? $_SESSION['username']); ?></h3><p>Selamat datang!</p></div>
              <div class="icon"><i class="fas fa-smile"></i></div>
            </div>
          </div>
        </div>

        <div class="row">
          <!-- Etalase Produk dengan foto -->
          <div class="col-12 mb-4">
            <div class="card card-outline product-card">
              <div class="card-header">
                <h5 class="card-title mb-0"><i class="fas fa-coffee" style="color:#3d1c00;margin-right:8px;"></i>Etalase Menu Kopi</h5>
              </div>
              <div class="card-body">
                <div class="row">
                  <?php foreach ($products as $product): ?>
                  <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                    <div class="card h-100 shadow-sm" style="border-top:3px solid <?php echo htmlspecialchars($product['warna']); ?>;">
                      <!-- Foto Produk -->
                      <div style="height:180px; overflow:hidden; background:#fdf6ee; display:flex; align-items:center; justify-content:center;">
                        <?php if (!empty($product['foto']) && file_exists(__DIR__ . '/../uploads/produk/' . $product['foto'])): ?>
                          <img src="<?php echo BASE_URL; ?>/uploads/produk/<?php echo htmlspecialchars($product['foto']); ?>" alt="<?php echo htmlspecialchars($product['nama']); ?>" style="width:100%;height:100%;object-fit:cover;">
                        <?php else: ?>
                          <div style="text-align:center;">
                            <i class="<?php echo htmlspecialchars($product['icon']); ?> fa-3x" style="color:<?php echo htmlspecialchars($product['warna']); ?>;"></i>
                            <div style="font-size:11px;color:#aaa;margin-top:6px;">Belum ada foto</div>
                          </div>
                        <?php endif; ?>
                      </div>
                      <div class="card-body d-flex flex-column p-3">
                        <h6 class="font-weight-bold mb-1" style="color:#3d1c00;"><?php echo htmlspecialchars($product['nama']); ?></h6>
                        <p class="text-muted mb-2" style="font-size:12px;flex:1;"><?php echo htmlspecialchars($product['deskripsi']); ?></p>
                        <div class="d-flex justify-content-between align-items-center mb-2">
                          <span class="font-weight-bold" style="color:#c8842a;">Rp <?php echo number_format($product['harga'], 0, ',', '.'); ?></span>
                          <span class="badge <?php echo $product['stok'] > 0 ? 'badge-success' : 'badge-danger'; ?>">
                            Stok: <?php echo (int)$product['stok']; ?>
                          </span>
                        </div>
                        <?php if ($product['stok'] > 0): ?>
                        <form method="POST" action="<?php echo BASE_URL; ?>/actions/proses_keranjang.php" class="d-flex">
                          <input type="hidden" name="action" value="add">
                          <input type="hidden" name="id" value="<?php echo $product['id']; ?>">
                          <input type="number" name="qty" value="1" min="1" max="<?php echo (int)$product['stok']; ?>" class="form-control form-control-sm mr-2" style="width:65px;">
                          <button type="submit" class="btn btn-sm btn-warning flex-fill"><i class="fas fa-cart-plus"></i> Tambah</button>
                        </form>
                        <?php else: ?>
                          <button class="btn btn-sm btn-secondary w-100" disabled><i class="fas fa-times"></i> Habis</button>
                        <?php endif; ?>
                      </div>
                    </div>
                  </div>
                  <?php endforeach; ?>
                </div>
              </div>
            </div>
          </div>
        </div>

          <div class="col-lg-4">
            <div class="card card-outline review-card mb-4">
              <div class="card-header">
                <h5 class="card-title mb-0"><i class="fas fa-star" style="color:#c8842a;margin-right:8px;"></i>Ulasan Terbaru</h5>
              </div>
              <div class="card-body">
                <?php if (empty($ulasan)): ?>
                  <p class="text-muted">Belum ada ulasan.</p>
                <?php endif; ?>
                <?php foreach ($ulasan as $u): ?>
                  <div class="mb-3">
                    <div class="d-flex justify-content-between mb-2">
                      <strong><?php echo htmlspecialchars($u['nama'] ?? $u['nama_pengguna'] ?? 'Anonim'); ?></strong>
                      <span class="badge badge-warning"><?php echo str_repeat('⭐', $u['rating']); ?></span>
                    </div>
                    <div class="text-muted" style="font-size:13px; margin-bottom:6px;">
                      <?php echo htmlspecialchars($u['menu'] ?? 'Menu'); ?> • <?php echo date('d M Y', strtotime($u['created_at'])); ?>
                    </div>
                    <p class="mb-0" style="font-size:14px; color:#555;"><?php echo htmlspecialchars($u['komentar']); ?></p>
                  </div>
                <?php endforeach; ?>
              </div>
            </div>
          </div>
        </div>

        <!-- Form Ulasan -->
        <div class="row">
          <div class="col-12">
            <div class="card card-outline product-card" id="review-form">
              <div class="card-header">
                <h5 class="card-title mb-0"><i class="fas fa-pen" style="color:#3d1c00;margin-right:8px;"></i>Berikan Ulasan</h5>
              </div>
              <div class="card-body">
                <form method="POST" action="<?php echo BASE_URL; ?>/actions/proses_ulasan.php">
                  <input type="hidden" name="return" value="store.php">
                  <div class="row">
                    <div class="col-md-4 mb-3">
                      <label>Nama</label>
                      <input type="text" name="nama" class="form-control" value="<?php echo htmlspecialchars($_SESSION['nama'] ?? $_SESSION['username']); ?>" required>
                    </div>
                    <div class="col-md-4 mb-3">
                      <label>Menu Favorit</label>
                      <?php if (!empty($products)): ?>
                        <select name="menu" class="form-control" required>
                          <option value="">Pilih menu favorit</option>
                          <?php foreach ($products as $product): ?>
                            <option value="<?php echo htmlspecialchars($product['nama']); ?>"><?php echo htmlspecialchars($product['nama']); ?></option>
                          <?php endforeach; ?>
                        </select>
                      <?php else: ?>
                        <input type="text" name="menu" class="form-control" placeholder="Contoh: Latte" required>
                      <?php endif; ?>
                    </div>
                    <div class="col-md-4 mb-3">
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
                  </div>
                  <div class="form-group mb-3">
                    <label>Komentar</label>
                    <textarea name="komentar" class="form-control" rows="4" placeholder="Tulis komentar Anda..." required></textarea>
                  </div>
                  <button type="submit" class="btn btn-warning"><i class="fas fa-paper-plane"></i> Kirim Ulasan</button>
                </form>
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
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
  if ('<?php echo $pesan; ?>' === 'cart') {
    Swal.fire({ icon: 'success', title: 'Berhasil', text: 'Produk ditambahkan ke keranjang.' });
  }
  if ('<?php echo $pesan; ?>' === 'checkout') {
    Swal.fire({ icon: 'success', title: 'Berhasil', text: 'Checkout berhasil dilakukan.' });
  }
  if ('<?php echo $pesan; ?>' === 'ulasan') {
    Swal.fire({ icon: 'success', title: 'Berhasil', text: 'Ulasan Anda sudah terkirim.' });
  }
</script>
</body>
</html>
