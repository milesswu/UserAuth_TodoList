<?php
class User {
    private $_db,
            $_data,
            $_sessionName,
            $_cookieName,
            $_isLoggedIn;

    public function __construct($user = null) {
        $this->_db = DB::getInstance();
        $this->_sessionName = Config::get('session/session_name');
        $this->_cookieName = Config::get('remember/cookie_name');

        if (!$user) {
            if(Session::exists($this->_sessionName)) {
                $user = Session::get($this->_sessionName);

                if ($this->find($user)) {
                    $this->_isLoggedIn = true;
                } else {
                    //logout
                }
            }
        } else {
            $this->find($user);
        }
    }

    public function exists() {
        return (!empty($this->_data)) ? true : false;
    }

    public function create($fields = array()) {
        if (!$this->_db->insert('users', $fields)) {
            throw new Exception('Error creating new account');
        }
    }

    public function login($username = null, $password = null, $remember = false) {

        //automatically logs back in if user data still exists
        if (!$username && !$password && $this->exists()) {
            Session::put($this->_sessionName, $this->data()->user_id);
            return true;
        }

        $user = $this->find($username);
        //print_r($this->_data);
        if ($user) {
            if ($this->data()->password === Hash::make($password, $this->data()->salt)) {
                Session::put($this->_sessionName, $this->data()->user_id);

                //handle remember me functionality
                if ($remember) {
                    //generate hash
                    $hash = Hash::unique();
                    $hashCheck = $this->_db->get('users_session', array('user_id', '=', $this->data()->user_id));

                    //ensure user is assigned a hash
                    if (!$hashCheck->count()) {
                        $this->_db->insert('users_session', array(
                            'user_id' => $this->data()->user_id,
                            'hash' => $hash
                        ));
                    } else {
                        $hash = $hashCheck->first_result()->hash;
                    }

                    Cookie::create($this->_cookieName, $hash, Config::get('remember/cookie_expiry'));

                }

                return true;
            }
        }

        return false;
    }

    public function logout() {
        $this->_db->delete('users_session', array('user_id', '=', $this->data()->user_id));
        Session::delete($this->_sessionName);
        Cookie::delete($this->_cookieName);
    }

    //helper funcitno to find a user in the database
    public function find($user = null) {
        if ($user) {
            $field = (is_numeric($user)) ? 'user_id' : 'username';
            $data = $this->_db->get('users', array($field, '=', $user));

            if ($data->count()) {
                $this->_data = $data->first_result();
                return true;
            }
        }
        return false;
    }

    public function data() {
        return $this->_data;
    }

    public function isLoggedIn() {
        return $this->_isLoggedIn;
    }
}

?>