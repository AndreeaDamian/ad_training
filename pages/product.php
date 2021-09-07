<?php

require_once '../common.php';

unauthenticated();

if (isset($_GET['id'])) {
    $productID = strip_tags($_GET['id']);
    $query = connect()->prepare("SELECT * FROM products WHERE id = :productID");
    $query->bindParam(':productID', $productID);
    $query->execute();
    $product = $query->fetch();
}
$titleErr = $priceErr = '';
$title = $price = '';
if (isset($_POST['_METHOD']) && $_POST['_METHOD'] == 'PUT') {
    $id = strip_tags($_POST['id']);
    $description = strip_tags($_POST['description']);
    if (empty($_POST['title'])) {
        $titleErr = translate('Title is required.');
    } else {
        $title = strip_tags($_POST['title']);
    }

    if (empty($_POST['price'])) {
        $priceErr = translate('Price is required.');
    } else {
        $price = str_replace(',', '.', strip_tags($_POST['price']));
    }

    if ($titleErr == '' && $priceErr == '') {
        if (is_uploaded_file($_FILES['image']['tmp_name'])) {
            $image = uploadImage($_FILES['image']);
            $query = connect()->prepare("
                UPDATE products 
                SET image_path = :image  
                WHERE id = :id
            ");
            $query->bindParam(':image', $image);
            $query->bindParam(':id', $id);
            $query->execute();
        }

        $query = connect()->prepare("
            UPDATE products 
            SET title = :title, description = :description, price = :price 
            WHERE id = :id
        ");
        $query->bindParam(':title', $title);
        $query->bindParam(':description', $description);
        $query->bindParam(':price', $price);
        $query->bindParam(':id', $id);
        $query->execute();

        header('Location: product.php?id='.$id);
        exit;
    }
}

if (isset($_POST['_METHOD']) && $_POST['_METHOD'] == 'POST') {
    $description = strip_tags($_POST['description']);
    $image = null;
    if (empty($_POST['title'])) {
        $titleErr = translate('Title is required.');
    } else {
        $title = strip_tags($_POST['title']);
    }

    if (empty($_POST['price'])) {
        $priceErr = translate('Price is required.');
    } else {
        $price = str_replace(',', '.', strip_tags($_POST['price']));
    }

    if ($titleErr == '' || $priceErr == '') {
        if (is_uploaded_file($_FILES['image']['tmp_name'])) {
            $image = uploadImage($_FILES['image']);
        }
        $query = connect()->prepare("
            INSERT INTO products (title, description, price, image_path) 
            VALUES (:title, :description, :price, :image)
        ");
        $query->bindParam(':title', $title);
        $query->bindParam(':description', $description);
        $query->bindParam(':price', $price);
        $query->bindParam(':image', $image);
        $query->execute();

        header('Location: products.php');
        exit;
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
        <title>Shop - <?= isset($productID) ? $product['title'] : translate('Add product') ?></title>
        <link rel="stylesheet" href="../assets/style.css">
    </head>
    <body>
        <?php require_once 'header.php'; ?>
        <section>
            <form class="checkout-form" action="<?= htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST" enctype="multipart/form-data">
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
                <p><?= isset($titleErr) ? $titleErr : '' ?></p>
                <p><?= isset($priceErr) ? $priceErr : '' ?></p>
            </div>
        </section>
    </body>
</html>