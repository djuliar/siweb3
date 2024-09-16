<?php
include 'db.php';

function register($username, $password) {
    global $conn;

    // Cek apakah username sudah ada
    $sql = "SELECT id FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        return "Username sudah digunakan";
    } else {
        // Hash password sebelum disimpan
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        
        // Insert user baru
        $sql = "INSERT INTO users (username, password) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $username, $hashed_password);
        
        if ($stmt->execute()) {
            return "Registrasi berhasil";
        } else {
            return "Registrasi gagal";
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    $message = register($username, $password);
    echo $message;
}
?>

<form method="post">
    Username: <input type="text" name="username" required><br>
    Password: <input type="password" name="password" required><br>
    <button type="submit">Register</button>
</form>