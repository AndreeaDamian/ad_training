<?php

session_start();

$key = array_search($_GET['product_id'], $_SESSION['cart']);

unset($_SESSION['cart'][$key]);

//$_SESSION['message'] = "Product deleted from cart";
header('location: pages/cart.php');
?>