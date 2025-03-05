<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$koneksi = mysqli_connect("localhost", "root", "", "perpus_deka");
if (!$koneksi) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
?>