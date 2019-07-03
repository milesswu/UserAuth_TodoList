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
            echo nl2br("Connected\r\n");
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
    
    //Handle database querying, prevents sql injections
    public function query($sql, $params = array()) {
        $this->_error = false;
        if ($this->_query = $this->_pdo->prepare($sql)) {
            $x = 1;
            if (count($params)) {
                foreach($params as $param) {
                    $this->_query->bindValue($x, $param);
                    $x++;
                }
            }

            if ($this->_query->execute()) {
                $this->_results = $this->_query->fetchAll(PDO::FETCH_OBJ);
                $this->_count = $this->_query->rowCount();
            } else {
                $this->_error = true;
            }
        }

        return $this;
    }

    public function action($action, $table, $where = array()) {
        if (count($where) === 3) {
            $operators = array('=', '>', '<', '>=', '<=');
            $field =    $where[0];
            $operator = $where[1];
            $value =    $where[2];

            if (in_array($operator, $operators)) {
                $sql = "{$action} FROM {$table} WHERE ${field} {$operator} ?";
                if (!$this->query($sql, array($value))->error()) {
                    return $this;
                }
            }
        }
    }

    public function get($table, $where) {
        return $this->action('SELECT *', $table, $where);
    }

    public function delete($table, $where) {
        return $this->action('DELETE', $table, $where);
    }

    //TODO: Add a where function to handle multiple WHERE conditions for a query

    public function error() {
        return $this->_error;
    }

    public function count() {
        return $this->_count;
    }
}

?>