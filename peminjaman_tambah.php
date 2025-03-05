<div class="bg-white p-6 rounded-lg shadow-sm">
    <h1 class="text-2xl font-semibold text-gray-800 mb-6">Peminjaman Buku</h1>

    <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 mb-6">
        <div class="flex items-center">
            <i class="fas fa-exclamation-triangle mr-2"></i>
            <p>
                <span class="font-bold">Perhatian:</span> 
                Keterlambatan pengembalian buku akan dikenakan denda sebesar Rp 5.000 per hari.
            </p>
        </div>
    </div>

    <div class="max-w-3xl">
        <form method="post">
            <?php
            if(isset($_POST['submit'])) {
                $id_buku = $_POST['id_buku'];
                $id_user = $_SESSION['user']['id_user'];
                $tanggal_peminjaman = $_POST['tanggal_peminjaman'];
                $tanggal_pengembalian = $_POST['tanggal_pengembalian'];
                $status_peminjaman = 'dipinjam';

                // Cek apakah user masih meminjam buku yang sama dan belum dikembalikan
                $cek_peminjaman = mysqli_query($koneksi, 
                    "SELECT * FROM peminjaman 
                    WHERE id_user = '$id_user' 
                    AND id_buku = '$id_buku' 
                    AND status_peminjaman = 'dipinjam'"
                );

                if(mysqli_num_rows($cek_peminjaman) > 0) {
                    echo "<script>
                        alert('Anda masih meminjam buku yang sama dan belum dikembalikan!');
                        location.href = '?page=peminjaman';
                    </script>";
                } else {
                    // Check book stock first
                    $check_stock = mysqli_query($koneksi, "SELECT stok FROM buku WHERE id_buku = '$id_buku'");
                    $stock_data = mysqli_fetch_assoc($check_stock);
                    
                    if($stock_data['stok'] <= 0) {
                        echo "<script>
                            alert('Maaf, stok buku ini telah habis!');
                            location.href = '?page=peminjaman';
                        </script>";
                    } else {
                        mysqli_begin_transaction($koneksi);
                        
                        try {
                            // Query untuk menambah peminjaman
                            $query_peminjaman = mysqli_query($koneksi, 
                                "INSERT INTO peminjaman(id_buku, id_user, tanggal_peminjaman, tanggal_pengembalian, status_peminjaman) 
                                VALUES ('$id_buku', '$id_user', '$tanggal_peminjaman', '$tanggal_pengembalian', '$status_peminjaman')"
                            );
                            
                            // Query untuk mengurangi stok buku
                            $query_update_stok = mysqli_query($koneksi, 
                                "UPDATE buku SET stok = stok - 1 WHERE id_buku = '$id_buku'"
                            );
                            
                            if($query_peminjaman && $query_update_stok) {
                                mysqli_commit($koneksi);
                                echo "<script>
                                    alert('Peminjaman berhasil ditambahkan');
                                    location.href = '?page=peminjaman';
                                </script>";
                            } else {
                                throw new Exception("Gagal menambah peminjaman atau mengupdate stok");
                            }
                        } catch (Exception $e) {
                            mysqli_rollback($koneksi);
                            echo "<script>
                                alert('Peminjaman gagal ditambahkan');
                                location.href = '?page=peminjaman';
                            </script>";
                        }
                    }
                }
            }
            ?>
            
            
            <div class="space-y-6">
                <!-- Buku Selection -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-center">
                    <label class="text-sm font-medium text-gray-700">Buku</label>
                    <div class="md:col-span-3">
                        <select name="id_buku" class="w-full p-2 border-2 rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                            <option value="">-- Pilih Buku --</option>
                            <?php
                            $buk = mysqli_query($koneksi, "SELECT * FROM buku WHERE stok > 0");
                            while($buku = mysqli_fetch_array($buk)) {
                                echo '<option value="' . $buku['id_buku'] . '">' . htmlspecialchars($buku['judul']) . ' (Stok: ' . $buku['stok'] . ')</option>';
                            }
                            ?>
                        </select>
                    </div>
                </div>

                <!-- Tanggal Peminjaman -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-center">
                    <label class="text-sm font-medium text-gray-700">Tanggal Peminjaman</label>
                    <div class="md:col-span-3">
                        <input type="date" class="w-full border-2 p-2 rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" name="tanggal_peminjaman" required>
                    </div>
                </div>

                <!-- Tanggal Pengembalian -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-center">
                    <label class="text-sm font-medium text-gray-700">Tanggal Pengembalian</label>
                    <div class="md:col-span-3">
                        <input type="date" class="w-full rounded-lg border-2 p-2 border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" name="tanggal_pengembalian" required>
                    </div>
                </div>

                <!-- Status Peminjaman -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-center">
                    <label class="text-sm font-medium text-gray-700">Status Peminjaman</label>
                    <div class="md:col-span-3">
                        <select name="status_peminjaman" class="w-full rounded-lg border-2 p-2 border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="dipinjam">Dipinjam</option>
                            <option value="dikembalikan">Dikembalikan</option>
                        </select>
                    </div>
                </div>

                <!-- Buttons -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-center">
                    <div></div>
                    <div class="md:col-span-3 flex space-x-3">
                        <button type="submit" name="submit" value="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                            Simpan
                        </button>
                        <button type="reset" class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">
                            Reset
                        </button>
                        <a href="?page=peminjaman" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">
                            Kembali
                        </a>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
