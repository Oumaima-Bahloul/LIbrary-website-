<?php
session_start();
include 'api/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch cart items to show the summary
$stmt = $pdo->prepare("SELECT p.*, c.quantity FROM products p JOIN cart c ON p.id = c.product_id WHERE c.user_id = ?");
$stmt->execute([$user_id]);
$cart_items = $stmt->fetchAll();

if (empty($cart_items)) {
    header("Location: cart.php");
    exit();
}

// Calculate totals
$grand_total = 0;
foreach ($cart_items as $item) {
    $grand_total += ($item['price'] * $item['quantity']);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Checkout - La Fleur</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">
    <div class="container py-5">
        <div class="row g-5">
            <div class="col-md-7 col-lg-8">
                <h4 class="mb-3">Shipping Address</h4>
                <form action="process_order.php" method="POST" class="needs-validation">
                    <div class="row g-3">
                        <div class="col-sm-6">
                            <label class="form-label">Full Name</label>
                            <input type="text" class="form-control" name="full_name" value="<?php echo htmlspecialchars($_SESSION['user_name']); ?>" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Phone Number</label>
                            <div class="input-group">
                                <span class="input-group-text">+216</span>
                                <input type="tel" class="form-control" name="phone" placeholder="99 999 999" required>
                            </div>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Address</label>
                            <input type="text" class="form-control" name="address" placeholder="1234 Main St" required>
                        </div>

                        <div class="col-md-5">
                            <label class="form-label">City</label>
                            <select class="form-select" name="city" required>
                                <option value="">Choose...</option>
                                <option>Tunis</option>
                                <option>Sousse</option>
                                <option>Sfax</option>
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">Zip</label>
                            <input type="text" class="form-control" name="zip" required>
                        </div>
                    </div>

                    <hr class="my-4">

                    <h4 class="mb-3">Payment</h4>
                    <div class="my-3">
                        <div class="form-check">
                            <input id="credit" name="paymentMethod" type="radio" class="form-check-input" checked required>
                            <label class="form-check-label" for="credit">Cash on Delivery</label>
                        </div>
                    </div>

                    <button class="w-100 btn btn-primary btn-lg rounded-pill" type="submit">Place Order</button>
                </form>
            </div>

            <div class="col-md-5 col-lg-4 order-md-last">
                <h4 class="d-flex justify-content-between align-items-center mb-3">
                    <span class="text-primary">Your cart</span>
                    <span class="badge bg-primary rounded-pill"><?php echo count($cart_items); ?></span>
                </h4>
                <ul class="list-group mb-3">
                    <?php foreach ($cart_items as $item): ?>
                        <li class="list-group-item d-flex justify-content-between lh-sm">
                            <div>
                                <h6 class="my-0"><?php echo htmlspecialchars($item['title']); ?></h6>
                                <small class="text-muted">Qty: <?php echo $item['quantity']; ?></small>
                            </div>
                            <span class="text-muted"><?php echo number_format($item['price'] * $item['quantity']); ?> TND</span>
                        </li>
                    <?php endforeach; ?>

                    <li class="list-group-item d-flex justify-content-between bg-light">
                        <div class="text-success">
                            <h6 class="my-0">Promo code</h6>
                            <small>EXAMPLECODE</small>
                        </div>
                        <span class="text-success">−0 TND</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between">
                        <span>Total (TND)</span>
                        <strong><?php echo number_format($grand_total); ?> TND</strong>
                    </li>
                </ul>

                <form class="card p-2">
                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="Promo code">
                        <button type="submit" class="btn btn-secondary">Redeem</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>

</html>