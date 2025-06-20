<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// LOAD ENV
require_once __DIR__ . '/load_env.php';
loadEnv(__DIR__ . '/.env');

// DATABASE CONFIG
require_once __DIR__ . '/config/database.php'; // This file should define $luckpermsConn (mysqli object)

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

    $originalInputName = $name; // Store the original input for potential error messages

    // --- Validate and prepare name based on version ---
    $nameForLuckPermsLookup = $name; // This will be the value passed to strtolower() for DB lookup

    if ($version === 'bedrock') {
        // *** MODIFICATION START ***
        // Check if the name DOES NOT start with a dot
        if (strpos($originalInputName, '.') !== 0) {
            // If it doesn't, prepend the dot.
            // Update $nameForLuckPermsLookup as this is what's used for the DB query.
            $nameForLuckPermsLookup = '.' . $originalInputName;
            logError("Auto-corrected Bedrock name: $originalInputName to $nameForLuckPermsLookup");
            // No exit here, continue with the corrected name.
        } else {
            // If it already starts with a dot, just use the original input name for lookup
            $nameForLuckPermsLookup = $originalInputName;
        }
        // *** MODIFICATION END ***

    } elseif ($version === 'java') {
        if (strpos($originalInputName, '.') === 0) { // Check original input
            logError("Java name invalid format: $originalInputName");
            header("Location: index.php?error=invalid_java_name");
            exit();
        }
        $nameForLuckPermsLookup = $originalInputName; // Java names shouldn't have a dot
    } else {
        logError("Invalid game version: $version (Original name: $originalInputName)");
        header("Location: index.php?error=invalid_version");
        exit();
    }

    // --- Perform LuckPerms Database Lookup (case-insensitive) ---
    try {
        global $luckpermsConn;

        $stmt = $luckpermsConn->prepare("SELECT uuid, username, primary_group FROM luckperms_players WHERE LOWER(username) = ?");

        if (!$stmt) {
            throw new Exception("Prepare failed: " . $luckpermsConn->error);
        }

        $stmt->bind_param("s", strtolower($nameForLuckPermsLookup)); // Bind the lowercase value
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows === 0) {
            logError("Username not found in LuckPerms DB: " . strtolower($nameForLuckPermsLookup) . " (Original Input: $originalInputName)");
            header("Location: index.php?error=not_found");
            exit();
        }

        $stmt->bind_result($uuid, $usernameFromDb, $primary_group);
        $stmt->fetch();

        // --- Determine Final Canonical Display Name for Session ---
        $finalDisplayName = $usernameFromDb;

        if ($version === 'java') {
            $mojang_api_url = "https://api.mojang.com/users/profiles/minecraft/" . urlencode($usernameFromDb);
            $mojang_response = @file_get_contents($mojang_api_url);

            if ($mojang_response !== false) {
                $mojang_data = json_decode($mojang_response, true);
                if (isset($mojang_data['name']) && !empty($mojang_data['name'])) {
                    $finalDisplayName = $mojang_data['name'];
                }
            } else {
                 logError("Mojang API lookup failed for Java user: $usernameFromDb");
            }
        }
        
        // --- Successful login - Save session ---
        $_SESSION['name'] = htmlspecialchars($finalDisplayName);
        $_SESSION['version'] = $version;
        $_SESSION['uuid'] = $uuid;
        $_SESSION['primary_group'] = $primary_group;

        logError("Login success: " . $_SESSION['name'] . " ($uuid), group: $primary_group, version: $version");
        header("Location: store.php");
        exit();

    } catch (Exception $e) {
        logError("Database error during LuckPerms lookup: " . $e->getMessage() . " (Original Input: $originalInputName)");
        header("Location: index.php?error=server_error");
        exit();
    }

} else {
    logError("Invalid access method to login_process.php");
    header("Location: index.php");
    exit();
}