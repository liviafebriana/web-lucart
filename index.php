<?php
session_start();
include 'koneksi.php';

$isLoggedIn = isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true;

if (isset($_SESSION['id_user'])) {
    $id = $_SESSION['id_user'];
} else {
    $id_user = null;
}


if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $success_message = '';
    $error_message = '';

    // Upload and change profile picture
    if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] == 0) {
        $userId = $_SESSION['id_user'];
        $target_dir = "image/";
        $target_file = $target_dir . basename($_FILES["profile_picture"]["name"]);
        $uploadOk = 1;

        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // File type validation
        if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg") {
            echo '<script>
                    document.addEventListener("DOMContentLoaded", function() {
                        Swal.fire({
                            icon: "error",
                            title: "Jenis File tidak valid",
                            text: "Hanya JPG, JPEG, & PNG yang diperbolehkan.",
                            confirmButtonText: "OK"
                        });
                    });
                  </script>';
            $uploadOk = 0;
        }

        if ($uploadOk == 1) {
            // File upload
            if (move_uploaded_file($_FILES["profile_picture"]["tmp_name"], $target_file)) {
                // Update database
                $update_query = "UPDATE user SET foto='$target_file' WHERE id_user='$userId'";
                if (mysqli_query($koneksi, $update_query)) {
                    $_SESSION['foto'] = $target_file;
                    echo '<script>
                            document.addEventListener("DOMContentLoaded", function() {
                                Swal.fire({
                                    icon: "success",
                                    title: "Success",
                                    text: "Foto profil berhasil diperbarui.",
                                    confirmButtonText: "OK"
                                }).then(() => {
                                    window.location.href = "index.php";
                                });
                            });
                          </script>';
                } else {
                    echo '<script>
                            document.addEventListener("DOMContentLoaded", function() {
                                Swal.fire({
                                    icon: "error",
                                    title: "Database Error",
                                    text: "Gagal memperbarui database: ' . mysqli_error($koneksi) . '",
                                    confirmButtonText: "OK"
                                });
                            });
                          </script>';
                }
            } else {
                echo '<script>
                        document.addEventListener("DOMContentLoaded", function() {
                            Swal.fire({
                                icon: "error",
                                title: "Upload Error",
                                text: "Gagal meng-upload file.",
                                confirmButtonText: "OK"
                            });
                        });
                      </script>';
            }
        }
    }

    // Message submission
    if (isset($_POST['simpan']) && isset($_POST['pesan']) && isset($_POST['jenis'])) {
        $pesan = $_POST['pesan'];
        $jenis = $_POST['jenis'];
        $pekerjaan = $_POST['pekerjaan'];
        $rating = isset($_POST['rating']) ? $_POST['rating'] : 0;
        $date = date('Y-m-d');
        $id_user = $_SESSION['id_user'];

        // Validate message length
        if (strlen($pesan) > 200) {
            echo '<script>
                    document.addEventListener("DOMContentLoaded", function() {
                        Swal.fire({
                            icon: "warning",
                            title: "Warning",
                            text: "Pesan tidak boleh lebih dari 200 karakter.",
                            confirmButtonText: "OK"
                        }).then(() => {
                            window.history.back();
                        });
                    });
                  </script>';
            exit();
        }

        $jenis_db = ($jenis === "question") ? "question" : "testimoni";
        $sql = "INSERT INTO tq (pesan, jenis, pekerjaan, date, rating, id_user) VALUES ('$pesan', '$jenis', '$pekerjaan', '$date', '$rating', '$id_user')";

        if ($koneksi->query($sql) === true) {
            echo '<script>
                    document.addEventListener("DOMContentLoaded", function() {
                        Swal.fire({
                            icon: "success",
                            title: "Success",
                            text: "Data berhasil dikirim.",
                            confirmButtonText: "OK"
                        }).then(() => {
                            window.location.href = "index.php";
                        });
                    });
                  </script>';
        } else {
            echo '<script>
                    document.addEventListener("DOMContentLoaded", function() {
                        Swal.fire({
                            icon: "error",
                            title: "Error",
                            text: "Terjadi kesalahan silahkan coba lagi.",
                            confirmButtonText: "OK"
                        });
                    });
                  </script>';
        }
    }

    // Edit username
    if (isset($_POST['username'])) {
        $username = $_POST['username'];
        $update_username_query = "UPDATE user SET username='$username' WHERE id_user='$id'";

        if (mysqli_query($koneksi, $update_username_query)) {
            $_SESSION['username'] = $username;
            $success_message = "Nama anda berhasil diperbarui!";
        } else {
            $error_message = "Gagal memperbarui nama: " . mysqli_error($koneksi);
        }
    }

    // Set SweetAlert messages for username update
    if (!empty($success_message)) {
        echo '<script>
                document.addEventListener("DOMContentLoaded", function() {
                    Swal.fire({
                        icon: "success",
                        title: "Berhasil",
                        text: "' . $success_message . '",
                        confirmButtonText: "OK"
                    }).then(() => {
                        window.location.href = "index.php";
                    });
                });
              </script>';
    }

    if (!empty($error_message)) {
        echo '<script>
                document.addEventListener("DOMContentLoaded", function() {
                    Swal.fire({
                        icon: "error",
                        title: "Oops!",
                        text: "' . $error_message . '",
                        confirmButtonText: "OK"
                    });
                });
              </script>';
    }
}
?>


