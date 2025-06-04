<?php
require_once __DIR__ . '/../load_env.php'; // adjust path if needed
loadEnv(__DIR__ . '/../.env');             // make sure path is correct


// First DB - MySQLi (LuckPerms)
$host     = getenv('DB1_HOST');
$port     = getenv('DB1_PORT');
$dbname   = getenv('DB1_NAME');
$username = getenv('DB1_USER');
$password = getenv('DB1_PASS');

$conn = new mysqli($host, $username, $password, $dbname, $port);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Second DB - PDO (Store Items)
$storeHost = getenv('DB2_HOST');
$storePort = getenv('DB2_PORT');
$storeDb   = getenv('DB2_NAME');
$storeUser = getenv('DB2_USER');
$storePass = getenv('DB2_PASS');

try {
    $pdo = new PDO("mysql:host=$storeHost;port=$storePort;dbname=$storeDb;charset=utf8mb4", $storeUser, $storePass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $createTableSQL = "
        CREATE TABLE IF NOT EXISTS store_items (
            id INT AUTO_INCREMENT PRIMARY KEY,
            amount INT NOT NULL,
            price VARCHAR(50) NOT NULL,
            price_value INT NOT NULL,
            bg_class VARCHAR(50),
            badge VARCHAR(100),
            command_to_execute TEXT
        );
    ";
    $pdo->exec($createTableSQL);
} catch (PDOException $e) {
    echo "❌ Error: " . $e->getMessage();
}


// Third DB - PDO (Payments)
$payHost = getenv('DB3_HOST');
$payPort = getenv('DB3_PORT');
$payDb   = getenv('DB3_NAME');
$payUser = getenv('DB3_USER');
$payPass = getenv('DB3_PASS');

try {
    $paymentPdo = new PDO("mysql:host=$payHost;port=$payPort;dbname=$payDb;charset=utf8mb4", $payUser, $payPass);
    $paymentPdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Create payments table if not exists
    $createTableSQL = "
    CREATE TABLE IF NOT EXISTS payments (
        id INT AUTO_INCREMENT PRIMARY KEY,
        midtrans_order_id VARCHAR(64) NOT NULL,
        nickname VARCHAR(32) NOT NULL,
        product_name VARCHAR(128) NOT NULL,
        price INT NOT NULL,
        tanggal_pembelian DATETIME DEFAULT CURRENT_TIMESTAMP
    );
";
    $paymentPdo->exec($createTableSQL);
} catch (PDOException $e) {
    echo "❌ Payment DB Error: " . $e->getMessage();
}