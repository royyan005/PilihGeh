<?php
    include('../class/Login.php');

    if (!LoginAdmin::isLoggedIn()){
        header("location: ../index.php");
    }

    if (isset($_POST['logout'])) {
        DB::query('DELETE FROM logintokenadmin WHERE idadmin=:idadmin', array(':idadmin'=>LoginAdmin::isLoggedIn()));
        header("location: ../index.php");
    }
$data = DB::query('SELECT * FROM kandidat');
$pemberitahuan = '';
if(isset($_POST['hapus'])){
    $pemberitahuan = 'Kandidat nomor '.$_POST['hapus'].' telah dihapus!';
    unlink("../User/assets/".DB::query('SELECT foto FROM kandidat WHERE idkandidat=:idkandidat', array(':idkandidat'=>$_POST['hapus']))[0]['foto']);
    DB::query('DELETE FROM kandidat WHERE idkandidat=:idkandidat', array(':idkandidat'=>$_POST['hapus']));
    $data = DB::query('SELECT * FROM kandidat');
}
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>PilihGeh - PendingVote</title>

    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.css" rel="stylesheet">
    <link rel="icon" href="../User/assets/title.png" type="image/png">

</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <ul class="navbar-nav bg-gradient-dashboard sidebar sidebar-dark accordion" id="accordionSidebar">

            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.php">
                <div class="sidebar-brand-text mx-3">Pilihgeh Admin</div>
            </a>

            <!-- Divider -->
            <hr class="sidebar-divider my-0">

            <!-- Nav Item - Dashboard -->
            <li class="nav-item">
                <a class="nav-link" href="index.php">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Dashboard</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="vote.php">
                    <i class="fas fa-fw fa-child"></i>
                    <span>Daftar Voters</span>
                </a>
            </li>


            <li class="nav-item">
                <a class="nav-link" href="pendingvote.php">
                    <i class="fas fa-fw fa-spinner"></i>
                    <span>Pending Vote</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="finalvote.php">
                    <i class="fas fa-fw fa-check"></i>
                    <span>Final Vote</span>
                </a>
            </li>

            <li class="nav-item active">
                <a class="nav-link" href="registrationPage.php">
                    <i class="fas fa-fw fa-list"></i>
                    <span>Registration</span>
                </a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider d-none d-md-block">

            <!-- Sidebar Toggler (Sidebar) -->
            <div class="text-center d-none d-md-inline">
                <button class="rounded-circle border-0" id="sidebarToggle"></button>
            </div>
        </ul>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

                    <!-- Sidebar Toggle (Topbar) -->
                    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                        <i class="fa fa-bars"></i>
                    </button>

                    <!-- Topbar Navbar -->
                    <ul class="navbar-nav ml-auto">

                        <!-- Nav Item - User Information -->
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="mr-2 d-none d-lg-inline text-gray-600 small">
                                    <?php echo DB::query('SELECT nama FROM admin WHERE id=:id', array(':id'=>LoginAdmin::isLoggedIn()))[0]['nama'];?>
                                </span>
                                <img class="img-profile rounded-circle"
                                    src="img/undraw_profile.svg">
                            </a>
                            <!-- Dropdown - User Information -->
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                aria-labelledby="userDropdown">
                                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Logout
                                </a>
                            </div>
                        </li>
                    </ul>
                </nav>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Page Heading -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Registration Page</h1>
                        <a href="addRegistrationPage.php" class="btn btn-sm btn-primary shadow-sm">
                            <i class="fas fa-plus fa-sm text-white-50"></i> Tambah Kandidat
                        </a>
                    </div>
                    <span id="note" style="color:red;"> <?php echo $pemberitahuan;?></span>

                    <!-- Content Row -->
                    <div class="row">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>Nomor Paslon</th>
                                            <th>Nama Ketua</th>
                                            <th>Nama Wakil Ketua</th>
                                            <th>Gambar</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        foreach($data as $loop){
                                        ?>
                                        <tr>
                                            <td><?php echo $loop['idkandidat'];?></td>
                                            <td><?php echo $loop['ketua'];?></td>
                                            <td><?php echo $loop['wakil'];?></td>
                                            <td>
                                                <img id="myImg<?php echo $loop['idkandidat'];?>" src="../User/assets/<?php echo $loop['foto'];?>" alt="" style="width:150px" class="img-thumbnail" onclick="getimage('myImg<?php echo $loop['idkandidat'];?>','img<?php echo $loop['idkandidat'];?>','myModal<?php echo $loop['idkandidat'];?>');">

                                                <!-- The Modal -->
                                                <div id="myModal<?php echo $loop['idkandidat'];?>" class="modal">
                                                <span class="close" id="close<?php echo $loop['idkandidat'];?>" style="font-size: 80px; color: white;" onclick="modalclose('myModal<?php echo $loop['idkandidat'];?>');">&times;</span>
                                                <img class="modal-content" id="img<?php echo $loop['idkandidat'];?>">
                                                </div>
                                            </td>
                                            <td>
                                                <form action="" method="post" class="d-inline">
                                                    <button class="btn btn-danger" name="hapus" value="<?php echo $loop['idkandidat'];?>">
                                                        <i class="fa fa-trash fa-sm"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                   
                                        <?php
                                            }
                                        if(empty($data)) {
                                            echo '
                                            <tr>
                                                <td colspan="7" class="text-center">
                                                    Data Kosong
                                                </td>
                                            </tr>';
                                        }
                                        
                                        ?>
                                       
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy; PilihGeh 2021</span>
                    </div>
                </div>
            </footer>
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
                <div class="modal-footer">
                    <form action="" method="post">
                        <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                        <button class="btn btn-primary" name="logout">Logout</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.js"></script>

    <!-- Page level plugins -->
    <script src="vendor/chart.js/Chart.min.js"></script>

    <!-- Page level custom scripts -->
    <script src="js/demo/chart-area-demo.js"></script>
    <script src="js/demo/chart-pie-demo.js"></script>

</body>

</html>
    