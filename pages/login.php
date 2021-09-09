<?php

require_once '../common.php';

$error = [];

if (isset($_POST['login'])) {
    $email = strip_tags($_POST['email']);
    $password = strip_tags($_POST['password']);
    if (empty($email) || empty($password)) {
        $error['login'] = translate('Login failed! Email or password incorrect!');
    } elseif ($email == ADMIN_USERNAME && $password == ADMIN_PASSWORD) {
        $_SESSION['login'] = 'Admin is logged!';

        header('Location: products.php');
        exit;
    } else {
        $error['login'] = translate('Login failed! Email or password incorrect!');
    }

}

if (isset($_POST['logout'])) {
    unset($_SESSION['login']);
    header('Location: login.php');
    exit;
}

authenticated();

?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport"
              content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Shop - <?= translate('Login') ?></title>
        <link rel="stylesheet" href="../assets/style.css">
    </head>
    <body>
        <?php require_once 'header.php'; ?>
        <section>
            <div class="login">
                <form action="login.php" method="POST">
                    <input type="hidden" name="login" value="true" required>
                    <div>
                        <span><?= translate('Email') ?></span>
                        <input type="email" name="email">
                    </div>
                    <div>
                        <span><?= translate('Password') ?></span>
                        <input type="password" name="password">
                    </div>
                    <button type="submit"><?= translate('Login') ?></button>
                </form>
            </div>
            <div>
                <p style="text-align: center; color: red"><?= $error['login'] ?? '' ?></p>
            </div>
        </section>
    </body>
</html>
