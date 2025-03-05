<?php
include "koneksi.php";
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Login - Perpustakaan Digital</title>
        
        <!-- Existing styles -->
        <script src="https://cdn.tailwindcss.com"></script>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
        <script src="https://kit.fontawesome.com/yourcode.js"></script>
    </head>
    <body class="bg-gradient-to-br from-blue-500 to-purple-600 min-h-screen flex items-center justify-center p-4">
        <div class="max-w-md w-full">
            <!-- Card Container -->
            <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
                <!-- Header -->
                <div class="relative h-32 bg-gradient-to-r from-blue-600 to-blue-700">
                    <div class="absolute bottom-0 left-0 right-0 text-center transform translate-y-1/2">
                        <div class="inline-block p-4 px-6 bg-white rounded-2xl shadow-lg">
                            <h3 class="text-2xl font-bold text-gray-800">Perpustakaan Digital</h3>
                        </div>
                    </div>
                </div>

                <!-- Form Section -->
                <div class="px-8 pt-16 pb-8">
                    <?php
                    if (isset($_POST['login'])) {
                        $username = $_POST['username'];
                        $password = md5($_POST['password']);

                        $data = mysqli_query($koneksi, "SELECT * FROM user WHERE username='$username' AND password='$password'");
                        $cek = mysqli_num_rows($data);
                        if ($cek > 0) {
                            $_SESSION['user'] = mysqli_fetch_array($data);
                            echo '<script>alert("Selamat datang, Login Berhasil"); location.href="index.php";</script>';
                        } else {
                            echo '<div class="mb-4 p-4 bg-red-100 border-l-4 border-red-500 text-red-700">Username atau Password salah!</div>';
                        }
                    }
                    ?>
                    
                    <form method="post" class="space-y-6">
                        <!-- Username Input -->
                        <div class="relative">
                            <input type="text" name="username" 
                                   class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-colors text-gray-700 outline-none"
                                   placeholder="Username"
                                   required />
                        </div>

                        <!-- Password Input -->
                        <div class="relative">
                            <input type="password" name="password"
                                   class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-colors text-gray-700 outline-none"
                                   placeholder="Password"
                                   required />
                        </div>

                        <!-- Buttons -->
                        <div class="flex gap-4">
                            <button type="submit" name="login" value="login"
                                    class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-6 rounded-lg transition duration-200 ease-in-out transform hover:scale-105">
                                Login
                            </button>
                            <a href="register.php"
                               class="flex-1 bg-purple-600 hover:bg-purple-700 text-white font-semibold py-3 px-6 rounded-lg text-center transition duration-200 ease-in-out transform hover:scale-105">
                                Register
                            </a>
                        </div>
                    </form>
                </div>

                <!-- Footer -->
                <div class="py-4 text-center bg-gray-50">
                    <p class="text-sm text-gray-600">&copy; DEKA ENDAH PASHA</p>
                </div>
            </div>
        </div>
    </body>
</html>
