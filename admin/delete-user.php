<?php
include '../api/db.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Prevent deleting yourself 
    // If you have a session started, you can check if $id == $_SESSION['user_id']

    try {
        $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
        $stmt->execute([$id]);
        header("Location: manage-users.php?success=user_deleted");
    } catch (PDOException $e) {
        die("Error: " . $e->getMessage());
    }
} else {
    header("Location: manage-users.php");
}
exit();
