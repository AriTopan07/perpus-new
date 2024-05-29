<?php
session_start();
include "../../../../config/koneksi.php";

if ($_GET['aksi'] == "pengembalian") {

    include "Pemberitahuan.php";

    // Determine the fine based on the condition of the book
    if ($_POST['kondisiBukuSaatDikembalikan'] == "Baik") {
        $denda = "Tidak ada";
    } elseif ($_POST['kondisiBukuSaatDikembalikan'] == "Rusak") {
        $denda = "Rp 20.000";
    } elseif ($_POST['kondisiBukuSaatDikembalikan'] == "Hilang") {
        $denda = "Rp 50.000";
    }

    $judul_buku = $_POST['judulBuku'];
    $tanggal_pengembalian = $_POST['tanggalPengembalian'];
    $kondisiBukuSaatDikembalikan = $_POST['kondisiBukuSaatDikembalikan'];

    // Get the id_peminjaman based on the selected book title
    $ambil_id = mysqli_query($koneksi, "SELECT * FROM peminjaman WHERE judul_buku = '$judul_buku' AND tanggal_pengembalian = ''");
    $row = mysqli_fetch_assoc($ambil_id);

    if ($row) {
        $id_peminjaman = $row['id_peminjaman'];

        // Update the peminjaman record with the return information
        $query = "UPDATE peminjaman SET 
                    tanggal_pengembalian = '$tanggal_pengembalian', 
                    kondisi_buku_saat_dikembalikan = '$kondisiBukuSaatDikembalikan', 
                    denda = '$denda' 
                  WHERE id_peminjaman = $id_peminjaman";

        $sql = mysqli_query($koneksi, $query);

        if ($sql) {
            // Send notification to admin
            InsertPemberitahuanPengembalian();

            $_SESSION['berhasil'] = "Pengembalian buku berhasil!";
            header("location: " . $_SERVER['HTTP_REFERER']);
        } else {
            $_SESSION['gagal'] = "Pengembalian buku gagal!";
            header("location: " . $_SERVER['HTTP_REFERER']);
        }
    } else {
        $_SESSION['gagal'] = "Data peminjaman tidak ditemukan atau buku sudah dikembalikan!";
        header("location: " . $_SERVER['HTTP_REFERER']);
    }
}

function InsertPemberitahuanPengembalian()
{
    include "../../../../config/koneksi.php";

    $nama_anggota = $_SESSION['fullname'];
    $notif = addslashes("<i class='fa fa-repeat'></i> #" . $nama_anggota . " telah mengembalikan buku");
    $level = "Admin";
    $status = "Belum dibaca";

    $sql = "INSERT INTO pemberitahuan(isi_pemberitahuan, level_user, status) 
                VALUES('$notif', '$level', '$status')";
    $result = mysqli_query($koneksi, $sql);
}
