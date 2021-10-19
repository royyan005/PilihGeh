<?php
include('./class/Login.php');
$Error = '';

if (Login::isLoggedIn()){
    header("location: ./User/vote.php");
} else if (LoginAdmin::isLoggedIn()){
    header("location: ./Admin/index.php");
}

if (isset($_POST['login'])) {
  $username = $_POST['username'];
  $password = $_POST['password'];
  
  if (DB::query('SELECT username FROM datavoter WHERE username=:username', array(':username'=>$username))) {
    if($password==DB::query('SELECT password FROM datavoter WHERE username=:username', array(':username'=>$username))[0]['password']) {
              
        $cstrong = True;
        $token = bin2hex(openssl_random_pseudo_bytes(64, $cstrong)) ;
        $userid = DB::query('SELECT userid FROM datavoter WHERE username=:username', array(':username'=>$username))[0]['userid'];
        DB::query('DELETE FROM logintoken WHERE userid=:userid', array(':userid'=>$userid));
        DB::query('INSERT INTO logintoken VALUES (\'\', :token, :userid)', array(':token'=>sha1($token), ':userid'=>$userid));

        setcookie("SNID", $token, time() + 60 * 60 * 24 * 7, '/', NULL, NULL, TRUE);
        setcookie("SNID_", '1', time() + 60 * 60 * 24 * 3, '/', NULL, NULL, TRUE);

        header("location: ./User/changepass.php");
    } else if(password_verify($password,DB::query('SELECT password FROM datavoter WHERE username=:username', array(':username'=>$username))[0]['password'])) {
              
        $cstrong = True;
        $token = bin2hex(openssl_random_pseudo_bytes(64, $cstrong)) ;
        $userid = DB::query('SELECT userid FROM datavoter WHERE username=:username', array(':username'=>$username))[0]['userid'];
        DB::query('DELETE FROM logintoken WHERE userid=:userid', array(':userid'=>$userid));
        DB::query('INSERT INTO logintoken VALUES (\'\', :token, :userid)', array(':token'=>sha1($token), ':userid'=>$userid));

        setcookie("SNID", $token, time() + 60 * 60 * 24 * 7, '/', NULL, NULL, TRUE);
        setcookie("SNID_", '1', time() + 60 * 60 * 24 * 3, '/', NULL, NULL, TRUE);

        header("location: ./User/vote.php");
    } else {
      $Error =  "Incorrect Password";
    }
  } else if (DB::query('SELECT username FROM admin WHERE username=:username', array(':username'=>$username))) {
    if(password_verify($password,DB::query('SELECT password FROM admin WHERE username=:username', array(':username'=>$username))[0]['password'])) {
        
        $cstrong = True;
        $token = bin2hex(openssl_random_pseudo_bytes(64, $cstrong)) ;
        $id = DB::query('SELECT id FROM admin WHERE username=:username', array(':username'=>$username))[0]['id'];
        DB::query('DELETE FROM logintokenadmin WHERE idadmin=:idadmin', array(':idadmin'=>$id));
        DB::query('INSERT INTO logintokenadmin VALUES (\'\', :token, :id)', array(':token'=>sha1($token), ':id'=>$id));

        setcookie("SNID", $token, time() + 60 * 60 * 24 * 7, '/', NULL, NULL, TRUE);
        setcookie("SNID_", '1', time() + 60 * 60 * 24 * 3, '/', NULL, NULL, TRUE);
        
        header("location: ./Admin/index.php");
    } else {
        $Error = "Incorrect Password";
    }
  } else {
    $Error =  "You're Not Registered";
  }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pemira HIMATRO</title>
    <link rel="stylesheet" href="./User/vendor/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="./User/vendor/bootstrap/js/bootstrap.min.js">
    <link rel="stylesheet" href="./User/style/stylesfix.css">
    <link rel="icon" href="./User/assets/title.png" type="image/png">
    <script src="https://kit.fontawesome.com/386e6055da.js" crossorigin="anonymous"></script>
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
                                        <img class="logo" src="./User/assets/Logo.png" alt="">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col login-form">
                                        <div style="font-size: 32px; font-weight: bold;">Log In.</div>
                                        <div class="text-opacity info-login">Log in with username and password.</div>
                                        <div class="form-login">
                                            <form action="" method="post">
                                                <label class="text-opacity" for="">Username</label><br>
                                                <input class="form-box" type="text" name="username" placeholder="username"><br>
                                                <label class="text-opacity" for="">Password</label><br>
                                                <input class="form-box" type="password" name="password" placeholder="your password"><br>
                                                <span class="error"><?php echo $Error;?></span><br>
                                                <input class="submit-box" type="submit" name="login" value="Log in">
                                            </form><br><br>
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
</body>
</html>