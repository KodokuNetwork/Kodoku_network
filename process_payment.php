<?php
session_start(); // Mulai sesi

// Cek apakah user sudah login
if (!isset($_SESSION['name'])) {
    // Jika tidak login, arahkan ke halaman login atau beri pesan
    header('Location: login.php');  // Ganti dengan URL halaman login kamu
    exit();  // Stop eksekusi lebih lanjut
}

require_once __DIR__ . '/config/pterodactyl.php';

header('Content-Type: application/json');

// === Ambil data dari POST ===
$orderId = $_POST['order_id'] ?? '';
$name = $_SESSION['name']; // Ambil dari session
$item = $_POST['item'] ?? 0;

if (!$orderId || !$name || !$item) {
    echo json_encode(['success' => false, 'message' => '❌ Data tidak lengkap']);
    exit;
}

// === Fungsi log sederhana ===
function logPayment($message) {
    $logDir = __DIR__ . '/logs';
    if (!is_dir($logDir)) mkdir($logDir, 0777, true);
    $file = $logDir . '/payment_log.txt';
    file_put_contents($file, "[" . date('Y-m-d H:i:s') . "] $message" . PHP_EOL, FILE_APPEND);
}

// === Kirim webhook ke Discord ===
function sendDiscordOrderEmbed($orderId, $player, $amount)
{
    $webhookUrl = 'https://discord.com/api/webhooks/1384199961981227109/Z5OK7DXTN6-2AoSBV9ynt8J2pzcFF1eav8mwasmMH9p29sTXKvlIpZIDikNR-zGQVAPS'; // Ganti webhook

    $jsonData = json_encode([
        "content" => "@here 🎟️ Order baru masuk. Silakan buka tiket untuk melanjutkan pembayaran!",
        "embeds" => [[
            "title" => "🛒 Order Diajukan",
            "description" => "**Player:** $player\n**Jumlah:** $amount K-Bucks\n**Order ID:** `$orderId`\nStatus: Belum dibayar",
            "color" => hexdec("FFD700"),
            "timestamp" => date("c")
        ]],
        "components" => [[
            "type" => 1,
            "components" => [[
                "type" => 2,
                "label" => "✅ Tandai sebagai Dibayar",
                "style" => 3,
                "custom_id" => "confirm_$orderId"
            ]]
        ]]
    ]);

    $ch = curl_init($webhookUrl);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_exec($ch);
    curl_close($ch);
}

// === Prevent re-send on refresh ===
if (empty($_SESSION['sent_orders']) || !in_array($orderId, $_SESSION['sent_orders'])) {
    sendDiscordOrderEmbed($orderId, $name, $item);
    $_SESSION['sent_orders'][] = $orderId;
    $_SESSION['order_id'] = $orderId;
}

// === Kirim perintah ke server Minecraft ===
logPayment("🔔 Payment confirmed | Order: $orderId | User: $name | Item: $item");

$command = "credits give $name $item";
$response = sendCommandToServer($command);

if (isset($response['error'])) {
    logPayment("❌ Command failed: " . $response['error']);
    echo json_encode(['success' => false, 'message' => "❌ Command failed: " . $response['error']]);
} else {
    logPayment("✅ Command executed: $command");

    // Hapus session setelah perintah berhasil
    unset($_SESSION['item']);
    unset($_SESSION['order_id']);
    unset($_SESSION['webhook_sent']);

    echo json_encode(['success' => true, 'message' => "✅ $item K-Bucks berhasil dikirim ke $name"]);
}

?>
<style>
    .kodoku-invoice-card {
        background: #0f0f0f;
        border-radius: 16px;
        box-shadow: 0 0 12px rgba(255, 215, 0, 0.2);
        padding: 30px;
        color: #fff;
    }

    .kodoku-invoice-card strong {
        color: #FFD700;
    }

    .btn-primary.bit-font {
        background-color: #FFD700;
        border: none;
        color: #000;
        font-weight: bold;
    }

    .btn-outline-secondary.bit-font {
        border-color: #FFD700;
        color: #FFD700;
    }

    .btn-outline-secondary.bit-font:hover {
        background: #FFD700;
        color: #000;
    }

    .text-warning-custom {
        color: #FF4D4D;
        font-weight: bold;
    }

    .bg-kodoku-dark {
        background-color: #000;
    }

    .bit-font {
        font-family: 'BitFont', sans-serif;
    }
</style>

<main class="flex-fill bg-kodoku-dark">
    <div class="container py-5">
        <div class="mx-auto" style="max-width: 540px;">
            <div class="kodoku-invoice-card">
                <h3 class="text-center mb-4 bit-font">🎟️ ORDER DIBUAT</h3>
                <p class="text-center mb-4" style="font-size: 13px;">
                    Order telah tercatat namun <span class="text-warning-custom">belum dibayar</span>.<br>
                    Silakan lanjutkan proses pembayaran melalui tiket Discord.
                </p>

                <hr class="border-warning-subtle mb-4">

                <ul class="list-unstyled fs-6 mb-4">
                    <li><span class="bit-font">Order ID:</span> <?= htmlspecialchars($orderId) ?></li>
                    <li><span class="bit-font">Player:</span> <?= htmlspecialchars($name) ?></li>
                    <li><span class="bit-font">K-Bucks:</span> <?= htmlspecialchars($item) ?></li>
                </ul>

                <div class="d-grid gap-3">
                    <a href="https://discord.gg/WbafFVcVdv" target="_blank" class="btn btn-primary w-100" style="background-color: #FFD700; border: none; color: #000; font-weight: bold;">
                        🎫 Buka Tiket Discord
                    </a>
                </div>
            </div>
        </div>
    </div>
</main>

<?php
include 'includes/footer.php';
include 'includes/scripts.php';
?>