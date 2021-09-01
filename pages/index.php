<?php
    require_once('../common.php');
    session_start();

    if(!isset($_SESSION['cart']) || isset($_SESSION['cart']) && empty($_SESSION['cart'])) {
        $query = connect()->prepare("SELECT * FROM products");
        $query->execute();
    } else {
        $query = connect()->prepare("SELECT * FROM products WHERE id NOT IN (".implode(',',$_SESSION['cart']).")");
        $query->execute();
    }
    $query->setFetchMode(PDO::FETCH_ASSOC);
    $products = $query->fetchAll();

    if(isset($_GET['product_id'])) {
        if(!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }

        if(!in_array($_GET['product_id'], $_SESSION['cart'])){
            array_push($_SESSION['cart'], $_GET['product_id']);
        }

        header('Location: index.php');
    }
?>


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
    <section>
        <table>
            <tr>
                <th>Title</th>
                <th>Description</th>
                <th>Price</th>
                <th>Action</th>
            </tr>
            <?php
                foreach ($products as $row) { ?>
                    <tr>
                        <td><?= $row['title'] ?></td>
                        <td><?= $row['description'] ?></td>
                        <td><?= $row['price'] ?></td>
                        <td style="width: 10%"><a href="index.php?product_id=<?= $row['id'] ?>">ADD TO CART</a></td>
                    </tr>
            <?php } ?>

        </table>
    </section>
</body>
</html>