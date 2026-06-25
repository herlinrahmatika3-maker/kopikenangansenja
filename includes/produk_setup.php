<?php

function setup_users_table($con)
{
    $createTable = "CREATE TABLE IF NOT EXISTS users (
        username VARCHAR(50) PRIMARY KEY,
        password VARCHAR(255) NOT NULL,
        nama VARCHAR(255) NOT NULL,
        role ENUM('admin','pelanggan') NOT NULL DEFAULT 'pelanggan'
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
    mysqli_query($con, $createTable);

    $columns = [];
    $result = mysqli_query($con, "SHOW COLUMNS FROM users");
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $columns[$row['Field']] = $row;
        }
    }

    if (!isset($columns['role'])) {
        mysqli_query($con, "ALTER TABLE users ADD COLUMN role ENUM('admin','pelanggan') NOT NULL DEFAULT 'pelanggan' AFTER nama");
    }

    mysqli_query($con, "UPDATE users SET role = 'pelanggan' WHERE role IN ('pelan', 'pel', '') OR role IS NULL");

    $checkAdmin = mysqli_query($con, "SELECT COUNT(*) AS total FROM users WHERE username='admin'");
    if ($checkAdmin) {
        $row = mysqli_fetch_assoc($checkAdmin);
        if ($row['total'] == 0) {
            mysqli_query($con, "INSERT INTO users (username, password, nama, role) VALUES ('admin', 'admin123', 'Administrator', 'admin')");
        }
    }

    $checkPelanggan = mysqli_query($con, "SELECT COUNT(*) AS total FROM users WHERE role='pelanggan'");
    if ($checkPelanggan) {
        $row = mysqli_fetch_assoc($checkPelanggan);
        if ($row['total'] == 0) {
            mysqli_query($con, "INSERT INTO users (username, password, nama, role) VALUES ('user1', 'user123', 'Pelanggan', 'pelanggan')");
        }
    }
}

