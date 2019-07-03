<?php
require_once 'core/init.php';

//this function will load according to specified host without needing to directly acces the globals array
echo Config::get('mysql/host') . nl2br("\n"); 

//Example of how we might use the DB class to easily access database through instances
/*
    $users = DB::getInstance()->query('SELECT username FROM users');
    if ($users->count()) {
        foreach($users as $user) {
            echo $user->$username;
        }
    }
*/

$user = DB::getInstance();
$user->query("SELECT username FROM users WHERE username = ?", array('robert'));
if (!$user->count()) {
    echo nl2br("No user\r\n");
} else {
    echo nl2br("OK\r\n");
}

$user->get('users', array('username', '=', 'milesswu'));

if (!$user->count()) {
    echo nl2br("No user\r\n");
} else {
    echo nl2br("OK\r\n");
    /*
    foreach($user->results() as $user) {
        echo $user->first_name, '<br>';
    }
    */
    echo $user->first_result()->username;
}
?>