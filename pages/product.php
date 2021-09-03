<?php

require_once '../common.php';

if (isset($_GET['id'])) {
    $productID = strip_tags($_GET['id']);
    $query = connect()->prepare("SELECT * FROM products WHERE id ='$productID'");
    $query->execute();
    $product = $query->fetch();
}

if (isset($_POST['_METHOD']) && $_POST['_METHOD'] == 'PUT') {
    $id = strip_tags($_POST['id']);
    $title = strip_tags($_POST['title']);
    $description = strip_tags($_POST['description']);
    $price = str_replace(',', '.', strip_tags($_POST['price']));

    if (is_uploaded_file($_FILES['image']['tmp_name'])) {
        $image = uploadImage($_FILES['image']);
        $query = connect()->prepare("
            UPDATE products 
            SET image_path='$image' 
            WHERE id='$id'
        ");
        $query->execute();
    }

    $query = connect()->prepare("
        UPDATE products 
        SET title='$title', description='$description', price='$price' 
        WHERE id='$id'
    ");
    $query->execute();

    header('Location: product.php?id='.$id);
    exit;
}

if (isset($_POST['_METHOD']) && $_POST['_METHOD'] == 'POST') {
    $title = strip_tags($_POST['title']);
    $description = strip_tags($_POST['description']);
    $price = strip_tags($_POST['price']);
    $image = null;

    if (is_uploaded_file($_FILES['image']['tmp_name'])) {
        $image =  uploadImage($_FILES['image']);
    }
    $query = connect()->prepare("
        INSERT INTO products (title, description, price, image_path) 
        VALUES ('$title', '$description', '$price', '$image')
    ");
    $query->execute();

    header('Location: products.php');
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
        <title>Shop - <?= isset($productID) ? $product['title'] : translate('Add product') ?></title>
        <link rel="stylesheet" href="../assets/style.css">
    </head>
    <body>
        <?php require_once 'header.php'; ?>
        <section>
            <form class="checkout-form" action="product.php" method="POST" enctype="multipart/form-data">
                <?php if (isset($productID)): ?>
                    <input type="hidden" name="_METHOD" value="PUT">
                    <input type="hidden" name="id" value="<?= $productID ?>">
                <?php else: ?>
                    <input type="hidden" name="_METHOD" value="POST">
                <?php endif ?>
                <div>
                    <span><?= translate('Title') ?></span>
                    <input type="text" name="title" value="<?= isset($productID) ? $product['title'] : '' ?>" required>
                </div>
                <div>
                    <span><?= translate('Description') ?></span>
                    <textarea rows="7" name="description"><?= isset($productID) ? $product['description'] : '' ?></textarea>
                </div>
                <div>
                    <span><?= translate('Price') ?></span>
                    <input type="text" name="price" value="<?= isset($productID) ? $product['price'] : '' ?>" required>
                </div>
                <div>
                    <?php if (isset($productID) && $product['image_path']): ?>
                        <img src="<?= $product['image_path'] ?>" alt="">
                    <?php endif ?>
                    <input type="file" name="image" accept="image/*" id="fileToUpload">
                </div>
                <div>
                    <button type="submit"><?= isset($productID) ? translate('Edit') : translate('Add') ?></button>
                </div>
            </form>
            <div>
                <p><?= isset($error) ? $error : '' ?></p>
            </div>
        </section>
    </body>
</html>