function setup_produk_table($con)
{
    $createTable = "CREATE TABLE IF NOT EXISTS produk (
        id INT AUTO_INCREMENT PRIMARY KEY,
        nama VARCHAR(255) NOT NULL,
        harga INT NOT NULL,
        stok INT NOT NULL,
        deskripsi TEXT,
        icon VARCHAR(100) NOT NULL DEFAULT 'fas fa-mug-hot',
        warna VARCHAR(7) NOT NULL DEFAULT '#a0601a',
        foto VARCHAR(255) DEFAULT NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
    mysqli_query($con, $createTable);

    // Add foto column if not exists
    $hasFoto = mysqli_query($con, "SHOW COLUMNS FROM produk LIKE 'foto'");
    if (!$hasFoto || mysqli_num_rows($hasFoto) === 0) {
        mysqli_query($con, "ALTER TABLE produk ADD COLUMN foto VARCHAR(255) DEFAULT NULL AFTER warna");
    }

    $hasId = mysqli_query($con, "SHOW COLUMNS FROM produk LIKE 'id'");
    if (!$hasId || mysqli_num_rows($hasId) === 0) {
        mysqli_query($con, "ALTER TABLE produk ADD COLUMN id INT NOT NULL AUTO_INCREMENT PRIMARY KEY FIRST");
    }

    $hasNama = mysqli_query($con, "SHOW COLUMNS FROM produk LIKE 'nama'");
    if (!$hasNama || mysqli_num_rows($hasNama) === 0) {
        mysqli_query($con, "ALTER TABLE produk ADD COLUMN nama VARCHAR(255) NOT NULL AFTER id");
        mysqli_query($con, "UPDATE produk SET nama = 'Produk'");
    }

    $hasIcon = mysqli_query($con, "SHOW COLUMNS FROM produk LIKE 'icon'");
    if (!$hasIcon || mysqli_num_rows($hasIcon) === 0) {
        mysqli_query($con, "ALTER TABLE produk ADD COLUMN icon VARCHAR(100) NOT NULL DEFAULT 'fas fa-mug-hot' AFTER deskripsi");
    }

    $hasWarna = mysqli_query($con, "SHOW COLUMNS FROM produk LIKE 'warna'");
    if (!$hasWarna || mysqli_num_rows($hasWarna) === 0) {
        mysqli_query($con, "ALTER TABLE produk ADD COLUMN warna VARCHAR(7) NOT NULL DEFAULT '#a0601a' AFTER icon");
    }

    // Auto-link existing products to their photos by name
    $photoMap = [
        'espresso'        => 'espresso.jpg',
        'americano'       => 'americano.jpg',
        'cappuccino'      => 'cappuccino.jpg',
        'latte'           => 'latte.jpg',
        'choco cookies'   => 'chococookies.jpg',
        'red velvet milk' => 'redvelvet.jpg',
    ];
    $allProduk = mysqli_query($con, "SELECT id, nama, foto FROM produk");
    if ($allProduk) {
        while ($row = mysqli_fetch_assoc($allProduk)) {
            if (!empty($row['foto'])) continue; // skip if already has photo
            $namaLower = strtolower(trim($row['nama']));
            foreach ($photoMap as $key => $file) {
                if (strpos($namaLower, $key) !== false) {
                    $pid = (int)$row['id'];
                    $safeFile = mysqli_real_escape_string($con, $file);
                    mysqli_query($con, "UPDATE produk SET foto='$safeFile' WHERE id=$pid");
                    break;
                }
            }
        }
    }

    $checkEmpty = mysqli_query($con, "SELECT COUNT(*) AS total FROM produk");
    if ($checkEmpty) {
        $rowCount = mysqli_fetch_assoc($checkEmpty);
        if ($rowCount['total'] == 0) {
            $seed = [
                ['nama'=>'Espresso',       'harga'=>25000,'stok'=>50,'deskripsi'=>'Kopi murni pekat dengan cita rasa kuat dan aroma yang menggugah selera.',                                       'icon'=>'fas fa-fire',   'warna'=>'#3d1c00','foto'=>'espresso.jpg'],
                ['nama'=>'Americano',      'harga'=>28000,'stok'=>45,'deskripsi'=>'Espresso yang diencerkan dengan air panas, ringan namun tetap kaya rasa.',                                       'icon'=>'fas fa-tint',   'warna'=>'#5a3010','foto'=>'americano.jpg'],
                ['nama'=>'Cappuccino',     'harga'=>32000,'stok'=>40,'deskripsi'=>'Perpaduan sempurna espresso, susu panas, dan busa susu yang lembut.',                                           'icon'=>'fas fa-cloud',  'warna'=>'#c8842a','foto'=>'cappuccino.jpg'],
                ['nama'=>'Latte',          'harga'=>35000,'stok'=>38,'deskripsi'=>'Kopi lembut dengan kandungan susu lebih banyak, cocok untuk pemula.',                                           'icon'=>'fas fa-mug-hot','warna'=>'#a0601a','foto'=>'latte.jpg'],
                ['nama'=>'Choco Cookies',  'harga'=>15000,'stok'=>30,'deskripsi'=>'Cookies renyah dengan taburan coklat chip yang melimpah, cocok menemani kopi Anda.',                            'icon'=>'fas fa-cookie', 'warna'=>'#6b3a10','foto'=>'chococookies.jpg'],
                ['nama'=>'Red Velvet Milk','harga'=>35000,'stok'=>25,'deskripsi'=>'Manis elegan dan creamy. Red Velvet Latte diracik sempurna untuk sentuhan manis di hari yang sibuk.',           'icon'=>'fas fa-heart',  'warna'=>'#c0392b','foto'=>'redvelvet.jpg'],
            ];
            foreach ($seed as $item) {
                $nama      = mysqli_real_escape_string($con, $item['nama']);
                $harga     = (int) $item['harga'];
                $stok      = (int) $item['stok'];
                $deskripsi = mysqli_real_escape_string($con, $item['deskripsi']);
                $icon      = mysqli_real_escape_string($con, $item['icon']);
                $warna     = mysqli_real_escape_string($con, $item['warna']);
                $foto      = mysqli_real_escape_string($con, $item['foto']);
                mysqli_query($con, "INSERT INTO produk (nama, harga, stok, deskripsi, icon, warna, foto) VALUES ('$nama', $harga, $stok, '$deskripsi', '$icon', '$warna', '$foto')");
            }
        }
    }
}

