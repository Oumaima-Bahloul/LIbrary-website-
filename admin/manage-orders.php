<?php
session_start();
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}
include '../api/db.php';

// Fetch all orders - Newest first
$stmt = $pdo->query("SELECT * FROM orders ORDER BY order_date DESC");
$orders = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Customer Orders - La Fleur</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        /* Custom table styling to match the new dashboard */
        .table thead {
            background-color: #f8f9fa;
            border-top: 2px solid #dee2e6;
        }

        .order-id {
            font-weight: bold;
            color: #0d6efd;
        }

        .address-cell {
            max-width: 200px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
    </style>
</head>

<body class="bg-light">

    <div class="d-flex">
        <?php include 'sidebar.php'; ?>

        <main class="flex-grow-1 p-4" style="min-height: 100vh;">

            <button id="sidebarToggle" class="btn btn-dark d-md-none mb-3">
                <i class="bi bi-list"></i>
            </button>

            <div class="container-fluid">
                <div class="mb-4">
                    <h2 class="fw-bold mb-1">Customer Orders</h2>
                    <p class="text-muted">Monitor and manage recent customer purchases.</p>
                </div>

                <div class="card border-0 shadow-sm rounded-3 overflow-hidden">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="ps-4">ID</th>
                                    <th>Customer</th>
                                    <th>Email</th>
                                    <th>Address</th>
                                    <th>Total Price</th>
                                    <th>Status</th>
                                    <th class="text-end pe-4">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($orders as $order): ?>
                                    <tr>
                                        <td class="ps-4">
                                            <span class="order-id">#<?php echo $order['id']; ?></span>
                                        </td>
                                        <td class="fw-semibold">
                                            <?php echo htmlspecialchars($order['user_name']); ?>
                                        </td>
                                        <td>
                                            <?php echo htmlspecialchars($order['user_email']); ?>
                                        </td>
                                        <td>
                                            <div class="address-cell text-muted small">
                                                <?php echo htmlspecialchars($order['address']); ?>
                                            </div>
                                        </td>
                                        <td class="fw-bold text-dark">
                                            <?php echo number_format($order['total_price'], 2); ?> DT
                                        </td>
                                        <td>
                                            <?php
                                            // Dynamic badge coloring based on status
                                            $status = strtolower($order['status']);
                                            $badge_class = 'bg-warning text-dark'; // default
                                            if ($status == 'confirmed' || $status == 'delivered') $badge_class = 'bg-success';
                                            if ($status == 'cancelled') $badge_class = 'bg-danger';
                                            ?>
                                            <span class="badge rounded-pill <?php echo $badge_class; ?> px-3">
                                                <?php echo ucfirst($order['status']); ?>
                                            </span>
                                        </td>
                                        <td class="text-end pe-4">
                                            <a href="view-order.php?id=<?php echo $order['id']; ?>" class="btn btn-sm btn-outline-primary rounded shadow-sm">
                                                <i class="bi bi-eye me-1"></i> Details
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                                <?php if (empty($orders)): ?>
                                    <tr>
                                        <td colspan="7" class="text-center py-5 text-muted">
                                            <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                            No orders found.
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>