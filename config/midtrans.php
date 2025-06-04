<?php
require_once __DIR__ . '/../load_env.php';
loadEnv(__DIR__ . '/../.env');

function createSnapToken($amount): ?string {
    $serverKey = getenv('MIDTRANS_SERVER_KEY');
    $midtransApi = "https://app.sandbox.midtrans.com/snap/v1/transactions";

    $order_id = "ORDER-" . uniqid();
    
    // Simpan order_id ke session supaya bisa dicek lagi nanti
    $_SESSION['order_id'] = $order_id;

    $params = [
        'transaction_details' => [
            'order_id' => $order_id,
            'gross_amount' => $amount
        ],
        'customer_details' => [
            'first_name' => 'Test',
            'last_name' => 'User',
            'email' => 'test@example.com',
            'phone' => '081234567890'
        ],
        "enabled_payments" => [
            "other_qris"
        ],
        'callbacks' => [
            'finish' => 'http://localhost/hiduplawak/Kodoku/store.php',
            'unfinish' => 'http://localhost/hiduplawak/Kodoku/store.php?status=unpaid',
            'error' => 'http://localhost/hiduplawak/Kodoku/store.php?status=error'
        ]
    ];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $midtransApi);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($params));
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Accept: application/json',
        'Authorization: Basic ' . base64_encode($serverKey . ':')
    ]);
    $response = curl_exec($ch);
    curl_close($ch);

    $result = json_decode($response, true);
    return $result['token'] ?? null;
}