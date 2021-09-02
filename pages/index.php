<?php

require_once '../common.php';

if (!isset($_SESSION['cart']) || isset($_SESSION['cart']) && empty($_SESSION['cart'])) {
    $query = connect()->prepare('SELECT * FROM products');
} else {
    $cartIds = implode(',', $_SESSION['cart']);
    $query = connect()->prepare("SELECT * FROM products WHERE id NOT IN ($cartIds)");
}

$query->execute();
$products = $query->fetchAll();

if (isset($_POST['product_id'])) {
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    if (!in_array($_POST['product_id'], $_SESSION['cart'])) {
        array_push($_SESSION['cart'], $_POST['product_id']);
    }

    header('Location: index.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport"
              content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>SHOP</title>
        <link rel="stylesheet" href="../assets/style.css">
    </head>
    <body>
        <?php
            require_once 'header.php';
        ?>
        <section>
            <table>
                <tr>
                    <th><?= translate('Title') ?></th>
                    <th><?= translate('Description') ?></th>
                    <th><?= translate('Price') ?></th>
                    <th><?= translate('Action') ?></th>
                </tr>
                <?php
                    foreach ($products as $row): ?>
                        <tr>
                            <td><?= $row['title'] ?></td>
                            <td><?= $row['description'] ?></td>
                            <td><?= $row['price'] ?></td>
                            <td style="width: 10%">
                                <form method="POST">
                                    <input type="hidden" name="product_id" value="<?= $row['id'] ?>">
                                    <button type="submit"><?= translate('ADD TO CART') ?></button>
                                </form>
                            </td>
                        </tr>
                <?php endforeach ?>
            </table>
        </section>
    </body>
</html>