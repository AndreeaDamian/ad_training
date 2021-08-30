<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport"
              content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Shop</title>
        <link rel="stylesheet" href="../assets/style.css">
    </head>
    <body>
       <?php include("header.php"); ?>
       <?php
            include("../common.php");
            getProducts();
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
                    while ($row = $products->fetch_assoc()) { ?>
                        <tr>
                            <td><?= $row['title'] ?></td>
                            <td><?= $row['description'] ?></td>
                            <td><?= $row['price'] ?></td>
                            <td style="width: 10%"><a href="../addToCart.php?product_id=<?= $row['id']?>">ADD TO CART</a></td>
                        </tr>
                <?php } ?>
            </table>
        </section>
    </body>
</html>