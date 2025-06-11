<?php
require_once __DIR__ . '/config/midtrans.php';
header('Content-Type: application/json');

$input = json_decode(file_get_contents('php://input'), true);
$amount = $input['amount'] ?? 0;

if ($amount < 15000) {
    echo json_encode(['error' => 'Amount too low.']);
    exit;
}

$token = createSnapToken($amount);
echo json_encode(['token' => $token]);
