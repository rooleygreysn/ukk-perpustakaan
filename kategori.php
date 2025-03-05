<div class="bg-gray-100 p-6 rounded-lg shadow-sm">
    <h1 class="text-2xl font-semibold text-gray-800 mb-6">Kategori Buku</h1>
    
    <div class="mb-4">
        <a href="index.php?page=kategori_tambah" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors duration-200">
            <i class="fas fa-plus mr-2"></i>
            Tambah Data
        </a>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full bg-gray-50 rounded-lg overflow-hidden">
            <thead class="bg-gray-200">
                <tr>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600">No</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600">Nama Kategori</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                <?php
                $i = 1;
                $query = mysqli_query($koneksi, "SELECT*FROM kategori");
                while($data = mysqli_fetch_array($query)){
                ?>
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 text-sm text-gray-700"><?php echo $i++; ?></td>
                    <td class="px-6 py-4 text-sm text-gray-700"><?php echo $data['kategori']; ?></td>
                    <td class="px-6 py-4 text-sm space-x-2">
                        <a href="?page=kategori_ubah&&id=<?php echo $data['id_kategori'];?>" 
                           class="inline-flex items-center px-3 py-1.5 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors duration-200">
                            <i class="fas fa-edit mr-2"></i>
                            Ubah
                        </a>
                        <a onclick="return confirm('Apakah anda yakin menghapus data ini?')" 
                           href="?page=kategori_hapus&&id=<?php echo $data['id_kategori']; ?>"
                           class="inline-flex items-center px-3 py-1.5 bg-red-500 text-white rounded-lg hover:bg-red-600 transition-colors duration-200">
                            <i class="fas fa-trash mr-2"></i>
                            Hapus
                        </a>
                    </td>
                </tr>
                <?php
                }
                ?>
            </tbody>
        </table>
    </div>
</div>