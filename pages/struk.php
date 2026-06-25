<?php
require_once __DIR__ . '/../config/constants.php';
require_once __DIR__ . '/../includes/customer_check.php';
require_once __DIR__ . '/../config/koneksi.php';

if (!isset($_SESSION['last_order'])) {
    header('Location: store.php');
    exit;
}

$order = $_SESSION['last_order'];
?>
<!doctype html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Struk Pembayaran - Kopi Kenangan Senja</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/style.css">
  <style>
    body { background: #f5efe6; }
    .struk-wrapper { max-width: 520px; margin: 40px auto; }
    .antrian-badge {
      background: linear-gradient(135deg, #3d1c00, #7a3b10);
      color: #fff;
      border-radius: 16px;
      padding: 24px;
      text-align: center;
      margin-bottom: 20px;
    }
    .antrian-number {
      font-size: 56px;
      font-weight: 900;
      color: #f0c070;
      letter-spacing: 2px;
      line-height: 1;
    }
    .struk-paper {
      background: #fff;
      border-radius: 12px;
      box-shadow: 0 4px 24px rgba(61,28,0,.12);
      overflow: hidden;
    }
    .struk-header {
      background: linear-gradient(135deg, #3d1c00, #7a3b10);
      color: #fff;
      padding: 20px;
      text-align: center;
    }
    .struk-body { padding: 20px; }
    .struk-row { display: flex; justify-content: space-between; padding: 6px 0; border-bottom: 1px dashed #e9d8c0; font-size: 14px; }
    .struk-row:last-child { border-bottom: none; }
    .struk-total { background: #fdf6ee; border-radius: 8px; padding: 14px; margin-top: 12px; display: flex; justify-content: space-between; align-items: center; }
    .struk-total .label { font-weight: 700; color: #3d1c00; font-size: 16px; }
    .struk-total .value { font-weight: 900; color: #c8842a; font-size: 22px; }
    .metode-badge { display: inline-block; padding: 4px 14px; border-radius: 20px; font-size: 13px; font-weight: 600; }
    @media print {
      .no-print { display: none !important; }
      body { background: #fff; }
      .struk-wrapper { margin: 0; }
    }
  </style>
</head>
<body>
<div class="struk-wrapper">

  <!-- Nomor Antrian -->
  <div class="antrian-badge">
    <div style="font-size:13px; letter-spacing:2px; text-transform:uppercase; opacity:.8; margin-bottom:8px;">Nomor Antrian Anda</div>
    <div class="antrian-number"><?php echo htmlspecialchars($order['no_antrian']); ?></div>
    <div style="font-size:12px; opacity:.7; margin-top:8px;">Tunjukkan nomor ini ke kasir</div>
  </div>

  <!-- Struk Pembayaran -->
  <div class="struk-paper">
    <div class="struk-header">
      <div style="font-size:22px; font-weight:800; letter-spacing:1px;">☕ Kopi Kenangan Senja</div>
      <div style="font-size:12px; opacity:.8; margin-top:4px;">STRUK PEMBAYARAN</div>
    </div>
    <div class="struk-body">

      <!-- Info Pesanan -->
      <div class="mb-3" style="font-size:13px; color:#888; text-align:center;">
        <div><?php echo htmlspecialchars($order['created_at']); ?></div>
        <div>Pelanggan: <strong style="color:#3d1c00;"><?php echo htmlspecialchars($order['nama_pelanggan']); ?></strong></div>
      </div>

      <hr style="border-color:#e9d8c0;">

      <!-- Detail Item -->
      <div class="mb-3">
        <?php foreach ($order['items'] as $item): ?>
        <div class="struk-row">
          <div>
            <div style="font-weight:600; color:#3d1c00;"><?php echo htmlspecialchars($item['product']['nama']); ?></div>
            <div style="font-size:12px; color:#999;">Rp <?php echo number_format($item['product']['harga'], 0, ',', '.'); ?> × <?php echo $item['qty']; ?></div>
          </div>
          <div style="font-weight:600; color:#555;">Rp <?php echo number_format($item['subtotal'], 0, ',', '.'); ?></div>
        </div>
        <?php endforeach; ?>
      </div>

      <!-- Total -->
      <div class="struk-total">
        <div class="label">TOTAL</div>
        <div class="value">Rp <?php echo number_format($order['total'], 0, ',', '.'); ?></div>
      </div>

      <!-- Metode Bayar -->
      <div class="text-center mt-3">
        <span class="metode-badge" style="background:#e8f5e9; color:#2e7d32;">
          <?php
          $metodeLabel = [
            'tunai' => '💵 Tunai',
            'transfer' => '🏦 Transfer Bank',
            'qris' => '📱 QRIS',
            'ewallet' => '💳 E-Wallet',
          ];
          echo htmlspecialchars($metodeLabel[$order['metode']] ?? $order['metode']);
          ?>
        </span>
      </div>

      <hr style="border-color:#e9d8c0; margin-top:16px;">
      <div style="text-align:center; font-size:12px; color:#aaa;">
        Terima kasih sudah berkunjung!<br>
        Pesanan Anda sedang diproses ☕
      </div>

    </div>
  </div>

  <!-- Tombol Aksi -->
  <div class="mt-3 d-flex gap-2 no-print">
    <button onclick="window.print()" class="btn btn-outline-secondary flex-fill">
      <i class="fas fa-print mr-1"></i> Cetak Struk
    </button>
    <a href="<?php echo BASE_URL; ?>/pages/store.php" class="btn btn-warning flex-fill">
      <i class="fas fa-store mr-1"></i> Belanja Lagi
    </a>
  </div>

</div>
</body>
</html>
