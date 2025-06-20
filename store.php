<?php
define('BASE_URL', 'https://store.kodoku.me/');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['name'])) {
    header('Location: index.php');
    exit;
}

require 'config/database.php';
$bodyClass = 'store-bg';
include 'includes/header.php';
include 'includes/navbar.php';

// Function: Format K-Bucks with visual bonus
function formatKbucksAmountHTML($amount) {
    $main = floor($amount / 100) * 100;
    $bonus = $amount - $main;
    if ($bonus > 0) {
        return $main . ' <span class="bonus-badge">+'.$bonus.'</span>';
    } else {
        return $main;
    }
}
?>

<?php if (isset($_SESSION['name'])): ?>
    <div class="alert alert-success alert-dismissible fade show text-center d-flex justify-content-between align-items-center" role="alert" style="padding-right: 2.5rem;">
        <span class="mx-auto">Welcome, <?= htmlspecialchars($_SESSION['name']); ?>!</span>
        <button type="button" class="bg-transparent border-0 close position-absolute" style="right: 1rem; font-size: 1.5rem; line-height: 1;" data-bs-dismiss="alert" aria-label="Close">
            <span aria-hidden="true" style="font-weight: bold;">&times;</span>
        </button>
    </div>
<?php endif; ?>

<style>
    .kodoku-store-card {
        background: rgb(15, 15, 15);
        border: 2px solid rgba(255, 255, 255, 0.08);
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.3);
        border-radius: 16px;
        padding: 2rem 1.5rem;
        text-align: center;
        display: flex;
        flex-direction: column;
        align-items: center;
        transition: transform 0.2s ease;
        height: 100%;
        position: relative;
    }

    .kodoku-store-card:hover {
        background: rgb(22, 22, 22);
        transform: scale(1.02);
    }

    .kodoku-store-card img {
        width: 100px;
        height: 100px;
        object-fit: contain;
        margin-bottom: 1rem;
    }

    .kodoku-store-amount {
        font-size: 1.8rem;
        font-weight: 600;
        color: #fff;
        line-height: 1;
    }

    .kodoku-store-label {
        font-size: 0.9rem;
        color: #888;
        margin-top: 0.2rem;
        margin-bottom: 1.5rem;
    }

    .kodoku-store-btn {
        width: 100%;
        background-color: #15803d;
        border: none;
        border-radius: 12px;
        color: white;
        padding: 0.4rem 1rem;
        height: 40px;
        font-size: 0.95rem;
        font-weight: 500;
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 0.4rem;
        transition: background-color 0.2s ease;
    }

    .kodoku-store-btn:hover {
        background-color: #166534;
    }

    .kodoku-store-btn img {
        height: 20px;
        width: 20px;
        display: block;
        margin: 0;
        vertical-align: middle;
    }

    .best-seller-badge {
        position: absolute;
        top: 0;
        left: 0;
        background-color: #FFD700;
        color: #111;
        padding: 0.4rem 0.8rem;
        border-radius: 0 0.75rem 0.75rem 0;
        font-weight: bold;
        font-size: 0.8rem;
        z-index: 10;
    }

    .bonus-badge {
        display: inline-block;
        background-color: #22c55e;
        color: white;
        font-size: 0.85rem;
        font-weight: 600;
        padding: 2px 6px;
        border-radius: 8px;
        margin-left: 6px;
        vertical-align: middle;
    }
</style>

<main class="flex-fill">
    <div class="container pb-5">
        <!-- Hero Banner -->
        <div class="row justify-content-center mb-5 mt-4"
            style="background-image: url('<?= BASE_URL ?>assets/images/storecard.png'); background-size: cover; background-position: center; height: 300px; border-radius: 18px; padding-top: 50px;">
            <div class="col-12 text-left text-white d-flex align-items-center justify-content-start" style="height: 100%; padding-left: 20px;">
                <div>
                    <h1 class="display-5 fw-bold bit-font" style="text-shadow: 2px 2px 5px rgba(0, 0, 0, 0.7);">K-Bucks Store</h1>
                    <p class="fs-4" style="color: #FFD966; text-shadow: 2px 2px 5px rgba(0, 0, 0, 0.7);">Purchase K-Bucks for any powerful & premium items</p>
                </div>
            </div>
        </div>

        <!-- Store Items -->
        <div class="row g-4">
            <?php
            $query = $pdo->query("SELECT * FROM store_items");
            $items = $query->fetchAll(PDO::FETCH_ASSOC);

            $bestSellerIds = [55, 58, 63]; // ganti sesuai item best seller kamu

            foreach ($items as $item):
            ?>
                <div class="col-12 col-sm-6 col-md-4">
                    <div class="kodoku-store-card">
                        <?php if (in_array((int)$item['id'], $bestSellerIds)): ?>
                            <span class="best-seller-badge">⭐ Best Seller</span>
                        <?php endif; ?>
                        <img src="<?= BASE_URL ?>assets/images/Minecoins_1.png" alt="K-Bucks">
                        <div class="kodoku-store-amount"><?= formatKbucksAmountHTML((int)$item['amount']) ?></div>
                        <div class="kodoku-store-label">K-Bucks</div>
                        <form method="POST" action="<?= BASE_URL ?>buy" class="w-100 mt-auto">
                            <input type="hidden" name="item_type" value="store_item">
                            <input type="hidden" name="item_id" value="<?= $item['id'] ?>">
                            <button type="submit" class="kodoku-store-btn">
                                <img src="<?= BASE_URL ?>assets/images/cart.png" alt="Cart">
                                Rp<?= number_format($item['price_value'], 0, ',', '.') ?>
                            </button>
                        </form>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</main>

<?php include 'includes/footer.php'; ?>
<?php include 'includes/scripts.php'; ?>
