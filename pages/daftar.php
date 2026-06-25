<?php require_once __DIR__ . '/../config/constants.php'; ?>
<!doctype html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Daftar - Kopi Kenangan Senja</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
  <style>
    body.login-page {
      background: linear-gradient(135deg, #1a0a00 0%, #3d1c00 40%, #6b3310 100%);
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
    }
    .login-box { width: 380px; }
    .login-logo { text-align: center; margin-bottom: 20px; }
    .login-logo .logo-icon { font-size: 60px; color: #c8842a; }
    .login-logo h1 { color: #fff; font-size: 26px; font-weight: 700; margin: 8px 0 0; }
    .login-logo h1 span { color: #c8842a; }
    .login-logo p { color: #c8a97a; font-size: 13px; letter-spacing: 3px; margin: 0; }
    .card.login-card {
      border-radius: 16px;
      box-shadow: 0 20px 60px rgba(0,0,0,0.5);
      border: none;
      background: rgba(255,255,255,0.97);
    }
    .card-body { padding: 35px 35px 25px; }
    .card-body h5 { color: #3d1c00; font-weight: 700; margin-bottom: 20px; font-size: 18px; }
    .form-control {
      border-radius: 8px; border: 1.5px solid #e0d0c0;
      padding: 10px 14px; font-size: 14px; transition: border-color 0.2s;
    }
    .form-control:focus { border-color: #c8842a; box-shadow: 0 0 0 3px rgba(200,132,42,0.15); }
    .input-group-text {
      border-radius: 8px 0 0 8px; background: #f5ede0;
      border: 1.5px solid #e0d0c0; border-right: none; color: #c8842a;
    }
    .input-group .form-control { border-radius: 0 8px 8px 0; border-left: none; }
    .btn-submit {
      background: linear-gradient(135deg, #c8842a, #a0601a);
      border: none; color: #fff; font-weight: 700; font-size: 15px;
      padding: 11px; border-radius: 8px; width: 100%; margin-top: 8px;
      cursor: pointer; transition: opacity 0.2s;
    }
    .btn-submit:hover { opacity: 0.90; }
    .icheck-primary label { color: #5a3a1a; font-size: 13px; cursor: pointer; }
    .icheck-primary input[type="checkbox"] {
      accent-color: #c8842a; width: 15px; height: 15px;
      cursor: pointer; margin-right: 6px; vertical-align: middle;
    }
    .login-footer { text-align: center; margin-top: 15px; color: #c8a97a; font-size: 12px; letter-spacing: 2px; }
  </style>
</head>
<body class="login-page">
<div class="login-box">

  <div class="login-logo">
    <div class="logo-icon"><i class="fas fa-mug-hot"></i></div>
    <h1>Kopi Kenangan <span>&#9829;</span></h1>
    <p>SENJA</p>
  </div>

  <div class="card login-card">
    <div class="card-body">
      <form method='POST' action='<?php echo BASE_URL; ?>/actions/prosesdaftar.php'>
        <input type="text" name="username" class="form-control mb-3" placeholder="Username" required>
        <input type="password" name="password" class="form-control mb-3" placeholder="Password" required>
        <input type="text" name="nama" class="form-control mb-3" placeholder="Nama Lengkap" required>
        <input type='submit' value='Daftar' class="btn-submit" >
      </form>
      <p class="mt-3 mb-0 text-center" style="font-size:13px;">Sudah punya akun? <a href="<?php echo BASE_URL; ?>/pages/login.php" class="text-decoration-none" style="color:#c8842a;">Login di sini</a></p>
    </div>
  </div>

  <p class="login-footer">100% ARABICA COFFEE &#9749;</p>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
  var params = new URLSearchParams(window.location.search);
  if (params.get('error') === '1') {
    Swal.fire({ icon: 'error', title: 'Gagal Daftar', text: 'Semua kolom wajib diisi.', confirmButtonColor: '#c8842a' });
  }
  if (params.get('error') === '2') {
    Swal.fire({ icon: 'warning', title: 'Username Sudah Terpakai', text: 'Silakan pilih username lain.', confirmButtonColor: '#c8842a' });
  }
  if (params.get('error') === '3') {
    Swal.fire({ icon: 'error', title: 'Gagal Menyimpan', text: 'Terjadi kesalahan, coba lagi.', confirmButtonColor: '#c8842a' });
  }
</script>
</body>
</html>
