<!DOCTYPE html>
<html>
<head>
    <title>Cetak Laporan</title>
    
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid black;
            padding: 8px;
            text-align: left;
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        .status-lunas {
            color: green;
            font-weight: bold;
        }
        .status-belum-lunas {
            color: red;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <h2>LAPORAN PEMINJAMAN BUKU</h2>
    <table>
        <tr>
            <th>No</th>
            <th>User</th>
            <th>Buku</th>
            <th>Tanggal Peminjaman</th>
            <th>Tanggal Pengembalian</th>
            <th>Status Peminjaman</th>
            <th>Keterlambatan</th>
            <th>Denda</th>
            <th>Status Denda</th>
        </tr>
        <?php
        include "koneksi.php";

        $where_clause = "";
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

        $i = 1;
        while($data = mysqli_fetch_array($query)){
            $keterlambatan = $data['status_peminjaman'] == 'dikembalikan' ? 
                ($data['jumlah_hari_terlambat'] ?? 0) : 
                $data['hari_terlambat'];
            
            $denda = $data['status_peminjaman'] == 'dikembalikan' ? 
                ($data['jumlah_denda'] ?? 0) : 
                $data['denda_berjalan'];

            // Tentukan status dan class untuk denda
            $status_denda = '-';
            $status_class = '';
            if ($denda > 0) {
                if ($data['status_peminjaman'] == 'dikembalikan') {
                    $status_denda = $data['status_pembayaran'] == 'lunas' ? 'Lunas' : 'Belum Lunas';
                    $status_class = $data['status_pembayaran'] == 'lunas' ? 'status-lunas' : 'status-belum-lunas';
                } else {
                    $status_denda = 'Berjalan';
                }
            }
            ?>
            <tr>
                <td><?php echo $i++; ?></td>
                <td><?php echo htmlspecialchars($data['nama']); ?></td> 
                <td><?php echo htmlspecialchars($data['judul']); ?></td> 
                <td><?php echo date('d/m/Y', strtotime($data['tanggal_peminjaman'])); ?></td> 
                <td><?php echo date('d/m/Y', strtotime($data['tanggal_pengembalian'])); ?></td> 
                <td><?php echo $data['status_peminjaman']; ?></td>
                <td><?php echo $keterlambatan > 0 ? $keterlambatan . ' hari' : '-'; ?></td>
                <td><?php echo $denda > 0 ? 'Rp ' . number_format($denda, 0, ',', '.') : '-'; ?></td>
                <td class="<?php echo $status_class; ?>"><?php echo $status_denda; ?></td>
            </tr>
        <?php
        }
        ?>
    </table>
    <script>
        window.print();
        setTimeout(function() {
            window.close();
        }, 100);
    </script>
</body>
</html>