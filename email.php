<html>
    <head>
        <title><?= translate('Shop Order') ?></title>
    </head>
    <body>
        <p><?= translate('Order Date') ?>: <?= $date ?></p>
        <table style="border: solid 2px black; border-collapse: collapse; display: table;  width: 100%;">
            <tr style="border: solid 1px black; padding: 10px; text-align: left;">
                <th><?= translate('Name') ?></th>
                <th><?= translate('Contact Details') ?></th>
                <th><?= translate('Comment') ?></th>
            </tr>
            <tr style="border: solid 1px black; padding: 10px; text-align: left;">
                <td><?= $name ?></td>
                <td><?= $contactDetails ?></td>
                <td><?= $comment ?></td>
            </tr>
        </table>
        <br>
        <table style="border: solid 2px black; border-collapse: collapse; display: table;  width: 100%;">
            <tr style="border: solid 1px black; padding: 10px; text-align: left;">
                <th><?= translate('Product Name') ?></th>
                <th><?= translate('Image') ?></th>
                <th><?= translate('Description') ?></th>
                <th><?= translate('Price') ?></th>
                <th><?= translate('Image') ?></th>
            </tr>
           <?php foreach ($products as $product): ?>
                <tr style="border: solid 1px black; padding: 10px; text-align: left;">
                    <td><?= $product['title'] ?></td>
                    <td><img style="height: 100px;display: flex;" src="<?= $product['image_path'] ?>"></td>
                    <td><?= $product['description'] ?></td>
                    <td><?= $product['price'] ?></td>
                    <td><?= $product['image_path'] ?></td>
                </tr>
            <?php endforeach ?>
        </table>
    </body>
</html>