<?php
require_once 'core/init.php';

if (Input::exists('post')) {
    echo "Submitted", '<br>';
}
?>

<form action="" method="POST">
    <div class="field">
        <label for="username">Username</label>
        <input type="text" name="username" id="username" value="">
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
        <input type="text" name="first_name" id="first_name">
        <label for="last_name">Last Name</label>
        <input type="text" name="last_name" id="last_name">
        <br>
        <input type="submit" value="Register">
    </div>
</form>