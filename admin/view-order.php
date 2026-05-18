<?php
session_start();
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}
include '../api/db.php';

$order_id = $_GET['id'];
$message = "";
$shipping_fee = 8000;
// --- NEW: Handle Status Update ---
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_status'])) {
    $new_status = $_POST['status'];
    $update_stmt = $pdo->prepare("UPDATE orders SET status = ? WHERE id = ?");
    if ($update_stmt->execute([$new_status, $order_id])) {
        $message = "<div class='alert alert-success border-0 shadow-sm'>Order status updated to <strong>$new_status</strong></div>";
    }
}

// 1. Fetch Order Info
$stmt = $pdo->prepare("SELECT * FROM orders WHERE id = ?");
$stmt->execute([$order_id]);
$order = $stmt->fetch();

if (!$order) {
    die("Order not found.");
}
// Set Subtotal and Grand Total
$subtotal = $order['total_price'] ?? 0;
if ($subtotal < 100000) {
    $shipping_fee = 8000;
} else {
    $shipping_fee = 0;
}
$total_price = $subtotal + $shipping_fee;
// 2. Fetch Items in this Order
$items_stmt = $pdo->prepare("
    SELECT oi.*, p.title, p.image 
    FROM order_items oi 
    JOIN products p ON oi.product_id = p.id 
    WHERE oi.order_id = ?
");
$items_stmt->execute([$order_id]);
$items = $items_stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Order #<?php echo $order_id; ?> Details</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
</head>

<body class="bg-light">

    <div class="d-flex">
        <?php include 'sidebar.php'; ?>

        <main class="flex-grow-1 p-4">
            <div class="container-fluid">

                <div class="d-flex justify-content-between align-items-center mb-4">
                    <a href="manage-orders.php" class="btn btn-outline-secondary border-0">
                        <i class="bi bi-arrow-left"></i> Back to Orders
                    </a>
                    <h2 class="fw-bold mb-0">Order #<?php echo $order_id; ?></h2>
                </div>

                <?php echo $message; ?>

                <div class="row">
                    <div class="col-md-8">
                        <div class="card shadow-sm border-0 mb-4">
                            <div class="card-header bg-white py-3">
                                <h5 class="mb-0 fw-bold">Items in Order</h5>
                            </div>
                            <div class="card-body">
                                <table class="table align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Product</th>
                                            <th>Price</th>
                                            <th>Qty</th>
                                            <th>Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($items as $item): ?>
                                            <tr>
                                                <td>
                                                    <?php
                                                    $img_name = basename($item['image']);
                                                    if (strpos($item['image'], 'http') === 0) {
                                                        $src = $item['image'];
                                                    } elseif (file_exists("../catalogue/pics/" . $img_name)) {
                                                        $src = "../catalogue/pics/" . $img_name;
                                                    } else {
                                                        $src = "../images/" . $img_name;
                                                    }
                                                    ?>
                                                    <img src="<?php echo $src; ?>" width="50" class="me-3 rounded shadow-sm">
                                                    <span class="fw-semibold"><?php echo htmlspecialchars($item['title']); ?></span>
                                                </td>
                                                <td><?php echo number_format($item['price_at_purchase']); ?> DT</td>
                                                <td><?php echo $item['quantity']; ?></td>
                                                <td class="fw-bold"><?php echo number_format($item['price_at_purchase'] * $item['quantity']); ?> DT</td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                    <tfoot class="table-light">
                                        <tr>
                                            <td colspan="3" class="text-end text-muted">Sub-total:</td>
                                            <td class="fw-bold"><?php echo number_format($subtotal); ?> DT</td>
                                        </tr>
                                        <tr>
                                            <td colspan="3" class="text-end text-muted">Shipping Fee:</td>
                                            <td class="fw-bold"><?php echo number_format($shipping_fee); ?> DT</td>
                                        </tr>
                                        <tr>
                                            <td colspan="3" class="text-end fw-bold">Total Order Amount:</td>
                                            <td class="fw-bold text-primary" style="font-size: 1.2rem;">
                                                <?php echo number_format($total_price); ?> DT
                                            </td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>

                        <div class="card shadow-sm border-0 mt-4">
                            <div class="card-header bg-dark text-white py-3">
                                <h6 class="mb-0 fw-bold"><i class="bi bi-person-fill me-2"></i>Customer Delivery Info</h6>
                            </div>
                            <div class="card-body p-4">
                                <div class="row g-4">
                                    <div class="col-md-4">
                                        <label class="small text-muted text-uppercase fw-bold d-block mb-1">Customer Name</label>
                                        <p class="fw-semibold h6 mb-0"><?php echo htmlspecialchars($order['user_name'] ?? 'N/A'); ?></p>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="small text-muted text-uppercase fw-bold d-block mb-1">Contact Details</label>
                                        <p class="mb-0 text-dark"><?php echo htmlspecialchars($order['user_email'] ?? ''); ?></p>
                                        <p class="mb-0 text-primary fw-bold"><?php echo htmlspecialchars($order['phone'] ?? ''); ?></p>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="small text-muted text-uppercase fw-bold d-block mb-1">Shipping Address</label>
                                        <p class="mb-0 lh-sm"><?php echo nl2br(htmlspecialchars($order['address'] ?? '')); ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="card shadow-sm border-0">
                            <div class="card-header bg-white py-3">
                                <h5 class="mb-0 fw-bold">Action: Update Status</h5>
                            </div>
                            <div class="card-body">
                                <form method="POST">
                                    <div class="mb-4">
                                        <label class="form-label small fw-bold">Current Status:</label>
                                        <?php
                                        $badge_class = "bg-warning text-dark"; // Default for pending
                                        if ($order['status'] == 'Shipped') $badge_class = "bg-primary";
                                        if ($order['status'] == 'Delivered') $badge_class = "bg-success";
                                        if ($order['status'] == 'Cancelled') $badge_class = "bg-danger";
                                        ?>
                                        <div><span class="badge <?php echo $badge_class; ?> px-3 py-2 fs-6 mb-3"><?php echo $order['status']; ?></span></div>

                                        <label class="form-label small fw-bold">Update To:</label>
                                        <select name="status" class="form-select mb-3 shadow-sm">
                                            <option value="Pending" <?php if ($order['status'] == 'Pending') echo 'selected'; ?>>Pending</option>
                                            <option value="Shipped" <?php if ($order['status'] == 'Shipped') echo 'selected'; ?>>Shipped</option>
                                            <option value="Delivered" <?php if ($order['status'] == 'Delivered') echo 'selected'; ?>>Delivered</option>
                                            <option value="Cancelled" <?php if ($order['status'] == 'Cancelled') echo 'selected'; ?>>Cancelled</option>
                                        </select>
                                    </div>
                                    <button type="submit" name="update_status" class="btn btn-primary w-100 shadow-sm py-2">
                                        <i class="bi bi-arrow-repeat me-2"></i>Apply New Status
                                    </button>
                                </form>
                                <div class="card border border-light-subtle shadow-sm rounded-3 bg-white mt-2">
                                    <div class="card-body p-3 text-center">
                                        <h6 class="mb-1 fw-bold text-dark small">Invoice Document</h6>
                                        <p class="text-muted mb-3" style="font-size: 0.75rem;">Generate and print a physical receipt copy for this order.</p>
                                        <button type="button" onclick="window.print()" class="btn btn-sm btn-outline-dark rounded-pill px-4 w-100">
                                            <i class="bi bi-printer me-2"></i>Print Receipt
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>