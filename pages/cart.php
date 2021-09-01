<?php
    require_once('../common.php');
    session_start();

    if(!empty($_SESSION['cart'])){
        $query = connect()->prepare("SELECT * FROM products WHERE id IN (".implode(',',$_SESSION['cart']).")");
        $query->execute();
        $query->setFetchMode(PDO::FETCH_ASSOC);
        $products = $query->fetchAll();

        if(isset($_GET['product_id'])) {
            $key = array_search($_GET['product_id'], $_SESSION['cart']);
            unset($_SESSION['cart'][$key]);
            header('Location: cart.php');
        }
    }

    if(isset($_GET['type']) && $_GET['type'] == 'checkout') {

        if(isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
            $to      = SHOP_MANAGER_EMAIL;
            $subject = 'Shop Order';
            $message = '<html>
                        <head>
                          <title>Shop Order</title>
                        </head>
                        <body>
                          <p> Order Date: '.date('d-m-Y H:i:s').'</p>
                          <table>
                            <tr>
                              <th>Person</th><th>Contact Details</th><th>Comment</th>
                            </tr>
                            <tr>
                              <td>'.strip_tags($_GET['name']).'</td><td>'.strip_tags($_GET['contact_details']).'</td><td>'.strip_tags($_GET['comment']).'</td>
                            </tr>
                          </table>
                          <br>
                          <table>
                            <tr>
                              <th>Product Name</th><th>Description</th><th>Price</th><th>Image</th>
                            </tr>';
            foreach ($products as  $product) {
                $message .= '<tr>
                                  <td>'.$product['title'].'</td><td>'.$product['title'].'</td><td>'.$product['price'].'</td><td>'.$product['image_path'].'</td>
                                </tr>';
            }
            $message .=  '</table></body></html>';


            $headers = 'MIME-Version: 1.0'. "\r\n" .
                'Content-type: text/html; charset=iso-8859-1'. "\r\n" .
                'From: shop@noreply.com' . "\r\n" ;

            mail($to, $subject, $message, $headers);


    //            unset($_SESSION['cart']);
            $success = "Checkout complete!";
            header('Location: cart.php');
        } else {
            $error = "Cart can't be empty";
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
    <title>Shop - Cart</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>
<?php include("header.php"); ?>
<section>
    <table>
        <tr>
            <th>Title</th>
            <th>Description</th>
            <th>Price</th>
            <th>Action</th>
        </tr>
        <?php
        if(isset($products)) {
            foreach ($products as $row) { ?>
                <tr>
                    <td><?= $row['title'] ?></td>
                    <td><?= $row['description'] ?></td>
                    <td><?= $row['price'] ?></td>
                    <td style="width: 10%"><a href="cart.php?product_id=<?= $row['id'] ?>">REMOVE</a></td>
                </tr>
            <?php } ?>
        <?php } else { ?>
            <tr>
                <td>There are no products in cart!</td>
            </tr>
        <?php } ?>
    </table>
</section>
<section>
    <div class="">
        <?php if(isset($products)) { ?>
            <form class="checkout-form" method="GET">
                <input type="hidden" name="type" value="checkout">
                <div>
                    <span>Name</span>
                    <input type="text" name="name" required>
                </div>
                <div>
                    <span>Contact Details</span>
                    <textarea name="contact_details" required></textarea>
                </div>
                <div>
                    <span>Comments</span>
                    <textarea name="comment"></textarea>
                </div>
                <div>
                    <!--                        <a class="btn" href="">Go to index</a>-->
                    <button type="submit">Checkout</button>
                </div>
            </form>
        <?php } ?>
    </div>
    <?php if(isset($success)) { ?>
        <p><?= $success ?></p>
    <?php } ?>
</section>

</body>
</html>