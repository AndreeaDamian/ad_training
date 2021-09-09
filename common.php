<?php

require_once 'config.php';

session_start();

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

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
        throw new PDOException(
            'Unable to connect to database',
            0,
            $e
        );
    }
}

function redirectIfUnauthenticated()
{
    if (!isset($_SESSION['login'])) {
        header('Location: login.php');
        exit;
    }
}

function redirectIfAuthenticated()
{
    if (isset($_SESSION['login'])) {
        header('Location: products.php');
        exit;
    }
}

function uploadImage($file)
{
    $directoryPath = __DIR__.'/uploads/';
    $name = rand().'-'.basename($file['name']);
    $filePath = $directoryPath . $name;
    $uploadOk = 1;

    $check = getimagesize($file['tmp_name']);
    if ($check !== false) {
        $uploadOk = 1;
    } else {
        $uploadOk = 0;
    }

    if ($uploadOk == 1) {
        if (!is_dir($directoryPath)) {
            mkdir($directoryPath, 0755);
        }
        if (move_uploaded_file($file['tmp_name'], $filePath)) {
            return $directoryPath.$name;
        }
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
        'Remove'        => 'ELIMINA',
        'There are no products in cart!' => 'Nu exista produse in cos!',
        'Name'          => 'Nume',
        'Contact Details' => 'Detalii de contact',
        'Comments'      => 'Comentarii',
        'Go to index'   => 'Catre Acasa',
        'Checkout'      => 'Trimite comanda',
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
        'Nr'            => 'Nr',
        'Sorry, there was an error uploading your file.' => 'Ne pare rau! A aparut o eroare la incarcarea fisierului',
        'Sorry, your file was not uploaded.' => 'Ne pare rau! Fisierul nu a fost incarcat!',
        'File is not an image.' => 'Fisierul nu este o imagine!',
        'Add'           => 'Adauga',
        'Edit'          => 'Editeaza',
        'Date'          => 'Data',
        'Customer Details' => 'Detalii client',
        'Purchased products' => 'Produse Comandate',
        'Total'         => 'Total',
        'Orders'        => 'Comenzi',
        'DETAILS'       => 'DETALII',
        'Order Nr'      => 'Comanda Nr',
        'Title is required.'      => 'Titlul este obligatoriu',
        'Price is required.' => 'Pretul este obligatoriu',
        'Contact Details are required.' => 'Datele de contact sunt obligatorii',
        'Name is required.' => 'Numele este obligatoriu',
        'Shop' => 'Shop',
    ];

    if (!isset($strings[$string])) {
        return $string;
    }
    return $strings[$string];
}

?>