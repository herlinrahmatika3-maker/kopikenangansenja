<?php
require_once __DIR__ . '/../config/constants.php';
require_once __DIR__ . '/../includes/auth_check.php';
require_once __DIR__ . '/../config/koneksi.php';
require_once __DIR__ . '/../includes/produk_setup.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? 'add';
    $return = isset($_POST['return']) ? $_POST['return'] : 'store.php';

    if ($action === 'delete') {
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
            header('Location: ' . BASE_URL . '/pages/login.php?akses=ditolak');
            exit;
        }

        $id = (int) ($_POST['id'] ?? 0);
        if ($id > 0) {
            mysqli_query($con, "DELETE FROM ulasan WHERE id = $id LIMIT 1");
        }

        header('Location: ' . BASE_URL . '/pages/' . basename($return) . '?pesan=deleted');
        exit;
    }

    $nama = mysqli_real_escape_string($con, trim($_POST['nama'] ?? ''));
    $menu = mysqli_real_escape_string($con, trim($_POST['menu'] ?? ''));
    $rating = (int) ($_POST['rating'] ?? 0);
    $komentar = mysqli_real_escape_string($con, trim($_POST['komentar'] ?? ''));

    if ($nama && $menu && $rating > 0 && $komentar) {
        $insert = mysqli_query($con, "INSERT INTO ulasan (username, nama, menu, rating, komentar) VALUES ('" . mysqli_real_escape_string($con, $_SESSION['username']) . "', '$nama', '$menu', $rating, '$komentar')");
        if (!$insert) {
            $errorMsg = 'gagal';
            header('Location: ' . BASE_URL . '/pages/' . basename($return) . '?pesan=' . $errorMsg);
            exit;
        }
    }

    header('Location: ' . BASE_URL . '/pages/' . basename($return) . '?pesan=ulasan');
    exit;
}

header('Location: ' . BASE_URL . '/pages/store.php');
exit;
