<div class="bg-white p-6 rounded-lg shadow-sm">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-semibold text-gray-800">Peminjaman Buku</h1>
        <a href="?page=peminjaman_tambah" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors duration-200">
            <i class="fas fa-plus mr-2"></i>
            Tambah Peminjaman
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <?php
        $query = mysqli_query($koneksi, "
        SELECT peminjaman.*, user.nama, buku.judul, buku.gambar, buku.penulis 
        FROM peminjaman 
        LEFT JOIN user ON peminjaman.id_user = user.id_user 
        LEFT JOIN buku ON buku.id_buku = peminjaman.id_buku 
        WHERE peminjaman.id_user = " . intval($_SESSION['user']['id_user'])
        );
        
        while($data = mysqli_fetch_array($query)){
            $status_color = $data['status_peminjaman'] == 'dikembalikan' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800';
            $days_remaining = (strtotime($data['tanggal_pengembalian']) - time()) / (60 * 60 * 24);
            $denda_per_hari = 5000; // Denda Rp 1.000 per hari
            $today = strtotime(date('Y-m-d'));
            $return_date = strtotime($data['tanggal_pengembalian']);
            $days_late = 0;
            $total_denda = 0;

            if ($today > $return_date && $data['status_peminjaman'] != 'dikembalikan') {
                $days_late = ceil(($today - $return_date) / (60 * 60 * 24));
                $total_denda = $days_late * $denda_per_hari;
            }
        ?>
            <div class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300">
                <div class="relative">
                    <img class="h-48 w-full object-cover" 
                         src="assets/uploads/<?php echo $data['gambar']; ?>" 
                         alt="<?php echo $data['judul']; ?>">
                    <div class="absolute top-4 right-4">
                        <span class="px-3 py-1 rounded-full text-sm font-semibold <?php echo $status_color; ?>">
                            <?php echo $data['status_peminjaman']; ?>
                        </span>
                    </div>
                </div>
                
                <div class="p-6">
                    <h3 class="text-xl font-semibold text-gray-900 mb-2"><?php echo $data['judul']; ?></h3>
                    <div class="space-y-3">
                        <div class="flex items-center text-gray-600">
                            <i class="far fa-calendar-alt w-5"></i>
                            <span class="text-sm">Dipinjam: <?php echo date('d M Y', strtotime($data['tanggal_peminjaman'])); ?></span>
                        </div>
                        <div class="flex items-center text-gray-600">
                            <i class="far fa-calendar-check w-5"></i>
                            <span class="text-sm">Kembali: <?php echo date('d M Y', strtotime($data['tanggal_pengembalian'])); ?></span>
                        </div>
                        <?php if($days_remaining > 0 && $data['status_peminjaman'] != 'dikembalikan') { ?>
                            <div class="flex items-center <?php echo $days_remaining <= 3 ? 'text-red-600' : 'text-blue-600'; ?>">
                                <i class="far fa-clock w-5"></i>
                                <span class="text-sm font-medium">
                                    <?php echo ceil($days_remaining); ?> hari tersisa
                                </span>
                            </div>
                        <?php } ?>
                        <?php if($days_late > 0) { ?>
                            <div class="flex items-center text-red-600">
                                <i class="fas fa-exclamation-triangle w-5"></i>
                                <span class="text-sm font-medium">
                                    Telat <?php echo $days_late; ?> hari
                                </span>
                            </div>
                            <div class="flex items-center text-red-600">
                                <i class="fas fa-money-bill w-5"></i>
                                <span class="text-sm font-medium">
                                    Denda: Rp <?php echo number_format($total_denda, 0, ',', '.'); ?>
                                </span>
                            </div>
                        <?php } ?>
                    </div>

                    <?php if($data['status_peminjaman'] != 'dikembalikan') { ?>
                        <div class="mt-6 flex space-x-3">
                            <a href="?page=peminjaman_ubah&&id=<?php echo $data['id_peminjaman'];?>" 
                               class="flex-1 text-center py-2 bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200 transition-colors duration-200">
                                <i class="fas fa-edit mr-2"></i>Ubah
                            </a>
                        </div>
                    <?php } ?>
                </div>
            </div>
        <?php } ?>
    </div>
</div>