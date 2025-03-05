<div class="bg-white p-6 rounded-lg shadow-sm">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-semibold text-gray-800">
            <i class="fas fa-file-alt text-blue-500 mr-2"></i>
            Laporan Peminjaman Buku
        </h1>
        
        <!-- Filter Status Denda -->
        <div class="flex items-center space-x-4">
            <form method="get" class="flex items-center space-x-4">
                <input type="hidden" name="page" value="laporan">
                <select name="status_denda" class="border-2 border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:border-blue-500">
                    <option value="">Semua Status Denda</option>
                    <option value="lunas" <?php echo (isset($_GET['status_denda']) && $_GET['status_denda'] == 'lunas') ? 'selected' : ''; ?>>Lunas</option>
                    <option value="belum_lunas" <?php echo (isset($_GET['status_denda']) && $_GET['status_denda'] == 'belum_lunas') ? 'selected' : ''; ?>>Belum Lunas</option>
                </select>
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600">
                    <i class="fas fa-filter mr-2"></i>Filter
                </button>
            </form>
            <a href="cetak.php<?php echo isset($_GET['status_denda']) ? '?status_denda='.$_GET['status_denda'] : ''; ?>" target="_blank" 
               class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors duration-200">
                <i class="fas fa-print mr-2"></i>
                Cetak Data
            </a>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Buku</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal Peminjaman</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal Pengembalian</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Keterlambatan</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Denda</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status Denda</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status Peminjaman</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <?php
                $i = 1;
                $where_clause = "";
                
                // Filter berdasarkan status denda
                if(isset($_GET['status_denda']) && !empty($_GET['status_denda'])) {
                    if($_GET['status_denda'] == 'lunas') {
                        $where_clause = "AND (d.status_pembayaran = 'lunas')";
                    } elseif($_GET['status_denda'] == 'belum_lunas') {
                        $where_clause = "AND (d.status_pembayaran = 'belum_lunas' OR (p.status_peminjaman != 'dikembalikan' AND CURRENT_DATE > p.tanggal_pengembalian))";
                    }
                }

                $query = mysqli_query($koneksi, "SELECT p.*, u.nama, b.judul, 
                    d.jumlah_hari_terlambat, d.jumlah_denda, d.status_pembayaran,
                    CASE 
                        WHEN p.status_peminjaman != 'dikembalikan' AND CURRENT_DATE > p.tanggal_pengembalian 
                        THEN DATEDIFF(CURRENT_DATE, p.tanggal_pengembalian)
                        ELSE 0
                    END as hari_terlambat,
                    CASE 
                        WHEN p.status_peminjaman != 'dikembalikan' AND CURRENT_DATE > p.tanggal_pengembalian 
                        THEN DATEDIFF(CURRENT_DATE, p.tanggal_pengembalian) * 5000
                        ELSE 0
                    END as denda_berjalan
                    FROM peminjaman p
                    LEFT JOIN user u ON p.id_user = u.id_user 
                    LEFT JOIN buku b ON b.id_buku = p.id_buku
                    LEFT JOIN denda d ON d.id_peminjaman = p.id_peminjaman
                    WHERE 1=1 $where_clause
                    ORDER BY p.tanggal_peminjaman DESC");

                while($data = mysqli_fetch_array($query)){
                    $statusClass = $data['status_peminjaman'] == 'dikembalikan' ? 'text-green-600' : 'text-yellow-600';
                    $dendaClass = $data['status_pembayaran'] == 'lunas' ? 'text-green-600' : 'text-red-600';
                    $keterlambatan = $data['status_peminjaman'] == 'dikembalikan' ? 
                        ($data['jumlah_hari_terlambat'] ?? 0) : 
                        $data['hari_terlambat'];
                    
                    $denda = $data['status_peminjaman'] == 'dikembalikan' ? 
                        ($data['jumlah_denda'] ?? 0) : 
                        $data['denda_berjalan'];
                ?>
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?php echo $i++; ?></td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?php echo htmlspecialchars($data['nama']); ?></td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?php echo htmlspecialchars($data['judul']); ?></td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?php echo date('d/m/Y', strtotime($data['tanggal_peminjaman'])); ?></td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?php echo date('d/m/Y', strtotime($data['tanggal_pengembalian'])); ?></td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            <?php echo $keterlambatan > 0 ? $keterlambatan . ' hari' : '-'; ?>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            <?php echo $denda > 0 ? 'Rp ' . number_format($denda, 0, ',', '.') : '-'; ?>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm <?php echo $dendaClass; ?> font-medium">
                            <?php 
                            if ($denda > 0) {
                                if ($data['status_peminjaman'] == 'dikembalikan') {
                                    // Tambahkan form untuk update status denda
                                    ?>
                                    <form method="post" action="update_status_denda.php" class="inline-flex items-center">
                                        <input type="hidden" name="id_peminjaman" value="<?php echo $data['id_peminjaman']; ?>">
                                        <select name="status_pembayaran" 
                                                onchange="this.form.submit()" 
                                                class="rounded px-2 py-1 text-sm border 
                                                <?php echo $data['status_pembayaran'] == 'lunas' ? 'bg-green-100' : 'bg-red-100'; ?>">
                                            <option value="belum_lunas" <?php echo $data['status_pembayaran'] == 'belum_lunas' ? 'selected' : ''; ?>>
                                                Belum Lunas
                                            </option>
                                            <option value="lunas" <?php echo $data['status_pembayaran'] == 'lunas' ? 'selected' : ''; ?>>
                                                Lunas
                                            </option>
                                        </select>
                                    </form>
                                    <?php
                                } else {
                                    echo 'Berjalan';
                                }
                            } else {
                                echo '-';
                            }
                            ?>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm <?php echo $statusClass; ?> font-medium">
                            <?php echo ucfirst($data['status_peminjaman']); ?>
                        </td>
                    </tr>
                <?php
                }
                ?>
            </tbody>
        </table>
    </div>
</div>