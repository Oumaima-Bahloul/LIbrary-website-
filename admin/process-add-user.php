<?php
session_start();
// Check if the user is NOT logged in or is NOT an admin
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}
include '../api/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $first_name = $_POST['first_name'];
    $last_name  = $_POST['last_name'];
    $email      = $_POST['email'];
    $role       = $_POST['role'];

    // Security: Hash the password before saving!
    $password   = password_hash($_POST['password'], PASSWORD_DEFAULT);

    try {
        $sql = "INSERT INTO users (first_name, last_name, email, password, role) 
                VALUES (:first_name, :last_name, :email, :password, :role)";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':first_name' => $first_name,
            ':last_name'  => $last_name,
            ':email'      => $email,
            ':password'   => $password,
            ':role'       => $role
        ]);

        header("Location: manage-users.php?success=user_added");
        exit();
    } catch (PDOException $e) {
        // If email already exists, it will trigger an error
        die("Error: " . $e->getMessage());
    }
}
