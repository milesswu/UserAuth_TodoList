<?php
require_once 'core/init.php';

if (Input::exists('post')) {
    //checks whether the token created by the session matches the token supplied by hidden form
    if (Token::check(Input::get('token'))) {
        echo "validation begins", '<br>';
        $validate = new Validate();
        $validation = $validate->check($_POST, array(
            //these much match the field names in the form i.e. <input name="name">
            'username' => array(
                'required' => true,
                'type' => 'alphanumeric',
                'min' => 2,
                'max' => 30,
                'unique' => 'users' //will check if unique to the 'users' table
            ),
            'password' => array(
                'required' => true,
                'min' => 8,
                'max' => 30
            ),
            're_password' => array(
                'required' => true,
                'matches' => 'password' //must match password field
            ),
            'first_name' => array(
                'required' => true,
                'type' => 'alphabetic',
                'min' => 2,
                'max' => 30
            ),
            'last_name' => array(
                'required' => true,
                'type' => 'alphabetic',
                'min' => 2,
                'max' => 30
            )
        ));

        if ($validation->passed()) {
            //register user
            $user = new User();
            $salt = Hash::salt(32);
            try {
                $user->create(array(
                    'username' => Input::get('username'),
                    'password' => Hash::make(Input::get('password'), $salt),
                    'salt' => $salt,
                    'first_name' => Input::get('first_name'),
                    'last_name' => Input::get('last_name'),
                    'join_time' => date('Y-m-d:H:i:s'),
                    'group_id' => 1
                ));
            } catch(Exception $e) {
                die($e->getMessage());
            }
            //sets the session to have name 'success' with value 'Registered successfully'
            Session::flash('success', 'Registered successfully!');
            Redirect::to('index.php');
        } else {
            //error
            foreach($validation->errors() as $error) {
                echo $error, '<br>';
            }
        }
    }

}
?>

<form action="" method="POST">
    <div class="field">
        <label for="username">Username</label>
        <input 
            type="text" 
            name="username" 
            id="username" 
            value="<?php echo escape(Input::get('username')); ?>"
        >
    </div>

    <div class="field">
        <label for="password">Enter password</label>
        <input type="password" name="password" id="password">
    </div>

    <div class="field">
        <label for="re_password">Re-type password</label>
        <input type="password" name="re_password" id="re_password">
    </div>

    <div class="field">
        <label for="first_name">First Name</label>
        <input 
            type="text" 
            name="first_name" 
            id="first_name"
            value="<?php echo escape(Input::get('first_name')); ?>"    
        >
        <label for="last_name">Last Name</label>
        <input 
            type="text" 
            name="last_name" 
            id="last_name"
            value="<?php echo escape(Input::get('last_name')); ?>"
        >
        <br>
        <input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
        <input type="submit" value="Register">
    </div>
</form>