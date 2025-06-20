<?php
define('BASE_URL', 'https://store.kodoku.me/');

if (session_status() === PHP_SESSION_NONE) session_start();

require 'config/database.php';

$bodyClass = 'store-bg';
include 'includes/header.php';
include 'includes/navbar.php';

if (!isset($_SESSION['name'])) {
  die("❌ You must be logged in to buy.");
}

$player = $_SESSION['name'];
$item_type = $_POST['item_type'] ?? $_SESSION['item_type'] ?? '';
$item_id = (int)($_POST['item_id'] ?? $_SESSION['item_id'] ?? 0);
$redeem_code = strtoupper(trim($_POST['redeem_code'] ?? ''));

$message = '';
$error = false;
$redeem_error = '';
$purchase_data = [];
$discount_percent = 0;

// === [Redeem Code Logic] ===
if ($redeem_code !== '') {
  $stmt = $redeemConn->prepare("SELECT discount_percent FROM redeem_codes WHERE code = ? AND expires_at > NOW() AND (usage_limit IS NULL OR usage_count < usage_limit)");
  $stmt->bind_param("s", $redeem_code);
  $stmt->execute();
  $stmt->store_result();

  if ($stmt->num_rows > 0) {
    $stmt->bind_result($discount_percent);
    $stmt->fetch();
    $_SESSION['applied_discount'] = $discount_percent;
    $_SESSION['redeem_code'] = $redeem_code;
  } else {
    $redeem_error = "❌ Kode diskon tidak valid atau sudah kadaluarsa.";
  }
  $stmt->close();
}

if ($item_type === 'store_item') {
  if ($item_id <= 0) {
    $error = true;
    $message = "Invalid item ID.";
  } else {
    $stmt = $pdo->prepare("SELECT * FROM store_items WHERE id = ?");
    $stmt->execute([$item_id]);
    $item = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$item) {
      $error = true;
      $message = "Item not found.";
    } else {
      $price = (float) str_replace(['Rp.', ',', '.'], ['', '', ''], $item['price']);
      $creditAmount = (int) $item['amount'];

      if (isset($_SESSION['applied_discount']) && $redeem_code === '') {
        $discount_percent = $_SESSION['applied_discount'];
      }

      if ($discount_percent > 0) {
        $price *= (1 - $discount_percent / 100);
      }

      $_SESSION['item'] = $creditAmount;
      $_SESSION['item_type'] = $item_type;
      $_SESSION['item_id'] = $item['id'];
      $_SESSION['final_price'] = $price;

      $purchase_data = [
        'name' => $item['amount'],
        'price' => $price,
        'id' => $item['id']
      ];
    }
  }
} else {
  $error = true;
  $message = "Invalid item type.";
}
?>

<main class="flex-fill">
  <div class="container mt-5 mb-5">
    <?php if ($error): ?>
      <div class="alert alert-danger"><?= $message ?></div>
      <a href="<?= BASE_URL ?>store.php" class="btn btn-light mt-3">Back to Store</a>
    <?php else: ?>
      <h2 class="text-center mb-5 text-white">Konfirmasi Pembelian</h2>
      <div class="row justify-content-center">
        <div class="col-md-5">
          <div class="kodoku-summary-card">
            <div class="section-title">Order Summary</div>
            <p class="mb-1">
              <span class="kodoku-kbucks-amount"><?= htmlspecialchars($purchase_data['name']) ?></span>
              <span class="kodoku-kbucks-label">K-Bucks</span>
            </p>
            <p class="price-text mb-3">Rp<?= number_format($purchase_data['price'], 0, ',', '.') ?></p>
            <hr>
            <p class="d-flex justify-content-between">
              <span>Subtotal:</span>
              <span class="price-text">Rp<?= number_format($purchase_data['price'], 0, ',', '.') ?></span>
            </p>

            <?php if ($redeem_error): ?>
              <div class="alert alert-warning mt-3"><?= $redeem_error ?></div>
            <?php endif; ?>

            <form method="POST" class="mt-4">
              <input type="hidden" name="item_type" value="store_item">
              <input type="hidden" name="item_id" value="<?= $purchase_data['id'] ?>">
              <?php if (isset($_SESSION['redeem_code'])): ?>
                <input type="hidden" name="redeem_code" value="<?= htmlspecialchars($_SESSION['redeem_code']) ?>">
              <?php endif; ?>
              <div class="mb-3">
                <label for="redeem_code" class="form-label">Kode Diskon</label>
                <input type="text" name="redeem_code" id="redeem_code" class="form-control" placeholder="Masukkan kode diskon" value="<?= htmlspecialchars($redeem_code) ?>">
              </div>
              <button type="submit" class="btn btn-outline-warning w-100">Gunakan Kode</button>
            </form>

            <div class="d-flex gap-2 mt-4">
              <form method="POST" action="<?= BASE_URL ?>remove_item.php" class="flex-fill">
                <input type="hidden" name="item_id" value="<?= $purchase_data['id'] ?>">
                <button type="submit" class="btn btn-danger w-100">Hapus</button>
              </form>

              <!-- Tombol Bayar Sekarang (langsung ke process_payment.php) -->
              <form method="POST" action="<?= BASE_URL ?>process-payment" class="flex-fill">
                <input type="hidden" name="order_id" value="<?= uniqid('ORD-MANUAL-') ?>">
                <button type="submit" class="btn btn-success w-100">Bayar Sekarang (Manual)</button>
              </form>
            </div>
          </div>
        </div>
      </div>
    <?php endif; ?>
  </div>
</main>

<?php include 'includes/footer.php'; ?>
<?php include 'includes/scripts.php'; ?>
