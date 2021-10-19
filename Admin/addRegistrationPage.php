<?php
    include('../class/Login.php');

    if (!LoginAdmin::isLoggedIn()){
        header("location: ../index.php");
    }

    if (isset($_POST['logout'])) {              
        
        DB::query('DELETE FROM logintokenadmin WHERE idadmin=:idadmin', array(':idadmin'=>LoginAdmin::isLoggedIn()));
        header("location: ../index.php");
    }

    $pemberitahuan = '';

    if(isset($_POST['submit'])){
        $nomor = $_POST['nomor'];
        $ketua = $_POST['name'];
        $wakil = $_POST['wakilname'];

        $filename = $_FILES['gambar']['name'];
        $fileTMP = $_FILES['gambar']['tmp_name'];
        $filesize = $_FILES['gambar']['size'];
        $fileerror = $_FILES['gambar']['error'];
        $filetype = $_FILES['gambar']['type'];

        $fileExt = explode('.', $filename);
        $fileExtNew = strtolower(end($fileExt));

        $fileallow = array('jpg', 'jpeg', 'png');

        if (in_array($fileExtNew, $fileallow)) {
            if ($fileerror === 0) {
                if ($filesize <  52428800) {
                    if(empty(DB::query('SELECT idkandidat FROM kandidat WHERE idkandidat=:idkandidat', array(':idkandidat'=>$nomor)))){
                        if(is_numeric($nomor)){
                            $filenamenew = uniqid('', true) . "." . $fileExtNew;
                            DB::query('INSERT INTO kandidat VALUES (:idkandidat, :ketua, :wakil, :foto)', array(':idkandidat'=>$nomor, ':ketua' => $ketua, ':wakil' => $wakil, ':foto' => $filenamenew));
                            $filedestination = '../User/assets/' . $filenamenew;
                            move_uploaded_file($fileTMP, $filedestination);
                            
                            $pemberitahuan = 'Berhasil Menambahkan Kandidat!';
                        } else {
                            $pemberitahuan = 'Nomor Harus Berupa Angka!';
                        }
                    } else {
                        $pemberitahuan = 'Nomor Kandidat Sudah Terpakai';
                    }
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

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>PilihGeh - TambahVote</title>

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
                        <h1 class="h3 mb-0 text-gray-800">Tambah Daftar Voters</h1>
                    </div>
                        <span style="color: red;"><?php echo $pemberitahuan; ?></span>

                    <!-- Content Row -->
                    <div class="card shadow">
                        <div class="card-body">
                            <form action="" method="post" enctype="multipart/form-data">
                                <div class="form-group">
                                    <label for="name">Nomor Paslon</label>
                                    <input type="text" class="form-control" placeholder="Nomor Paslon" name="nomor" required>
                                </div>
                                <div class="form-group">
                                    <label for="name">Nama Ketua</label>
                                    <input type="text" class="form-control" placeholder="Nama Ketua" name="name" required>
                                </div>
                                <div class="form-group">
                                    <label for="name">Nama Wakil Ketua</label>
                                    <input type="text" class="form-control" placeholder="Nama Wakil Ketua" name="wakilname" required>
                                </div>
                                <div class="form-group">
                                    <label for="gambar">Gambar Paslon</label>
                                    <input type="file" class="form-control" placeholder="Gambar Paslon" name="gambar" required>
                                </div>
                                
                                <button type="submit" name="submit" class="btn btn-purple btn-block">
                                    Simpan
                                </button>
                                
                            </form>
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
    <script src="js/sb-admin-2.min.js"></script>

    <!-- Page level plugins -->
    <script src="vendor/chart.js/Chart.min.js"></script>

    <!-- Page level custom scripts -->
    <script src="js/demo/chart-area-demo.js"></script>
    <script src="js/demo/chart-pie-demo.js"></script>

</body>

</html>