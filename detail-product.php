<?php
session_start();
include 'koneksi.php';

$host = "localhost";
$user = "root";
$pass = "";
$db = "lucart";

$koneksi = mysqli_connect($host, $user, $pass, $db);
if (!$koneksi) { //cek koneksi
    die("tidak bisa terkoneksi ke database");
}

$id_product = "";
$name_product = "";
$stok_product = "";
$harga_product = "";
$deskripsi_product = "";
$kategori_product = "";
$gambar_product = "";
$ekstensi = "";
$ekstensi1 = "";

if (isset($_GET['op'])) {
    $op = $_GET['op'];
} else {
    $op = "";
}

if ($op == 'delete') {
    $id_product = $_GET['id_product'];
    $sql1 = "DELETE FROM product WHERE id_product = '$id_product'";
    $q1 = mysqli_query($koneksi, $sql1);

    if ($q1) {
        $_SESSION['pesan'] = "Berhasil dihapus.";
        $_SESSION['alert_type'] = "success";
    } else {
        $_SESSION['pesan'] = "Gagal menghapus data.";
        $_SESSION['alert_type'] = "error";
    }

    // Redirect setelah delete agar session bisa muncul pada halaman
    header("Location: detail-product.php");
    exit();
}


if ($op == 'edit') {
    // Data untuk edit
    $id_product = $_GET['id_product'];
    $sql1 = "SELECT * FROM product WHERE id_product = '$id_product'";
    $q1 = mysqli_query($koneksi, $sql1);
    if ($r1 = mysqli_fetch_array($q1)) {
        $name_product = $r1['name_product'];
        $stok_product = $r1['stok_product'];
        $harga_product = $r1['harga_product'];
        $deskripsi_product = $r1['deskripsi_product'];
        $kategori_product = $r1['kategori_product'];
        $gambar_product = $r1['gambar_product'];
    }
    if ($id_product == '') {
        $_SESSION['pesan'] = "Data tidak ditemukan.";
        $_SESSION['alert_type'] = "warn";
    }
}

