<?php

require_once __DIR__ . '/../load_env.php';

loadEnv(__DIR__ . '/../.env');



function createSnapToken(array $data): ?string {
    $serverKey = getenv('MIDTRANS_SERVER_KEY');
    $midtransApi = "https://app.sandbox.midtrans.com/snap/v1/transactions";

    $order_id = $data['order_id'];
    $amount = $data['amount'];
    $item_name = $data['item_name'];
    $item_id = $data['item_id'];

    $_SESSION['order_id'] = $order_id;

    $params = [
        'transaction_details' => [
            'order_id' => $order_id,
            'gross_amount' => $amount
        ],
        'item_details' => [[
            'id' => $item_id,
            'price' => $amount,
            'quantity' => 1,
            'name' => $item_name
        ]],
        'customer_details' => [
            'first_name' => $_SESSION['name'] ?? 'Guest',
            'email' => 'dummy@example.com',
            'phone' => '081234567890'
        ],
        "enabled_payments" => ["other_qris"],
        'callbacks' => [
            'finish' => 'https://store.kodoku.me/store.php',
            'unfinish' => 'https://store.kodoku.me/store.php?status=unpaid',
            'error' => 'https://store.kodoku.me/store.php?status=error'
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