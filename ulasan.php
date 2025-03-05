<div class="bg-white p-6 rounded-lg shadow-sm">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-semibold text-gray-800">Ulasan Buku</h1>
        <a href="index.php?page=ulasan_tambah" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors duration-200">
            <i class="fas fa-plus mr-2"></i>
            Tambah Ulasan
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <?php
        $query = mysqli_query($koneksi, "SELECT ulasan.*, user.nama, buku.judul, buku.gambar, buku.penulis 
            FROM ulasan 
            LEFT JOIN user ON user.id_user = ulasan.id_user 
            LEFT JOIN buku ON buku.id_buku = ulasan.id_buku");
        while($data = mysqli_fetch_array($query)){
        ?>
            <div class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300">
                <div class="relative h-48">
                    <img class="w-full h-full object-cover" 
                         src="assets/uploads/<?php echo $data['gambar']; ?>" 
                         alt="<?php echo $data['judul']; ?>">
                    <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/70 to-transparent p-4">
                        <h3 class="text-white text-xl font-semibold"><?php echo $data['judul']; ?></h3>
                        <p class="text-white/80 text-sm"><?php echo $data['penulis']; ?></p>
                    </div>
                </div>

                <div class="p-6">
                <div class="flex items-center space-x-3">
                    <img src="https://ui-avatars.com/api/?name=<?php echo urlencode($data['nama']); ?>" class="w-10 h-10 rounded-full">
                    <div>
                        </div>
                        <div>
                            <p class="font-medium text-gray-900"><?php echo $data['nama']; ?></p>
                            <div class="flex items-center">
                                <?php
                                for($j = 1; $j <= 5; $j++) {
                                    if($j <= $data['rating']) {
                                        echo '<i class="fas fa-star text-yellow-400"></i>';
                                    } else {
                                        echo '<i class="far fa-star text-gray-300"></i>';
                                    }
                                }
                                ?>
                            </div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <p class="text-gray-600 whitespace-pre-wrap break-words"><?php echo nl2br($data['ulasan']); ?></p>
                    </div>

                    <div class="flex space-x-2 mt-4">
                        <a href="?page=ulasan_ubah&&id=<?php echo $data['id_ulasan'];?>" 
                           class="flex-1 inline-flex items-center justify-center px-4 py-2 bg-indigo-50 text-indigo-700 rounded-lg hover:bg-indigo-100 transition-colors duration-200">
                            <i class="fas fa-edit mr-2"></i>
                            Ubah
                        </a>
                        <a onclick="return confirm('Apakah anda yakin menghapus data ini?')" 
                           href="?page=ulasan_hapus&&id=<?php echo $data['id_ulasan']; ?>"
                           class="flex-1 inline-flex items-center justify-center px-4 py-2 bg-red-50 text-red-700 rounded-lg hover:bg-red-100 transition-colors duration-200">
                            <i class="fas fa-trash-alt mr-2"></i>
                            Hapus
                        </a>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>
</div>