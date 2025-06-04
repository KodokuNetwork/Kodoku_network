<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/config/pterodactyl.php';

header('Content-Type: application/json');

function logPayment($message) {
    $logDir = __DIR__ . '/logs';
    if (!is_dir($logDir)) mkdir($logDir, 0777, true);
    $file = $logDir . '/payment_log.txt';
    file_put_contents($file, "[" . date('Y-m-d H:i:s') . "] $message" . PHP_EOL, FILE_APPEND);
}

$rawBody = file_get_contents("php://input");
$data = json_decode($rawBody, true);

$status = $data['transaction_status'] ?? '';
$orderId = $data['order_id'] ?? 'UNKNOWN_ORDER';

$name = $_SESSION['name'] ?? 'UnknownUser';
$item = $_SESSION['item'] ?? 0;

logPayment("🔔 Payment received | Order: $orderId | User: $name | Status: $status | Item: $item");

if (in_array($status, ['capture', 'settlement'])) {
    $command = "credits give $name $item";
    $response = sendCommandToServer($command);

    if (isset($response['error'])) {
        logPayment("❌ Command failed: " . $response['error']);
        echo json_encode(['success' => false, 'message' => "❌ Command failed: " . $response['error']]);
    } else {
        logPayment("✅ Command sent: $command");
        echo json_encode(['success' => true, 'message' => "✅ $item credits sent to $name."]);
    }
} else {
    logPayment("❌ Payment not completed. Status: $status");
    echo json_encode(['success' => false, 'message' => "❌ Payment not completed."]);
}
