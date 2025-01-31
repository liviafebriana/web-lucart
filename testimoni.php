<?php
session_start();
include 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id_tq']) && isset($_POST['action'])) {
    $id_tq = $_POST['id_tq'];
    $action = $_POST['action'];

    if ($action == 'show') {
        $update_query = "UPDATE tq SET status = 1 where id_tq = '$id_tq'";

        if (mysqli_query($koneksi, $update_query)) {
            $_SESSION['pesan'] = "Testimoni berhasil ditampilkan!";
            $_SESSION['alert_type'] = "success";
            $_SESSION['redirect'] = "testimoni.php";
        } else {
            $_SESSION['pesan'] = "Error updating status: " . mysqli_error($koneksi);
            $_SESSION['alert_type'] = "warn";
        }
    } elseif ($action == 'hide') {
        $update_query = "UPDATE tq SET status = 0 where id_tq = '$id_tq'";

        if (mysqli_query($koneksi, $update_query)) {
            $_SESSION['pesan'] = "Testimoni berhasil disembunyikan";
            $_SESSION['alert_type'] = "success";
            $_SESSION['redirect'] = "testimoni.php";
        } else {
            $_SESSION['pesan'] = "Error updating status: " . mysqli_error($koneksi);
            $_SESSION['alert_type'] = "warn";
        }
    }
    echo "<script>window.location.href = '" . $_SERVER['PHP_SELF'] . "';</script>";
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>AdminLucart | Testimoni</title>

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

    <style>
        @import url(https://fonts.googleapis.com/css?family=Open+Sans:400,300);

        .card .card-body .btn-mute {
            color: #fff;
            background-color: #8D493A;
        }

        .alert {
            position: absolute;
            display: block !important;
            /* Membuat alert mengambang */
            left: 50%;
            top: -2px;
            /* Agar alert berada di tengah */
            transform: translateX(-50%);
            /* Menyelaraskan posisi ke tengah */
            z-index: 999;
            /* Pastikan di atas elemen lain */
            width: 100%;
            /* Sesuaikan ukuran alert */
            padding: 15px;
            font-family: 'Poppins', sans-serif;
            font-size: 0.9rem;
            border-radius: 5px;
            opacity: 0.95;
            background-color: rgba(255, 255, 255, 0.8);
            /* Tambahkan background dengan sedikit transparansi */
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            /* Memberikan efek bayangan */
        }

        .close-btn {
            position: absolute;
            top: 8px;
            right: 10px;
            background: none;
            border: none;
            font-size: 1.2rem;
            cursor: pointer;
        }

        .alert:hover {
            cursor: pointer;
        }

        .alert:before {
            padding-right: 12px;
        }

        .alert:after {
            content: '';
            font-family: 'FontAwesome';
            float: right;
            padding: 3px;

            &:hover {
                cursor: pointer;
            }
        }

        .alert-info {
            color: #00529B;
            background-color: #BDE5F8;
            border: 1px solid darken(#BDE5F8, 15%);
        }

        .alert-info:before {
            content: '\f05a';
            font-family: 'FontAwesome';
        }

        .alert-warn {
            color: #9F6000;
            background-color: #FEEFB3;
            border: 1px solid darken(#FEEFB3, 15%);
        }

        .alert-warn:before {
            content: '\f071';
            font-family: 'FontAwesome';
        }

        .alert-error {
            color: #D8000C;
            background-color: #FFBABA;
            border: 1px solid darken(#FFBABA, 15%);
        }

        .alert-error:before {
            content: '\f057';
            font-family: 'FontAwesome';
        }

        .alert-success {
            color: #4F8A10;
            background-color: #DFF2BF;
            border: 1px solid darken(#DFF2BF, 15%);
        }

        .alert-success:before {
            content: '\f058';
            font-family: 'FontAwesome';
        }
    </style>
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
                            <a href="testimoni.php" class="nav-link active" style="background-color: #5F7161; color: #ffffff;">
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
                            <a href="customer.php" class="nav-link">
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
                            <h1>Testimoni</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
                                <li class="breadcrumb-item active">Testimoni</li>
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
                                    <h3 class="card-title">Testimoni yang Baru Masuk</h3>
                                </div>
                                <div class="card-body">
                                    <table id="example2" class="table table-bordered table-hover text-center">
                                        <thead>
                                            <tr class="text-center">
                                                <th>No.</th>
                                                <th>Foto</th>
                                                <th>Username</th>
                                                <th>Email</th>
                                                <th>Pekerjaan</th>
                                                <th>Rating</th>
                                                <th class="col-4">Pesan</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <?php
                                        $sql2 = "SELECT tq.id_tq, tq.pesan, tq.jenis, tq.pekerjaan, tq.rating, user.username, user.email, user.foto 
                                        FROM tq JOIN user ON tq.id_user = user.id_user WHERE tq.jenis = 'testimoni' AND tq.status = 0 ORDER BY tq.id_tq DESC";
                                        $q2 = mysqli_query($koneksi, $sql2);
                                        $urut = 1;
                                        ?>
                                        <tbody>
                                            <?php while ($row = mysqli_fetch_assoc($q2)): ?>
                                                <tr>
                                                    <td style="text-align:center; vertical-align:middle;"><?php echo $urut++; ?></td>
                                                    <td style="text-align:center; vertical-align:middle;"><img src="<?php echo $row['foto']; ?>" alt="<?php echo $row['foto']; ?>" style="width: 70px; height: auto; border-radius: 50%;"></td>
                                                    <td style="text-align:center; vertical-align:middle;"><?php echo $row['username']; ?></td>
                                                    <td style="text-align:center; vertical-align:middle;"><?php echo $row['email']; ?></td>
                                                    <td style="text-align:center; vertical-align:middle;"><?php echo $row['pekerjaan'] ?></td>
                                                    <td style="text-align:center; vertical-align:middle;"><?php echo $row['rating'] ?></td>
                                                    <td class="text-start"><?php echo $row['pesan'] ?></td>
                                                    <td style="text-align:center; vertical-align:middle;">
                                                        <form action="" method="post" class="show-testimoni-form">
                                                            <input type="hidden" name="id_tq" value="<?php echo $row['id_tq']; ?>">
                                                            <input type="hidden" name="action" value="show">
                                                            <button type="button" class="btn btn-info btn-sm show-testimoni-btn">
                                                                <i class="fa-solid fa-eye"></i>
                                                            </button>
                                                        </form>
                                                    </td>
                                                </tr>
                                            <?php endwhile; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- Tambahkan library SweetAlert -->
                            <script>
                                document.querySelectorAll('.show-testimoni-btn').forEach(button => {
                                    button.addEventListener('click', function() {
                                        const form = this.closest('.show-testimoni-form');
                                        Swal.fire({
                                            title: "Anda yakin menampilkan testimoni ini?",
                                            text: "Testimoni akan ditampilkan dan dapat dilihat oleh publik!",
                                            icon: "warning",
                                            showCancelButton: true,
                                            confirmButtonColor: "#3085d6",
                                            cancelButtonColor: "#d33",
                                            confirmButtonText: "Yes, Tampilkan!"
                                        }).then((result) => {
                                            if (result.isConfirmed) {
                                                // Menampilkan SweetAlert konfirmasi ditampilkan
                                                Swal.fire({
                                                    title: "Tampil!",
                                                    text: "Testimoni telah berhasil ditampilkan.",
                                                    icon: "success",
                                                    timer: 1500, // Durasi tampil 1.5 detik
                                                    showConfirmButton: false
                                                });

                                                // Delay sebelum form disubmit
                                                setTimeout(() => {
                                                    form.submit();
                                                }, 1500); // Tunggu selama 1.5 detik
                                            }
                                        });
                                    });
                                });
                            </script>



                            <!-- DataTable with default features -->
                            <div class="card">
                                <div class="card-header" style="background-color: #B7CADB;">
                                    <h3 class="card-title">Testimoni yang Tampil</h3>
                                </div>
                                <div class="card-body">
                                    <table id="example2" class="table table-bordered table-hover text-center">
                                        <thead>
                                            <tr class="text-center">
                                                <th>No.</th>
                                                <th>Foto</th>
                                                <th>Username</th>
                                                <th>Email</th>
                                                <th>Pekerjaan</th>
                                                <th>Rating</th>
                                                <th class="col-4">Pesan</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <?php
                                        $sql2 = "SELECT tq.id_tq, tq.pesan, tq.jenis, tq.pekerjaan, tq.rating, user.username, user.email, user.foto 
                                        FROM tq JOIN user ON tq.id_user = user.id_user WHERE tq.jenis = 'testimoni' AND tq.status = 1 ORDER BY tq.id_tq DESC";
                                        $q2 = mysqli_query($koneksi, $sql2);
                                        $urut = 1;
                                        ?>
                                        <tbody>
                                            <?php while ($row = mysqli_fetch_assoc($q2)): ?>
                                                <tr>
                                                    <td style="text-align:center; vertical-align:middle;"><?php echo $urut++; ?></td>
                                                    <td style="text-align:center; vertical-align:middle;"><img src="<?php echo $row['foto']; ?>" alt="<?php echo $row['foto']; ?>" style="width: 70px; height: auto; border-radius: 50%;"></td>
                                                    <td style="text-align:center; vertical-align:middle;"><?php echo $row['username']; ?></td>
                                                    <td style="text-align:center; vertical-align:middle;"><?php echo $row['email']; ?></td>
                                                    <td style="text-align:center; vertical-align:middle;"><?php echo $row['pekerjaan'] ?></td>
                                                    <td style="text-align:center; vertical-align:middle;"><?php echo $row['rating'] ?></td>
                                                    <td class="text-start"><?php echo $row['pesan'] ?></td>
                                                    <td style="text-align:center; vertical-align:middle;">
                                                        <form action="" method="post" class="hide-testimoni-form">
                                                            <input type="hidden" name="id_tq" value="<?php echo $row['id_tq']; ?>">
                                                            <input type="hidden" name="action" value="hide">
                                                            <button type="button" class="btn btn-mute btn-sm hide-testimoni-btn">
                                                                <i class="fa-solid fa-eye-slash"></i>
                                                            </button>
                                                        </form>
                                                    </td>
                                                </tr>
                                            <?php endwhile; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- Tambahkan library SweetAlert -->
                            <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

                            <script>
                                document.querySelectorAll('.hide-testimoni-btn').forEach(button => {
                                    button.addEventListener('click', function() {
                                        const form = this.closest('.hide-testimoni-form');
                                        Swal.fire({
                                            title: "Anda yakin menyembunyikan testimoni ini?",
                                            text: "Testimoni akan disembunyikan dan tidak dapat dilihat oleh publik!",
                                            icon: "warning",
                                            showCancelButton: true,
                                            confirmButtonColor: "#3085d6",
                                            cancelButtonColor: "#d33",
                                            confirmButtonText: "Ya, Sembunyikan!"
                                        }).then((result) => {
                                            if (result.isConfirmed) {
                                                // Menampilkan SweetAlert konfirmasi disembunyikan
                                                Swal.fire({
                                                    title: "Tersembunyi!",
                                                    text: "Testimoni telah berhasil disembunyikan.",
                                                    icon: "success",
                                                    timer: 1500, // Durasi tampil 1.5 detik
                                                    showConfirmButton: false
                                                });

                                                // Delay sebelum form disubmit
                                                setTimeout(() => {
                                                    form.submit();
                                                }, 1500); // Tunggu selama 1.5 detik
                                            }
                                        });
                                    });
                                });
                            </script>
                        </div>
                    </div>
                </div>
            </section>

            <!-- /.content -->
        </div>
        <!-- /.content-wrapper -->

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
    <!-- Sweet Alert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- AdminLTE App -->
    <script src="js/admin.js"></script>
    <script src="js/admin-page.js"></script>
    <script src="js/demo.js"></script>


    <script>
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