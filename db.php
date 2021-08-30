<?php
include('config.php');
class db extends mysqli {


    // single instance of self shared among all instances
    private static $instance = null;


    // db connection config vars
    private $user = DBUSER;
    private $pass = DBPWD;
    private $dbName = DBNAME;
    private $dbHost = DBHOST;

    private function __construct() {
        parent::__construct($this->dbHost, $this->user, $this->pass, $this->dbName);
        if (mysqli_connect_error()) {
            exit('Connect Error (' . mysqli_connect_errno() . ') '
                . mysqli_connect_error());
        }
        parent::set_charset('utf-8');

    }

    public static function getInstance() {
        if (!self::$instance instanceof self) {
            self::$instance = new self;
        }
        return self::$instance;
    }

    public function __clone() {
        trigger_error('Clone is not allowed.', E_USER_ERROR);
    }

    public function __wakeup() {
        trigger_error('Deserializing is not allowed.', E_USER_ERROR);
    }

    public function dbquery($query)
    {
        if($this->query($query))
        {
            return true;
        }
    }

    public function get_result($query)
    {
        $result = $this->query($query);
        if ($result->num_rows > 0){
            return $result;
        } else
            return null;
    }
}

?>