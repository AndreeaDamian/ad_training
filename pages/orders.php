<?php

require_once '../common.php';

redirectIfUnauthenticated();

$query = connect()->prepare('
    SELECT orders.*, SUM(products.price) as total
    FROM orders 
    INNER JOIN order_product ON order_product.order_id=orders.id
    INNER JOIN products ON products.id=order_product.product_id
    GROUP BY orders.id
    ORDER BY orders.id DESC
');
$query->execute();
$orders = $query->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport"
              content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title><?= translate('Shop') ?> - <?= translate('Orders') ?></title>
        <link rel="stylesheet" href="../assets/style.css">
    </head>
    <body>
        <?php require_once 'header.php'; ?>
        <section>
            <table>
                <tr>
                    <th><?= translate('ID') ?></th>
                    <th style="width: 10%"><?= translate('Date') ?></th>
                    <th><?= translate('Customer Details') ?></th>
                    <th style="width: 10%"><?= translate('Total') ?></th>
                    <th><?= translate('Actions') ?></th>
                </tr>
                <?php foreach ($orders as $order): ?>
                    <tr>
                        <td><?= $order['id'] ?></td>
                        <td><?= $order['created_at'] ?></td>
                        <td>
                            <ul style="display: inline-block">
                                <li><?= translate('Name') ?>: <?= $order['name'] ?></li>
                                <li><?= translate('Contact Details') ?>: <?= $order['contact_details'] ?></li>
                                <li><?= translate('Comment') ?>: <?= $order['comment'] ?></li>
                            </ul>
                        </td>
                        <td><?= $order['total'] ?> RON</td>
                        <td><a href="order.php?id=<?= $order['id'] ?>"><?= translate('DETAILS') ?></a></td>
                    </tr>
                <?php endforeach ?>
            </table>
        </section>
    </body>
</html>
