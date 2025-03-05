<?php
// Memastikan ada koneksi ke database
include 'koneksi.php';

// Query untuk menghitung total user berdasarkan level
$query_admin = mysqli_query($koneksi, "SELECT COUNT(*) as total_admin FROM user WHERE level='admin'");
$query_petugas = mysqli_query($koneksi, "SELECT COUNT(*) as total_petugas FROM user WHERE level='petugas'");
$query_peminjam = mysqli_query($koneksi, "SELECT COUNT(*) as total_peminjam FROM user WHERE level='peminjam'");

// Mengambil hasil query
$admin = mysqli_fetch_assoc($query_admin);
$petugas = mysqli_fetch_assoc($query_petugas);
$peminjam = mysqli_fetch_assoc($query_peminjam);

// Total semua user
$total_user = $admin['total_admin'] + $petugas['total_petugas'] + $peminjam['total_peminjam'];

// Query untuk mengambil semua data user
$query_users = mysqli_query($koneksi, "SELECT * FROM user ORDER BY level");
?>

<!-- Cards Section -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
    <div class="bg-white rounded-lg shadow p-6 border-l-4 border-blue-500">
        <div class="flex items-center">
            <div class="flex-1">
                <h3 class="text-lg font-semibold text-gray-700">Total Admin</h3>
                <p class="text-2xl font-bold text-blue-600"><?php echo $admin['total_admin']; ?></p>
            </div>
            <div class="text-blue-500">
                <i class="fas fa-user-shield text-3xl"></i>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-6 border-l-4 border-green-500">
        <div class="flex items-center">
            <div class="flex-1">
                <h3 class="text-lg font-semibold text-gray-700">Total Petugas</h3>
                <p class="text-2xl font-bold text-green-600"><?php echo $petugas['total_petugas']; ?></p>
            </div>
            <div class="text-green-500">
                <i class="fas fa-user-tie text-3xl"></i>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-6 border-l-4 border-purple-500">
        <div class="flex items-center">
            <div class="flex-1">
                <h3 class="text-lg font-semibold text-gray-700">Total Peminjam</h3>
                <p class="text-2xl font-bold text-purple-600"><?php echo $peminjam['total_peminjam']; ?></p>
            </div>
            <div class="text-purple-500">
                <i class="fas fa-users text-3xl"></i>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-6 border-l-4 border-yellow-500">
        <div class="flex items-center">
            <div class="flex-1">
                <h3 class="text-lg font-semibold text-gray-700">Total User</h3>
                <p class="text-2xl font-bold text-yellow-600"><?php echo $total_user; ?></p>
            </div>
            <div class="text-yellow-500">
                <i class="fas fa-user-friends text-3xl"></i>
            </div>
        </div>
    </div>
</div>

<!-- Table Section -->
<div class="bg-white rounded-lg shadow">
    <div class="p-6">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-semibold text-gray-800">Daftar User</h2>
            <?php if($_SESSION['user']['level'] == 'admin'): ?>
                <a href="?page=register_admin" class="inline-flex items-center bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg transition-colors duration-200">
                    <i class="fas fa-user-plus mr-2"></i>
                    Registrasi User
                </a>
            <?php endif; ?>
        </div>

        <!-- Tab buttons -->
        <div class="flex space-x-4 mb-4">
            <button onclick="showTable('admin')" class="px-4 py-2 text-sm font-medium rounded-lg bg-blue-100 text-blue-800 hover:bg-blue-200">Admin</button>
            <button onclick="showTable('petugas')" class="px-4 py-2 text-sm font-medium rounded-lg bg-green-100 text-green-800 hover:bg-green-200">Petugas</button>
            <button onclick="showTable('peminjam')" class="px-4 py-2 text-sm font-medium rounded-lg bg-purple-100 text-purple-800 hover:bg-purple-200">Peminjam</button>
        </div>

        <?php
        $roles = ['admin', 'petugas', 'peminjam'];
        foreach($roles as $role):
        ?>
        <div id="table-<?php echo $role; ?>" class="role-table hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Username</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Lengkap</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Level</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php 
                        $no = 1;
                        mysqli_data_seek($query_users, 0);
                        while($user = mysqli_fetch_assoc($query_users)) {
                            if($user['level'] == $role):
                        ?>
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?php echo $no++; ?></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"><?php echo htmlspecialchars($user['username']); ?></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?php echo htmlspecialchars($user['email']); ?></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?php echo htmlspecialchars($user['nama']); ?></td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    <?php 
                                    switch($role) {
                                        case 'admin':
                                            echo 'bg-blue-100 text-blue-800';
                                            break;
                                        case 'petugas':
                                            echo 'bg-green-100 text-green-800';
                                            break;
                                        default:
                                            echo 'bg-purple-100 text-purple-800';
                                    }
                                    ?>">
                                    <?php echo ucfirst($role); ?>
                                </span>
                            </td>
                        </tr>
                        <?php 
                            endif;
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>

<!-- Tambahkan script JavaScript -->
<script>
function showTable(role) {
    // Sembunyikan semua tabel
    document.querySelectorAll('.role-table').forEach(table => {
        table.classList.add('hidden');
    });
    
    // Tampilkan tabel yang dipilih
    document.getElementById('table-' + role).classList.remove('hidden');
}

// Tampilkan tabel admin saat halaman dimuat
document.addEventListener('DOMContentLoaded', function() {
    showTable('admin');
});
</script>
