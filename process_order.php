<?php
session_start();
include 'api/db.php';

if (!isset($_SESSION['user_id']) || $_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: index.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// --- UPDATE 1: Capture the phone number from the form ---
$full_name = $_POST['full_name'];
$phone = $_POST['phone']; // <--- New line here
$address = $_POST['address'];
$city = $_POST['city'];
$zip = $_POST['zip'];

try {
    $pdo->beginTransaction();

    // 1. Get items from your database cart table
    $stmt = $pdo->prepare("SELECT p.id, p.price, c.quantity FROM products p JOIN cart c ON p.id = c.product_id WHERE c.user_id = ?");
    $stmt->execute([$user_id]);
    $items = $stmt->fetchAll();

    if (empty($items)) {
        throw new Exception("Your cart is empty.");
    }

    // 2. Calculate final total
    $total = 0;
    foreach ($items as $item) {
        $total += $item['price'] * $item['quantity'];
    }
    if ($total < 100) {
        $total += 7;
    } // Example shipping in TND

    // --- UPDATE 2: Add 'phone' to the INSERT query columns and the execute array ---
    $order_stmt = $pdo->prepare("INSERT INTO orders (user_name, user_email, phone, address, total_price, status) VALUES (?, ?, ?, ?, ?, 'Pending')");
    $order_stmt->execute([
        $full_name,
        $_SESSION['user_email'] ?? 'no-email@test.com',
        $phone, // <--- New variable added to the array
        "$address, $city, $zip",
        $total
    ]);

    $order_id = $pdo->lastInsertId();

    // 4. Move items to order_items
    $item_stmt = $pdo->prepare("INSERT INTO order_items (order_id, product_id, quantity, price_at_purchase) VALUES (?, ?, ?, ?)");
    foreach ($items as $item) {
        $item_stmt->execute([$order_id, $item['id'], $item['quantity'], $item['price']]);
    }

    // 5. Clear the database cart for this user
    $clear_stmt = $pdo->prepare("DELETE FROM cart WHERE user_id = ?");
    $clear_stmt->execute([$user_id]);

    $pdo->commit();

    echo "<script>alert('Order #$order_id Placed Successfully!'); window.location.href='index.php';</script>";
} catch (Exception $e) {
    if ($pdo->inTransaction()) $pdo->rollBack();
    die("Error: " . $e->getMessage());
}
