<?php
include 'db.php';
session_start();

function login($username, $password) {
    global $conn;

    // Ambil data user berdasarkan username
    $sql = "SELECT id, password FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        // Verifikasi password
        if (password_verify($password, $row['password'])) {
            // Set session
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['username'] = $username;
            return true;
        } else {
            return false;
        }
    } else {
        return false;
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if (login($username, $password)) {
        // Redirect ke halaman beranda
        header('Location: home.php');
        exit;
    } else {
        echo "Login gagal. Username atau password salah.";
    }
}
?>

<form method="post">
    Username: <input type="text" name="username" required><br>
    Password: <input type="password" name="password" required><br>
    <button type="submit">Login</button>
</form>