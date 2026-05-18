<?php
$host = "localhost";
$user = "root";     // Default for XAMPP
$pass = "";         // Default for XAMPP (empty)
$dbname = "lafleur_db"; // CHANGE THIS to your actual database name

// Create the connection
$conn = mysqli_connect($host, $user, $pass, $dbname);

// Check if it worked
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Set charset to utf8 to handle French/Arabic characters correctly
mysqli_set_charset($conn, "utf8");
