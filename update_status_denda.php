<?php
session_start();
include "koneksi.php";

// Cek apakah user adalah admin atau petugas
if (!isset($_SESSION['user']) || ($_SESSION['user']['level'] != 'admin' && $_SESSION['user']['level'] != 'petugas')) {
    echo '<script>alert("Anda tidak memiliki akses!"); window.location.href="index.php";</script>';
    exit;
}

if (isset($_POST['id_peminjaman']) && isset($_POST['status_pembayaran'])) {
    $id_peminjaman = $_POST['id_peminjaman'];
    $status_pembayaran = $_POST['status_pembayaran'];
    
    // Update status pembayaran di tabel denda
    $query = mysqli_query($koneksi, 
        "UPDATE denda 
         SET status_pembayaran = '$status_pembayaran',
             tanggal_pembayaran = " . ($status_pembayaran == 'lunas' ? 'CURRENT_DATE' : 'NULL') . "
         WHERE id_peminjaman = $id_peminjaman"
    );
    
    if ($query) {
        echo '<script>
            alert("Status denda berhasil diupdate!");
            window.location.href="index.php?page=laporan";
        </script>';
    } else {
        echo '<script>
            alert("Gagal mengupdate status denda!");
            window.location.href="index.php?page=laporan";
        </script>';
    }
} else {
    header('Location: index.php?page=laporan');
}
?> 