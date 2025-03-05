<div class="space-y-6">
    <!-- Header -->
    <div>
        <h1 class="text-2xl font-semibold text-gray-800">Dashboard</h1>
        <nav class="flex" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <span class="text-sm font-medium text-gray-500">Dashboard</span>
                </li>
            </ol>
        </nav>
    </div>

    <!-- Stat Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Kategori Card -->
        <div class="bg-blue-500 rounded-lg shadow-sm">
            <div class="p-4">
                <div class="flex items-center">
                    <div class="flex-1">
                        <div class="text-3xl font-bold text-white">
                            <?php echo mysqli_num_rows(mysqli_query($koneksi, "SELECT*FROM kategori")); ?>
                        </div>
                        <div class="text-white">Total Kategori</div>
                    </div>
                    <div class="text-white">
                        <i class="fas fa-table text-3xl"></i>
                    </div>
                </div>
            </div>
            <div class="px-4 py-3 bg-blue-600 rounded-b-lg">
                <a href="?page=kategori" class="flex justify-between items-center text-white hover:text-blue-100">
                    <span class="text-sm">Lihat Detail</span>
                    <i class="fas fa-arrow-right"></i>
                </a>
            </div>
        </div>

        <!-- Buku Card -->
        <div class="bg-yellow-500 rounded-lg shadow-sm">
            <div class="p-4">
                <div class="flex items-center">
                    <div class="flex-1">
                        <div class="text-3xl font-bold text-white">
                            <?php echo mysqli_num_rows(mysqli_query($koneksi, "SELECT*FROM buku")); ?>
                        </div>
                        <div class="text-white">Total Buku</div>
                    </div>
                    <div class="text-white">
                        <i class="fas fa-book text-3xl"></i>
                    </div>
                </div>
            </div>
            <div class="px-4 py-3 bg-yellow-600 rounded-b-lg">
                <a href="?page=buku" class="flex justify-between items-center text-white hover:text-yellow-100">
                    <span class="text-sm">Lihat Detail</span>
                    <i class="fas fa-arrow-right"></i>
                </a>
            </div>
        </div>

        <!-- Ulasan Card -->
        <div class="bg-green-500 rounded-lg shadow-sm">
            <div class="p-4">
                <div class="flex items-center">
                    <div class="flex-1">
                        <div class="text-3xl font-bold text-white">
                            <?php echo mysqli_num_rows(mysqli_query($koneksi, "SELECT*FROM ulasan")); ?>
                        </div>
                        <div class="text-white">Total Ulasan</div>
                    </div>
                    <div class="text-white">
                        <i class="fas fa-comment text-3xl"></i>
                    </div>
                </div>
            </div>
            <div class="px-4 py-3 bg-green-600 rounded-b-lg">
                <a href="?page=ulasan" class="flex justify-between items-center text-white hover:text-green-100">
                    <span class="text-sm">Lihat Detail</span>
                    <i class="fas fa-arrow-right"></i>
                </a>
            </div>
        </div>

        <!-- User Card -->
        <?php if ($_SESSION['user']['level'] != 'peminjam'): ?>
            <div class="bg-red-500 rounded-lg shadow-sm">
                <div class="p-4">
                    <div class="flex items-center">
                        <div class="flex-1">
                            <div class="text-3xl font-bold text-white">
                                <?php echo mysqli_num_rows(mysqli_query($koneksi, "SELECT*FROM user")); ?>
                            </div>
                            <div class="text-white">Total User</div>
                        </div>
                        <div class="text-white">
                            <i class="fas fa-users text-3xl"></i>
                        </div>
                    </div>
                </div>
                <div class="px-4 py-3 bg-red-600 rounded-b-lg">
                    <a href="?page=total_user" class="flex justify-between items-center text-white hover:text-red-100">
                        <span class="text-sm">Lihat Detail</span>
                        <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <!-- User Info Card -->
    <div class="bg-white rounded-lg shadow-sm">
        <div class="p-6">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">Informasi Pengguna</h2>
            <div class="space-y-4">
                <div class="flex border-b border-gray-200 pb-3">
                    <div class="w-48 text-gray-600">Nama</div>
                    <div class="w-4 text-gray-600">:</div>
                    <div class="flex-1 text-gray-900"><?php echo $_SESSION['user']['nama']; ?></div>
                </div>
                <div class="flex border-b border-gray-200 pb-3">
                    <div class="w-48 text-gray-600">Level User</div>
                    <div class="w-4 text-gray-600">:</div>
                    <div class="flex-1 text-gray-900"><?php echo $_SESSION['user']['level']; ?></div>
                </div>
                <div class="flex border-b border-gray-200 pb-3">
                    <div class="w-48 text-gray-600">Tanggal Login</div>
                    <div class="w-4 text-gray-600">:</div>
                    <div class="flex-1 text-gray-900"><?php echo date('d-m-y'); ?></div>
                </div>
            </div>
        </div>
    </div>
</div>
                        