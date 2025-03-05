<div class="max-w-7xl mx-auto">
    <div class="flex items-center justify-between mb-8">
        <h1 class="text-2xl font-bold text-gray-900">Tambah Buku</h1>
        <a href="?page=buku" class="flex items-center text-gray-600 hover:text-gray-900">
            <i class="fas fa-arrow-left mr-2"></i>
            Kembali ke Daftar Buku
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
        <div class="p-8">
            <form method="post" enctype="multipart/form-data" class="space-y-8">
                <?php
                    if(isset($_POST['submit'])) {
                        $id_kategori = $_POST['id_kategori'];
                        $judul = $_POST['judul'];
                        $penulis = $_POST['penulis'];
                        $penerbit = $_POST['penerbit'];
                        $tahun_terbit = $_POST['tahun_terbit'];
                        $deskripsi = $_POST['deskripsi'];
                        
                        // Cek duplikasi judul
                        $check_query = "SELECT judul FROM buku WHERE judul = ?";
                        $check_stmt = mysqli_prepare($koneksi, $check_query);
                        mysqli_stmt_bind_param($check_stmt, "s", $judul);
                        mysqli_stmt_execute($check_stmt);
                        mysqli_stmt_store_result($check_stmt);

                        if(mysqli_stmt_num_rows($check_stmt) > 0) {
                            echo "<script>
                                    alert('Gagal menambahkan buku. Judul buku sudah ada!');
                                    window.location.href='index.php?page=buku';
                                  </script>";
                            exit();
                        }
                        mysqli_stmt_close($check_stmt);

                        // Proses upload gambar
                        $nama_file = '';
                        if(isset($_FILES['cover']) && $_FILES['cover']['error'] == 0) {
                            $tipe_file = $_FILES['cover']['type'];
                            $nama_asli = $_FILES['cover']['name'];
                            $tmp_file = $_FILES['cover']['tmp_name'];
                            
                            // Cek tipe file
                            $allowed_types = array('image/jpeg', 'image/jpg', 'image/png');
                            if(!in_array($tipe_file, $allowed_types)) {
                                echo '<script>alert("Tipe file tidak diizinkan. Hanya file JPG, JPEG, dan PNG yang diperbolehkan.");</script>';
                                return;
                            }
                            
                            // Generate nama file baru
                            $nama_file = time() . '_' . $nama_asli;
                            
                            // Upload file
                            $upload_path = 'assets/uploads/';
                            if(!is_dir($upload_path)) {
                                mkdir($upload_path, 0777, true);
                            }
                            
                            if(move_uploaded_file($tmp_file, $upload_path . $nama_file)) {
                                $stok = $_POST['stok'];
                                $query = mysqli_query($koneksi, "INSERT INTO buku(id_kategori, judul, penulis, penerbit, tahun_terbit, deskripsi, stok, gambar) 
                                    VALUES ('$id_kategori', '$judul', '$penulis', '$penerbit', '$tahun_terbit', '$deskripsi', '$stok', '$nama_file')");
                                
                                if($query) {
                                    echo '<script>alert("Tambah data berhasil."); window.location.href="?page=buku";</script>';
                                } else {
                                    echo '<script>alert("Tambah data gagal.");</script>';
                                    unlink($upload_path . $nama_file);
                                }
                            } else {
                                echo '<script>alert("Upload file gagal");</script>';
                            }
                        } else {
                            echo '<script>alert("Pilih file gambar terlebih dahulu");</script>';
                        }
                    }
                ?>
                
                <div class="space-y-6">
                    <!-- Kategori -->
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 items-start">
                        <label class="block text-sm font-medium text-gray-700 md:text-right md:mt-3">
                            Kategori Buku
                        </label>
                        <div class="md:col-span-3">
                            <select name="id_kategori" class="w-full border-2 p-2 rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <?php
                                $kat = mysqli_query($koneksi, "SELECT * FROM kategori");
                                while($kategori = mysqli_fetch_array($kat)) {
                                    echo "<option value='{$kategori['id_kategori']}'>{$kategori['kategori']}</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>

                    <!-- Judul -->
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 items-start">
                        <label class="block text-sm font-medium text-gray-700 md:text-right md:mt-3">
                            Judul Buku
                        </label>
                        <div class="md:col-span-3">
                            <input type="text" name="judul" required 
                                   class="w-full rounded-lg border-2 p-2 border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        </div>
                    </div>

                    <!-- Penulis -->
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 items-start">
                        <label class="block text-sm font-medium text-gray-700 md:text-right md:mt-3">
                            Penulis
                        </label>
                        <div class="md:col-span-3">
                            <input type="text" name="penulis" class="border-2 p-2 w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        </div>
                    </div>

                    <!-- Penerbit -->
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 items-start">
                        <label class="block text-sm font-medium text-gray-700 md:text-right md:mt-3">
                            Penerbit
                        </label>
                        <div class="md:col-span-3">
                            <input type="text" name="penerbit" class="w-full border-2 p-2 rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        </div>
                    </div>

                    <!-- Tahun Terbit -->
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 items-start">
                        <label class="block text-sm font-medium text-gray-700 md:text-right md:mt-3">
                            Tahun Terbit
                        </label>
                        <div class="md:col-span-3">
                            <input type="number" name="tahun_terbit" class="w-full border-2 p-2 rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        </div>
                    </div>

                    <!-- Deskripsi -->
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 items-start">
                        <label class="block text-sm font-medium text-gray-700 md:text-right md:mt-3">
                            Deskripsi
                        </label>
                        <div class="md:col-span-3">
                            <textarea name="deskripsi" rows="5" class="w-full border-2 p-2 rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"></textarea>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 items-start">
                        <label class="block text-sm font-medium text-gray-700 md:text-right md:mt-3">
                            Stok Buku
                        </label>
                     <div class="md:col-span-3">
        <input type="number" min="0" name="stok" class="w-full border-2 p-2 rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
    </div>
</div>

                    <!-- Cover Buku -->
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 items-start">
                        <label class="block text-sm font-medium text-gray-700 md:text-right md:mt-3">
                            Cover Buku
                        </label>
                        <div class="md:col-span-3">
                            <div class="flex items-center justify-center w-full">
                                <label class="flex flex-col w-full h-32 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer hover:bg-gray-50">
                                    <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                        <i class="fas fa-cloud-upload-alt text-gray-400 text-3xl"></i>
                                        <p class="mt-1 text-sm text-gray-500">
                                            Klik untuk mengunggah gambar
                                        </p>
                                    </div>
                                    <input type="file" name="cover" accept="image/*" required id="coverInput" onchange="previewImage(this)" 
                                           class="hidden w-full h-full">
                                </label>
                            </div>
                            <div class="mt-4">
                                <img id="coverPreview" class="hidden max-w-xs rounded-lg shadow-sm" src="#" alt="Preview Cover">
                            </div>
                        </div>
                    </div>

                    <!-- Buttons -->
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 items-start">
                        <div></div>
                        <div class="md:col-span-3 flex space-x-3">
                            <button type="submit" name="submit" value="submit" 
                                    class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                                Simpan
                            </button>
                            <button type="reset" 
                                    class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">
                                Reset
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
function previewImage(input) {
    const preview = document.getElementById('coverPreview');
    const file = input.files[0];
    
    if (file) {
        const reader = new FileReader();
        
        reader.onload = function(e) {
            preview.src = e.target.result;
            preview.classList.remove('hidden');
        }
        
        reader.readAsDataURL(file);
    } else {
        preview.src = '#';
        preview.classList.add('hidden');
    }
}
</script>