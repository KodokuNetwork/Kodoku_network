<?php
require_once __DIR__ . '/../load_env.php';
loadEnv(__DIR__ . '/../.env');

// === [1] LuckPerms DB - MySQLi ===
$lpHost = getenv('DB1_HOST');
$lpPort = getenv('DB1_PORT');
$lpName = getenv('DB1_NAME');
$lpUser = getenv('DB1_USER');
$lpPass = getenv('DB1_PASS');

$luckpermsConn = new mysqli($lpHost, $lpUser, $lpPass, $lpName, $lpPort);
if ($luckpermsConn->connect_error) {
    die("❌ Connection failed (LuckPerms): " . $luckpermsConn->connect_error);
}

// OPTIONAL alias if needed by old code:
$conn = $luckpermsConn;


// === [2] Store Items DB - PDO ===
$storeHost = getenv('DB2_HOST');
$storePort = getenv('DB2_PORT');
$storeDb   = getenv('DB2_NAME');
$storeUser = getenv('DB2_USER');
$storePass = getenv('DB2_PASS');

try {
    $pdo = new PDO("mysql:host=$storeHost;port=$storePort;dbname=$storeDb;charset=utf8mb4", $storeUser, $storePass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $pdo->exec("
        CREATE TABLE IF NOT EXISTS store_items (
            id INT AUTO_INCREMENT PRIMARY KEY,
            amount INT NOT NULL,
            price VARCHAR(50) NOT NULL,
            price_value INT NOT NULL,
            bg_class VARCHAR(50),
            badge VARCHAR(100),
            command_to_execute TEXT
        );
    ");
} catch (PDOException $e) {
    echo "❌ Store DB Error: " . $e->getMessage();
}


// === [3] Payment DB - PDO ===
$payHost = getenv('DB3_HOST');
$payPort = getenv('DB3_PORT');
$payDb   = getenv('DB3_NAME');
$payUser = getenv('DB3_USER');
$payPass = getenv('DB3_PASS');

try {
    $paymentPdo = new PDO("mysql:host=$payHost;port=$payPort;dbname=$payDb;charset=utf8mb4", $payUser, $payPass);
    $paymentPdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $paymentPdo->exec("
        CREATE TABLE IF NOT EXISTS payments (
            id INT AUTO_INCREMENT PRIMARY KEY,
            midtrans_order_id VARCHAR(64) NOT NULL,
            nickname VARCHAR(32) NOT NULL,
            product_name VARCHAR(128) NOT NULL,
            price INT NOT NULL,
            tanggal_pembelian DATETIME DEFAULT CURRENT_TIMESTAMP
        );
    ");
} catch (PDOException $e) {
    echo "❌ Payment DB Error: " . $e->getMessage();
}


// === [4] News DB - MySQLi ===
$newsHost = getenv("DB4_HOST");
$newsPort = getenv("DB4_PORT") ?: 3306;
$newsDb   = getenv("DB4_NAME");
$newsUser = getenv("DB4_USER");
$newsPass = getenv("DB4_PASS");

$newsConn = new mysqli($newsHost, $newsUser, $newsPass, $newsDb, $newsPort);
if ($newsConn->connect_error) {
    die("❌ Connection failed (News): " . $newsConn->connect_error);
}

// === [5] Redeem Code DB - MySQLi ===
$redeemHost = getenv("DB5_HOST");
$redeemPort = getenv("DB5_PORT") ?: 3306;
$redeemDb   = getenv("DB5_NAME");
$redeemUser = getenv("DB5_USER");
$redeemPass = getenv("DB5_PASS");

$redeemConn = new mysqli($redeemHost, $redeemUser, $redeemPass, $redeemDb, $redeemPort);
if ($redeemConn->connect_error) {
    die("❌ Connection failed (Redeem): " . $redeemConn->connect_error);
}