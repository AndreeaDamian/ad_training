<?php
    require 'db.php';

    function getProducts()
    {
        session_start();

        global $products;

        if(!isset($_SESSION['cart']) || isset($_SESSION['cart']) && empty($_SESSION['cart'])) {
            $query="select * from products";
        } else {
            $query = "SELECT * FROM products WHERE id NOT IN (".implode(',',$_SESSION['cart']).")";
        }
        $products =  db::getInstance()->get_result($query);
        return $products;
    }

    function getSessionProducts ()
    {
        session_start();

        global $products;

        if(!empty($_SESSION['cart'])){
            $query = "SELECT * FROM products WHERE id IN (".implode(',',$_SESSION['cart']).")";
            $products =  db::getInstance()->get_result($query);
        }

        return $products;
    }

?>