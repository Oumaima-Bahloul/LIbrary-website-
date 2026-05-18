<?php
session_start();
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}
include '../api/db.php';

$message = "";

// 1. Get the book ID from the URL
if (!isset($_GET['id'])) {
    header("Location: products.php");
    exit();
}
$id = $_GET['id'];

// 2. Fetch current book details
$stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
$stmt->execute([$id]);
$product = $stmt->fetch();

if (!$product) {
    die("Product not found.");
}

// 3. Handle the Update (Post Request)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $category = $_POST['category'];
    $price = $_POST['price'];
    $image_url = $_POST['image_url'];
    $final_image_path = $product['image']; // Keep old image by default

    // Check for new file upload
    if (!empty($_FILES['image_file']['name'])) {
        $target_dir = "../images/";
        $file_name = basename($_FILES['image_file']['name']);
        if (move_uploaded_file($_FILES['image_file']['tmp_name'], $target_dir . $file_name)) {
            $final_image_path = "images/" . $file_name;
        }
    } elseif (!empty($image_url)) {
        $final_image_path = $image_url;
    }

    $update_stmt = $pdo->prepare("UPDATE products SET title=?, category=?, price=?, image=? WHERE id=?");
    if ($update_stmt->execute([$title, $category, $price, $final_image_path, $id])) {
        $message = "<div class='alert alert-success shadow-sm border-0'><i class='bi bi-check-circle me-2'></i>Product updated successfully! <a href='products.php' class='alert-link'>View inventory</a></div>";

        // Refresh local data to show updated values in the form
        $product['title'] = $title;
        $product['category'] = $category;
        $product['price'] = $price;
        $product['image'] = $final_image_path;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Edit Product - <?php echo htmlspecialchars($product['title']); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        .form-label {
            font-weight: 600;
            color: #495057;
        }

        .current-img-preview {
            width: 100px;
            height: 140px;
            object-fit: cover;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
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

            <div class="container" style="max-width: 850px;">
                <div class="d-flex align-items-center justify-content-between mb-4">
                    <div>
                        <h2 class="fw-bold mb-1">Edit Product</h2>
                        <p class="text-muted mb-0">ID: #<?php echo $id; ?> — Updating "<?php echo htmlspecialchars($product['title']); ?>"</p>
                    </div>
                    <a href="products.php" class="btn btn-outline-secondary btn-sm">
                        <i class="bi bi-arrow-left"></i> Back to Inventory
                    </a>
                </div>

                <?php echo $message; ?>

                <div class="card shadow-sm border-0 p-4">
                    <form action="edit-product.php?id=<?php echo $id; ?>" method="POST" enctype="multipart/form-data">

                        <div class="mb-4">
                            <label class="form-label">Product Title</label>
                            <input type="text" name="title" class="form-control form-control-lg" value="<?php echo htmlspecialchars($product['title']); ?>" required>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label class="form-label">Category</label>
                                <select name="category" class="form-select">
                                    <?php
                                    $cats = ["Books", "EBooks", "Trending", "Magazines", "Supplies & Accessories", "Stationary", "Educational Kits", "Gifts & Merch"];
                                    foreach ($cats as $cat) {
                                        $selected = ($product['category'] == $cat) ? 'selected' : '';
                                        echo "<option value='$cat' $selected>$cat</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Price (DT)</label>
                                <div class="input-group">
                                    <input type="number" step="0.01" name="price" class="form-control" value="<?php echo $product['price']; ?>" required>
                                    <span class="input-group-text">DT</span>
                                </div>
                            </div>
                        </div>

                        <hr class="text-muted opacity-25">

                        <div class="row align-items-center mb-4 mt-4">
                            <div class="col-auto">
                                <label class="form-label d-block mb-2">Current Image</label>
                                <?php
                                $img_src = (strpos($product['image'], 'http') === 0) ? $product['image'] : '../' . ltrim($product['image'], '/');
                                ?>
                                <img src="<?php echo $img_src; ?>" class="current-img-preview" alt="Preview">
                            </div>
                            <div class="col">
                                <div class="p-3 bg-light rounded-3">
                                    <h6 class="fw-bold mb-3 small text-uppercase">Replace Image</h6>

                                    <div class="mb-3">
                                        <label class="form-label small text-muted">Upload New File</label>
                                        <input type="file" name="image_file" class="form-control">
                                    </div>

                                    <div class="text-center my-2">
                                        <span class="badge bg-white text-muted border px-2">OR</span>
                                    </div>

                                    <div>
                                        <label class="form-label small text-muted">New Image URL</label>
                                        <input type="text" name="image_url" class="form-control" placeholder="https://..." value="<?php echo (strpos($product['image'], 'http') === 0) ? $product['image'] : ''; ?>">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex gap-2 pt-2">
                            <button type="submit" class="btn btn-primary flex-grow-1 shadow-sm py-3 fw-bold">
                                <i class="bi bi-cloud-arrow-up me-2"></i> Save Changes
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