<?php
require_once 'core/init.php';

$user = new User();

if (!$user->isLoggedIn()) {
    Redirect::to('index.php');
}

if (Input::exists()) {
    if (Token::check(Input::get('token'))) {
        $validate = new Validate();
        $validation = $validate->check($_POST, array(
            'old_password' => array(
                'required' => true
            ),
            'new_password' => array(
                'required' => true,
                'min' => 8,
                'max' => 64
            ),
            're_password' => array(
                'required' => true,
                'min' => 8,
                'max' => 64,
                'matches' => 'new_password'
            )
        ));

        if ($validation->passed()) {
            //change password
            if (Hash::make(Input::get('old_password'), $user->data()->salt) != $user->data()->password) {
                echo 'Incorrect password entered!', '<br>';
            } else {
                $salt = Hash::salt(32);
                $user->update(array(
                    'password' => Hash::make(Input::get('new_password'), $salt),
                    'salt' => $salt
                ));

                Session::flash('home', 'Password successfully updated');
                Redirect::to('index.php');
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
        <label for="old_password">Old Password</label>
        <input type="password" name="old_password" id="old_password">
    </div>

    <div class="field">
        <label for="new_password">New Password</label>
        <input type="password" name="new_password" id="new_password">
    </div>

    <div class="field">
        <label for="re_password">Re-type New Password</label>
        <input type="password" name="re_password" id="re_password">
    </div>

    <input type="hidden" name="token" value="<?php echo Token::generate();?>">
    <input type="submit" value="Update">
</form>