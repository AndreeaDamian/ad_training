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
    <?php
        include("../common.php");
        getSessionProducts();
    ?>
    <section>
        <table>
            <tr>
                <th>Title</th>
                <th>Description</th>
                <th>Price</th>
                <th>Action</th>
            </tr>
            <?php
            if($products) {
                while ($row = $products->fetch_assoc()) { ?>
                <tr>
                    <td><?= $row['title'] ?></td>
                    <td><?= $row['description'] ?></td>
                    <td><?= $row['price'] ?></td>
                    <td style="width: 10%"><a href="../deleteItemCart.php?product_id=<?= $row['id']?>">REMOVE</a></td>
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
            <?php if($products) { ?>
                <form class="checkout-form" action="" method="GET">
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
                        <textarea name="comments"></textarea>
                    </div>
                    <div>
<!--                        <a class="btn" href="">Go to index</a>-->
                        <button type="submit">Checkout</button>
                    </div>
                </form>
            <?php } ?>
        </div>

    </section>

</body>
</html>