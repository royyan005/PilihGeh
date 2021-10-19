<?php
include('../class/DB.php');

if (isset($_POST['createaccount'])) {
    $nama = $_POST['nama'];
    $username = $_POST['username'];
    $password = $_POST['password'];

    if (!DB::query('SELECT username FROM admin where username=:username', array(':username'=>$username))) {

        if (strlen($username) >= 3 && strlen($username) <= 32) {

            if (preg_match('/[a-zA-Z0-9_]+/', $username)) {
                        DB::query('INSERT INTO admin VALUES (\'\', :nama, :username, :password)', array(':nama'=>$nama, ':username'=>$username, ':password'=>password_hash($password, PASSWORD_BCRYPT)));
                        echo 'Success!';
            } else {
                echo 'Invalid Username!';
            }
        } else {
        echo 'Invalid Username!';
        }
    } else {
        echo 'User already exists!';
    }
}

?>

<h1>Register</h1>
<form action="create-account.php" method="post">
<input type="text" name="nama" value="" placeholder="Nama Admin"><p />
<input type="text" name="username" value="" placeholder="Username ..."><p />
<input type="password" name="password" value="" placeholder="Password ..."><p />
<input type="submit" name="createaccount" value="Create Account">
</form>