if (isset($_POST['simpan'])) {
    // Retrieve form data
    $id_product = isset($_POST['id_product']) ? $_POST['id_product'] : ""; // Check if ID exists
    $name_product = $_POST['name_product'];
    $stok_product = $_POST['stok_product'];
    $harga_product = $_POST['harga_product'];
    $deskripsi_product = $_POST['deskripsi_product'];
    $kategori_product = $_POST['kategori_product'];
    $gambar_product = $_FILES['gambar_product']['name']; // Get the image name

    // Determine the operation based on the presence of an ID
    $op = !empty($id_product) ? 'edit' : 'insert'; // Set operation mode

    // Check if an image is uploaded
    if (!empty($gambar_product)) {
        $allowed_extensions = ['png', 'jpg', 'jpeg'];
        $file_extension = strtolower(pathinfo($gambar_product, PATHINFO_EXTENSION));
        $file_tmp = $_FILES['gambar_product']['tmp_name'];

        // Validate the extension
        if (in_array($file_extension, $allowed_extensions)) {
            move_uploaded_file($file_tmp, 'image/' . $gambar_product);
        } else {
            $_SESSION['pesan'] = "Ekstensi tidak diperbolehkan.";
            $_SESSION['alert_type'] = "warn";
            header("Location: detail-product.php");
            exit();
        }
    } else {
        // If no new image is uploaded, retain the existing image from the database
        $sql1 = "SELECT gambar_product FROM product WHERE id_product = '$id_product'";
        $q1 = mysqli_query($koneksi, $sql1);
        $r1 = mysqli_fetch_array($q1);
        $gambar_product = $r1['gambar_product'];
    }

    // Check if all fields are filled
    if ($name_product && $stok_product && $harga_product && $deskripsi_product && $kategori_product && $gambar_product) {
        if ($op == 'edit') {
            // Ensure the ID exists for updating
            if ($id_product) {
                $sql1 = "UPDATE product SET 
                            name_product = '$name_product', 
                            stok_product = '$stok_product', 
                            harga_product = '$harga_product', 
                            gambar_product = '$gambar_product', 
                            deskripsi_product = '$deskripsi_product', 
                            kategori_product = '$kategori_product' 
                          WHERE id_product = '$id_product'";
                $q1 = mysqli_query($koneksi, $sql1);
                if ($q1) {
                    // Success alert
                    $_SESSION['pesan'] = "Data berhasil diupdate.";
                    $_SESSION['alert_type'] = "success";
                    $_SESSION['redirect'] = "detail-product.php";
                } else {
                    // Error alert with specific error message
                    $_SESSION['pesan'] = "Data gagal diupdate: " . mysqli_error($koneksi);
                    $_SESSION['alert_type'] = "warn";
                }
            } else {
                // Error alert if ID is missing
                $_SESSION['pesan'] = "ID produk tidak ditemukan.";
                $_SESSION['alert_type'] = "warn";
            }
        } else { // Insert operation
            $sql1 = "INSERT INTO product (name_product, stok_product, harga_product, gambar_product, deskripsi_product, kategori_product) 
                     VALUES ('$name_product', '$stok_product', '$harga_product', '$gambar_product', '$deskripsi_product', '$kategori_product')";
            $q1 = mysqli_query($koneksi, $sql1);
            if ($q1) {
                // Success alert for insert
                $_SESSION['pesan'] = "Berhasil memasukkan data baru.";
                $_SESSION['alert_type'] = "success";
                $_SESSION['redirect'] = "detail-product.php";
            } else {
                // Error alert for insert
                $_SESSION['pesan'] = "Gagal memasukkan data: " . mysqli_error($koneksi);
                $_SESSION['alert_type'] = "warn";
            }
        }
    } else {
        // Alert if not all fields are filled
        $_SESSION['pesan'] = "Silahkan masukkan semua data.";
        $_SESSION['alert_type'] = "warn";
    }
}

if (isset($_SESSION['pesan']) && isset($_SESSION['alert_type'])): ?>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const alertType = "<?php echo $_SESSION['alert_type']; ?>";
            const message = "<?php echo $_SESSION['pesan']; ?>";

            if (alertType === "success" || alertType === "warn") {
                Swal.fire({
                    icon: alertType === "success" ? "success" : "warning",
                    title: message,
                    showConfirmButton: false,
                    timer: 2000
                });
            } else {
                Swal.fire({
                    icon: "error",
                    title: message,
                    showConfirmButton: true
                });
            }

            // Clear the session messages after displaying them
            <?php unset($_SESSION['pesan']);
            unset($_SESSION['alert_type']); ?>
        });
    </script>
