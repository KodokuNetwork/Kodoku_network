<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// LOAD ENV
require_once __DIR__ . '/load_env.php';
loadEnv(__DIR__ . '/.env');

// DATABASE CONFIG
require_once __DIR__ . '/config/database.php';

function logError($message) {
    $logDir = __DIR__ . '/logs';
    if (!is_dir($logDir)) {
        mkdir($logDir, 0777, true);
    }
    $file = $logDir . '/error_log.txt';
    $time = date('Y-m-d H:i:s');
    file_put_contents($file, "[$time] $message" . PHP_EOL, FILE_APPEND);
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = trim($_POST['name'] ?? '');
    $version = $_POST['version'] ?? '';

    if (empty($name)) {
        logError("Empty username submitted.");
        header("Location: index.php?error=empty_name");
        exit();
    }

    if ($version === 'bedrock') {
        if (strpos($name, '.') !== 0) {
            logError("Bedrock name invalid format: $name");
            $name = '.' . $name;
            header("Location: index.php?error=invalid_bedrock_name");
            exit();
        }
    } elseif ($version === 'java') {
        if (strpos($name, '.') === 0) {
            logError("Java name invalid format: $name");
            header("Location: index.php?error=invalid_java_name");
            exit();
        }
    } else {
        logError("Invalid game version: $version");
        header("Location: index.php?error=invalid_version");
        exit();
    }

    $cleanName = $name;

    try {
        global $luckpermsConn; // opsional, kalau di dalam function
        $stmt = $luckpermsConn->prepare("SELECT uuid, username, primary_group FROM luckperms_players WHERE username = ?");

        if (!$stmt) {
            throw new Exception("Prepare failed: " . $conn->error);
        }

        $stmt->bind_param("s", $cleanName);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows === 0) {
            logError("Username not found in database: $cleanName");
            header("Location: index.php?error=not_found");
            exit();
        }

        $stmt->bind_result($uuid, $username, $primary_group);
        $stmt->fetch();

        // Successful login - save session
        $_SESSION['name'] = htmlspecialchars($name);
        $_SESSION['version'] = $version;
        $_SESSION['uuid'] = $uuid;
        $_SESSION['primary_group'] = $primary_group;

        logError("Login success: $username ($uuid), group: $primary_group");
        header("Location: store.php");
        exit();

    } catch (Exception $e) {
        logError("Database error: " . $e->getMessage());
        header("Location: index.php?error=server_error");
        exit();
    }
} else {
    logError("Invalid access method to login_process.php");
    header("Location: index.php");
    exit();
}
