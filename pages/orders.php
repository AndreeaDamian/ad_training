<?php

require_once '../common.php';

unauthenticated();

$query = connect()->prepare('SELECT * FROM orders ORDER BY id DESC');
$query->execute();
$orders = $query->fetchAll();

?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport"
              content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Shop - <?= translate('Orders') ?></title>
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
                    <th style="width: 25%"><?= translate('Purchased products') ?></th>
                    <th style="width: 10%"><?= translate('Total') ?></th>
                    <th><?= translate('Actions') ?></th>
                </tr>
                <?php foreach ($orders as $key => $order): ?>
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
                        <td>
                            <ul style="list-style-type: disc; display: inline-block;">
                                <?php
                                    $orderedProducts = getOrderedProducts($order['id']);
                                    foreach($orderedProducts as $product): ?>
                                        <li><?= $product['title']?> - <?= $product['price']?> RON</li>
                                <?php endforeach ?>
                            </ul>
                        </td>
                        <td><?= array_sum(array_column($orderedProducts, 'price')) ?> RON</td>
                        <td><a href="order.php?id=<?= $order['id'] ?>"><?= translate('DETAILS') ?></a></td>
                    </tr>
                <?php endforeach ?>
            </table>
        </section>
    </body>
</html>
