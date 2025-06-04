<?php $bodyClass = 'store-bg'; ?>
<?php include 'includes/header.php'; ?>
<?php include 'includes/navbar.php'; ?>
<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require 'config/database.php';
// require 'config/store_database.php';  // For $storeConn (store DB)

if (isset($_SESSION['name'])) {
?>
    <div class="alert alert-success alert-dismissible fade show text-center d-flex justify-content-between align-items-center" role="alert" style="padding-right: 2.5rem;">
        <span class="mx-auto">Welcome, <?php echo htmlspecialchars($_SESSION['name']); ?>!</span>
        <button type="button" class="bg-transparent border-0 close position-absolute" style="right: 1rem; font-size: 1.5rem; line-height: 1;" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true" style="font-weight: bold;">&times;</span>
        </button>
    </div>
<?php
}
?>

<main class="flex-fill">
    <div class="container">
        <!-- <h1 class="mt-5">K-Store</h1>
        <p>Find your dream items within great value</p>

        <div class="row mt-4">

            <div class="col-md-6">
                <div class="card bg-info text-white mb-4">
                    <div class="card-body">
                        <h5 class="card-title">New offers!</h5>
                        <h6 class="card-subtitle mb-2">Summer Cosmetic Bundle</h6>
                        <p class="card-text">
                            $10.49 <span class="badge badge-danger">Expired 50% Off</span>
                        </p>
                        <a href="#" class="btn btn-light">Buy Now</a>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="row">
                    <div class="col-md-12 mb-4">
                        <div class="card bg-success text-white">
                            <div class="card-body">
                                <h5 class="card-title">Powerful Items</h5>
                                <p class="card-text">Explore powerful game items!</p>
                                <a href="#" class="btn btn-light">Shop Now</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="card bg-primary text-white">
                            <div class="card-body">
                                <h5 class="card-title">Exclusive Skins</h5>
                                <p class="card-text">Unlock exclusive character skins!</p>
                                <a href="#" class="btn btn-light">View Skins</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div> -->

        <!-- Dynamic Store Section -->
        <h1 class="mt-5">K-Bucks</h1>
        <p>Purchase K-Bucks for any powerful & premium items</p>
        <div class="row">
            <?php
            $query = $pdo->query("SELECT * FROM store_items");
            $items = $query->fetchAll(PDO::FETCH_ASSOC);
            foreach ($items as $item) {
            ?>
                <div class="col-md-4 mb-4">
                    <div class="card h-100 d-flex flex-column text-white" style="background-color: #1a1a1a; border-radius: 18px; box-shadow: 0 4px 12px rgba(0,0,0,0.25);">
                        <div class="card-body d-flex flex-column align-items-center justify-content-center text-center">

                            <!-- Gambar item -->
                             <img src="assets/images/Minecoins_1.png" alt="K-Bucks" class="img-fluid mb-3" style="max-height: 120px;">
                            <!-- Jumlah -->
                            <h4 class="fw-bold mb-1"><?= number_format($item['amount'], 0, ',', '.') ?></h4>
                            <p style="color: rgba(255,255,255,0.6); font-size: 14px; margin-bottom: 1rem;">K-Bucks</p>

                            <!-- Tombol beli -->
                            <form method="POST" action="process_buy.php" class="mt-auto w-100">
                                <input type="hidden" name="item_type" value="store_item">
                                <input type="hidden" name="item_id" value="<?= $item['id'] ?>">
                                <div class="d-flex justify-content-center">
                                    <button type="submit" class="btn w-100 text-white" style="background-color: #22c55e; border-radius: 12px;">
                                        <i class="fas fa-shopping-cart me-2"></i>
                                        Rp <?= number_format($item['price_value'], 0, ',', '.') ?>
                                    </button>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
            <?php
            }
            ?>
        </div>

    </div>
</main>

<?php include 'includes/footer.php'; ?>
<?php include 'includes/scripts.php'; ?>