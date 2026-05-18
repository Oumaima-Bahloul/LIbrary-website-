<?php
session_start();
include 'api/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['register'])) {
    $first_name = $_POST['first_name'];
    $last_name  = $_POST['last_name']; // You'll need to add this input to your form
    $email      = $_POST['email'];
    $password   = $_POST['password'];
    $role       = 'user'; // Default role for new signups

    // 1. Hash the password for security (like user #2 in image_2e6d0f.png)
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    try {
        // 2. Prepare the SQL
        $stmt = $pdo->prepare("INSERT INTO users (first_name, last_name, email, password, role) VALUES (?, ?, ?, ?, ?)");

        // 3. Execute
        if ($stmt->execute([$first_name, $last_name, $email, $hashed_password, $role])) {
            // Success! Redirect to login with a success message
            header("Location: login.php?success=1");
            exit();
        }
    } catch (PDOException $e) {
        // Handle duplicate emails or other errors
        header("Location: login.php?error=Registration failed. Email might already exist.");
        exit();
    }
}