<?php endif; ?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>AdminLucart | Products</title>

    <!-- Google Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="css/admin-min.css">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        @import url(https://fonts.googleapis.com/css?family=Open+Sans:400,300);

        .alert {
            position: absolute;
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
                            <a href="detail-product.php" class="nav-link active" style="background-color: #5F7161; color: #ffffff;">
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
                            <h1>Product</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
                                <li class="breadcrumb-item active">Products</li>
                            </ol>
                        </div>
                    </div>
                </div><!-- /.container-fluid -->
            </section>


            <!-- Main content -->
            <section class="content">


                <!-- Default box -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title"> Data Products</h3>

                        <div class="card-tools">
                            <button type="button" class="btn btn-primary btn-flat btn-sm" data-toggle="modal" data-target="#modal-sm">
                                <i class="fas fa-plus"></i>
                                Tambah Data
                            </button>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <table class="table table-bordered projects">
                            <thead>
                                <tr class="text-center">
                                    <th style="width: 1%">
                                        No.
                                    </th>
                                    <th style="width: 20%">
                                        Nama Produk
                                    </th>
                                    <th style="width: 15%">
                                        Gambar
                                    </th>
                                    <th style="width: 10%">
                                        Stok
                                    </th>
                                    <th style="width: 10%">
                                        Harga
                                    </th>
                                    <th style="width: 10%">
                                        Kategori
                                    </th>
                                    <th style="width: 20%">
                                        Deskripsi
                                    </th>
                                    <th style="width: 20%">
                                        Aksi
                                    </th>

                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $sql2 = "select * from product order by id_product desc";
                                $q2 = mysqli_query($koneksi, $sql2);
                                $urut = 1;
                                while ($r2 = mysqli_fetch_array($q2)) {
                                    $id_product = $r2['id_product'];
                                    $name_product = $r2['name_product'];
                                    $stok_product = $r2['stok_product'];
                                    $harga_product = $r2['harga_product'];
                                    $deskripsi_product = $r2['deskripsi_product'];
                                    $kategori_product = $r2['kategori_product'];
                                    $gambar_product = $r2['gambar_product'];
                                ?>
                                    <tr>
                                        <td style="text-align: center;">
                                            <?php echo $urut++; ?>
                                        </td>
                                        <td style="text-align: center;">
                                            <?php echo $name_product ?>
                                        </td>
                                        <td style="text-align: center;">
                                            <img src="image/<?= $gambar_product ?>" height="100" width="100">
                                        </td>
                                        <td style="text-align: center;">
                                            <?php echo $stok_product ?>
                                        </td>
                                        <td style="text-align: center;">
                                            <?php echo $harga_product ?>
                                        </td>
                                        <td style="text-align: center;">
                                            <?php echo $kategori_product ?>
                                        </td>
                                        <td style="text-align: center;">
                                            <?php echo $deskripsi_product ?>
                                        </td>
                                        <td class="project-actions" style="text-align: center;">
                                            <a href="#" class="btn btn-info btn-sm" data-toggle="modal" data-target="#modal-edit"
                                                onclick="setEditData('<?php echo addslashes($id_product); ?>', '<?php echo addslashes($name_product); ?>', '<?php echo addslashes($stok_product); ?>', '<?php echo addslashes($harga_product); ?>', '<?php echo addslashes($deskripsi_product); ?>', '<?php echo addslashes($kategori_product); ?>', '<?php echo addslashes($gambar_product); ?>')"> <!-- addcslashes untuk menangani karakter spesial. -->
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a class="btn btn-danger btn-sm delete-btn" href="detail-product.php?op=delete&id_product=<?php echo $id_product ?>">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                        </td>
                                    </tr>

                                <?php
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->

            </section>
            <!-- /.content -->
        </div>
        <!-- /.content-wrapper -->

        <!-- TAMBAH PRODUCT -->
        <div class="modal fade" id="modal-sm">
            <div class="modal-dialog modal-md">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Tambah Data</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form action="" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="id_product">
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="name_product">Nama Produk</label>
                                <input type="text" class="form-control" id="name_product" name="name_product">
                            </div>
                            <div class="form-group">
                                <label for="deskripsi_product">Deskripsi</label>
                                <textarea class="form-control" id="deskripsi_product" name="deskripsi_product" rows="4"></textarea>
                            </div>
                            <div class="form-group">
                                <label for="harga_product">Harga Produk</label>
                                <input type="number" class="form-control" id="harga_product" name="harga_product">
                            </div>
                            <div class="form-group">
                                <label for="stok_product">Stok Produk</label>
                                <input type="number" class="form-control" id="stok_product" name="stok_product">
                            </div>
                            <div class="form-group">
                                <label for="kategori_product">Kategori</label>
                                <select class="form-control custom-select" id="kategori_product" name="kategori_product">
                                    <option selected disabled>Pilih salah satu</option>
                                    <option>T-shirt Custom</option>
                                    <option>T-shirt Kartun</option>
                                    <option>T-shirt Club</option>

                                </select>
                            </div>
                            <div class="form-group">
                                <label for="gambar_product">Gambar Produk</label>
                                <input type="file" class="form-control" id="gambar_product" name="gambar_product">
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default btn-flat" data-dismiss="modal">Batal</button>
                                <button type="submit" name="simpan" class="btn btn-primary btn-flat">Simpan</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- EDIT PRODUCT -->
        <div class="modal fade" id="modal-edit">
            <div class="modal-dialog modal-md">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Data</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form action="" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="id_product" id="id-product"> <!-- ID produk -->
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="name-product">Nama Produk</label>
                                <input type="text" class="form-control" id="name-product" name="name_product" value="<?php echo $name_product ?>">
                            </div>
                            <div class="form-group">
                                <label for="deskripsi-product">Deskripsi</label>
                                <textarea class="form-control" id="deskripsi-product" name="deskripsi_product" value="<?php echo $deskripsi_product ?>" rows="4"></textarea>
                            </div>
                            <div class="form-group">
                                <label for="harga-product">Harga Produk</label>
                                <input type="number" class="form-control" id="harga-product" name="harga_product" value="<?php echo $harga_product ?>">
                            </div>
                            <div class="form-group">
                                <label for="stok-product">Stok Produk</label>
                                <input type="number" class="form-control" id="stok-product" name="stok_product" value="<?php echo $stok_product ?>">
                            </div>
                            <div class="form-group">
                                <label for="kategori-product">Kategori</label>
                                <select class="form-control custom-select" id="kategori-product" name="kategori_product" value="<?php echo $kategori_product ?>">
                                    <option selected disabled>Pilih salah satu</option>
                                    <option>T-shirt Custom</option>
                                    <option>T-shirt Kartun</option>
                                    <option>T-shirt Club</option>

                                </select>
                            </div>
                            <div class="form-group">
                                <label for="gambar_product">Gambar Produk</label>
                                <input type="file" class="form-control" id="gambar_product" name="gambar_product" value="<?php echo $gambar_product ?>">
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default btn-flat" data-dismiss="modal">Batal</button>
                                <button type="submit" name="simpan" class="btn btn-primary btn-flat">Simpan</button>
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


    <script>
        function setEditData(idProduct, namaProduct, stokProduct, hargaProduct, deskripsiProduct, kategoriProduct) {
            // Set the values of modal fields based on the data passed
            document.getElementById('id-product').value = idProduct;
            document.getElementById('name-product').value = namaProduct;
            document.getElementById('stok-product').value = stokProduct;
            document.getElementById('harga-product').value = hargaProduct;
            document.getElementById('deskripsi-product').value = deskripsiProduct;

            // Set the selected value for the dropdown
            document.getElementById('kategori-product').value = kategoriProduct;

            // Gambar produk tidak bisa langsung diisi menggunakan file input. Anda bisa menggunakan
            // atribut lain untuk menampilkan nama file jika dibutuhkan.
        }
    </script>

    <!-- jQuery -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <!-- Bootstrap 4 -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>

    <!-- AdminLTE App -->
    <script src="js/admin.js"></script>
    <script src="js/admin-page.js"></script>
    <script src="js/demo.js"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Find all delete buttons and add confirmation event
            document.querySelectorAll(".delete-btn").forEach(function(button) {
                button.addEventListener("click", function(event) {
                    event.preventDefault();
                    const deleteUrl = button.getAttribute("href");

                    Swal.fire({
                        title: "Yakin ingin menghapus?",
                        text: "Data yang dihapus tidak bisa dikembalikan!",
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#3085d6",
                        cancelButtonColor: "#d33",
                        confirmButtonText: "Ya, hapus!"
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Redirect to delete URL if confirmed
                            window.location.href = deleteUrl;
                        }
                    });
                });
            });
        });
    </script>

</body>

</html>