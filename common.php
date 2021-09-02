<?php

    require_once "config.php";

    session_start();

    function connect()
    {
        $servername = DBHOST;
        $username = DBUSER;
        $password = DBPWD;
        $database = DBNAME;

        try {
            $conn = new PDO(
                "mysql:host=$servername;dbname=$database",
                $username,
                $password
            );
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            return $conn;
        } catch (PDOException $e) {
            throw new PDOException( 'Unable to connect to database', 0, $e);
        }
    }

    function unauthenticated()
    {
        if (!isset($_SESSION['login'])) {
            header('Location: login.php');
            exit;
        }
    }

    function translate($string)
    {
        $strings = [
            'Title'         => 'Nume produs',
            'Description'   => 'Descriere',
            'Price'         => 'Pret',
            'Action'        => 'Actiune',
            'ADD TO CART'   => 'ADAUGA IN COS',
            'Login'         => 'Autentificare',
            'Home'          => 'Acasa',
            'Cart'          => 'Cosul meu',
            'Products'      => 'Produse',
            'Logout'        => 'Dezautentificare',
        ];

        if (!isset($strings[$string])) return $string;
        return $strings[$string];
    }

?>