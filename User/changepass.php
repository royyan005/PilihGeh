<?php
include('../class/Login.php');
$Error = '';

if (!Login::isLoggedIn()){
    header("location: ../index.php");
}
if (DB::query('SELECT password FROM datavoter WHERE userid=:userid', array(':userid'=>Login::isLoggedIn()))[0]['password']!=DB::query('SELECT username FROM datavoter WHERE userid=:userid', array(':userid'=>Login::isLoggedIn()))[0]['username']){
    header("location: vote.php");
}

if (isset($_POST['change'])) {
  $password = $_POST['password'];
  $repeatpassword = $_POST['repeat'];
  
  if($password==$repeatpassword){
    DB::query('UPDATE datavoter SET password=:password WHERE userid=:userid', array(':password'=>password_hash($password, PASSWORD_BCRYPT), ':userid'=>Login::isLoggedIn()));
    header("location: vote.php");
  } 
}
if (isset($_POST['logout'])) {
    DB::query('DELETE FROM logintoken WHERE userid=:userid', array(':userid'=>Login::isLoggedIn()));
    header("location: ../index.php");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pemira HIMATRO</title>
    <link rel="stylesheet" href="./vendor/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="./vendor/bootstrap/js/bootstrap.min.js">
    <link rel="stylesheet" href="./style/stylesfix.css">
    <link rel="icon" href="./assets/title.png" type="image/png">
    <script src="https://kit.fontawesome.com/386e6055da.js" crossorigin="anonymous"></script>
    <script>
        function verify(){
            var pass = document.getElementById("pass");
            var repeat = document.getElementById("repeatpass");
            var error = document.getElementById("error");
            if(pass.value !== repeat.value){
                error.innerHTML = 'Password Tidak Sama!';
                error.style.color = 'red';
                document.getElementById("change").disabled = true;
            } else if (pass.value === '' || repeat.value === ''){
                error.innerHTML = 'Harus Isi Keduanya!';
                error.style.color = 'red';
                document.getElementById("change").disabled = true;
            } else {
                error.innerHTML = 'Sama';
                error.style.color = 'green';
                document.getElementById("change").disabled = false;
            }
        }
    </script>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <div class="col">
                <div class="row">
                    <div class="col-12 col-xl-7 welcome-text">
                        <div class="text-1">Selamat Datang Di</div>
                        <div class="text-1">"PilihGeh"</div>
                        <div class="text-2">Choose the person you like to be the winner</div>
                    </div>
                    <div class="col-12 col-xl-5 right-content">
                        <div class="row">
                            <div class="col login">
                                <div class="row">
                                    <div class="col login-logo text-center">
                                        <img class="logo" src="./assets/Logo.png" alt="">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col login-form">
                                        <div style="font-size: 32px; font-weight: bold;">Change Password</div>
                                        <div class="text-opacity info-login">Change Your Password For The First Time, <?php echo DB::query('SELECT nama FROM datavoter WHERE userid=:userid', array(':userid'=>Login::isLoggedIn()))[0]['nama']?></div>
                                        <div class="form-login">
                                            <form action="" method="post">
                                                <label class="text-opacity" for="">Password</label><br>
                                                <input class="form-box" type="password" name="password" id="pass" placeholder="New Password" onkeyup="verify();"><br>
                                                <label class="text-opacity" for="">Repeat Password</label><br>
                                                <input class="form-box" type="password" name="repeat" id="repeatpass" placeholder="Verify New Password" onkeyup="verify();"><br>
                                                <span class="error" id="error"></span><br>
                                                <input class="submit-box" type="submit" name="change" id="change" value="Change Password"><br>
                                                <button class="logout-button" name="logout">Logout</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row footer-aje">
                            <div class="col login-footer">
                                <hr>
                                <span>2021 Copyright PilihGeh &#8226; All Rights reserved &#8226; Made in Lampung</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="scroll">
    </div>

    <script src="//code.jquery.com/jquery-3.1.0.js"></script>
    <script>
        function goTo(selector, timeout, cb) {
            var $el = $(selector);
            if (!$el[0]) return;
            var $par = $el.parent();
            if ($par.is("body")) $par = $("html, body");
                setTimeout(() => {
                $par.stop().animate({scrollTop: $el.offset().top}, 1000, cb && cb.call($el[0]));
                }, timeout || 0);
        }

    goTo("#scroll", 3000, function(){

    });
    </script>
    <script>
        $("input[id=change]").attr("disabled", "disabled");
    </script>
</body>
</html>