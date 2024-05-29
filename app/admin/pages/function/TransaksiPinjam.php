<?php
session_start();
include "../../../../config/koneksi.php";

if ($_GET['aksi'] == "pinjam") {

    // Validasi input form
    if (empty($_POST['judulBuku'])) {
        $_SESSION['gagal'] = "Peminjaman buku gagal, Kamu belum memilih buku yang akan dipinjam !";
        header("location: " . $_SERVER['HTTP_REFERER']);
        exit();
    }

    if (empty($_POST['kondisiBukuSaatDipinjam'])) {
        $_SESSION['gagal'] = "Peminjaman buku gagal, Kamu belum memilih kondisi buku yang akan dipinjam !";
        header("location: " . $_SERVER['HTTP_REFERER']);
        exit();
    }

    // Ambil nama peminjam dari salah satu input yang diisi
    $nama_anggota = !empty($_POST['namaPeminjam']) ? $_POST['namaPeminjam'] : $_POST['namaAnggota'];

    if (empty($nama_anggota)) {
        $_SESSION['gagal'] = "Peminjaman buku gagal, Kamu belum memasukkan atau memilih nama peminjam !";
        header("location: " . $_SERVER['HTTP_REFERER']);
        exit();
    }

    // Variabel lainnya
    $judul_buku = $_POST['judulBuku'];
    $tanggal_peminjaman = $_POST['tanggalPeminjaman'];
    $kondisi_buku_saat_dipinjam = $_POST['kondisiBukuSaatDipinjam'];

    // Cek apakah sudah pernah meminjam buku yang sama dan belum dikembalikan
    $query = mysqli_query($koneksi, "SELECT * FROM peminjaman WHERE nama_anggota = '$nama_anggota' AND judul_buku = '$judul_buku' AND tanggal_pengembalian = ''");
    $cek = mysqli_num_rows($query);

    if ($cek > 0) {
        $_SESSION['gagal'] = "Peminjaman buku gagal, $nama_anggota sudah meminjam buku ini sebelumnya dan belum dikembalikan!";
        header("location: " . $_SERVER['HTTP_REFERER']);
        exit();
    }

    // Insert data ke tabel peminjaman hanya jika belum pernah meminjam buku yang sama
    $sql = "INSERT INTO peminjaman (nama_anggota, judul_buku, tanggal_peminjaman, kondisi_buku_saat_dipinjam) 
            VALUES ('$nama_anggota', '$judul_buku', '$tanggal_peminjaman', '$kondisi_buku_saat_dipinjam')";

    // Tambahkan pengecekan apakah query INSERT berhasil dijalankan atau tidak
    if (mysqli_query($koneksi, $sql)) {
        // Send notification to admin
        InsertPemberitahuanPeminjaman($koneksi);
        $_SESSION['berhasil'] = "Peminjaman buku berhasil!";
        header("location: " . $_SERVER['HTTP_REFERER']);
    } else {
        $_SESSION['gagal'] = "Terjadi masalah dalam pengiriman data peminjaman!";
        header("location: " . $_SERVER['HTTP_REFERER']);
    }
}


function InsertPemberitahuanPeminjaman($koneksi)
{
    $nama_anggota = !empty($_POST['namaPeminjam']) ? $_POST['namaPeminjam'] : $_POST['namaAnggota'];
    $notif = addslashes("<i class='fa fa-exchange'></i> #" . $nama_anggota . " Telah meminjam Buku");
    $level = "Admin";
    $status = "Belum dibaca";

    $sql = "INSERT INTO pemberitahuan (isi_pemberitahuan, level_user, status) 
                VALUES ('$notif', '$level', '$status')";
    mysqli_query($koneksi, $sql); // Eksekusi query

    // Periksa apakah query berhasil dieksekusi
    if (mysqli_affected_rows($koneksi) > 0) {
        return true; // Berhasil
    } else {
        return false; // Gagal
    }
}



function UpdateDataPeminjaman()
{
    include "../../../../config/koneksi.php";

    $nama_lama = $_SESSION['fullname'];
    $nama_anggota = $_POST['Fullname'];

    // Mencari nama dalam database berdasarkan session nama lengkap
    $query1 = mysqli_query($koneksi, "SELECT * FROM user WHERE fullname = '$nama_lama'");
    $row = mysqli_fetch_assoc($query1);

    // membuat variable dari hasil query1
    $nama_lama = $row['fullname'];

    // Fungsi update nama anggota pada table peminjaman
    $query = "UPDATE peminjaman SET nama_anggota = '$nama_anggota'";
    $query .= "WHERE nama_anggota = '$nama_lama'";

    $sql = mysqli_query($koneksi, $query);
}
