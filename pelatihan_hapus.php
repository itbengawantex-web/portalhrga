<?php
session_start();
include('config/dbcon.php');

if (isset($_GET['id_log'])) {
    $id_log = mysqli_real_escape_string($con, $_GET['id_log']);

    $delete = mysqli_query($con, "DELETE FROM pelatihan WHERE id_log='$id_log'");

    if ($delete) {
        $_SESSION['status'] = "Data berhasil dihapus";
    } else {
        $_SESSION['status'] = "Gagal menghapus data";
    }
}

header("Location: pelatihan.php");
exit();
