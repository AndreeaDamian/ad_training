<?php
    if($_GET['product_id']) {
        session_start();

        var_dump(isset($_SESSION['cart']));

        if(!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = array();
        }

        if(!in_array($_GET['product_id'], $_SESSION['cart'])){
            array_push($_SESSION['cart'], $_GET['product_id']);
//            $_SESSION['message'] = 'Product added to cart';
        } else{
//            $_SESSION['message'] = 'Product already in cart';
        }

        header('Location: pages/index.php');

    }

?>