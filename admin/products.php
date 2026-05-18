<?php
session_start();
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}
include '../api/db.php';

// Fetch all products
$stmt = $pdo->query("SELECT * FROM products ORDER BY id DESC");
$products = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Manage Inventory - La Fleur</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        .product-img {
            width: 45px;
            height: 60px;
            object-fit: cover;
            border-radius: 4px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        /* Custom table styling to match the new dashboard */
        .table thead {
            background-color: #f8f9fa;
            border-top: 2px solid #dee2e6;
        }

        .action-btns .btn {
            padding: 0.25rem 0.5rem;
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
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h2 class="fw-bold mb-1">Manage Inventory</h2>
                        <p class="text-muted">Update, add, or remove books from your collection.</p>
                    </div>
                    <a href="add-product.php" class="btn btn-primary shadow-sm">
                        <i class="bi bi-plus-lg me-1"></i> Add New Product
                    </a>
                </div>

                <div class="card border-0 shadow-sm rounded-3 overflow-hidden">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="ps-4">Product</th>
                                    <th>Category</th>
                                    <th>Price</th>
                                    <th class="text-end pe-4">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($products as $product): ?>
                                    <tr>
                                        <td class="ps-4">
                                            <div class="d-flex align-items-center">
                                                <?php
                                                $img_name = basename($product['image']);
                                                if (strpos($product['image'], 'http') === 0) {
                                                    $src = $product['image'];
                                                } elseif (file_exists("../catalogue/pics/" . $img_name)) {
                                                    $src = "../catalogue/pics/" . $img_name;
                                                } else {
                                                    $src = "../images/" . $img_name;
                                                }
                                                ?>
                                                <img src="<?php echo $src; ?>" class="product-img me-3" alt="cover">
                                                <span class="fw-bold"><?php echo htmlspecialchars($product['title']); ?></span>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge rounded-pill bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-25 px-3">
                                                <?php echo htmlspecialchars($product['category']); ?>
                                            </span>
                                        </td>
                                        <td class="fw-semibold">
                                            <?php echo number_format($product['price'], 2); ?> DT
                                        </td>
                                        <td class="text-end pe-4 action-btns">
                                            <div class="btn-group gap-2">
                                                <a href="edit-product.php?id=<?php echo $product['id']; ?>" class="btn btn-outline-primary btn-sm rounded shadow-sm" title="Edit">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                                <a href="delete-product.php?id=<?php echo $product['id']; ?>"
                                                    class="btn btn-outline-danger btn-sm rounded shadow-sm"
                                                    onclick="return confirm('Are you sure you want to delete this book?')"
                                                    title="Delete">
                                                    <i class="bi bi-trash"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
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