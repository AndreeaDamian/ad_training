<?php
    include ('config.php');

    function connect()
    {
        $servername = DBHOST;
        $username = DBUSER;
        $password = DBPWD;
        $database = DBNAME;

        try {
            $conn = new PDO("mysql:host=$servername;dbname=$database", $username, $password);
            // set the PDO error mode to exception
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            return $conn;
        } catch(PDOException $e) {
            echo "Connection failed: " . $e->getMessage();
        }
    }

?>