<?php
if (!isset($_SESSION['user']['id_user'])) {
    echo '<script>alert("Anda belum login!"); window.location.href="login.php";</script>';
    exit;
}

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id == 0) {
    echo '<script>alert("ID tidak valid."); window.location.href="?page=peminjaman";</script>';
    exit;
}

if (isset($_POST['submit'])) {
    $id_buku = $_POST['id_buku'];
    $id_user = $_SESSION['user']['id_user'];
    $tanggal_peminjaman = $_POST['tanggal_peminjaman'];
    $tanggal_pengembalian = $_POST['tanggal_pengembalian'];
    $status_peminjaman = $_POST['status_peminjaman'];

    // Ambil status peminjaman sebelumnya
    $query_status = mysqli_query($koneksi, "SELECT status_peminjaman FROM peminjaman WHERE id_peminjaman = $id");
    $data_status = mysqli_fetch_assoc($query_status);
    $status_sebelumnya = $data_status['status_peminjaman'];

    mysqli_begin_transaction($koneksi);

    try {
        // Update peminjaman
        $stmt = mysqli_prepare($koneksi, "UPDATE peminjaman SET id_buku=?, tanggal_peminjaman=?, tanggal_pengembalian=?, status_peminjaman=? WHERE id_peminjaman=?");
        mysqli_stmt_bind_param($stmt, "isssi", $id_buku, $tanggal_peminjaman, $tanggal_pengembalian, $status_peminjaman, $id);
        $query = mysqli_stmt_execute($stmt);

        // Jika status berubah menjadi dikembalikan
        if ($status_sebelumnya != 'dikembalikan' && $status_peminjaman == 'dikembalikan') {
            // Update stok buku (tambah 1)
            $query_stok = mysqli_query($koneksi, "UPDATE buku SET stok = stok + 1 WHERE id_buku = $id_buku");
            
            if (!$query_stok) {
                throw new Exception("Gagal mengupdate stok buku");
            }

            // Hitung denda jika ada
            $tanggal_pengembalian_aktual = date('Y-m-d');
            $return_date = strtotime($tanggal_pengembalian);
            $actual_return = strtotime($tanggal_pengembalian_aktual);
            
            if ($actual_return > $return_date) {
                $days_late = ceil(($actual_return - $return_date) / (60 * 60 * 24));
                $total_denda = $days_late * 5000;
                
                // Insert ke tabel denda
                $query_denda = mysqli_query($koneksi, 
                    "INSERT INTO denda (id_peminjaman, jumlah_hari_terlambat, jumlah_denda) 
                     VALUES ($id, $days_late, $total_denda)"
                );
                
                if (!$query_denda) {
                    throw new Exception("Gagal mencatat denda");
                }
            }
        }

        mysqli_commit($koneksi);
        echo '<script>alert("Ubah data berhasil."); window.location.href="?page=peminjaman";</script>';
    } catch (Exception $e) {
        mysqli_rollback($koneksi);
        echo '<script>alert("Ubah data gagal: ' . $e->getMessage() . '");</script>';
    }
}

$query = mysqli_query($koneksi, "SELECT * FROM peminjaman WHERE id_peminjaman=$id");
$data = mysqli_fetch_array($query);
?>

<div class="bg-white p-6 rounded-lg shadow-sm">
    <h1 class="text-2xl font-semibold text-gray-800 mb-6">Ubah Peminjaman Buku</h1>
    
    <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 mb-6">
        <div class="flex items-center">
            <i class="fas fa-exclamation-triangle mr-2"></i>
            <p>
                <span class="font-bold">Perhatian:</span> 
                Keterlambatan pengembalian buku akan dikenakan denda sebesar Rp 5.000 per hari.
            </p>
        </div>
    </div>
    
    <form method="post" class="space-y-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-center">
            <label class="text-sm font-medium text-gray-700">Buku</label>
            <div class="md:col-span-2">
                <?php if($_SESSION['user']['level'] != 'peminjam') { ?>
                    <select name="id_buku" class="w-full border-2 p-2 rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                        <option value="">-- Pilih Buku --</option>
                        <?php
                        $buk = mysqli_query($koneksi, "SELECT * FROM buku");
                        while ($buku = mysqli_fetch_array($buk)) {
                            $selected = ($buku['id_buku'] == $data['id_buku']) ? 'selected' : '';
                            echo '<option value="' . $buku['id_buku'] . '" ' . $selected . '>' . htmlspecialchars($buku['judul']) . '</option>';
                        }
                        ?>
                    </select>
                <?php } else { 
                    $buku_query = mysqli_query($koneksi, "SELECT judul FROM buku WHERE id_buku = " . $data['id_buku']);
                    $buku_data = mysqli_fetch_assoc($buku_query);
                    ?>
                    <input type="text" class="w-full border-2 p-2 rounded-lg border-gray-300 bg-gray-100" 
                           value="<?php echo htmlspecialchars($buku_data['judul']); ?>" readonly>
                    <input type="hidden" name="id_buku" value="<?php echo $data['id_buku']; ?>">
                <?php } ?>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-center">
            <label class="text-sm font-medium text-gray-700">Tanggal Peminjaman</label>
            <div class="md:col-span-2">
                <?php if($_SESSION['user']['level'] != 'peminjam') { ?>
                    <input type="date" class="w-full border-2 p-2 rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" 
                        value="<?php echo $data['tanggal_peminjaman']; ?>" name="tanggal_peminjaman">
                <?php } else { ?>
                    <input type="text" class="w-full border-2 p-2 rounded-lg border-gray-300 bg-gray-100" 
                        value="<?php echo date('d M Y', strtotime($data['tanggal_peminjaman'])); ?>" readonly>
                    <input type="hidden" name="tanggal_peminjaman" value="<?php echo $data['tanggal_peminjaman']; ?>">
                <?php } ?>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-center">
            <label class="text-sm font-medium text-gray-700">Tanggal Pengembalian</label>
            <div class="md:col-span-2">
                <?php if($_SESSION['user']['level'] != 'peminjam') { ?>
                    <input type="date" class="w-full border-2 p-2 rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" 
                        value="<?php echo $data['tanggal_pengembalian']; ?>" name="tanggal_pengembalian">
                <?php } else { ?>
                    <input type="text" class="w-full border-2 p-2 rounded-lg border-gray-300 bg-gray-100" 
                        value="<?php echo date('d M Y', strtotime($data['tanggal_pengembalian'])); ?>" readonly>
                    <input type="hidden" name="tanggal_pengembalian" value="<?php echo $data['tanggal_pengembalian']; ?>">
                <?php } ?>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-center">
            <label class="text-sm font-medium text-gray-700">Status Peminjaman</label>
            <div class="md:col-span-2">
                <select name="status_peminjaman" class="w-full rounded-lg border-2 p-2 border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <option value="dipinjam" <?php if($data['status_peminjaman'] == 'dipinjam') echo 'selected'; ?>>Dipinjam</option>
                    <option value="dikembalikan" <?php if($data['status_peminjaman'] == 'dikembalikan') echo 'selected'; ?>>Dikembalikan</option>
                </select>
            </div>
        </div>

        <div class="flex justify-end space-x-4 mt-6">
            <button type="submit" name="submit" value="submit"
                    class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                Simpan
            </button>
            <a href="?page=peminjaman"
               class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">
                Kembali
            </a>
        </div>
    </form>
</div>
