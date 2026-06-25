<?php
require_once __DIR__ . '/../config/constants.php';
require_once __DIR__ . '/../includes/customer_check.php';
require_once __DIR__ . '/../config/koneksi.php';
require_once __DIR__ . '/../includes/produk_setup.php';

$action = isset($_REQUEST['action']) ? $_REQUEST['action'] : '';
$id = isset($_REQUEST['id']) ? (int) $_REQUEST['id'] : 0;
$qty = isset($_REQUEST['qty']) ? (int) $_REQUEST['qty'] : 1;

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

switch ($action) {
    case 'add':
        if ($id > 0 && $qty > 0) {
            $productQuery = mysqli_query($con, "SELECT * FROM produk WHERE id=$id LIMIT 1");
            if ($productQuery && mysqli_num_rows($productQuery) > 0) {
                $product = mysqli_fetch_assoc($productQuery);
                if ($qty > $product['stok']) {
                    $qty = $product['stok'];
                }
                if (isset($_SESSION['cart'][$id])) {
                    $_SESSION['cart'][$id] += $qty;
                } else {
                    $_SESSION['cart'][$id] = $qty;
                }
            }
        }
        header('Location: ' . BASE_URL . '/pages/store.php?pesan=cart');
        exit;

    case 'update':
        if ($id > 0 && $qty > 0) {
            $productQuery = mysqli_query($con, "SELECT * FROM produk WHERE id=$id LIMIT 1");
            if ($productQuery && mysqli_num_rows($productQuery) > 0) {
                $product = mysqli_fetch_assoc($productQuery);
                $_SESSION['cart'][$id] = min($qty, $product['stok']);
            }
        }
        header('Location: ' . BASE_URL . '/pages/keranjang.php');
        exit;

    case 'remove':
        if ($id > 0 && isset($_SESSION['cart'][$id])) {
            unset($_SESSION['cart'][$id]);
        }
        header('Location: ' . BASE_URL . '/pages/keranjang.php');
        exit;

    case 'checkout':
        $cart = $_SESSION['cart'] ?? [];
        if (empty($cart)) {
            header('Location: ' . BASE_URL . '/pages/keranjang.php');
            exit;
        }

        $metode = mysqli_real_escape_string($con, $_POST['metode_bayar'] ?? 'tunai');
        $username = $_SESSION['username'] ?? '';
        $nama_pelanggan = $_SESSION['nama'] ?? $username;
        $nama_pelanggan = mysqli_real_escape_string($con, $nama_pelanggan);

        // Generate nomor antrian
        $today = date('Ymd');
        $countQuery = mysqli_query($con, "SELECT COUNT(*) AS total FROM pesanan WHERE DATE(created_at) = CURDATE()");
        $countRow = mysqli_fetch_assoc($countQuery);
        $antrian = ($countRow['total'] ?? 0) + 1;
        $no_antrian = 'KKS-' . $today . '-' . str_pad($antrian, 3, '0', STR_PAD_LEFT);

        // Get products and calculate total
        $ids = array_keys($cart);
        $idsList = implode(',', array_map('intval', $ids));
        $productQuery = mysqli_query($con, "SELECT * FROM produk WHERE id IN ($idsList)");
        $products = [];
        while ($row = mysqli_fetch_assoc($productQuery)) {
            $products[$row['id']] = $row;
        }

        $total = 0;
        $items = [];
        foreach ($cart as $pid => $qty) {
            if (!isset($products[$pid])) continue;
            $p = $products[$pid];
            $actualQty = min($qty, $p['stok']);
            $subtotal = $actualQty * $p['harga'];
            $total += $subtotal;
            $items[] = ['product' => $p, 'qty' => $actualQty, 'subtotal' => $subtotal];
        }

        // Handle bukti bayar upload
        $bukti_bayar_val = "NULL";
        $uploadDir = __DIR__ . '/../uploads/bukti/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }
        if (isset($_FILES['bukti_bayar']) && $_FILES['bukti_bayar']['error'] === UPLOAD_ERR_OK) {
            $ext = strtolower(pathinfo($_FILES['bukti_bayar']['name'], PATHINFO_EXTENSION));
            $allowed = ['jpg','jpeg','png','gif','webp'];
            if (in_array($ext, $allowed) && $_FILES['bukti_bayar']['size'] <= 2*1024*1024) {
                $filename = 'bukti_' . time() . '_' . rand(1000,9999) . '.' . $ext;
                if (move_uploaded_file($_FILES['bukti_bayar']['tmp_name'], $uploadDir . $filename)) {
                    $bukti_bayar_val = "'" . mysqli_real_escape_string($con, $filename) . "'";
                }
            }
        }

        // Insert pesanan
        $insertPesanan = "INSERT INTO pesanan (no_antrian, username, nama_pelanggan, total, metode_bayar, bukti_bayar, status)
                          VALUES ('$no_antrian', '" . mysqli_real_escape_string($con, $username) . "', '$nama_pelanggan', $total, '$metode', $bukti_bayar_val, 'dibayar')";
        mysqli_query($con, $insertPesanan);
        $id_pesanan = mysqli_insert_id($con);

        // Insert detail & kurangi stok
        foreach ($items as $item) {
            $p = $item['product'];
            $q = $item['qty'];
            $sub = $item['subtotal'];
            $nama_p = mysqli_real_escape_string($con, $p['nama']);
            $harga_p = (int)$p['harga'];
            mysqli_query($con, "INSERT INTO pesanan_detail (id_pesanan, id_produk, nama_produk, harga, qty, subtotal)
                                VALUES ($id_pesanan, {$p['id']}, '$nama_p', $harga_p, $q, $sub)");
            // Kurangi stok
            mysqli_query($con, "UPDATE produk SET stok = stok - $q WHERE id = {$p['id']} AND stok >= $q");
        }

        // Clear cart
        unset($_SESSION['cart']);

        // Store order info in session for struk
        $_SESSION['last_order'] = [
            'no_antrian' => $no_antrian,
            'id_pesanan' => $id_pesanan,
            'total' => $total,
            'metode' => $metode,
            'items' => $items,
            'nama_pelanggan' => $_SESSION['nama'] ?? $username,
            'created_at' => date('d M Y H:i'),
        ];

        header('Location: ' . BASE_URL . '/pages/struk.php');
        exit;
}

header('Location: ' . BASE_URL . '/pages/store.php');
exit;
