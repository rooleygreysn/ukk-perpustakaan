<div class="px-6 py-4">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-semibold text-gray-800">Daftar Buku</h1>
        <a href="index.php?page=buku_tambah" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors duration-200">
            <i class="fas fa-plus mr-2"></i>
            Tambah Buku
        </a>
    </div>
    
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-6">
        <?php
        $query = mysqli_query($koneksi, "SELECT * FROM buku LEFT JOIN kategori ON buku.id_kategori = kategori.id_kategori");
        while($data = mysqli_fetch_array($query)){
        ?>
        <div class="bg-white rounded-lg shadow-sm hover:shadow-md transition-shadow duration-200">
            <div class="relative" style="aspect-ratio: 3/4;">
                <?php if(!empty($data['gambar'])) { ?>
                    <div class="w-full h-full overflow-hidden rounded-t-lg">
                        <img src="assets/uploads/<?php echo $data['gambar']; ?>" 
                             class="w-full h-full object-cover"
                             style="object-position: center center;"
                             alt="Cover <?php echo htmlspecialchars($data['judul']); ?>">
                    </div>
                <?php } else { ?>
                    <div class="w-full h-full flex flex-col items-center justify-center bg-gray-50 rounded-t-lg">
                        <i class="fas fa-book text-4xl text-gray-400"></i>
                        <p class="mt-2 text-sm text-gray-500">Tidak ada cover</p>
                    </div>
                <?php } ?>
            </div>
            
            <div class="p-4">
                <h3 class="text-lg font-semibold text-gray-800 line-clamp-2 mb-2">
                    <?php echo htmlspecialchars($data['judul']); ?>
                </h3>
                <p class="text-sm text-gray-600 mb-2">
                    <i class="fas fa-user mr-2"></i>
                    <?php echo htmlspecialchars($data['penulis']); ?>
                </p>
                <p class="text-sm text-gray-600 mb-4">
                    <i class="fas fa-tag mr-2"></i>
                    <?php echo htmlspecialchars($data['kategori']); ?>
                </p>
                <p class="text-sm text-gray-600 mb-2">
                    <i class="fas fa-layer-group mr-2"></i>
                    Stok: <?php echo $data['stok']; ?>
                </p>
                <div class="text-sm text-gray-600 mb-4">
                    <p class="mb-1">
                        <i class="fas fa-align-left mr-2"></i>
                        Deskripsi:
                    </p>
                    <?php 
                    $paragraphs = explode("\n", $data['deskripsi']);
                    foreach($paragraphs as $paragraph) {
                        if(trim($paragraph) !== '') {
                            echo "<p class='text-justify'>" . htmlspecialchars($paragraph) . "</p>";
                        }
                    }
                    ?>
                </div>
                <div class="flex justify-end space-x-2">
                    <a href="?page=buku_ubah&&id=<?php echo $data['id_buku']; ?>" 
                       class="px-3 py-1.5 bg-blue-500 text-white text-sm rounded hover:bg-blue-600 transition-colors duration-200">
                        <i class="fas fa-edit"></i>
                    </a>
                    <a onclick="return confirm('Apakah anda yakin menghapus data ini?')" 
                       href="?page=buku_hapus&&id=<?php echo $data['id_buku']; ?>" 
                       class="px-3 py-1.5 bg-red-500 text-white text-sm rounded hover:bg-red-600 transition-colors duration-200">
                        <i class="fas fa-trash"></i>
                    </a>
                </div>
            </div>
        </div>
        <?php } ?>
    </div>
</div>
</div>