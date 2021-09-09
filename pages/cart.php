<?php

require_once '../common.php';

if (!empty($_SESSION['cart'])) {
    $cartIds = $_SESSION['cart'];
    $placeholders = implode(',', array_fill(0, count($cartIds), '?'));
    $query = connect()->prepare("SELECT * FROM products WHERE id IN ($placeholders)");
    $query->execute(array_values($cartIds));
    $products = $query->fetchAll();
}

$errors = [];
$data = [];
if (isset($_POST['checkout'])) {
    if (!empty($_SESSION['cart'])) {
        $to = SHOP_MANAGER_EMAIL;
        $subject = translate('Shop Order');
        $date = date('Y-m-d H:i:s');
        if (empty($_POST['name'])) {
            $errors['name'] = translate('Name is required.');
        } else {
            $data['name'] = strip_tags($_POST['name']);
        }

        if (empty($_POST['contact_details'])) {
            $errors['contact_details'] = translate('Contact Details are required.');
        } else {
            $data['contact_details'] = strip_tags($_POST['contact_details']);
        }

        if (count($errors) == 0) {
            $data['comment'] = strip_tags($_POST['comment']);
            $conn = connect();
            $query = $conn->prepare('
                INSERT INTO orders(name, contact_details, comment, created_at) 
                VALUES (?, ?, ?, ?)
            ');
            $query->execute([
                $data['name'],
                $data['contact_details'],
                $data['comment'],
                $date
            ]);
            $orderId = $conn->lastInsertId();

            foreach ($products as $product) {
                $query2 = $conn->prepare('INSERT INTO order_product(order_id, product_id) VALUES (?, ?)');
                $query2->execute([$orderId, $product['id']]);
            }

            ob_start();
            include '../email.php';
            $message = ob_get_contents();
            ob_end_clean();

            $headers = 'MIME-Version: 1.0'. "\r\n" .
                'Content-type: text/html; charset=iso-8859-1'. "\r\n" .
                'From: no-reply@shop.com' . "\r\n" ;

            mail($to, $subject, $message, $headers);

            unset($_SESSION['cart']);
            header('Location: cart.php');
            exit;
        }
    }
}

if (isset($_POST['_METHOD']) && $_POST['_METHOD'] == 'DELETE') {
    $key = array_search($_POST['product_id'], $_SESSION['cart']);
    unset($_SESSION['cart'][$key]);
    header('Location: cart.php');
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
        <title><?= translate('Shop') ?> - <?= translate('Cart') ?></title>
        <link rel="stylesheet" href="../assets/style.css">
    </head>
    <body>
        <?php require_once 'header.php'; ?>
        <section>
            <table>
                <tr>
                    <th><?= translate('Title') ?></th>
                    <th><?= translate('Image') ?></th>
                    <th><?= translate('Description') ?></th>
                    <th><?= translate('Price') ?></th>
                    <th><?= translate('Action') ?></th>
                </tr>
                <?php if (isset($products)): ?>
                    <?php foreach ($products as $row): ?>
                        <tr>
                            <td><?= $row['title'] ?></td>
                            <td><img style="height: 100px;display: flex;" src="<?= $row['image_path'] ? $row['image_path'] : '../assets/images/placeholder.png' ?>"></td>
                            <td><?= $row['description'] ?></td>
                            <td><?= $row['price'] ?></td>
                            <td style="width: 10%">
                                <form method="POST">
                                    <input type="hidden" name="_METHOD" value="DELETE">
                                    <input type="hidden" name="product_id" value="<?= $row['id'] ?>">
                                    <button type="submit"><?= translate('Remove') ?></button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach ?>
                <?php else: ?>
                    <tr>
                        <td><?= translate('There are no products in cart!') ?></td>
                    </tr>
                <?php endif ?>
            </table>
        </section>
        <section>
            <div>
                <?php if (isset($products)): ?>
                    <div style="display: contents; color: red">
                        <p><?= isset($errors['name']) ? $errors['name'] : '' ?></p>
                        <p><?= isset($errors['contact_details']) ? $errors['contact_details'] : '' ?></p>
                    </div>
                    <form class="checkout-form" method="POST">
                        <input type="hidden" name="checkout" value="true">
                        <div>
                            <span><?= translate('Name') ?></span>
                            <input type="text" name="name" value="<?= $_POST['name'] ?? '' ?>">
                        </div>
                        <div>
                            <span><?= translate('Contact Details') ?></span>
                            <textarea name="contact_details"><?= $_POST['contact_details'] ?? '' ?></textarea>
                        </div>
                        <div>
                            <span><?= translate('Comments') ?></span>
                            <textarea name="comment"><?= $_POST['comment'] ?? '' ?></textarea>
                        </div>
                        <div>
                            <a class="index-link" href="index.php"><?= translate('Go to index') ?></a>
                            <button type="submit"><?= translate('Checkout') ?></button>
                        </div>
                    </form>
                <?php endif ?>
            </div>
        </section>
    </body>
</html>