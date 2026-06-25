<?php
require_once __DIR__ . '/../config/constants.php';
require_once __DIR__ . '/../includes/customer_check.php';
require_once __DIR__ . '/../config/koneksi.php';
require_once __DIR__ . '/../includes/produk_setup.php';

$cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
$products = [];
$cartTotal = 0;

if (!empty($cart)) {
    $ids = array_keys($cart);
    $idsList = implode(',', array_map('intval', $ids));
    $query = mysqli_query($con, "SELECT * FROM produk WHERE id IN ($idsList)");
    while ($row = mysqli_fetch_assoc($query)) {
        $products[$row['id']] = $row;
    }
}
?>
<!doctype html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Keranjang - Kopi Kenangan Senja</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
  <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/style.css">
  <style>
    .product-thumb { width:52px; height:52px; object-fit:cover; border-radius:8px; border:2px solid #e9d8c0; }
    .icon-thumb { width:52px; height:52px; border-radius:8px; display:flex; align-items:center; justify-content:center; font-size:22px; }
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
        <a href="<?php echo BASE_URL; ?>/pages/keranjang.php" class="nav-link"><i class="fas fa-shopping-cart" style="margin-right:6px;"></i>Keranjang</a>
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
            <h1 class="m-0" style="color:#3d1c00;"><i class="fas fa-shopping-cart" style="color:#c8842a;margin-right:8px;"></i>Keranjang Belanja</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="<?php echo BASE_URL; ?>/pages/store.php">Toko</a></li>
              <li class="breadcrumb-item active">Keranjang</li>
            </ol>
          </div>
        </div>
      </div>
    </div>

    <section class="content">
      <div class="container-fluid">
        <div class="card card-outline" style="border-top:4px solid #c8842a;">
          <div class="card-header">
            <h5 class="card-title mb-0">Isi Keranjang</h5>
          </div>
          <div class="card-body p-0">
            <?php if (empty($cart) || empty($products)): ?>
              <div class="p-4 text-center text-muted">
                <i class="fas fa-shopping-cart fa-3x mb-3" style="color:#ddd;"></i>
                <p>Keranjang Anda masih kosong.</p>
                <a href="<?php echo BASE_URL; ?>/pages/store.php" class="btn btn-warning">Mulai Belanja</a>
              </div>
            <?php else: ?>
              <div class="table-responsive">
                <table class="table table-hover mb-0">
                  <thead style="background:#3d1c00;color:#fff;">
                    <tr>
                      <th>Foto</th>
                      <th>Produk</th>
                      <th>Harga</th>
                      <th>Jumlah</th>
                      <th>Subtotal</th>
                      <th class="text-center">Aksi</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach ($cart as $id => $qty):
                      if (!isset($products[$id])) { continue; }
                      $product = $products[$id];
                      $subtotal = $qty * $product['harga'];
                      $cartTotal += $subtotal;
                    ?>
                    <tr>
                      <td>
                        <?php if (!empty($product['foto']) && file_exists(__DIR__ . '/../uploads/produk/' . $product['foto'])): ?>
                          <img src="<?php echo BASE_URL; ?>/uploads/produk/<?php echo htmlspecialchars($product['foto']); ?>" class="product-thumb" alt="">
                        <?php else: ?>
                          <div class="icon-thumb" style="background:<?php echo htmlspecialchars($product['warna']); ?>22;">
                            <i class="<?php echo htmlspecialchars($product['icon']); ?>" style="color:<?php echo htmlspecialchars($product['warna']); ?>;"></i>
                          </div>
                        <?php endif; ?>
                      </td>
                      <td>
                        <strong><?php echo htmlspecialchars($product['nama']); ?></strong><br>
                        <small class="text-muted"><?php echo htmlspecialchars($product['deskripsi']); ?></small>
                      </td>
                      <td>Rp <?php echo number_format($product['harga'], 0, ',', '.'); ?></td>
                      <td>
                        <form method="POST" action="<?php echo BASE_URL; ?>/actions/proses_keranjang.php" class="d-flex align-items-center">
                          <input type="hidden" name="action" value="update">
                          <input type="hidden" name="id" value="<?php echo $product['id']; ?>">
                          <input type="number" name="qty" value="<?php echo $qty; ?>" min="1" max="<?php echo (int)$product['stok']; ?>" class="form-control form-control-sm" style="width:80px;">
                          <button type="submit" class="btn btn-sm btn-primary ml-2">Update</button>
                        </form>
                      </td>
                      <td>Rp <?php echo number_format($subtotal, 0, ',', '.'); ?></td>
                      <td class="text-center">
                        <a href="<?php echo BASE_URL; ?>/actions/proses_keranjang.php?action=remove&id=<?php echo $product['id']; ?>" class="btn btn-sm btn-danger">Hapus</a>
                      </td>
                    </tr>
                    <?php endforeach; ?>
                  </tbody>
                </table>
              </div>
              <div class="p-4 border-top d-flex justify-content-between align-items-center">
                <div><strong>Total Pembayaran:</strong></div>
                <div><strong style="font-size:20px;color:#c8842a;">Rp <?php echo number_format($cartTotal, 0, ',', '.'); ?></strong></div>
              </div>
              <div class="p-4 border-top text-right">
                <a href="<?php echo BASE_URL; ?>/pages/store.php" class="btn btn-secondary mr-2"><i class="fas fa-arrow-left"></i> Lanjut Belanja</a>
                <button type="button" class="btn btn-success" onclick="konfirmasiCheckout(<?php echo $cartTotal; ?>)">
                  <i class="fas fa-cash-register"></i> Checkout & Bayar
                </button>
              </div>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </section>
  </div>

  <footer class="main-footer text-center" style="background:#2d1200;color:#a08050;border-top:1px solid #5a2d00;">
    <strong>&copy; 2026 Kopi Kenangan Senja</strong> &mdash; All rights reserved.
  </footer>
</div>

<!-- Modal Checkout -->
<div class="modal fade" id="modalCheckout" tabindex="-1">
  <div class="modal-dialog modal-md">
    <div class="modal-content">
      <div class="modal-header" style="background:#3d1c00;color:#fff;">
        <h5 class="modal-title"><i class="fas fa-cash-register mr-2"></i>Konfirmasi Checkout</h5>
        <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
      </div>
      <form action="<?php echo BASE_URL; ?>/actions/proses_keranjang.php" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="action" value="checkout">
        <div class="modal-body">
          <div class="mb-3">
            <label><strong>Total yang harus dibayar:</strong></label>
            <div class="h4 text-warning" id="totalBayar">-</div>
          </div>
          <div class="mb-3">
            <label>Metode Pembayaran</label>
            <select name="metode_bayar" class="form-control" id="metodeBayar" onchange="toggleBukti()">
              <option value="tunai">💵 Tunai (Bayar di Kasir)</option>
              <option value="transfer">🏦 Transfer Bank</option>
              <option value="qris">📱 QRIS</option>
              <option value="ewallet">💳 E-Wallet</option>
            </select>
          </div>
          <div id="buktiSection" style="display:none;">
            <div class="mb-3">
              <label><i class="fas fa-image mr-1"></i>Upload Bukti Pembayaran</label>
              <div class="custom-file">
                <input type="file" class="custom-file-input" id="buktiBayar" name="bukti_bayar" accept="image/*" onchange="previewBukti(this)">
                <label class="custom-file-label" for="buktiBayar">Pilih foto bukti transfer...</label>
              </div>
              <small class="text-muted">Format: JPG, PNG. Maks 2MB.</small>
            </div>
            <div id="previewBuktiContainer" style="display:none;">
              <img id="previewBuktiImg" src="" alt="Bukti Bayar" style="max-width:100%;max-height:200px;border-radius:8px;border:1px solid #ddd;padding:4px;">
            </div>
          </div>
          <div class="alert alert-info mb-0">
            <i class="fas fa-info-circle mr-1"></i>
            Setelah checkout, stok produk akan berkurang dan Anda akan mendapatkan <strong>nomor antrian</strong> beserta struk pembayaran.
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-success"><i class="fas fa-check mr-1"></i>Konfirmasi Checkout</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.4/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function konfirmasiCheckout(total) {
  var fmt = 'Rp ' + total.toLocaleString('id-ID');
  document.getElementById('totalBayar').innerText = fmt;
  $('#modalCheckout').modal('show');
}
function toggleBukti() {
  var metode = document.getElementById('metodeBayar').value;
  var buktiSection = document.getElementById('buktiSection');
  if (metode === 'transfer' || metode === 'qris' || metode === 'ewallet') {
    buktiSection.style.display = 'block';
  } else {
    buktiSection.style.display = 'none';
  }
}
function previewBukti(input) {
  if (input.files && input.files[0]) {
    var reader = new FileReader();
    reader.onload = function(e) {
      document.getElementById('previewBuktiImg').src = e.target.result;
      document.getElementById('previewBuktiContainer').style.display = 'block';
    };
    reader.readAsDataURL(input.files[0]);
    document.querySelector('.custom-file-label').innerText = input.files[0].name;
  }
}
</script>
</body>
</html>
