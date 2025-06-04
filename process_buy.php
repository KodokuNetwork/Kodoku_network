<?php $bodyClass = 'store-bg'; ?>
<?php include 'includes/header.php'; ?>
<?php include 'includes/navbar.php'; ?>

<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require 'config/database.php';
require_once 'config/midtrans.php';

if (!isset($_SESSION['name'])) {
    die("❌ You must be logged in to buy.");
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die("❌ Invalid request method.");
}

$player = $_SESSION['name'];
$item_type = $_POST['item_type'] ?? '';

$message = '';
$error = false;
$purchase_data = [];

if ($item_type === 'store_item') {
    $item_id = (int)($_POST['item_id'] ?? 0);
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
            $quantity = (int)($_POST['quantity'] ?? 1);
            $price = (float) str_replace(['Rp.', ',', '.'], ['', '', ''], $item['price']);
            $creditAmount = (int) $item['amount'];

            $_SESSION['item'] = $creditAmount;

            $purchase_data = [
                'name' => $item['amount'],
                'price' => $price,
                'id' => $item['id']
            ];

            $snapToken = createSnapToken($price);
        }
    }
} else {
    $error = true;
    $message = "Invalid item type.";
}
?>
<main class="flex-fill">
    <div class="container mt-5">
        <?php if ($error): ?>
            <div class="alert alert-danger"><?= $message ?></div>
            <a href="index.php" class="btn btn-light mt-3">Back to Store</a>
        <?php else: ?>
            <h2 class="text-center mb-5">Confirm Payment</h2>
            <div class="row">
                <!-- Item Section -->
                <div class="col-md-8">
                    <div class="card-dark d-flex align-items-center">
                        <img src="https://via.placeholder.com/120x80.png?text=Item" class="mr-3 rounded" alt="Item">
                        <div>
                            <h5><?= htmlspecialchars($purchase_data['name']) ?></h5>
                            <p>Rp <?= number_format($purchase_data['price'], 0, ',', '.') ?></p>
                            <form method="POST" action="remove_item.php" style="display:inline;">
                                <input type="hidden" name="item_id" value="<?= $purchase_data['id'] ?>">
                                <button type="submit" class="btn btn-danger btn-sm">Remove</button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Summary Section -->
                <div class="col-md-4">
                    <div class="summary-box">
                        <div class="section-title">Order Summary</div>
                        <p><?= htmlspecialchars($purchase_data['name']) ?> credits</p>
                        <p class="price-text">Rp <?= number_format($purchase_data['price'], 0, ',', '.') ?></p>
                        <hr style="border-color: #444;">
                        <p class="d-flex justify-content-between">
                            <span>Subtotal:</span>
                            <span class="price-text">Rp <?= number_format($purchase_data['price'], 0, ',', '.') ?></span>
                        </p>
                        <form method="POST" action="remove_item.php" style="display:inline;">
                            <input type="hidden" name="item_id" value="<?= $purchase_data['id'] ?>">
                            <button type="submit" class="btn btn-danger btn-block mt-3">Remove</button>
                        </form>
                        <button id="pay-button" class="btn btn-success btn-block mt-3">Process Payment</button>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</main>

<?php include 'includes/footer.php'; ?>
<?php include 'includes/scripts.php'; ?>

<script src="https://app.sandbox.midtrans.com/snap/snap.js"
    data-client-key="<?= getenv('MIDTRANS_CLIENT_KEY') ?>"></script>
<script>
    document.getElementById('pay-button').onclick = function(event) {
        event.preventDefault();

        window.snap.pay("<?= $snapToken ?>", {
            onSuccess: function(result) {
                fetch('process_payment.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify(result)
                    })
                    .then(res => res.json())
                    .then(data => {
                        const msg = encodeURIComponent(data.message);
                        window.location.href = "store.php?msg=" + msg;
                    });
            },
            onPending: function(result) {
                alert("⏳ Waiting for payment...");
            },
            onError: function(result) {
                alert("❌ Payment failed!");
            }
        });
    };
</script>