<div class="bg-white p-6 rounded-lg shadow-sm">
    <h1 class="text-2xl font-semibold text-gray-800 mb-6">Ulasan Buku</h1>

    <?php
    // Validasi ID ulasan dari URL
    if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
        echo '<script>alert("ID ulasan tidak valid!"); window.location.href="?page=ulasan";</script>';
        exit;
    }

    $id = (int) $_GET['id'];

    // Mengambil data ulasan berdasarkan ID
    $query = mysqli_query($koneksi, "SELECT * FROM ulasan WHERE id_ulasan = $id");
    $data = mysqli_fetch_array($query);

    if (!$data) {
        echo '<script>alert("Data tidak ditemukan!"); window.location.href="?page=ulasan";</script>';
        exit;
    }

    if (isset($_POST['submit'])) {
        $id_buku = mysqli_real_escape_string($koneksi, $_POST['id_buku']);
        $ulasan = mysqli_real_escape_string($koneksi, $_POST['ulasan']);
        $rating = (int) $_POST['rating']; // Pastikan rating berupa angka

        $update = mysqli_query($koneksi, "UPDATE ulasan SET id_buku='$id_buku', ulasan='$ulasan', rating='$rating' WHERE id_ulasan=$id");

        if ($update) {
            echo '<div class="mb-4 p-4 rounded-lg bg-green-100 text-green-700">Ubah data berhasil.</div>';
        } else {
            echo '<div class="mb-4 p-4 rounded-lg bg-red-100 text-red-700">Ubah data gagal.</div>';
        }
    }
    ?>

    <form method="post" class="space-y-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-center">
            <label class="block text-sm font-medium text-gray-700">Buku</label>
            <div class="md:col-span-2">
                <select name="id_buku" class="mt-1 block w-full rounded-md border-2 p-2 border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                    <option value="">-- Pilih Buku --</option>
                    <?php
                    $buk = mysqli_query($koneksi, "SELECT * FROM buku");
                    while ($buku = mysqli_fetch_array($buk)) {
                        $selected = ($data['id_buku'] == $buku['id_buku']) ? 'selected' : '';
                        echo '<option value="' . $buku['id_buku'] . '" ' . $selected . '>' . htmlspecialchars($buku['judul']) . '</option>';
                    }
                    ?>
                </select>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-start">
            <label class="block text-sm font-medium text-gray-700">Ulasan</label>
            <div class="md:col-span-2">
                <textarea name="ulasan" rows="5" class="mt-1 p-2 border-2 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required><?= htmlspecialchars($data['ulasan']); ?></textarea>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-center">
            <label class="block text-sm font-medium text-gray-700">Rating</label>
            <div class="md:col-span-2">
                <select name="rating" class="mt-1 block w-full rounded-md border-2 p-2 border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                    <?php
                    for ($i = 1; $i <= 10; $i++) {
                        $selected = ($data['rating'] == $i) ? 'selected' : '';
                        echo '<option value="' . $i . '" ' . $selected . '>' . $i . ' Bintang</option>';
                    }
                    ?>
                </select>
            </div>
        </div>

        <div class="flex justify-end space-x-3">
            <a href="?page=ulasan" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-md transition-colors duration-200">
                Kembali
            </a>
            <button type="reset" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-md transition-colors duration-200">
                Reset
            </button>
            <button type="submit" name="submit" value="submit" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-md transition-colors duration-200">
                Simpan
            </button>
        </div>
    </form>
</div>
