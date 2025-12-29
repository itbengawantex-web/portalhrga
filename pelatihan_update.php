<?php
include('config/dbcon.php');

$id_log          = $_POST['id_log'];
$tanggal         = $_POST['tanggal'];
$nama            = $_POST['nama'];
$departemen            = $_POST['departemen'];
$judul_pelatihan = $_POST['judul_pelatihan'];
$pemateri        = $_POST['pemateri'];
$pretest            = $_POST['pretest'];
$posttest            = $_POST['posttest'];
$durasi_jam      = $_POST['durasi_jam'];

$query = "UPDATE pelatihan SET
            tanggal = '$tanggal',
            nama = '$nama',
            departemen = '$departemen',
            judul_pelatihan = '$judul_pelatihan',
            pemateri = '$pemateri',
            pretest = '$pretest',
            posttest = '$posttest',
            durasi_jam = '$durasi_jam'
          WHERE id_log = '$id_log'";

if (mysqli_query($con, $query)) {
    echo json_encode([
        'status' => 'success',
        'message' => 'Data training berhasil diperbarui'
    ]);
} else {
    echo json_encode([
        'status' => 'error',
        'message' => mysqli_error($con)
    ]);
}
