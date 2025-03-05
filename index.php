<?php
include "koneksi.php";
    if(!isset($_SESSION['user'])){
        header('location:login.php');
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perpustakaan Digital</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Awesome -->
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js"></script>
    <!-- Inter Font -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body class="bg-gray-50">
    <div class="min-h-screen">
        <!-- Sidebar -->
        <aside class="fixed inset-y-0 left-0 bg-gray-900 w-64 transition-transform duration-300 ease-in-out transform -translate-x-full sm:translate-x-0" id="sidebar">
            <div class="flex items-center justify-center h-16 bg-gray-800">
                <span class="text-white text-xl font-semibold">Deka Perpustakaan</span>
            </div>
            
            <nav class="mt-5 px-4">
                <div class="space-y-4">
                    <!-- Dashboard Link -->
                    <a href="?page=home" class="flex items-center px-4 py-2.5 text-gray-300 hover:bg-gray-800 rounded-lg transition-all duration-200">
                        <i class="fas fa-tachometer-alt w-5 h-5"></i>
                        <span class="ml-3">Dashboard</span>
                    </a>

                    <?php if ($_SESSION['user']['level'] != 'peminjam'): ?>
                        <!-- Admin Menu -->
                        <a href="?page=kategori" class="flex items-center px-4 py-2.5 text-gray-300 hover:bg-gray-800 rounded-lg transition-all duration-200">
                            <i class="fas fa-table w-5 h-5"></i>
                            <span class="ml-3">Kategori</span>
                        </a>
                        <a href="?page=buku" class="flex items-center px-4 py-2.5 text-gray-300 hover:bg-gray-800 rounded-lg transition-all duration-200">
                            <i class="fas fa-book w-5 h-5"></i>
                            <span class="ml-3">Buku</span>
                        </a>
                        <a href="?page=total_user" class="flex items-center px-4 py-2.5 text-gray-300 hover:bg-gray-800 rounded-lg transition-all duration-200">
                            <i class="fas fa-users w-5 h-5"></i>
                            <span class="ml-3">Total User</span>
                        </a>
                    <?php else: ?>
                        <!-- User Menu -->
                        <a href="?page=peminjaman" class="flex items-center px-4 py-2.5 text-gray-300 hover:bg-gray-800 rounded-lg transition-all duration-200">
                            <i class="fas fa-book-open w-5 h-5"></i>
                            <span class="ml-3">Peminjaman</span>
                        </a>
                    <?php endif; ?>

                    <!-- Common Menu Items -->
                    <a href="?page=ulasan" class="flex items-center px-4 py-2.5 text-gray-300 hover:bg-gray-800 rounded-lg transition-all duration-200">
                        <i class="fas fa-comment w-5 h-5"></i>
                        <span class="ml-3">Ulasan</span>
                    </a>

                    <?php if ($_SESSION['user']['level'] != 'peminjam'): ?>
                        <a href="?page=laporan" class="flex items-center px-4 py-2.5 text-gray-300 hover:bg-gray-800 rounded-lg transition-all duration-200">
                            <i class="fas fa-chart-bar w-5 h-5"></i>
                            <span class="ml-3">Laporan</span>
                        </a>
                    <?php endif; ?>

                    <a href="logout.php" class="flex items-center px-4 py-2.5 text-red-400 hover:bg-red-500 hover:text-white rounded-lg transition-all duration-200">
                        <i class="fas fa-power-off w-5 h-5"></i>
                        <span class="ml-3">Logout</span>
                    </a>
                </div>
            </nav>

            <div class="absolute bottom-0 w-full p-4 border-t border-gray-800">
                <div class="flex items-center space-x-3">
                    <img src="https://ui-avatars.com/api/?name=<?php echo urlencode($_SESSION['user']['nama']); ?>" class="w-10 h-10 rounded-full">
                    <div>
                        <p class="text-sm font-medium text-gray-300"><?php echo htmlspecialchars($_SESSION['user']['nama'], ENT_QUOTES, 'UTF-8'); ?></p>
                        <p class="text-xs text-gray-500"><?php echo $_SESSION['user']['level']; ?></p>
                    </div>
                </div>
            </div>
        </aside>

        <!-- Main Content -->
        <div class="sm:ml-64">
            <!-- Top Navigation -->
            <nav class="bg-white shadow-sm">
                <div class="mx-auto px-4">
                    <div class="flex justify-between h-16">
                        <div class="flex items-center">
                            <button class="sm:hidden p-2 rounded-md hover:bg-gray-100" onclick="toggleSidebar()">
                                <i class="fas fa-bars"></i>
                            </button>
                        </div>
                        
                        <div class="flex items-center">
                            <div class="relative">
                                <input type="text" placeholder="Cari..." class="w-64 px-4 py-2 rounded-lg border border-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <button class="absolute right-3 top-2.5 text-gray-400 hover:text-gray-600">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </nav>

            <!-- Page Content -->
            <main class="p-6">
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <?php
                    $page = isset($_GET['page']) ? trim(htmlspecialchars($_GET['page'])) : 'home';
                    $allowed_pages = ['home', 'kategori', 'kategori_tambah', 'kategori_ubah', 'kategori_hapus', 'buku', 'buku_tambah', 'buku_ubah', 'buku_hapus', 'peminjaman', 'peminjaman_tambah', 'peminjaman_ubah', 'peminjaman_hapus', 'ulasan', 'ulasan_tambah', 'ulasan_ubah', 'ulasan_hapus', 'laporan', 'cetak', 'total_user'];
                    if (in_array($page, $allowed_pages) && file_exists($page . '.php')) {
                        include $page . '.php';
                    } else {
                        switch($page) {
                            case 'total_user':
                                include 'total_user.php';
                                break;

                            case 'register_admin':
                                include 'register_admin.php';
                                break;
                            
                            default:
                                include '404.php';
                                break;
                        }
                    }
                    ?>
                </div>
            </main>

            <!-- Footer -->
            <footer class="bg-white shadow-sm mt-8">
                <div class="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between items-center">
                        <p class="text-sm text-gray-500">Copyright © Deka Endah Pasha 2025</p>
                        <div class="flex space-x-4">
                            <a href="#" class="text-sm text-gray-500 hover:text-gray-900">Privacy Policy</a>
                            <a href="#" class="text-sm text-gray-500 hover:text-gray-900">Terms & Conditions</a>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    </div>

    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('-translate-x-full');
        }
    </script>
</body>
</html>