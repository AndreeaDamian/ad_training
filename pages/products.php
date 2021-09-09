<?php

require_once '../common.php';

redirectIfUnauthenticated();

$conn = connect();
$query = $conn->prepare('SELECT * FROM products');
$query->execute();
$products = $query->fetchAll();

if (isset($_POST['_METHOD']) && $_POST['_METHOD'] == 'DELETE') {
    $productId = strip_tags($_POST['product_id']);
    $query = $conn->prepare('SELECT * FROM products WHERE id=?');
    $query->execute([$productId]);
    $product = $query->fetch();
    if ($product) {
        $query = $conn->prepare('DELETE FROM products WHERE id=?');
        $query->execute([$productId]);
    }
    header ('Location: products.php');
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
        <title>Shop - <?= translate('Products') ?></title>
        <link rel="stylesheet" href="../assets/style.css">
    </head>
    <body>
        <?php require_once 'header.php'; ?>
        <section>
            <div class="add-btn">
                <a href="product.php"><?= translate('Add product') ?></a>
            </div>
            <table>
                <tr>
                    <th><?= translate('Nr') ?></th>
                    <th><?= translate('Title') ?></th>
                    <th><?= translate('Image') ?></th>
                    <th><?= translate('Description') ?></th>
                    <th><?= translate('Price') ?></th>
                    <th><?= translate('Actions') ?></th>
                </tr>
                <?php foreach ($products as $key => $product): ?>
                    <tr>
                        <td><?= $key+1 ?></td>
                        <td><?= $product['title'] ?></td>
                        <td><img style="height: 100px;display: flex;" src="<?= $product['image_path'] ? $product['image_path'] : '../assets/images/placeholder.png' ?>"></td>
                        <td><?= $product['description'] ?></td>
                        <td><?= $product['price'] ?></td>
                        <td style="width: 10%">
                            <a href="product.php?id=<?= $product['id'] ?>"><?= translate('EDIT') ?></a> |
                            <form action="products.php" method="POST">
                                <input type="hidden" name="_METHOD" value="DELETE">
                                <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                                <button type="submit"><?= translate('DELETE') ?></button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach ?>
            </table>
        </section>
    </body>
</html>