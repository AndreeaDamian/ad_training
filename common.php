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
            'Remove'        => 'Sterge',
            'There are no products in cart!' => 'Nu exista produse in cos!',
            'Name'          => 'Nume',
            'Contact Details' => 'Detalii de contact',
            'Comments'      => 'Comentarii',
            'Go to index'   => 'Catre Acasa',
            'Checkout'      => 'Checkout',
            'Shop Order'    => 'Comanda magazin',
            'Order Date'    => 'Data comanda',
            'Product Name'  => 'Nume produs',
            'Image'         => 'Imagine',
            'Email'         => 'Email',
            'Password'      => 'Parola',
            'Login failed! Email or password incorrect!' => 'Autentificare nereusita! Email sau parola incorecta!',
            'Add product'   => 'Adauga produs',
            'Actions'       => 'Actiuni',
            'EDIT'          => 'EDITEAZA',
            'DELETE'        => 'STERGE',
        ];

        if (!isset($strings[$string])) return $string;
        return $strings[$string];
    }

?>