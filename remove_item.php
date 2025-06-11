<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (isset($_POST['item_id'])) {
    $itemId = $_POST['item_id'];

    // Assuming you're using a session array like $_SESSION['cart']
    if (isset($_SESSION['cart']) && is_array($_SESSION['cart'])) {
        // Remove item with matching ID
        $_SESSION['cart'] = array_filter($_SESSION['cart'], function($item) use ($itemId) {
            return $item['id'] != $itemId;
        });
    }
}

// Redirect back to store or confirmation page
header("Location: store.php");
exit;
