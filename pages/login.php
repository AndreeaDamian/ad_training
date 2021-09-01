<?php
    require_once('../common.php');
    session_start();

    if(isset($_POST['login'])) {

        $email = strip_tags($_POST['email']);
        $password = strip_tags($_POST['password']);
        if($email == '' || $password == '')  echo 'Login failed! Email or password incorrect!';
        if($email == ADMIN_USERNAME && $password == ADMIN_PASSWORD) {
            if(!isset($_SESSION['login'])) {
                $_SESSION['login'] = 'Admin is logged!';
            }
            header('Location: index.php');
        } else {
            echo 'Login failed! Email or password incorrect!';
        }
    }

    if(isset($_GET['logout'])) {
        unset($_SESSION['login']);
        header('Location: login.php');
    }

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Shop</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>
<?php include("header.php"); ?>
    <section>
        <div class="login">
            <form action="login.php" method="POST">

                <input type="hidden" name="login" value="true" required>
                <div>
                    <span>Email</span>
                    <input type="email" name="email" required>
                </div>
                <div>
                    <span>Password</span>
                    <input type="password" name="password" required>
                </div>
                <button type="submit">Login</button>
            </form>
        </div>
    </section>
</body>
</html>
