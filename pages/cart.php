<?php

require_once '../common.php';

if (!empty($_SESSION['cart'])){
    $cartIds = implode(',', $_SESSION['cart']);
    $query = connect()->prepare("SELECT * FROM products WHERE id IN ($cartIds)");
    $query->execute();
    $products = $query->fetchAll();
}

if (isset($_POST['checkout'])) {
    if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
        $to      = SHOP_MANAGER_EMAIL;
        $subject = 'Shop Order';

        $date = date('d-m-Y H:i:s');
        $name = strip_tags($_POST['name']);
        $contactDetails = strip_tags($_POST['contact_details']);
        $comment = strip_tags($_POST['comment']);

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

if (isset($_POST['_METHOD']) && $_POST['_METHOD'] == 'DELETE') {
    $key = array_search($_POST['product_id'], $_SESSION['cart']);
    unset($_SESSION['cart'][$key]);
    if (empty($_SESSION['cart'])) unset($_SESSION['cart']);
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
        <title>Shop - Cart</title>
        <link rel="stylesheet" href="../assets/style.css">
    </head>
    <body>
        <?php include 'header.php'; ?>
        <section>
            <table>
                <tr>
                    <th><?= translate('Title') ?></th>
                    <th><?= translate('Description') ?></th>
                    <th><?= translate('Price') ?></th>
                    <th><?= translate('Action') ?></th>
                </tr>
                <?php if (isset($products)):
                    foreach ($products as $row): ?>
                        <tr>
                            <td><?= $row['title'] ?></td>
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
                    <form class="checkout-form" method="POST">
                        <input type="hidden" name="checkout" value="true">
                        <div>
                            <span><?= translate('Name') ?></span>
                            <input type="text" name="name" required>
                        </div>
                        <div>
                            <span><?= translate('Contact Details') ?></span>
                            <textarea name="contact_details" required></textarea>
                        </div>
                        <div>
                            <span><?= translate('Comments') ?></span>
                            <textarea name="comment"></textarea>
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