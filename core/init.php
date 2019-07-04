<?php
session_start();

$GLOBALS['config'] = array(
    'mysql' => array(
        'host' => '127.0.0.1',
        'username' => 'root',
        'password' => '',
        'db' => 'login_register'
    ),
    'remember' => array(
        'cookie_name' => 'hash',
        'cookie_expiry' => 604800
    ),
    'session' => array(
        'session_name' => 'user',
        'token_name' => 'token'
    )
);

//could have a list of "require_once 'classes/Config.php';" statements to include all class files
//instead use this method to autoload classes as they are needed
//pass in a function that is run whenever a class is accessed; gets the name of the class
spl_autoload_register(function($class) {
    require_once 'classes/' . $class . '.php';
}); 

require_once 'functions/sanitize.php';

if (Cookie::exists(Config::get('remember/cookie_name')) && !Session::exists(Config::get('session/session_name'))) {
    $hash = Cookie::get(Config::get('remember/cookie_name'));
    $hashCheck = DB::getInstance()->get('users_session', array('hash', '=', $hash));

    if ($hashCheck->count()) {
        $user = new User($hashCheck->first_result()->user_id);
        $user->login();
    }
}
?>