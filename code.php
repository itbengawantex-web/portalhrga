<?php
session_start(); // WAJIB

// koneksi database
$conn = mysqli_connect("localhost", "root", "", "portalhrga");

if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

if (isset($_POST['simpan'])) {

    // ambil data dari form
    $tanggal         = $_POST['tanggal'];
    $nama_rek        = $_POST['nama']; // <-- SESUAI FORM
    $posisi          = $_POST['posisi'];
    $psikotes        = $_POST['psikotes'];
    $interview_hr    = $_POST['interview_hr'];
    $interview_user  = $_POST['interview_user'];
    $status          = $_POST['status'];

    $query = "INSERT INTO rekrutmen 
        (tanggal, nama_rek, posisi, psikotes, interview_hr, interview_user, status)
        VALUES (?, ?, ?, ?, ?, ?, ?)";

    $stmt = mysqli_prepare($conn, $query);

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

        // SIMPAN SESSION DULU
        $_SESSION['status'] = "Data rekrutmen berhasil disimpan";
        $_SESSION['status_type'] = "success";

        // BARU REDIRECT
        header("Location: tbhrekrutmen.php");
        exit;
    } else {
        $_SESSION['status'] = "Gagal menyimpan data rekrutmen";
        $_SESSION['status_type'] = "danger";
        header("Location: tbhrekrutmen.php");
        exit;
    }
}
?>
