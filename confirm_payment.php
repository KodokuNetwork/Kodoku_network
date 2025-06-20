<?php
require_once __DIR__ . '/config/pterodactyl.php';

header('Content-Type: application/json');

// === Ambil data dari POST ===
$orderId = $_POST['order_id'] ?? '';
$name    = $_POST['name'] ?? '';
$item    = (int) ($_POST['item'] ?? 0);

// === Validasi dasar ===
if (!$orderId || !$name || !$item) {
  echo json_encode(['success' => false, 'message' => '❌ Data tidak lengkap']);
  exit;
}

// === Fungsi log sederhana ===
function logPayment($message) {
  $logDir = __DIR__ . '/logs';
  if (!is_dir($logDir)) mkdir($logDir, 0777, true);
  file_put_contents($logDir . '/payment_log.txt', "[" . date('Y-m-d H:i:s') . "] $message" . PHP_EOL, FILE_APPEND);
}

// === Kirim command ke Pterodactyl ===
logPayment("✅ Payment confirmed manually | Order: $orderId | Player: $name | Item: $item");

$command = "credits give $name $item";
$response = sendCommandToServer($command);

if (isset($response['error'])) {
  logPayment("❌ Command failed: " . $response['error']);
  echo json_encode(['success' => false, 'message' => "❌ Command failed: " . $response['error']]);
} else {
  logPayment("✅ Command executed: $command");
  echo json_encode(['success' => true, 'message' => "✅ $item K-Bucks berhasil dikirim ke $name"]);
}