function setup_ulasan_table($con)
{
    $createTable = "CREATE TABLE IF NOT EXISTS ulasan (
        id INT AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(50) NULL,
        nama VARCHAR(255) NOT NULL,
        menu VARCHAR(255) NOT NULL,
        rating TINYINT NOT NULL,
        komentar TEXT NOT NULL,
        created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
    mysqli_query($con, $createTable);

    $columns = [];
    $result = mysqli_query($con, "SHOW COLUMNS FROM ulasan");
    if ($result) {
        while ($col = mysqli_fetch_assoc($result)) {
            $columns[$col['Field']] = true;
        }
    }

    if (!isset($columns['id'])) {
        mysqli_query($con, "ALTER TABLE ulasan ADD COLUMN id INT NOT NULL AUTO_INCREMENT PRIMARY KEY FIRST");
    }
    if (!isset($columns['username'])) {
        mysqli_query($con, "ALTER TABLE ulasan ADD COLUMN username VARCHAR(50) NULL AFTER id");
    }
    if (!isset($columns['nama'])) {
        mysqli_query($con, "ALTER TABLE ulasan ADD COLUMN nama VARCHAR(255) NOT NULL DEFAULT '' AFTER username");
    }
    if (!isset($columns['menu'])) {
        mysqli_query($con, "ALTER TABLE ulasan ADD COLUMN menu VARCHAR(255) NOT NULL DEFAULT 'Menu' AFTER nama");
    }
    if (!isset($columns['rating'])) {
        mysqli_query($con, "ALTER TABLE ulasan ADD COLUMN rating TINYINT NOT NULL DEFAULT 5 AFTER menu");
    }
    if (!isset($columns['komentar'])) {
        mysqli_query($con, "ALTER TABLE ulasan ADD COLUMN komentar TEXT NOT NULL AFTER rating");
    }
    if (!isset($columns['created_at'])) {
        mysqli_query($con, "ALTER TABLE ulasan ADD COLUMN created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP AFTER komentar");
    }
}

function setup_pesanan_table($con)
{
    // Pesanan (orders) table
    $createTable = "CREATE TABLE IF NOT EXISTS pesanan (
        id INT AUTO_INCREMENT PRIMARY KEY,
        no_antrian VARCHAR(20) NOT NULL,
        username VARCHAR(50) NOT NULL,
        nama_pelanggan VARCHAR(255) NOT NULL,
        total INT NOT NULL,
        metode_bayar VARCHAR(50) NOT NULL DEFAULT 'tunai',
        bukti_bayar VARCHAR(255) DEFAULT NULL,
        status ENUM('menunggu','dibayar','selesai') NOT NULL DEFAULT 'menunggu',
        created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
    mysqli_query($con, $createTable);

    // Pesanan detail table
    $createDetail = "CREATE TABLE IF NOT EXISTS pesanan_detail (
        id INT AUTO_INCREMENT PRIMARY KEY,
        id_pesanan INT NOT NULL,
        id_produk INT NOT NULL,
        nama_produk VARCHAR(255) NOT NULL,
        harga INT NOT NULL,
        qty INT NOT NULL,
        subtotal INT NOT NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
    mysqli_query($con, $createDetail);

    // Add bukti_bayar column if missing
    $columns = [];
    $result = mysqli_query($con, "SHOW COLUMNS FROM pesanan");
    if ($result) {
        while ($col = mysqli_fetch_assoc($result)) {
            $columns[$col['Field']] = true;
        }
    }
    if (!isset($columns['bukti_bayar'])) {
        mysqli_query($con, "ALTER TABLE pesanan ADD COLUMN bukti_bayar VARCHAR(255) DEFAULT NULL AFTER metode_bayar");
    }
    if (!isset($columns['status'])) {
        mysqli_query($con, "ALTER TABLE pesanan ADD COLUMN status ENUM('menunggu','dibayar','selesai') NOT NULL DEFAULT 'menunggu' AFTER bukti_bayar");
    }
}

setup_users_table($con);
setup_produk_table($con);
setup_ulasan_table($con);
setup_pesanan_table($con);
