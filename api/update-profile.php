<?php
session_start();
include_once 'db.php'; // Ensure db.php is in the same 'api' folder

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['user_id'])) {
    $uid = $_SESSION['user_id'];

    // Get data from the form
    $full_name = trim($_POST['name']);
    $address = trim($_POST['address']);

    // Split the full name into First and Last name based on the first space
    $name_parts = explode(" ", $full_name, 2);
    $first_name = $name_parts[0];
    $last_name = isset($name_parts[1]) ? $name_parts[1] : '';

    try {
        // Updated SQL to match your exact database columns
        $sql = "UPDATE users SET first_name = ?, last_name = ?, address = ? WHERE id = ?";
        $stmt = $pdo->prepare($sql);

        if ($stmt->execute([$first_name, $last_name, $address, $uid])) {
            // Update the session name so the navbar updates immediately
            $_SESSION['user_name'] = $first_name;

            // Redirect back with a success message
            header("Location: ../profile.php?success=1");
            exit();
        } else {
            header("Location: ../profile.php?error=update_failed");
            exit();
        }
    } catch (PDOException $e) {
        // If there is still a column error, this will tell us exactly which one
        die("Database Error: " . $e->getMessage());
    }
} else {
    // If someone tries to access this file directly without POST
    header("Location: ../profile.php");
    exit();
}
