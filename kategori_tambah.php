<div class="bg-white p-6 rounded-lg shadow-sm">
    <h1 class="text-2xl font-semibold text-gray-800 mb-6">Tambah Kategori Buku</h1>

    <?php
    if(isset($_POST['submit'])) {
        $kategori = $_POST['kategori'];
        
        // Cek apakah kategori sudah ada
        $cek_kategori = mysqli_query($koneksi, "SELECT * FROM kategori WHERE kategori = '$kategori'");
        
        if(mysqli_num_rows($cek_kategori) > 0) {
            echo '<div class="mb-4 p-4 bg-red-100 text-red-700 rounded-lg">Kategori sudah ada!</div>';
        } else {
            $query = mysqli_query($koneksi, "INSERT INTO kategori(kategori) values('$kategori')");

            if($query) {
                echo '<div class="mb-4 p-4 bg-green-100 text-green-700 rounded-lg">Data berhasil ditambahkan!</div>';
            } else {
                echo '<div class="mb-4 p-4 bg-red-100 text-red-700 rounded-lg">Gagal menambahkan data!</div>';
            }
        }
    }
    ?>

    <form method="post" class="space-y-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-center">
            <label class="text-gray-700 font-medium">Nama Kategori</label>
            <div class="md:col-span-3">
                <input type="text" name="kategori" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div></div>
            <div class="md:col-span-3 space-x-2">
                <button type="submit" name="submit" value="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors duration-200">
                    <i class="fas fa-save mr-2"></i>Simpan
                </button>
                <button type="reset" class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition-colors duration-200">
                    <i class="fas fa-undo mr-2"></i>Reset
                </button>
                <a href="?page=kategori" class="inline-block px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition-colors duration-200">
                    <i class="fas fa-arrow-left mr-2"></i>Kembali
                </a>
            </div>
        </div>
    </form>
</div>
