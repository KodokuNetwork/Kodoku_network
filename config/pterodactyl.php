<?php
require_once __DIR__ . '/../load_env.php';
loadEnv(__DIR__ . '/../.env');

// Load environment variables
$apiKey     = getenv('PTERO_API_KEY');
$serverUUID = getenv('PTERO_SERVER_UUID');
$panelUrl   = rtrim(getenv('PTERO_PANEL_URL'), '/');

if (!$apiKey || !$serverUUID || !$panelUrl) {
    die("❌ Missing required .env variables for Pterodactyl connection.");
}

/**
 * Sends a command to the Minecraft server via Pterodactyl API.
 *
 * @param string $command The command to send (e.g. "say Hello!")
 * @return array Response or error array
 */
function sendCommandToServer($command)
{
    global $apiKey, $serverUUID, $panelUrl;

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "$panelUrl/api/client/servers/$serverUUID/command");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(['command' => $command]));
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Authorization: Bearer ' . $apiKey,
        'Accept: application/json',
        'Content-Type: application/json'
    ]);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);
    curl_close($ch);

    if ($error) {
        return ['error' => "CURL error: $error"];
    } elseif ($httpCode >= 400) {
        return [
            'error' => "HTTP $httpCode",
            'response' => $response
        ];
    }

    return ['success' => true, 'response' => $response];
}
