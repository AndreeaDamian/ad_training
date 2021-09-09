<?php

require_once '../common.php';

redirectIfUnauthenticated();

$conn = connect();
$orderId = strip_tags($_GET['id']);
$query = $conn->prepare('SELECT * FROM orders WHERE id = ?');
$query->execute([$orderId]);
$order = $query->fetch();

$q = $conn->prepare('
    SELECT *
    FROM order_product
    INNER JOIN products ON order_product.product_id=products.id 
    WHERE order_id = ?
');
$q->execute([$order['id']]);
$products = $q->fetchAll();

?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title><?= translate('Shop') ?> - <?= translate('Order Nr') ?> <?= $order['id'] ?></title>
        <link rel="stylesheet" href="../assets/style.css">
    </head>
    <body>
        <?php require_once 'header.php'; ?>
        <section>
            <div>
                <h3><?= translate('Customer Details') ?></h3>
               <table style="width: 50%">
                   <tr>
                       <td style="width: 25%;font-weight: bold;"><?= translate('Name') ?></td>
                       <td><?= $order['name'] ?></td>
                   </tr>
                   <tr>
                       <td style="width: 25%;font-weight: bold;"><?= translate('Contact Details') ?></td>
                       <td><?= $order['contact_details'] ?></td>
                   </tr>
                   <tr>
                       <td style="width: 25%;font-weight: bold;"><?= translate('Comment') ?></td>
                       <td><?= $order['comment'] ?></td>
                   </tr>
               </table>
            </div>
        </section>
        <section>
            <div>
                <h3><?= translate('Purchased products') ?></h3>
                <table>
                    <tr>
                        <th><?= translate('Nr') ?></th>
                        <th><?= translate('Title') ?></th>
                        <th><?= translate('Image') ?></th>
                        <th><?= translate('Description') ?></th>
                        <th><?= translate('Price') ?></th>
                    </tr>
                    <?php foreach ($products as $key => $product): ?>
                        <tr>
                            <td><?= $key + 1 ?></td>
                            <td><?= $product['title'] ?></td>
                            <td><img style="height: 100px;display: flex;" src="<?= $product['image_path'] ? $product['image_path'] : '../assets/images/placeholder.png' ?>"></td>
                            <td><?= $product['description'] ?></td>
                            <td><?= $product['price'] ?></td>
                        </tr>
                    <?php endforeach ?>
                </table>
            </div>
        </section>
    </body>
</html>
