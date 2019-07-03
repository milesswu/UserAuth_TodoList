<?php
class DB {
    private static $_instance = null;
    private $_pdo,              //stores instantiated pdo object
            $_query,            //store last query executed
            $_error = false,    //whether there has been an error
            $_results,          //result set
            $_count = 0;        //number of results

    //Connects to database on construction
    //this is private with the intention that instances of DB go through the getInstance function
    private function __construct() {
        try {
            //PDO object takes host and database string, username, password
            $this->_pdo = new PDO('mysql:host=' . Config::get('mysql/host') . ';dbname=' . Config::get('mysql/db'),
                Config::get('mysql/username'),
                Config::get('mysql/password')); 
            echo "Connected";
        } catch(PDOException $e) {
            die($e->getMessage());
        }
    }

    //Returns a new instance of the database connection if does not already exist
    //Otherwise, simply returns existing instance (no need to reconnect)
    public static function getInstance() {
        if (!isset(self::$_instance)) {
            self::$_instance = new DB();
        }
        return self::$_instance;
    }
}

?>