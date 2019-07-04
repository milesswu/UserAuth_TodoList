<?php
require_once 'core/init.php';

if (Input::exists()) {
    if (Token::check(Input::get('token'))) {
        echo "validation begins", '<br>';
        $validate = new Validate();
        $validation = $validate->check($_POST, array(
            //these much match the field names in the form i.e. <input name="name">
            'username' => array(
                'required' => true,
            ),
            'password' => array(
                'required' => true,
            ),
        ));

        if ($validation->passed()) {
            //log in
            $user = new User();
            $remember = (Input::get('remember') === 'on') ? true : false;
            $login = $user->login(Input::get('username'), Input::get('password'), $remember);

            if ($login) {
                Redirect::to('index.php');
            } else {
                echo 'Failed to login!', '<br>';
            }
        } else {
            foreach($validation->errors() as $error) {
                echo $error, '<br>';
            }
        }
    }
}

?>

<form action="" method="POST">
    <div class="field">
        <label for="username">Enter Username</label>
        <input 
            type="text" 
            name="username" 
            id="username" 
            value="<?php echo escape(Input::get('username')); ?>"
        >
    </div>

    <div class="field">
        <label for="password">Enter Password</label>
        <input type="password" name="password" id="password">
    </div>

    <div class="field">
        <label for="remember">
            <input type="checkbox" name="remember" id="remember">Remember Me?
        </label> 
    </div>

    <input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
    <input type="submit" value="Log In">
</form>