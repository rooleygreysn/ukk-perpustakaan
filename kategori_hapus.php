<?php
$id = $_GET['id'];

// Cek apakah kategori masih digunakan di tabel buku
$check_query = mysqli_query($koneksi, "SELECT COUNT(*) as count FROM buku WHERE id_kategori=$id");
$result = mysqli_fetch_assoc($check_query);

if ($result['count'] > 0) {
    // Jika kategori masih digunakan, tampilkan pesan error
    ?>
    <script>
        alert('Kategori tidak dapat dihapus karena masih digunakan dalam data buku!');
        location.href = "index.php?page=kategori";
    </script>
    <?php
} else {
    // Jika kategori tidak digunakan, lakukan penghapusan
    $query = mysqli_query($koneksi, "DELETE FROM kategori WHERE id_kategori=$id");
    ?>
    <script>
        alert('Hapus data berhasil');
        location.href = "index.php?page=kategori";
    </script>
    <?php
}