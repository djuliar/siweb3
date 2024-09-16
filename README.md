Contoh kode untuk **registrasi** dan **login** menggunakan PHP Native dan MySQL. Contoh ini juga menyertakan **redirect** ke halaman beranda setelah login berhasil.

### 1. **Database Structure**
Pastikan kamu sudah memiliki database MySQL dengan tabel `users` seperti berikut:

```sql
CREATE TABLE users (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

### 2. **Koneksi Database (db.php)**

```php
<?php
$host = 'localhost';
$user = 'root';
$password = '';
$dbname = 'test_db';

$conn = new mysqli($host, $user, $password, $dbname);

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}
?>
```

### 3. **Registrasi User (register.php)**

```php
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
```

### 4. **Login User (login.php)**

```php
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
```

### 5. **Halaman Beranda (home.php)**

```php
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
```

### 6. **Logout (logout.php)**

```php
<?php
session_start();
session_destroy();

// Redirect ke halaman login
header('Location: login.php');
exit;
```

### Penjelasan:
1. **db.php**: Mengatur koneksi ke database.
2. **register.php**: Mengurus registrasi user baru dengan validasi agar username unik dan menyimpan password dengan aman menggunakan `password_hash()`.
3. **login.php**: Mengecek login user dan mengatur session jika berhasil login.
4. **home.php**: Halaman beranda yang hanya dapat diakses setelah login.
5. **logout.php**: Mengakhiri sesi login user.

### Cara Menggunakan:
1. Import database dengan tabel `users`.
2. Letakkan semua file PHP di server yang terhubung dengan database.
3. Akses halaman `register.php` untuk mendaftarkan user.
4. Akses `login.php` untuk login dan dialihkan ke `home.php` setelah berhasil.