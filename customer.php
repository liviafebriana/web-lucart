<?php
session_start();
include 'koneksi.php';

// Fungsi untuk menghasilkan password acak
function generateRandomPassword($length = 7)
{
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomPassword = '';
    for ($i = 0; $i < $length; $i++) {
        $randomPassword .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomPassword;
}

$password = "";

if (isset($_GET['op'])) {
    $op = $_GET['op'];
} else {
    $op = "";
}

if ($op == 'edit') {
    $id_user = $_GET['id_user'];
    $sql1 = "SELECT * FROM user WHERE id_user = '$id_user'";
    $q1 = mysqli_query($koneksi, $sql1);
    if ($r1 = mysqli_fetch_array($q1)) {
        $password = $r1['password'];
    }
    if ($id_user == '') {
        $error = "Data tidak ditemukan";
    }
}

if (isset($_POST['simpan'])) {
    $id_user = isset($_POST['id_user']) ? $_POST['id_user'] : "";
    $password = generateRandomPassword();
    $op = !empty($id_user) ? 'edit' : 'insert';

    if ($password) {
        if ($op == 'edit' && $id_user) {
            $sql1 = "UPDATE user SET password = '$password' WHERE id_user = '$id_user'";
            $q1 = mysqli_query($koneksi, $sql1);

            if ($q1) {
                echo '<script>
                    document.addEventListener("DOMContentLoaded", function() {
                        Swal.fire({
                            title: "Success!",
                            text: "Data berhasil diupdate dengan password baru: ' . $password . '",
                            icon: "success",
                            confirmButtonText: "Okay"
                        }).then(() => {
                            window.location.href = "customer.php";
                        });
                    });
                    </script>';
            } else {
                echo '<script>
                    document.addEventListener("DOMContentLoaded", function() {
                        Swal.fire({
                            title: "Error!",
                            text: "Data gagal diupdate: ' . mysqli_error($koneksi) . '",
                            icon: "error",
                            confirmButtonText: "Okay"
                        });
                    });
                      </script>';
            }
        } else {
            echo '<script>
                document.addEventListener("DOMContentLoaded", function() {
                    Swal.fire({
                        title: "Error!",
                        text: "ID produk tidak ditemukan.",
                        icon: "error",
                        confirmButtonText: "Okay"
                    });
                });
                  </script>';
        }
    } else {
        echo '<script>
            document.addEventListener("DOMContentLoaded", function() {
                Swal.fire({
                    title: "Warning!",
                    text: "Silahkan masukkan semua data",
                    icon: "warning",
                    confirmButtonText: "Okay"
                });
            });
              </script>';
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>AdminLucart | Customer</title>

    <!-- Google Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="css/admin-min.css">
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    

</head>

<body class="hold-transition sidebar-mini">
    <!-- Site wrapper -->
    <div class="wrapper">
        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand navbar-white navbar-light">
            <!-- Left navbar links -->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
                </li>
                <li class="nav-item d-none d-sm-inline-block">
                    <a href="dashboard.php" class="nav-link">Home</a>
                </li>
            </ul>

            <!-- Right navbar links -->
            <ul class="navbar-nav ml-auto">

                <li class="nav-item">
                    <a class="nav-link" data-widget="fullscreen" href="#" role="button">
                        <i class="fas fa-expand-arrows-alt"></i>
                    </a>
                </li>

                <li class="nav-item">
                    <button class="btn btn-secondary btn-sm logout-button mt-1" id="logoutButton" onclick="confirmLogout()">
                        <i class="fa-solid fa-arrow-right-from-bracket"></i> Logout
                    </button>
                </li>
            </ul>
        </nav>
        <!-- /.navbar -->

        <script>
            // SweetAlert confirmation for Logout
            function confirmLogout() {
                Swal.fire({
                    title: 'Anda yakin untuk log out?',
                    text: "Anda akan log out dari akun ini",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, log out'
                }).then((result) => {
                    if (result.isConfirmed) {
                        location.href = 'login.php';
                    }
                });
            }
        </script>

        <!-- Main Sidebar Container -->
        <aside class="main-sidebar sidebar-dark-primary elevation-4" style="background-color: #2A2828;">
            <!-- Brand Logo -->
            <a href="dashboard.php" class="brand-link">
                <img src="img/logoo.png" alt="Logo" class="brand-image">
                <span class="brand-text">LU<span style="color: #597445; font-weight:600;">CART</span></span>
            </a>

            <!-- Sidebar -->
            <div class="sidebar">
                <!-- Sidebar user (optional) -->
                <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                    <div class="image">
                        <img src="img/prof-admin.jpg" class="img-circle elevation-2" alt="User Image">
                    </div>
                    <div class="info">
                        <a href="#" class="d-block">
                            <?php
                            if ($_SESSION['role'] == 'admin') {
                                echo "Admin";
                            } elseif ($_SESSION['role'] == 'pegawai') {
                                echo $_SESSION['username'];
                            }
                            ?>
                        </a>
                    </div>
                </div>

                <!-- SidebarSearch Form -->
                <div class="form-inline">
                    <div class="input-group" data-widget="sidebar-search">
                        <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
                        <div class="input-group-append">
                            <button class="btn btn-sidebar">
                                <i class="fas fa-search fa-fw"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Sidebar Menu -->
                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                        <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
               <li class="nav-item">
                            <a href="dashboard.php" class="nav-link">
                                <i class="nav-icon fa-solid fa-house"></i>
                                <p>
                                    Dashboard
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="detail-product.php" class="nav-link">
                                <i class="nav-icon fa-solid fa-box"></i>
                                <p>
                                    Products
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="pegawai.php" class="nav-link">
                                <i class="nav-icon fas fa-regular fa-id-card"></i>
                                <p>
                                    Pegawai
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="testimoni.php" class="nav-link">
                                <i class="nav-icon fa-solid fa-comments"></i>
                                <p>
                                    Testimoni
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="question.php" class="nav-link">
                                <i class="nav-icon fa-solid fa-circle-question"></i>
                                <p>
                                    Question
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="customer.php" class="nav-link active" style="background-color: #5F7161; color: #ffffff;">
                                <i class="nav-icon fa-solid fa-users"></i>
                                <p>
                                    Customer
                                </p>
                            </a>
                        </li>
                    </ul>
                </nav>
                <!-- /.sidebar-menu -->
            </div>
            <!-- /.sidebar -->
        </aside>


        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1>Customer</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
                                <li class="breadcrumb-item active">Customer</li>
                            </ol>
                        </div>
                    </div>
                </div><!-- /.container-fluid -->
            </section>


            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12">
                            <!-- DataTable with hover style -->
                            <div class="card">
                                <div class="card-header" style="background-color: #B7CADB;">
                                    <h3 class="card-title">Data Customer</h3>
                                </div>
                                <div class="card-body">
                                    <table id="example2" class="table table-bordered table-hover text-center">
                                        <thead>

                                            <tr>
                                                <th>No.</th>
                                                <th>Foto</th>
                                                <th>Username</th>
                                                <th>Email</th>
                                                <th>Password</th>
                                                <th>Aksi</th>
                                            </tr>

                                        </thead>
                                        <tbody>
                                            <?php
                                            $sql2 = "SELECT * FROM user WHERE status = 0 AND role='customer' ORDER BY id_user DESC";
                                            $q2 = mysqli_query($koneksi, $sql2);
                                            $urut = 1;
                                            while ($r2 = mysqli_fetch_array($q2)) {
                                                $id_user = $r2['id_user'];
                                                $foto = $r2['foto'];
                                                $username = $r2['username'];
                                                $email = $r2['email'];
                                                $password = $r2['password'];
                                                $status = $r2['status'];
                                            ?>
                                                <tr>
                                                    <td style="text-align:center; vertical-align:middle;"><?php echo $urut++; ?></td>
                                                    <td style="text-align:center; vertical-align:middle;"><img src="<?php echo $r2['foto']; ?>" alt="<?php echo $r2['foto']; ?>" style="width: 70px; height: auto; border-radius: 50%;"></td>
                                                    <td style="text-align:center; vertical-align:middle;"><?php echo $r2['username']; ?></td>
                                                    <td style="text-align:center; vertical-align:middle;"><?php echo $r2['email']; ?></td>
                                                    <td style="text-align:center; vertical-align:middle;"><?php echo $r2['password']; ?></td>
                                                    <td style="text-align:center; vertical-align:middle;">
                                                        <a href="#" class="btn btn-info btn-sm" data-toggle="modal" data-target="#modal-edit"
                                                            onclick="setEditData('<?php echo addslashes($id_user); ?>', '<?php echo addslashes($password); ?>')"> <!-- addcslashes untuk menangani karakter spesial. -->
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                            <?php
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- /.content -->
        </div>
        <!-- /.content-wrapper -->

        <!-- EDIT USER -->
        <div class="modal fade" id="modal-edit">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Data</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form action="" method="POST" enctype="multipart/form-data" id="resetForm">
                        <input type="hidden" name="id_user" id="id-user"> <!-- ID produk -->
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="password">Password</label>
                                <input type="text" class="form-control" id="password" name="password" value="<?php echo $password ?>">
                            </div>
                            <div class="modal-footer">
                                <button type="submit" name="simpan" class="btn btn-primary btn-flat">Reset</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <footer class="main-footer text-center">
            <strong>Copyright &copy; 2024 <a href="https://adminlte.io">AdminLucart</a>.</strong>
            All rights reserved.
        </footer>

        <!-- Control Sidebar -->
        <aside class="control-sidebar control-sidebar-dark">
            <!-- Control sidebar content goes here -->
        </aside>
        <!-- /.control-sidebar -->
    </div>
    <!-- ./wrapper -->


    <!-- jQuery -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <!-- Bootstrap 4 -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <!-- AdminLTE App -->
    <script src="js/admin.js"></script>
    <script src="js/admin-page.js"></script>
    <script src="js/demo.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>



    <script>
        function setEditData(idUser, password) {
            // Set the values of modal fields based on the data passed
            document.getElementById('id-user').value = idUser;
            document.getElementById('password').value = password;
        }

        $(document).ready(function() {
            $('#example1, #example2').DataTable({
                "paging": true, // Enable pagination
                //"lengthMenu": [10], // Display only 10 records per page
                "searching": true, // Enable searching
                "ordering": true, // Enable sorting
                "info": true, // Show information
                "language": {
                    "paginate": {
                        "previous": "Previous",
                        "next": "Next"
                    }
                }
            });
        });
    </script>

</body>

</html>