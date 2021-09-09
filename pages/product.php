<?php

require_once '../common.php';

redirectIfUnauthenticated();

$conn = connect();

if (isset($_GET['id'])) {
    $productId = strip_tags($_GET['id']);
    $query = $conn->prepare('SELECT * FROM products WHERE id = ?');
    $query->execute([$productId]);
    $product = $query->fetch();
}

$errors = [];
$data = [];

if (isset($_POST['_METHOD']) && $_POST['_METHOD'] == 'PUT') {
    $id = strip_tags($_POST['id']);
    $data['description'] = strip_tags($_POST['description']);
    if (empty($_POST['title'])) {
        $errors['title'] = translate('Title is required.');
    } else {
        $data['title'] = strip_tags($_POST['title']);
    }

    if (empty($_POST['price'])) {
        $errors['price'] = translate('Price is required.');
    } else {
        $data['price'] = str_replace(',', '.', strip_tags($_POST['price']));
    }

    if (is_uploaded_file($_FILES['image'])) {
        $check = getimagesize($_FILES['image']['tmp_name']);
        if ($check == false) {
            $errors['image'] = translate('File is not an image.');
        }
    }

    if (count($errors) == 0) {
        if (is_uploaded_file($_FILES['image']['tmp_name'])) {
            $image = uploadImage($_FILES['image']);
            $query = $conn->prepare('
                UPDATE products 
                SET image_path = ?  
                WHERE id = ?
            ');
            $query->execute([$image, $id]);
        }

        $query = $conn->prepare('
            UPDATE products 
            SET title = ?, description = ?, price = ? 
            WHERE id = ?
        ');
        $query->execute([
            $data['title'],
            $data['description'],
            $data['price'],
            $id
        ]);

        header('Location: product.php?id='.$id);
        exit;
    }
}

if (isset($_POST['_METHOD']) && $_POST['_METHOD'] == 'POST') {
    $data['description'] = strip_tags($_POST['description']);
    $image = null;
    if (empty($_POST['title'])) {
        $errors['title'] = translate('Title is required.');
    } else {
        $data['title'] = strip_tags($_POST['title']);
    }

    if (empty($_POST['price'])) {
        $errors['price'] = translate('Price is required.');
    } else {
        $data['price'] = str_replace(',', '.', strip_tags($_POST['price']));
    }

    if($_FILES['image']) {
        $check = getimagesize($_FILES['image']['tmp_name']);
        if ($check == false) {
            $errors['image'] = translate('File is not an image.');
        }
    }

    if (count($errors) == 0) {
        if (is_uploaded_file($_FILES['image']['tmp_name'])) {
            $image = uploadImage($_FILES['image']);
        }
        $query = $conn->prepare('
            INSERT INTO products (title, description, price, image_path)
            VALUES (?, ?, ?, ?)
        ');
        $query->execute([
            $data['title'],
            $data['description'],
            $data['price'],
            $image
        ]);

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
        <title><?= translate('Shop') ?> - <?= isset($product) ? $product['title'] : translate('Add product') ?></title>
        <link rel="stylesheet" href="../assets/style.css">
    </head>
    <body>
        <?php require_once 'header.php'; ?>
        <section>
            <form class="checkout-form" method="POST" enctype="multipart/form-data">
                <?php if (isset($product)): ?>
                    <input type="hidden" name="_METHOD" value="PUT">
                    <input type="hidden" name="id" value="<?= $product['id'] ?>">
                <?php else: ?>
                    <input type="hidden" name="_METHOD" value="POST">
                <?php endif ?>
                <div>
                    <span><?= translate('Title') ?></span>
                    <input type="text" name="title" value="<?= $product['title'] ?? $_POST['title'] ?? '' ?>">
                </div>
                <div>
                    <span><?= translate('Description') ?></span>
                    <textarea rows="7" name="description"><?= $product['description'] ?? $_POST['description'] ?? '' ?></textarea>
                </div>
                <div>
                    <span><?= translate('Price') ?></span>
                    <input type="text" name="price" value="<?= $product['price'] ?? $_POST['price'] ?? '' ?>">
                </div>
                <div>
                    <?php if (isset($product) && $product['image_path']): ?>
                        <img src="<?= $product['image_path'] ?>" alt="">
                    <?php endif ?>
                    <input type="file" name="image" accept="image/*" id="fileToUpload">
                </div>
                <div>
                    <button type="submit"><?= isset($product) ? translate('Edit') : translate('Add') ?></button>
                </div>
            </form>
            <div>
                <p><?= $errors['title'] ?? '' ?></p>
                <p><?= $errors['price'] ?? '' ?></p>
                <p><?= $errors['image'] ?? '' ?></p>
            </div>
        </section>
    </body>
</html>