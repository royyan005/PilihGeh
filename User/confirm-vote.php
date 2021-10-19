<?php
include('../class/Login.php');

if (!Login::isLoggedIn()) {
    header("location: ../index.php");
}
if (DB::query('SELECT password FROM datavoter WHERE userid=:userid', array(':userid'=>Login::isLoggedIn()))[0]['password']==DB::query('SELECT username FROM datavoter WHERE userid=:userid', array(':userid'=>Login::isLoggedIn()))[0]['username']){
    header("location: changepass.php");
}

if (isset($_POST['logout'])) {
    DB::query('DELETE FROM logintoken WHERE userid=:userid', array(':userid' => Login::isLoggedIn()));
    header("location: ../index.php");
}

$pemberitahuan = $vote = '';
$token = DB::query('SELECT token FROM datavoter WHERE userid=:userid', array(':userid'=> Login::isLoggedIn()))[0]['token'];

if($token==0){
    header("location: vote.php");
}
if(!empty($_GET['id'])){
    if(!empty(DB::query('SELECT idkandidat FROM kandidat WHERE idkandidat=:idkandidat', array(':idkandidat'=>$_GET['id'])))){
        $vote = $_GET['id'];
    }
}

if (isset($_POST['vote'])) {
    
    $filename = $_FILES['ktm']['name'];
    $fileTMP = $_FILES['ktm']['tmp_name'];
    $filesize = $_FILES['ktm']['size'];
    $fileerror = $_FILES['ktm']['error'];
    $filetype = $_FILES['ktm']['type'];

    $fileExt = explode('.', $filename);
    $fileExtNew = strtolower(end($fileExt));

    $fileallow = array('jpg', 'jpeg', 'png');

    if (in_array($fileExtNew, $fileallow)) {
        if ($fileerror === 0) {
            if ($filesize <  52428800) {
                $filenamenew = uniqid('', true) . "." . $fileExtNew;
                DB::query('INSERT INTO votepending VALUES (\'\', :userid, :vote, :gambar)', array(':userid' => Login::isLoggedIn(), ':vote' => $vote, ':gambar' => $filenamenew));
                $filedestination = '../KTM/' . $filenamenew;
                move_uploaded_file($fileTMP, $filedestination);
                DB::query('UPDATE datavoter SET token=0 WHERE userid=:userid', array(':userid' => Login::isLoggedIn()));
                header("location: vote.php");
            } else {
                $pemberitahuan = 'Ukuran File Terlalu Besar!';
            }
        } else {
            $pemberitahuan = 'File Gagal Di Upload Karena Error!';
        }
    } else {
        $pemberitahuan = 'Anda Tidak Bisa Upload File Dengan Tipe Data Ini!';
    }
}
?>
<script>
    function hasExtension(inputID, exts) {
        var fileName = document.getElementById(inputID).value;
        return (new RegExp('(' + exts.join('|').replace(/\./g, '\\.') + ')$')).test(fileName);
    }

    function previewImage() {
        if (hasExtension('image-source-ktm', ['png', 'jpg', 'jpeg', 'JPEG', 'JPG', 'PNG'])) {
            document.getElementById("image-preview-ktm").style.display = "block";
            var oFReader = new FileReader();
            oFReader.readAsDataURL(document.getElementById("image-source-ktm").files[0]);

            oFReader.onload = function(oFREvent) {
                document.getElementById("image-preview-ktm").src = oFREvent.target.result;
            };
        } else {
            document.getElementById("image-preview-ktm").style.display = "block";
            var oFReader = new FileReader();
            oFReader.readAsDataURL(document.getElementById("image-source-ktm").files[0]);

            oFReader.onload = function(oFREvent) {
                document.getElementById("image-preview-ktm").src = "assets/no.jpg";
            };
        }
    };
</script>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- bootstrap css -->
    <link rel="stylesheet" href="./vendor/bootstrap/css/bootstrap.min.css">
    <!-- css -->
    <link rel="stylesheet" href="./style/main.css">
    <link rel="icon" href="./assets/title.png" type="image/png">
    <title>Vote</title>
</head>
<style>
    #image-preview-ktm {
        display: none;
    }
</style>

