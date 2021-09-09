<?php

require_once '../common.php';

if (empty($_SESSION['cart'])) {
    $query = connect()->prepare('SELECT * FROM products');
    $query->execute();
} else {
    $cartIds = $_SESSION['cart'];
    $placeholders = implode(',', array_fill(0, count($cartIds), '?'));
    $query = connect()->prepare("SELECT * FROM products WHERE id NOT IN ($placeholders)");
    $query->execute(array_values($cartIds));
}
$products = $query->fetchAll();

if (isset($_POST['product_id'])) {
    $productId = strip_tags($_POST['product_id']);
    if (is_numeric($productId) == true) {
        if (!in_array( $productId, $_SESSION['cart'])) {
            array_push($_SESSION['cart'], $_POST['product_id']);
        }

        header('Location: index.php');
        exit;
    } else {
        $message = translate('There are some errors!');
    }
}
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport"
              content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title><?= translate('Shop') ?></title>
        <link rel="stylesheet" href="../assets/style.css">
    </head>
    <body>
        <?php require_once 'header.php'; ?>
        <section>
            <h3 style="color: red;"><?= isset($message) ? $message : '' ?></h3>
            <table>
                <tr>
                    <th><?= translate('Title') ?></th>
                    <th><?= translate('Image') ?></th>
                    <th><?= translate('Description') ?></th>
                    <th><?= translate('Price') ?></th>
                    <th><?= translate('Action') ?></th>
                </tr>
                <?php foreach ($products as $row): ?>
                    <tr>
                        <td><?= $row['title'] ?></td>
                        <td><img style="height: 100px;display: flex;" src="<?= $row['image_path'] ? $row['image_path'] : '../assets/images/placeholder.png' ?>"></td>
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