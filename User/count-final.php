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
    <link rel="stylesheet" href="./style/styles.css">
    <link rel="icon" href="./assets/title.png" type="image/png">
    <title>Final Count</title>
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
                    <li class="nav-item mx-md-2"><a href="vote.php" class="nav-link">Vote</a></li>
                    <li class="nav-item mx-md-2"><a href="count-final.php" class="nav-link active">Count</a></li>
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
        <section>
            <div class="container-fluid">
                <div class="row">
                    <div class="col">
                        <div class="circle">
                            <div class="count-num">
                            <?php
                                echo DB::query('SELECT COUNT(userid) FROM votepending')[0][0]+ DB::query('SELECT COUNT(userid) FROM votemasuk')[0][0]+DB::query('SELECT COUNT(userid) FROM votetolak')[0][0];
                            ?>
                            </div>
                        </div>
                        <div class="count-text">Total Suara Masuk</div>
                    </div>
                </div>
            </div>
        </section>
        <section>
            <div class="container">
                <div class="row">
                    <?php
                        foreach($kandidat as $loop){
                    ?>
                    <div class="col-md">
                        <div class="circle2">
                            <div class="count-num2">
                            <?php
                                echo DB::query('SELECT COUNT(userid) FROM votemasuk WHERE vote=:vote', array(':vote'=>$loop['idkandidat']))[0][0];
                            ?>
                            </div>
                        </div>
                        <div class="count-text2" id='namapaslon'>Paslon <?php echo $loop['idkandidat'];?></div>
                    </div>
                    <?php } ?>
                    <div class="col-md-12">
                        <div class="circle2">
                            <div class="count-num2">
                            <?php
                                echo DB::query('SELECT COUNT(userid) FROM votetolak')[0][0];
                            ?>
                            </div>
                        </div>
                        <div class="count-text2" id='namapaslon'>Tidak Sah</div>
                    </div>
                </div>
            </div>
        </section>

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

        if(now.getTime() < min.getTime()){
            $("div[class=circle2]").remove();
            $("div[id=namapaslon]").remove();
        } 
    </script>
</body>
</html>