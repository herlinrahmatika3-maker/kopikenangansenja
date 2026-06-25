<?php
require_once __DIR__ . '/../config/constants.php';
session_start();

// KONEKSI DATABASE
$conn = mysqli_connect("localhost","root","","kopikenangansenja");

if(!$conn){
    die("Koneksi gagal : " . mysqli_connect_error());
}

// AMBIL DATA DARI FORM LOGIN
$username = mysqli_real_escape_string($conn, @$_POST['username']);
$password = mysqli_real_escape_string($conn, @$_POST['password']);
$remember = @$_POST['remember'];

// CEK USER DI DATABASE
$query = mysqli_query($conn,
"SELECT * FROM users
WHERE username='$username'
AND password='$password'
LIMIT 1");

if ($query && mysqli_num_rows($query) > 0) {
    $user = mysqli_fetch_assoc($query);
    $_SESSION['username'] = $user['username'];
    $_SESSION['nama'] = $user['nama'];
    $_SESSION['role'] = $user['role'];

    if ($remember == 'Y') {
        setcookie('username', $username, time() + 86400, "/");
        setcookie('password', $password, time() + 86400, "/");
        setcookie('remember', 'Y', time() + 86400, "/");
    }

    if ($user['role'] === 'admin') {
        header('Location: ' . BASE_URL . '/pages/tentang.php?login=sukses');
    } else {
        header('Location: ' . BASE_URL . '/pages/pelanggan.php?login=sukses');
    }
    exit;
}

// fallback admin user
if ($username === 'admin' && $password === 'admin123') {
    $_SESSION['username'] = 'admin';
    $_SESSION['nama'] = 'Administrator';
    $_SESSION['role'] = 'admin';

    if ($remember == 'Y') {
        setcookie('username', $username, time() + 86400, "/");
        setcookie('password', $password, time() + 86400, "/");
        setcookie('remember', 'Y', time() + 86400, "/");
    }

    header('Location: ' . BASE_URL . '/pages/tentang.php?login=sukses');
    exit;
}

header('Location: ' . BASE_URL . '/pages/login.php?error=1');
?>