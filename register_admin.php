<?php
// Cek apakah user adalah admin
if($_SESSION['user']['level'] != 'admin') {
    echo '<script>
        alert("Anda tidak memiliki akses ke halaman ini!");
        window.location.href="?page=total_user";
    </script>';
    exit;
}
?>

<div class="container mx-auto px-4">
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-2xl font-semibold text-gray-800 mb-4">Tambah User Baru</h2>
        <form method="post">
            <div class="grid grid-cols-1 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Username</label>
                    <input type="text" name="username" class="mt-1 block w-full rounded-md border-2 border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700">Password</label>
                    <input type="password" name="password" class="mt-1 block w-full rounded-md border-2 border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700">Email</label>
                    <input type="email" name="email" class="mt-1 block w-full rounded-md border-2 border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700">Nama Lengkap</label>
                    <input type="text" name="nama_lengkap" class="mt-1 block w-full rounded-md border-2 border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700">No. Telepon</label>
                    <input type="text" name="no_telepon" class="mt-1 block w-full rounded-md border-2 border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700">Alamat</label>
                    <textarea name="alamat" rows="3" class="mt-1 block w-full rounded-md border-2 border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required></textarea>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700">Level</label>
                    <select name="level" class="mt-1 block w-full rounded-md border-2 border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                        <option value="admin">Admin</option>
                        <option value="petugas">Petugas</option>
                        <option value="peminjam">Peminjam</option>
                    </select>
                </div>
                
                <div class="flex justify-end space-x-3">
                    <a href="?page=total_user" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition-colors duration-200">Batal</a>
                    <button type="submit" name="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg transition-colors duration-200">Tambah User</button>
                </div>
            </div>
        </form>
    </div>
</div>

<?php
if(isset($_POST['submit'])) {
    $username = $_POST['username'];
    $password = md5($_POST['password']);
    $email = $_POST['email'];
    $nama_lengkap = $_POST['nama_lengkap'];
    $no_telepon = $_POST['no_telepon'];
    $alamat = $_POST['alamat'];
    $level = $_POST['level'];
    
    $cek = mysqli_query($koneksi, "SELECT * FROM user WHERE username='$username'");
    $cek_email = mysqli_query($koneksi, "SELECT * FROM user WHERE email='$email'");
    
    if(mysqli_num_rows($cek) > 0) {
        echo '<script>alert("Username sudah digunakan!");</script>';
    } else if(mysqli_num_rows($cek_email) > 0) {
        echo '<script>alert("Email sudah digunakan!");</script>';
    } else {
        $query = mysqli_query($koneksi, "INSERT INTO user(username, password, email, nama, no_telepon, alamat, level) 
            VALUES('$username', '$password', '$email', '$nama_lengkap', '$no_telepon', '$alamat', '$level')");
        
        if($query) {
            echo '<script>alert("Pendaftaran berhasil!"); window.location.href="?page=total_user";</script>';
        } else {
            echo '<script>alert("Pendaftaran gagal!");</script>';
        }
    }
}
?>