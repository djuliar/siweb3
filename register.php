<?php
include 'db.php';
session_start();

// Cek apakah user sudah login
if (isset($_SESSION['user_id'])) {
    header('Location: home.php');
    exit;
}

function register($username, $password) {
    global $conn;

    if(check_confirm() == false){
        return "<div class='row'>
                <div class='col-12'>
                    <div class='alert alert-danger'>Password Konfirmasi Tidak Sama!</div>
                </div>
            </div>";
    } else {
        // Cek apakah username sudah ada
        $sql = "SELECT id FROM users WHERE username = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            return "<div class='row'>
                <div class='col-12'>
                    <div class='alert alert-danger'>Username sudah digunakan</div>
                </div>
            </div>";
        } else {
            // Hash password sebelum disimpan
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            
            // Insert user baru
            $sql = "INSERT INTO users (username, password) VALUES (?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ss", $username, $hashed_password);
            
            if ($stmt->execute()) {
                header('Location: auth.php?page=login');
                return "Registrasi berhasil";
            } else {
                header('Location: auth.php?page=register');
                return "Registrasi gagal";
            }
        }
    }
}

function check_confirm() {
    if($_POST['password'] != $_POST['confirm']){
        return false;
    } else {
        return true;
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['confirm'];
    
    $message = register($username, $password);
    echo $message;
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
    <div class="form-group">
        <label for="confirm password">Confirm Password</label>
        <input type="password" class="form-control" id="confirm" name="confirm" placeholder="Enter your confirm password">
    </div>

    <button type="submit" class="btn btn-primary btn-block">Register</button>
    <div class="form-group mt-4">
        <div class="text-center">
            <span>Already have an account?</span>
            <a href="?page=login">Login Here</a>
        </div>
    </div>
</form>