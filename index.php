<?php
require_once 'core/init.php';

//this function will load according to specified host without needing to directly acces the globals array
var_dump(Config::get('mysql/host')); 

//Example of how we might use the DB class to easily access database through instances
/*
    $users = DB::getInstance()->query('SELECT username FROM users');
    if ($users->count()) {
        foreach($users as $user) {
            echo $user->$username;
        }
    }
*/
?>