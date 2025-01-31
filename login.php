<?php
session_start();
include 'koneksi.php';
if (isset($_POST['login'])) {
    $email = mysqli_real_escape_string($koneksi, $_POST['email']);
    $password = mysqli_real_escape_string($koneksi, $_POST['password']);

    $hashed_password = md5($password);

    $query = mysqli_query($koneksi, "SELECT * FROM user WHERE email='$email' AND password='$hashed_password' OR password='$password'");

    if (mysqli_num_rows($query) > 0) {
        $data = mysqli_fetch_array($query);

        $_SESSION['logged_in'] = true;
        $_SESSION['id_user'] = $data['id_user'];
        $_SESSION['username'] = $data['username'];
        $_SESSION['email'] = $data['email'];
        $_SESSION['role'] = $data['role'];
        $_SESSION['foto'] = $data['foto'];

        if ($data['role'] == 'admin') {
            echo '<div class="alert alert-success" style="position: relative;">
                    Login Admin berhasil!
                    <button class="close-btn" onclick="window.location.href=\'dashboard.php\'" style="position: absolute; top: 8px; right: 10px; background: none; border: none; font-size: 1.2rem; cursor: pointer;">&times;</button>
                  </div>';
        } elseif ($data['role'] == 'pegawai') {
            if ($data['status'] == 1) {
                echo '<div class="alert alert-success" style="position: relative;">
                        Login Pegawai berhasil!
                        <button class="close-btn" onclick="window.location.href=\'dashboard.php\'" style="position: absolute; top: 8px; right: 10px; background: none; border: none; font-size: 1.2rem; cursor: pointer;">&times;</button>
                      </div>';
            } else {
                echo '<div class="alert alert-info" style="position: relative;">
                        Akun anda belum diverifikasi! Mohon ditunggu yaa
                        <button class="close-btn" onclick="window.location.href=\'login.php\'" style="position: absolute; top: 8px; right: 10px; background: none; border: none; font-size: 1.2rem; cursor: pointer;">&times;</button>
                      </div>';
            }
        } elseif ($data['role'] == 'customer') {
            echo '<div class="alert alert-success" style="position: relative;">
                    Login sebagai customer berhasil!
                    <button class="close-btn" onclick="window.location.href=\'index.php\'" style="position: absolute; top: 8px; right: 10px; background: none; border: none; font-size: 1.2rem; cursor: pointer;">&times;</button>
                  </div>';
        }
    } else {
        $_SESSION['logged_in'] = false;
        echo '<div class="alert alert-warn" style="position: relative;">
                Email atau password anda salah!
                <button class="close-btn" onclick="window.location.href=\'login.php\'" style="position: absolute; top: 8px; right: 10px; background: none; border: none; font-size: 1.2rem; cursor: pointer;">&times;</button>
              </div>';
    }
}
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Lucart | Log in</title>

    <!-- Google Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="css/admin.css">

    <style>
        @import url('https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css');
        @import url(https://fonts.googleapis.com/css?family=Open+Sans:400,300);

        .alert {
            margin: 10px 0px;
            padding: 12px;
            border-radius: 5px;
            width: 95%;
            top: -14%;

            font-family: 'Poppins', sans-serif;
            font-size: .9rem;
            font-weight: 300;
            letter-spacing: 1px;
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

<body class="hold-transition login-page" style="background-image: url(img/bg-admin.png);">
    <div class="login-box">
        <!-- /.login-logo -->
        <div class="card card-outline card-primary" style="background-color: rgba(248, 245, 245); border-top-color: #776B5D;">
            <div class="card-header text-center">
                <a href="login.php" class="h1"><img src="img/logo2.png" alt="logo" style="width: 60px; height: 30px; margin-right: 10px;"><b>Lu</b>cart</a>
                <style>
                    img:hover,
                    a:hover {
                        color: #665A48;
                    }
                </style>
            </div>
            <div class="card-body">
                <p class="login-box-msg">Sign in to start your session</p>

                <form action="" method="post" id="contact-form">
                    <div class="input-group mb-3">
                        <input type="email" name="email" class="form-control" placeholder="Email">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-envelope"></span>
                            </div>
                        </div>
                    </div>
                    <div class="input-group mb-3">
                        <input type="password" name="password" class="form-control" placeholder="Password">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 mt-3 d-flex justify-content-center">
                            <button type="submit" id="form-submit" name="login" class="btn" style="background: #776B5D; color: white; font-weight: 500; width: 100px;">Login</button>
                            <style>
                                button:hover {
                                    background: #574f45;
                                }
                            </style>
                        </div>
                    </div>

                </form>
            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->
    </div>
    <!-- /.login-box -->

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