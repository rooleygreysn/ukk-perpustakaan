<div class="bg-white rounded-lg shadow-sm p-6">
    <h1 class="text-2xl font-semibold text-gray-800 mb-6">Edit Buku</h1>

    <?php
        $id = $_GET['id'];
        if(isset($_POST['submit'])) {
            $id_kategori = $_POST['id_kategori'];
            $judul = $_POST['judul'];
            $penulis = $_POST['penulis'];
            $penerbit = $_POST['penerbit'];
            $tahun_terbit = $_POST['tahun_terbit'];
            $deskripsi = $_POST['deskripsi'];
            $stok = $_POST['stok'];

            $query = mysqli_query($koneksi, 
                "UPDATE buku 
                SET id_kategori='$id_kategori', 
                    judul='$judul', 
                    penulis='$penulis', 
                    penerbit='$penerbit', 
                    tahun_terbit='$tahun_terbit', 
                    deskripsi='$deskripsi',
                    stok='$stok' 
                WHERE id_buku=$id"
            );

            if($query) {
                echo '<script>
                    alert("Ubah data berhasil.");
                    window.location.href="?page=buku";
                </script>';
            } else {
                echo '<script>alert("Ubah data gagal.");</script>';
            }
        }
        $query = mysqli_query($koneksi, "SELECT * FROM buku WHERE id_buku=$id");
        $data = mysqli_fetch_array($query);
    ?>

    <form method="post" class="space-y-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-center">
            <label class="text-sm font-medium text-gray-700">Kategori</label>
            <div class="md:col-span-2">
                <select name="id_kategori" class="w-full rounded-lg border-2 p-2 border-gray-300" required>
                    <?php
                    $kat = mysqli_query($koneksi, "SELECT * FROM kategori");
                    while($kategori = mysqli_fetch_array($kat)) {
                        $selected = ($kategori['id_kategori'] == $data['id_kategori']) ? 'selected' : '';
                        ?>
                        <option value="<?php echo $kategori['id_kategori']; ?>" <?php echo $selected; ?>>
                            <?php echo $kategori['kategori']; ?>
                        </option>
                        <?php
                    }
                    ?>
                </select>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-center">
            <label class="text-sm font-medium text-gray-700">Judul</label>
            <div class="md:col-span-2">
                <input type="text" name="judul" class="w-full rounded-lg border-2 p-2 border-gray-300" 
                       value="<?php echo htmlspecialchars($data['judul']); ?>" required>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-center">
            <label class="text-sm font-medium text-gray-700">Penulis</label>
            <div class="md:col-span-2">
                <input type="text" name="penulis" class="w-full rounded-lg border-2 p-2 border-gray-300" 
                       value="<?php echo htmlspecialchars($data['penulis']); ?>" required>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-center">
            <label class="text-sm font-medium text-gray-700">Penerbit</label>
            <div class="md:col-span-2">
                <input type="text" name="penerbit" class="w-full rounded-lg border-2 p-2 border-gray-300" 
                       value="<?php echo htmlspecialchars($data['penerbit']); ?>" required>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-center">
            <label class="text-sm font-medium text-gray-700">Tahun Terbit</label>
            <div class="md:col-span-2">
                <input type="text" name="tahun_terbit" class="w-full rounded-lg border-2 p-2 border-gray-300" 
                       value="<?php echo htmlspecialchars($data['tahun_terbit']); ?>" required>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-center">
            <label class="text-sm font-medium text-gray-700">Stok</label>
            <div class="md:col-span-2">
                <input type="number" name="stok" class="w-full rounded-lg border-2 p-2 border-gray-300" 
                       value="<?php echo htmlspecialchars($data['stok']); ?>" min="0" required>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-start">
            <label class="text-sm font-medium text-gray-700">Deskripsi</label>
            <div class="md:col-span-2">
                <textarea name="deskripsi" class="w-full rounded-lg border-2 p-2 border-gray-300" 
                          rows="4" required><?php echo htmlspecialchars($data['deskripsi']); ?></textarea>
            </div>
        </div>

        <div class="flex justify-end space-x-4">
            <button type="submit" name="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                Simpan
            </button>
            <a href="?page=buku" class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600">
                Kembali
            </a>
        </div>
    </form>
</div>
