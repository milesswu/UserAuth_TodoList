<?php
require_once 'core/init.php';

$user = new User();

if(!$user->isLoggedIn()) {
    Redirect::to('index.php');
}

if (Input::exists()) {
    if (Token::check(Input::get('token'))) {
        $validate = new Validate();
        $validation = $validate->check($_POST, array(
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
            try {
                $user->update(array(
                    'first_name' => Input::get('first_name'),
                    'last_name' => Input::get('last_name')
                ));

                Session::flash('home', 'Informatino successfully updated');
                Redirect::to('index.php');
            } catch(Exception $e) {
                die($e->getMessage());
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
        <label for="first_name">First Name</label>
        <input type="text" name="first_name" value ="<?php echo escape($user->data()->first_name); ?>">
        <br>
        <label for="last_name">Last Name</label>
        <input type="text" name="last_name" value ="<?php echo escape($user->data()->last_name); ?>">
        <br>
        <input type="hidden" name="token" value="<?php echo Token::generate();?>">
        <input type="submit" value="Update">
    </div>
</form>