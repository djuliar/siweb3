<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= (@$_GET['page'] == 'register') ? 'Register' : 'Login' ?> Page</title>
    <link rel="stylesheet" href="basic/css/4.5.2-bootstrap.min.css">
    <link rel="stylesheet" href="basic/css/style.css">
    <style>
        /* Custom CSS to center the form */
        .centered-form {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .margin-top{
            margin-top: 150px;
        }

        /* Custom CSS for the side image */
        .side-image {
            background-size: cover;
            background-position: center;
            height: 100vh;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row margin-top">
        <?php if(@$_GET['page'] == 'register'){ ?>
            <div class="col-md-6 side-image">
                <img src="basic/images/register.png" class="img-fluid" alt="login-image">
            </div>

            <div class="col-md-6">
                <div class="card custom-card">
                    <div class="card-header">
                        Register
                    </div>
                    <div class="card-body">
                        <?php include('register.php'); ?>
                    </div>
                </div>
            </div>
        <?php } elseif(@$_GET['page'] == 'login'){ ?>
            <div class="col-md-6 side-image">
                <img src="basic/images/login.png" class="img-fluid" alt="login-image">
            </div>

            <div class="col-md-6">
                <div class="card custom-card">
                    <div class="card-header">
                        Login
                    </div>
                    <div class="card-body">
                        <?php include('login.php'); ?>
                    </div>
                </div>
            </div>
        <?php } else { $_GET['page'] = null; } ?>
        </div>
    </div>

    <script src="basic/js/jquery-3.5.1.js"></script>
    <script src="basic/js/popper.min.js"></script>
    <script src="basic/js/bootstrap.bundle.min.js"></script>


</body>
</html>