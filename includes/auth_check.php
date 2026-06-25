<?php

session_start();
require_once __DIR__ . '/../config/constants.php';
require_once __DIR__ . '/../config/koneksi.php';
require_once __DIR__ . '/produk_setup.php';

if (isset($_SESSION['username']) && !isset($_SESSION['role'])) {
    $safeUser = mysqli_real_escape_string($con, $_SESSION['username']);
    $userQuery = mysqli_query($con, "SELECT * FROM users WHERE username='$safeUser' LIMIT 1");
    if ($userQuery && mysqli_num_rows($userQuery) > 0) {
        $user = mysqli_fetch_assoc($userQuery);
        $_SESSION['role'] = $user['role'];
        $_SESSION['nama'] = $user['nama'];
    }
}

if (!isset($_SESSION['username'])) {
    if (isset($_COOKIE['username']) && isset($_COOKIE['password']) && isset($_COOKIE['remember']) && $_COOKIE['remember'] == 'Y') {
        $cookieUser = $_COOKIE['username'];
        $cookiePass = $_COOKIE['password'];

        $safeUser = mysqli_real_escape_string($con, $cookieUser);
        $safePass = mysqli_real_escape_string($con, $cookiePass);
        $userQuery = mysqli_query($con, "SELECT * FROM users WHERE username='$safeUser' AND password='$safePass' LIMIT 1");

        if ($userQuery && mysqli_num_rows($userQuery) > 0) {
            $user = mysqli_fetch_assoc($userQuery);
            $_SESSION['username'] = $user['username'];
            $_SESSION['nama'] = $user['nama'];
            $_SESSION['role'] = $user['role'];
        } else {
            $adminUser = 'admin';
            $adminPass = 'admin123';
            if ($cookieUser === $adminUser && $cookiePass === $adminPass) {
                $_SESSION['username'] = $adminUser;
                $_SESSION['nama'] = 'Administrator';
                $_SESSION['role'] = 'admin';
            } else {
                setcookie('username', '', time() - 86400, '/');
                setcookie('password', '', time() - 86400, '/');
                setcookie('remember', '', time() - 86400, '/');
                header('Location: ' . BASE_URL . '/pages/login.php?akses=ditolak');
                exit();
            }
        }
    } else {
        header('Location: ' . BASE_URL . '/pages/login.php?akses=ditolak');
        exit();
    }
}
?>
