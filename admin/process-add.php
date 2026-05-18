<?php
session_start();
// Check if the user is NOT logged in or is NOT an admin
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}
// 1. Connect to the database using your existing API file
include '../api/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // 2. Collect data from the form
    $title    = $_POST['title'];
    $category = $_POST['category'];
    $price    = $_POST['price'];

    // Grab specific info (use null if the field was hidden/empty)
    $author   = !empty($_POST['author']) ? $_POST['author'] : null;
    $format   = !empty($_POST['format']) ? $_POST['format'] : null;
    $material = !empty($_POST['material']) ? $_POST['material'] : null;

    // 3. Handle the Image Upload
    $target_dir = "../images/";
    $image_name = basename($_FILES["image"]["name"]);
    $target_file = $target_dir . $image_name;

    // This is the path we save in the database so the website can find it
    $db_image_path = "";

    // Check if user provided a URL or an Uploaded File
    $db_image_path = "";

    if (!empty($_POST['image_url'])) {
        // 1. User provided a link
        $db_image_path = $_POST['image_url'];
    } elseif (!empty($_FILES['image_file']['name'])) {
        // 2. User uploaded a file
        $target_dir = "../images/";
        $image_name = basename($_FILES["image_file"]["name"]);
        $target_file = $target_dir . $image_name;

        if (move_uploaded_file($_FILES["image_file"]["tmp_name"], $target_file)) {
            $db_image_path = "images/" . $image_name;
        }
    }

    // Ensure the path is not empty before inserting
    if ($db_image_path == "") {
        die("Error: Please provide either an image file or a URL.");
    }

    // Then run your $stmt->execute using $db_image_path for the ':image' parameter
    // Move the file from temporary storage to your 'images' folder
    if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
        try {
            // 4. Prepare the SQL (Protects against SQL Injection)
            $sql = "INSERT INTO products (title, category, price, author, format, material, image) 
                    VALUES (:title, :category, :price, :author, :format, :material, :image)";

            $stmt = $pdo->prepare($sql);

            // 5. Execute the save command
            $stmt->execute([
                ':title'    => $title,
                ':category' => $category,
                ':price'    => $price,
                ':author'   => $author,
                ':format'   => $format,
                ':material' => $material,
                ':image'    => $db_image_path
            ]);

            // 6. Redirect back to the dashboard with a success message
            header("Location: index.php?success=1");
            exit();
        } catch (PDOException $e) {
            // If there is a database error (like a missing column), show it here
            die("Database Error: " . $e->getMessage());
        }
    } else {
        echo "Error: Could not upload the image. Check if the 'images' folder exists.";
    }
}
