<?php
session_start();

// Cek apakah user sudah login
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

echo "Selamat datang, " . $_SESSION['username'] . "!";
?>

<a href="logout.php">Logout</a>