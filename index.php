<?php
require_once __DIR__ . '/config/constants.php';
require_once __DIR__ . '/includes/auth_check.php';

if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
    header('Location: ' . BASE_URL . '/pages/tentang.php');
    exit;
}

if (isset($_SESSION['role']) && $_SESSION['role'] === 'pelanggan') {
    header('Location: ' . BASE_URL . '/pages/pelanggan.php');
    exit;
}

header('Location: ' . BASE_URL . '/pages/login.php');
?>
