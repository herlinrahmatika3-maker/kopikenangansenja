<?php
require_once __DIR__ . '/../config/constants.php';

require_once __DIR__ . '/../config/koneksi.php';
require_once __DIR__ . '/../includes/produk_setup.php';

setup_users_table($con);

$username = mysqli_real_escape_string($con, trim($_POST['username'] ?? ''));
$password = mysqli_real_escape_string($con, trim($_POST['password'] ?? ''));
$nama = mysqli_real_escape_string($con, trim($_POST['nama'] ?? ''));

if (!$username || !$password || !$nama) {
    header('Location: ' . BASE_URL . '/pages/daftar.php?error=1');
    exit;
}

$checkUser = mysqli_query($con, "SELECT username FROM users WHERE username='$username' LIMIT 1");
if ($checkUser && mysqli_num_rows($checkUser) > 0) {
    header('Location: ' . BASE_URL . '/pages/daftar.php?error=2');
    exit;
}

$query = "INSERT INTO users(username,password,nama,role) VALUES('$username','$password','$nama','pelanggan')";
$result = mysqli_query($con, $query);

if ($result) {
    header('Location: ' . BASE_URL . '/pages/login.php?daftar=sukses');
    exit;
}

header('Location: ' . BASE_URL . '/pages/daftar.php?error=3');
exit;

?>