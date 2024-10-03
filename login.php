<?php
include 'db.php';
session_start();

// Cek apakah user sudah login
if (isset($_SESSION['user_id'])) {
    header('Location: home.php');
    exit;
}

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
        echo "<div class='row'>
                <div class='col-12'>
                    <div class='alert alert-danger'>Login gagal. Username atau password salah.</div>
                </div>
            </div>";
    }
}
?>

<form method="post">
    <div class="form-group">
        <label for="name">Username</label>
        <input type="text" class="form-control" id="username" name="username" placeholder="Enter your username" required>
    </div>
    <div class="form-group">
        <label for="password">Password</label>
        <input type="password" class="form-control" id="password" name="password" placeholder="Enter your new password" required>
    </div>

    <button type="submit" class="btn btn-primary btn-block">Login</button>
    <div class="form-group mt-4">
        <div class="text-center">
            <span>Don't have an account?</span>
            <a href="?page=register">Register Here</a>
        </div>
    </div>
</form>