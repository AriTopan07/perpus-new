<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1 style="font-family: 'Quicksand', sans-serif; font-weight: bold;">
            Peminjaman Buku
            <small>
                <script type='text/javascript'>
                    var months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
                    var myDays = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jum&#39;at', 'Sabtu'];
                    var date = new Date();
                    var day = date.getDate();
                    var month = date.getMonth();
                    var thisDay = date.getDay(),
                        thisDay = myDays[thisDay];
                    var yy = date.getYear();
                    var year = (yy < 1000) ? yy + 1900 : yy;
                    document.write(thisDay + ', ' + day + ' ' + months[month] + ' ' + year);
                    //
                </script>
            </small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="dashboard"><i class="fa fa-dashboard"></i> Dashboard</a></li>
            <li class="active">Peminjaman Buku</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="nav-tabs-custom">
                    <ul class="nav nav-tabs">
                        <li class="active"><a href="#tgl-pinjam" data-toggle="tab">Riwayat Peminjaman Buku</a></li>
                    </ul>
                    <div class="tab-content">
                        <!-- Font Awesome Icons -->
                        <div class="tab-pane active" id="tgl-pinjam">
                            <section id="new">
                                <?php
                                include "../../config/koneksi.php";

                                $fullname = $_SESSION['fullname'];
                                $sql = mysqli_query($koneksi, "SELECT * FROM peminjaman WHERE nama_anggota = '$fullname' AND tanggal_pengembalian = ''");
                                $hasil = mysqli_num_rows($sql);
                                ?>

                                <?php
                                if ($hasil > 0) {
                                    $sql3 = mysqli_query($koneksi, "SELECT * FROM peminjaman WHERE nama_anggota = '$fullname' AND tanggal_pengembalian = ''");
                                    $row = mysqli_num_rows($sql3);
                                    echo "
                                    <div class='alert alert-danger small'>
                                        Kamu saat ini telah meminjam sebanyak " . $hasil . " Buku
                                    </div>";
                                } else {
                                    //
                                }
                                ?>
                            </section>
                            <table class="table table-bordered" id="example1">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Anggota</th>
                                        <th>Judul Buku</th>
                                        <th>Tanggal Peminjaman</th>
                                        <th>Tanggal Pengembalian</th>
                                        <th>Kondisi Buku Saat Dipinjam</th>
                                        <th>Kondisi Buku Saat Dikembalikan</th>
                                        <th>Denda</th>
                                    </tr>
                                </thead>
                                <?php
                                include "../../config/koneksi.php";

                                $no = 1;
                                $fullname = $_SESSION['fullname'];
                                $query = mysqli_query($koneksi, "SELECT * FROM peminjaman WHERE nama_anggota = '$fullname'");
                                while ($row = mysqli_fetch_assoc($query)) {
                                ?>
                                    <tbody>
                                        <tr>
                                            <td><?= $no++; ?></td>
                                            <td><?= $row['nama_anggota']; ?></td>
                                            <td><?= $row['judul_buku']; ?></td>
                                            <td><?= $row['tanggal_peminjaman']; ?></td>
                                            <td><?= $row['tanggal_pengembalian']; ?></td>
                                            <td><?= $row['kondisi_buku_saat_dipinjam']; ?></td>
                                            <td><?= $row['kondisi_buku_saat_dikembalikan']; ?></td>
                                            <td><?= $row['denda']; ?></td>
                                        </tr>
                                    </tbody>
                                <?php
                                }
                                ?>
                            </table>
                        </div>

                    </div>
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
    </section>
    <!-- /.content -->
</div>
<!-- jQuery 3 -->
<script src="../../assets/bower_components/jquery/dist/jquery.min.js"></script>
<script src="../../assets/dist/js/sweetalert.min.js"></script>
<!-- Pesan Berhasil Edit -->
<script>
    <?php
    if (isset($_SESSION['berhasil']) && $_SESSION['berhasil'] <> '') {
        echo "swal({
            icon: 'success',
            title: 'Berhasil',
            text: '$_SESSION[berhasil]'
        })";
    }
    $_SESSION['berhasil'] = '';
    ?>
</script>
<!-- Pesan Gagal Edit -->
<script>
    <?php
    if (isset($_SESSION['gagal']) && $_SESSION['gagal'] <> '') {
        echo "swal({
                icon: 'error',
                title: 'Gagal',
                text: '$_SESSION[gagal]'
              })";
    }
    $_SESSION['gagal'] = '';
    ?>
</script>
<!-- Swal Hapus Data -->
<script>
    $('.btn-del').on('click', function(e) {
        e.preventDefault();
        const href = $(this).attr('href')

        swal({
                icon: 'warning',
                title: 'Peringatan',
                text: 'Apakah anda yakin ingin menghapus data administrator ini ?',
                buttons: true,
                dangerMode: true,
                buttons: ['Tidak, Batalkan !', 'Iya, Hapus']
            })
            .then((willDelete) => {
                if (willDelete) {
                    document.location.href = href;
                } else {
                    swal({
                        icon: 'error',
                        title: 'Dibatalkan',
                        text: 'Data administrator tersebut tetap aman !'
                    })
                }
            });
    })
</script>