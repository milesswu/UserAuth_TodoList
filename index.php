<?php
require_once 'core/init.php';

if (Session::exists('success')) {
    //prints the value associated with the 'success' session name
    echo Session::flash('success'), '<br>';
}

if (Session::exists('home')) {
    echo '<p>' . Session::flash('home') . '<p>';
}

//this function will load according to specified host without needing to directly acces the globals array
//echo Config::get('mysql/host') . nl2br("\n"); 
//echo Session::get(Config::get('session/session_name'));

$user = new User();
if ($user->isLoggedIn()) {
    ?>
    <p>Hello <a href="#"><?php echo escape($user->data()->username); ?></a>!</p>
    <ul>
        <li><a href="logout.php">Log out</a></li>
    </ul>
<?php
} else {
    ?>
    <a href="login.php">Log in</a><br>
    <a href="register.php">Register</a>
<?php
}



//DB::getInstance();
//DB::getInstance();
//Example of how we might use the DB class to easily access database through instances
/*
    $users = DB::getInstance()->query('SELECT username FROM users');
    if ($users->count()) {
        foreach($users as $user) {
            echo $user->$username;
        }
    }
*/
/*
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
    
    echo $user->first_result()->username, '<br>';
}
/*
 var_dump($userInsert = DB::getInstance()->insert('users', array(
    'username' => 'rgeil',
    'password' => 'backend-god',
    'salt' => 'salt',
    'first_name' => 'Robert',
    'last_name' => 'Gle'
)));


$userInsert = DB::getInstance()->update('users', 2, array(
    'last_name' => 'Geil'
));
*/
?>