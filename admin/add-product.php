<?php
session_start();
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}
include '../api/db.php';

$message = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $category = $_POST['category'];
    $price = $_POST['price'];
    $image_url = $_POST['image_url'];
    $final_image_path = "";

    // 1. Logic: If a file is uploaded, use it. If not, use the URL.
    if (!empty($_FILES['image_file']['name'])) {
        $target_dir = "../images/";
        $file_name = basename($_FILES['image_file']['name']);
        $target_file = $target_dir . $file_name;

        if (move_uploaded_file($_FILES['image_file']['tmp_name'], $target_file)) {
            $final_image_path = "images/" . $file_name;
        }
    } elseif (!empty($image_url)) {
        $final_image_path = $image_url;
    }

    if (!empty($final_image_path)) {
        $stmt = $pdo->prepare("INSERT INTO products (title, category, price, image) VALUES (?, ?, ?, ?)");
        if ($stmt->execute([$title, $category, $price, $final_image_path])) {
            $message = "<div class='alert alert-success shadow-sm border-0'><i class='bi bi-check-circle me-2'></i>Product added successfully!</div>";
        }
    } else {
        $message = "<div class='alert alert-danger shadow-sm border-0'><i class='bi bi-exclamation-triangle me-2'></i>Please provide an image (File or URL).</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Add New Product - La Fleur</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        .form-label {
            font-weight: 600;
            color: #495057;
        }

        .card {
            border-radius: 12px;
        }

        .btn-success {
            padding: 12px;
            font-weight: 600;
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

            <div class="container" style="max-width: 800px;">
                <div class="mb-4">
                    <h2 class="fw-bold mb-1">Add New Product</h2>
                    <p class="text-muted">Fill in the details to add a new item to your bookstore.</p>
                </div>

                <?php echo $message; ?>

                <div class="card shadow-sm border-0 p-4">
                    <form action="add-product.php" method="POST" enctype="multipart/form-data">
                        <div class="mb-4">
                            <label class="form-label">Product Title</label>
                            <input type="text" name="title" class="form-control form-control-lg" placeholder="Enter product title" required>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label class="form-label">Category</label>
                                <select name="category" class="form-select">
                                    <option value="Books">Books</option>
                                    <option value="EBooks">EBooks</option>
                                    <option value="Trending">Trending</option>
                                    <option value="Magazines">Magazines</option>
                                    <option value="Supplies & Accessories">Supplies & Accessories</option>
                                    <option value="Stationary">Stationary</option>
                                    <option value="Educational Kits">Educational Kits</option>
                                    <option value="Gifts & Merch">Gifts & Merch</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Price (DT)</label>
                                <div class="input-group">
                                    <input type="number" step="0.01" name="price" class="form-control" placeholder="0.00" required>
                                    <span class="input-group-text">DT</span>
                                </div>
                            </div>
                        </div>

                        <div class="p-3 bg-light rounded-3 mb-4">
                            <h6 class="fw-bold mb-3"><i class="bi bi-image me-2"></i>Product Media</h6>

                            <div class="mb-3">
                                <label class="form-label small text-uppercase text-muted">Option A: Upload Image File</label>
                                <input type="file" name="image_file" class="form-control">
                            </div>

                            <div class="text-center my-2">
                                <span class="badge bg-white text-muted border px-3">OR</span>
                            </div>

                            <div>
                                <label class="form-label small text-uppercase text-muted">Option B: Paste Image URL</label>
                                <input type="text" name="image_url" class="form-control" placeholder="https://example.com/image.jpg">
                            </div>
                        </div>

                        <div class="d-flex gap-2">
                            <a href="products.php" class="btn btn-light border flex-grow-1">Cancel</a>
                            <button type="submit" class="btn btn-success flex-grow-1 shadow-sm">
                                <i class="bi bi-plus-circle me-1"></i> Add to Inventory
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>