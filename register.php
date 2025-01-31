<?php
session_start();
include 'koneksi.php';
if (isset($_POST['submit'])) { // Untuk create
    $email = $_POST['email'];
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Untuk insert
    $cek_email = mysqli_query($koneksi, "select * from user where email='$email'");
    if (mysqli_num_rows($cek_email) > 0) {
        $_SESSION['pesan'] = "Email anda sudah terdaftar, silahkan gunakan email yang lain.";
        $_SESSION['alert_type'] = "warn";
    } else {
        $insert = mysqli_query($koneksi, "INSERT INTO user (email, username, password) VALUES ('$email', '$username', '$password')");

        if ($insert) {
            $_SESSION['pesan'] = "Berhasil memasukkan data!";
            $_SESSION['alert_type'] = "success";
            $_SESSION['redirect'] = "login.php";
        } else {
            $_SESSION['pesan'] = "Gagal memasukkan data!";
            $_SESSION['alert_type'] = "warn";
        }
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>AdminLucart | Registration</title>

    <!-- Google Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="css/admin.css">

    <style>
        @import url(https://fonts.googleapis.com/css?family=Open+Sans:400,300);

        .alert {
            position: absolute;
            /* Membuat alert mengambang */
            left: 48%;
            top: -15px;
            /* Agar alert berada di tengah */
            transform: translateX(-50%);
            /* Menyelaraskan posisi ke tengah */
            z-index: 999;
            /* Pastikan di atas elemen lain */
            width: 96%;
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

<body>
    <div class="hold-transition register-page" style="background: url(img/bg-admin.png);  position: relative; height: 100vh;">
        <?php
        if (isset($_SESSION['pesan'])) {
            $alertType = $_SESSION['alert_type'] === "success" ? "alert-success" : "alert-warn";

            echo '<div class="alert ' . $alertType . ' alert-dismissible fade show" role="alert" style="position: relative;">
                ' . $_SESSION['pesan'] . '
                <button class="close-btn" style="position: absolute; top: 8px; right: 10px; background: none; border: none; font-size: 1.2rem; cursor: pointer;">&times;</button>
             </div>';

            unset($_SESSION['pesan']);
            unset($_SESSION['alert_type']);

            if (isset($_SESSION['redirect'])) {
                echo '<script>
                document.querySelector(".close-btn").addEventListener("click", function() {
                    window.location.href = "' . $_SESSION['redirect'] . '";
                });
                </script>';
                unset($_SESSION['redirect']);
            }
        }
        ?>
        <div class="register-box">
            <div class="card card-outline card-primary" style="background-color: rgba(248, 245, 245); border-top-color: #776B5D;">
                <div class="card-header text-center">
                    <a href="register.php" class="h1"><b>Admin</b>Lucart</a>
                    <style>
                        a:hover {
                            color: #776B5D;
                        }
                    </style>
                </div>
                <div class="card-body">
                    <p class="login-box-msg">Register a new account</p>

                    <form action="" method="POST" class="mb-4">
                        <div class="input-group mb-3">
                            <input type="text" name="username" id="username" class="form-control" placeholder="Username">
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-user"></span>
                                </div>
                            </div>
                        </div>
                        <div class="input-group mb-3">
                            <input type="email" name="email" id="email" class="form-control" placeholder="Email">
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-envelope"></span>
                                </div>
                            </div>
                        </div>
                        <div class="input-group mb-3">
                            <input type="password" name="password" id="password" class="form-control" placeholder="Password">
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-lock"></span>
                                </div>
                            </div>
                        </div>
                        <div class="input-group mb-3">
                            <input type="password" class="form-control" placeholder="Retype password">
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-lock"></span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <!-- /.col -->
                            <div class="col-5 ml-auto">
                                <button type="submit" name="submit" class="btn btn-block" style="background: #776B5D; color: white; font-weight: 500;">Register</button>
                                <style>
                                    button:hover {
                                        background: #574f45;
                                    }
                                </style>
                            </div>
                            <!-- /.col -->
                        </div>
                    </form>


                    <a href="login.php" class="text-center" style="color: #776B5D;">I already have a account</a>
                </div>
                <!-- /.form-box -->
            </div><!-- /.card -->
        </div>
        <!-- /.register-box -->
    </div>

    <!-- jQuery -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <!-- Bootstrap 4 -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
    <!-- AdminLTE App -->
    <script src="js/admin.js"></script>

    <script>
        $('.alert').click(function() {
            $(this).fadeOut();
        });
    </script>
</body>

</html>