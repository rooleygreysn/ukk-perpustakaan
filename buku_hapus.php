<?php
$id = $_GET['id'];

// Ambil nama file gambar sebelum menghapus data
$query_gambar = mysqli_query($koneksi, "SELECT gambar FROM buku WHERE id_buku=$id");
$data_gambar = mysqli_fetch_array($query_gambar);

mysqli_begin_transaction($koneksi);

try {
    // Hapus ulasan terkait buku terlebih dahulu
    $query_hapus_ulasan = mysqli_query($koneksi, "DELETE FROM ulasan WHERE id_buku=$id");
    
    // Hapus peminjaman terkait buku
    $query_hapus_peminjaman = mysqli_query($koneksi, "DELETE FROM peminjaman WHERE id_buku=$id");
    
    // Baru kemudian hapus buku
    $query = mysqli_query($koneksi, "DELETE FROM buku WHERE id_buku=$id");

    if($query) {
        // Hapus file gambar jika ada
        if($data_gambar['gambar'] && file_exists('assets/uploads/' . $data_gambar['gambar'])) {
            unlink('assets/uploads/' . $data_gambar['gambar']);
        }
        
        mysqli_commit($koneksi);
        echo '<script>
            alert("Hapus data berhasil");
            location.href = "index.php?page=buku";
        </script>';
    } else {
        throw new Exception("Gagal menghapus data");
    }
} catch (Exception $e) {
    mysqli_rollback($koneksi);
    echo '<script>
        alert("Hapus data gagal: ' . $e->getMessage() . '");
        location.href = "index.php?page=buku";
    </script>';
}
?>