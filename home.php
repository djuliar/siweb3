<?php session_start();
// Cek apakah user sudah login
if (!isset($_SESSION['user_id'])) {
    header('Location: auth.php?page=login');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= (@$_GET['page'] == 'register') ? 'Register' : 'Login' ?> Page</title>
    <link rel="stylesheet" href="basic/css/4.5.2-bootstrap.min.css">
    <link rel="stylesheet" href="basic/css/style.css">
</head>
<body>
<div class="container">
    <div class="row">
        <div class="col-lg-12">
            <div class="jumbotron mt-5">
                <h1><?php echo "Selamat datang, " . ucfirst($_SESSION['username']) . "!"; ?></h1>
                <a href="logout.php" class="btn btn-primary btn-lg">Logout</a>
            </div>
        </div>
    </div>
</div>


    <script src="basic/js/jquery-3.5.1.js"></script>
    <script src="basic/js/popper.min.js"></script>
    <script src="basic/js/bootstrap.bundle.min.js"></script>
</body>
</html>