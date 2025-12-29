<?php
session_start();
include('config/dbcon.php'); 



/* =======================
   SIMPAN REKRUTMEN
======================= */
if (isset($_POST['simpan'])) {

    $tanggal        = $_POST['tanggal'];
    $nama_rek       = $_POST['nama'];
    $posisi         = $_POST['posisi'];
    $psikotes       = $_POST['psikotes'];
    $interview_hr   = $_POST['interview_hr'];
    $interview_user = $_POST['interview_user'];
    $status         = $_POST['status'];

    $query = "INSERT INTO rekrutmen 
        (tanggal, nama_rek, posisi, psikotes, interview_hr, interview_user, status)
        VALUES (?, ?, ?, ?, ?, ?, ?)";

    $stmt = mysqli_prepare($con, $query);
    mysqli_stmt_bind_param(
        $stmt,
        "sssssss",
        $tanggal,
        $nama_rek,
        $posisi,
        $psikotes,
        $interview_hr,
        $interview_user,
        $status
    );

    if (mysqli_stmt_execute($stmt)) {
        $_SESSION['status'] = "Data rekrutmen berhasil disimpan";
        $_SESSION['status_type'] = "success";
    } else {
        $_SESSION['status'] = "Gagal menyimpan data rekrutmen";
        $_SESSION['status_type'] = "danger";
    }

    header("Location: tbhrekrutmen.php");
    exit;
}

if (isset($_POST['simpan_training'])) {

    $tanggal         = $_POST['tanggal'];
    $nama            = $_POST['nama'];
    $judul_pelatihan = $_POST['judul_Training'];
    $pemateri        = $_POST['Pemateri'];
    $durasi_jam      = $_POST['durasi_jam'];

    $query = "INSERT INTO pelatihan 
        (tanggal, nama, judul_pelatihan, pemateri, durasi_jam)
        VALUES (?, ?, ?, ?, ?)";

    $stmt = mysqli_prepare($con, $query);
    mysqli_stmt_bind_param(
        $stmt,
        "ssssi",
        $tanggal,
        $nama,
        $judul_pelatihan,
        $pemateri,
        $durasi_jam
    );

    if (mysqli_stmt_execute($stmt)) {
        $_SESSION['status'] = "Data pelatihan berhasil disimpan";
        $_SESSION['status_type'] = "success";
    } else {
        $_SESSION['status'] = "Gagal menyimpan data pelatihan";
        $_SESSION['status_type'] = "danger";
    }

    header("Location: tbhpelatihan.php");
    exit;
}