<body>
    <!-- Navbar -->
    <div class="container-fluid">
        <nav class="row navbar navbar-expand-lg navbar-light bg-white">
            <a href="vote.php" class="navbar-brand">
                <img src="./assets/Logo.png" alt="">
            </a>

            <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navb">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navb">
                <ul class="navbar-nav ml-auto mr-3">
                    <li class="nav-item mx-md-2"><a href="vote.php" class="nav-link active">Vote</a></li>
                    <li class="nav-item mx-md-2"><a href="count-final.php" class="nav-link">Count</a></li>
                </ul>

                <!-- Mobile button -->
                <form class="form-inline d-sm-block d-md-none" action="" method="post">
                    <button class="btn btn-login my-2 my-sm-0" type="submit" name="logout">
                        Logout
                    </button>
                </form>
                <!-- Desktop button -->
                <form class="form-inline my-2 my-lg-0 d-none d-md-block" action="" method="post">
                    <button class="btn btn-login btn-navbar-right my-2 my-sm-0 px-3" type="submit" name="logout">
                        Logout
                    </button>
                </form>
            </div>
        </nav>
    </div>

    <main>
        <section class="home" id="home">
            <div class="container">
                <div class="row">
                    <div class="col text-center section-title-heading">
                        <div class="confirm-heading">
                            <span style="font-size: 20px; color: black; font-weight: bold;">KONFIRMASI VOTE</span><br>
                            <span style="font-size: 12px;">Silahkan foto bersama KTM atau Kartu Identitas lain sebagai alat bukti sah.</span>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="confirm-vote">
            <div class="container">
                <div class="row">
                    <div class="col">
                        <?php if (!empty($vote)) { ?>
                            <form class="form-confirm-vote" action="" method="post" enctype="multipart/form-data">
                                <label class="label-confirm-vote" for="">KTM atau Kartu Identitas lain.</label>
                                <div class="row">
                                    <div class="col">
                                        <img id="image-preview-ktm" alt="image-preview-ktm" style="width: 200px;">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col" style="padding-top: 5px;">
                                        <div class="upload-button-wrapper">
                                            <button class="button">Tambahkan File</button>
                                            <input type="file" name="ktm" id="image-source-ktm" accept="image/*" onchange="previewImage();" required>
                                        </div>
                                        <div style="font-size: 13px; color: red;margin-top: 5px;"> <?php echo $pemberitahuan; ?> </div>
                                    </div>
                                </div>
                                <div id="note" style="color: red; font-size: 12px;"></div>
                                <input class="submit-box" type="submit" id='submit' value="Submit" name="vote">
                            </form>
                        <?php } else { ?>
                            <div style="text-align: center;">
                                <br>
                                <img src="./assets/image_sudah_vote.png" alt="" style="width: 300px;"><br><br>
                                <span style="color: red; font-size: 20px; font-weight: bold;">ERROR PERMISSION</span>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </section>

        <footer class="section-footer mb-4 border-top">
            <div class="container-fluid">
                <div class="row footer-content justify-content-center align-items-Center pt-4">
                    <div class="col-auto text-copy text-gray-500 font-weight-light">
                        2021 Copyright PilihGeh • All rights reserved • Made in Lampung
                    </div>
                </div>
            </div>
        </footer>
    </main>

    <!-- js -->
    <script src="./vendor/jquery/jquery-3.6.0.min.js"></script>
    <script src="./vendor/bootstrap/js/bootstrap.min.js"></script>
    <script src="https://unpkg.com/scrollreveal"></script>
    <script src="./scripts/main.js"></script>
    <script>
        const now = new Date();
        const min = new Date("May 18 2021 05:45");
        const max = new Date("May 30 2021 05:50");

        document.getElementById('submit').disabled = true;
        if(now.getTime() > min.getTime() && now.getTime() < max.getTime()){
            document.getElementById('submit').disabled = false;
        } else if (now.getTime() > max.getTime()){
            document.getElementById("note").innerHTML = "*Mohon Maaf, Waktu Telah Habis!";
        } else if (now.getTime() < min.getTime()){
            document.getElementById("note").innerHTML = "*Belum Memasuki Waktu Untuk Vote!";
        }
    </script>
</body>

</html>