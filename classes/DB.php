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
    
/* ************************************************************************
 * QUERY FUNCTIONS
 */

    //Handle database querying, prevents sql injections
    public function query($sql, $params = array()) {
        $this->_error = false;
        if ($this->_query = $this->_pdo->prepare($sql)) {
            $x = 1;
            if (count($params)) {
                foreach($params as $param) {
                    //echo $x . 'th parameter: ' . $param, '<br>';
                    $this->_query->bindValue($x, $param);
                    $x++;
                }
            }
            //var_dump($this->_query);
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
        return false;
    }

    public function get($table, $where) {
        return $this->action('SELECT *', $table, $where);
    }

    //TODO: Add a where function to handle multiple WHERE conditions for a query

/*****************************************************************************
 * QUERY RESULT HANDLING
 */
    public function results() {
        return $this->_results;
    }

    public function first_result() {
        return $this->results()[0];
    }

/********************************************************************************
 * INSERTION, DELETION, AND UPDATING
 */

    public function insert($table, $fields = array()) {
        if (!count($fields)) {
            return false;
        }
        $keys = array_keys($fields); //returns keys of the fields array
        $values = '';
        $x = 1;

        foreach($fields as $field) {
            $values .= "?";
            if ($x < count($fields)) {
                $values .= ', ';
            }
            $x++;
        }
        $sql = "INSERT INTO ${table} (" . implode(', ', $keys) . ") VALUES({$values})";
        //echo $sql, '<br>';
        if (!$this->query($sql, $fields)->error()) {
            return true;
        }
        return false;
    }

    //TODO: Change update function to be more robust
    public function update($table, $id, $fields = array()) {
        $set = '';
        $x = 1;

        //parse fields to be updated
        foreach($fields as $name => $value) {
            $set .= "{$name} = ?";
            if ($x < count($fields)) {
                $set .= ', ';
            }
            $x++;
        }

        $sql = "UPDATE {$table} SET {$set} WHERE user_id = {$id}";
        //echo $sql, '<br>';
        if (!$this->query($sql, $fields)->error()) {
            return true;
        }
        
        return false;
    }

    public function delete($table, $where) {
        return $this->action('DELETE', $table, $where);
    }

/******************************************************************************88
 * HELPER FUNCTIONS
 */
    public function error() {
        return $this->_error;
    }

    public function count() {
        return $this->_count;
    }
}

?>