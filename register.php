<?php
require_once 'core/init.php';

if (Input::exists('post')) {
    $validate = new Validate();
    $validation = $validate->check($_POST, array(
        //these much match the field names in the form i.e. <input name="name">
        'username' => array(
            'required' => true,
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
            'min' => 2,
            'max' => 30
        ),
        'last_name' => array(
            'required' => true,
            'min' => 2,
            'max' => 30
        )
    ));

    if ($validation->passed()) {
        //register user
        echo "Submitted successfully", '<br>';
    } else {
        //error
        print_r($validation->errors());
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
        <input type="submit" value="Register">
    </div>
</form>