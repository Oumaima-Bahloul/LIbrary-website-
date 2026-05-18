<?php
session_start();
include 'api/db.php'; // Make sure this path is correct

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$product_id = $_GET['id'] ?? 0;
$action = $_GET['action'] ?? '';

if ($product_id > 0) {
    switch ($action) {
        case 'add':
            // 1. Check if the item is already in the cart
            $check = $pdo->prepare("SELECT * FROM cart WHERE user_id = ? AND product_id = ?");
            $check->execute([$user_id, $product_id]);
            $existing_item = $check->fetch();

            if ($existing_item) {
                // 2. If it exists, just increase quantity by 1
                $update = $pdo->prepare("UPDATE cart SET quantity = quantity + 1 WHERE user_id = ? AND product_id = ?");
                $update->execute([$user_id, $product_id]);
            } else {
                // 3. If it's new, insert it with quantity 1
                $insert = $pdo->prepare("INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, 1)");
                $insert->execute([$user_id, $product_id]);
            }
            break;

        case 'increase':
            $pdo->prepare("UPDATE cart SET quantity = quantity + 1 WHERE user_id = ? AND product_id = ?")->execute([$user_id, $product_id]);
            break;

        case 'decrease':
            $pdo->prepare("UPDATE cart SET quantity = quantity - 1 WHERE user_id = ? AND product_id = ? AND quantity > 1")->execute([$user_id, $product_id]);
            break;

        case 'remove':
            $pdo->prepare("DELETE FROM cart WHERE user_id = ? AND product_id = ?")->execute([$user_id, $product_id]);
            break;
    }
}

// REDIRECT: This is the important part
// If they were on the cart page, go back to cart. If they were shopping, stay on the store.
// 1. Get the page the user came from
$previous_page = $_SERVER['HTTP_REFERER'] ?? 'index.php';

// 2. Redirect back to that specific page
header("Location: " . $previous_page);
exit();
