<?php
if(isset($_GET['id'])) {
    $id = $_GET['id'];
    
    // Get the book ID
    $get_book = mysqli_query($koneksi, "SELECT id_buku FROM peminjaman WHERE id_peminjaman = $id");
    $book_data = mysqli_fetch_assoc($get_book);
    $id_buku = $book_data['id_buku'];
    
    // Update the status to 'dikembalikan'
    $query = mysqli_query($koneksi, "UPDATE peminjaman SET status_peminjaman = 'dikembalikan' WHERE id_peminjaman = $id");
    
    if($query) {
        // Increase the book stock
        mysqli_query($koneksi, "UPDATE buku SET stok = stok + 1 WHERE id_buku = $id_buku");
        
        $tanggal_pengembalian = date('Y-m-d');
        $query_peminjaman = mysqli_query($koneksi, "SELECT tanggal_pengembalian FROM peminjaman WHERE id_peminjaman = $id");
        $data_peminjaman = mysqli_fetch_assoc($query_peminjaman);

        $denda_per_hari = 5000;
        $return_date = strtotime($data_peminjaman['tanggal_pengembalian']);
        $actual_return = strtotime($tanggal_pengembalian);
        $days_late = 0;
        $total_denda = 0;

        if ($actual_return > $return_date) {
            $days_late = ceil(($actual_return - $return_date) / (60 * 60 * 24));
            $total_denda = $days_late * $denda_per_hari;
            
            // Insert ke tabel denda
            mysqli_query($koneksi, "INSERT INTO denda (id_peminjaman, jumlah_hari_terlambat, jumlah_denda) 
                                   VALUES ($id, $days_late, $total_denda)");
        }
        
        echo "<script>
            alert('Buku berhasil dikembalikan');
            location.href = '?page=peminjaman';
        </script>";
    } else {
        echo "<script>
            alert('Buku gagal dikembalikan');
            location.href = '?page=peminjaman';
        </script>";
    }
}
?>