<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="Mark Otto, Jacob Thornton, and Bootstrap contributors">
    <meta name="generator" content="Hugo 0.84.0">
    <title>Lucart</title>

    <link rel="stylesheet" href="css/index2.css">

    <!--AOS-->
    <link rel="stylesheet" href="https://unpkg.com/aos@next/dist/aos.css" />

    <!--Icons-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!--Google web Font-->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Barlow+Condensed:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Pacifico&display=swap" rel="stylesheet">

    <!--Boostrap-->
    <link rel="canonical" href="https://getbootstrap.com/docs/5.0/examples/carousel/">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">

    <!-- Libraries Stylesheet -->
    <link href="lib/animate/animate.min.css" rel="stylesheet">
    <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">

    <!-- icon title -->
    <link rel="icon" href="img/logolagi.png">

    <style>
        .profile-popup {
            font-family: "Poppins", sans-serif;
            position: fixed;
            top: 90px;
            right: 10px;
            width: 350px;
            height: auto;
            z-index: 1050;
            display: none;
            /* Awalnya tersembunyi */
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .profile-popup {
            max-width: 400px;
            margin: auto;
            background: linear-gradient(to bottom right, #f7f9fc, #e9eff5);
            border-radius: 15px;
            overflow: hidden;
        }

        .profile-img {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 50%;
            border: 4px solid rgba(23, 14, 14, 0.7);
        }

        .close-icon {
            position: absolute;
            top: 15px;
            right: 15px;
            font-size: 20px;
            cursor: pointer;
            transition: transform 0.3s ease;
        }

        .close-icon:hover {
            transform: scale(1.2);
        }

        .primary-profil {
            background: linear-gradient(45deg, #0278AE, #51ADCF);
            border: none;
            transition: background 0.3s ease, transform 0.2s ease;
        }

        .primary-profil:hover {
            background: linear-gradient(45deg, #0056b3, #003d80);
            transform: translateY(-2px);
        }

        .btn-danger {
            background: linear-gradient(45deg, #dc3545, #a71d2a);
            border: none;
            transition: background 0.3s ease, transform 0.2s ease;
        }

        .btn-danger:hover {
            background: linear-gradient(45deg, #a71d2a, #850e1b);
            transform: translateY(-2px);
        }

        .modal-content {
            border-radius: 15px;
            overflow: hidden;
        }

        .modal-header {
            background-color: #54473F;
            border-bottom: 2px solid #625757;
        }

        .modal-body {
            background-color: #f8f9fa;
        }

        .form-control {
            border-radius: 8px;
            padding: 10px;
            border-color: #625757;
        }

        .btn-success {
            background: linear-gradient(45deg, #28a745, #218838);
            border: none;
            transition: background 0.3s ease, transform 0.2s ease;
        }

        .btn-success:hover {
            background: linear-gradient(45deg, #218838, #1c7430);
            transform: translateY(-2px);
        }

        .btn-secondary {
            background: linear-gradient(45deg, #838383, #687980);
            border: none;
            transition: background 0.3s ease, transform 0.2s ease;
        }

        .btn-secondary:hover {
            background: linear-gradient(45deg, #6E6D6D, #52575D);
            transform: translateY(-2px);
        }

        #editProfilePopup {
            font-family: "Poppins", sans-serif;
        }


        .produk .navbar-product {
            display: flex;
            flex-wrap: wrap;
            margin-bottom: 80px;
            margin-top: 20px;
        }

        .produk .navbar-product ul li button {
            font-family: "Poppins", sans-serif;
            text-decoration: none;
            font-weight: 500;
            font-size: 18px;
            color: #252525;
            margin-right: 15px;
            cursor: pointer;
            transition: 0.5s all;
        }

        .produk .navbar-product ul li button.active,
        .produk .navbar-product ul li button:hover {
            border-radius: 0 20px 0 20px;
            background-color: #4F6F52;
            color: #ececec;
            font-weight: 500;
        }
    </style>
</head>

<body>

    <header>
        <nav class="navbar navbar-expand-md navbar-dark fixed-top p-1">
            <div class="container">
                <div class="logo">
                    <a class="navbar-brand" href="#"><img src="img/logo.jpg" alt="" /></a>
                </div>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse"
                    aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse justify-content-end" id="navbarCollapse">
                    <ul class="navbar-nav ml-auto mb-2 mb-md-0">
                        <li class="nav-item">
                            <a class="nav-link" id="nav-home" aria-current="page" href="#home">Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="nav-about" href="#about">About</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="nav-produk" href="#produk">Product</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="nav-testimonials" href="#testimonials">Testimonials</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="nav-faq" href="#faq">FAQ</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="nav-kontak" href="#kontak">Contact</a>
                        </li>
                        <?php if (isset($_SESSION['role']) && $_SESSION['role'] == 'customer'): ?>
                            <li class="nav-item">
                                <a class="nav-link" id="profileButton" href="#" onclick="profilePopup()"><i class="fa-solid fa-circle-user"></i></a>
                            </li>
                        <?php else: ?>
                            <li class="nav-item">
                                <a class="nav-link" id="profileButton" href="#" onclick="showRegisterAlert()"><i class="fa-solid fa-circle-user"></i></a>
                            </li>
                        <?php endif; ?>

                        <script>
                            function showRegisterAlert() {
                                Swal.fire({
                                    title: 'Tidak ada akun yang ditemukan!',
                                    text: "Anda belum memiliki akun. Apakah Anda ingin mendaftar?",
                                    icon: 'info',
                                    showCancelButton: true,
                                    confirmButtonColor: '#3085d6',
                                    cancelButtonColor: '#d33',
                                    confirmButtonText: 'Daftar Sekarang'
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        location.href = 'register-customer.php';
                                    }
                                });
                            }
                        </script>
                    </ul>
                </div>
            </div>
        </nav>

        <!-- Popup Profile -->
        <div class="card profile-popup shadow-lg border-0" id="profilePopup">
            <div class="card-body position-relative">
                <!-- Icon Close -->
                <span class="close-icon text-dark fw-bold" id="closePopup">&times;</span>

                <div class="profile-header text-center">
                    <img id="profilePreview" src="<?php echo $_SESSION['foto'] ?? 'default-profile.png'; ?>" alt="Profile" class="profile-img mb-3">
                    <div>
                        <h6 id="username"><?php echo $_SESSION['username'] ?? 'Guest'; ?></h6>
                        <p id="email" class="text-muted fst-italic"><?php echo $_SESSION['email'] ?? '-'; ?></p>
                    </div>
                </div>

                <hr class="my-4">

                <!-- Tombol Edit Profil -->
                <button class="btn btn-primary primary-profil btn-sm w-100 shadow-sm mb-2" id="editProfileButton" data-bs-toggle="modal" data-bs-target="#editProfilePopup">
                    <i class="fa-solid fa-user-edit me-2"></i>Edit Profil
                </button>

                <!-- Tombol Logout -->
                <button class="btn btn-danger btn-sm w-100 shadow-sm" id="logoutButton" onclick="confirmLogout()">
                    <i class="fa-solid fa-arrow-right-from-bracket me-2"></i>Logout
                </button>
            </div>
        </div>

        <!-- Popup Edit Profil -->
        <div class="modal fade" id="editProfilePopup" tabindex="-1" aria-labelledby="editProfilePopupLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content shadow-lg">
                    <div class="modal-header text-white">
                        <h5 class="modal-title" id="editProfilePopupLabel">Edit Profil</h5>
                        <!-- <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button> -->
                    </div>
                    <div class="modal-body bg-light">
                        <form action="" method="POST" enctype="multipart/form-data" id="editProfileForm">
                            <!-- Input Edit Nama -->
                            <div class="mb-4">
                                <label for="usernameInput" class="form-label fw-bold">Nama Baru</label>
                                <input type="text" class="form-control border-2 shadow-sm" name="username" id="usernameInput" value="<?php echo $_SESSION['username'] ?? ''; ?>" required>
                            </div>
                            <!-- Input Ganti Foto -->
                            <div class="mb-4">
                                <label for="profile_picture" class="form-label fw-bold">Ganti Foto</label>
                                <input type="file" class="form-control border-2 shadow-sm" name="profile_picture" id="profile_picture" required>
                            </div>
                            <!-- Tombol Simpan -->
                            <!-- <button type="submit" class="btn btn-success w-100 shadow-sm" onclick="confirmSave(event)">Simpan</button> -->
                            <!-- Tombol Simpan -->
                            <div class="d-flex justify-content-end gap-2">
                                <button type="button" class="btn btn-secondary w-40 shadow-sm" data-bs-dismiss="modal">Batal</button>
                                <button type="submit" class="btn btn-success w-40 shadow-sm" onclick="confirmSave(event)">Simpan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>


        <!-- JavaScript for Toggle Form Edit Profile and SweetAlert Confirmations -->
        <script>
            function toggleEditForm() {
                const form = document.getElementById('editProfileForm');
                form.style.display = form.style.display === 'none' ? 'block' : 'none';
            }

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

            function confirmSave(event) {
                event.preventDefault(); // Cegah pengiriman default formulir

                // Validasi manual input (jika diperlukan)
                const usernameInput = document.getElementById('usernameInput').value.trim();
                if (!usernameInput) {
                    Swal.fire('Oops...', 'Nama tidak boleh kosong!', 'error');
                    return;
                }

                Swal.fire({
                    title: 'Simpan perubahan?',
                    text: "Apakah Anda ingin menyimpan perubahan?",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, simpan!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById('editProfileForm').submit(); // Submit formulir jika dikonfirmasi
                    }
                });
            }
        </script>


    </header>

    <main>
        <!--CAROUSEL-->
        <section id="home">
            <div class="container-fluid px-0 mb-5">
                <div id="header-carousel" class="carousel slide" data-bs-ride="carousel">
                    <div class="carousel-inner">
                        <div class="carousel-item active">
                            <img class="w-100" src="img/baner.jpg" alt="Image">
                            <div class="carousel-caption">
                                <div class="container">
                                    <div class="row justify-content-end">
                                        <div class="col-lg-8 text-end">
                                            <h5 class="display-1 mb-2 animated slideInRight">Lembut di Kulit</h5>
                                            <p class="text-dark mb-4">Terbuat dari bahan yang sangat lembut dan memberikan kenyamanan.</p>
                                            <a href="#produk" class="btn btn-outline-dark rounded-pill animated slideInLeft">Shop Now</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="carousel-item ">
                            <img class="w-100" src="img/banertiga.jpg" alt="Image">
                            <div class="carousel-caption">
                                <div class="container">
                                    <div class="row justify-content-start">
                                        <div class="col-lg-8 text-start">
                                            <h5 class="display-1 mb-2 animated slideInRight">Tahan Lama</h5>
                                            <p class="text-dark mb-4">Kualitas bahan yang awet menjaga bentuk dan warna.</p>
                                            <a href="#produk" class="btn btn-outline-dark rounded-pill animated slideInRight">Shop Now</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <button class="carousel-control-prev" type="button" data-bs-target="#header-carousel"
                        data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Previous</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#header-carousel"
                        data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Next</span>
                    </button>
                </div>
            </div>
        </section>


        <!-- ABOUT -->
        <section class="about-us" id="about">
            <div class="about_section layout_padding">
                <div class="container mt-5">
                    <div class="about_section_2">
                        <div class="row">
                            <div class="col-md-6" data-aos="fade-up" data-aos-duration="1000">
                                <div class="about_taital_box">
                                    <h1 class="about_taital">About Our shop</h1>
                                    <h1 class="about_taital_1">Lucart Custom</h1>
                                    <p class=" about_text">Lucart berfokus pada penjualan t-shirt custom dengan design yang menyesuaikan keingginan customer. Dibuat dari bahan berkualitas tinggi, setiap t-shirt dirancang dengan cermat untuk detail, memastikan daya tahan dan kenyamanan maksimal.</p>
                                    <a href="#about" class="readmore_btn btn btn-outline-dark" id="openPopupAboutBtn">READ MORE</a>
                                </div>
                            </div>
                            <div class="col-md-6" data-aos="fade-up" data-aos-delay="400">
                                <div class="image_iman">
                                    <img src="img/aboutt.png" class="about_img">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div id="overlayabout" class="overlay-about">
                <div id="popupabout" class="popup-about">
                    <div class="popup-body-about">
                        <div class="row align-items-center">

                            <div class="overlay-img col-lg-5">
                                <img src="img/owner.jpg" alt="Image" class="img-fluid">
                                <div class="team-social">
                                    <h3>Muhammad Vian Ferdian</h3>
                                    <p class="new">Owner Lucart</p>
                                    <a href="https://www.instagram.com/vianferdian_24/" target="_blank"><i class="fa-brands fa-instagram"></i></a>
                                    <a href="" target="_blank"><i class="fa-brands fa-facebook-f"></i></a>
                                    <a href="" target="_blank"><i class="fa-brands fa-twitter"></i></a>
                                </div>
                            </div>

                            <div class="text-about col-lg-6">
                                <h2 class="popup-title-about">ABOUT LUCART</h2>
                                <p class="popup-desc-about">Lucart menghadirkan koleksi t-shirt yang memadukan keindahan dengan sentuhan modern. Setiap t-shirt dibuat dari <mark>bahan berkualitas tinggi</mark>, memastikan dan daya tahan yang luar biasa.</p>
                                <p class="popup-desc-about"> Dirancang dengan cermat untuk setiap detail, t-shirt Lucart tidak hanya menawarkan gaya yang elegan tetapi juga <mark>kesederhanaan yang menawan</mark>.</p>
                                <p class="popup-desc-about">Cocok untuk berbagai kesempatan, baik formal maupun kasual, Lucart adalah pilihan tepat bagi mereka yang <mark>mengutamakan kualitas dan estetika</mark> dalam berbusana.</p>
                                <a href="#produk" class="btn btn-dark py-2 px-4 mb-4 animated slideInLeft">SHOP NOW</a>
                            </div>

                        </div>
                        <div class="popup-close-about">
                            <button class="btn btn-outline-dark close-about position-absolute top-0 end-0" id="closePopupAboutBtn" type="submit"><i class="fa-solid fa-xmark"></i></button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="container">
                <div class="row g-4 mb-5 text-center">
                    <div class="col-lg-3 col-sm-6" data-aos="fade-up" data-aos-delay="200">
                        <div class="service-item rounded pt-3">
                            <div class="feature p-4">
                                <i class="fa-3x fa-solid fa-cart-shopping mb-4 "></i>
                                <h5>Mudah Berbelanja</h5>
                                <p>Belanja jadi lebih praktis, cukup dengan beberapa klik saja.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-6" data-aos="fade-up" data-aos-delay="400">
                        <div class="service-item rounded pt-3">
                            <div class="feature p-4">
                                <i class="fa-3x fa-regular fa-clock mb-4 "></i>
                                <h5>Tahan Lama</h5>
                                <p>Kualitas bahan yang awet, menjaga bentuk dan warna meski sering dicuci.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-6" data-aos="fade-up" data-aos-delay="600">
                        <div class="service-item rounded pt-3">
                            <div class="feature p-4">
                                <i class="fa-3x fa-regular fa-snowflake mb-4"></i>
                                <h5>Sejuk</h5>
                                <p>Material yang dirancang agar tidak panas, ideal untuk cuaca tropis.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-6" data-aos="fade-up" data-aos-delay="800">
                        <div class="service-item rounded pt-3">
                            <div class="feature p-4">
                                <i class="fa-3x fa-brands fa-cotton-bureau mb-4"></i>
                                <h5>Lembut</h5>
                                <p>Terbuat dari bahan yang sangat lembut serta memberikan kenyamanan ekstra.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


        </section>

        <!--PARALLAX 1-->
        <div class="parallax-effect-container">
            <div class="parallax-effect-image" style="background-image: url(https://fastly.picsum.photos/id/821/5000/3333.jpg?hmac=zDv5UYxwCTpXgUI9pkre97bCA67pbnca0kRbMCvM5A0)"></div>
            <div class="parallax-effect-content">
                Make A New Step
            </div>
        </div>


        <!-- Produk -->
        <section class="bg-light produk" id="produk">
            <div class="container py-5" data-aos="fade-up" data-aos-duration="1000">
                <center>
                    <h1>Our Product</h1>
                </center>
                <div class="navbar-product justify-content-center">
                    <ul class="nav" id="myTab" role="tablist" data-aos="fade-up" data-aos-delay="100">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="all-products-tab" data-toggle="tab" data-target="#all-products"
                                type="button" role="tab" aria-controls="all-products" aria-selected="true">All Products</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="tshirt-custom-tab" data-toggle="tab" data-target="#tshirt-custom"
                                type="button" role="tab" aria-controls="tshirt-custom" aria-selected="false">T-shirt Custom</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="tshirt-kartun-tab" data-toggle="tab" data-target="#tshirt-kartun"
                                type="button" role="tab" aria-controls="tshirt-kartun" aria-selected="false">T-shirt Kartun</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="tshirt-club-tab" data-toggle="tab" data-target="#tshirt-club"
                                type="button" role="tab" aria-controls="tshirt-club" aria-selected="false">T-shirt Club</button>
                        </li>
                    </ul>
                </div>

                <div class="tab-content mt-4" id="myTabContent">
                    <div class="container mt-5 tab-pane fade show active" id="all-products" role="tabpanel" aria-labelledby="all-products-tab">
                        <div class="row g-4">
                            <?php
                            $query = mysqli_query($koneksi, "SELECT * FROM `product` LIMIT 8") or die('query failed');
                            if (mysqli_num_rows($query) > 0) {
                                while ($data = mysqli_fetch_assoc($query)) {
                                    $modal_id = "modal_" . $data['id_product'];
                            ?>
                                    <div class="col-md-3" data-bs-toggle="modal" data-bs-target="#<?php echo $modal_id; ?>" data-aos="fade-up" data-aos-delay="200">
                                        <div class="card product-card position-relative">
                                            <img src="image/<?= $data["gambar_product"] ?>" class="card-img-top" alt="Image">
                                            <div class="card-body">
                                                <h5 class="card-title special">Kaos Pria Wanita T-Shirt | <?= $data["name_product"] ?></h5>
                                                <p class="price"><small>Rp</small><?= number_format($data["harga_product"], 0, ',', '.'); ?></p>
                                                <a href="#produk" class="btn btn-outline-dark" data-toggle="modal" data-target="#product-modal">View Detail</a>
                                            </div>
                                        </div>
                                    </div>


                                    <!-- MODAL PRODUK -->
                                    <div class="modal fade" id="<?php echo $modal_id; ?>" tabindex="-1" aria-labelledby="<?php echo $modal_id; ?>" aria-hidden="true">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h2>Detail Produk</h2>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="row justify-content-between align-items-center">
                                                        <div class="col-lg-5">
                                                            <div class="img-modal">
                                                                <img src="image/<?= $data["gambar_product"] ?>" alt="Image" class="img-fluid">
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-6">
                                                            <h2 class="name-product">Kaos Pria Wanita T-Shirt | <?= $data["name_product"] ?></h2>
                                                            <p class="price-product"><small>Rp</small><?= number_format($data["harga_product"], 0, ',', '.'); ?> - <small>Rp</small>90.000</p>
                                                            <ul>
                                                                <li>Color</li>
                                                                <li class="navy"></li>
                                                                <li class="white"></li>
                                                                <li class="black"></li>
                                                                <li class="blue"></li>
                                                            </ul>
                                                            <p class="desc-product"><?= $data["deskripsi_product"] ?></p>
                                                        </div>
                                                    </div>
                                                    <table>
                                                        <thead>
                                                            <tr>
                                                                <th>Size</th>
                                                                <th>S</th>
                                                                <th>M</th>
                                                                <th>L</th>
                                                                <th>XL</th>
                                                                <th>2XL</th>
                                                                <th>3XL</th>
                                                                <th>4XL</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td>Lingkar Dada</td>
                                                                <td>47</td>
                                                                <td>48</td>
                                                                <td>50</td>
                                                                <td>52</td>
                                                                <td>54</td>
                                                                <td>56</td>
                                                                <td>60</td>
                                                            </tr>
                                                            <tr>
                                                                <td>Panjang Badan</td>
                                                                <td>68</td>
                                                                <td>72</td>
                                                                <td>74</td>
                                                                <td>76</td>
                                                                <td>78</td>
                                                                <td>80</td>
                                                                <td>82</td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                    <p class="note">*Lengan panjang +10k</p>
                                                    <div class="d-flex justify-content-end mt-3">
                                                        <a href="https://whatsform.com/ftJJtl">
                                                            <button id="hubPopupBtn" class="btn btn-primary" data-bs-dismiss="modal">
                                                                Pesan sekarang
                                                            </button>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                            <?php
                                }
                            } else {
                                echo "<P>No product failed</P>";
                            }
                            ?>
                        </div>
                    </div>

                    <!-- Kategori Custom -->
                    <div class="tab-pane fade" id="tshirt-custom" role="tabpanel" aria-labelledby="tshirt-custom-tab">
                        <div class="row">
                            <?php
                            $query = mysqli_query($koneksi, "SELECT * FROM `product` WHERE kategori_product='T-shirt Custom' LIMIT 8") or die('query failed');
                            if (mysqli_num_rows($query) > 0) {
                                $index = 0;
                                while ($data = mysqli_fetch_assoc($query)) {
                            ?>
                                    <div class="col-md-3" data-bs-toggle="modal" data-bs-target="#katModalCustom<?php echo $index + 1; ?>" data-aos="fade-up" data-aos-delay="200">
                                        <div class="card product-card position-relative">
                                            <img src="image/<?= $data["gambar_product"] ?>" class="card-img-top" alt="Image">
                                            <div class="card-body">
                                                <h5 class="card-title special">Kaos Pria Wanita T-Shirt | <?= $data["name_product"] ?></h5>
                                                <p class="price"><small>Rp</small><?= number_format($data["harga_product"], 0, ',', '.'); ?></p>
                                                <a href="#produk" class="btn btn-outline-dark" data-toggle="modal" data-target="#product-modal">View Detail</a>
                                            </div>
                                        </div>
                                    </div>
                            <?php
                                    $index++;
                                }
                            } else {
                                echo "<p>No products found.</p>";
                            }
                            ?>
                        </div>
                    </div>

                    <?php
                    $query = mysqli_query($koneksi, "SELECT * FROM `product` WHERE kategori_product='T-shirt Custom' LIMIT 8") or die('query failed');
                    if (mysqli_num_rows($query) > 0) {
                        $index = 0;
                        while ($data = mysqli_fetch_assoc($query)) {
                    ?>
                            <div class="modal fade" id="katModalCustom<?php echo $index + 1; ?>" tabindex="-1" aria-labelledby="katModalCustom<?php echo $index + 1; ?>" aria-hidden="true">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h2>Detail Produk</h2>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row justify-content-between align-items-center">
                                                <div class="col-lg-5">
                                                    <div class="img-modal">
                                                        <img src="image/<?= $data["gambar_product"] ?>" alt="Image" class="img-fluid">
                                                    </div>
                                                </div>
                                                <div class="col-lg-6">
                                                    <h2 class="name-product">Kaos Pria Wanita T-Shirt | <?= $data["name_product"] ?></h2>
                                                    <p class="price-product"><small>Rp</small><?= number_format($data["harga_product"], 0, ',', '.'); ?> - <small>Rp</small>90.000</p>
                                                    <ul>
                                                        <li>Color</li>
                                                        <li class="navy"></li>
                                                        <li class="white"></li>
                                                        <li class="black"></li>
                                                        <li class="blue"></li>
                                                    </ul>
                                                    <p class="desc-product"><?= $data["deskripsi_product"] ?></p>
                                                </div>
                                            </div>
                                            <table>
                                                <thead>
                                                    <tr>
                                                        <th>Size</th>
                                                        <th>S</th>
                                                        <th>M</th>
                                                        <th>L</th>
                                                        <th>XL</th>
                                                        <th>2XL</th>
                                                        <th>3XL</th>
                                                        <th>4XL</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td>Lingkar Dada</td>
                                                        <td>47</td>
                                                        <td>48</td>
                                                        <td>50</td>
                                                        <td>52</td>
                                                        <td>54</td>
                                                        <td>56</td>
                                                        <td>60</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Panjang Badan</td>
                                                        <td>68</td>
                                                        <td>72</td>
                                                        <td>74</td>
                                                        <td>76</td>
                                                        <td>78</td>
                                                        <td>80</td>
                                                        <td>82</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                            <p class="note">*Lengan panjang +10k</p>
                                            <div class="d-flex justify-content-end mt-3">
                                                <a href="https://whatsform.com/ftJJtl">
                                                    <button id="hubPopupBtn" class="btn btn-primary" data-bs-dismiss="modal">
                                                        Pesan sekarang
                                                    </button>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                    <?php
                            $index++;
                        }
                    } else {
                        echo "<p>No products found.</p>";
                    }
                    ?>

                    <!-- Kategori Kartun -->
                    <div class="tab-pane fade" id="tshirt-kartun" role="tabpanel" aria-labelledby="tshirt-kartun-tab">
                        <div class="row">
                            <?php
                            $query = mysqli_query($koneksi, "SELECT * FROM `product` WHERE kategori_product='T-shirt Kartun' LIMIT 8") or die('query failed');
                            if (mysqli_num_rows($query) > 0) {
                                $index = 0;
                                while ($data = mysqli_fetch_assoc($query)) {
                            ?>
                                    <div class="col-md-3" data-bs-toggle="modal" data-bs-target="#katModalKartun<?php echo $index + 1; ?>" data-aos="fade-up" data-aos-delay="200">
                                        <div class="card product-card position-relative">
                                            <img src="image/<?= $data["gambar_product"] ?>" class="card-img-top" alt="Image">
                                            <div class="card-body">
                                                <h5 class="card-title special">Kaos Pria Wanita T-Shirt | <?= $data["name_product"] ?></h5>
                                                <p class="price"><small>Rp</small><?= number_format($data["harga_product"], 0, ',', '.'); ?></p>
                                                <a href="#produk" class="btn btn-outline-dark" data-toggle="modal" data-target="#product-modal">View Detail</a>
                                            </div>
                                        </div>
                                    </div>
                            <?php
                                    $index++;
                                }
                            } else {
                                echo "<p>No products found.</p>";
                            }
                            ?>
                        </div>
                    </div>

                    <?php
                    $query = mysqli_query($koneksi, "SELECT * FROM `product` WHERE kategori_product='T-shirt Kartun' LIMIT 8") or die('query failed');
                    if (mysqli_num_rows($query) > 0) {
                        $index = 0;
                        while ($data = mysqli_fetch_assoc($query)) {
                    ?>
                            <div class="modal fade" id="katModalKartun<?php echo $index + 1; ?>" tabindex="-1" aria-labelledby="katModalKartun<?php echo $index + 1; ?>" aria-hidden="true">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h2>Detail Produk</h2>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row justify-content-between align-items-center">
                                                <div class="col-lg-5">
                                                    <div class="img-modal">
                                                        <img src="image/<?= $data["gambar_product"] ?>" alt="Image" class="img-fluid">
                                                    </div>
                                                </div>
                                                <div class="col-lg-6">
                                                    <h2 class="name-product">Kaos Pria Wanita T-Shirt | <?= $data["name_product"] ?></h2>
                                                    <p class="price-product"><small>Rp</small><?= number_format($data["harga_product"], 0, ',', '.'); ?> - <small>Rp</small>90.000</p>
                                                    <ul>
                                                        <li>Color</li>
                                                        <li class="navy"></li>
                                                        <li class="white"></li>
                                                        <li class="black"></li>
                                                        <li class="blue"></li>
                                                    </ul>
                                                    <p class="desc-product"><?= $data["deskripsi_product"] ?></p>
                                                </div>
                                            </div>
                                            <table>
                                                <thead>
                                                    <tr>
                                                        <th>Size</th>
                                                        <th>S</th>
                                                        <th>M</th>
                                                        <th>L</th>
                                                        <th>XL</th>
                                                        <th>2XL</th>
                                                        <th>3XL</th>
                                                        <th>4XL</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td>Lingkar Dada</td>
                                                        <td>47</td>
                                                        <td>48</td>
                                                        <td>50</td>
                                                        <td>52</td>
                                                        <td>54</td>
                                                        <td>56</td>
                                                        <td>60</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Panjang Badan</td>
                                                        <td>68</td>
                                                        <td>72</td>
                                                        <td>74</td>
                                                        <td>76</td>
                                                        <td>78</td>
                                                        <td>80</td>
                                                        <td>82</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                            <p class="note">*Lengan panjang +10k</p>
                                            <div class="d-flex justify-content-end mt-3">
                                                <a href="https://whatsform.com/ftJJtl">
                                                    <button id="hubPopupBtn" class="btn btn-primary btn-pesan" data-bs-dismiss="modal">
                                                        Pesan sekarang
                                                    </button>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                    <?php
                            $index++;
                        }
                    } else {
                        echo "<p>No products found.</p>";
                    }
                    ?>

                    <!-- Kategori Club -->
                    <div class="tab-pane fade" id="tshirt-club" role="tabpanel" aria-labelledby="tshirt-club-tab">
                        <div class="row">
                            <?php
                            $query = mysqli_query($koneksi, "SELECT * FROM `product` WHERE kategori_product='T-shirt Club' LIMIT 8") or die('query failed');
                            if (mysqli_num_rows($query) > 0) {
                                $index = 0;
                                while ($data = mysqli_fetch_assoc($query)) {
                            ?>
                                    <div class="col-md-3" data-bs-toggle="modal" data-bs-target="#katModalClub<?php echo $index + 1; ?>" data-aos="fade-up" data-aos-delay="200">
                                        <div class="card product-card position-relative">
                                            <img src="image/<?= $data["gambar_product"] ?>" class="card-img-top" alt="Image">
                                            <div class="card-body">
                                                <h5 class="card-title special">Kaos Pria Wanita T-Shirt | <?= $data["name_product"] ?></h5>
                                                <p class="price"><small>Rp</small><?= number_format($data["harga_product"], 0, ',', '.'); ?></p>
                                                <a href="#produk" class="btn btn-outline-dark" data-toggle="modal" data-target="#product-modal">View Detail</a>
                                            </div>
                                        </div>
                                    </div>
                            <?php
                                    $index++;
                                }
                            } else {
                                echo "<p>No products found.</p>";
                            }
                            ?>
                        </div>
                    </div>

                    <?php
                    $query = mysqli_query($koneksi, "SELECT * FROM `product` WHERE kategori_product='T-shirt Club' LIMIT 8") or die('query failed');
                    if (mysqli_num_rows($query) > 0) {
                        $index = 0;
                        while ($data = mysqli_fetch_assoc($query)) {
                    ?>
                            <div class="modal fade" id="katModalClub<?php echo $index + 1; ?>" tabindex="-1" aria-labelledby="katModalClub<?php echo $index + 1; ?>" aria-hidden="true">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h2>Detail Produk</h2>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row justify-content-between align-items-center">
                                                <div class="col-lg-5">
                                                    <div class="img-modal">
                                                        <img src="image/<?= $data["gambar_product"] ?>" alt="Image" class="img-fluid">
                                                    </div>
                                                </div>
                                                <div class="col-lg-6">
                                                    <h2 class="name-product">Kaos Pria Wanita T-Shirt | <?= $data["name_product"] ?></h2>
                                                    <p class="price-product"><small>Rp</small><?= number_format($data["harga_product"], 0, ',', '.'); ?> - <small>Rp</small>90.000</p>
                                                    <ul>
                                                        <li>Color</li>
                                                        <li class="navy"></li>
                                                        <li class="white"></li>
                                                        <li class="black"></li>
                                                        <li class="blue"></li>
                                                    </ul>
                                                    <p class="desc-product"><?= $data["deskripsi_product"] ?></p>
                                                </div>
                                            </div>
                                            <table>
                                                <thead>
                                                    <tr>
                                                        <th>Size</th>
                                                        <th>S</th>
                                                        <th>M</th>
                                                        <th>L</th>
                                                        <th>XL</th>
                                                        <th>2XL</th>
                                                        <th>3XL</th>
                                                        <th>4XL</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td>Lingkar Dada</td>
                                                        <td>47</td>
                                                        <td>48</td>
                                                        <td>50</td>
                                                        <td>52</td>
                                                        <td>54</td>
                                                        <td>56</td>
                                                        <td>60</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Panjang Badan</td>
                                                        <td>68</td>
                                                        <td>72</td>
                                                        <td>74</td>
                                                        <td>76</td>
                                                        <td>78</td>
                                                        <td>80</td>
                                                        <td>82</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                            <p class="note">*Lengan panjang +10k</p>
                                            <div class="d-flex justify-content-end mt-3">
                                                <a href="https://whatsform.com/ftJJtl">
                                                    <button id="hubPopupBtn" class="btn btn-primary" data-bs-dismiss="modal">
                                                        Pesan sekarang
                                                    </button>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                    <?php
                            $index++;
                        }
                    } else {
                        echo "<p>No products found.</p>";
                    }
                    ?>
                </div>
            </div>
        </section>

        <!--TESTIMONI-->
        <section id="testimonials" class="testimonial">
            <div class="container-fluid overflow-hidden pb-2">
                <div class="container py-5">
                    <div class="section-title text-center mb-5">
                        <div class="sub-style">
                            <h5 class="sub-title px-3">OUR CLIENTS RIVIEWS</h5>
                        </div>
                        <h1 class="display-5 mb-4" style="font-weight: 500;">What Our Clients Say</h1>
                    </div>
                    <div class="owl-carousel testimonial-carousel">
                        <?php
                        $sql2 = "SELECT tq.id_tq, tq.pesan, tq.jenis, tq.pekerjaan, tq.rating, user.username, user.email, user.foto 
                                            FROM tq JOIN user ON tq.id_user = user.id_user WHERE tq.jenis = 'testimoni' AND tq.status = 1 ORDER BY tq.id_tq DESC";
                        $q2 = mysqli_query($koneksi, $sql2);
                        $urut = 1;
                        while ($row = mysqli_fetch_assoc($q2)):
                        ?>
                            <div class="testimonial-item">
                                <div class="testimonial-content p-4 mb-5">
                                    <p class="fs-5 mb-0 text-black"><?php echo $row['pesan']; ?>
                                    </p>
                                    <div class="d-flex justify-content-end rating">
                                        <?php
                                        $rating = $row['rating'];
                                        for ($i = 0; $i < 5; $i++):
                                            if ($i < $rating):
                                                echo '<i class="fa-solid fa-star" style="margin-right: 2px; color: #FFD966; margin-top: 10px;"></i>';
                                            else:
                                                echo '<i class="fa-solid fa-star-o"></i>';
                                            endif;
                                        endfor;
                                        ?>
                                    </div>
                                </div>
                                <div class="d-flex">
                                    <div class="rounded-circle me-4 img-testi">
                                        <img class="img-fluid rounded-circle" src="<?php echo $row['foto']; ?> " alt="img">
                                    </div>
                                    <div class="my-auto">
                                        <h5><?php echo $row['username']; ?> </h5>
                                        <p class="mb-0" style="font-weight: 300; color: #e8e8e8;"><?php echo $row['pekerjaan']; ?> </p>
                                    </div>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    </div>
                </div>
            </div>
        </section>

        <!-- FAQ -->
        <section class="faq-section" id="faq">
            <div class="container p-5">
                <div class="row align-items-center">
                    <div class="col-md-6 col-lg-6" data-aos="fade-up" data-aos-delay="600">
                        <div class="img-wrap">
                            <img src="img/faq.jpg" alt="Image" class="img-fluid">
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-6">
                        <h2 class="section-title text-center" data-aos="fade-up" data-aos-duration="1000">Frequently Asked Questions</h2>
                        <p class="text-center" data-aos="fade-up" data-aos-delay="200">Pertanyaan yang sering ditanyakan seputar Lucart</p>
                        <div class="accordion" data-aos="fade-up" data-aos-delay="300" id="accordionFAQ">
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingOne">
                                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                        Apakah di Lucart bisa custom design?
                                    </button>
                                </h2>
                                <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#accordionFAQ">
                                    <div class="accordion-body">
                                        Tentu bisa, bahkan jika anda belum memiliki design maka akan kami buatkan.
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingTwo">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                        Apakah kualitas produk Lucart sepadan dengan harganya?
                                    </button>
                                </h2>
                                <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#accordionFAQ">
                                    <div class="accordion-body">
                                        Sepadan, karena Lucart menawarkan kenyamanan tak tertandingi dengan material Cotton Combed 30s yang lembut, sejuk, dan tahan lama.
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingThree">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                        Bagaimana cara pemesanan produk di Lucart?
                                    </button>
                                </h2>
                                <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#accordionFAQ">
                                    <div class="accordion-body">
                                        Untuk memesan produk dapat dilakukan dengan menghubungi admin via whatsapp atau mengunjungi sosial media yang tersedia seperti Shopee, Instagram, dan Tiktok.
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingFour">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                                        Tersedia berapa ukuran kaos di Lucart?
                                    </button>
                                </h2>
                                <div id="collapseFour" class="accordion-collapse collapse" aria-labelledby="headingFour" data-bs-parent="#accordionFAQ">
                                    <div class="accordion-body">
                                        Lucart menyediakan 7 ukuran (S, M, L, XL, XXL, 3XL, dan 4XL). <br> Untuk setiap ukuran berbeda harga.
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Contact -->
        <section class="bg-light" id="kontak">
            <div class="container-xxl py-5">
                <div class="container">
                    <div class="text-center" data-aos="fade-up" data-aos-duration="1000">
                        <h5 class="section-title-cont text-center fw-normal">Contact Us</h5>
                        <hr style="width: 50px; height: 3px;" class="mx-auto">
                        <h1 class="mb-5">Contact For Any Query</h1>
                    </div>
                    <div class="row g-4 contact-info">
                        <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-duration="1000">
                            <div class="box">
                                <div class="img-box">
                                    <i class="fa-brands fa-instagram"></i>
                                </div>
                                <h5 class="detail-box">Instagram</h5>
                                <p class="isi-box">lucart.ofc</p>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="200">
                            <div class="box">
                                <div class="img-box">
                                    <i class="fa-brands fa-tiktok"></i>
                                </div>
                                <h5 class="detail-box">Tiktok</h5>
                                <p class="isi-box">lucart.ofc</p>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="400">
                            <div class="box">
                                <div class="img-box">
                                    <i class="fa-solid fa-cart-shopping"></i>
                                </div>
                                <h5 class="detail-box">Shopee</h5>
                                <p class="isi-box">Lucart</p>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="800">
                            <div class="box">
                                <div class="img-box">
                                    <i class="fa fa-share"></i>
                                </div>
                                <h5 class="detail-box">Follow Us</h5>
                                <p class="isi-box">
                                    <i class="fa-brands fa-instagram" style="margin-right: 10px;"></i>
                                    <i class="fa-brands fa-tiktok" style="margin-right: 10px;"></i>
                                    <i class="fa-solid fa-cart-shopping"></i>
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="row g-4 mt-5">
                        <div class="col-md-12 col-lg-6" data-aos="fade-up" data-aos-duration="1000">
                            <iframe class="position-relative rounded w-100 h-100"
                                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3962.283023265935!2d108.5341578735624!3d-6.73528596585283!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e6f1df0e55b2ed3%3A0x51cf481547b4b319!2sSMK%20Negeri%201%20Cirebon!5e0!3m2!1sen!2sid!4v1720416809823!5m2!1sen!2sid"
                                frameborder="0" style="min-height: 350px; border:0;" allowfullscreen="" aria-hidden="false"
                                tabindex="0">
                            </iframe>
                        </div>
                        <div class="col-md-12 col-lg-6">
                            <div class="wow fadeInUp">
                                <form action="" method="post" id="contact-form" class="contact-form">
                                    <h3 class="mb-5 text-center">Questions and Testimoni here!</h3>
                                    <div class="row g-3" data-aos="fade-up" data-aos-delay="200">
                                        <div class="col-md-6">
                                            <div class="form-floating">
                                                <fieldset>
                                                    <input type="text" class="form-control" id="pekerjaan" name="pekerjaan" placeholder="Pekerjaan">
                                                </fieldset>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <fieldset>
                                                <select name="jenis" id="subject" autocomplete="on" required>
                                                    <option value="" disabled selected>Pilih Jenis</option>
                                                    <option value="question">Question</option>
                                                    <option value="testimoni">Testimoni</option>
                                                </select>
                                            </fieldset>
                                        </div>
                                        <div class="col-12">
                                            <div class="form-floating">
                                                <fieldset>
                                                    <textarea class="form-control" placeholder="Tinggalkan pesan disini"
                                                        id="pesan" name="pesan" style="height: 150px"
                                                        maxlength="200" oninput="updateCharCount()"></textarea>
                                                    <small id="char-count" class="text-muted m-2 mt-5" style="color: #d6d6d6;">Sisa karakter : 200</small>
                                                </fieldset>
                                            </div>
                                        </div>

                                        <div class="col-lg-12">
                                            <fieldset>
                                                <div class="rating-container" id="rating-container"
                                                    style="visibility: hidden; display: flex; align-items: center;">
                                                    <label for="rating-stars">Your Rating: </label>
                                                    <div class="stars" id="rating-stars">
                                                        <span class="star" data-value="1">★</span>
                                                        <span class="star" data-value="2">★</span>
                                                        <span class="star" data-value="3">★</span>
                                                        <span class="star" data-value="4">★</span>
                                                        <span class="star" data-value="5">★</span>
                                                    </div>
                                                </div>
                                                <input type="hidden" name="rating" id="rating-input" value="0">
                                            </fieldset>
                                        </div>

                                        <div class="col-12">
                                            <fieldset>
                                                <?php if ($isLoggedIn): ?>
                                                    <button class="btn btn-dark w-100 py-3" type="submit" name="simpan">Send Message</button>
                                                <?php else: ?>
                                                    <button class="btn btn-dark w-100 py-3" type="button" name="simpan" onclick="showInfoAlert()">Send Message</button>
                                                <?php endif; ?>
                                            </fieldset>
                                        </div>

                                        <script>
                                            function showInfoAlert() {
                                                Swal.fire({
                                                    title: 'Anda belum memiliki akun!',
                                                    text: "Silahkan daftar agar dapat memberikan testimoni & pertanyaan.",
                                                    icon: 'info',
                                                    showCancelButton: true,
                                                    confirmButtonColor: '#3085d6',
                                                    cancelButtonColor: '#d33',
                                                    confirmButtonText: 'Daftar Sekarang'
                                                }).then((result) => {
                                                    if (result.isConfirmed) {
                                                        location.href = 'register-customer.php';
                                                    }
                                                });
                                            }
                                        </script>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>


    <!--PARALLAX 2-->
    <div class="parallax-effect-container">
        <div class="parallax-effect-image" style="background-image: url(https://fastly.picsum.photos/id/535/2962/3949.jpg?hmac=Cs154XYJYEmSoM-YPHR1Kcp2LDRzxLfKxa67Av3ZIhY)"></div>
        <div class="parallax-effect-content">
            <span>Terimakasih</span><br> telah mengunjungi website kami
        </div>
    </div>


    <!-- FOOTER -->
    <div class="footer container-fluid text-light wow fadeIn">
        <div class="container py-5">
            <div class="row g-5">
                <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-duration="1000">
                    <img class="footer-logo align-items-center" src="img/foter.jpg" alt="logo" />
                    <p>Kaos Lucart adalah kombinasi sempurna dari kenyamanan, kualitas, dan gaya, menjadikannya pilihan utama untuk pakaian hari-hari anda.</p>
                </div>
                <div class="col-lg-2 col-md-6" data-aos="fade-up" data-aos-delay="200">
                    <h4 class="section-title ff-secondary text-start text-white fw-normal mb-4">Company</h4>
                    <a class="btn btn-link" href="#about">About Us</a>
                    <a class="btn btn-link" href="#produk">Product</a>
                    <a class="btn btn-link" href="#testimonials">Testimonials</a>
                    <a class="btn btn-link" href="#faq">FAQ</a>
                    <a class="btn btn-link" href="#kontak">Contact</a>
                </div>
                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="400">
                    <h4 class="section-title ff-secondary text-start text-white fw-normal mb-4">Contact</h4>
                    <p class="mb-2"><i class="fa fa-map-marker-alt me-3"></i>Jl. Perjuangan, Sunyaragi, Kec. Kesambi, Kota Cirebon, Jawa Barat</p>
                    <p class="mb-2"><i class="fa fa-phone-alt me-3"></i>0819 1452 3689</p>
                    <p class="mb-2"><i class="fa-solid fa-envelope me-3"></i>lucartofc@gmail.com</p>
                </div>
                <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="600">
                    <h4 class="section-title ff-secondary text-start text-white fw-normal mb-4">Sosial Media</h4>
                    <p class="mb-2"><i class="fa-brands fa-tiktok me-3"></i>lucart.ofc</p>
                    <p class="mb-2"><i class="fa-solid fa-bag-shopping me-3"></i>Lucart</p>
                    <p class="mb-2"><i class="fa-brands fa-instagram me-3"></i>lucart.ofc</p>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="copyright">
                <div class="row">
                    <div class="col-md-12 text-center text-md-center mb-3 mb-md-0">
                        &copy; Copyright 2024, All Right Reserved | Created by Livia Febriana
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="whatsapp">
        <a href="https://wa.me/6283823269127?text=permisi, saya ingin menanyakan..." class="shadow rounded-circle back-to-top">
            <i class="fa-brands fa-whatsapp"></i>
        </a>
    </div>


    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.min.js" integrity="sha384-cuYeSxntonz0PPNlHhBs68uyIAVpIIOZZ5JqeqvYYIcEL727kskC66kF92t6Xl2V" crossorigin="anonymous"></script>
    <script src="lib/owlcarousel/owl.carousel.min.js"></script>
    <script src="https://unpkg.com/aos@next/dist/aos.js"></script>
    <script src="js/index.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        AOS.init();

        // NAVBAR
        document.addEventListener('DOMContentLoaded', function() {
            const sections = document.querySelectorAll('section');
            const navLinks = document.querySelectorAll('.nav-link');

            window.addEventListener('scroll', () => {
                let current = '';

                sections.forEach(section => {
                    const sectionTop = section.offsetTop;
                    if (pageYOffset >= sectionTop - 60) { // 60 is the offset for the fixed navbar
                        current = section.getAttribute('id');
                    }
                });

                navLinks.forEach(link => {
                    link.classList.remove('active');
                    if (link.getAttribute('href').includes(current)) {
                        link.classList.add('active');
                    }
                });
            });

            // Toggle navbar on small screens
            const navbarToggler = document.querySelector('.navbar-toggler');
            const navbarCollapse = document.querySelector('#navbarCollapse');

            navbarToggler.addEventListener('click', () => {
                navbarCollapse.classList.toggle('active');
            });
        });

        //POPUP PROFILE
        const profileButton = document.getElementById('profileButton');
        const profilePopup = document.getElementById('profilePopup');
        const closePopup = document.getElementById('closePopup');
        const profileInput = document.getElementById('profileInput');
        const profilePreview = document.getElementById('profilePreview');
        const changePhotoButton = document.getElementById('changePhotoButton');
        const saveButton = document.getElementById('saveButton');

        // Tampilkan atau sembunyikan popup saat tombol profil diklik
        profileButton.addEventListener('click', () => {
            profilePopup.style.display = profilePopup.style.display === 'none' ? 'block' : 'none';
        });

        // Tutup popup saat ikon "X" diklik
        closePopup.addEventListener('click', () => {
            profilePopup.style.display = 'none';
        });

        // POPUP ABOUT 
        document.getElementById('openPopupAboutBtn').addEventListener('click', function() {
            document.getElementById('overlayabout').style.display = 'block';
            document.getElementById('popupabout').style.display = 'block';
        });

        document.getElementById('closePopupAboutBtn').addEventListener('click', function() {
            document.getElementById('overlayabout').style.display = 'none';
            document.getElementById('popupabout').style.display = 'none';
        });

        document.getElementById('overlayabout').addEventListener('click', function() {
            document.getElementById('overlayabout').style.display = 'none';
            document.getElementById('popupabout').style.display = 'none';
        });

        function showPopup() {
            document.getElementById('overlay').style.display = 'flex';
        }

        (function($) {
            "use strict";

            // Testimonial-carousel
            $(".testimonial-carousel").owlCarousel({
                autoplay: true,
                smartSpeed: 2000,
                center: false,
                dots: false,
                loop: true,
                margin: 25,
                nav: true,
                navText: [
                    '<i class="bi bi-arrow-left"></i>',
                    '<i class="bi bi-arrow-right"></i>'
                ],
                responsiveClass: true,
                responsive: {
                    0: {
                        items: 1
                    },
                    576: {
                        items: 1
                    },
                    768: {
                        items: 2
                    },
                    992: {
                        items: 2
                    },
                    1200: {
                        items: 2
                    }
                }
            });

        })(jQuery);

        //RATING TESTI
        const subjectSelect = document.getElementById('subject');
        const ratingContainer = document.getElementById('rating-container');
        const stars = document.querySelectorAll('#rating-stars .star');
        let selectedRating = 0;


        // Tampilkan atau sembunyikan rating berdasarkan pilihan
        subjectSelect.addEventListener('change', function() {
            if (subjectSelect.value === 'testimoni') {
                ratingContainer.style.visibility = 'visible';
            } else {
                ratingContainer.style.visibility = 'hidden';
                resetStars();
            }
        });

        // Hover dan klik untuk rating star
        stars.forEach((star, index) => {
            star.addEventListener('mouseover', () => {
                highlightStars(index);
            });

            star.addEventListener('mouseout', resetStars);

            star.addEventListener('click', () => {
                selectedRating = index + 1;
                setActiveStars(selectedRating - 1);
                document.getElementById('rating-input').value = selectedRating;
            });
        });

        function highlightStars(index) {
            stars.forEach((star, i) => {
                star.classList.toggle('hover', i <= index);
            });
        }

        function resetStars() {
            stars.forEach(star => star.classList.remove('hover'));
            setActiveStars(selectedRating - 1);
        }

        function setActiveStars(index) {
            stars.forEach((star, i) => {
                star.classList.toggle('active', i <= index);
            });
        }

        function updateCharCount() {
            const textarea = document.getElementById('pesan');
            const charCount = document.getElementById('char-count');
            const remaining = 200 - textarea.value.length;
            charCount.textContent = `Sisa karakter : ${remaining}`;
        }
    </script>


</body>

</html>