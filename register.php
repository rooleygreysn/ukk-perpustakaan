<?php
include "koneksi.php";
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>Register ke Perpustakaan Digital</title>
        <link href="css/styles.css" rel="stylesheet" />
        <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    </head>
    <body class="bg-primary">
        <div id="layoutAuthentication">
            <div id="layoutAuthentication_content">
                <main>
                    <div class="container">
                        <div class="row justify-content-center">
                            <div class="col-lg-5">
                                <div class="card shadow-lg border-0 rounded-lg mt-5">
                                    <div class="card-header bg-secondary text-white text-center">
                                        <h3 class="font-weight-light my-4">Register Perpustakaan Digital</h3>
                                    </div>
                                    <div class="card-body">
                                        <?php
                                        if (isset($_POST['register'])) {
                                            $nama = $_POST['nama'];
                                            $email = $_POST['email'];
                                            $alamat = $_POST['alamat'];
                                            $no_telepon = $_POST['no_telepon'];
                                            $username = $_POST['username'];
                                            $level = $_POST['level'];
                                            $password = md5($_POST['password']);

                                            // Cek duplikasi nama dan username
                                            $cek_duplikasi = mysqli_query($koneksi, "SELECT * FROM user WHERE nama = '$nama' OR username = '$username'");
                                            
                                            if(mysqli_num_rows($cek_duplikasi) > 0) {
                                                echo '<div class="alert alert-danger" role="alert">Registrasi gagal! Nama atau username sudah digunakan.</div>';
                                            } else {
                                                $insert = mysqli_query($koneksi, "INSERT INTO user(nama, email, alamat, no_telepon, username, password, level) 
                                                    VALUES('$nama', '$email', '$alamat', '$no_telepon', '$username', '$password', '$level')");

                                                if ($insert) {
                                                    echo '<script>alert("Selamat, register berhasil. Silahkan Login."); location.href="login.php";</script>';
                                                } else {
                                                    echo '<div class="alert alert-danger" role="alert">Register gagal, silakan coba lagi.</div>';
                                                }
                                            }
                                        }
                                        ?>
                                        <form method="post">
                                            <div class="form-floating mb-3">
                                                <input class="form-control" type="text" name="nama" placeholder="Masukkan Nama Lengkap" required />
                                                <label>Nama Lengkap</label>
                                            </div>
                                            <div class="form-floating mb-3">
                                                <input class="form-control" type="email" name="email" placeholder="Masukkan Email" required />
                                                <label>Email</label>
                                            </div>
                                            <div class="form-floating mb-3">
                                                <input class="form-control" type="text" name="no_telepon" placeholder="Masukkan No. Telepon" required />
                                                <label>No. Telepon</label>
                                            </div>
                                            <div class="form-floating mb-3">
                                                <textarea name="alamat" rows="3" class="form-control" placeholder="Masukkan Alamat" required></textarea>
                                                <label>Alamat</label>
                                            </div>
                                            <div class="form-floating mb-3">
                                                <input class="form-control" type="text" name="username" placeholder="Masukkan Username" required />
                                                <label>Username</label>
                                            </div>
                                            <div class="form-floating mb-3">
                                                <input class="form-control" type="password" name="password" placeholder="Masukkan Password" required />
                                                <label>Password</label>
                                            </div>
                                            <div class="form-floating mb-3">
                                                <input type="hidden" name="level" value="peminjam">
                                                <input class="form-control" type="text" value="Peminjam" disabled />
                                                <label>Level</label>
                                            </div>
                                            <div class="d-flex justify-content-between align-items-center mt-4 mb-0">
                                                <button class="btn btn-primary" type="submit" name="register">
                                                    <i class="fas fa-user-plus me-2"></i>Register
                                                </button>
                                                <a class="btn btn-danger" href="login.php">
                                                    <i class="fas fa-sign-in-alt me-2"></i>Login
                                                </a>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="card-footer text-center">
                                        <div class="small text-muted">
                                            &copy; DEKA ENDAH PASHA
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </main>
            </div>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
        <script src="js/scripts.js"></script>
    </body>
</html>
