<?php
require_once __DIR__ . '/../config/constants.php';
require_once __DIR__ . '/../includes/admin_check.php';
require_once __DIR__ . '/../config/koneksi.php';
require_once __DIR__ . '/../includes/produk_setup.php';

// Ensure upload directory exists
$uploadDir = __DIR__ . '/../uploads/produk/';
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0755, true);
}

function handleFotoUpload($uploadDir) {
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
        $ext = strtolower(pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        if (!in_array($ext, $allowed)) {
            return ['error' => 'Format file tidak didukung.'];
        }
        if ($_FILES['foto']['size'] > 2 * 1024 * 1024) {
            return ['error' => 'Ukuran file maksimal 2MB.'];
        }
        $filename = 'produk_' . time() . '_' . rand(1000,9999) . '.' . $ext;
        if (move_uploaded_file($_FILES['foto']['tmp_name'], $uploadDir . $filename)) {
            return ['filename' => $filename];
        }
        return ['error' => 'Gagal upload foto.'];
    }
    return ['filename' => null];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $nama = mysqli_real_escape_string($con, trim($_POST['nama'] ?? ''));
    $harga = (int) ($_POST['harga'] ?? 0);
    $stok = (int) ($_POST['stok'] ?? 0);
    $deskripsi = mysqli_real_escape_string($con, trim($_POST['deskripsi'] ?? ''));
    $icon = mysqli_real_escape_string($con, trim($_POST['icon'] ?? 'fas fa-mug-hot'));
    $warna = mysqli_real_escape_string($con, trim($_POST['warna'] ?? '#a0601a'));

    if ($action === 'tambah') {
        $fotoResult = handleFotoUpload($uploadDir);
        $fotoVal = isset($fotoResult['filename']) && $fotoResult['filename'] ? "'" . mysqli_real_escape_string($con, $fotoResult['filename']) . "'" : "NULL";
        $query = "INSERT INTO produk (nama, harga, stok, deskripsi, icon, warna, foto) VALUES ('$nama', $harga, $stok, '$deskripsi', '$icon', '$warna', $fotoVal)";
        mysqli_query($con, $query);
        header('Location: ' . BASE_URL . '/pages/data.php?pesan=tambah');
        exit;
    }

    if ($action === 'edit') {
        $id = (int) ($_POST['id'] ?? 0);
        $fotoLama = $_POST['foto_lama'] ?? '';
        $hapusFoto = isset($_POST['hapus_foto']) && $_POST['hapus_foto'] == 1;

        if ($id > 0) {
            $fotoResult = handleFotoUpload($uploadDir);
            $newFoto = $fotoResult['filename'] ?? null;

            if ($newFoto) {
                // Delete old photo
                if ($fotoLama && file_exists($uploadDir . $fotoLama)) {
                    unlink($uploadDir . $fotoLama);
                }
                $fotoSql = ", foto='" . mysqli_real_escape_string($con, $newFoto) . "'";
            } elseif ($hapusFoto) {
                if ($fotoLama && file_exists($uploadDir . $fotoLama)) {
                    unlink($uploadDir . $fotoLama);
                }
                $fotoSql = ", foto=NULL";
            } else {
                $fotoSql = "";
            }

            $query = "UPDATE produk SET nama='$nama', harga=$harga, stok=$stok, deskripsi='$deskripsi', icon='$icon', warna='$warna'$fotoSql WHERE id=$id";
            mysqli_query($con, $query);
            header('Location: ' . BASE_URL . '/pages/data.php?pesan=edit');
            exit;
        }
    }
}

if (isset($_GET['hapus'])) {
    $hapusId = (int) $_GET['hapus'];
    if ($hapusId > 0) {
        // Delete photo file too
        $res = mysqli_query($con, "SELECT foto FROM produk WHERE id=$hapusId LIMIT 1");
        if ($res && $row = mysqli_fetch_assoc($res)) {
            if (!empty($row['foto']) && file_exists($uploadDir . $row['foto'])) {
                unlink($uploadDir . $row['foto']);
            }
        }
        mysqli_query($con, "DELETE FROM produk WHERE id=$hapusId");
        header('Location: ' . BASE_URL . '/pages/data.php?pesan=hapus');
        exit;
    }
}

header('Location: ' . BASE_URL . '/pages/data.php');
exit;
