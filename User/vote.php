<?php
include('../class/Login.php');

if (!Login::isLoggedIn()){
    header("location: ../index.php");
}
if (DB::query('SELECT password FROM datavoter WHERE userid=:userid', array(':userid'=>Login::isLoggedIn()))[0]['password']==DB::query('SELECT username FROM datavoter WHERE userid=:userid', array(':userid'=>Login::isLoggedIn()))[0]['username']){
    header("location: changepass.php");
}

if (isset($_POST['logout'])) {
    DB::query('DELETE FROM logintoken WHERE userid=:userid', array(':userid'=>Login::isLoggedIn()));
    header("location: ../index.php");
}
$kandidat = DB::query('SELECT * FROM kandidat');
$token = DB::query('SELECT token FROM datavoter WHERE userid=:userid', array(':userid'=> Login::isLoggedIn()))[0]['token'];
?>

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
                        <h3>Pemilihan Calon Ketua Himpunan dan Wakil Ketua Himpunan Mahasiswa
                            <br />
                            Teknik Elektro Universitas Lampung
                        </h3>
                    </div>
                </div>
            </div>
        </section>

        <section class="vote" id="vote">
            <div class="container">
                <div class="vote-content row justify-content-center">
                <?php if($token==1) {?>
                    <?php 
                        foreach ($kandidat as $kandidatloop) {
                    ?>
                    <div class="card-vote d-flex flex-column">
                        <img class="card-image" src="./assets/<?php echo $kandidatloop['foto'];?>" alt="" style="width:300px; height:350px;">
                        <div class="card-body">
                          <div style="text-align: center; font-weight: 500; font-size: 18px;">Paslon <?php echo $kandidatloop['idkandidat'];?></div><br>
                          <table class="card-table" width="100%">
                                <tr>
                                    <th width="50%" class="title-card"><?php echo $kandidatloop['ketua'];?></th>
                                    <td width="50%" class="text-right card-detail">
                                        (Ketua)
                                    </td>
                                </tr>
                                <tr>
                                    <th width="50%" class="title-card"><?php echo $kandidatloop['wakil'];?></th>
                                    <td width="50%" class="text-right card-detail">
                                        (Wakil Ketua)
                                    </td>
                                </tr>
                          </table> 
                        </div>
                        <div class="vote-button mt-auto">
                            <a href="confirm-vote.php?id=<?php echo $kandidatloop['idkandidat'];?>">
                                <button type="submit" id="submit" class="btn btn-vote-option btn-block py-2">
                                    VOTE
                                </button>
                            </a>
                        </div>
                    </div>
                    
                    <?php
                        }
                        ?>
                <?php } else {?>
                    <div style="text-align: center;">
                        <img src="./assets/Image_Berhasil_Voting_Checklist.png" alt="" style="height: 230px;"><br><br>
                        <span style="font-size: 22px; font-weight: bold; color: #33cc99;">Anda Sudah Melakukan Vote</span>
                    </div>
                    <?php }?>
                </div>
            </div>
        </section>
        

        <!-- Design Pop Up -->
        <div class="modal fade" id="sumbit-vote" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content" style="border-radius: 10px;">
                    <div class="bgpopup close" data-dismiss="modal"></div>
                    <div class="popup-vote">
                        Apakah Kamu Yakin Dengan Pilihanmu?<br>
                        <span style="font-size: 13px; opacity: .6;">Pilihan yang kamu pilih tidak bisa diganti lagi</span><br>
                        <input type="submit" name="submit" class="bt-oke-popup" value="YES">
                        <input type="button" class="bt-tidak-popup" data-dismiss="modal" value="NO">
                    </div>
                </div>
            </div>
        </div>

        <footer class="section-footer mt-5 mb-4 border-top"> 
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
        const min = new Date("May 18 2021 05:00");
        const max = new Date("May 30 2021 05:10");

        $("button[id=submit]").attr("disabled", "disabled");
        if(now.getTime() > min.getTime() && now.getTime() < max.getTime()){
            $("button[id=submit]").attr("disabled", false);
        }
    </script>
</body>
</html>