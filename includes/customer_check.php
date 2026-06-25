<?php
require_once __DIR__ . '/auth_check.php';
$role = isset($_SESSION['role']) ? strtolower(trim($_SESSION['role'])) : '';
$isPelanggan = $role === 'pelanggan' || $role === 'pelan' || $role === 'pel';
if (!$isPelanggan) {
    if ($role === 'admin') {
        header('Location: ' . BASE_URL . '/pages/tentang.php');
    } else {
        header('Location: ' . BASE_URL . '/pages/login.php?akses=ditolak');
    }
    exit();
}
