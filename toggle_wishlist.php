<?php
session_start();
include 'api/db.php';

if (isset($_SESSION['user_id']) && isset($_GET['id'])) {
    $user_id = $_SESSION['user_id'];
    $product_id = $_GET['id'];

    // 1. Check if THIS SPECIFIC product is already in the wishlist
    $check = $pdo->prepare("SELECT * FROM wishlist WHERE user_id = ? AND product_id = ?");
    $check->execute([$user_id, $product_id]);

    if ($check->rowCount() > 0) {
        // 2. If it exists, remove ONLY this product
        $delete = $pdo->prepare("DELETE FROM wishlist WHERE user_id = ? AND product_id = ?");
        $delete->execute([$user_id, $product_id]);
    } else {
        // 3. If it doesn't exist, add it
        $insert = $pdo->prepare("INSERT INTO wishlist (user_id, product_id) VALUES (?, ?)");
        $insert->execute([$user_id, $product_id]);
    }
}

// Redirect back to where the user was
header("Location: " . $_SERVER['HTTP_REFERER']);
exit();
