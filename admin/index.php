<?php
session_start();
// Basic security check
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}
include '../api/db.php';

// Fetch your dashboard stats
$total_products = $pdo->query("SELECT COUNT(*) FROM products")->fetchColumn();
$total_users = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
$total_orders = $pdo->query("SELECT COUNT(*) FROM orders")->fetchColumn();

// --- NEW: Data for Category Pie Chart ---
$cat_query = $pdo->query("SELECT category, COUNT(*) as count FROM products GROUP BY category");
$cat_data = $cat_query->fetchAll(PDO::FETCH_ASSOC);

$cat_labels = [];
$cat_counts = [];
foreach ($cat_data as $row) {
    $cat_labels[] = $row['category'];
    $cat_counts[] = $row['count'];
}

// --- NEW: Data for Orders Bar Chart (Last 6 months) ---
$order_query = $pdo->query("SELECT DATE_FORMAT(created_at, '%b') as month, COUNT(*) as total 
                            FROM orders 
                            GROUP BY month 
                            ORDER BY created_at ASC 
                            LIMIT 6");
$order_data = $order_query->fetchAll(PDO::FETCH_ASSOC);

$month_labels = [];
$order_counts = [];
foreach ($order_data as $row) {
    $month_labels[] = $row['month'];
    $order_counts[] = $row['total'];
}
// --- NEW: Data for Revenue Line Chart ---
$revenue_query = $pdo->query("SELECT DATE_FORMAT(created_at, '%b') as month, SUM(total_price) as total_revenue 
                              FROM orders 
                              GROUP BY month 
                              ORDER BY created_at ASC 
                              LIMIT 6");
$revenue_data = $revenue_query->fetchAll(PDO::FETCH_ASSOC);

$rev_labels = [];
$rev_amounts = [];
foreach ($revenue_data as $row) {
    $rev_labels[] = $row['month'];
    $rev_amounts[] = $row['total_revenue'];
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard - La Fleur</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body class="bg-light">

    <div class="d-flex">
        <?php
        if (file_exists('sidebar.php')) {
            include 'sidebar.php';
        } else {
            echo "<div class='bg-danger text-white p-3'>Error: sidebar.php not found!</div>";
        }
        ?>

        <div class="flex-grow-1 p-4">
            <button id="sidebarToggle" class="btn btn-dark d-md-none mb-3">
                <i class="bi bi-list"></i>
            </button>

            <div class="container-fluid">
                <h2 class="fw-bold mb-4">Admin Dashboard - La Fleur</h2>

                <div class="row g-4 mb-4">
                    <div class="col-md-4">
                        <div class="card shadow-sm border-0 bg-primary text-white p-3 h-100">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-uppercase mb-1" style="font-size: 0.8rem;">Total Products</h6>
                                    <h2 class="mb-0 fw-bold"><?php echo $total_products; ?></h2>
                                </div>
                                <i class="bi bi-book fs-1 opacity-50"></i>
                            </div>
                            <a href="products.php" class="text-white mt-3 text-decoration-none small">View Details ></a>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="card shadow-sm border-0 bg-success text-white p-3 h-100">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-uppercase mb-1" style="font-size: 0.8rem;">Total Users</h6>
                                    <h2 class="mb-0 fw-bold"><?php echo $total_users; ?></h2>
                                </div>
                                <i class="bi bi-people fs-1 opacity-50"></i>
                            </div>
                            <a href="manage-users.php" class="text-white mt-3 text-decoration-none small">View Users ></a>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="card shadow-sm border-0 bg-info text-white p-3 h-100">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-uppercase mb-1" style="font-size: 0.8rem;">Total Orders</h6>
                                    <h2 class="mb-0 fw-bold"><?php echo $total_orders; ?></h2>
                                </div>
                                <i class="bi bi-cart-check fs-1 opacity-50"></i>
                            </div>
                            <a href="manage-orders.php" class="text-white mt-3 text-decoration-none small">View Orders ></a>
                        </div>
                    </div>
                </div>
                <div class="container-fluid">
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="card border-0 shadow-sm p-4 bg-white">
                                <h2 class="fw-bold mb-1">Welcome back, Admin!</h2>
                                <p class="text-muted mb-0">
                                    You are currently managing <strong><?php echo $total_products; ?></strong> products
                                    and overseeing <strong><?php echo $total_orders; ?></strong> orders. Use the dashboard to quickly navigate to different sections and keep an eye on the store's performance. Thank you for your hard work.
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="row g-4 mb-4">
                    </div>

                    <div class="row g-4 mb-4">
                        <div class="col-lg-7">
                            <div class="card border-0 shadow-sm h-100">
                                <div class="card-header bg-white py-3 border-0">
                                    <h6 class="fw-bold mb-0">Order Trends</h6>
                                </div>
                                <div class="card-body" style="height: 300px;">
                                    <canvas id="ordersChart"></canvas>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-5">
                            <div class="card border-0 shadow-sm h-100">
                                <div class="card-header bg-white py-3 border-0">
                                    <h6 class="fw-bold mb-0">Product Categories</h6>
                                </div>
                                <div class="card-body" style="height: 300px;">
                                    <canvas id="categoryChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="card border-0 shadow-sm">
                                <div class="card-header bg-white py-3 border-0 d-flex justify-content-between align-items-center">
                                    <h6 class="fw-bold mb-0">Monthly Revenue (DT)</h6>
                                    <span class="badge bg-success-subtle text-success">Financial Overview</span>
                                </div>
                                <div class="card-body" style="height: 350px;">
                                    <canvas id="revenueChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // --- Category Pie Chart ---
        const catCtx = document.getElementById('categoryChart').getContext('2d');
        new Chart(catCtx, {
            type: 'doughnut',
            data: {
                labels: <?php echo json_encode($cat_labels); ?>,
                datasets: [{
                    data: <?php echo json_encode($cat_counts); ?>,
                    backgroundColor: [
                        '#0d6efd', // Blue (Primary)
                        '#198754', // Green (Success)
                        '#0dcaf0', // Cyan (Info)
                        '#ffc107', // Yellow (Warning)
                        '#dc3545', // Red (Danger)
                        '#6610f2', // Indigo
                        '#6f42c1', // Purple
                        '#e83e8c', // Pink
                        '#fd7e14', // Orange
                        '#20c997', // Teal
                        '#adb5bd', // Gray
                        '#343a40', // Dark Gray
                        '#bc8f8f', // Rosy Brown
                        '#4b0082' // Indigo Dye
                    ],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });

        // --- Orders Bar Chart ---
        const orderCtx = document.getElementById('ordersChart').getContext('2d');
        new Chart(orderCtx, {
            type: 'bar',
            data: {
                labels: <?php echo json_encode($month_labels); ?>,
                datasets: [{
                    label: 'Number of Orders',
                    data: <?php echo json_encode($order_counts); ?>,
                    backgroundColor: '#0d6efd',
                    borderRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                }
            }
        });
        // --- Revenue Line Chart ---
        const revCtx = document.getElementById('revenueChart').getContext('2d');
        new Chart(revCtx, {
            type: 'line',
            data: {
                labels: <?php echo json_encode($rev_labels); ?>,
                datasets: [{
                    label: 'Total Revenue (DT)',
                    data: <?php echo json_encode($rev_amounts); ?>,
                    borderColor: '#198754', // Green for money
                    backgroundColor: 'rgba(25, 135, 84, 0.1)', // Light green fill
                    fill: true,
                    tension: 0.4, // Makes the line curvy and professional
                    borderWidth: 3,
                    pointRadius: 5,
                    pointBackgroundColor: '#198754'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return context.parsed.y.toFixed(3) + ' DT';
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return value + ' DT';
                            }
                        }
                    }
                }
            }
        });
    </script>
</body>

</html>